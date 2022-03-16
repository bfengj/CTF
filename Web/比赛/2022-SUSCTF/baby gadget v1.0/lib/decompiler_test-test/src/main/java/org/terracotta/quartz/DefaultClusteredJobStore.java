package org.terracotta.quartz;

import java.util.ArrayList;
import java.util.Collection;
import java.util.Date;
import java.util.HashSet;
import java.util.Iterator;
import java.util.List;
import java.util.Map;
import java.util.NoSuchElementException;
import java.util.Set;
import org.quartz.Calendar;
import org.quartz.JobDataMap;
import org.quartz.JobDetail;
import org.quartz.JobKey;
import org.quartz.JobPersistenceException;
import org.quartz.ObjectAlreadyExistsException;
import org.quartz.SchedulerException;
import org.quartz.Trigger;
import org.quartz.TriggerKey;
import org.quartz.impl.matchers.GroupMatcher;
import org.quartz.impl.matchers.StringMatcher;
import org.quartz.impl.triggers.SimpleTriggerImpl;
import org.quartz.spi.ClassLoadHelper;
import org.quartz.spi.OperableTrigger;
import org.quartz.spi.SchedulerSignaler;
import org.quartz.spi.TriggerFiredBundle;
import org.quartz.spi.TriggerFiredResult;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import org.terracotta.quartz.collections.TimeTriggerSet;
import org.terracotta.quartz.collections.ToolkitDSHolder;
import org.terracotta.quartz.wrappers.DefaultWrapperFactory;
import org.terracotta.quartz.wrappers.FiredTrigger;
import org.terracotta.quartz.wrappers.JobFacade;
import org.terracotta.quartz.wrappers.JobWrapper;
import org.terracotta.quartz.wrappers.TriggerFacade;
import org.terracotta.quartz.wrappers.TriggerWrapper;
import org.terracotta.quartz.wrappers.WrapperFactory;
import org.terracotta.toolkit.Toolkit;
import org.terracotta.toolkit.atomic.ToolkitTransactionType;
import org.terracotta.toolkit.cluster.ClusterEvent;
import org.terracotta.toolkit.cluster.ClusterInfo;
import org.terracotta.toolkit.cluster.ClusterNode;
import org.terracotta.toolkit.concurrent.locks.ToolkitLock;
import org.terracotta.toolkit.internal.ToolkitInternal;
import org.terracotta.toolkit.internal.concurrent.locks.ToolkitLockTypeInternal;
import org.terracotta.toolkit.rejoin.RejoinException;
import org.terracotta.toolkit.store.ToolkitStore;

class DefaultClusteredJobStore implements ClusteredJobStore {
   private final ToolkitDSHolder toolkitDSHolder;
   private final Toolkit toolkit;
   private final JobFacade jobFacade;
   private final TriggerFacade triggerFacade;
   private final TimeTriggerSet timeTriggers;
   private final ToolkitStore<String, Calendar> calendarsByName;
   private long misfireThreshold;
   private final ToolkitLockTypeInternal lockType;
   private final transient ToolkitLock lock;
   private final ClusterInfo clusterInfo;
   private final WrapperFactory wrapperFactory;
   private long ftrCtr;
   private volatile SchedulerSignaler signaler;
   private final Logger logger;
   private volatile String terracottaClientId;
   private long estimatedTimeToReleaseAndAcquireTrigger;
   private volatile DefaultClusteredJobStore.LocalLockState localStateLock;
   private volatile DefaultClusteredJobStore.TriggerRemovedFromCandidateFiringListHandler triggerRemovedFromCandidateFiringListHandler;
   private volatile boolean toolkitShutdown;
   private long retryInterval;

   public DefaultClusteredJobStore(boolean synchWrite, Toolkit toolkit, String jobStoreName) {
      this(synchWrite, toolkit, jobStoreName, new ToolkitDSHolder(jobStoreName, toolkit), new DefaultWrapperFactory());
   }

   public DefaultClusteredJobStore(boolean synchWrite, Toolkit toolkit, String jobStoreName, ToolkitDSHolder toolkitDSHolder, WrapperFactory wrapperFactory) {
      this.misfireThreshold = 60000L;
      this.estimatedTimeToReleaseAndAcquireTrigger = 15L;
      this.toolkit = toolkit;
      this.wrapperFactory = wrapperFactory;
      this.clusterInfo = toolkit.getClusterInfo();
      this.toolkitDSHolder = toolkitDSHolder;
      this.jobFacade = new JobFacade(toolkitDSHolder);
      this.triggerFacade = new TriggerFacade(toolkitDSHolder);
      this.timeTriggers = toolkitDSHolder.getOrCreateTimeTriggerSet();
      this.calendarsByName = toolkitDSHolder.getOrCreateCalendarWrapperMap();
      this.lockType = synchWrite ? ToolkitLockTypeInternal.SYNCHRONOUS_WRITE : ToolkitLockTypeInternal.WRITE;
      ToolkitTransactionType txnType = synchWrite ? ToolkitTransactionType.SYNC : ToolkitTransactionType.NORMAL;
      this.lock = new TransactionControllingLock((ToolkitInternal)toolkit, toolkitDSHolder.getLock(this.lockType), txnType);
      this.logger = LoggerFactory.getLogger(this.getClass());
      this.getLog().info("Synchronous write locking is [" + synchWrite + "]");
   }

   private Logger getLog() {
      return this.logger;
   }

   private void disable() {
      this.toolkitShutdown = true;

      try {
         this.getLocalLockState().disableLocking();
      } catch (InterruptedException var2) {
         this.getLog().error("failed to disable the job store", var2);
      }

   }

   private DefaultClusteredJobStore.LocalLockState getLocalLockState() {
      DefaultClusteredJobStore.LocalLockState rv = this.localStateLock;
      if (rv != null) {
         return rv;
      } else {
         Class var2 = DefaultClusteredJobStore.class;
         synchronized(DefaultClusteredJobStore.class) {
            if (this.localStateLock == null) {
               this.localStateLock = new DefaultClusteredJobStore.LocalLockState();
            }

            return this.localStateLock;
         }
      }
   }

   void lock() throws JobPersistenceException {
      this.getLocalLockState().attemptAcquireBegin();

      try {
         this.lock.lock();
      } catch (RejoinException var2) {
         this.getLocalLockState().release();
         throw var2;
      }
   }

   void unlock() {
      try {
         this.lock.unlock();
      } finally {
         this.getLocalLockState().release();
      }

   }

   public void initialize(ClassLoadHelper loadHelper, SchedulerSignaler schedulerSignaler) {
      this.terracottaClientId = this.clusterInfo.getCurrentNode().getId();
      this.ftrCtr = System.currentTimeMillis();
      this.signaler = schedulerSignaler;
      this.getLog().info(this.getClass().getSimpleName() + " initialized.");
      ((ToolkitInternal)this.toolkit).registerBeforeShutdownHook(new DefaultClusteredJobStore.ShutdownHook(this));
   }

   public void schedulerStarted() throws SchedulerException {
      this.clusterInfo.addClusterListener(this);
      Collection<ClusterNode> nodes = this.clusterInfo.getNodes();
      Set<String> activeClientIDs = new HashSet();
      Iterator i$ = nodes.iterator();

      while(i$.hasNext()) {
         ClusterNode node = (ClusterNode)i$.next();
         boolean added = activeClientIDs.add(node.getId());
         if (!added) {
            this.getLog().error("DUPLICATE node ID detected: " + node);
         }
      }

      this.lock();

      try {
         List<TriggerWrapper> toEval = new ArrayList();
         Iterator iter = this.triggerFacade.allTriggerKeys().iterator();

         TriggerWrapper tw;
         while(iter.hasNext()) {
            TriggerKey triggerKey = (TriggerKey)iter.next();
            tw = this.triggerFacade.get(triggerKey);
            String lastTerracotaClientId = tw.getLastTerracotaClientId();
            if (lastTerracotaClientId != null && (!activeClientIDs.contains(lastTerracotaClientId) || tw.getState() == TriggerWrapper.TriggerState.ERROR)) {
               toEval.add(tw);
            }
         }

         iter = toEval.iterator();

         while(iter.hasNext()) {
            TriggerWrapper tw = (TriggerWrapper)iter.next();
            this.evalOrphanedTrigger(tw, true);
         }

         iter = this.triggerFacade.allFiredTriggers().iterator();

         while(iter.hasNext()) {
            FiredTrigger ft = (FiredTrigger)iter.next();
            if (!activeClientIDs.contains(ft.getClientId())) {
               this.getLog().info("Found non-complete fired trigger: " + ft);
               iter.remove();
               tw = this.triggerFacade.get(ft.getTriggerKey());
               if (tw == null) {
                  this.getLog().error("no trigger found for executing trigger: " + ft.getTriggerKey());
               } else {
                  this.scheduleRecoveryIfNeeded(tw, ft);
               }
            }
         }

      } finally {
         this.unlock();
      }
   }

