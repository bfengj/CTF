package org.terracotta.quartz;

import org.quartz.JobListener;
import org.quartz.spi.JobStore;

public interface TerracottaJobStoreExtensions extends JobStore, JobListener {
   void setMisfireThreshold(long var1);

   void setEstimatedTimeToReleaseAndAcquireTrigger(long var1);

   void setSynchronousWrite(String var1);

   void setThreadPoolSize(int var1);

   String getUUID();

   void setTcRetryInterval(long var1);
}
