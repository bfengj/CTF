package org.quartz;

import java.util.Date;

public interface JobExecutionContext {
   Scheduler getScheduler();

   Trigger getTrigger();

   Calendar getCalendar();

   boolean isRecovering();

   TriggerKey getRecoveringTriggerKey() throws IllegalStateException;

   int getRefireCount();

   JobDataMap getMergedJobDataMap();

   JobDetail getJobDetail();

   Job getJobInstance();

   Date getFireTime();

   Date getScheduledFireTime();

   Date getPreviousFireTime();

   Date getNextFireTime();

   String getFireInstanceId();

   Object getResult();

   void setResult(Object var1);

   long getJobRunTime();

   void put(Object var1, Object var2);

   Object get(Object var1);
}