   public void schedulerPaused() {
   }

   public void schedulerResumed() {
   }

   private void evalOrphanedTrigger(TriggerWrapper tw, boolean newNode) {
      this.getLog().info("Evaluating orphaned trigger " + tw);
      JobWrapper jobWrapper = this.jobFacade.get(tw.getJobKey());
      if (jobWrapper == null) {
         this.getLog().error("No job found for orphaned trigger: " + tw);
         this.jobFacade.removeBlockedJob(tw.getJobKey());
      } else {
         if (newNode && tw.getState() == TriggerWrapper.TriggerState.ERROR) {
            tw.setState(TriggerWrapper.TriggerState.WAITING, this.terracottaClientId, this.triggerFacade);
            this.timeTriggers.add(tw);
         }

         if (tw.getState() == TriggerWrapper.TriggerState.BLOCKED) {
            tw.setState(TriggerWrapper.TriggerState.WAITING, this.terracottaClientId, this.triggerFacade);
            this.timeTriggers.add(tw);
         } else if (tw.getState() == TriggerWrapper.TriggerState.PAUSED_BLOCKED) {
            tw.setState(TriggerWrapper.TriggerState.PAUSED, this.terracottaClientId, this.triggerFacade);
         }

         if (tw.getState() == TriggerWrapper.TriggerState.ACQUIRED) {
            tw.setState(TriggerWrapper.TriggerState.WAITING, this.terracottaClientId, this.triggerFacade);
            this.timeTriggers.add(tw);
         }

         if (!tw.mayFireAgain() && !jobWrapper.requestsRecovery()) {
            try {
               this.removeTrigger(tw.getKey());
            } catch (JobPersistenceException var7) {
               this.getLog().error("Can't remove completed trigger (and related job) " + tw, var7);
            }
         }

         if (jobWrapper.isConcurrentExectionDisallowed()) {
            this.jobFacade.removeBlockedJob(jobWrapper.getKey());
            List<TriggerWrapper> triggersForJob = this.triggerFacade.getTriggerWrappersForJob(jobWrapper.getKey());
            Iterator i$ = triggersForJob.iterator();

            while(i$.hasNext()) {
               TriggerWrapper trigger = (TriggerWrapper)i$.next();
               if (trigger.getState() == TriggerWrapper.TriggerState.BLOCKED) {
                  trigger.setState(TriggerWrapper.TriggerState.WAITING, this.terracottaClientId, this.triggerFacade);
                  this.timeTriggers.add(trigger);
               } else if (trigger.getState() == TriggerWrapper.TriggerState.PAUSED_BLOCKED) {
                  trigger.setState(TriggerWrapper.TriggerState.PAUSED, this.terracottaClientId, this.triggerFacade);
               }
            }
         }

      }
   }

   private void scheduleRecoveryIfNeeded(TriggerWrapper tw, FiredTrigger recovering) {
      JobWrapper jobWrapper = this.jobFacade.get(tw.getJobKey());
      if (jobWrapper == null) {
         this.getLog().error("No job found for orphaned trigger: " + tw);
      } else {
         if (jobWrapper.requestsRecovery()) {
            OperableTrigger recoveryTrigger = this.createRecoveryTrigger(tw, jobWrapper, "recover_" + this.terracottaClientId + "_" + this.ftrCtr++, recovering);
            JobDataMap jd = tw.getTriggerClone().getJobDataMap();
            jd.put("QRTZ_FAILED_JOB_ORIG_TRIGGER_NAME", tw.getKey().getName());
            jd.put("QRTZ_FAILED_JOB_ORIG_TRIGGER_GROUP", tw.getKey().getGroup());
            jd.put("QRTZ_FAILED_JOB_ORIG_TRIGGER_FIRETIME_IN_MILLISECONDS_AS_STRING", String.valueOf(recovering.getFireTime()));
            jd.put("QRTZ_FAILED_JOB_ORIG_TRIGGER_SCHEDULED_FIRETIME_IN_MILLISECONDS_AS_STRING", String.valueOf(recovering.getScheduledFireTime()));
            recoveryTrigger.setJobDataMap(jd);
            recoveryTrigger.computeFirstFireTime((Calendar)null);

            try {
               this.storeTrigger(recoveryTrigger, false);
               if (!tw.mayFireAgain()) {
                  this.removeTrigger(tw.getKey());
               }

               this.getLog().info("Recovered job " + jobWrapper + " for trigger " + tw);
            } catch (JobPersistenceException var7) {
               this.getLog().error("Can't recover job " + jobWrapper + " for trigger " + tw, var7);
            }
         }

      }
   }

   protected OperableTrigger createRecoveryTrigger(TriggerWrapper tw, JobWrapper jw, String name, FiredTrigger recovering) {
      SimpleTriggerImpl recoveryTrigger = new SimpleTriggerImpl(name, "RECOVERING_JOBS", new Date(recovering.getScheduledFireTime()));
      recoveryTrigger.setJobName(jw.getKey().getName());
      recoveryTrigger.setJobGroup(jw.getKey().getGroup());
      recoveryTrigger.setMisfireInstruction(-1);
      recoveryTrigger.setPriority(tw.getPriority());
      return recoveryTrigger;
   }

   private long getMisfireThreshold() {
      return this.misfireThreshold;
   }

   public void setMisfireThreshold(long misfireThreshold) {
      if (misfireThreshold < 1L) {
         throw new IllegalArgumentException("Misfirethreashold must be larger than 0");
      } else {
         this.misfireThreshold = misfireThreshold;
      }
   }

   public void shutdown() {
   }

   public boolean supportsPersistence() {
      throw new AssertionError();
   }

   public void storeJobAndTrigger(JobDetail newJob, OperableTrigger newTrigger) throws JobPersistenceException {
      this.lock();

      try {
         this.storeJob(newJob, false);
         this.storeTrigger(newTrigger, false);
      } finally {
         this.unlock();
      }

   }

   public void storeJob(JobDetail newJob, boolean replaceExisting) throws ObjectAlreadyExistsException, JobPersistenceException {
      JobDetail clone = (JobDetail)newJob.clone();
      this.lock();

      try {
         JobWrapper jw = this.wrapperFactory.createJobWrapper(clone);
         if (this.jobFacade.containsKey(jw.getKey())) {
            if (!replaceExisting) {
               throw new ObjectAlreadyExistsException(newJob);
            }
         } else {
            Set<String> grpSet = this.toolkitDSHolder.getOrCreateJobsGroupMap(newJob.getKey().getGroup());
            grpSet.add(jw.getKey().getName());
            if (!this.jobFacade.hasGroup(jw.getKey().getGroup())) {
               this.jobFacade.addGroup(jw.getKey().getGroup());
            }
         }

         this.jobFacade.put(jw.getKey(), jw);
      } finally {
         this.unlock();
      }

   }

