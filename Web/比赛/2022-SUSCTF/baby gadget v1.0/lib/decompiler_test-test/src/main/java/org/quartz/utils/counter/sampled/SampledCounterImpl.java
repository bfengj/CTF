package org.quartz.utils.counter.sampled;

import java.util.TimerTask;
import org.quartz.utils.CircularLossyQueue;
import org.quartz.utils.counter.CounterImpl;

public class SampledCounterImpl extends CounterImpl implements SampledCounter {
   private static final long serialVersionUID = -3605369302464131521L;
   private static final int MILLIS_PER_SEC = 1000;
   protected final CircularLossyQueue<TimeStampedCounterValue> history;
   protected final boolean resetOnSample;
   private final TimerTask samplerTask;
   private final long intervalMillis;

   public SampledCounterImpl(SampledCounterConfig config) {
      super(config.getInitialValue());
      this.intervalMillis = (long)(config.getIntervalSecs() * 1000);
      this.history = new CircularLossyQueue(config.getHistorySize());
      this.resetOnSample = config.isResetOnSample();
      this.samplerTask = new TimerTask() {
         public void run() {
            SampledCounterImpl.this.recordSample();
         }
      };
      this.recordSample();
   }

   public TimeStampedCounterValue getMostRecentSample() {
      return (TimeStampedCounterValue)this.history.peek();
   }

   public TimeStampedCounterValue[] getAllSampleValues() {
      return (TimeStampedCounterValue[])this.history.toArray(new TimeStampedCounterValue[this.history.depth()]);
   }

   public void shutdown() {
      if (this.samplerTask != null) {
         this.samplerTask.cancel();
      }

   }

   public TimerTask getTimerTask() {
      return this.samplerTask;
   }

   public long getIntervalMillis() {
      return this.intervalMillis;
   }

   void recordSample() {
      long sample;
      if (this.resetOnSample) {
         sample = this.getAndReset();
      } else {
         sample = this.getValue();
      }

      long now = System.currentTimeMillis();
      TimeStampedCounterValue timedSample = new TimeStampedCounterValue(now, sample);
      this.history.push(timedSample);
   }

   public long getAndReset() {
      return this.getAndSet(0L);
   }
}
