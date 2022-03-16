package org.quartz.core;

import java.io.InputStream;
import java.lang.management.ManagementFactory;
import java.rmi.NotBoundException;
import java.rmi.RemoteException;
import java.rmi.registry.LocateRegistry;
import java.rmi.registry.Registry;
import java.rmi.server.UnicastRemoteObject;
import java.util.ArrayList;
import java.util.Collection;
import java.util.Collections;
import java.util.Date;
import java.util.HashMap;
import java.util.Iterator;
import java.util.LinkedList;
import java.util.List;
import java.util.Map;
import java.util.Properties;
import java.util.Random;
import java.util.Set;
import java.util.Timer;
import java.util.Map.Entry;
import javax.management.MBeanServer;
import javax.management.ObjectName;
import org.quartz.Calendar;
import org.quartz.InterruptableJob;
import org.quartz.Job;
import org.quartz.JobDataMap;
import org.quartz.JobDetail;
import org.quartz.JobExecutionContext;
import org.quartz.JobExecutionException;
import org.quartz.JobKey;
import org.quartz.JobListener;
import org.quartz.ListenerManager;
import org.quartz.Matcher;
import org.quartz.ObjectAlreadyExistsException;
import org.quartz.SchedulerContext;
import org.quartz.SchedulerException;
import org.quartz.SchedulerListener;
import org.quartz.SchedulerMetaData;
import org.quartz.Trigger;
import org.quartz.TriggerBuilder;
import org.quartz.TriggerKey;
import org.quartz.TriggerListener;
import org.quartz.UnableToInterruptJobException;
import org.quartz.core.jmx.QuartzSchedulerMBean;
import org.quartz.impl.SchedulerRepository;
import org.quartz.impl.matchers.GroupMatcher;
import org.quartz.simpl.PropertySettingJobFactory;
import org.quartz.spi.JobFactory;
import org.quartz.spi.OperableTrigger;
import org.quartz.spi.SchedulerPlugin;
import org.quartz.spi.SchedulerSignaler;
import org.quartz.spi.ThreadExecutor;
import org.quartz.utils.UpdateChecker;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

public class QuartzScheduler implements RemotableQuartzScheduler {
   private static String VERSION_MAJOR = "UNKNOWN";
   private static String VERSION_MINOR = "UNKNOWN";
   private static String VERSION_ITERATION = "UNKNOWN";
   private QuartzSchedulerResources resources;
   private QuartzSchedulerThread schedThread;
   private ThreadGroup threadGroup;
   private SchedulerContext context = new SchedulerContext();
   private ListenerManager listenerManager = new ListenerManagerImpl();
   private HashMap<String, JobListener> internalJobListeners = new HashMap(10);
   private HashMap<String, TriggerListener> internalTriggerListeners = new HashMap(10);
   private ArrayList<SchedulerListener> internalSchedulerListeners = new ArrayList(10);
   private JobFactory jobFactory = new PropertySettingJobFactory();
   ExecutingJobsManager jobMgr = null;
   ErrorLogger errLogger = null;
   private SchedulerSignaler signaler;
   private Random random = new Random();
   private ArrayList<Object> holdToPreventGC = new ArrayList(5);
   private boolean signalOnSchedulingChange = true;
   private volatile boolean closed = false;
   private volatile boolean shuttingDown = false;
   private boolean boundRemotely = false;
   private QuartzSchedulerMBean jmxBean = null;
   private Date initialStart = null;
   private final Timer updateTimer;
   private final Logger log = LoggerFactory.getLogger(this.getClass());

   public QuartzScheduler(QuartzSchedulerResources resources, long idleWaitTime, @Deprecated long dbRetryInterval) throws SchedulerException {
      this.resources = resources;
      if (resources.getJobStore() instanceof JobListener) {
         this.addInternalJobListener((JobListener)resources.getJobStore());
      }

      this.schedThread = new QuartzSchedulerThread(this, resources);
      ThreadExecutor schedThreadExecutor = resources.getThreadExecutor();
      schedThreadExecutor.execute(this.schedThread);
      if (idleWaitTime > 0L) {
         this.schedThread.setIdleWaitTime(idleWaitTime);
      }

      this.jobMgr = new ExecutingJobsManager();
      this.addInternalJobListener(this.jobMgr);
      this.errLogger = new ErrorLogger();
      this.addInternalSchedulerListener(this.errLogger);
      this.signaler = new SchedulerSignalerImpl(this, this.schedThread);
      if (this.shouldRunUpdateCheck()) {
         this.updateTimer = this.scheduleUpdateCheck();
      } else {
         this.updateTimer = null;
      }

      this.getLog().info("Quartz Scheduler v." + this.getVersion() + " created.");
   }

   public void initialize() throws SchedulerException {
      try {
         this.bind();
      } catch (Exception var3) {
         throw new SchedulerException("Unable to bind scheduler to RMI Registry.", var3);
      }

      if (this.resources.getJMXExport()) {
         try {
            this.registerJMX();
         } catch (Exception var2) {
            throw new SchedulerException("Unable to register scheduler with MBeanServer.", var2);
         }
      }

      this.getLog().info("Scheduler meta-data: " + (new SchedulerMetaData(this.getSchedulerName(), this.getSchedulerInstanceId(), this.getClass(), this.boundRemotely, this.runningSince() != null, this.isInStandbyMode(), this.isShutdown(), this.runningSince(), this.numJobsExecuted(), this.getJobStoreClass(), this.supportsPersistence(), this.isClustered(), this.getThreadPoolClass(), this.getThreadPoolSize(), this.getVersion())).toString());
   }

   public String getVersion() {
      return getVersionMajor() + "." + getVersionMinor() + "." + getVersionIteration();
   }

   public static String getVersionMajor() {
      return VERSION_MAJOR;
   }

   private boolean shouldRunUpdateCheck() {
      return this.resources.isRunUpdateCheck() && !Boolean.getBoolean("org.quartz.scheduler.skipUpdateCheck") && !Boolean.getBoolean("org.terracotta.quartz.skipUpdateCheck");
   }

   public static String getVersionMinor() {
      return VERSION_MINOR;
   }

   public static String getVersionIteration() {
      return VERSION_ITERATION;
   }

   public SchedulerSignaler getSchedulerSignaler() {
      return this.signaler;
   }

   public Logger getLog() {
      return this.log;
   }

   private Timer scheduleUpdateCheck() {
      Timer rval = new Timer(true);
      rval.scheduleAtFixedRate(new UpdateChecker(), 1000L, 604800000L);
      return rval;
   }

   private void registerJMX() throws Exception {
      String jmxObjectName = this.resources.getJMXObjectName();
      MBeanServer mbs = ManagementFactory.getPlatformMBeanServer();
      this.jmxBean = new QuartzSchedulerMBeanImpl(this);
      mbs.registerMBean(this.jmxBean, new ObjectName(jmxObjectName));
   }

