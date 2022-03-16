package org.terracotta.quartz;

import java.util.Collection;
import java.util.List;
import java.util.Map;
import java.util.Set;
import java.util.Timer;
import org.quartz.Calendar;
import org.quartz.JobDetail;
import org.quartz.JobExecutionContext;
import org.quartz.JobExecutionException;
import org.quartz.JobKey;
import org.quartz.JobPersistenceException;
import org.quartz.SchedulerConfigException;
import org.quartz.SchedulerException;
import org.quartz.Trigger;
import org.quartz.TriggerKey;
import org.quartz.impl.matchers.GroupMatcher;
import org.quartz.spi.ClassLoadHelper;
import org.quartz.spi.OperableTrigger;
import org.quartz.spi.SchedulerSignaler;
import org.quartz.spi.TriggerFiredResult;
import org.terracotta.toolkit.cluster.ClusterInfo;
import org.terracotta.toolkit.internal.ToolkitInternal;

public class PlainTerracottaJobStore<T extends ClusteredJobStore> implements TerracottaJobStoreExtensions {
   private static final long WEEKLY = 604800000L;
   private Timer updateCheckTimer;
   private volatile T clusteredJobStore = null;
   private Long misfireThreshold = null;
   private String schedName;
   private String synchWrite = "false";
   private Long estimatedTimeToReleaseAndAcquireTrigger = null;
   private String schedInstanceId;
   private long tcRetryInterval;
   private int threadPoolSize;
   private final ClusterInfo clusterInfo;
   protected final ToolkitInternal toolkit;

   public PlainTerracottaJobStore(ToolkitInternal toolkit) {
      this.toolkit = toolkit;
      this.clusterInfo = toolkit.getClusterInfo();
   }

   public void setSynchronousWrite(String synchWrite) {
      this.synchWrite = synchWrite;
   }

   public void setThreadPoolSize(int size) {
      this.threadPoolSize = size;
   }

   public List<OperableTrigger> acquireNextTriggers(long noLaterThan, int maxCount, long timeWindow) throws JobPersistenceException {
      return this.clusteredJobStore.acquireNextTriggers(noLaterThan, maxCount, timeWindow);
   }

   public List<String> getCalendarNames() throws JobPersistenceException {
      return this.clusteredJobStore.getCalendarNames();
   }

   public List<String> getJobGroupNames() throws JobPersistenceException {
      return this.clusteredJobStore.getJobGroupNames();
   }

   public Set<JobKey> getJobKeys(GroupMatcher<JobKey> matcher) throws JobPersistenceException {
      return this.clusteredJobStore.getJobKeys(matcher);
   }

   public int getNumberOfCalendars() throws JobPersistenceException {
      return this.clusteredJobStore.getNumberOfCalendars();
   }

   public int getNumberOfJobs() throws JobPersistenceException {
      return this.clusteredJobStore.getNumberOfJobs();
   }

   public int getNumberOfTriggers() throws JobPersistenceException {
      return this.clusteredJobStore.getNumberOfTriggers();
   }

   public Set<String> getPausedTriggerGroups() throws JobPersistenceException {
      return this.clusteredJobStore.getPausedTriggerGroups();
   }

   public List<String> getTriggerGroupNames() throws JobPersistenceException {
      return this.clusteredJobStore.getTriggerGroupNames();
   }

   public Set<TriggerKey> getTriggerKeys(GroupMatcher<TriggerKey> matcher) throws JobPersistenceException {
      return this.clusteredJobStore.getTriggerKeys(matcher);
   }

   public List<OperableTrigger> getTriggersForJob(JobKey jobKey) throws JobPersistenceException {
      return this.clusteredJobStore.getTriggersForJob(jobKey);
   }

   public Trigger.TriggerState getTriggerState(TriggerKey triggerKey) throws JobPersistenceException {
      return this.clusteredJobStore.getTriggerState(triggerKey);
   }

