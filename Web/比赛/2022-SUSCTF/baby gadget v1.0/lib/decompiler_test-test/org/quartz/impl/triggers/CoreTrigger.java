package org.quartz.impl.triggers;

import org.quartz.Trigger;

public interface CoreTrigger extends Trigger {
   boolean hasAdditionalProperties();
}