   private void unregisterJMX() throws Exception {
      String jmxObjectName = this.resources.getJMXObjectName();
      MBeanServer mbs = ManagementFactory.getPlatformMBeanServer();
      mbs.unregisterMBean(new ObjectName(jmxObjectName));
      this.jmxBean.setSampledStatisticsEnabled(false);
      this.getLog().info("Scheduler unregistered from name '" + jmxObjectName + "' in the local MBeanServer.");
   }

   private void bind() throws RemoteException {
      String host = this.resources.getRMIRegistryHost();
      if (host != null && host.length() != 0) {
         RemotableQuartzScheduler exportable = null;
         if (this.resources.getRMIServerPort() > 0) {
            exportable = (RemotableQuartzScheduler)UnicastRemoteObject.exportObject(this, this.resources.getRMIServerPort());
         } else {
            exportable = (RemotableQuartzScheduler)UnicastRemoteObject.exportObject(this);
         }

         Registry registry = null;
         if (this.resources.getRMICreateRegistryStrategy().equals("as_needed")) {
            try {
               registry = LocateRegistry.getRegistry(this.resources.getRMIRegistryPort());
               registry.list();
            } catch (Exception var6) {
               registry = LocateRegistry.createRegistry(this.resources.getRMIRegistryPort());
            }
         } else if (this.resources.getRMICreateRegistryStrategy().equals("always")) {
            try {
               registry = LocateRegistry.createRegistry(this.resources.getRMIRegistryPort());
            } catch (Exception var5) {
               registry = LocateRegistry.getRegistry(this.resources.getRMIRegistryPort());
            }
         } else {
            registry = LocateRegistry.getRegistry(this.resources.getRMIRegistryHost(), this.resources.getRMIRegistryPort());
         }

         String bindName = this.resources.getRMIBindName();
         registry.rebind(bindName, exportable);
         this.boundRemotely = true;
         this.getLog().info("Scheduler bound to RMI registry under name '" + bindName + "'");
      }
   }

   private void unBind() throws RemoteException {
      String host = this.resources.getRMIRegistryHost();
      if (host != null && host.length() != 0) {
         Registry registry = LocateRegistry.getRegistry(this.resources.getRMIRegistryHost(), this.resources.getRMIRegistryPort());
         String bindName = this.resources.getRMIBindName();

         try {
            registry.unbind(bindName);
            UnicastRemoteObject.unexportObject(this, true);
         } catch (NotBoundException var5) {
         }

         this.getLog().info("Scheduler un-bound from name '" + bindName + "' in RMI registry");
      }
   }

   public String getSchedulerName() {
      return this.resources.getName();
   }

   public String getSchedulerInstanceId() {
      return this.resources.getInstanceId();
   }

   public ThreadGroup getSchedulerThreadGroup() {
      if (this.threadGroup == null) {
         this.threadGroup = new ThreadGroup("QuartzScheduler:" + this.getSchedulerName());
         if (this.resources.getMakeSchedulerThreadDaemon()) {
            this.threadGroup.setDaemon(true);
         }
      }

      return this.threadGroup;
   }

   public void addNoGCObject(Object obj) {
      this.holdToPreventGC.add(obj);
   }

   public boolean removeNoGCObject(Object obj) {
      return this.holdToPreventGC.remove(obj);
   }

   public SchedulerContext getSchedulerContext() throws SchedulerException {
      return this.context;
   }

   public boolean isSignalOnSchedulingChange() {
      return this.signalOnSchedulingChange;
   }

   public void setSignalOnSchedulingChange(boolean signalOnSchedulingChange) {
      this.signalOnSchedulingChange = signalOnSchedulingChange;
   }

   public void start() throws SchedulerException {
      if (!this.shuttingDown && !this.closed) {
         this.notifySchedulerListenersStarting();
         if (this.initialStart == null) {
            this.initialStart = new Date();
            this.resources.getJobStore().schedulerStarted();
            this.startPlugins();
         } else {
            this.resources.getJobStore().schedulerResumed();
         }

         this.schedThread.togglePause(false);
         this.getLog().info("Scheduler " + this.resources.getUniqueIdentifier() + " started.");
         this.notifySchedulerListenersStarted();
      } else {
         throw new SchedulerException("The Scheduler cannot be restarted after shutdown() has been called.");
      }
   }

   public void startDelayed(final int seconds) throws SchedulerException {
      if (!this.shuttingDown && !this.closed) {
         Thread t = new Thread(new Runnable() {
            public void run() {
               try {
                  Thread.sleep((long)seconds * 1000L);
               } catch (InterruptedException var3) {
               }

               try {
                  QuartzScheduler.this.start();
               } catch (SchedulerException var2) {
                  QuartzScheduler.this.getLog().error("Unable to start secheduler after startup delay.", var2);
               }

            }
         });
         t.start();
      } else {
         throw new SchedulerException("The Scheduler cannot be restarted after shutdown() has been called.");
      }
   }

   public void standby() {
      this.resources.getJobStore().schedulerPaused();
      this.schedThread.togglePause(true);
      this.getLog().info("Scheduler " + this.resources.getUniqueIdentifier() + " paused.");
      this.notifySchedulerListenersInStandbyMode();
   }

   public boolean isInStandbyMode() {
      return this.schedThread.isPaused();
   }

   public Date runningSince() {
      return this.initialStart == null ? null : new Date(this.initialStart.getTime());
   }

   public int numJobsExecuted() {
      return this.jobMgr.getNumJobsFired();
   }

   public Class<?> getJobStoreClass() {
      return this.resources.getJobStore().getClass();
   }

   public boolean supportsPersistence() {
      return this.resources.getJobStore().supportsPersistence();
   }

   public boolean isClustered() {
      return this.resources.getJobStore().isClustered();
   }

   public Class<?> getThreadPoolClass() {
      return this.resources.getThreadPool().getClass();
   }

   public int getThreadPoolSize() {
      return this.resources.getThreadPool().getPoolSize();
   }

   public void shutdown() {
      this.shutdown(false);
   }