   public boolean removeJob(JobKey jobKey) throws JobPersistenceException {
      boolean found = false;
      this.lock();

      try {
         List<OperableTrigger> trigger = this.getTriggersForJob(jobKey);

         for(Iterator i$ = trigger.iterator(); i$.hasNext(); found = true) {
            OperableTrigger trig = (OperableTrigger)i$.next();
            this.removeTrigger(trig.getKey());
         }

         found |= this.jobFacade.remove(jobKey) != null;
         if (found) {
            Set<String> grpSet = this.toolkitDSHolder.getOrCreateJobsGroupMap(jobKey.getGroup());
            grpSet.remove(jobKey.getName());
            if (grpSet.isEmpty()) {
               this.toolkitDSHolder.removeJobsGroupMap(jobKey.getGroup());
               this.jobFacade.removeGroup(jobKey.getGroup());
            }
         }
      } finally {
         this.unlock();
      }

      return found;
   }

   public boolean removeJobs(List<JobKey> jobKeys) throws JobPersistenceException {
      boolean allFound = true;
      this.lock();

      JobKey key;
      try {
         for(Iterator i$ = jobKeys.iterator(); i$.hasNext(); allFound = this.removeJob(key) && allFound) {
            key = (JobKey)i$.next();
         }
      } finally {
         this.unlock();
      }

      return allFound;
   }

   public boolean removeTriggers(List<TriggerKey> triggerKeys) throws JobPersistenceException {
      boolean allFound = true;
      this.lock();

      TriggerKey key;
      try {
         for(Iterator i$ = triggerKeys.iterator(); i$.hasNext(); allFound = this.removeTrigger(key) && allFound) {
            key = (TriggerKey)i$.next();
         }
      } finally {
         this.unlock();
      }

      return allFound;
   }

   public void storeJobsAndTriggers(Map<JobDetail, Set<? extends Trigger>> triggersAndJobs, boolean replace) throws ObjectAlreadyExistsException, JobPersistenceException {
      this.lock();

      try {
         Iterator i$;
         JobDetail job;
         Iterator i$;
         Trigger trigger;
         if (!replace) {
            i$ = triggersAndJobs.keySet().iterator();

            while(i$.hasNext()) {
               job = (JobDetail)i$.next();
               if (this.checkExists(job.getKey())) {
                  throw new ObjectAlreadyExistsException(job);
               }

               i$ = ((Set)triggersAndJobs.get(job)).iterator();

               while(i$.hasNext()) {
                  trigger = (Trigger)i$.next();
                  if (this.checkExists(trigger.getKey())) {
                     throw new ObjectAlreadyExistsException(trigger);
                  }
               }
            }
         }

         i$ = triggersAndJobs.keySet().iterator();

         while(i$.hasNext()) {
            job = (JobDetail)i$.next();
            this.storeJob(job, true);
            i$ = ((Set)triggersAndJobs.get(job)).iterator();

            while(i$.hasNext()) {
               trigger = (Trigger)i$.next();
               this.storeTrigger((OperableTrigger)trigger, true);
            }
         }
      } finally {
         this.unlock();
      }

   }

   public void storeTrigger(OperableTrigger newTrigger, boolean replaceExisting) throws JobPersistenceException {
      OperableTrigger clone = (OperableTrigger)newTrigger.clone();
      this.lock();

      try {
         JobDetail job = this.retrieveJob(newTrigger.getJobKey());
         if (job == null) {
            throw new JobPersistenceException("The job (" + newTrigger.getJobKey() + ") referenced by the trigger does not exist.");
         }

         TriggerWrapper tw = this.wrapperFactory.createTriggerWrapper(clone, job.isConcurrentExectionDisallowed());
         if (this.triggerFacade.containsKey(tw.getKey())) {
            if (!replaceExisting) {
               throw new ObjectAlreadyExistsException(newTrigger);
            }

            this.removeTrigger(newTrigger.getKey(), false);
         }

         Set<String> grpSet = this.toolkitDSHolder.getOrCreateTriggersGroupMap(newTrigger.getKey().getGroup());
         grpSet.add(newTrigger.getKey().getName());
         if (!this.triggerFacade.hasGroup(newTrigger.getKey().getGroup())) {
            this.triggerFacade.addGroup(newTrigger.getKey().getGroup());
         }

         if (!this.triggerFacade.pausedGroupsContain(newTrigger.getKey().getGroup()) && !this.jobFacade.pausedGroupsContain(newTrigger.getJobKey().getGroup())) {
            if (this.jobFacade.blockedJobsContain(tw.getJobKey())) {
               tw.setState(TriggerWrapper.TriggerState.BLOCKED, this.terracottaClientId, this.triggerFacade);
            } else {
               this.timeTriggers.add(tw);
            }
         } else {
            tw.setState(TriggerWrapper.TriggerState.PAUSED, this.terracottaClientId, this.triggerFacade);
            if (this.jobFacade.blockedJobsContain(tw.getJobKey())) {
               tw.setState(TriggerWrapper.TriggerState.PAUSED_BLOCKED, this.terracottaClientId, this.triggerFacade);
            }
         }

         this.triggerFacade.put(tw.getKey(), tw);
      } finally {
         this.unlock();
      }

   }

   public boolean removeTrigger(TriggerKey triggerKey) throws JobPersistenceException {
      return this.removeTrigger(triggerKey, true);
   }

   private boolean removeTrigger(TriggerKey triggerKey, boolean removeOrphanedJob) throws JobPersistenceException {
      this.lock();
      TriggerWrapper tw = null;

      try {
         tw = this.triggerFacade.remove(triggerKey);
         if (tw != null) {
            Set<String> grpSet = this.toolkitDSHolder.getOrCreateTriggersGroupMap(triggerKey.getGroup());
            grpSet.remove(triggerKey.getName());
            if (grpSet.size() == 0) {
               this.toolkitDSHolder.removeTriggersGroupMap(triggerKey.getGroup());
               this.triggerFacade.removeGroup(triggerKey.getGroup());
            }

            this.timeTriggers.remove(tw);
            if (removeOrphanedJob) {
               JobWrapper jw = this.jobFacade.get(tw.getJobKey());
               List<OperableTrigger> trigs = this.getTriggersForJob(tw.getJobKey());
               if ((trigs == null || trigs.size() == 0) && !jw.isDurable()) {
                  JobKey jobKey = tw.getJobKey();
                  if (this.removeJob(jobKey)) {
                     this.signaler.notifySchedulerListenersJobDeleted(jobKey);
                  }
               }
            }
         }
      } finally {
         this.unlock();
      }

      return tw != null;
   }

   public boolean replaceTrigger(TriggerKey triggerKey, OperableTrigger newTrigger) throws JobPersistenceException {
      boolean found = false;
      this.lock();

      try {
         TriggerWrapper tw = this.triggerFacade.remove(triggerKey);
         found = tw != null;
         if (tw != null) {
            if (!tw.getJobKey().equals(newTrigger.getJobKey())) {
               throw new JobPersistenceException("New trigger is not related to the same job as the old trigger.");
            }

            Set<String> grpSet = this.toolkitDSHolder.getOrCreateTriggersGroupMap(triggerKey.getGroup());
            grpSet.remove(triggerKey.getName());
            if (grpSet.size() == 0) {
               this.toolkitDSHolder.removeTriggersGroupMap(triggerKey.getGroup());
               this.triggerFacade.removeGroup(triggerKey.getGroup());
            }

            this.timeTriggers.remove(tw);

            try {
               this.storeTrigger(newTrigger, false);
            } catch (JobPersistenceException var10) {
               this.storeTrigger(tw.getTriggerClone(), false);
               throw var10;
            }
         }
      } finally {
         this.unlock();
      }

      return found;
   }

   public JobDetail retrieveJob(JobKey jobKey) throws JobPersistenceException {
      JobWrapper jobWrapper = this.getJob(jobKey);
      return jobWrapper == null ? null : jobWrapper.getJobDetailClone();
   }

   JobWrapper getJob(JobKey key) throws JobPersistenceException {
      this.lock();

      JobWrapper var2;
      try {
         var2 = this.jobFacade.get(key);
      } finally {
         this.unlock();
      }

      return var2;
   }

   public OperableTrigger retrieveTrigger(TriggerKey triggerKey) throws JobPersistenceException {
      this.lock();

      OperableTrigger var3;
      try {
         TriggerWrapper tw = this.triggerFacade.get(triggerKey);
         var3 = tw != null ? tw.getTriggerClone() : null;
      } finally {
         this.unlock();
      }

      return var3;
   }

