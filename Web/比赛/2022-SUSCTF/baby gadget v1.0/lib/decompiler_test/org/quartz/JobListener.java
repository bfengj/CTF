package org.quartz;

public interface JobListener {
   String getName();

   void jobToBeExecuted(JobExecutionContext var1);

   void jobExecutionVetoed(JobExecutionContext var1);

   void jobWasExecuted(JobExecutionContext var1, JobExecutionException var2);
}