   public void shutdown(boolean waitForJobsToComplete) {
      if (!this.shuttingDown && !this.closed) {
         this.shuttingDown = true;
         this.getLog().info("Scheduler " + this.resources.getUniqueIdentifier() + " shutting down.");
         this.standby();
         this.schedThread.halt(waitForJobsToComplete);
         this.notifySchedulerListenersShuttingdown();
         if (this.resources.isInterruptJobsOnShutdown() && !waitForJobsToComplete || this.resources.isInterruptJobsOnShutdownWithWait() && waitForJobsToComplete) {
            List<JobExecutionContext> jobs = this.getCurrentlyExecutingJobs();
            Iterator i$ = jobs.iterator();

            while(i$.hasNext()) {
               JobExecutionContext job = (JobExecutionContext)i$.next();
               if (job.getJobInstance() instanceof InterruptableJob) {
                  try {
                     ((InterruptableJob)job.getJobInstance()).interrupt();
                  } catch (Throwable var8) {
                     this.getLog().warn("Encountered error when interrupting job {} during shutdown: {}", job.getJobDetail().getKey(), var8);
                  }
               }
            }
         }

         this.resources.getThreadPool().shutdown(waitForJobsToComplete);
         this.closed = true;
         if (this.resources.getJMXExport()) {
            try {
               this.unregisterJMX();
            } catch (Exception var7) {
            }
         }

         if (this.boundRemotely) {
            try {
               this.unBind();
            } catch (RemoteException var6) {
            }
         }

         this.shutdownPlugins();
         this.resources.getJobStore().shutdown();
         this.notifySchedulerListenersShutdown();
         SchedulerRepository.getInstance().remove(this.resources.getName());
         this.holdToPreventGC.clear();
         if (this.updateTimer != null) {
            this.updateTimer.cancel();
         }

         this.getLog().info("Scheduler " + this.resources.getUniqueIdentifier() + " shutdown complete.");
      }
   }

   public boolean isShutdown() {
      return this.closed;
   }

   public boolean isShuttingDown() {
      return this.shuttingDown;
   }

   public boolean isStarted() {
      return !this.shuttingDown && !this.closed && !this.isInStandbyMode() && this.initialStart != null;
   }

   public void validateState() throws SchedulerException {
      if (this.isShutdown()) {
         throw new SchedulerException("The Scheduler has been shutdown.");
      }
   }

   public List<JobExecutionContext> getCurrentlyExecutingJobs() {
      return this.jobMgr.getExecutingJobs();
   }

   public Date scheduleJob(JobDetail jobDetail, Trigger trigger) throws SchedulerException {
      this.validateState();
      if (jobDetail == null) {
         throw new SchedulerException("JobDetail cannot be null");
      } else if (trigger == null) {
         throw new SchedulerException("Trigger cannot be null");
      } else if (jobDetail.getKey() == null) {
         throw new SchedulerException("Job's key cannot be null");
      } else if (jobDetail.getJobClass() == null) {
         throw new SchedulerException("Job's class cannot be null");
      } else {
         OperableTrigger trig = (OperableTrigger)trigger;
         if (trigger.getJobKey() == null) {
            trig.setJobKey(jobDetail.getKey());
         } else if (!trigger.getJobKey().equals(jobDetail.getKey())) {
            throw new SchedulerException("Trigger does not reference given job!");
         }

         trig.validate();
         Calendar cal = null;
         if (trigger.getCalendarName() != null) {
            cal = this.resources.getJobStore().retrieveCalendar(trigger.getCalendarName());
         }

         Date ft = trig.computeFirstFireTime(cal);
         if (ft == null) {
            throw new SchedulerException("Based on configured schedule, the given trigger '" + trigger.getKey() + "' will never fire.");
         } else {
            this.resources.getJobStore().storeJobAndTrigger(jobDetail, trig);
            this.notifySchedulerListenersJobAdded(jobDetail);
            this.notifySchedulerThread(trigger.getNextFireTime().getTime());
            this.notifySchedulerListenersSchduled(trigger);
            return ft;
         }
      }
   }

   public Date scheduleJob(Trigger trigger) throws SchedulerException {
      this.validateState();
      if (trigger == null) {
         throw new SchedulerException("Trigger cannot be null");
      } else {
         OperableTrigger trig = (OperableTrigger)trigger;
         trig.validate();
         Calendar cal = null;
         if (trigger.getCalendarName() != null) {
            cal = this.resources.getJobStore().retrieveCalendar(trigger.getCalendarName());
            if (cal == null) {
               throw new SchedulerException("Calendar not found: " + trigger.getCalendarName());
            }
         }

         Date ft = trig.computeFirstFireTime(cal);
         if (ft == null) {
            throw new SchedulerException("Based on configured schedule, the given trigger '" + trigger.getKey() + "' will never fire.");
         } else {
            this.resources.getJobStore().storeTrigger(trig, false);
            this.notifySchedulerThread(trigger.getNextFireTime().getTime());
            this.notifySchedulerListenersSchduled(trigger);
            return ft;
         }
      }
   }

   public void addJob(JobDetail jobDetail, boolean replace) throws SchedulerException {
      this.addJob(jobDetail, replace, false);
   }

   public void addJob(JobDetail jobDetail, boolean replace, boolean storeNonDurableWhileAwaitingScheduling) throws SchedulerException {
      this.validateState();
      if (!storeNonDurableWhileAwaitingScheduling && !jobDetail.isDurable()) {
         throw new SchedulerException("Jobs added with no trigger must be durable.");
      } else {
         this.resources.getJobStore().storeJob(jobDetail, replace);
         this.notifySchedulerThread(0L);
         this.notifySchedulerListenersJobAdded(jobDetail);
      }
   }

   public boolean deleteJob(JobKey jobKey) throws SchedulerException {
      this.validateState();
      boolean result = false;
      List<? extends Trigger> triggers = this.getTriggersOfJob(jobKey);

      for(Iterator i$ = triggers.iterator(); i$.hasNext(); result = true) {
         Trigger trigger = (Trigger)i$.next();
         if (!this.unscheduleJob(trigger.getKey())) {
            StringBuilder sb = (new StringBuilder()).append("Unable to unschedule trigger [").append(trigger.getKey()).append("] while deleting job [").append(jobKey).append("]");
            throw new SchedulerException(sb.toString());
         }
      }

      result = this.resources.getJobStore().removeJob(jobKey) || result;
      if (result) {
         this.notifySchedulerThread(0L);
         this.notifySchedulerListenersJobDeleted(jobKey);
      }

      return result;
   }

   public boolean deleteJobs(List<JobKey> jobKeys) throws SchedulerException {
      this.validateState();
      boolean result = false;
      result = this.resources.getJobStore().removeJobs(jobKeys);
      this.notifySchedulerThread(0L);
      Iterator i$ = jobKeys.iterator();

      while(i$.hasNext()) {
         JobKey key = (JobKey)i$.next();
         this.notifySchedulerListenersJobDeleted(key);
      }

      return result;
   }