   public boolean checkExists(JobKey jobKey) {
      return this.jobFacade.containsKey(jobKey);
   }

   public boolean checkExists(TriggerKey triggerKey) throws JobPersistenceException {
      return this.triggerFacade.containsKey(triggerKey);
   }

   public void clearAllSchedulingData() throws JobPersistenceException {
      this.lock();

      try {
         List<String> lst = this.getTriggerGroupNames();
         Iterator i$ = lst.iterator();

         label95:
         while(true) {
            String name;
            Set keys;
            Iterator i$;
            if (i$.hasNext()) {
               name = (String)i$.next();
               keys = this.getTriggerKeys(GroupMatcher.triggerGroupEquals(name));
               i$ = keys.iterator();

               while(true) {
                  if (!i$.hasNext()) {
                     continue label95;
                  }

                  TriggerKey key = (TriggerKey)i$.next();
                  this.removeTrigger(key);
               }
            }

            lst = this.getJobGroupNames();
            i$ = lst.iterator();

            label86:
            while(true) {
               if (i$.hasNext()) {
                  name = (String)i$.next();
                  keys = this.getJobKeys(GroupMatcher.jobGroupEquals(name));
                  i$ = keys.iterator();

                  while(true) {
                     if (!i$.hasNext()) {
                        continue label86;
                     }

                     JobKey key = (JobKey)i$.next();
                     this.removeJob(key);
                  }
               }

               lst = this.getCalendarNames();
               i$ = lst.iterator();

               while(i$.hasNext()) {
                  name = (String)i$.next();
                  this.removeCalendar(name);
               }

               return;
            }
         }
      } finally {
         this.unlock();
      }
   }

   public Trigger.TriggerState getTriggerState(TriggerKey key) throws JobPersistenceException {
      this.lock();

      TriggerWrapper tw;
      try {
         tw = this.triggerFacade.get(key);
      } finally {
         this.unlock();
      }

      if (tw == null) {
         return Trigger.TriggerState.NONE;
      } else if (tw.getState() == TriggerWrapper.TriggerState.COMPLETE) {
         return Trigger.TriggerState.COMPLETE;
      } else if (tw.getState() == TriggerWrapper.TriggerState.PAUSED) {
         return Trigger.TriggerState.PAUSED;
      } else if (tw.getState() == TriggerWrapper.TriggerState.PAUSED_BLOCKED) {
         return Trigger.TriggerState.PAUSED;
      } else if (tw.getState() == TriggerWrapper.TriggerState.BLOCKED) {
         return Trigger.TriggerState.BLOCKED;
      } else {
         return tw.getState() == TriggerWrapper.TriggerState.ERROR ? Trigger.TriggerState.ERROR : Trigger.TriggerState.NORMAL;
      }
   }

   public void storeCalendar(String name, Calendar calendar, boolean replaceExisting, boolean updateTriggers) throws ObjectAlreadyExistsException, JobPersistenceException {
      Calendar clone = (Calendar)calendar.clone();
      this.lock();

      try {
         Calendar cal = (Calendar)this.calendarsByName.get(name);
         if (cal != null && !replaceExisting) {
            throw new ObjectAlreadyExistsException("Calendar with name '" + name + "' already exists.");
         }

         if (cal != null) {
            this.calendarsByName.remove(name);
         }

         this.calendarsByName.putNoReturn(name, clone);
         if (cal != null && updateTriggers) {
            Iterator i$ = this.triggerFacade.getTriggerWrappersForCalendar(name).iterator();

            while(i$.hasNext()) {
               TriggerWrapper tw = (TriggerWrapper)i$.next();
               boolean removed = this.timeTriggers.remove(tw);
               tw.updateWithNewCalendar(clone, this.getMisfireThreshold(), this.triggerFacade);
               if (removed) {
                  this.timeTriggers.add(tw);
               }
            }
         }
      } finally {
         this.unlock();
      }

   }

   public boolean removeCalendar(String calName) throws JobPersistenceException {
      int numRefs = 0;
      this.lock();

      boolean var9;
      try {
         Iterator i$ = this.triggerFacade.allTriggerKeys().iterator();

         while(i$.hasNext()) {
            TriggerKey triggerKey = (TriggerKey)i$.next();
            TriggerWrapper tw = this.triggerFacade.get(triggerKey);
            if (tw.getCalendarName() != null && tw.getCalendarName().equals(calName)) {
               ++numRefs;
            }
         }

         if (numRefs > 0) {
            throw new JobPersistenceException("Calender cannot be removed if it referenced by a Trigger!");
         }

         var9 = this.calendarsByName.remove(calName) != null;
      } finally {
         this.unlock();
      }

      return var9;
   }

   public Calendar retrieveCalendar(String calName) throws JobPersistenceException {
      this.lock();

      Calendar var3;
      try {
         Calendar cw = (Calendar)this.calendarsByName.get(calName);
         var3 = (Calendar)(cw == null ? null : cw.clone());
      } finally {
         this.unlock();
      }

      return var3;
   }

   public int getNumberOfJobs() throws JobPersistenceException {
      this.lock();

      int var1;
      try {
         var1 = this.jobFacade.numberOfJobs();
      } finally {
         this.unlock();
      }

      return var1;
   }

   public int getNumberOfTriggers() throws JobPersistenceException {
      this.lock();

      int var1;
      try {
         var1 = this.triggerFacade.numberOfTriggers();
      } finally {
         this.unlock();
      }

      return var1;
   }

   public int getNumberOfCalendars() throws JobPersistenceException {
      this.lock();

      int var1;
      try {
         var1 = this.calendarsByName.size();
      } finally {
         this.unlock();
      }

      return var1;
   }

   public Set<JobKey> getJobKeys(GroupMatcher<JobKey> matcher) throws JobPersistenceException {
      this.lock();

      HashSet var15;
      try {
         Set<String> matchingGroups = new HashSet();
         switch(matcher.getCompareWithOperator()) {
         case EQUALS:
            matchingGroups.add(matcher.getCompareToValue());
            break;
         default:
            Iterator i$ = this.jobFacade.getAllGroupNames().iterator();

            while(i$.hasNext()) {
               String group = (String)i$.next();
               if (matcher.getCompareWithOperator().evaluate(group, matcher.getCompareToValue())) {
                  matchingGroups.add(group);
               }
            }
         }

         Set<JobKey> out = new HashSet();
         Iterator i$ = matchingGroups.iterator();

         while(i$.hasNext()) {
            String matchingGroup = (String)i$.next();
            Set<String> grpJobNames = this.toolkitDSHolder.getOrCreateJobsGroupMap(matchingGroup);
            Iterator i$ = grpJobNames.iterator();

            while(i$.hasNext()) {
               String jobName = (String)i$.next();
               JobKey jobKey = new JobKey(jobName, matchingGroup);
               if (this.jobFacade.containsKey(jobKey)) {
                  out.add(jobKey);
               }
            }
         }

         var15 = out;
      } finally {
         this.unlock();
      }

      return var15;
   }

   public List<String> getCalendarNames() throws JobPersistenceException {
      this.lock();

      ArrayList var2;
      try {
         Set<String> names = this.calendarsByName.keySet();
         var2 = new ArrayList(names);
      } finally {
         this.unlock();
      }

      return var2;
   }

