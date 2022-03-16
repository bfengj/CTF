package org.quartz;

public interface SchedulerListener {
   void jobScheduled(Trigger var1);

   void jobUnscheduled(TriggerKey var1);

   void triggerFinalized(Trigger var1);

   void triggerPaused(TriggerKey var1);

   void triggersPaused(String var1);

   void triggerResumed(TriggerKey var1);

   void triggersResumed(String var1);

   void jobAdded(JobDetail var1);

   void jobDeleted(JobKey var1);

   void jobPaused(JobKey var1);

   void jobsPaused(String var1);

   void jobResumed(JobKey var1);

   void jobsResumed(String var1);

   void schedulerError(String var1, SchedulerException var2);

   void schedulerInStandbyMode();

   void schedulerStarted();

   void schedulerStarting();

   void schedulerShutdown();

   void schedulerShuttingdown();

   void schedulingDataCleared();
}