   public void scheduleJobs(Map<JobDetail, Set<? extends Trigger>> triggersAndJobs, boolean replace) throws SchedulerException {
      this.validateState();
      Iterator i$ = triggersAndJobs.entrySet().iterator();

      while(true) {
         JobDetail job;
         Set triggers;
         do {
            Entry e;
            do {
               if (!i$.hasNext()) {
                  this.resources.getJobStore().storeJobsAndTriggers(triggersAndJobs, replace);
                  this.notifySchedulerThread(0L);
                  i$ = triggersAndJobs.keySet().iterator();

                  while(i$.hasNext()) {
                     JobDetail job = (JobDetail)i$.next();
                     this.notifySchedulerListenersJobAdded(job);
                  }

                  return;
               }

               e = (Entry)i$.next();
               job = (JobDetail)e.getKey();
            } while(job == null);

            triggers = (Set)e.getValue();
         } while(triggers == null);

         Iterator i$ = triggers.iterator();

         while(i$.hasNext()) {
            Trigger trigger = (Trigger)i$.next();
            OperableTrigger opt = (OperableTrigger)trigger;
            opt.setJobKey(job.getKey());
            opt.validate();
            Calendar cal = null;
            if (trigger.getCalendarName() != null) {
               cal = this.resources.getJobStore().retrieveCalendar(trigger.getCalendarName());
               if (cal == null) {
                  throw new SchedulerException("Calendar '" + trigger.getCalendarName() + "' not found for trigger: " + trigger.getKey());
               }
            }

            Date ft = opt.computeFirstFireTime(cal);
            if (ft == null) {
               throw new SchedulerException("Based on configured schedule, the given trigger will never fire.");
            }
         }
      }
   }

   public void scheduleJob(JobDetail jobDetail, Set<? extends Trigger> triggersForJob, boolean replace) throws SchedulerException {
      Map<JobDetail, Set<? extends Trigger>> triggersAndJobs = new HashMap();
      triggersAndJobs.put(jobDetail, triggersForJob);
      this.scheduleJobs(triggersAndJobs, replace);
   }

   public boolean unscheduleJobs(List<TriggerKey> triggerKeys) throws SchedulerException {
      this.validateState();
      boolean result = false;
      result = this.resources.getJobStore().removeTriggers(triggerKeys);
      this.notifySchedulerThread(0L);
      Iterator i$ = triggerKeys.iterator();

      while(i$.hasNext()) {
         TriggerKey key = (TriggerKey)i$.next();
         this.notifySchedulerListenersUnscheduled(key);
      }

      return result;
   }

   public boolean unscheduleJob(TriggerKey triggerKey) throws SchedulerException {
      this.validateState();
      if (this.resources.getJobStore().removeTrigger(triggerKey)) {
         this.notifySchedulerThread(0L);
         this.notifySchedulerListenersUnscheduled(triggerKey);
         return true;
      } else {
         return false;
      }
   }

   public Date rescheduleJob(TriggerKey triggerKey, Trigger newTrigger) throws SchedulerException {
      this.validateState();
      if (triggerKey == null) {
         throw new IllegalArgumentException("triggerKey cannot be null");
      } else if (newTrigger == null) {
         throw new IllegalArgumentException("newTrigger cannot be null");
      } else {
         OperableTrigger trig = (OperableTrigger)newTrigger;
         Trigger oldTrigger = this.getTrigger(triggerKey);
         if (oldTrigger == null) {
            return null;
         } else {
            trig.setJobKey(oldTrigger.getJobKey());
            trig.validate();
            Calendar cal = null;
            if (newTrigger.getCalendarName() != null) {
               cal = this.resources.getJobStore().retrieveCalendar(newTrigger.getCalendarName());
            }

            Date ft = trig.computeFirstFireTime(cal);
            if (ft == null) {
               throw new SchedulerException("Based on configured schedule, the given trigger will never fire.");
            } else if (this.resources.getJobStore().replaceTrigger(triggerKey, trig)) {
               this.notifySchedulerThread(newTrigger.getNextFireTime().getTime());
               this.notifySchedulerListenersUnscheduled(triggerKey);
               this.notifySchedulerListenersSchduled(newTrigger);
               return ft;
            } else {
               return null;
            }
         }
      }
   }

   private String newTriggerId() {
      long r = this.random.nextLong();
      if (r < 0L) {
         r = -r;
      }

      return "MT_" + Long.toString(r, 30 + (int)(System.currentTimeMillis() % 7L));
   }

   public void triggerJob(JobKey jobKey, JobDataMap data) throws SchedulerException {
      this.validateState();
      OperableTrigger trig = (OperableTrigger)TriggerBuilder.newTrigger().withIdentity(this.newTriggerId(), "DEFAULT").forJob(jobKey).build();
      trig.computeFirstFireTime((Calendar)null);
      if (data != null) {
         trig.setJobDataMap(data);
      }

      boolean collision = true;

      while(collision) {
         try {
            this.resources.getJobStore().storeTrigger(trig, false);
            collision = false;
         } catch (ObjectAlreadyExistsException var6) {
            trig.setKey(new TriggerKey(this.newTriggerId(), "DEFAULT"));
         }
      }

      this.notifySchedulerThread(trig.getNextFireTime().getTime());
      this.notifySchedulerListenersSchduled(trig);
   }

   public void triggerJob(OperableTrigger trig) throws SchedulerException {
      this.validateState();
      trig.computeFirstFireTime((Calendar)null);
      boolean collision = true;

      while(collision) {
         try {
            this.resources.getJobStore().storeTrigger(trig, false);
            collision = false;
         } catch (ObjectAlreadyExistsException var4) {
            trig.setKey(new TriggerKey(this.newTriggerId(), "DEFAULT"));
         }
      }

      this.notifySchedulerThread(trig.getNextFireTime().getTime());
      this.notifySchedulerListenersSchduled(trig);
   }

   public void pauseTrigger(TriggerKey triggerKey) throws SchedulerException {
      this.validateState();
      this.resources.getJobStore().pauseTrigger(triggerKey);
      this.notifySchedulerThread(0L);
      this.notifySchedulerListenersPausedTrigger(triggerKey);
   }

   public void pauseTriggers(GroupMatcher<TriggerKey> matcher) throws SchedulerException {
      this.validateState();
      if (matcher == null) {
         matcher = GroupMatcher.groupEquals("DEFAULT");
      }

      Collection<String> pausedGroups = this.resources.getJobStore().pauseTriggers(matcher);
      this.notifySchedulerThread(0L);
      Iterator i$ = pausedGroups.iterator();

      while(i$.hasNext()) {
         String pausedGroup = (String)i$.next();
         this.notifySchedulerListenersPausedTriggers(pausedGroup);
      }

   }

   public void pauseJob(JobKey jobKey) throws SchedulerException {
      this.validateState();
      this.resources.getJobStore().pauseJob(jobKey);
      this.notifySchedulerThread(0L);
      this.notifySchedulerListenersPausedJob(jobKey);
   }

   public void pauseJobs(GroupMatcher<JobKey> groupMatcher) throws SchedulerException {
      this.validateState();
      if (groupMatcher == null) {
         groupMatcher = GroupMatcher.groupEquals("DEFAULT");
      }

      Collection<String> pausedGroups = this.resources.getJobStore().pauseJobs(groupMatcher);
      this.notifySchedulerThread(0L);
      Iterator i$ = pausedGroups.iterator();

      while(i$.hasNext()) {
         String pausedGroup = (String)i$.next();
         this.notifySchedulerListenersPausedJobs(pausedGroup);
      }

   }

