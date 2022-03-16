package org.quartz;

import java.util.Set;

public interface DailyTimeIntervalTrigger extends Trigger {
   int REPEAT_INDEFINITELY = -1;
   int MISFIRE_INSTRUCTION_FIRE_ONCE_NOW = 1;
   int MISFIRE_INSTRUCTION_DO_NOTHING = 2;

   DateBuilder.IntervalUnit getRepeatIntervalUnit();

   int getRepeatCount();

   int getRepeatInterval();

   TimeOfDay getStartTimeOfDay();

   TimeOfDay getEndTimeOfDay();

   Set<Integer> getDaysOfWeek();

   int getTimesTriggered();

   TriggerBuilder<DailyTimeIntervalTrigger> getTriggerBuilder();
}
