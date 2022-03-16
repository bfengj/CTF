package org.quartz;

public interface Job {
   void execute(JobExecutionContext var1) throws JobExecutionException;
}
