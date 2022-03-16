package org.quartz.simpl;

import org.quartz.SchedulerConfigException;
import org.quartz.spi.ThreadPool;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

public class ZeroSizeThreadPool implements ThreadPool {
   private final Logger log = LoggerFactory.getLogger(this.getClass());

   public Logger getLog() {
      return this.log;
   }

   public int getPoolSize() {
      return 0;
   }

   public void initialize() throws SchedulerConfigException {
   }

   public void shutdown() {
      this.shutdown(true);
   }

   public void shutdown(boolean waitForJobsToComplete) {
      this.getLog().debug("shutdown complete");
   }

   public boolean runInThread(Runnable runnable) {
      throw new UnsupportedOperationException("This ThreadPool should not be used on Scheduler instances that are start()ed.");
   }

   public int blockForAvailableThreads() {
      throw new UnsupportedOperationException("This ThreadPool should not be used on Scheduler instances that are start()ed.");
   }

   public void setInstanceId(String schedInstId) {
   }

   public void setInstanceName(String schedName) {
   }
}
