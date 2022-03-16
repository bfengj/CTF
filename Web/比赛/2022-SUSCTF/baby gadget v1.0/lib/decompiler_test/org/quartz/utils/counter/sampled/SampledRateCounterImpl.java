package org.quartz.utils.counter.sampled;

public class SampledRateCounterImpl extends SampledCounterImpl implements SampledRateCounter {
   private static final long serialVersionUID = 6531350452676920607L;
   private static final String OPERATION_NOT_SUPPORTED_MSG = "This operation is not supported. Use SampledCounter Or Counter instead";
   private long numeratorValue;
   private long denominatorValue;

   public SampledRateCounterImpl(SampledRateCounterConfig config) {
      super(config);
   }

   public synchronized void setValue(long numerator, long denominator) {
      this.numeratorValue = numerator;
      this.denominatorValue = denominator;
   }

   public synchronized void increment(long numerator, long denominator) {
      this.numeratorValue += numerator;
      this.denominatorValue += denominator;
   }

   public synchronized void decrement(long numerator, long denominator) {
      this.numeratorValue -= numerator;
      this.denominatorValue -= denominator;
   }

   public synchronized void setDenominatorValue(long newValue) {
      this.denominatorValue = newValue;
   }

   public synchronized void setNumeratorValue(long newValue) {
      this.numeratorValue = newValue;
   }

   public synchronized long getValue() {
      return this.denominatorValue == 0L ? 0L : this.numeratorValue / this.denominatorValue;
   }

   public synchronized long getAndReset() {
      long prevVal = this.getValue();
      this.setValue(0L, 0L);
      return prevVal;
   }

   public long getAndSet(long newValue) {
      throw new UnsupportedOperationException("This operation is not supported. Use SampledCounter Or Counter instead");
   }

   public synchronized void setValue(long newValue) {
      throw new UnsupportedOperationException("This operation is not supported. Use SampledCounter Or Counter instead");
   }

   public long decrement() {
      throw new UnsupportedOperationException("This operation is not supported. Use SampledCounter Or Counter instead");
   }

   public long decrement(long amount) {
      throw new UnsupportedOperationException("This operation is not supported. Use SampledCounter Or Counter instead");
   }

   public long getMaxValue() {
      throw new UnsupportedOperationException("This operation is not supported. Use SampledCounter Or Counter instead");
   }

   public long getMinValue() {
      throw new UnsupportedOperationException("This operation is not supported. Use SampledCounter Or Counter instead");
   }

   public long increment() {
      throw new UnsupportedOperationException("This operation is not supported. Use SampledCounter Or Counter instead");
   }

   public long increment(long amount) {
      throw new UnsupportedOperationException("This operation is not supported. Use SampledCounter Or Counter instead");
   }
}
