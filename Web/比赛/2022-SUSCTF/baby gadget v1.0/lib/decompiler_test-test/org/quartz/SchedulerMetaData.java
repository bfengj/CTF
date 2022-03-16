package org.quartz;

import java.io.Serializable;
import java.util.Date;

public class SchedulerMetaData implements Serializable {
   private static final long serialVersionUID = 4203690002633917647L;
   private String schedName;
   private String schedInst;
   private Class<?> schedClass;
   private boolean isRemote;
   private boolean started;
   private boolean isInStandbyMode;
   private boolean shutdown;
   private Date startTime;
   private int numJobsExec;
   private Class<?> jsClass;
   private boolean jsPersistent;
   private boolean jsClustered;
   private Class<?> tpClass;
   private int tpSize;
   private String version;

   public SchedulerMetaData(String schedName, String schedInst, Class<?> schedClass, boolean isRemote, boolean started, boolean isInStandbyMode, boolean shutdown, Date startTime, int numJobsExec, Class<?> jsClass, boolean jsPersistent, boolean jsClustered, Class<?> tpClass, int tpSize, String version) {
      this.schedName = schedName;
      this.schedInst = schedInst;
      this.schedClass = schedClass;
      this.isRemote = isRemote;
      this.started = started;
      this.isInStandbyMode = isInStandbyMode;
      this.shutdown = shutdown;
      this.startTime = startTime;
      this.numJobsExec = numJobsExec;
      this.jsClass = jsClass;
      this.jsPersistent = jsPersistent;
      this.jsClustered = jsClustered;
      this.tpClass = tpClass;
      this.tpSize = tpSize;
      this.version = version;
   }

   public String getSchedulerName() {
      return this.schedName;
   }

   public String getSchedulerInstanceId() {
      return this.schedInst;
   }

   public Class<?> getSchedulerClass() {
      return this.schedClass;
   }

   public Date getRunningSince() {
      return this.startTime;
   }

   public int getNumberOfJobsExecuted() {
      return this.numJobsExec;
   }

   public boolean isSchedulerRemote() {
      return this.isRemote;
   }

   public boolean isStarted() {
      return this.started;
   }

   public boolean isInStandbyMode() {
      return this.isInStandbyMode;
   }

   public boolean isShutdown() {
      return this.shutdown;
   }

   public Class<?> getJobStoreClass() {
      return this.jsClass;
   }

   public boolean isJobStoreSupportsPersistence() {
      return this.jsPersistent;
   }

   public boolean isJobStoreClustered() {
      return this.jsClustered;
   }

   public Class<?> getThreadPoolClass() {
      return this.tpClass;
   }

   public int getThreadPoolSize() {
      return this.tpSize;
   }

   public String getVersion() {
      return this.version;
   }

   public String toString() {
      try {
         return this.getSummary();
      } catch (SchedulerException var2) {
         return "SchedulerMetaData: undeterminable.";
      }
   }

   public String getSummary() throws SchedulerException {
      StringBuilder str = new StringBuilder("Quartz Scheduler (v");
      str.append(this.getVersion());
      str.append(") '");
      str.append(this.getSchedulerName());
      str.append("' with instanceId '");
      str.append(this.getSchedulerInstanceId());
      str.append("'\n");
      str.append("  Scheduler class: '");
      str.append(this.getSchedulerClass().getName());
      str.append("'");
      if (this.isSchedulerRemote()) {
         str.append(" - access via RMI.");
      } else {
         str.append(" - running locally.");
      }

      str.append("\n");
      if (!this.isShutdown()) {
         if (this.getRunningSince() != null) {
            str.append("  Running since: ");
            str.append(this.getRunningSince());
         } else {
            str.append("  NOT STARTED.");
         }

         str.append("\n");
         if (this.isInStandbyMode()) {
            str.append("  Currently in standby mode.");
         } else {
            str.append("  Not currently in standby mode.");
         }
      } else {
         str.append("  Scheduler has been SHUTDOWN.");
      }

      str.append("\n");
      str.append("  Number of jobs executed: ");
      str.append(this.getNumberOfJobsExecuted());
      str.append("\n");
      str.append("  Using thread pool '");
      str.append(this.getThreadPoolClass().getName());
      str.append("' - with ");
      str.append(this.getThreadPoolSize());
      str.append(" threads.");
      str.append("\n");
      str.append("  Using job-store '");
      str.append(this.getJobStoreClass().getName());
      str.append("' - which ");
      if (this.isJobStoreSupportsPersistence()) {
         str.append("supports persistence.");
      } else {
         str.append("does not support persistence.");
      }

      if (this.isJobStoreClustered()) {
         str.append(" and is clustered.");
      } else {
         str.append(" and is not clustered.");
      }

      str.append("\n");
      return str.toString();
   }
}
