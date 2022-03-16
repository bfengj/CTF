package org.quartz;

import java.util.TimeZone;
import org.quartz.impl.triggers.CalendarIntervalTriggerImpl;
import org.quartz.spi.MutableTrigger;

public class CalendarIntervalScheduleBuilder extends ScheduleBuilder<CalendarIntervalTrigger> {
   private int interval = 1;
   private DateBuilder.IntervalUnit intervalUnit;
   private int misfireInstruction;
   private TimeZone timeZone;
   private boolean preserveHourOfDayAcrossDaylightSavings;
   private boolean skipDayIfHourDoesNotExist;

   protected CalendarIntervalScheduleBuilder() {
      this.intervalUnit = DateBuilder.IntervalUnit.DAY;
      this.misfireInstruction = 0;
   }

   public static CalendarIntervalScheduleBuilder calendarIntervalSchedule() {
      return new CalendarIntervalScheduleBuilder();
   }

   public MutableTrigger build() {
      CalendarIntervalTriggerImpl st = new CalendarIntervalTriggerImpl();
      st.setRepeatInterval(this.interval);
      st.setRepeatIntervalUnit(this.intervalUnit);
      st.setMisfireInstruction(this.misfireInstruction);
      st.setTimeZone(this.timeZone);
      st.setPreserveHourOfDayAcrossDaylightSavings(this.preserveHourOfDayAcrossDaylightSavings);
      st.setSkipDayIfHourDoesNotExist(this.skipDayIfHourDoesNotExist);
      return st;
   }

   public CalendarIntervalScheduleBuilder withInterval(int timeInterval, DateBuilder.IntervalUnit unit) {
      if (unit == null) {
         throw new IllegalArgumentException("TimeUnit must be specified.");
      } else {
         this.validateInterval(timeInterval);
         this.interval = timeInterval;
         this.intervalUnit = unit;
         return this;
      }
   }

   public CalendarIntervalScheduleBuilder withIntervalInSeconds(int intervalInSeconds) {
      this.validateInterval(intervalInSeconds);
      this.interval = intervalInSeconds;
      this.intervalUnit = DateBuilder.IntervalUnit.SECOND;
      return this;
   }

   public CalendarIntervalScheduleBuilder withIntervalInMinutes(int intervalInMinutes) {
      this.validateInterval(intervalInMinutes);
      this.interval = intervalInMinutes;
      this.intervalUnit = DateBuilder.IntervalUnit.MINUTE;
      return this;
   }

   public CalendarIntervalScheduleBuilder withIntervalInHours(int intervalInHours) {
      this.validateInterval(intervalInHours);
      this.interval = intervalInHours;
      this.intervalUnit = DateBuilder.IntervalUnit.HOUR;
      return this;
   }

   public CalendarIntervalScheduleBuilder withIntervalInDays(int intervalInDays) {
      this.validateInterval(intervalInDays);
      this.interval = intervalInDays;
      this.intervalUnit = DateBuilder.IntervalUnit.DAY;
      return this;
   }

   public CalendarIntervalScheduleBuilder withIntervalInWeeks(int intervalInWeeks) {
      this.validateInterval(intervalInWeeks);
      this.interval = intervalInWeeks;
      this.intervalUnit = DateBuilder.IntervalUnit.WEEK;
      return this;
   }

   public CalendarIntervalScheduleBuilder withIntervalInMonths(int intervalInMonths) {
      this.validateInterval(intervalInMonths);
      this.interval = intervalInMonths;
      this.intervalUnit = DateBuilder.IntervalUnit.MONTH;
      return this;
   }

   public CalendarIntervalScheduleBuilder withIntervalInYears(int intervalInYears) {
      this.validateInterval(intervalInYears);
      this.interval = intervalInYears;
      this.intervalUnit = DateBuilder.IntervalUnit.YEAR;
      return this;
   }

   public CalendarIntervalScheduleBuilder withMisfireHandlingInstructionIgnoreMisfires() {
      this.misfireInstruction = -1;
      return this;
   }

   public CalendarIntervalScheduleBuilder withMisfireHandlingInstructionDoNothing() {
      this.misfireInstruction = 2;
      return this;
   }

   public CalendarIntervalScheduleBuilder withMisfireHandlingInstructionFireAndProceed() {
      this.misfireInstruction = 1;
      return this;
   }

   public CalendarIntervalScheduleBuilder inTimeZone(TimeZone timezone) {
      this.timeZone = timezone;
      return this;
   }

   public CalendarIntervalScheduleBuilder preserveHourOfDayAcrossDaylightSavings(boolean preserveHourOfDay) {
      this.preserveHourOfDayAcrossDaylightSavings = preserveHourOfDay;
      return this;
   }

   public CalendarIntervalScheduleBuilder skipDayIfHourDoesNotExist(boolean skipDay) {
      this.skipDayIfHourDoesNotExist = skipDay;
      return this;
   }

   private void validateInterval(int timeInterval) {
      if (timeInterval <= 0) {
         throw new IllegalArgumentException("Interval must be a positive value.");
      }
   }
}