   public void resumeTrigger(TriggerKey triggerKey) throws SchedulerException {
      this.validateState();
      this.resources.getJobStore().resumeTrigger(triggerKey);
      this.notifySchedulerThread(0L);
      this.notifySchedulerListenersResumedTrigger(triggerKey);
   }

   public void resumeTriggers(GroupMatcher<TriggerKey> matcher) throws SchedulerException {
      this.validateState();
      if (matcher == null) {
         matcher = GroupMatcher.groupEquals("DEFAULT");
      }

      Collection<String> pausedGroups = this.resources.getJobStore().resumeTriggers(matcher);
      this.notifySchedulerThread(0L);
      Iterator i$ = pausedGroups.iterator();

      while(i$.hasNext()) {
         String pausedGroup = (String)i$.next();
         this.notifySchedulerListenersResumedTriggers(pausedGroup);
      }

   }

   public Set<String> getPausedTriggerGroups() throws SchedulerException {
      return this.resources.getJobStore().getPausedTriggerGroups();
   }

   public void resumeJob(JobKey jobKey) throws SchedulerException {
      this.validateState();
      this.resources.getJobStore().resumeJob(jobKey);
      this.notifySchedulerThread(0L);
      this.notifySchedulerListenersResumedJob(jobKey);
   }

   public void resumeJobs(GroupMatcher<JobKey> matcher) throws SchedulerException {
      this.validateState();
      if (matcher == null) {
         matcher = GroupMatcher.groupEquals("DEFAULT");
      }

      Collection<String> resumedGroups = this.resources.getJobStore().resumeJobs(matcher);
      this.notifySchedulerThread(0L);
      Iterator i$ = resumedGroups.iterator();

      while(i$.hasNext()) {
         String pausedGroup = (String)i$.next();
         this.notifySchedulerListenersResumedJobs(pausedGroup);
      }

   }

   public void pauseAll() throws SchedulerException {
      this.validateState();
      this.resources.getJobStore().pauseAll();
      this.notifySchedulerThread(0L);
      this.notifySchedulerListenersPausedTriggers((String)null);
   }

   public void resumeAll() throws SchedulerException {
      this.validateState();
      this.resources.getJobStore().resumeAll();
      this.notifySchedulerThread(0L);
      this.notifySchedulerListenersResumedTrigger((TriggerKey)null);
   }

   public List<String> getJobGroupNames() throws SchedulerException {
      this.validateState();
      return this.resources.getJobStore().getJobGroupNames();
   }

   public Set<JobKey> getJobKeys(GroupMatcher<JobKey> matcher) throws SchedulerException {
      this.validateState();
      if (matcher == null) {
         matcher = GroupMatcher.groupEquals("DEFAULT");
      }

      return this.resources.getJobStore().getJobKeys(matcher);
   }

   public List<? extends Trigger> getTriggersOfJob(JobKey jobKey) throws SchedulerException {
      this.validateState();
      return this.resources.getJobStore().getTriggersForJob(jobKey);
   }

   public List<String> getTriggerGroupNames() throws SchedulerException {
      this.validateState();
      return this.resources.getJobStore().getTriggerGroupNames();
   }

   public Set<TriggerKey> getTriggerKeys(GroupMatcher<TriggerKey> matcher) throws SchedulerException {
      this.validateState();
      if (matcher == null) {
         matcher = GroupMatcher.groupEquals("DEFAULT");
      }

      return this.resources.getJobStore().getTriggerKeys(matcher);
   }

   public JobDetail getJobDetail(JobKey jobKey) throws SchedulerException {
      this.validateState();
      return this.resources.getJobStore().retrieveJob(jobKey);
   }

   public Trigger getTrigger(TriggerKey triggerKey) throws SchedulerException {
      this.validateState();
      return this.resources.getJobStore().retrieveTrigger(triggerKey);
   }

   public boolean checkExists(JobKey jobKey) throws SchedulerException {
      this.validateState();
      return this.resources.getJobStore().checkExists(jobKey);
   }

   public boolean checkExists(TriggerKey triggerKey) throws SchedulerException {
      this.validateState();
      return this.resources.getJobStore().checkExists(triggerKey);
   }

   public void clear() throws SchedulerException {
      this.validateState();
      this.resources.getJobStore().clearAllSchedulingData();
      this.notifySchedulerListenersUnscheduled((TriggerKey)null);
   }

   public Trigger.TriggerState getTriggerState(TriggerKey triggerKey) throws SchedulerException {
      this.validateState();
      return this.resources.getJobStore().getTriggerState(triggerKey);
   }

   public void addCalendar(String calName, Calendar calendar, boolean replace, boolean updateTriggers) throws SchedulerException {
      this.validateState();
      this.resources.getJobStore().storeCalendar(calName, calendar, replace, updateTriggers);
   }

   public boolean deleteCalendar(String calName) throws SchedulerException {
      this.validateState();
      return this.resources.getJobStore().removeCalendar(calName);
   }

   public Calendar getCalendar(String calName) throws SchedulerException {
      this.validateState();
      return this.resources.getJobStore().retrieveCalendar(calName);
   }

   public List<String> getCalendarNames() throws SchedulerException {
      this.validateState();
      return this.resources.getJobStore().getCalendarNames();
   }

   public ListenerManager getListenerManager() {
      return this.listenerManager;
   }

   public void addInternalJobListener(JobListener jobListener) {
      if (jobListener.getName() != null && jobListener.getName().length() != 0) {
         synchronized(this.internalJobListeners) {
            this.internalJobListeners.put(jobListener.getName(), jobListener);
         }
      } else {
         throw new IllegalArgumentException("JobListener name cannot be empty.");
      }
   }

   public boolean removeInternalJobListener(String name) {
      synchronized(this.internalJobListeners) {
         return this.internalJobListeners.remove(name) != null;
      }
   }

   public List<JobListener> getInternalJobListeners() {
      synchronized(this.internalJobListeners) {
         return Collections.unmodifiableList(new LinkedList(this.internalJobListeners.values()));
      }
   }

   public JobListener getInternalJobListener(String name) {
      synchronized(this.internalJobListeners) {
         return (JobListener)this.internalJobListeners.get(name);
      }
   }

   public void addInternalTriggerListener(TriggerListener triggerListener) {
      if (triggerListener.getName() != null && triggerListener.getName().length() != 0) {
         synchronized(this.internalTriggerListeners) {
            this.internalTriggerListeners.put(triggerListener.getName(), triggerListener);
         }
      } else {
         throw new IllegalArgumentException("TriggerListener name cannot be empty.");
      }
   }

   public boolean removeinternalTriggerListener(String name) {
      synchronized(this.internalTriggerListeners) {
         return this.internalTriggerListeners.remove(name) != null;
      }
   }

   public List<TriggerListener> getInternalTriggerListeners() {
      synchronized(this.internalTriggerListeners) {
         return Collections.unmodifiableList(new LinkedList(this.internalTriggerListeners.values()));
      }
   }

