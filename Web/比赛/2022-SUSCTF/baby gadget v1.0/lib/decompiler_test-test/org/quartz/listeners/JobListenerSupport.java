package org.quartz.listeners;

import org.quartz.JobExecutionContext;
import org.quartz.JobExecutionException;
import org.quartz.JobListener;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

public abstract class JobListenerSupport implements JobListener {
   private final Logger log = LoggerFactory.getLogger(this.getClass());

   protected Logger getLog() {
      return this.log;
   }

   public void jobToBeExecuted(JobExecutionContext context) {
   }

   public void jobExecutionVetoed(JobExecutionContext context) {
   }

   public void jobWasExecuted(JobExecutionContext context, JobExecutionException jobException) {
   }
}