   public Set<TriggerKey> getTriggerKeys(GroupMatcher<TriggerKey> matcher) throws JobPersistenceException {
      this.lock();

      HashSet var16;
      try {
         Set<String> groupNames = new HashSet();
         switch(matcher.getCompareWithOperator()) {
         case EQUALS:
            groupNames.add(matcher.getCompareToValue());
            break;
         default:
            Iterator i$ = this.triggerFacade.allTriggersGroupNames().iterator();

            while(i$.hasNext()) {
               String group = (String)i$.next();
               if (matcher.getCompareWithOperator().evaluate(group, matcher.getCompareToValue())) {
                  groupNames.add(group);
               }
            }
         }

         Set<TriggerKey> out = new HashSet();
         Iterator i$ = groupNames.iterator();

         while(i$.hasNext()) {
            String groupName = (String)i$.next();
            Set<String> grpSet = this.toolkitDSHolder.getOrCreateTriggersGroupMap(groupName);
            Iterator i$ = grpSet.iterator();

            while(i$.hasNext()) {
               String key = (String)i$.next();
               TriggerKey triggerKey = new TriggerKey(key, groupName);
               TriggerWrapper tw = this.triggerFacade.get(triggerKey);
               if (tw != null) {
                  out.add(triggerKey);
               }
            }
         }

         var16 = out;
      } finally {
         this.unlock();
      }

      return var16;
   }

   public List<String> getJobGroupNames() throws JobPersistenceException {
      this.lock();

      ArrayList var1;
      try {
         var1 = new ArrayList(this.jobFacade.getAllGroupNames());
      } finally {
         this.unlock();
      }

      return var1;
   }

   public List<String> getTriggerGroupNames() throws JobPersistenceException {
      this.lock();

      ArrayList var1;
      try {
         var1 = new ArrayList(this.triggerFacade.allTriggersGroupNames());
      } finally {
         this.unlock();
      }

      return var1;
   }

   public List<OperableTrigger> getTriggersForJob(JobKey jobKey) throws JobPersistenceException {
      List<OperableTrigger> trigList = new ArrayList();
      this.lock();

      try {
         Iterator i$ = this.triggerFacade.allTriggerKeys().iterator();

         while(i$.hasNext()) {
            TriggerKey triggerKey = (TriggerKey)i$.next();
            TriggerWrapper tw = this.triggerFacade.get(triggerKey);
            if (tw.getJobKey().equals(jobKey)) {
               trigList.add(tw.getTriggerClone());
            }
         }
      } finally {
         this.unlock();
      }

      return trigList;
   }

   public void pauseTrigger(TriggerKey triggerKey) throws JobPersistenceException {
      this.lock();

      try {
         TriggerWrapper tw = this.triggerFacade.get(triggerKey);
         if (tw == null) {
            return;
         }

         if (tw.getState() != TriggerWrapper.TriggerState.COMPLETE) {
            if (tw.getState() == TriggerWrapper.TriggerState.BLOCKED) {
               tw.setState(TriggerWrapper.TriggerState.PAUSED_BLOCKED, this.terracottaClientId, this.triggerFacade);
            } else {
               tw.setState(TriggerWrapper.TriggerState.PAUSED, this.terracottaClientId, this.triggerFacade);
            }

            this.timeTriggers.remove(tw);
            if (this.triggerRemovedFromCandidateFiringListHandler != null) {
               this.triggerRemovedFromCandidateFiringListHandler.removeCandidateTrigger(tw);
            }

            return;
         }
      } finally {
         this.unlock();
      }

   }

   public Collection<String> pauseTriggers(GroupMatcher<TriggerKey> matcher) throws JobPersistenceException {
      HashSet<String> pausedGroups = new HashSet();
      this.lock();

      try {
         Set<TriggerKey> triggerKeys = this.getTriggerKeys(matcher);
         Iterator i$ = triggerKeys.iterator();

         while(true) {
            if (!i$.hasNext()) {
               StringMatcher.StringOperatorName operator = matcher.getCompareWithOperator();
               if (operator.equals(StringMatcher.StringOperatorName.EQUALS)) {
                  this.triggerFacade.addPausedGroup(matcher.getCompareToValue());
                  pausedGroups.add(matcher.getCompareToValue());
               }
               break;
            }

            TriggerKey key = (TriggerKey)i$.next();
            this.triggerFacade.addPausedGroup(key.getGroup());
            pausedGroups.add(key.getGroup());
            this.pauseTrigger(key);
         }
      } finally {
         this.unlock();
      }

      return pausedGroups;
   }

   public void pauseJob(JobKey jobKey) throws JobPersistenceException {
      this.lock();

      try {
         Iterator i$ = this.getTriggersForJob(jobKey).iterator();

         while(i$.hasNext()) {
            OperableTrigger trigger = (OperableTrigger)i$.next();
            this.pauseTrigger(trigger.getKey());
         }
      } finally {
         this.unlock();
      }

   }

   public Collection<String> pauseJobs(GroupMatcher<JobKey> matcher) throws JobPersistenceException {
      Collection<String> pausedGroups = new HashSet();
      this.lock();

      try {
         Set<JobKey> jobKeys = this.getJobKeys(matcher);
         Iterator i$ = jobKeys.iterator();

         while(i$.hasNext()) {
            JobKey jobKey = (JobKey)i$.next();
            Iterator i$ = this.getTriggersForJob(jobKey).iterator();

            while(i$.hasNext()) {
               OperableTrigger trigger = (OperableTrigger)i$.next();
               this.pauseTrigger(trigger.getKey());
            }

            pausedGroups.add(jobKey.getGroup());
         }

         StringMatcher.StringOperatorName operator = matcher.getCompareWithOperator();
         if (operator.equals(StringMatcher.StringOperatorName.EQUALS)) {
            this.jobFacade.addPausedGroup(matcher.getCompareToValue());
            pausedGroups.add(matcher.getCompareToValue());
         }
      } finally {
         this.unlock();
      }

      return pausedGroups;
   }

   public void resumeTrigger(TriggerKey triggerKey) throws JobPersistenceException {
      this.lock();

      try {
         TriggerWrapper tw = this.triggerFacade.get(triggerKey);
         if (tw != null) {
            if (tw.getState() != TriggerWrapper.TriggerState.PAUSED && tw.getState() != TriggerWrapper.TriggerState.PAUSED_BLOCKED) {
               return;
            }

            if (this.jobFacade.blockedJobsContain(tw.getJobKey())) {
               tw.setState(TriggerWrapper.TriggerState.BLOCKED, this.terracottaClientId, this.triggerFacade);
            } else {
               tw.setState(TriggerWrapper.TriggerState.WAITING, this.terracottaClientId, this.triggerFacade);
            }

            this.applyMisfire(tw);
            if (tw.getState() == TriggerWrapper.TriggerState.WAITING) {
               this.timeTriggers.add(tw);
            }

            return;
         }
      } finally {
         this.unlock();
      }

   }

   public Collection<String> resumeTriggers(GroupMatcher<TriggerKey> matcher) throws JobPersistenceException {
      Collection<String> groups = new HashSet();
      this.lock();

      try {
         Set<TriggerKey> triggerKeys = this.getTriggerKeys(matcher);
         Iterator i$ = triggerKeys.iterator();

         while(i$.hasNext()) {
            TriggerKey triggerKey = (TriggerKey)i$.next();
            TriggerWrapper tw = this.triggerFacade.get(triggerKey);
            if (tw != null) {
               String jobGroup = tw.getJobKey().getGroup();
               if (this.jobFacade.pausedGroupsContain(jobGroup)) {
                  continue;
               }

               groups.add(triggerKey.getGroup());
            }

            this.resumeTrigger(triggerKey);
         }

         this.triggerFacade.removeAllPausedGroups(groups);
         return groups;
      } finally {
         this.unlock();
      }
   }

   public void resumeJob(JobKey jobKey) throws JobPersistenceException {
      this.lock();

      try {
         Iterator i$ = this.getTriggersForJob(jobKey).iterator();

         while(i$.hasNext()) {
            OperableTrigger trigger = (OperableTrigger)i$.next();
            this.resumeTrigger(trigger.getKey());
         }
      } finally {
         this.unlock();
      }

   }

   public Collection<String> resumeJobs(GroupMatcher<JobKey> matcher) throws JobPersistenceException {
      Collection<String> groups = new HashSet();
      this.lock();

      try {
         Set<JobKey> jobKeys = this.getJobKeys(matcher);
         Iterator i$ = jobKeys.iterator();

         while(i$.hasNext()) {
            JobKey jobKey = (JobKey)i$.next();
            if (groups.add(jobKey.getGroup())) {
               this.jobFacade.removePausedJobGroup(jobKey.getGroup());
            }

            Iterator i$ = this.getTriggersForJob(jobKey).iterator();

            while(i$.hasNext()) {
               OperableTrigger trigger = (OperableTrigger)i$.next();
               this.resumeTrigger(trigger.getKey());
            }
         }
      } finally {
         this.unlock();
      }

      return groups;
   }