   public TriggerListener getInternalTriggerListener(String name) {
      synchronized(this.internalTriggerListeners) {
         return (TriggerListener)this.internalTriggerListeners.get(name);
      }
   }

   public void addInternalSchedulerListener(SchedulerListener schedulerListener) {
      synchronized(this.internalSchedulerListeners) {
         this.internalSchedulerListeners.add(schedulerListener);
      }
   }

   public boolean removeInternalSchedulerListener(SchedulerListener schedulerListener) {
      synchronized(this.internalSchedulerListeners) {
         return this.internalSchedulerListeners.remove(schedulerListener);
      }
   }

   public List<SchedulerListener> getInternalSchedulerListeners() {
      synchronized(this.internalSchedulerListeners) {
         return Collections.unmodifiableList(new ArrayList(this.internalSchedulerListeners));
      }
   }

   protected void notifyJobStoreJobComplete(OperableTrigger trigger, JobDetail detail, Trigger.CompletedExecutionInstruction instCode) {
      this.resources.getJobStore().triggeredJobComplete(trigger, detail, instCode);
   }

   protected void notifyJobStoreJobVetoed(OperableTrigger trigger, JobDetail detail, Trigger.CompletedExecutionInstruction instCode) {
      this.resources.getJobStore().triggeredJobComplete(trigger, detail, instCode);
   }

   protected void notifySchedulerThread(long candidateNewNextFireTime) {
      if (this.isSignalOnSchedulingChange()) {
         this.signaler.signalSchedulingChange(candidateNewNextFireTime);
      }

   }

   private List<TriggerListener> buildTriggerListenerList() throws SchedulerException {
      List<TriggerListener> allListeners = new LinkedList();
      allListeners.addAll(this.getListenerManager().getTriggerListeners());
      allListeners.addAll(this.getInternalTriggerListeners());
      return allListeners;
   }

   private List<JobListener> buildJobListenerList() throws SchedulerException {
      List<JobListener> allListeners = new LinkedList();
      allListeners.addAll(this.getListenerManager().getJobListeners());
      allListeners.addAll(this.getInternalJobListeners());
      return allListeners;
   }

   private List<SchedulerListener> buildSchedulerListenerList() {
      List<SchedulerListener> allListeners = new LinkedList();
      allListeners.addAll(this.getListenerManager().getSchedulerListeners());
      allListeners.addAll(this.getInternalSchedulerListeners());
      return allListeners;
   }

   private boolean matchJobListener(JobListener listener, JobKey key) {
      List<Matcher<JobKey>> matchers = this.getListenerManager().getJobListenerMatchers(listener.getName());
      if (matchers == null) {
         return true;
      } else {
         Iterator i$ = matchers.iterator();

         Matcher matcher;
         do {
            if (!i$.hasNext()) {
               return false;
            }

            matcher = (Matcher)i$.next();
         } while(!matcher.isMatch(key));

         return true;
      }
   }

   private boolean matchTriggerListener(TriggerListener listener, TriggerKey key) {
      List<Matcher<TriggerKey>> matchers = this.getListenerManager().getTriggerListenerMatchers(listener.getName());
      if (matchers == null) {
         return true;
      } else {
         Iterator i$ = matchers.iterator();

         Matcher matcher;
         do {
            if (!i$.hasNext()) {
               return false;
            }

            matcher = (Matcher)i$.next();
         } while(!matcher.isMatch(key));

         return true;
      }
   }

   public boolean notifyTriggerListenersFired(JobExecutionContext jec) throws SchedulerException {
      boolean vetoedExecution = false;
      List<TriggerListener> triggerListeners = this.buildTriggerListenerList();
      Iterator i$ = triggerListeners.iterator();

      while(i$.hasNext()) {
         TriggerListener tl = (TriggerListener)i$.next();

         try {
            if (this.matchTriggerListener(tl, jec.getTrigger().getKey())) {
               tl.triggerFired(jec.getTrigger(), jec);
               if (tl.vetoJobExecution(jec.getTrigger(), jec)) {
                  vetoedExecution = true;
               }
            }
         } catch (Exception var8) {
            SchedulerException se = new SchedulerException("TriggerListener '" + tl.getName() + "' threw exception: " + var8.getMessage(), var8);
            throw se;
         }
      }

      return vetoedExecution;
   }

   public void notifyTriggerListenersMisfired(Trigger trigger) throws SchedulerException {
      List<TriggerListener> triggerListeners = this.buildTriggerListenerList();
      Iterator i$ = triggerListeners.iterator();

      while(i$.hasNext()) {
         TriggerListener tl = (TriggerListener)i$.next();

         try {
            if (this.matchTriggerListener(tl, trigger.getKey())) {
               tl.triggerMisfired(trigger);
            }
         } catch (Exception var7) {
            SchedulerException se = new SchedulerException("TriggerListener '" + tl.getName() + "' threw exception: " + var7.getMessage(), var7);
            throw se;
         }
      }

   }

   public void notifyTriggerListenersComplete(JobExecutionContext jec, Trigger.CompletedExecutionInstruction instCode) throws SchedulerException {
      List<TriggerListener> triggerListeners = this.buildTriggerListenerList();
      Iterator i$ = triggerListeners.iterator();

      while(i$.hasNext()) {
         TriggerListener tl = (TriggerListener)i$.next();

         try {
            if (this.matchTriggerListener(tl, jec.getTrigger().getKey())) {
               tl.triggerComplete(jec.getTrigger(), jec, instCode);
            }
         } catch (Exception var8) {
            SchedulerException se = new SchedulerException("TriggerListener '" + tl.getName() + "' threw exception: " + var8.getMessage(), var8);
            throw se;
         }
      }

   }

   public void notifyJobListenersToBeExecuted(JobExecutionContext jec) throws SchedulerException {
      List<JobListener> jobListeners = this.buildJobListenerList();
      Iterator i$ = jobListeners.iterator();

      while(i$.hasNext()) {
         JobListener jl = (JobListener)i$.next();

         try {
            if (this.matchJobListener(jl, jec.getJobDetail().getKey())) {
               jl.jobToBeExecuted(jec);
            }
         } catch (Exception var7) {
            SchedulerException se = new SchedulerException("JobListener '" + jl.getName() + "' threw exception: " + var7.getMessage(), var7);
            throw se;
         }
      }

   }

   public void notifyJobListenersWasVetoed(JobExecutionContext jec) throws SchedulerException {
      List<JobListener> jobListeners = this.buildJobListenerList();
      Iterator i$ = jobListeners.iterator();

      while(i$.hasNext()) {
         JobListener jl = (JobListener)i$.next();

         try {
            if (this.matchJobListener(jl, jec.getJobDetail().getKey())) {
               jl.jobExecutionVetoed(jec);
            }
         } catch (Exception var7) {
            SchedulerException se = new SchedulerException("JobListener '" + jl.getName() + "' threw exception: " + var7.getMessage(), var7);
            throw se;
         }
      }

   }

