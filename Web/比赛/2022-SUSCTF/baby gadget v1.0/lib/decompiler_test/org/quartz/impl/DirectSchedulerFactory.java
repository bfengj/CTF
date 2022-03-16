package org.quartz.impl;

import java.util.Collection;
import java.util.Iterator;
import java.util.Map;
import java.util.Map.Entry;
import org.quartz.Scheduler;
import org.quartz.SchedulerException;
import org.quartz.SchedulerFactory;
import org.quartz.core.JobRunShellFactory;
import org.quartz.core.QuartzScheduler;
import org.quartz.core.QuartzSchedulerResources;
import org.quartz.simpl.CascadingClassLoadHelper;
import org.quartz.simpl.RAMJobStore;
import org.quartz.simpl.SimpleThreadPool;
import org.quartz.spi.ClassLoadHelper;
import org.quartz.spi.JobStore;
import org.quartz.spi.SchedulerPlugin;
import org.quartz.spi.ThreadExecutor;
import org.quartz.spi.ThreadPool;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

public class DirectSchedulerFactory implements SchedulerFactory {
   public static final String DEFAULT_INSTANCE_ID = "SIMPLE_NON_CLUSTERED";
   public static final String DEFAULT_SCHEDULER_NAME = "SimpleQuartzScheduler";
   private static final boolean DEFAULT_JMX_EXPORT = false;
   private static final String DEFAULT_JMX_OBJECTNAME = null;
   private static final DefaultThreadExecutor DEFAULT_THREAD_EXECUTOR = new DefaultThreadExecutor();
   private static final int DEFAULT_BATCH_MAX_SIZE = 1;
   private static final long DEFAULT_BATCH_TIME_WINDOW = 0L;
   private boolean initialized = false;
   private static DirectSchedulerFactory instance = new DirectSchedulerFactory();
   private final Logger log = LoggerFactory.getLogger(this.getClass());

   protected Logger getLog() {
      return this.log;
   }

   protected DirectSchedulerFactory() {
   }

   public static DirectSchedulerFactory getInstance() {
      return instance;
   }

   public void createVolatileScheduler(int maxThreads) throws SchedulerException {
      SimpleThreadPool threadPool = new SimpleThreadPool(maxThreads, 5);
      threadPool.initialize();
      JobStore jobStore = new RAMJobStore();
      this.createScheduler(threadPool, jobStore);
   }

   public void createRemoteScheduler(String rmiHost, int rmiPort) throws SchedulerException {
      this.createRemoteScheduler("SimpleQuartzScheduler", "SIMPLE_NON_CLUSTERED", rmiHost, rmiPort);
   }

   public void createRemoteScheduler(String schedulerName, String schedulerInstanceId, String rmiHost, int rmiPort) throws SchedulerException {
      this.createRemoteScheduler(schedulerName, schedulerInstanceId, (String)null, rmiHost, rmiPort);
   }

   public void createRemoteScheduler(String schedulerName, String schedulerInstanceId, String rmiBindName, String rmiHost, int rmiPort) throws SchedulerException {
      String uid = rmiBindName != null ? rmiBindName : QuartzSchedulerResources.getUniqueIdentifier(schedulerName, schedulerInstanceId);
      RemoteScheduler remoteScheduler = new RemoteScheduler(uid, rmiHost, rmiPort);
      SchedulerRepository schedRep = SchedulerRepository.getInstance();
      schedRep.bind(remoteScheduler);
      this.initialized = true;
   }

   public void createScheduler(ThreadPool threadPool, JobStore jobStore) throws SchedulerException {
      this.createScheduler("SimpleQuartzScheduler", "SIMPLE_NON_CLUSTERED", threadPool, jobStore);
   }

   public void createScheduler(String schedulerName, String schedulerInstanceId, ThreadPool threadPool, JobStore jobStore) throws SchedulerException {
      this.createScheduler(schedulerName, schedulerInstanceId, threadPool, jobStore, (String)null, 0, -1L, -1L);
   }

   public void createScheduler(String schedulerName, String schedulerInstanceId, ThreadPool threadPool, JobStore jobStore, String rmiRegistryHost, int rmiRegistryPort, long idleWaitTime, long dbFailureRetryInterval) throws SchedulerException {
      this.createScheduler(schedulerName, schedulerInstanceId, threadPool, jobStore, (Map)null, rmiRegistryHost, rmiRegistryPort, idleWaitTime, dbFailureRetryInterval, false, DEFAULT_JMX_OBJECTNAME);
   }

