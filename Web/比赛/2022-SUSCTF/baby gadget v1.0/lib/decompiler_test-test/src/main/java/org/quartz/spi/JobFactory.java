package org.quartz.spi;

import org.quartz.Job;
import org.quartz.Scheduler;
import org.quartz.SchedulerException;

public interface JobFactory {
   Job newJob(TriggerFiredBundle var1, Scheduler var2) throws SchedulerException;
}