   public synchronized void initialize(ClassLoadHelper loadHelper, SchedulerSignaler signaler) throws SchedulerConfigException {
      if (this.clusteredJobStore != null) {
         throw new IllegalStateException("already initialized");
      } else {
         this.clusteredJobStore = this.createNewJobStoreInstance(this.schedName, Boolean.valueOf(this.synchWrite));
         this.clusteredJobStore.setThreadPoolSize(this.threadPoolSize);
         if (this.misfireThreshold != null) {
            this.clusteredJobStore.setMisfireThreshold(this.misfireThreshold);
            this.misfireThreshold = null;
         }

         if (this.estimatedTimeToReleaseAndAcquireTrigger != null) {
            this.clusteredJobStore.setEstimatedTimeToReleaseAndAcquireTrigger(this.estimatedTimeToReleaseAndAcquireTrigger);
            this.estimatedTimeToReleaseAndAcquireTrigger = null;
         }

         this.clusteredJobStore.setInstanceId(this.schedInstanceId);
         this.clusteredJobStore.setTcRetryInterval(this.tcRetryInterval);
         this.clusteredJobStore.initialize(loadHelper, signaler);
         this.scheduleUpdateCheck();
      }
   }

   public void pauseAll() throws JobPersistenceException {
      this.clusteredJobStore.pauseAll();
   }

   public void pauseJob(JobKey jobKey) throws JobPersistenceException {
      this.clusteredJobStore.pauseJob(jobKey);
   }

   public Collection<String> pauseJobs(GroupMatcher<JobKey> matcher) throws JobPersistenceException {
      return this.clusteredJobStore.pauseJobs(matcher);
   }

   public void pauseTrigger(TriggerKey triggerKey) throws JobPersistenceException {
      this.clusteredJobStore.pauseTrigger(triggerKey);
   }

   public Collection<String> pauseTriggers(GroupMatcher<TriggerKey> matcher) throws JobPersistenceException {
      return this.clusteredJobStore.pauseTriggers(matcher);
   }

   public void releaseAcquiredTrigger(OperableTrigger trigger) {
      this.clusteredJobStore.releaseAcquiredTrigger(trigger);
   }

   public List<TriggerFiredResult> triggersFired(List<OperableTrigger> triggers) throws JobPersistenceException {
      return this.clusteredJobStore.triggersFired(triggers);
   }

   public boolean removeCalendar(String calName) throws JobPersistenceException {
      return this.clusteredJobStore.removeCalendar(calName);
   }

   public boolean removeJob(JobKey jobKey) throws JobPersistenceException {
      return this.clusteredJobStore.removeJob(jobKey);
   }

   public boolean removeTrigger(TriggerKey triggerKey) throws JobPersistenceException {
      return this.clusteredJobStore.removeTrigger(triggerKey);
   }

   public boolean removeJobs(List<JobKey> jobKeys) throws JobPersistenceException {
      return this.clusteredJobStore.removeJobs(jobKeys);
   }

   public boolean removeTriggers(List<TriggerKey> triggerKeys) throws JobPersistenceException {
      return this.clusteredJobStore.removeTriggers(triggerKeys);
   }

   public void storeJobsAndTriggers(Map<JobDetail, Set<? extends Trigger>> triggersAndJobs, boolean replace) throws JobPersistenceException {
      this.clusteredJobStore.storeJobsAndTriggers(triggersAndJobs, replace);
   }

   public boolean replaceTrigger(TriggerKey triggerKey, OperableTrigger newTrigger) throws JobPersistenceException {
      return this.clusteredJobStore.replaceTrigger(triggerKey, newTrigger);
   }

   public void resumeAll() throws JobPersistenceException {
      this.clusteredJobStore.resumeAll();
   }

   public void resumeJob(JobKey jobKey) throws JobPersistenceException {
      this.clusteredJobStore.resumeJob(jobKey);
   }

   public Collection<String> resumeJobs(GroupMatcher<JobKey> matcher) throws JobPersistenceException {
      return this.clusteredJobStore.resumeJobs(matcher);
   }

   public void resumeTrigger(TriggerKey triggerKey) throws JobPersistenceException {
      this.clusteredJobStore.resumeTrigger(triggerKey);
   }

   public Collection<String> resumeTriggers(GroupMatcher<TriggerKey> matcher) throws JobPersistenceException {
      return this.clusteredJobStore.resumeTriggers(matcher);
   }

   public Calendar retrieveCalendar(String calName) throws JobPersistenceException {
      return this.clusteredJobStore.retrieveCalendar(calName);
   }

   public JobDetail retrieveJob(JobKey jobKey) throws JobPersistenceException {
      return this.clusteredJobStore.retrieveJob(jobKey);
   }

   public OperableTrigger retrieveTrigger(TriggerKey triggerKey) throws JobPersistenceException {
      return this.clusteredJobStore.retrieveTrigger(triggerKey);
   }

