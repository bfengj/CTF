package org.quartz.utils.counter.sampled;

import java.io.Serializable;

public class TimeStampedCounterValue implements Serializable {
   private static final long serialVersionUID = 1931111347823687672L;
   private final long counterValue;
   private final long timestamp;

   public TimeStampedCounterValue(long timestamp, long value) {
      this.timestamp = timestamp;
      this.counterValue = value;
   }

   public long getCounterValue() {
      return this.counterValue;
   }

   public long getTimestamp() {
      return this.timestamp;
   }

   public String toString() {
      return "value: " + this.counterValue + ", timestamp: " + this.timestamp;
   }
}
