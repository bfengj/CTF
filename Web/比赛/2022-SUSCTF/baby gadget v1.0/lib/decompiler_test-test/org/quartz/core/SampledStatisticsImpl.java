package org.quartz.core;

import java.util.Timer;
import org.quartz.JobDetail;
import org.quartz.JobExecutionContext;
import org.quartz.JobExecutionException;
import org.quartz.JobListener;
import org.quartz.SchedulerListener;
import org.quartz.Trigger;
import org.quartz.listeners.SchedulerListenerSupport;
import org.quartz.utils.counter.CounterConfig;
import org.quartz.utils.counter.CounterManager;
import org.quartz.utils.counter.CounterManagerImpl;
import org.quartz.utils.counter.sampled.SampledCounter;
import org.quartz.utils.counter.sampled.SampledCounterConfig;
import org.quartz.utils.counter.sampled.SampledRateCounterConfig;

public class SampledStatisticsImpl extends SchedulerListenerSupport implements SampledStatistics, JobListener, SchedulerListener {
   private final QuartzScheduler scheduler;
   private static final String NAME = "QuartzSampledStatistics";
   private static final int DEFAULT_HISTORY_SIZE = 30;
   private static final int DEFAULT_INTERVAL_SECS = 1;
   private static final SampledCounterConfig DEFAULT_SAMPLED_COUNTER_CONFIG = new SampledCounterConfig(1, 30, true, 0L);
   private static final SampledRateCounterConfig DEFAULT_SAMPLED_RATE_COUNTER_CONFIG = new SampledRateCounterConfig(1, 30, true);
   private volatile CounterManager counterManager;
   private final SampledCounter jobsScheduledCount;
   private final SampledCounter jobsExecutingCount;
   private final SampledCounter jobsCompletedCount;

   SampledStatisticsImpl(QuartzScheduler scheduler) {
      this.scheduler = scheduler;
      this.counterManager = new CounterManagerImpl(new Timer("QuartzSampledStatisticsTimer"));
      this.jobsScheduledCount = this.createSampledCounter(DEFAULT_SAMPLED_COUNTER_CONFIG);
      this.jobsExecutingCount = this.createSampledCounter(DEFAULT_SAMPLED_COUNTER_CONFIG);
      this.jobsCompletedCount = this.createSampledCounter(DEFAULT_SAMPLED_COUNTER_CONFIG);
      scheduler.addInternalSchedulerListener(this);
      scheduler.addInternalJobListener(this);
   }

   public void shutdown() {
      this.counterManager.shutdown(true);
   }

   private SampledCounter createSampledCounter(CounterConfig defaultCounterConfig) {
      return (SampledCounter)this.counterManager.createCounter(defaultCounterConfig);
   }

   public void clearStatistics() {
      this.jobsScheduledCount.getAndReset();
      this.jobsExecutingCount.getAndReset();
      this.jobsCompletedCount.getAndReset();
   }

   public long getJobsCompletedMostRecentSample() {
      return this.jobsCompletedCount.getMostRecentSample().getCounterValue();
   }

   public long getJobsExecutingMostRecentSample() {
      return this.jobsExecutingCount.getMostRecentSample().getCounterValue();
   }

   public long getJobsScheduledMostRecentSample() {
      return this.jobsScheduledCount.getMostRecentSample().getCounterValue();
   }

   public String getName() {
      return "QuartzSampledStatistics";
   }

   public void jobScheduled(Trigger trigger) {
      this.jobsScheduledCount.increment();
   }

   public void jobExecutionVetoed(JobExecutionContext context) {
   }

   public void jobToBeExecuted(JobExecutionContext context) {
      this.jobsExecutingCount.increment();
   }

   public void jobWasExecuted(JobExecutionContext context, JobExecutionException jobException) {
      this.jobsCompletedCount.increment();
   }

   public void jobAdded(JobDetail jobDetail) {
   }

   public void jobDeleted(String jobName, String groupName) {
   }
}
