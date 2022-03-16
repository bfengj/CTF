package org.quartz.core;

import java.util.ArrayList;
import java.util.List;
import org.quartz.management.ManagementRESTServiceConfiguration;
import org.quartz.spi.JobStore;
import org.quartz.spi.SchedulerPlugin;
import org.quartz.spi.ThreadExecutor;
import org.quartz.spi.ThreadPool;

public class QuartzSchedulerResources {
   public static final String CREATE_REGISTRY_NEVER = "never";
   public static final String CREATE_REGISTRY_ALWAYS = "always";
   public static final String CREATE_REGISTRY_AS_NEEDED = "as_needed";
   private String name;
   private String instanceId;
   private String threadName;
   private String rmiRegistryHost = null;
   private int rmiRegistryPort = 1099;
   private int rmiServerPort = -1;
   private String rmiCreateRegistryStrategy = "never";
   private ThreadPool threadPool;
   private JobStore jobStore;
   private JobRunShellFactory jobRunShellFactory;
   private List<SchedulerPlugin> schedulerPlugins = new ArrayList(10);
   private boolean makeSchedulerThreadDaemon = false;
   private boolean threadsInheritInitializersClassLoadContext = false;
   private String rmiBindName;
   private boolean jmxExport;
   private String jmxObjectName;
   private ManagementRESTServiceConfiguration managementRESTServiceConfiguration;
   private ThreadExecutor threadExecutor;
   private boolean runUpdateCheck = true;
   private long batchTimeWindow = 0L;
   private int maxBatchSize = 1;
   private boolean interruptJobsOnShutdown = false;
   private boolean interruptJobsOnShutdownWithWait = false;

   public String getName() {
      return this.name;
   }

   public void setName(String name) {
      if (name != null && name.trim().length() != 0) {
         this.name = name;
         if (this.threadName == null) {
            this.setThreadName(name + "_QuartzSchedulerThread");
         }

      } else {
         throw new IllegalArgumentException("Scheduler name cannot be empty.");
      }
   }

   public String getInstanceId() {
      return this.instanceId;
   }

   public void setInstanceId(String instanceId) {
      if (instanceId != null && instanceId.trim().length() != 0) {
         this.instanceId = instanceId;
      } else {
         throw new IllegalArgumentException("Scheduler instanceId cannot be empty.");
      }
   }

   public static String getUniqueIdentifier(String schedName, String schedInstId) {
      return schedName + "_$_" + schedInstId;
   }

   public String getUniqueIdentifier() {
      return getUniqueIdentifier(this.name, this.instanceId);
   }

   public String getRMIRegistryHost() {
      return this.rmiRegistryHost;
   }

   public void setRMIRegistryHost(String hostName) {
      this.rmiRegistryHost = hostName;
   }

   public int getRMIRegistryPort() {
      return this.rmiRegistryPort;
   }

   public void setRMIRegistryPort(int port) {
      this.rmiRegistryPort = port;
   }

   public int getRMIServerPort() {
      return this.rmiServerPort;
   }

   public void setRMIServerPort(int port) {
      this.rmiServerPort = port;
   }

   public String getRMICreateRegistryStrategy() {
      return this.rmiCreateRegistryStrategy;
   }

   public String getThreadName() {
      return this.threadName;
   }

   public void setThreadName(String threadName) {
      if (threadName != null && threadName.trim().length() != 0) {
         this.threadName = threadName;
      } else {
         throw new IllegalArgumentException("Scheduler thread name cannot be empty.");
      }
   }

   public void setRMICreateRegistryStrategy(String rmiCreateRegistryStrategy) {
      if (rmiCreateRegistryStrategy != null && rmiCreateRegistryStrategy.trim().length() != 0) {
         if (rmiCreateRegistryStrategy.equalsIgnoreCase("true")) {
            rmiCreateRegistryStrategy = "as_needed";
         } else if (rmiCreateRegistryStrategy.equalsIgnoreCase("false")) {
            rmiCreateRegistryStrategy = "never";
         } else if (rmiCreateRegistryStrategy.equalsIgnoreCase("always")) {
            rmiCreateRegistryStrategy = "always";
         } else if (rmiCreateRegistryStrategy.equalsIgnoreCase("as_needed")) {
            rmiCreateRegistryStrategy = "as_needed";
         } else {
            if (!rmiCreateRegistryStrategy.equalsIgnoreCase("never")) {
               throw new IllegalArgumentException("Faild to set RMICreateRegistryStrategy - strategy unknown: '" + rmiCreateRegistryStrategy + "'");
            }

            rmiCreateRegistryStrategy = "never";
         }
      } else {
         rmiCreateRegistryStrategy = "never";
      }

      this.rmiCreateRegistryStrategy = rmiCreateRegistryStrategy;
   }