   public void notifyJobListenersWasExecuted(JobExecutionContext jec, JobExecutionException je) throws SchedulerException {
      List<JobListener> jobListeners = this.buildJobListenerList();
      Iterator i$ = jobListeners.iterator();

      while(i$.hasNext()) {
         JobListener jl = (JobListener)i$.next();

         try {
            if (this.matchJobListener(jl, jec.getJobDetail().getKey())) {
               jl.jobWasExecuted(jec, je);
            }
         } catch (Exception var8) {
            SchedulerException se = new SchedulerException("JobListener '" + jl.getName() + "' threw exception: " + var8.getMessage(), var8);
            throw se;
         }
      }

   }

   public void notifySchedulerListenersError(String msg, SchedulerException se) {
      List<SchedulerListener> schedListeners = this.buildSchedulerListenerList();
      Iterator i$ = schedListeners.iterator();

      while(i$.hasNext()) {
         SchedulerListener sl = (SchedulerListener)i$.next();

         try {
            sl.schedulerError(msg, se);
         } catch (Exception var7) {
            this.getLog().error("Error while notifying SchedulerListener of error: ", var7);
            this.getLog().error("  Original error (for notification) was: " + msg, se);
         }
      }

   }

   public void notifySchedulerListenersSchduled(Trigger trigger) {
      List<SchedulerListener> schedListeners = this.buildSchedulerListenerList();
      Iterator i$ = schedListeners.iterator();

      while(i$.hasNext()) {
         SchedulerListener sl = (SchedulerListener)i$.next();

         try {
            sl.jobScheduled(trigger);
         } catch (Exception var6) {
            this.getLog().error("Error while notifying SchedulerListener of scheduled job.  Triger=" + trigger.getKey(), var6);
         }
      }

   }

   public void notifySchedulerListenersUnscheduled(TriggerKey triggerKey) {
      List<SchedulerListener> schedListeners = this.buildSchedulerListenerList();
      Iterator i$ = schedListeners.iterator();

      while(i$.hasNext()) {
         SchedulerListener sl = (SchedulerListener)i$.next();

         try {
            if (triggerKey == null) {
               sl.schedulingDataCleared();
            } else {
               sl.jobUnscheduled(triggerKey);
            }
         } catch (Exception var6) {
            this.getLog().error("Error while notifying SchedulerListener of unscheduled job.  Triger=" + (triggerKey == null ? "ALL DATA" : triggerKey), var6);
         }
      }

   }

   public void notifySchedulerListenersFinalized(Trigger trigger) {
      List<SchedulerListener> schedListeners = this.buildSchedulerListenerList();
      Iterator i$ = schedListeners.iterator();

      while(i$.hasNext()) {
         SchedulerListener sl = (SchedulerListener)i$.next();

         try {
            sl.triggerFinalized(trigger);
         } catch (Exception var6) {
            this.getLog().error("Error while notifying SchedulerListener of finalized trigger.  Triger=" + trigger.getKey(), var6);
         }
      }

   }

   public void notifySchedulerListenersPausedTrigger(TriggerKey triggerKey) {
      List<SchedulerListener> schedListeners = this.buildSchedulerListenerList();
      Iterator i$ = schedListeners.iterator();

      while(i$.hasNext()) {
         SchedulerListener sl = (SchedulerListener)i$.next();

         try {
            sl.triggerPaused(triggerKey);
         } catch (Exception var6) {
            this.getLog().error("Error while notifying SchedulerListener of paused trigger: " + triggerKey, var6);
         }
      }

   }

   public void notifySchedulerListenersPausedTriggers(String group) {
      List<SchedulerListener> schedListeners = this.buildSchedulerListenerList();
      Iterator i$ = schedListeners.iterator();

      while(i$.hasNext()) {
         SchedulerListener sl = (SchedulerListener)i$.next();

         try {
            sl.triggersPaused(group);
         } catch (Exception var6) {
            this.getLog().error("Error while notifying SchedulerListener of paused trigger group." + group, var6);
         }
      }

   }

   public void notifySchedulerListenersResumedTrigger(TriggerKey key) {
      List<SchedulerListener> schedListeners = this.buildSchedulerListenerList();
      Iterator i$ = schedListeners.iterator();

      while(i$.hasNext()) {
         SchedulerListener sl = (SchedulerListener)i$.next();

         try {
            sl.triggerResumed(key);
         } catch (Exception var6) {
            this.getLog().error("Error while notifying SchedulerListener of resumed trigger: " + key, var6);
         }
      }

   }

   public void notifySchedulerListenersResumedTriggers(String group) {
      List<SchedulerListener> schedListeners = this.buildSchedulerListenerList();
      Iterator i$ = schedListeners.iterator();

      while(i$.hasNext()) {
         SchedulerListener sl = (SchedulerListener)i$.next();

         try {
            sl.triggersResumed(group);
         } catch (Exception var6) {
            this.getLog().error("Error while notifying SchedulerListener of resumed group: " + group, var6);
         }
      }

   }

   public void notifySchedulerListenersPausedJob(JobKey key) {
      List<SchedulerListener> schedListeners = this.buildSchedulerListenerList();
      Iterator i$ = schedListeners.iterator();

      while(i$.hasNext()) {
         SchedulerListener sl = (SchedulerListener)i$.next();

         try {
            sl.jobPaused(key);
         } catch (Exception var6) {
            this.getLog().error("Error while notifying SchedulerListener of paused job: " + key, var6);
         }
      }

   }

   public void notifySchedulerListenersPausedJobs(String group) {
      List<SchedulerListener> schedListeners = this.buildSchedulerListenerList();
      Iterator i$ = schedListeners.iterator();

      while(i$.hasNext()) {
         SchedulerListener sl = (SchedulerListener)i$.next();

         try {
            sl.jobsPaused(group);
         } catch (Exception var6) {
            this.getLog().error("Error while notifying SchedulerListener of paused job group: " + group, var6);
         }
      }

   }

   public void notifySchedulerListenersResumedJob(JobKey key) {
      List<SchedulerListener> schedListeners = this.buildSchedulerListenerList();
      Iterator i$ = schedListeners.iterator();

      while(i$.hasNext()) {
         SchedulerListener sl = (SchedulerListener)i$.next();

         try {
            sl.jobResumed(key);
         } catch (Exception var6) {
            this.getLog().error("Error while notifying SchedulerListener of resumed job: " + key, var6);
         }
      }

   }

   public void notifySchedulerListenersResumedJobs(String group) {
      List<SchedulerListener> schedListeners = this.buildSchedulerListenerList();
      Iterator i$ = schedListeners.iterator();

      while(i$.hasNext()) {
         SchedulerListener sl = (SchedulerListener)i$.next();

         try {
            sl.jobsResumed(group);
         } catch (Exception var6) {
            this.getLog().error("Error while notifying SchedulerListener of resumed job group: " + group, var6);
         }
      }

   }

