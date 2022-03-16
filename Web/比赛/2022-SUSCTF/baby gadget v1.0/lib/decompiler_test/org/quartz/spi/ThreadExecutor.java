package org.quartz.spi;

public interface ThreadExecutor {
   void execute(Thread var1);

   void initialize();
}