   public ThreadPool getThreadPool() {
      return this.threadPool;
   }

   public void setThreadPool(ThreadPool threadPool) {
      if (threadPool == null) {
         throw new IllegalArgumentException("ThreadPool cannot be null.");
      } else {
         this.threadPool = threadPool;
      }
   }

   public JobStore getJobStore() {
      return this.jobStore;
   }

   public void setJobStore(JobStore jobStore) {
      if (jobStore == null) {
         throw new IllegalArgumentException("JobStore cannot be null.");
      } else {
         this.jobStore = jobStore;
      }
   }

   public JobRunShellFactory getJobRunShellFactory() {
      return this.jobRunShellFactory;
   }

   public void setJobRunShellFactory(JobRunShellFactory jobRunShellFactory) {
      if (jobRunShellFactory == null) {
         throw new IllegalArgumentException("JobRunShellFactory cannot be null.");
      } else {
         this.jobRunShellFactory = jobRunShellFactory;
      }
   }

   public void addSchedulerPlugin(SchedulerPlugin plugin) {
      this.schedulerPlugins.add(plugin);
   }

   public List<SchedulerPlugin> getSchedulerPlugins() {
      return this.schedulerPlugins;
   }

   public boolean getMakeSchedulerThreadDaemon() {
      return this.makeSchedulerThreadDaemon;
   }

   public void setMakeSchedulerThreadDaemon(boolean makeSchedulerThreadDaemon) {
      this.makeSchedulerThreadDaemon = makeSchedulerThreadDaemon;
   }

   public boolean isThreadsInheritInitializersClassLoadContext() {
      return this.threadsInheritInitializersClassLoadContext;
   }

   public void setThreadsInheritInitializersClassLoadContext(boolean threadsInheritInitializersClassLoadContext) {
      this.threadsInheritInitializersClassLoadContext = threadsInheritInitializersClassLoadContext;
   }

   public String getRMIBindName() {
      return this.rmiBindName == null ? this.getUniqueIdentifier() : this.rmiBindName;
   }

   public void setRMIBindName(String rmiBindName) {
      this.rmiBindName = rmiBindName;
   }

   public boolean getJMXExport() {
      return this.jmxExport;
   }

   public void setJMXExport(boolean jmxExport) {
      this.jmxExport = jmxExport;
   }

   public String getJMXObjectName() {
      return this.jmxObjectName == null ? generateJMXObjectName(this.name, this.instanceId) : this.jmxObjectName;
   }

   public void setJMXObjectName(String jmxObjectName) {
      this.jmxObjectName = jmxObjectName;
   }

   public ThreadExecutor getThreadExecutor() {
      return this.threadExecutor;
   }

   public void setThreadExecutor(ThreadExecutor threadExecutor) {
      this.threadExecutor = threadExecutor;
   }

   public static String generateJMXObjectName(String schedName, String schedInstId) {
      return "quartz:type=QuartzScheduler,name=" + schedName.replaceAll(":|=|\n", ".") + ",instance=" + schedInstId;
   }

   public boolean isRunUpdateCheck() {
      return this.runUpdateCheck;
   }

   public void setRunUpdateCheck(boolean runUpdateCheck) {
      this.runUpdateCheck = runUpdateCheck;
   }

   public long getBatchTimeWindow() {
      return this.batchTimeWindow;
   }

   public void setBatchTimeWindow(long batchTimeWindow) {
      this.batchTimeWindow = batchTimeWindow;
   }

   public int getMaxBatchSize() {
      return this.maxBatchSize;
   }

   public void setMaxBatchSize(int maxBatchSize) {
      this.maxBatchSize = maxBatchSize;
   }

   public boolean isInterruptJobsOnShutdown() {
      return this.interruptJobsOnShutdown;
   }

   public void setInterruptJobsOnShutdown(boolean interruptJobsOnShutdown) {
      this.interruptJobsOnShutdown = interruptJobsOnShutdown;
   }

   public boolean isInterruptJobsOnShutdownWithWait() {
      return this.interruptJobsOnShutdownWithWait;
   }

   public void setInterruptJobsOnShutdownWithWait(boolean interruptJobsOnShutdownWithWait) {
      this.interruptJobsOnShutdownWithWait = interruptJobsOnShutdownWithWait;
   }

   public ManagementRESTServiceConfiguration getManagementRESTServiceConfiguration() {
      return this.managementRESTServiceConfiguration;
   }

   public void setManagementRESTServiceConfiguration(ManagementRESTServiceConfiguration managementRESTServiceConfiguration) {
      this.managementRESTServiceConfiguration = managementRESTServiceConfiguration;
   }
}
