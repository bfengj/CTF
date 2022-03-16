package org.quartz.spi;

import java.util.Collection;
import java.util.List;
import java.util.Map;
import java.util.Set;
import org.quartz.Calendar;
import org.quartz.JobDetail;
import org.quartz.JobKey;
import org.quartz.JobPersistenceException;
import org.quartz.ObjectAlreadyExistsException;
import org.quartz.SchedulerConfigException;
import org.quartz.SchedulerException;
import org.quartz.Trigger;
import org.quartz.TriggerKey;
import org.quartz.impl.matchers.GroupMatcher;

public interface JobStore {
   void initialize(ClassLoadHelper var1, SchedulerSignaler var2) throws SchedulerConfigException;

   void schedulerStarted() throws SchedulerException;

   void schedulerPaused();

   void schedulerResumed();

   void shutdown();

   boolean supportsPersistence();

   long getEstimatedTimeToReleaseAndAcquireTrigger();

   boolean isClustered();

   void storeJobAndTrigger(JobDetail var1, OperableTrigger var2) throws ObjectAlreadyExistsException, JobPersistenceException;

   void storeJob(JobDetail var1, boolean var2) throws ObjectAlreadyExistsException, JobPersistenceException;

   void storeJobsAndTriggers(Map<JobDetail, Set<? extends Trigger>> var1, boolean var2) throws ObjectAlreadyExistsException, JobPersistenceException;

   boolean removeJob(JobKey var1) throws JobPersistenceException;

   boolean removeJobs(List<JobKey> var1) throws JobPersistenceException;

   JobDetail retrieveJob(JobKey var1) throws JobPersistenceException;

   void storeTrigger(OperableTrigger var1, boolean var2) throws ObjectAlreadyExistsException, JobPersistenceException;

   boolean removeTrigger(TriggerKey var1) throws JobPersistenceException;

   boolean removeTriggers(List<TriggerKey> var1) throws JobPersistenceException;

   boolean replaceTrigger(TriggerKey var1, OperableTrigger var2) throws JobPersistenceException;

   OperableTrigger retrieveTrigger(TriggerKey var1) throws JobPersistenceException;

   boolean checkExists(JobKey var1) throws JobPersistenceException;

   boolean checkExists(TriggerKey var1) throws JobPersistenceException;

   void clearAllSchedulingData() throws JobPersistenceException;

   void storeCalendar(String var1, Calendar var2, boolean var3, boolean var4) throws ObjectAlreadyExistsException, JobPersistenceException;

   boolean removeCalendar(String var1) throws JobPersistenceException;

   Calendar retrieveCalendar(String var1) throws JobPersistenceException;

   int getNumberOfJobs() throws JobPersistenceException;

   int getNumberOfTriggers() throws JobPersistenceException;

   int getNumberOfCalendars() throws JobPersistenceException;

   Set<JobKey> getJobKeys(GroupMatcher<JobKey> var1) throws JobPersistenceException;

   Set<TriggerKey> getTriggerKeys(GroupMatcher<TriggerKey> var1) throws JobPersistenceException;

   List<String> getJobGroupNames() throws JobPersistenceException;

   List<String> getTriggerGroupNames() throws JobPersistenceException;

   List<String> getCalendarNames() throws JobPersistenceException;

   List<OperableTrigger> getTriggersForJob(JobKey var1) throws JobPersistenceException;

   Trigger.TriggerState getTriggerState(TriggerKey var1) throws JobPersistenceException;

   void pauseTrigger(TriggerKey var1) throws JobPersistenceException;

   Collection<String> pauseTriggers(GroupMatcher<TriggerKey> var1) throws JobPersistenceException;

   void pauseJob(JobKey var1) throws JobPersistenceException;

   Collection<String> pauseJobs(GroupMatcher<JobKey> var1) throws JobPersistenceException;

   void resumeTrigger(TriggerKey var1) throws JobPersistenceException;

   Collection<String> resumeTriggers(GroupMatcher<TriggerKey> var1) throws JobPersistenceException;

   Set<String> getPausedTriggerGroups() throws JobPersistenceException;

   void resumeJob(JobKey var1) throws JobPersistenceException;

   Collection<String> resumeJobs(GroupMatcher<JobKey> var1) throws JobPersistenceException;

   void pauseAll() throws JobPersistenceException;

   void resumeAll() throws JobPersistenceException;

   List<OperableTrigger> acquireNextTriggers(long var1, int var3, long var4) throws JobPersistenceException;

   void releaseAcquiredTrigger(OperableTrigger var1);

   List<TriggerFiredResult> triggersFired(List<OperableTrigger> var1) throws JobPersistenceException;

   void triggeredJobComplete(OperableTrigger var1, JobDetail var2, Trigger.CompletedExecutionInstruction var3);

   void setInstanceId(String var1);

   void setInstanceName(String var1);

   void setThreadPoolSize(int var1);
}
