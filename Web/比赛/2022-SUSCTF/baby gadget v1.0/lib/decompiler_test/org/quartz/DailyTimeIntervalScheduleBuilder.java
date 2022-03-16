package org.quartz;

import java.util.Collections;
import java.util.Date;
import java.util.HashSet;
import java.util.Iterator;
import java.util.Set;
import org.quartz.impl.triggers.DailyTimeIntervalTriggerImpl;
import org.quartz.spi.MutableTrigger;

public class DailyTimeIntervalScheduleBuilder extends ScheduleBuilder<DailyTimeIntervalTrigger> {
   private int interval = 1;
   private DateBuilder.IntervalUnit intervalUnit;
   private Set<Integer> daysOfWeek;
   private TimeOfDay startTimeOfDay;
   private TimeOfDay endTimeOfDay;
   private int repeatCount;
   private int misfireInstruction;
   public static final Set<Integer> ALL_DAYS_OF_THE_WEEK;
   public static final Set<Integer> MONDAY_THROUGH_FRIDAY;
   public static final Set<Integer> SATURDAY_AND_SUNDAY;

   protected DailyTimeIntervalScheduleBuilder() {
      this.intervalUnit = DateBuilder.IntervalUnit.MINUTE;
      this.repeatCount = -1;
      this.misfireInstruction = 0;
   }

   public static DailyTimeIntervalScheduleBuilder dailyTimeIntervalSchedule() {
      return new DailyTimeIntervalScheduleBuilder();
   }

   public MutableTrigger build() {
      DailyTimeIntervalTriggerImpl st = new DailyTimeIntervalTriggerImpl();
      st.setRepeatInterval(this.interval);
      st.setRepeatIntervalUnit(this.intervalUnit);
      st.setMisfireInstruction(this.misfireInstruction);
      st.setRepeatCount(this.repeatCount);
      if (this.daysOfWeek != null) {
         st.setDaysOfWeek(this.daysOfWeek);
      } else {
         st.setDaysOfWeek(ALL_DAYS_OF_THE_WEEK);
      }

      if (this.startTimeOfDay != null) {
         st.setStartTimeOfDay(this.startTimeOfDay);
      } else {
         st.setStartTimeOfDay(TimeOfDay.hourAndMinuteOfDay(0, 0));
      }

      if (this.endTimeOfDay != null) {
         st.setEndTimeOfDay(this.endTimeOfDay);
      } else {
         st.setEndTimeOfDay(TimeOfDay.hourMinuteAndSecondOfDay(23, 59, 59));
      }

      return st;
   }

   public DailyTimeIntervalScheduleBuilder withInterval(int timeInterval, DateBuilder.IntervalUnit unit) {
      if (unit != null && (unit.equals(DateBuilder.IntervalUnit.SECOND) || unit.equals(DateBuilder.IntervalUnit.MINUTE) || unit.equals(DateBuilder.IntervalUnit.HOUR))) {
         this.validateInterval(timeInterval);
         this.interval = timeInterval;
         this.intervalUnit = unit;
         return this;
      } else {
         throw new IllegalArgumentException("Invalid repeat IntervalUnit (must be SECOND, MINUTE or HOUR).");
      }
   }

   public DailyTimeIntervalScheduleBuilder withIntervalInSeconds(int intervalInSeconds) {
      this.withInterval(intervalInSeconds, DateBuilder.IntervalUnit.SECOND);
      return this;
   }

   public DailyTimeIntervalScheduleBuilder withIntervalInMinutes(int intervalInMinutes) {
      this.withInterval(intervalInMinutes, DateBuilder.IntervalUnit.MINUTE);
      return this;
   }

   public DailyTimeIntervalScheduleBuilder withIntervalInHours(int intervalInHours) {
      this.withInterval(intervalInHours, DateBuilder.IntervalUnit.HOUR);
      return this;
   }

   public DailyTimeIntervalScheduleBuilder onDaysOfTheWeek(Set<Integer> onDaysOfWeek) {
      if (onDaysOfWeek != null && onDaysOfWeek.size() != 0) {
         Iterator i$ = onDaysOfWeek.iterator();

         Integer day;
         do {
            if (!i$.hasNext()) {
               this.daysOfWeek = onDaysOfWeek;
               return this;
            }

            day = (Integer)i$.next();
         } while(ALL_DAYS_OF_THE_WEEK.contains(day));

         throw new IllegalArgumentException("Invalid value for day of week: " + day);
      } else {
         throw new IllegalArgumentException("Days of week must be an non-empty set.");
      }
   }

   public DailyTimeIntervalScheduleBuilder onDaysOfTheWeek(Integer... onDaysOfWeek) {
      Set<Integer> daysAsSet = new HashSet(12);
      Collections.addAll(daysAsSet, onDaysOfWeek);
      return this.onDaysOfTheWeek((Set)daysAsSet);
   }

   public DailyTimeIntervalScheduleBuilder onMondayThroughFriday() {
      this.daysOfWeek = MONDAY_THROUGH_FRIDAY;
      return this;
   }

