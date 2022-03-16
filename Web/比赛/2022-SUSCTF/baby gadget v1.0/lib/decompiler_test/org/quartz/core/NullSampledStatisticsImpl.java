package org.quartz.core;

public class NullSampledStatisticsImpl implements SampledStatistics {
   public long getJobsCompletedMostRecentSample() {
      return 0L;
   }

   public long getJobsExecutingMostRecentSample() {
      return 0L;
   }

   public long getJobsScheduledMostRecentSample() {
      return 0L;
   }

   public void shutdown() {
   }
}
