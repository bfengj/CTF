package org.terracotta.quartz.wrappers;

import org.quartz.JobDetail;
import org.quartz.spi.OperableTrigger;

public interface WrapperFactory {
   JobWrapper createJobWrapper(JobDetail var1);

   TriggerWrapper createTriggerWrapper(OperableTrigger var1, boolean var2);
}
