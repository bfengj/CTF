package org.quartz.spi;

import org.quartz.SchedulerException;

public interface InstanceIdGenerator {
   String generateInstanceId() throws SchedulerException;
}
