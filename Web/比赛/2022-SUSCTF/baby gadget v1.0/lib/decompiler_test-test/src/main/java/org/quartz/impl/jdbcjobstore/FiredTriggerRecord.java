package org.quartz.impl.jdbcjobstore;

import java.io.Serializable;
import org.quartz.JobKey;
import org.quartz.TriggerKey;

public class FiredTriggerRecord implements Serializable {
   private static final long serialVersionUID = -7183096398865657533L;
   private String fireInstanceId;
   private long fireTimestamp;
   private long scheduleTimestamp;
   private String schedulerInstanceId;
   private TriggerKey triggerKey;
   private String fireInstanceState;
   private JobKey jobKey;
   private boolean jobDisallowsConcurrentExecution;
   private boolean jobRequestsRecovery;
   private int priority;

   public String getFireInstanceId() {
      return this.fireInstanceId;
   }

   public long getFireTimestamp() {
      return this.fireTimestamp;
   }

   public long getScheduleTimestamp() {
      return this.scheduleTimestamp;
   }

   public boolean isJobDisallowsConcurrentExecution() {
      return this.jobDisallowsConcurrentExecution;
   }

   public JobKey getJobKey() {
      return this.jobKey;
   }

   public String getSchedulerInstanceId() {
      return this.schedulerInstanceId;
   }

   public TriggerKey getTriggerKey() {
      return this.triggerKey;
   }

   public String getFireInstanceState() {
      return this.fireInstanceState;
   }

   public void setFireInstanceId(String string) {
      this.fireInstanceId = string;
   }

   public void setFireTimestamp(long l) {
      this.fireTimestamp = l;
   }

   public void setScheduleTimestamp(long l) {
      this.scheduleTimestamp = l;
   }

   public void setJobDisallowsConcurrentExecution(boolean b) {
      this.jobDisallowsConcurrentExecution = b;
   }

   public void setJobKey(JobKey key) {
      this.jobKey = key;
   }

   public void setSchedulerInstanceId(String string) {
      this.schedulerInstanceId = string;
   }

   public void setTriggerKey(TriggerKey key) {
      this.triggerKey = key;
   }

   public void setFireInstanceState(String string) {
      this.fireInstanceState = string;
   }

   public boolean isJobRequestsRecovery() {
      return this.jobRequestsRecovery;
   }

   public void setJobRequestsRecovery(boolean b) {
      this.jobRequestsRecovery = b;
   }

   public int getPriority() {
      return this.priority;
   }

   public void setPriority(int priority) {
      this.priority = priority;
   }
}
