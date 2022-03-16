package org.quartz.spi;

import java.util.Date;
import org.quartz.Calendar;
import org.quartz.JobExecutionContext;
import org.quartz.JobExecutionException;
import org.quartz.SchedulerException;
import org.quartz.Trigger;

public interface OperableTrigger extends MutableTrigger {
   void triggered(Calendar var1);

   Date computeFirstFireTime(Calendar var1);

   Trigger.CompletedExecutionInstruction executionComplete(JobExecutionContext var1, JobExecutionException var2);

   void updateAfterMisfire(Calendar var1);

   void updateWithNewCalendar(Calendar var1, long var2);

   void validate() throws SchedulerException;

   void setFireInstanceId(String var1);

   String getFireInstanceId();

   void setNextFireTime(Date var1);

   void setPreviousFireTime(Date var1);
}
