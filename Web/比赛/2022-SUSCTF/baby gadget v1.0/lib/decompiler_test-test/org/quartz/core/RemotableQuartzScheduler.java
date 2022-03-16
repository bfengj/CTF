package org.quartz.core;

import java.rmi.Remote;
import java.rmi.RemoteException;
import java.util.Date;
import java.util.List;
import java.util.Map;
import java.util.Set;
import org.quartz.Calendar;
import org.quartz.JobDataMap;
import org.quartz.JobDetail;
import org.quartz.JobExecutionContext;
import org.quartz.JobKey;
import org.quartz.SchedulerContext;
import org.quartz.SchedulerException;
import org.quartz.Trigger;
import org.quartz.TriggerKey;
import org.quartz.UnableToInterruptJobException;
import org.quartz.impl.matchers.GroupMatcher;
import org.quartz.spi.OperableTrigger;

public interface RemotableQuartzScheduler extends Remote {
   String getSchedulerName() throws RemoteException;

   String getSchedulerInstanceId() throws RemoteException;

   SchedulerContext getSchedulerContext() throws SchedulerException, RemoteException;

   void start() throws SchedulerException, RemoteException;

   void startDelayed(int var1) throws SchedulerException, RemoteException;

   void standby() throws RemoteException;

   boolean isInStandbyMode() throws RemoteException;

   void shutdown() throws RemoteException;

   void shutdown(boolean var1) throws RemoteException;

   boolean isShutdown() throws RemoteException;

   Date runningSince() throws RemoteException;

   String getVersion() throws RemoteException;

   int numJobsExecuted() throws RemoteException;

   Class<?> getJobStoreClass() throws RemoteException;

   boolean supportsPersistence() throws RemoteException;

   boolean isClustered() throws RemoteException;

   Class<?> getThreadPoolClass() throws RemoteException;

   int getThreadPoolSize() throws RemoteException;

   void clear() throws SchedulerException, RemoteException;

   List<JobExecutionContext> getCurrentlyExecutingJobs() throws SchedulerException, RemoteException;

   Date scheduleJob(JobDetail var1, Trigger var2) throws SchedulerException, RemoteException;

   Date scheduleJob(Trigger var1) throws SchedulerException, RemoteException;

   void addJob(JobDetail var1, boolean var2) throws SchedulerException, RemoteException;

   void addJob(JobDetail var1, boolean var2, boolean var3) throws SchedulerException, RemoteException;

   boolean deleteJob(JobKey var1) throws SchedulerException, RemoteException;

   boolean unscheduleJob(TriggerKey var1) throws SchedulerException, RemoteException;

   Date rescheduleJob(TriggerKey var1, Trigger var2) throws SchedulerException, RemoteException;

   void triggerJob(JobKey var1, JobDataMap var2) throws SchedulerException, RemoteException;

   void triggerJob(OperableTrigger var1) throws SchedulerException, RemoteException;

   void pauseTrigger(TriggerKey var1) throws SchedulerException, RemoteException;

   void pauseTriggers(GroupMatcher<TriggerKey> var1) throws SchedulerException, RemoteException;

   void pauseJob(JobKey var1) throws SchedulerException, RemoteException;

   void pauseJobs(GroupMatcher<JobKey> var1) throws SchedulerException, RemoteException;

   void resumeTrigger(TriggerKey var1) throws SchedulerException, RemoteException;

   void resumeTriggers(GroupMatcher<TriggerKey> var1) throws SchedulerException, RemoteException;

   Set<String> getPausedTriggerGroups() throws SchedulerException, RemoteException;

   void resumeJob(JobKey var1) throws SchedulerException, RemoteException;

   void resumeJobs(GroupMatcher<JobKey> var1) throws SchedulerException, RemoteException;

   void pauseAll() throws SchedulerException, RemoteException;

   void resumeAll() throws SchedulerException, RemoteException;

   List<String> getJobGroupNames() throws SchedulerException, RemoteException;

   Set<JobKey> getJobKeys(GroupMatcher<JobKey> var1) throws SchedulerException, RemoteException;

   List<? extends Trigger> getTriggersOfJob(JobKey var1) throws SchedulerException, RemoteException;

   List<String> getTriggerGroupNames() throws SchedulerException, RemoteException;

   Set<TriggerKey> getTriggerKeys(GroupMatcher<TriggerKey> var1) throws SchedulerException, RemoteException;

   JobDetail getJobDetail(JobKey var1) throws SchedulerException, RemoteException;

   Trigger getTrigger(TriggerKey var1) throws SchedulerException, RemoteException;

   Trigger.TriggerState getTriggerState(TriggerKey var1) throws SchedulerException, RemoteException;

   void addCalendar(String var1, Calendar var2, boolean var3, boolean var4) throws SchedulerException, RemoteException;

   boolean deleteCalendar(String var1) throws SchedulerException, RemoteException;

   Calendar getCalendar(String var1) throws SchedulerException, RemoteException;

   List<String> getCalendarNames() throws SchedulerException, RemoteException;

   boolean interrupt(JobKey var1) throws UnableToInterruptJobException, RemoteException;

   boolean interrupt(String var1) throws UnableToInterruptJobException, RemoteException;

   boolean checkExists(JobKey var1) throws SchedulerException, RemoteException;

   boolean checkExists(TriggerKey var1) throws SchedulerException, RemoteException;

   boolean deleteJobs(List<JobKey> var1) throws SchedulerException, RemoteException;

   void scheduleJobs(Map<JobDetail, Set<? extends Trigger>> var1, boolean var2) throws SchedulerException, RemoteException;

   void scheduleJob(JobDetail var1, Set<? extends Trigger> var2, boolean var3) throws SchedulerException, RemoteException;

   boolean unscheduleJobs(List<TriggerKey> var1) throws SchedulerException, RemoteException;
}
