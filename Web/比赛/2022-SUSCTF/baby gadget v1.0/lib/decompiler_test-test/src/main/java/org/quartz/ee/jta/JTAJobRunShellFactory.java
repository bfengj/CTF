package org.quartz.ee.jta;

import org.quartz.Scheduler;
import org.quartz.SchedulerConfigException;
import org.quartz.SchedulerException;
import org.quartz.core.JobRunShell;
import org.quartz.core.JobRunShellFactory;
import org.quartz.spi.TriggerFiredBundle;

public class JTAJobRunShellFactory implements JobRunShellFactory {
   private Scheduler scheduler;

   public void initialize(Scheduler sched) throws SchedulerConfigException {
      this.scheduler = sched;
   }

   public JobRunShell createJobRunShell(TriggerFiredBundle bundle) throws SchedulerException {
      return new JTAJobRunShell(this.scheduler, bundle);
   }
}
