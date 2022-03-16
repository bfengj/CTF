package org.quartz.core;

import org.quartz.Scheduler;
import org.quartz.SchedulerConfigException;
import org.quartz.SchedulerException;
import org.quartz.spi.TriggerFiredBundle;

public interface JobRunShellFactory {
   void initialize(Scheduler var1) throws SchedulerConfigException;

   JobRunShell createJobRunShell(TriggerFiredBundle var1) throws SchedulerException;
}