   public void pauseAll() throws JobPersistenceException {
      this.lock();

      try {
         List<String> names = this.getTriggerGroupNames();
         Iterator i$ = names.iterator();

         while(i$.hasNext()) {
            String name = (String)i$.next();
            this.pauseTriggers(GroupMatcher.triggerGroupEquals(name));
         }
      } finally {
         this.unlock();
      }

   }

   public void resumeAll() throws JobPersistenceException {
      this.lock();

      try {
         this.jobFacade.clearPausedJobGroups();
         List<String> names = this.getTriggerGroupNames();
         Iterator i$ = names.iterator();

         while(i$.hasNext()) {
            String name = (String)i$.next();
            this.resumeTriggers(GroupMatcher.triggerGroupEquals(name));
         }
      } finally {
         this.unlock();
      }

   }

   boolean applyMisfire(TriggerWrapper tw) throws JobPersistenceException {
      long misfireTime = System.currentTimeMillis();
      if (this.getMisfireThreshold() > 0L) {
         misfireTime -= this.getMisfireThreshold();
      }

      Date tnft = tw.getNextFireTime();
      if (tnft != null && tnft.getTime() <= misfireTime && tw.getMisfireInstruction() != -1) {
         Calendar cal = null;
         if (tw.getCalendarName() != null) {
            cal = this.retrieveCalendar(tw.getCalendarName());
         }

         this.signaler.notifyTriggerListenersMisfired(tw.getTriggerClone());
         tw.updateAfterMisfire(cal, this.triggerFacade);
         if (tw.getNextFireTime() == null) {
            tw.setState(TriggerWrapper.TriggerState.COMPLETE, this.terracottaClientId, this.triggerFacade);
            this.signaler.notifySchedulerListenersFinalized(tw.getTriggerClone());
            this.timeTriggers.remove(tw);
         } else if (tnft.equals(tw.getNextFireTime())) {
            return false;
         }

         return true;
      } else {
         return false;
      }
   }

   public List<OperableTrigger> acquireNextTriggers(long noLaterThan, int maxCount, long timeWindow) throws JobPersistenceException {
      List<OperableTrigger> result = new ArrayList();
      this.lock();

      try {
         Iterator i$ = this.getNextTriggerWrappers(this.timeTriggers, noLaterThan, maxCount, timeWindow).iterator();

         while(i$.hasNext()) {
            TriggerWrapper tw = (TriggerWrapper)i$.next();
            result.add(this.markAndCloneTrigger(tw));
         }

         ArrayList var16 = result;
         return var16;
      } finally {
         try {
            this.unlock();
         } catch (RejoinException var14) {
            if (!this.validateAcquired(result)) {
               throw var14;
            }
         }

      }
   }

   private boolean validateAcquired(List<OperableTrigger> result) {
      if (result.isEmpty()) {
         return false;
      } else {
         while(!this.toolkitShutdown) {
            try {
               this.lock();

               boolean var5;
               try {
                  Iterator i$ = result.iterator();

                  OperableTrigger ot;
                  TriggerWrapper tw;
                  do {
                     if (!i$.hasNext()) {
                        boolean var17 = true;
                        return var17;
                     }

                     ot = (OperableTrigger)i$.next();
                     tw = this.triggerFacade.get(ot.getKey());
                  } while(ot.getFireInstanceId().equals(tw.getTriggerClone().getFireInstanceId()) && TriggerWrapper.TriggerState.ACQUIRED.equals(tw.getState()));

                  var5 = false;
               } finally {
                  this.unlock();
               }

               return var5;
            } catch (JobPersistenceException var15) {
               try {
                  Thread.sleep(this.retryInterval);
               } catch (InterruptedException var13) {
                  throw new IllegalStateException("Received interrupted exception", var13);
               }
            } catch (RejoinException var16) {
               try {
                  Thread.sleep(this.retryInterval);
               } catch (InterruptedException var12) {
                  throw new IllegalStateException("Received interrupted exception", var12);
               }
            }
         }

         throw new IllegalStateException("Scheduler has been shutdown");
      }
   }

   OperableTrigger markAndCloneTrigger(TriggerWrapper tw) {
      tw.setState(TriggerWrapper.TriggerState.ACQUIRED, this.terracottaClientId, this.triggerFacade);
      String firedInstanceId = this.terracottaClientId + "-" + this.ftrCtr++;
      tw.setFireInstanceId(firedInstanceId, this.triggerFacade);
      return tw.getTriggerClone();
   }

   List<TriggerWrapper> getNextTriggerWrappers(long noLaterThan, int maxCount, long timeWindow) throws JobPersistenceException {
      return this.getNextTriggerWrappers(this.timeTriggers, noLaterThan, maxCount, timeWindow);
   }

   List<TriggerWrapper> getNextTriggerWrappers(TimeTriggerSet source, long noLaterThan, int maxCount, long timeWindow) throws JobPersistenceException {
      List<TriggerWrapper> wrappers = new ArrayList();
      Set<JobKey> acquiredJobKeysForNoConcurrentExec = new HashSet();
      Set<TriggerWrapper> excludedTriggers = new HashSet();
      JobPersistenceException caughtJpe = null;
      long firstAcquiredTriggerFireTime = 0L;

      try {
         while(true) {
            TriggerWrapper tw = null;

            try {
               TriggerKey triggerKey = source.removeFirst();
               if (triggerKey != null) {
                  tw = this.triggerFacade.get(triggerKey);
               }

               if (tw == null) {
                  break;
               }
            } catch (NoSuchElementException var15) {
               break;
            }

            if (tw.getNextFireTime() != null) {
               if (firstAcquiredTriggerFireTime > 0L && tw.getNextFireTime().getTime() > firstAcquiredTriggerFireTime + timeWindow) {
                  source.add(tw);
                  break;
               }

               if (this.applyMisfire(tw)) {
                  if (tw.getNextFireTime() != null) {
                     source.add(tw);
                  }
               } else {
                  if (tw.getNextFireTime().getTime() > noLaterThan + timeWindow) {
                     source.add(tw);
                     break;
                  }

                  if (tw.jobDisallowsConcurrence()) {
                     if (acquiredJobKeysForNoConcurrentExec.contains(tw.getJobKey())) {
                        excludedTriggers.add(tw);
                        continue;
                     }

                     acquiredJobKeysForNoConcurrentExec.add(tw.getJobKey());
                  }

                  wrappers.add(tw);
                  if (firstAcquiredTriggerFireTime == 0L) {
                     firstAcquiredTriggerFireTime = tw.getNextFireTime().getTime();
                  }

                  if (wrappers.size() == maxCount) {
                     break;
                  }
               }
            }
         }
      } catch (JobPersistenceException var16) {
         caughtJpe = var16;
      }

      Iterator i$;
      TriggerWrapper tw;
      if (excludedTriggers.size() > 0) {
         i$ = excludedTriggers.iterator();

         while(i$.hasNext()) {
            tw = (TriggerWrapper)i$.next();
            source.add(tw);
         }
      }

      if (caughtJpe == null) {
         return wrappers;
      } else {
         i$ = wrappers.iterator();

         while(i$.hasNext()) {
            tw = (TriggerWrapper)i$.next();
            source.add(tw);
         }

         throw new JobPersistenceException("Exception encountered while trying to select triggers for firing.", caughtJpe);
      }
   }

   public void setTriggerRemovedFromCandidateFiringListHandler(DefaultClusteredJobStore.TriggerRemovedFromCandidateFiringListHandler triggerRemovedFromCandidateFiringListHandler) {
      this.triggerRemovedFromCandidateFiringListHandler = triggerRemovedFromCandidateFiringListHandler;
   }