   public boolean checkExists(JobKey jobKey) throws JobPersistenceException {
      return this.clusteredJobStore.checkExists(jobKey);
   }

   public boolean checkExists(TriggerKey triggerKey) throws JobPersistenceException {
      return this.clusteredJobStore.checkExists(triggerKey);
   }

   public void clearAllSchedulingData() throws JobPersistenceException {
      this.clusteredJobStore.clearAllSchedulingData();
   }

   public void schedulerStarted() throws SchedulerException {
      this.clusteredJobStore.schedulerStarted();
   }

   public void schedulerPaused() {
      if (this.clusteredJobStore != null) {
         this.clusteredJobStore.schedulerPaused();
      }

   }

   public void schedulerResumed() {
      this.clusteredJobStore.schedulerResumed();
   }

   public void shutdown() {
      if (this.clusteredJobStore != null) {
         this.clusteredJobStore.shutdown();
      }

      if (this.updateCheckTimer != null) {
         this.updateCheckTimer.cancel();
      }

   }

   public void storeCalendar(String name, Calendar calendar, boolean replaceExisting, boolean updateTriggers) throws JobPersistenceException {
      this.clusteredJobStore.storeCalendar(name, calendar, replaceExisting, updateTriggers);
   }

   public void storeJob(JobDetail newJob, boolean replaceExisting) throws JobPersistenceException {
      this.clusteredJobStore.storeJob(newJob, replaceExisting);
   }

   public void storeJobAndTrigger(JobDetail newJob, OperableTrigger newTrigger) throws JobPersistenceException {
      this.clusteredJobStore.storeJobAndTrigger(newJob, newTrigger);
   }

   public void storeTrigger(OperableTrigger newTrigger, boolean replaceExisting) throws JobPersistenceException {
      this.clusteredJobStore.storeTrigger(newTrigger, replaceExisting);
   }

   public boolean supportsPersistence() {
      return true;
   }

   public String toString() {
      return this.clusteredJobStore.toString();
   }

   public void triggeredJobComplete(OperableTrigger trigger, JobDetail jobDetail, Trigger.CompletedExecutionInstruction triggerInstCode) {
      this.clusteredJobStore.triggeredJobComplete(trigger, jobDetail, triggerInstCode);
   }

   public synchronized void setMisfireThreshold(long threshold) {
      ClusteredJobStore cjs = this.clusteredJobStore;
      if (cjs != null) {
         cjs.setMisfireThreshold(threshold);
      } else {
         this.misfireThreshold = threshold;
      }

   }

   public synchronized void setEstimatedTimeToReleaseAndAcquireTrigger(long estimate) {
      ClusteredJobStore cjs = this.clusteredJobStore;
      if (cjs != null) {
         cjs.setEstimatedTimeToReleaseAndAcquireTrigger(estimate);
      } else {
         this.estimatedTimeToReleaseAndAcquireTrigger = estimate;
      }

   }

   public void setInstanceId(String schedInstId) {
      this.schedInstanceId = schedInstId;
   }

   public void setInstanceName(String schedName) {
      this.schedName = schedName;
   }

   public void setTcRetryInterval(long retryInterval) {
      this.tcRetryInterval = retryInterval;
   }

   public String getUUID() {
      return this.clusterInfo.getCurrentNode().getId();
   }

   protected T createNewJobStoreInstance(String schedulerName, boolean useSynchWrite) {
      return new DefaultClusteredJobStore(useSynchWrite, this.toolkit, schedulerName);
   }

   private void scheduleUpdateCheck() {
      if (!Boolean.getBoolean("org.terracotta.quartz.skipUpdateCheck")) {
         this.updateCheckTimer = new Timer("Update Checker", true);
         this.updateCheckTimer.scheduleAtFixedRate(new UpdateChecker(), 100L, 604800000L);
      }

   }

   public long getEstimatedTimeToReleaseAndAcquireTrigger() {
      return this.clusteredJobStore.getEstimatedTimeToReleaseAndAcquireTrigger();
   }

   public boolean isClustered() {
      return true;
   }

   protected T getClusteredJobStore() {
      return this.clusteredJobStore;
   }

   public String getName() {
      return this.getClass().getName();
   }

   public void jobToBeExecuted(JobExecutionContext context) {
   }

   public void jobExecutionVetoed(JobExecutionContext context) {
   }

   public void jobWasExecuted(JobExecutionContext context, JobExecutionException jobException) {
   }
}
