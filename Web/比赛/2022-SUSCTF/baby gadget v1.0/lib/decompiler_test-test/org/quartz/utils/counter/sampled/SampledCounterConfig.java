package org.quartz.utils.counter.sampled;

import org.quartz.utils.counter.Counter;
import org.quartz.utils.counter.CounterConfig;

public class SampledCounterConfig extends CounterConfig {
   private final int intervalSecs;
   private final int historySize;
   private final boolean isReset;

   public SampledCounterConfig(int intervalSecs, int historySize, boolean isResetOnSample, long initialValue) {
      super(initialValue);
      if (intervalSecs < 1) {
         throw new IllegalArgumentException("Interval (" + intervalSecs + ") must be greater than or equal to 1");
      } else if (historySize < 1) {
         throw new IllegalArgumentException("History size (" + historySize + ") must be greater than or equal to 1");
      } else {
         this.intervalSecs = intervalSecs;
         this.historySize = historySize;
         this.isReset = isResetOnSample;
      }
   }

   public int getHistorySize() {
      return this.historySize;
   }

   public int getIntervalSecs() {
      return this.intervalSecs;
   }

   public boolean isResetOnSample() {
      return this.isReset;
   }

   public Counter createCounter() {
      return new SampledCounterImpl(this);
   }
}
