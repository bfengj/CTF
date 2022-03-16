package org.quartz.commonj;

import commonj.work.Work;

class DelegatingWork implements Work {
   private final Runnable delegate;

   public DelegatingWork(Runnable delegate) {
      this.delegate = delegate;
   }

   public final Runnable getDelegate() {
      return this.delegate;
   }

   public void run() {
      this.delegate.run();
   }

   public boolean isDaemon() {
      return false;
   }

   public void release() {
   }
}
