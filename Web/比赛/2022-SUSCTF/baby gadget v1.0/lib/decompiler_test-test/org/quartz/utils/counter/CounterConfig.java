package org.quartz.utils.counter;

public class CounterConfig {
   private final long initialValue;

   public CounterConfig(long initialValue) {
      this.initialValue = initialValue;
   }

   public final long getInitialValue() {
      return this.initialValue;
   }

   public Counter createCounter() {
      return new CounterImpl(this.initialValue);
   }
}
