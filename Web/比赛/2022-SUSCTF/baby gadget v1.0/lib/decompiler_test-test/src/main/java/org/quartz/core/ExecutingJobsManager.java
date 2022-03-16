package org.quartz.core;

import java.util.ArrayList;
import java.util.Collections;
import java.util.HashMap;
import java.util.List;
import java.util.concurrent.atomic.AtomicInteger;
import org.quartz.JobExecutionContext;
import org.quartz.JobExecutionException;
import org.quartz.JobListener;
import org.quartz.spi.OperableTrigger;

class ExecutingJobsManager implements JobListener {
   HashMap<String, JobExecutionContext> executingJobs = new HashMap();
   AtomicInteger numJobsFired = new AtomicInteger(0);

   public String getName() {
      return this.getClass().getName();
   }

   public int getNumJobsCurrentlyExecuting() {
      synchronized(this.executingJobs) {
         return this.executingJobs.size();
      }
   }

   public void jobToBeExecuted(JobExecutionContext context) {
      this.numJobsFired.incrementAndGet();
      synchronized(this.executingJobs) {
         this.executingJobs.put(((OperableTrigger)context.getTrigger()).getFireInstanceId(), context);
      }
   }

   public void jobWasExecuted(JobExecutionContext context, JobExecutionException jobException) {
      synchronized(this.executingJobs) {
         this.executingJobs.remove(((OperableTrigger)context.getTrigger()).getFireInstanceId());
      }
   }

   public int getNumJobsFired() {
      return this.numJobsFired.get();
   }

   public List<JobExecutionContext> getExecutingJobs() {
      synchronized(this.executingJobs) {
         return Collections.unmodifiableList(new ArrayList(this.executingJobs.values()));
      }
   }

   public void jobExecutionVetoed(JobExecutionContext context) {
   }
}
