package org.quartz.utils.counter.sampled;

import org.quartz.utils.counter.Counter;

public interface SampledCounter extends Counter {
   void shutdown();

   TimeStampedCounterValue getMostRecentSample();

   TimeStampedCounterValue[] getAllSampleValues();

   long getAndReset();
}
