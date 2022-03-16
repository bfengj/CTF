package org.quartz.management;

import org.quartz.core.QuartzScheduler;

public interface ManagementServer {
   void start();

   void stop();

   void register(QuartzScheduler var1);

   void unregister(QuartzScheduler var1);

   boolean hasRegistered();
}
