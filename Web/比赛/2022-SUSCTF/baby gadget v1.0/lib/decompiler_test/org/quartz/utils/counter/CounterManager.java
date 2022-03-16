package org.quartz.utils.counter;

public interface CounterManager {
   Counter createCounter(CounterConfig var1);

   void shutdown(boolean var1);

   void shutdownCounter(Counter var1);
}