   public void releaseAcquiredTrigger(OperableTrigger trigger) {
      while(true) {
         if (!this.toolkitShutdown) {
            try {
               this.lock();

               try {
                  TriggerWrapper tw = this.triggerFacade.get(trigger.getKey());
                  if (tw != null && trigger.getFireInstanceId().equals(tw.getTriggerClone().getFireInstanceId()) && tw.getState() == TriggerWrapper.TriggerState.ACQUIRED) {
                     tw.setState(TriggerWrapper.TriggerState.WAITING, this.terracottaClientId, this.triggerFacade);
                     this.timeTriggers.add(tw);
                  }
               } finally {
                  this.unlock();
               }
            } catch (RejoinException var12) {
               try {
                  Thread.sleep(this.retryInterval);
                  continue;
               } catch (InterruptedException var10) {
                  throw new IllegalStateException("Received interrupted exception", var10);
               }
            } catch (JobPersistenceException var13) {
               try {
                  Thread.sleep(this.retryInterval);
                  continue;
               } catch (InterruptedException var9) {
                  throw new IllegalStateException("Received interrupted exception", var9);
               }
            }
         }

         return;
      }
   }

   public List<TriggerFiredResult> triggersFired(List<OperableTrigger> triggersFired) throws JobPersistenceException {
      List<TriggerFiredResult> results = new ArrayList();
      this.lock();

      try {
         Iterator i$ = triggersFired.iterator();

         while(i$.hasNext()) {
            OperableTrigger trigger = (OperableTrigger)i$.next();
            TriggerWrapper tw = this.triggerFacade.get(trigger.getKey());
            if (tw == null) {
               results.add(new TriggerFiredResult((TriggerFiredBundle)null));
            } else if (tw.getState() != TriggerWrapper.TriggerState.ACQUIRED) {
               results.add(new TriggerFiredResult((TriggerFiredBundle)null));
            } else {
               Calendar cal = null;
               if (tw.getCalendarName() != null) {
                  cal = this.retrieveCalendar(tw.getCalendarName());
                  if (cal == null) {
                     results.add(new TriggerFiredResult((TriggerFiredBundle)null));
                     continue;
                  }
               }

               Date prevFireTime = trigger.getPreviousFireTime();
               this.timeTriggers.remove(tw);
               tw.triggered(cal, this.triggerFacade);
               trigger.triggered(cal);
               tw.setState(TriggerWrapper.TriggerState.WAITING, this.terracottaClientId, this.triggerFacade);
               TriggerFiredBundle bndle = new TriggerFiredBundle(this.retrieveJob(trigger.getJobKey()), trigger, cal, false, new Date(), trigger.getPreviousFireTime(), prevFireTime, trigger.getNextFireTime());
               String fireInstanceId = trigger.getFireInstanceId();
               FiredTrigger prev = this.triggerFacade.getFiredTrigger(fireInstanceId);
               this.triggerFacade.putFiredTrigger(fireInstanceId, new FiredTrigger(this.terracottaClientId, tw.getKey(), trigger.getPreviousFireTime().getTime()));
               this.getLog().trace("Tracking " + trigger + " has fired on " + fireInstanceId);
               if (prev != null) {
                  throw new AssertionError("duplicate fireInstanceId detected (" + fireInstanceId + ") for " + trigger + ", previous is " + prev);
               }

               JobDetail job = bndle.getJobDetail();
               if (job.isConcurrentExectionDisallowed()) {
                  List<TriggerWrapper> trigs = this.triggerFacade.getTriggerWrappersForJob(job.getKey());
                  Iterator i$ = trigs.iterator();

                  while(i$.hasNext()) {
                     TriggerWrapper ttw = (TriggerWrapper)i$.next();
                     if (!ttw.getKey().equals(tw.getKey())) {
                        if (ttw.getState() == TriggerWrapper.TriggerState.WAITING) {
                           ttw.setState(TriggerWrapper.TriggerState.BLOCKED, this.terracottaClientId, this.triggerFacade);
                        }

                        if (ttw.getState() == TriggerWrapper.TriggerState.PAUSED) {
                           ttw.setState(TriggerWrapper.TriggerState.PAUSED_BLOCKED, this.terracottaClientId, this.triggerFacade);
                        }

                        this.timeTriggers.remove(ttw);
                        if (this.triggerRemovedFromCandidateFiringListHandler != null) {
                           this.triggerRemovedFromCandidateFiringListHandler.removeCandidateTrigger(ttw);
                        }
                     }
                  }

                  this.jobFacade.addBlockedJob(job.getKey());
               } else if (tw.getNextFireTime() != null) {
                  this.timeTriggers.add(tw);
               }

               results.add(new TriggerFiredResult(bndle));
            }
         }

         ArrayList var22 = results;
         return var22;
      } finally {
         try {
            this.unlock();
         } catch (RejoinException var20) {
            if (!this.validateFiring(results)) {
               throw var20;
            }
         }

      }
   }

   private boolean validateFiring(List<TriggerFiredResult> result) {
      if (result.isEmpty()) {
         return false;
      } else {
         while(!this.toolkitShutdown) {
            try {
               this.lock();

               boolean var17;
               try {
                  Iterator i$ = result.iterator();

                  while(i$.hasNext()) {
                     TriggerFiredResult tfr = (TriggerFiredResult)i$.next();
                     TriggerFiredBundle tfb = tfr.getTriggerFiredBundle();
                     if (tfb != null && !this.triggerFacade.containsFiredTrigger(tfb.getTrigger().getFireInstanceId())) {
                        boolean var5 = false;
                        return var5;
                     }
                  }

                  var17 = true;
               } finally {
                  this.unlock();
               }

               return var17;
            } catch (JobPersistenceException var15) {
               try {
                  Thread.sleep(this.retryInterval);
               } catch (InterruptedException var13) {
                  throw new IllegalStateException("Received interrupted exception", var13);
               }
            } catch (RejoinException var16) {
               try {
                  Thread.sleep(this.retryInterval);
               } catch (InterruptedException var12) {
                  throw new IllegalStateException("Received interrupted exception", var12);
               }
            }
         }

         throw new IllegalStateException("Scheduler has been shutdown");
      }
   }

