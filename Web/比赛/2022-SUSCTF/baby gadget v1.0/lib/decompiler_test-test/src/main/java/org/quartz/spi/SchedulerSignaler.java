package org.quartz.spi;

import org.quartz.JobKey;
import org.quartz.SchedulerException;
import org.quartz.Trigger;

public interface SchedulerSignaler {
   void notifyTriggerListenersMisfired(Trigger var1);

   void notifySchedulerListenersFinalized(Trigger var1);

   void notifySchedulerListenersJobDeleted(JobKey var1);

   void signalSchedulingChange(long var1);

   void notifySchedulerListenersError(String var1, SchedulerException var2);
}
