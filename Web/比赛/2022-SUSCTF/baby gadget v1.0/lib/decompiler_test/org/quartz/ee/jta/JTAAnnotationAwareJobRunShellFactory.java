package org.quartz.ee.jta;

import org.quartz.ExecuteInJTATransaction;
import org.quartz.Scheduler;
import org.quartz.SchedulerConfigException;
import org.quartz.SchedulerException;
import org.quartz.core.JobRunShell;
import org.quartz.core.JobRunShellFactory;
import org.quartz.spi.TriggerFiredBundle;
import org.quartz.utils.ClassUtils;

public class JTAAnnotationAwareJobRunShellFactory implements JobRunShellFactory {
   private Scheduler scheduler;

   public void initialize(Scheduler sched) throws SchedulerConfigException {
      this.scheduler = sched;
   }

   public JobRunShell createJobRunShell(TriggerFiredBundle bundle) throws SchedulerException {
      ExecuteInJTATransaction jtaAnnotation = (ExecuteInJTATransaction)ClassUtils.getAnnotation(bundle.getJobDetail().getJobClass(), ExecuteInJTATransaction.class);
      if (jtaAnnotation == null) {
         return new JobRunShell(this.scheduler, bundle);
      } else {
         int timeout = jtaAnnotation.timeout();
         return timeout >= 0 ? new JTAJobRunShell(this.scheduler, bundle, timeout) : new JTAJobRunShell(this.scheduler, bundle);
      }
   }
}