   public DailyTimeIntervalScheduleBuilder onSaturdayAndSunday() {
      this.daysOfWeek = SATURDAY_AND_SUNDAY;
      return this;
   }

   public DailyTimeIntervalScheduleBuilder onEveryDay() {
      this.daysOfWeek = ALL_DAYS_OF_THE_WEEK;
      return this;
   }

   public DailyTimeIntervalScheduleBuilder startingDailyAt(TimeOfDay timeOfDay) {
      if (timeOfDay == null) {
         throw new IllegalArgumentException("Start time of day cannot be null!");
      } else {
         this.startTimeOfDay = timeOfDay;
         return this;
      }
   }

   public DailyTimeIntervalScheduleBuilder endingDailyAt(TimeOfDay timeOfDay) {
      this.endTimeOfDay = timeOfDay;
      return this;
   }

   public DailyTimeIntervalScheduleBuilder endingDailyAfterCount(int count) {
      if (count <= 0) {
         throw new IllegalArgumentException("Ending daily after count must be a positive number!");
      } else if (this.startTimeOfDay == null) {
         throw new IllegalArgumentException("You must set the startDailyAt() before calling this endingDailyAfterCount()!");
      } else {
         Date today = new Date();
         Date startTimeOfDayDate = this.startTimeOfDay.getTimeOfDayForDate(today);
         Date maxEndTimeOfDayDate = TimeOfDay.hourMinuteAndSecondOfDay(23, 59, 59).getTimeOfDayForDate(today);
         long remainingMillisInDay = maxEndTimeOfDayDate.getTime() - startTimeOfDayDate.getTime();
         long intervalInMillis;
         if (this.intervalUnit == DateBuilder.IntervalUnit.SECOND) {
            intervalInMillis = (long)this.interval * 1000L;
         } else if (this.intervalUnit == DateBuilder.IntervalUnit.MINUTE) {
            intervalInMillis = (long)this.interval * 1000L * 60L;
         } else {
            if (this.intervalUnit != DateBuilder.IntervalUnit.HOUR) {
               throw new IllegalArgumentException("The IntervalUnit: " + this.intervalUnit + " is invalid for this trigger.");
            }

            intervalInMillis = (long)this.interval * 1000L * 60L * 24L;
         }

         if (remainingMillisInDay - intervalInMillis <= 0L) {
            throw new IllegalArgumentException("The startTimeOfDay is too late with given Interval and IntervalUnit values.");
         } else {
            long maxNumOfCount = remainingMillisInDay / intervalInMillis;
            if ((long)count > maxNumOfCount) {
               throw new IllegalArgumentException("The given count " + count + " is too large! The max you can set is " + maxNumOfCount);
            } else {
               long incrementInMillis = (long)(count - 1) * intervalInMillis;
               Date endTimeOfDayDate = new Date(startTimeOfDayDate.getTime() + incrementInMillis);
               if (endTimeOfDayDate.getTime() > maxEndTimeOfDayDate.getTime()) {
                  throw new IllegalArgumentException("The given count " + count + " is too large! The max you can set is " + maxNumOfCount);
               } else {
                  java.util.Calendar cal = java.util.Calendar.getInstance();
                  cal.setTime(endTimeOfDayDate);
                  int hour = cal.get(11);
                  int minute = cal.get(12);
                  int second = cal.get(13);
                  this.endTimeOfDay = TimeOfDay.hourMinuteAndSecondOfDay(hour, minute, second);
                  return this;
               }
            }
         }
      }
   }

   public DailyTimeIntervalScheduleBuilder withMisfireHandlingInstructionIgnoreMisfires() {
      this.misfireInstruction = -1;
      return this;
   }

   public DailyTimeIntervalScheduleBuilder withMisfireHandlingInstructionDoNothing() {
      this.misfireInstruction = 2;
      return this;
   }

   public DailyTimeIntervalScheduleBuilder withMisfireHandlingInstructionFireAndProceed() {
      this.misfireInstruction = 1;
      return this;
   }

   public DailyTimeIntervalScheduleBuilder withRepeatCount(int repeatCount) {
      this.repeatCount = repeatCount;
      return this;
   }

   private void validateInterval(int timeInterval) {
      if (timeInterval <= 0) {
         throw new IllegalArgumentException("Interval must be a positive value.");
      }
   }

   static {
      Set<Integer> t = new HashSet(7);

      int i;
      for(i = 1; i <= 7; ++i) {
         t.add(i);
      }

      ALL_DAYS_OF_THE_WEEK = Collections.unmodifiableSet(t);
      t = new HashSet(5);

      for(i = 2; i <= 6; ++i) {
         t.add(i);
      }

      MONDAY_THROUGH_FRIDAY = Collections.unmodifiableSet(t);
      t = new HashSet(2);
      t.add(1);
      t.add(7);
      SATURDAY_AND_SUNDAY = Collections.unmodifiableSet(t);
   }
}
