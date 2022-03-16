package org.quartz;

import java.text.ParseException;
import java.util.TimeZone;
import org.quartz.impl.triggers.CronTriggerImpl;
import org.quartz.spi.MutableTrigger;

public class CronScheduleBuilder extends ScheduleBuilder<CronTrigger> {
   private CronExpression cronExpression;
   private int misfireInstruction = 0;

   protected CronScheduleBuilder(CronExpression cronExpression) {
      if (cronExpression == null) {
         throw new NullPointerException("cronExpression cannot be null");
      } else {
         this.cronExpression = cronExpression;
      }
   }

   public MutableTrigger build() {
      CronTriggerImpl ct = new CronTriggerImpl();
      ct.setCronExpression(this.cronExpression);
      ct.setTimeZone(this.cronExpression.getTimeZone());
      ct.setMisfireInstruction(this.misfireInstruction);
      return ct;
   }

   public static CronScheduleBuilder cronSchedule(String cronExpression) {
      try {
         return cronSchedule(new CronExpression(cronExpression));
      } catch (ParseException var2) {
         throw new RuntimeException("CronExpression '" + cronExpression + "' is invalid.", var2);
      }
   }

   public static CronScheduleBuilder cronScheduleNonvalidatedExpression(String cronExpression) throws ParseException {
      return cronSchedule(new CronExpression(cronExpression));
   }

   private static CronScheduleBuilder cronScheduleNoParseException(String presumedValidCronExpression) {
      try {
         return cronSchedule(new CronExpression(presumedValidCronExpression));
      } catch (ParseException var2) {
         throw new RuntimeException("CronExpression '" + presumedValidCronExpression + "' is invalid, which should not be possible, please report bug to Quartz developers.", var2);
      }
   }

   public static CronScheduleBuilder cronSchedule(CronExpression cronExpression) {
      return new CronScheduleBuilder(cronExpression);
   }

   public static CronScheduleBuilder dailyAtHourAndMinute(int hour, int minute) {
      DateBuilder.validateHour(hour);
      DateBuilder.validateMinute(minute);
      String cronExpression = String.format("0 %d %d ? * *", minute, hour);
      return cronScheduleNoParseException(cronExpression);
   }

   public static CronScheduleBuilder atHourAndMinuteOnGivenDaysOfWeek(int hour, int minute, Integer... daysOfWeek) {
      if (daysOfWeek != null && daysOfWeek.length != 0) {
         Integer[] arr$ = daysOfWeek;
         int i = daysOfWeek.length;

         for(int i$ = 0; i$ < i; ++i$) {
            int dayOfWeek = arr$[i$];
            DateBuilder.validateDayOfWeek(dayOfWeek);
         }

         DateBuilder.validateHour(hour);
         DateBuilder.validateMinute(minute);
         String cronExpression = String.format("0 %d %d ? * %d", minute, hour, daysOfWeek[0]);

         for(i = 1; i < daysOfWeek.length; ++i) {
            cronExpression = cronExpression + "," + daysOfWeek[i];
         }

         return cronScheduleNoParseException(cronExpression);
      } else {
         throw new IllegalArgumentException("You must specify at least one day of week.");
      }
   }

   public static CronScheduleBuilder weeklyOnDayAndHourAndMinute(int dayOfWeek, int hour, int minute) {
      DateBuilder.validateDayOfWeek(dayOfWeek);
      DateBuilder.validateHour(hour);
      DateBuilder.validateMinute(minute);
      String cronExpression = String.format("0 %d %d ? * %d", minute, hour, dayOfWeek);
      return cronScheduleNoParseException(cronExpression);
   }

   public static CronScheduleBuilder monthlyOnDayAndHourAndMinute(int dayOfMonth, int hour, int minute) {
      DateBuilder.validateDayOfMonth(dayOfMonth);
      DateBuilder.validateHour(hour);
      DateBuilder.validateMinute(minute);
      String cronExpression = String.format("0 %d %d %d * ?", minute, hour, dayOfMonth);
      return cronScheduleNoParseException(cronExpression);
   }

   public CronScheduleBuilder inTimeZone(TimeZone timezone) {
      this.cronExpression.setTimeZone(timezone);
      return this;
   }

   public CronScheduleBuilder withMisfireHandlingInstructionIgnoreMisfires() {
      this.misfireInstruction = -1;
      return this;
   }

   public CronScheduleBuilder withMisfireHandlingInstructionDoNothing() {
      this.misfireInstruction = 2;
      return this;
   }

   public CronScheduleBuilder withMisfireHandlingInstructionFireAndProceed() {
      this.misfireInstruction = 1;
      return this;
   }
}
