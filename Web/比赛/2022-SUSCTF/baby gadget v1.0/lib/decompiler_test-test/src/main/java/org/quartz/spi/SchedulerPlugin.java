package org.quartz.spi;

import org.quartz.Scheduler;
import org.quartz.SchedulerException;

public interface SchedulerPlugin {
   void initialize(String var1, Scheduler var2, ClassLoadHelper var3) throws SchedulerException;

   void start();

   void shutdown();
}
