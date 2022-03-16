package org.quartz.impl;

import org.quartz.spi.ThreadExecutor;

public class DefaultThreadExecutor implements ThreadExecutor {
   public void initialize() {
   }

   public void execute(Thread thread) {
      thread.start();
   }
}
