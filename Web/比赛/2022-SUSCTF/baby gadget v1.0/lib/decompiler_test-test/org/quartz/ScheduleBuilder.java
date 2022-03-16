package org.quartz;

import org.quartz.spi.MutableTrigger;

public abstract class ScheduleBuilder<T extends Trigger> {
   protected abstract MutableTrigger build();
}