   public void triggeredJobComplete(OperableTrigger trigger, JobDetail jobDetail, Trigger.CompletedExecutionInstruction triggerInstCode) {
      while(true) {
         if (!this.toolkitShutdown) {
            try {
               this.lock();

               try {
                  String fireId = trigger.getFireInstanceId();
                  FiredTrigger removed = this.triggerFacade.removeFiredTrigger(fireId);
                  if (removed == null) {
                     this.getLog().warn("No fired trigger record found for " + trigger + " (" + fireId + ")");
                  } else {
                     JobKey jobKey = jobDetail.getKey();
                     JobWrapper jw = this.jobFacade.get(jobKey);
                     TriggerWrapper tw = this.triggerFacade.get(trigger.getKey());
                     if (jw == null) {
                        this.jobFacade.removeBlockedJob(jobKey);
                     } else {
                        if (jw.isPersistJobDataAfterExecution()) {
                           JobDataMap newData = jobDetail.getJobDataMap();
                           if (newData != null) {
                              newData = (JobDataMap)newData.clone();
                              newData.clearDirtyFlag();
                           }

                           jw.setJobDataMap(newData, this.jobFacade);
                        }

                        if (jw.isConcurrentExectionDisallowed()) {
                           this.jobFacade.removeBlockedJob(jw.getKey());
                           tw.setState(TriggerWrapper.TriggerState.WAITING, this.terracottaClientId, this.triggerFacade);
                           this.timeTriggers.add(tw);
                           List<TriggerWrapper> trigs = this.triggerFacade.getTriggerWrappersForJob(jw.getKey());
                           Iterator i$ = trigs.iterator();

                           while(i$.hasNext()) {
                              TriggerWrapper ttw = (TriggerWrapper)i$.next();
                              if (ttw.getState() == TriggerWrapper.TriggerState.BLOCKED) {
                                 ttw.setState(TriggerWrapper.TriggerState.WAITING, this.terracottaClientId, this.triggerFacade);
                                 this.timeTriggers.add(ttw);
                              }

                              if (ttw.getState() == TriggerWrapper.TriggerState.PAUSED_BLOCKED) {
                                 ttw.setState(TriggerWrapper.TriggerState.PAUSED, this.terracottaClientId, this.triggerFacade);
                              }
                           }

                           this.signaler.signalSchedulingChange(0L);
                        }
                     }

                     if (tw != null) {
                        if (triggerInstCode == Trigger.CompletedExecutionInstruction.DELETE_TRIGGER) {
                           if (trigger.getNextFireTime() == null) {
                              if (tw.getNextFireTime() == null) {
                                 this.removeTrigger(trigger.getKey());
                              }
                           } else {
                              this.removeTrigger(trigger.getKey());
                              this.signaler.signalSchedulingChange(0L);
                           }
                        } else if (triggerInstCode == Trigger.CompletedExecutionInstruction.SET_TRIGGER_COMPLETE) {
                           tw.setState(TriggerWrapper.TriggerState.COMPLETE, this.terracottaClientId, this.triggerFacade);
                           this.timeTriggers.remove(tw);
                           this.signaler.signalSchedulingChange(0L);
                        } else if (triggerInstCode == Trigger.CompletedExecutionInstruction.SET_TRIGGER_ERROR) {
                           this.getLog().info("Trigger " + trigger.getKey() + " set to ERROR state.");
                           tw.setState(TriggerWrapper.TriggerState.ERROR, this.terracottaClientId, this.triggerFacade);
                           this.signaler.signalSchedulingChange(0L);
                        } else if (triggerInstCode == Trigger.CompletedExecutionInstruction.SET_ALL_JOB_TRIGGERS_ERROR) {
                           this.getLog().info("All triggers of Job " + trigger.getJobKey() + " set to ERROR state.");
                           this.setAllTriggersOfJobToState(trigger.getJobKey(), TriggerWrapper.TriggerState.ERROR);
                           this.signaler.signalSchedulingChange(0L);
                        } else if (triggerInstCode == Trigger.CompletedExecutionInstruction.SET_ALL_JOB_TRIGGERS_COMPLETE) {
                           this.setAllTriggersOfJobToState(trigger.getJobKey(), TriggerWrapper.TriggerState.COMPLETE);
                           this.signaler.signalSchedulingChange(0L);
                        }
                     }
                  }
               } finally {
                  this.unlock();
               }
            } catch (RejoinException var21) {
               try {
                  Thread.sleep(this.retryInterval);
                  continue;
               } catch (InterruptedException var19) {
                  throw new IllegalStateException("Received interrupted exception", var19);
               }
            } catch (JobPersistenceException var22) {
               try {
                  Thread.sleep(this.retryInterval);
                  continue;
               } catch (InterruptedException var18) {
                  throw new IllegalStateException("Received interrupted exception", var18);
               }
            }
         }

         return;
      }
   }

   private void setAllTriggersOfJobToState(JobKey jobKey, TriggerWrapper.TriggerState state) {
      List<TriggerWrapper> tws = this.triggerFacade.getTriggerWrappersForJob(jobKey);
      Iterator i$ = tws.iterator();

      while(i$.hasNext()) {
         TriggerWrapper tw = (TriggerWrapper)i$.next();
         tw.setState(state, this.terracottaClientId, this.triggerFacade);
         if (state != TriggerWrapper.TriggerState.WAITING) {
            this.timeTriggers.remove(tw);
         }
      }

   }

   public Set<String> getPausedTriggerGroups() throws JobPersistenceException {
      this.lock();

      HashSet var1;
      try {
         var1 = new HashSet(this.triggerFacade.allPausedTriggersGroupNames());
      } finally {
         this.unlock();
      }

      return var1;
   }

   public void setInstanceId(String schedInstId) {
   }

   public void setInstanceName(String schedName) {
   }

   public void setTcRetryInterval(long retryInterval) {
      this.retryInterval = retryInterval;
   }

   public void nodeLeft(ClusterEvent event) {
      String nodeLeft = event.getNode().getId();

      try {
         this.lock();
      } catch (JobPersistenceException var11) {
         this.getLog().info("Job store is already disabled, not processing nodeLeft() for " + nodeLeft);
         return;
      }

      try {
         List<TriggerWrapper> toEval = new ArrayList();
         Iterator iter = this.triggerFacade.allTriggerKeys().iterator();

         TriggerWrapper tw;
         while(iter.hasNext()) {
            TriggerKey triggerKey = (TriggerKey)iter.next();
            tw = this.triggerFacade.get(triggerKey);
            String clientId = tw.getLastTerracotaClientId();
            if (clientId != null && clientId.equals(nodeLeft)) {
               toEval.add(tw);
            }
         }

         iter = toEval.iterator();

         while(iter.hasNext()) {
            TriggerWrapper tw = (TriggerWrapper)iter.next();
            this.evalOrphanedTrigger(tw, false);
         }

         iter = this.triggerFacade.allFiredTriggers().iterator();

         while(iter.hasNext()) {
            FiredTrigger ft = (FiredTrigger)iter.next();
            if (nodeLeft.equals(ft.getClientId())) {
               this.getLog().info("Found non-complete fired trigger: " + ft);
               iter.remove();
               tw = this.triggerFacade.get(ft.getTriggerKey());
               if (tw == null) {
                  this.getLog().error("no trigger found for executing trigger: " + ft.getTriggerKey());
               } else {
                  this.scheduleRecoveryIfNeeded(tw, ft);
               }
            }
         }
      } finally {
         this.unlock();
      }

      this.signaler.signalSchedulingChange(0L);
   }

   public long getEstimatedTimeToReleaseAndAcquireTrigger() {
      return this.estimatedTimeToReleaseAndAcquireTrigger;
   }

   public void setEstimatedTimeToReleaseAndAcquireTrigger(long estimate) {
      this.estimatedTimeToReleaseAndAcquireTrigger = estimate;
   }

   public void setThreadPoolSize(int size) {
   }

   public boolean isClustered() {
      throw new AssertionError();
   }

   void injectTriggerWrapper(TriggerWrapper triggerWrapper) {
      this.timeTriggers.add(triggerWrapper);
   }

   ClusterInfo getDsoCluster() {
      return this.clusterInfo;
   }

   public void onClusterEvent(ClusterEvent event) {
      switch(event.getType()) {
      case NODE_JOINED:
      case OPERATIONS_DISABLED:
      case OPERATIONS_ENABLED:
      default:
         break;
      case NODE_LEFT:
         this.getLog().info("Received node left notification for " + event.getNode().getId());
         this.nodeLeft(event);
         break;
      case NODE_REJOINED:
         this.getLog().info("Received rejoin notification " + this.terracottaClientId + " => " + event.getNode().getId());
         this.terracottaClientId = event.getNode().getId();
      }

   }

   protected TriggerFacade getTriggersFacade() {
      return this.triggerFacade;
   }

   interface TriggerRemovedFromCandidateFiringListHandler {
      boolean removeCandidateTrigger(TriggerWrapper var1);
   }

   private static class LocalLockState {
      private int acquires;
      private boolean disabled;

      private LocalLockState() {
         this.acquires = 0;
      }

      synchronized void attemptAcquireBegin() throws JobPersistenceException {
         if (this.disabled) {
            throw new JobPersistenceException("org.terracotta.quartz.TerracottaJobStore is disabled");
         } else {
            ++this.acquires;
         }
      }

      synchronized void release() {
         --this.acquires;
         this.notifyAll();
      }

      synchronized void disableLocking() throws InterruptedException {
         this.disabled = true;

         while(this.acquires > 0) {
            this.wait();
         }

      }

      // $FF: synthetic method
      LocalLockState(Object x0) {
         this();
      }
   }

   private static class ShutdownHook implements Runnable {
      private final DefaultClusteredJobStore store;

      ShutdownHook(DefaultClusteredJobStore store) {
         this.store = store;
      }

      public void run() {
         this.store.disable();
      }
   }
}
