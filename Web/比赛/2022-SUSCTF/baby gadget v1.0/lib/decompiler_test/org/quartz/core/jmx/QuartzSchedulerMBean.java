package org.quartz.core.jmx;

import java.util.Date;
import java.util.List;
import java.util.Map;
import java.util.Set;
import javax.management.openmbean.CompositeData;
import javax.management.openmbean.TabularData;

public interface QuartzSchedulerMBean {
   String SCHEDULER_STARTED = "schedulerStarted";
   String SCHEDULER_PAUSED = "schedulerPaused";
   String SCHEDULER_SHUTDOWN = "schedulerShutdown";
   String SCHEDULER_ERROR = "schedulerError";
   String JOB_ADDED = "jobAdded";
   String JOB_DELETED = "jobDeleted";
   String JOB_SCHEDULED = "jobScheduled";
   String JOB_UNSCHEDULED = "jobUnscheduled";
   String JOBS_PAUSED = "jobsPaused";
   String JOBS_RESUMED = "jobsResumed";
   String JOB_EXECUTION_VETOED = "jobExecutionVetoed";
   String JOB_TO_BE_EXECUTED = "jobToBeExecuted";
   String JOB_WAS_EXECUTED = "jobWasExecuted";
   String TRIGGER_FINALIZED = "triggerFinalized";
   String TRIGGERS_PAUSED = "triggersPaused";
   String TRIGGERS_RESUMED = "triggersResumed";
   String SCHEDULING_DATA_CLEARED = "schedulingDataCleared";
   String SAMPLED_STATISTICS_ENABLED = "sampledStatisticsEnabled";
   String SAMPLED_STATISTICS_RESET = "sampledStatisticsReset";

   String getSchedulerName();

   String getSchedulerInstanceId();

   boolean isStandbyMode();

   boolean isShutdown();

   String getVersion();

   String getJobStoreClassName();

   String getThreadPoolClassName();

   int getThreadPoolSize();

   long getJobsScheduledMostRecentSample();

   long getJobsExecutedMostRecentSample();

   long getJobsCompletedMostRecentSample();

   Map<String, Long> getPerformanceMetrics();

   TabularData getCurrentlyExecutingJobs() throws Exception;

   TabularData getAllJobDetails() throws Exception;

   List<CompositeData> getAllTriggers() throws Exception;

   List<String> getJobGroupNames() throws Exception;

   List<String> getJobNames(String var1) throws Exception;

   CompositeData getJobDetail(String var1, String var2) throws Exception;

   boolean isStarted();

   void start() throws Exception;

   void shutdown();

   void standby();

   void clear() throws Exception;

   Date scheduleJob(String var1, String var2, String var3, String var4) throws Exception;

   void scheduleBasicJob(Map<String, Object> var1, Map<String, Object> var2) throws Exception;

   void scheduleJob(Map<String, Object> var1, Map<String, Object> var2) throws Exception;

   void scheduleJob(String var1, String var2, Map<String, Object> var3) throws Exception;

   boolean unscheduleJob(String var1, String var2) throws Exception;

   boolean interruptJob(String var1, String var2) throws Exception;

   boolean interruptJob(String var1) throws Exception;

   void triggerJob(String var1, String var2, Map<String, String> var3) throws Exception;

   boolean deleteJob(String var1, String var2) throws Exception;

   void addJob(CompositeData var1, boolean var2) throws Exception;

   void addJob(Map<String, Object> var1, boolean var2) throws Exception;

   void pauseJobGroup(String var1) throws Exception;

   void pauseJobsStartingWith(String var1) throws Exception;

   void pauseJobsEndingWith(String var1) throws Exception;

   void pauseJobsContaining(String var1) throws Exception;

   void pauseJobsAll() throws Exception;

   void resumeJobGroup(String var1) throws Exception;

   void resumeJobsStartingWith(String var1) throws Exception;

   void resumeJobsEndingWith(String var1) throws Exception;

   void resumeJobsContaining(String var1) throws Exception;

   void resumeJobsAll() throws Exception;

   void pauseJob(String var1, String var2) throws Exception;

   void resumeJob(String var1, String var2) throws Exception;

   List<String> getTriggerGroupNames() throws Exception;

   List<String> getTriggerNames(String var1) throws Exception;

   CompositeData getTrigger(String var1, String var2) throws Exception;

   String getTriggerState(String var1, String var2) throws Exception;

   List<CompositeData> getTriggersOfJob(String var1, String var2) throws Exception;

   Set<String> getPausedTriggerGroups() throws Exception;

   void pauseAllTriggers() throws Exception;

   void resumeAllTriggers() throws Exception;

   void pauseTriggerGroup(String var1) throws Exception;

   void pauseTriggersStartingWith(String var1) throws Exception;

   void pauseTriggersEndingWith(String var1) throws Exception;

   void pauseTriggersContaining(String var1) throws Exception;

   void pauseTriggersAll() throws Exception;

   void resumeTriggerGroup(String var1) throws Exception;

   void resumeTriggersStartingWith(String var1) throws Exception;

   void resumeTriggersEndingWith(String var1) throws Exception;

   void resumeTriggersContaining(String var1) throws Exception;

   void resumeTriggersAll() throws Exception;

   void pauseTrigger(String var1, String var2) throws Exception;

   void resumeTrigger(String var1, String var2) throws Exception;

   List<String> getCalendarNames() throws Exception;

   void deleteCalendar(String var1) throws Exception;

   void setSampledStatisticsEnabled(boolean var1);

   boolean isSampledStatisticsEnabled();
}
