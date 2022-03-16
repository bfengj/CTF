package org.terracotta.quartz.wrappers;

import org.quartz.JobDetail;
import org.quartz.spi.OperableTrigger;

public class DefaultWrapperFactory implements WrapperFactory {
   public JobWrapper createJobWrapper(JobDetail jobDetail) {
      return new JobWrapper(jobDetail);
   }

   public TriggerWrapper createTriggerWrapper(OperableTrigger trigger, boolean jobDisallowsConcurrence) {
      return new TriggerWrapper(trigger, jobDisallowsConcurrence);
   }
}
