package org.quartz.utils.counter;

import java.io.Serializable;
import java.util.concurrent.atomic.AtomicLong;

public class CounterImpl implements Counter, Serializable {
   private static final long serialVersionUID = -1529134342654953984L;
   private AtomicLong value;

   public CounterImpl() {
      this(0L);
   }

   public CounterImpl(long initialValue) {
      this.value = new AtomicLong(initialValue);
   }

   public long increment() {
      return this.value.incrementAndGet();
   }

   public long decrement() {
      return this.value.decrementAndGet();
   }

   public long getAndSet(long newValue) {
      return this.value.getAndSet(newValue);
   }

   public long getValue() {
      return this.value.get();
   }

   public long increment(long amount) {
      return this.value.addAndGet(amount);
   }

   public long decrement(long amount) {
      return this.value.addAndGet(amount * -1L);
   }

   public void setValue(long newValue) {
      this.value.set(newValue);
   }
}
