package org.quartz.impl;

import java.util.Date;
import java.util.List;
import java.util.Map;
import java.util.Set;
import org.quartz.Calendar;
import org.quartz.JobDataMap;
import org.quartz.JobDetail;
import org.quartz.JobExecutionContext;
import org.quartz.JobKey;
import org.quartz.ListenerManager;
import org.quartz.Scheduler;
import org.quartz.SchedulerContext;
import org.quartz.SchedulerException;
import org.quartz.SchedulerMetaData;
import org.quartz.Trigger;
import org.quartz.TriggerKey;
import org.quartz.UnableToInterruptJobException;
import org.quartz.core.QuartzScheduler;
import org.quartz.impl.matchers.GroupMatcher;
import org.quartz.spi.JobFactory;

public class StdScheduler implements Scheduler {
   private QuartzScheduler sched;

   public StdScheduler(QuartzScheduler sched) {
      this.sched = sched;
   }

   public String getSchedulerName() {
      return this.sched.getSchedulerName();
   }

   public String getSchedulerInstanceId() {
      return this.sched.getSchedulerInstanceId();
   }

   public SchedulerMetaData getMetaData() {
      return new SchedulerMetaData(this.getSchedulerName(), this.getSchedulerInstanceId(), this.getClass(), false, this.isStarted(), this.isInStandbyMode(), this.isShutdown(), this.sched.runningSince(), this.sched.numJobsExecuted(), this.sched.getJobStoreClass(), this.sched.supportsPersistence(), this.sched.isClustered(), this.sched.getThreadPoolClass(), this.sched.getThreadPoolSize(), this.sched.getVersion());
   }

   public SchedulerContext getContext() throws SchedulerException {
      return this.sched.getSchedulerContext();
   }

   public void start() throws SchedulerException {
      this.sched.start();
   }

   public void startDelayed(int seconds) throws SchedulerException {
      this.sched.startDelayed(seconds);
   }

   public void standby() {
      this.sched.standby();
   }

   public boolean isStarted() {
      return this.sched.runningSince() != null;
   }

   public boolean isInStandbyMode() {
      return this.sched.isInStandbyMode();
   }

   public void shutdown() {
      this.sched.shutdown();
   }

   public void shutdown(boolean waitForJobsToComplete) {
      this.sched.shutdown(waitForJobsToComplete);
   }

   public boolean isShutdown() {
      return this.sched.isShutdown();
   }

   public List<JobExecutionContext> getCurrentlyExecutingJobs() {
      return this.sched.getCurrentlyExecutingJobs();
   }

   public void clear() throws SchedulerException {
      this.sched.clear();
   }

   public Date scheduleJob(JobDetail jobDetail, Trigger trigger) throws SchedulerException {
      return this.sched.scheduleJob(jobDetail, trigger);
   }

   public Date scheduleJob(Trigger trigger) throws SchedulerException {
      return this.sched.scheduleJob(trigger);
   }

   public void addJob(JobDetail jobDetail, boolean replace) throws SchedulerException {
      this.sched.addJob(jobDetail, replace);
   }

   public void addJob(JobDetail jobDetail, boolean replace, boolean storeNonDurableWhileAwaitingScheduling) throws SchedulerException {
      this.sched.addJob(jobDetail, replace, storeNonDurableWhileAwaitingScheduling);
   }

   public boolean deleteJobs(List<JobKey> jobKeys) throws SchedulerException {
      return this.sched.deleteJobs(jobKeys);
   }

   public void scheduleJobs(Map<JobDetail, Set<? extends Trigger>> triggersAndJobs, boolean replace) throws SchedulerException {
      this.sched.scheduleJobs(triggersAndJobs, replace);
   }

   public void scheduleJob(JobDetail jobDetail, Set<? extends Trigger> triggersForJob, boolean replace) throws SchedulerException {
      this.sched.scheduleJob(jobDetail, triggersForJob, replace);
   }

   public boolean unscheduleJobs(List<TriggerKey> triggerKeys) throws SchedulerException {
      return this.sched.unscheduleJobs(triggerKeys);
   }

   public boolean deleteJob(JobKey jobKey) throws SchedulerException {
      return this.sched.deleteJob(jobKey);
   }

   public boolean unscheduleJob(TriggerKey triggerKey) throws SchedulerException {
      return this.sched.unscheduleJob(triggerKey);
   }

