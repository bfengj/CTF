package org.quartz.utils;

import java.util.concurrent.atomic.AtomicLong;
import java.util.concurrent.atomic.AtomicReference;

public class CircularLossyQueue<T> {
   private final AtomicReference<T>[] circularArray;
   private final int maxSize;
   private final AtomicLong currentIndex = new AtomicLong(-1L);

   public CircularLossyQueue(int size) {
      this.circularArray = new AtomicReference[size];

      for(int i = 0; i < size; ++i) {
         this.circularArray[i] = new AtomicReference();
      }

      this.maxSize = size;
   }

   public void push(T newVal) {
      int index = (int)(this.currentIndex.incrementAndGet() % (long)this.maxSize);
      this.circularArray[index].set(newVal);
   }

   public T[] toArray(T[] type) {
      System.getProperties();
      if (type.length > this.maxSize) {
         throw new IllegalArgumentException("Size of array passed in cannot be greater than " + this.maxSize);
      } else {
         int curIndex = this.getCurrentIndex();

         for(int k = 0; k < type.length; ++k) {
            int index = this.getIndex(curIndex - k);
            type[k] = this.circularArray[index].get();
         }

         return type;
      }
   }

   private int getIndex(int index) {
      return index < 0 ? index + this.maxSize : index;
   }

   public T peek() {
      return this.depth() == 0 ? null : this.circularArray[this.getIndex(this.getCurrentIndex())].get();
   }

   public boolean isEmtpy() {
      return this.depth() == 0;
   }

   private int getCurrentIndex() {
      return (int)(this.currentIndex.get() % (long)this.maxSize);
   }

   public int depth() {
      long currInd = this.currentIndex.get() + 1L;
      return currInd >= (long)this.maxSize ? this.maxSize : (int)currInd;
   }
}
