package org.quartz;

import java.util.Date;
import java.util.List;
import java.util.Map;
import java.util.Set;
import org.quartz.impl.matchers.GroupMatcher;
import org.quartz.spi.JobFactory;

public interface Scheduler {
   String DEFAULT_GROUP = "DEFAULT";
   String DEFAULT_RECOVERY_GROUP = "RECOVERING_JOBS";
   String DEFAULT_FAIL_OVER_GROUP = "FAILED_OVER_JOBS";
   String FAILED_JOB_ORIGINAL_TRIGGER_NAME = "QRTZ_FAILED_JOB_ORIG_TRIGGER_NAME";
   String FAILED_JOB_ORIGINAL_TRIGGER_GROUP = "QRTZ_FAILED_JOB_ORIG_TRIGGER_GROUP";
   String FAILED_JOB_ORIGINAL_TRIGGER_FIRETIME_IN_MILLISECONDS = "QRTZ_FAILED_JOB_ORIG_TRIGGER_FIRETIME_IN_MILLISECONDS_AS_STRING";
   String FAILED_JOB_ORIGINAL_TRIGGER_SCHEDULED_FIRETIME_IN_MILLISECONDS = "QRTZ_FAILED_JOB_ORIG_TRIGGER_SCHEDULED_FIRETIME_IN_MILLISECONDS_AS_STRING";

   String getSchedulerName() throws SchedulerException;

   String getSchedulerInstanceId() throws SchedulerException;

   SchedulerContext getContext() throws SchedulerException;

   void start() throws SchedulerException;

   void startDelayed(int var1) throws SchedulerException;

   boolean isStarted() throws SchedulerException;

   void standby() throws SchedulerException;

   boolean isInStandbyMode() throws SchedulerException;

   void shutdown() throws SchedulerException;

   void shutdown(boolean var1) throws SchedulerException;

   boolean isShutdown() throws SchedulerException;

   SchedulerMetaData getMetaData() throws SchedulerException;

   List<JobExecutionContext> getCurrentlyExecutingJobs() throws SchedulerException;

   void setJobFactory(JobFactory var1) throws SchedulerException;

   ListenerManager getListenerManager() throws SchedulerException;

   Date scheduleJob(JobDetail var1, Trigger var2) throws SchedulerException;

   Date scheduleJob(Trigger var1) throws SchedulerException;

   void scheduleJobs(Map<JobDetail, Set<? extends Trigger>> var1, boolean var2) throws SchedulerException;

   void scheduleJob(JobDetail var1, Set<? extends Trigger> var2, boolean var3) throws SchedulerException;

   boolean unscheduleJob(TriggerKey var1) throws SchedulerException;

   boolean unscheduleJobs(List<TriggerKey> var1) throws SchedulerException;

   Date rescheduleJob(TriggerKey var1, Trigger var2) throws SchedulerException;

   void addJob(JobDetail var1, boolean var2) throws SchedulerException;

   void addJob(JobDetail var1, boolean var2, boolean var3) throws SchedulerException;

   boolean deleteJob(JobKey var1) throws SchedulerException;

   boolean deleteJobs(List<JobKey> var1) throws SchedulerException;

   void triggerJob(JobKey var1) throws SchedulerException;

   void triggerJob(JobKey var1, JobDataMap var2) throws SchedulerException;

   void pauseJob(JobKey var1) throws SchedulerException;

   void pauseJobs(GroupMatcher<JobKey> var1) throws SchedulerException;

   void pauseTrigger(TriggerKey var1) throws SchedulerException;

   void pauseTriggers(GroupMatcher<TriggerKey> var1) throws SchedulerException;

   void resumeJob(JobKey var1) throws SchedulerException;

   void resumeJobs(GroupMatcher<JobKey> var1) throws SchedulerException;

   void resumeTrigger(TriggerKey var1) throws SchedulerException;

   void resumeTriggers(GroupMatcher<TriggerKey> var1) throws SchedulerException;

   void pauseAll() throws SchedulerException;

   void resumeAll() throws SchedulerException;

   List<String> getJobGroupNames() throws SchedulerException;

   Set<JobKey> getJobKeys(GroupMatcher<JobKey> var1) throws SchedulerException;

   List<? extends Trigger> getTriggersOfJob(JobKey var1) throws SchedulerException;

   List<String> getTriggerGroupNames() throws SchedulerException;

   Set<TriggerKey> getTriggerKeys(GroupMatcher<TriggerKey> var1) throws SchedulerException;

   Set<String> getPausedTriggerGroups() throws SchedulerException;

   JobDetail getJobDetail(JobKey var1) throws SchedulerException;

   Trigger getTrigger(TriggerKey var1) throws SchedulerException;

   Trigger.TriggerState getTriggerState(TriggerKey var1) throws SchedulerException;

   void addCalendar(String var1, Calendar var2, boolean var3, boolean var4) throws SchedulerException;

   boolean deleteCalendar(String var1) throws SchedulerException;

   Calendar getCalendar(String var1) throws SchedulerException;

   List<String> getCalendarNames() throws SchedulerException;

   boolean interrupt(JobKey var1) throws UnableToInterruptJobException;

   boolean interrupt(String var1) throws UnableToInterruptJobException;

   boolean checkExists(JobKey var1) throws SchedulerException;

   boolean checkExists(TriggerKey var1) throws SchedulerException;

   void clear() throws SchedulerException;
}
