package org.terracotta.quartz;

import org.quartz.spi.JobStore;
import org.terracotta.toolkit.cluster.ClusterListener;

public interface ClusteredJobStore extends JobStore, ClusterListener {
   void setMisfireThreshold(long var1);

   void setEstimatedTimeToReleaseAndAcquireTrigger(long var1);

   void setTcRetryInterval(long var1);
}
