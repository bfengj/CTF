package org.quartz.spi;

import org.quartz.SchedulerConfigException;

public interface ThreadPool {
   boolean runInThread(Runnable var1);

   int blockForAvailableThreads();

   void initialize() throws SchedulerConfigException;

   void shutdown(boolean var1);

   int getPoolSize();

   void setInstanceId(String var1);

   void setInstanceName(String var1);
}
