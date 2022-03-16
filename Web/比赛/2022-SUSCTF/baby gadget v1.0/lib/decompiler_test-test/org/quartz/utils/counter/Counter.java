package org.quartz.utils.counter;

public interface Counter {
   long increment();

   long decrement();

   long getAndSet(long var1);

   long getValue();

   long increment(long var1);

   long decrement(long var1);

   void setValue(long var1);
}
