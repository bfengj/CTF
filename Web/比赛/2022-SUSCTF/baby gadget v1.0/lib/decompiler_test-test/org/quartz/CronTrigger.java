package org.quartz;

import java.util.TimeZone;

public interface CronTrigger extends Trigger {
   long serialVersionUID = -8644953146451592766L;
   int MISFIRE_INSTRUCTION_FIRE_ONCE_NOW = 1;
   int MISFIRE_INSTRUCTION_DO_NOTHING = 2;

   String getCronExpression();

   TimeZone getTimeZone();

   String getExpressionSummary();

   TriggerBuilder<CronTrigger> getTriggerBuilder();
}