   public void createScheduler(String schedulerName, String schedulerInstanceId, ThreadPool threadPool, JobStore jobStore, Map<String, SchedulerPlugin> schedulerPluginMap, String rmiRegistryHost, int rmiRegistryPort, long idleWaitTime, long dbFailureRetryInterval, boolean jmxExport, String jmxObjectName) throws SchedulerException {
      this.createScheduler(schedulerName, schedulerInstanceId, threadPool, DEFAULT_THREAD_EXECUTOR, jobStore, schedulerPluginMap, rmiRegistryHost, rmiRegistryPort, idleWaitTime, dbFailureRetryInterval, jmxExport, jmxObjectName);
   }

   public void createScheduler(String schedulerName, String schedulerInstanceId, ThreadPool threadPool, ThreadExecutor threadExecutor, JobStore jobStore, Map<String, SchedulerPlugin> schedulerPluginMap, String rmiRegistryHost, int rmiRegistryPort, long idleWaitTime, long dbFailureRetryInterval, boolean jmxExport, String jmxObjectName) throws SchedulerException {
      this.createScheduler(schedulerName, schedulerInstanceId, threadPool, DEFAULT_THREAD_EXECUTOR, jobStore, schedulerPluginMap, rmiRegistryHost, rmiRegistryPort, idleWaitTime, dbFailureRetryInterval, jmxExport, jmxObjectName, 1, 0L);
   }

   public void createScheduler(String schedulerName, String schedulerInstanceId, ThreadPool threadPool, ThreadExecutor threadExecutor, JobStore jobStore, Map<String, SchedulerPlugin> schedulerPluginMap, String rmiRegistryHost, int rmiRegistryPort, long idleWaitTime, long dbFailureRetryInterval, boolean jmxExport, String jmxObjectName, int maxBatchSize, long batchTimeWindow) throws SchedulerException {
      JobRunShellFactory jrsf = new StdJobRunShellFactory();
      threadPool.initialize();
      QuartzSchedulerResources qrs = new QuartzSchedulerResources();
      qrs.setName(schedulerName);
      qrs.setInstanceId(schedulerInstanceId);
      SchedulerDetailsSetter.setDetails(threadPool, schedulerName, schedulerInstanceId);
      qrs.setJobRunShellFactory(jrsf);
      qrs.setThreadPool(threadPool);
      qrs.setThreadExecutor(threadExecutor);
      qrs.setJobStore(jobStore);
      qrs.setMaxBatchSize(maxBatchSize);
      qrs.setBatchTimeWindow(batchTimeWindow);
      qrs.setRMIRegistryHost(rmiRegistryHost);
      qrs.setRMIRegistryPort(rmiRegistryPort);
      qrs.setJMXExport(jmxExport);
      if (jmxObjectName != null) {
         qrs.setJMXObjectName(jmxObjectName);
      }

      if (schedulerPluginMap != null) {
         Iterator pluginIter = schedulerPluginMap.values().iterator();

         while(pluginIter.hasNext()) {
            qrs.addSchedulerPlugin((SchedulerPlugin)pluginIter.next());
         }
      }

      QuartzScheduler qs = new QuartzScheduler(qrs, idleWaitTime, dbFailureRetryInterval);
      ClassLoadHelper cch = new CascadingClassLoadHelper();
      cch.initialize();
      SchedulerDetailsSetter.setDetails(jobStore, schedulerName, schedulerInstanceId);
      jobStore.initialize(cch, qs.getSchedulerSignaler());
      Scheduler scheduler = new StdScheduler(qs);
      jrsf.initialize(scheduler);
      qs.initialize();
      if (schedulerPluginMap != null) {
         Iterator pluginEntryIter = schedulerPluginMap.entrySet().iterator();

         while(pluginEntryIter.hasNext()) {
            Entry<String, SchedulerPlugin> pluginEntry = (Entry)pluginEntryIter.next();
            ((SchedulerPlugin)pluginEntry.getValue()).initialize((String)pluginEntry.getKey(), scheduler, cch);
         }
      }

      this.getLog().info("Quartz scheduler '" + scheduler.getSchedulerName());
      this.getLog().info("Quartz scheduler version: " + qs.getVersion());
      SchedulerRepository schedRep = SchedulerRepository.getInstance();
      qs.addNoGCObject(schedRep);
      schedRep.bind(scheduler);
      this.initialized = true;
   }

   public Scheduler getScheduler() throws SchedulerException {
      if (!this.initialized) {
         throw new SchedulerException("you must call createRemoteScheduler or createScheduler methods before calling getScheduler()");
      } else {
         return this.getScheduler("SimpleQuartzScheduler");
      }
   }

   public Scheduler getScheduler(String schedName) throws SchedulerException {
      SchedulerRepository schedRep = SchedulerRepository.getInstance();
      return schedRep.lookup(schedName);
   }

   public Collection<Scheduler> getAllSchedulers() throws SchedulerException {
      return SchedulerRepository.getInstance().lookupAll();
   }
}