   public void notifySchedulerListenersInStandbyMode() {
      List<SchedulerListener> schedListeners = this.buildSchedulerListenerList();
      Iterator i$ = schedListeners.iterator();

      while(i$.hasNext()) {
         SchedulerListener sl = (SchedulerListener)i$.next();

         try {
            sl.schedulerInStandbyMode();
         } catch (Exception var5) {
            this.getLog().error("Error while notifying SchedulerListener of inStandByMode.", var5);
         }
      }

   }

   public void notifySchedulerListenersStarted() {
      List<SchedulerListener> schedListeners = this.buildSchedulerListenerList();
      Iterator i$ = schedListeners.iterator();

      while(i$.hasNext()) {
         SchedulerListener sl = (SchedulerListener)i$.next();

         try {
            sl.schedulerStarted();
         } catch (Exception var5) {
            this.getLog().error("Error while notifying SchedulerListener of startup.", var5);
         }
      }

   }

   public void notifySchedulerListenersStarting() {
      List<SchedulerListener> schedListeners = this.buildSchedulerListenerList();
      Iterator i$ = schedListeners.iterator();

      while(i$.hasNext()) {
         SchedulerListener sl = (SchedulerListener)i$.next();

         try {
            sl.schedulerStarting();
         } catch (Exception var5) {
            this.getLog().error("Error while notifying SchedulerListener of startup.", var5);
         }
      }

   }

   public void notifySchedulerListenersShutdown() {
      List<SchedulerListener> schedListeners = this.buildSchedulerListenerList();
      Iterator i$ = schedListeners.iterator();

      while(i$.hasNext()) {
         SchedulerListener sl = (SchedulerListener)i$.next();

         try {
            sl.schedulerShutdown();
         } catch (Exception var5) {
            this.getLog().error("Error while notifying SchedulerListener of shutdown.", var5);
         }
      }

   }

   public void notifySchedulerListenersShuttingdown() {
      List<SchedulerListener> schedListeners = this.buildSchedulerListenerList();
      Iterator i$ = schedListeners.iterator();

      while(i$.hasNext()) {
         SchedulerListener sl = (SchedulerListener)i$.next();

         try {
            sl.schedulerShuttingdown();
         } catch (Exception var5) {
            this.getLog().error("Error while notifying SchedulerListener of shutdown.", var5);
         }
      }

   }

   public void notifySchedulerListenersJobAdded(JobDetail jobDetail) {
      List<SchedulerListener> schedListeners = this.buildSchedulerListenerList();
      Iterator i$ = schedListeners.iterator();

      while(i$.hasNext()) {
         SchedulerListener sl = (SchedulerListener)i$.next();

         try {
            sl.jobAdded(jobDetail);
         } catch (Exception var6) {
            this.getLog().error("Error while notifying SchedulerListener of JobAdded.", var6);
         }
      }

   }

   public void notifySchedulerListenersJobDeleted(JobKey jobKey) {
      List<SchedulerListener> schedListeners = this.buildSchedulerListenerList();
      Iterator i$ = schedListeners.iterator();

      while(i$.hasNext()) {
         SchedulerListener sl = (SchedulerListener)i$.next();

         try {
            sl.jobDeleted(jobKey);
         } catch (Exception var6) {
            this.getLog().error("Error while notifying SchedulerListener of JobAdded.", var6);
         }
      }

   }

   public void setJobFactory(JobFactory factory) throws SchedulerException {
      if (factory == null) {
         throw new IllegalArgumentException("JobFactory cannot be set to null!");
      } else {
         this.getLog().info("JobFactory set to: " + factory);
         this.jobFactory = factory;
      }
   }

   public JobFactory getJobFactory() {
      return this.jobFactory;
   }

   public boolean interrupt(JobKey jobKey) throws UnableToInterruptJobException {
      List<JobExecutionContext> jobs = this.getCurrentlyExecutingJobs();
      JobDetail jobDetail = null;
      Job job = null;
      boolean interrupted = false;
      Iterator i$ = jobs.iterator();

      while(i$.hasNext()) {
         JobExecutionContext jec = (JobExecutionContext)i$.next();
         jobDetail = jec.getJobDetail();
         if (jobKey.equals(jobDetail.getKey())) {
            job = jec.getJobInstance();
            if (!(job instanceof InterruptableJob)) {
               throw new UnableToInterruptJobException("Job " + jobDetail.getKey() + " can not be interrupted, since it does not implement " + InterruptableJob.class.getName());
            }

            ((InterruptableJob)job).interrupt();
            interrupted = true;
         }
      }

      return interrupted;
   }

   public boolean interrupt(String fireInstanceId) throws UnableToInterruptJobException {
      List<JobExecutionContext> jobs = this.getCurrentlyExecutingJobs();
      Job job = null;
      Iterator i$ = jobs.iterator();

      JobExecutionContext jec;
      do {
         if (!i$.hasNext()) {
            return false;
         }

         jec = (JobExecutionContext)i$.next();
      } while(!jec.getFireInstanceId().equals(fireInstanceId));

      job = jec.getJobInstance();
      if (job instanceof InterruptableJob) {
         ((InterruptableJob)job).interrupt();
         return true;
      } else {
         throw new UnableToInterruptJobException("Job " + jec.getJobDetail().getKey() + " can not be interrupted, since it does not implement " + InterruptableJob.class.getName());
      }
   }

   private void shutdownPlugins() {
      Iterator itr = this.resources.getSchedulerPlugins().iterator();

      while(itr.hasNext()) {
         SchedulerPlugin plugin = (SchedulerPlugin)itr.next();
         plugin.shutdown();
      }

   }

   private void startPlugins() {
      Iterator itr = this.resources.getSchedulerPlugins().iterator();

      while(itr.hasNext()) {
         SchedulerPlugin plugin = (SchedulerPlugin)itr.next();
         plugin.start();
      }

   }

   static {
      Properties props = new Properties();
      InputStream is = null;

      try {
         is = QuartzScheduler.class.getResourceAsStream("quartz-build.properties");
         if (is != null) {
            props.load(is);
            String version = props.getProperty("version");
            if (version != null) {
               String[] versionComponents = version.split("\\.");
               VERSION_MAJOR = versionComponents[0];
               VERSION_MINOR = versionComponents[1];
               if (versionComponents.length > 2) {
                  VERSION_ITERATION = versionComponents[2];
               } else {
                  VERSION_ITERATION = "0";
               }
            } else {
               LoggerFactory.getLogger(QuartzScheduler.class).error("Can't parse Quartz version from quartz-build.properties");
            }
         }
      } catch (Exception var12) {
         LoggerFactory.getLogger(QuartzScheduler.class).error("Error loading version info from quartz-build.properties.", var12);
      } finally {
         if (is != null) {
            try {
               is.close();
            } catch (Exception var11) {
            }
         }

      }

   }
}
