package org.quartz.utils.counter.sampled;

public interface SampledRateCounter extends SampledCounter {
   void increment(long var1, long var3);

   void decrement(long var1, long var3);

   void setValue(long var1, long var3);

   void setNumeratorValue(long var1);

   void setDenominatorValue(long var1);
}
