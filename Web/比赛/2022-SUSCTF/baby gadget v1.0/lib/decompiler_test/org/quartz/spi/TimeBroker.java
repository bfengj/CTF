package org.quartz.spi;

import java.util.Date;
import org.quartz.SchedulerConfigException;
import org.quartz.SchedulerException;

/** @deprecated */
public interface TimeBroker {
   Date getCurrentTime() throws SchedulerException;

   void initialize() throws SchedulerConfigException;

   void shutdown();
}