   public Date rescheduleJob(TriggerKey triggerKey, Trigger newTrigger) throws SchedulerException {
      return this.sched.rescheduleJob(triggerKey, newTrigger);
   }

   public void triggerJob(JobKey jobKey) throws SchedulerException {
      this.triggerJob(jobKey, (JobDataMap)null);
   }

   public void triggerJob(JobKey jobKey, JobDataMap data) throws SchedulerException {
      this.sched.triggerJob(jobKey, data);
   }

   public void pauseTrigger(TriggerKey triggerKey) throws SchedulerException {
      this.sched.pauseTrigger(triggerKey);
   }

   public void pauseTriggers(GroupMatcher<TriggerKey> matcher) throws SchedulerException {
      this.sched.pauseTriggers(matcher);
   }

   public void pauseJob(JobKey jobKey) throws SchedulerException {
      this.sched.pauseJob(jobKey);
   }

   public Set<String> getPausedTriggerGroups() throws SchedulerException {
      return this.sched.getPausedTriggerGroups();
   }

   public void pauseJobs(GroupMatcher<JobKey> matcher) throws SchedulerException {
      this.sched.pauseJobs(matcher);
   }

   public void resumeTrigger(TriggerKey triggerKey) throws SchedulerException {
      this.sched.resumeTrigger(triggerKey);
   }

   public void resumeTriggers(GroupMatcher<TriggerKey> matcher) throws SchedulerException {
      this.sched.resumeTriggers(matcher);
   }

   public void resumeJob(JobKey jobKey) throws SchedulerException {
      this.sched.resumeJob(jobKey);
   }

   public void resumeJobs(GroupMatcher<JobKey> matcher) throws SchedulerException {
      this.sched.resumeJobs(matcher);
   }

   public void pauseAll() throws SchedulerException {
      this.sched.pauseAll();
   }

   public void resumeAll() throws SchedulerException {
      this.sched.resumeAll();
   }

   public List<String> getJobGroupNames() throws SchedulerException {
      return this.sched.getJobGroupNames();
   }

   public List<? extends Trigger> getTriggersOfJob(JobKey jobKey) throws SchedulerException {
      return this.sched.getTriggersOfJob(jobKey);
   }

   public Set<JobKey> getJobKeys(GroupMatcher<JobKey> matcher) throws SchedulerException {
      return this.sched.getJobKeys(matcher);
   }

   public List<String> getTriggerGroupNames() throws SchedulerException {
      return this.sched.getTriggerGroupNames();
   }

   public Set<TriggerKey> getTriggerKeys(GroupMatcher<TriggerKey> matcher) throws SchedulerException {
      return this.sched.getTriggerKeys(matcher);
   }

   public JobDetail getJobDetail(JobKey jobKey) throws SchedulerException {
      return this.sched.getJobDetail(jobKey);
   }

   public Trigger getTrigger(TriggerKey triggerKey) throws SchedulerException {
      return this.sched.getTrigger(triggerKey);
   }

   public Trigger.TriggerState getTriggerState(TriggerKey triggerKey) throws SchedulerException {
      return this.sched.getTriggerState(triggerKey);
   }

   public void addCalendar(String calName, Calendar calendar, boolean replace, boolean updateTriggers) throws SchedulerException {
      this.sched.addCalendar(calName, calendar, replace, updateTriggers);
   }

   public boolean deleteCalendar(String calName) throws SchedulerException {
      return this.sched.deleteCalendar(calName);
   }

   public Calendar getCalendar(String calName) throws SchedulerException {
      return this.sched.getCalendar(calName);
   }

   public List<String> getCalendarNames() throws SchedulerException {
      return this.sched.getCalendarNames();
   }

   public boolean checkExists(JobKey jobKey) throws SchedulerException {
      return this.sched.checkExists(jobKey);
   }

   public boolean checkExists(TriggerKey triggerKey) throws SchedulerException {
      return this.sched.checkExists(triggerKey);
   }

   public void setJobFactory(JobFactory factory) throws SchedulerException {
      this.sched.setJobFactory(factory);
   }

   public ListenerManager getListenerManager() throws SchedulerException {
      return this.sched.getListenerManager();
   }

   public boolean interrupt(JobKey jobKey) throws UnableToInterruptJobException {
      return this.sched.interrupt(jobKey);
   }

   public boolean interrupt(String fireInstanceId) throws UnableToInterruptJobException {
      return this.sched.interrupt(fireInstanceId);
   }
}
