package org.quartz;

import java.util.TimeZone;

public interface CalendarIntervalTrigger extends Trigger {
   int MISFIRE_INSTRUCTION_FIRE_ONCE_NOW = 1;
   int MISFIRE_INSTRUCTION_DO_NOTHING = 2;

   DateBuilder.IntervalUnit getRepeatIntervalUnit();

   int getRepeatInterval();

   int getTimesTriggered();

   TimeZone getTimeZone();

   boolean isPreserveHourOfDayAcrossDaylightSavings();

   boolean isSkipDayIfHourDoesNotExist();

   TriggerBuilder<CalendarIntervalTrigger> getTriggerBuilder();
}
