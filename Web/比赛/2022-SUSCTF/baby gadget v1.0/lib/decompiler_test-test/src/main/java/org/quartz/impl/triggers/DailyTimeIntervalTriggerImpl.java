package org.quartz.impl.triggers;

import java.util.Calendar;
import java.util.Date;
import java.util.Set;
import org.quartz.DailyTimeIntervalScheduleBuilder;
import org.quartz.DailyTimeIntervalTrigger;
import org.quartz.DateBuilder;
import org.quartz.ScheduleBuilder;
import org.quartz.SchedulerException;
import org.quartz.TimeOfDay;

public class DailyTimeIntervalTriggerImpl extends AbstractTrigger<DailyTimeIntervalTrigger> implements DailyTimeIntervalTrigger, CoreTrigger {
   private static final long serialVersionUID = -632667786771388749L;
   private static final int YEAR_TO_GIVEUP_SCHEDULING_AT = Calendar.getInstance().get(1) + 100;
   private Date startTime;
   private Date endTime;
   private Date nextFireTime;
   private Date previousFireTime;
   private int repeatCount;
   private int repeatInterval;
   private DateBuilder.IntervalUnit repeatIntervalUnit;
   private Set<Integer> daysOfWeek;
   private TimeOfDay startTimeOfDay;
   private TimeOfDay endTimeOfDay;
   private int timesTriggered;
   private boolean complete;

   public DailyTimeIntervalTriggerImpl() {
      this.startTime = null;
      this.endTime = null;
      this.nextFireTime = null;
      this.previousFireTime = null;
      this.repeatCount = -1;
      this.repeatInterval = 1;
      this.repeatIntervalUnit = DateBuilder.IntervalUnit.MINUTE;
      this.timesTriggered = 0;
      this.complete = false;
   }

   public DailyTimeIntervalTriggerImpl(String name, TimeOfDay startTimeOfDay, TimeOfDay endTimeOfDay, DateBuilder.IntervalUnit intervalUnit, int repeatInterval) {
      this(name, (String)null, startTimeOfDay, endTimeOfDay, intervalUnit, repeatInterval);
   }

   public DailyTimeIntervalTriggerImpl(String name, String group, TimeOfDay startTimeOfDay, TimeOfDay endTimeOfDay, DateBuilder.IntervalUnit intervalUnit, int repeatInterval) {
      this(name, group, new Date(), (Date)null, startTimeOfDay, endTimeOfDay, intervalUnit, repeatInterval);
   }

   public DailyTimeIntervalTriggerImpl(String name, Date startTime, Date endTime, TimeOfDay startTimeOfDay, TimeOfDay endTimeOfDay, DateBuilder.IntervalUnit intervalUnit, int repeatInterval) {
      this(name, (String)null, startTime, endTime, startTimeOfDay, endTimeOfDay, intervalUnit, repeatInterval);
   }

   public DailyTimeIntervalTriggerImpl(String name, String group, Date startTime, Date endTime, TimeOfDay startTimeOfDay, TimeOfDay endTimeOfDay, DateBuilder.IntervalUnit intervalUnit, int repeatInterval) {
      super(name, group);
      this.startTime = null;
      this.endTime = null;
      this.nextFireTime = null;
      this.previousFireTime = null;
      this.repeatCount = -1;
      this.repeatInterval = 1;
      this.repeatIntervalUnit = DateBuilder.IntervalUnit.MINUTE;
      this.timesTriggered = 0;
      this.complete = false;
      this.setStartTime(startTime);
      this.setEndTime(endTime);
      this.setRepeatIntervalUnit(intervalUnit);
      this.setRepeatInterval(repeatInterval);
      this.setStartTimeOfDay(startTimeOfDay);
      this.setEndTimeOfDay(endTimeOfDay);
   }

   public DailyTimeIntervalTriggerImpl(String name, String group, String jobName, String jobGroup, Date startTime, Date endTime, TimeOfDay startTimeOfDay, TimeOfDay endTimeOfDay, DateBuilder.IntervalUnit intervalUnit, int repeatInterval) {
      super(name, group, jobName, jobGroup);
      this.startTime = null;
      this.endTime = null;
      this.nextFireTime = null;
      this.previousFireTime = null;
      this.repeatCount = -1;
      this.repeatInterval = 1;
      this.repeatIntervalUnit = DateBuilder.IntervalUnit.MINUTE;
      this.timesTriggered = 0;
      this.complete = false;
      this.setStartTime(startTime);
      this.setEndTime(endTime);
      this.setRepeatIntervalUnit(intervalUnit);
      this.setRepeatInterval(repeatInterval);
      this.setStartTimeOfDay(startTimeOfDay);
      this.setEndTimeOfDay(endTimeOfDay);
   }

   public Date getStartTime() {
      if (this.startTime == null) {
         this.startTime = new Date();
      }

      return this.startTime;
   }

   public void setStartTime(Date startTime) {
      if (startTime == null) {
         throw new IllegalArgumentException("Start time cannot be null");
      } else {
         Date eTime = this.getEndTime();
         if (eTime != null && eTime.before(startTime)) {
            throw new IllegalArgumentException("End time cannot be before start time");
         } else {
            this.startTime = startTime;
         }
      }
   }

   public Date getEndTime() {
      return this.endTime;
   }

   public void setEndTime(Date endTime) {
      Date sTime = this.getStartTime();
      if (sTime != null && endTime != null && sTime.after(endTime)) {
         throw new IllegalArgumentException("End time cannot be before start time");
      } else {
         this.endTime = endTime;
      }
   }

   public DateBuilder.IntervalUnit getRepeatIntervalUnit() {
      return this.repeatIntervalUnit;
   }

   public void setRepeatIntervalUnit(DateBuilder.IntervalUnit intervalUnit) {
      if (this.repeatIntervalUnit != null && (this.repeatIntervalUnit.equals(DateBuilder.IntervalUnit.SECOND) || this.repeatIntervalUnit.equals(DateBuilder.IntervalUnit.MINUTE) || this.repeatIntervalUnit.equals(DateBuilder.IntervalUnit.HOUR))) {
         this.repeatIntervalUnit = intervalUnit;
      } else {
         throw new IllegalArgumentException("Invalid repeat IntervalUnit (must be SECOND, MINUTE or HOUR).");
      }
   }

   public int getRepeatInterval() {
      return this.repeatInterval;
   }

   public void setRepeatInterval(int repeatInterval) {
      if (repeatInterval < 0) {
         throw new IllegalArgumentException("Repeat interval must be >= 1");
      } else {
         this.repeatInterval = repeatInterval;
      }
   }

   public int getTimesTriggered() {
      return this.timesTriggered;
   }

   public void setTimesTriggered(int timesTriggered) {
      this.timesTriggered = timesTriggered;
   }

   protected boolean validateMisfireInstruction(int misfireInstruction) {
      return misfireInstruction >= -1 && misfireInstruction <= 2;
   }

   public void updateAfterMisfire(org.quartz.Calendar cal) {
      int instr = this.getMisfireInstruction();
      if (instr != -1) {
         if (instr == 0) {
            instr = 1;
         }

         if (instr == 2) {
            Date newFireTime;
            for(newFireTime = this.getFireTimeAfter(new Date()); newFireTime != null && cal != null && !cal.isTimeIncluded(newFireTime.getTime()); newFireTime = this.getFireTimeAfter(newFireTime)) {
            }

            this.setNextFireTime(newFireTime);
         } else if (instr == 1) {
            this.setNextFireTime(new Date());
         }

      }
   }

   public void triggered(org.quartz.Calendar calendar) {
      ++this.timesTriggered;
      this.previousFireTime = this.nextFireTime;
      this.nextFireTime = this.getFireTimeAfter(this.nextFireTime);

      while(this.nextFireTime != null && calendar != null && !calendar.isTimeIncluded(this.nextFireTime.getTime())) {
         this.nextFireTime = this.getFireTimeAfter(this.nextFireTime);
         if (this.nextFireTime == null) {
            break;
         }

         Calendar c = Calendar.getInstance();
         c.setTime(this.nextFireTime);
         if (c.get(1) > YEAR_TO_GIVEUP_SCHEDULING_AT) {
            this.nextFireTime = null;
         }
      }

      if (this.nextFireTime == null) {
         this.complete = true;
      }

   }

   public void updateWithNewCalendar(org.quartz.Calendar calendar, long misfireThreshold) {
      this.nextFireTime = this.getFireTimeAfter(this.previousFireTime);
      if (this.nextFireTime != null && calendar != null) {
         Date now = new Date();

         while(this.nextFireTime != null && !calendar.isTimeIncluded(this.nextFireTime.getTime())) {
            this.nextFireTime = this.getFireTimeAfter(this.nextFireTime);
            if (this.nextFireTime == null) {
               break;
            }

            Calendar c = Calendar.getInstance();
            c.setTime(this.nextFireTime);
            if (c.get(1) > YEAR_TO_GIVEUP_SCHEDULING_AT) {
               this.nextFireTime = null;
            }

            if (this.nextFireTime != null && this.nextFireTime.before(now)) {
               long diff = now.getTime() - this.nextFireTime.getTime();
               if (diff >= misfireThreshold) {
                  this.nextFireTime = this.getFireTimeAfter(this.nextFireTime);
               }
            }
         }

      }
   }

   public Date computeFirstFireTime(org.quartz.Calendar calendar) {
      Date sTime = this.getStartTime();
      Date startTimeOfDayDate = this.getStartTimeOfDay().getTimeOfDayForDate(sTime);
      if (DateBuilder.evenSecondDate(this.startTime).equals(startTimeOfDayDate)) {
         return this.startTime;
      } else {
         if (sTime.after(startTimeOfDayDate)) {
            this.nextFireTime = this.getFireTimeAfter(sTime);
         } else {
            this.nextFireTime = this.advanceToNextDayOfWeekIfNecessary(startTimeOfDayDate, false);
         }

         while(this.nextFireTime != null && calendar != null && !calendar.isTimeIncluded(this.nextFireTime.getTime())) {
            this.nextFireTime = this.getFireTimeAfter(this.nextFireTime);
            if (this.nextFireTime == null) {
               break;
            }

            Calendar c = Calendar.getInstance();
            c.setTime(this.nextFireTime);
            if (c.get(1) > YEAR_TO_GIVEUP_SCHEDULING_AT) {
               return null;
            }
         }

         return this.nextFireTime;
      }
   }

   private Calendar createCalendarTime(Date dateTime) {
      Calendar cal = Calendar.getInstance();
      cal.setTime(dateTime);
      return cal;
   }

   public Date getNextFireTime() {
      return this.nextFireTime;
   }

   public Date getPreviousFireTime() {
      return this.previousFireTime;
   }

   public void setNextFireTime(Date nextFireTime) {
      this.nextFireTime = nextFireTime;
   }

   public void setPreviousFireTime(Date previousFireTime) {
      this.previousFireTime = previousFireTime;
   }

   public Date getFireTimeAfter(Date afterTime) {
      if (this.complete) {
         return null;
      } else if (this.repeatCount != -1 && this.timesTriggered > this.repeatCount) {
         return null;
      } else {
         if (afterTime == null) {
            afterTime = new Date(System.currentTimeMillis() + 1000L);
         } else {
            afterTime = new Date(afterTime.getTime() + 1000L);
         }

         if (afterTime.before(this.startTime)) {
            afterTime = this.startTime;
         }

         boolean afterTimePastEndTimeOfDay = false;
         if (this.endTimeOfDay != null) {
            afterTimePastEndTimeOfDay = afterTime.getTime() > this.endTimeOfDay.getTimeOfDayForDate(afterTime).getTime();
         }

         Date fireTime = this.advanceToNextDayOfWeekIfNecessary(afterTime, afterTimePastEndTimeOfDay);
         if (fireTime == null) {
            return null;
         } else {
            Date fireTimeEndDate = null;
            if (this.endTimeOfDay == null) {
               fireTimeEndDate = (new TimeOfDay(23, 59, 59)).getTimeOfDayForDate(fireTime);
            } else {
               fireTimeEndDate = this.endTimeOfDay.getTimeOfDayForDate(fireTime);
            }

            Date fireTimeStartDate = this.startTimeOfDay.getTimeOfDayForDate(fireTime);
            if (fireTime.before(fireTimeStartDate)) {
               return fireTimeStartDate;
            } else {
               long fireMillis = fireTime.getTime();
               long startMillis = fireTimeStartDate.getTime();
               long secondsAfterStart = (fireMillis - startMillis) / 1000L;
               long repeatLong = (long)this.getRepeatInterval();
               Calendar sTime = this.createCalendarTime(fireTimeStartDate);
               DateBuilder.IntervalUnit repeatUnit = this.getRepeatIntervalUnit();
               long jumpCount;
               if (repeatUnit.equals(DateBuilder.IntervalUnit.SECOND)) {
                  jumpCount = secondsAfterStart / repeatLong;
                  if (secondsAfterStart % repeatLong != 0L) {
                     ++jumpCount;
                  }

                  sTime.add(13, this.getRepeatInterval() * (int)jumpCount);
                  fireTime = sTime.getTime();
               } else if (repeatUnit.equals(DateBuilder.IntervalUnit.MINUTE)) {
                  jumpCount = secondsAfterStart / (repeatLong * 60L);
                  if (secondsAfterStart % (repeatLong * 60L) != 0L) {
                     ++jumpCount;
                  }

                  sTime.add(12, this.getRepeatInterval() * (int)jumpCount);
                  fireTime = sTime.getTime();
               } else if (repeatUnit.equals(DateBuilder.IntervalUnit.HOUR)) {
                  jumpCount = secondsAfterStart / (repeatLong * 60L * 60L);
                  if (secondsAfterStart % (repeatLong * 60L * 60L) != 0L) {
                     ++jumpCount;
                  }

                  sTime.add(11, this.getRepeatInterval() * (int)jumpCount);
                  fireTime = sTime.getTime();
               }

               if (fireTime.after(fireTimeEndDate)) {
                  fireTime = this.advanceToNextDayOfWeekIfNecessary(fireTime, this.isSameDay(fireTime, fireTimeEndDate));
                  fireTime = this.startTimeOfDay.getTimeOfDayForDate(fireTime);
               }

               return fireTime;
            }
         }
      }
   }

   private boolean isSameDay(Date d1, Date d2) {
      Calendar c1 = this.createCalendarTime(d1);
      Calendar c2 = this.createCalendarTime(d2);
      return c1.get(1) == c2.get(1) && c1.get(6) == c2.get(6);
   }

   private Date advanceToNextDayOfWeekIfNecessary(Date fireTime, boolean forceToAdvanceNextDay) {
      TimeOfDay sTimeOfDay = this.getStartTimeOfDay();
      Date fireTimeStartDate = sTimeOfDay.getTimeOfDayForDate(fireTime);
      Calendar fireTimeStartDateCal = this.createCalendarTime(fireTimeStartDate);
      int dayOfWeekOfFireTime = fireTimeStartDateCal.get(7);
      Set<Integer> daysOfWeekToFire = this.getDaysOfWeek();
      if (forceToAdvanceNextDay || !daysOfWeekToFire.contains(dayOfWeekOfFireTime)) {
         for(int i = 1; i <= 7; ++i) {
            fireTimeStartDateCal.add(5, 1);
            dayOfWeekOfFireTime = fireTimeStartDateCal.get(7);
            if (daysOfWeekToFire.contains(dayOfWeekOfFireTime)) {
               fireTime = fireTimeStartDateCal.getTime();
               break;
            }
         }
      }

      Date eTime = this.getEndTime();
      return eTime != null && fireTime.getTime() > eTime.getTime() ? null : fireTime;
   }

   public Date getFinalFireTime() {
      if (!this.complete && this.getEndTime() != null) {
         Date eTime = this.getEndTime();
         if (this.endTimeOfDay != null) {
            Date endTimeOfDayDate = this.endTimeOfDay.getTimeOfDayForDate(eTime);
            if (eTime.getTime() < endTimeOfDayDate.getTime()) {
               eTime = endTimeOfDayDate;
            }
         }

         return eTime;
      } else {
         return null;
      }
   }

   public boolean mayFireAgain() {
      return this.getNextFireTime() != null;
   }

   public void validate() throws SchedulerException {
      super.validate();
      if (this.repeatIntervalUnit != null && (this.repeatIntervalUnit.equals(DateBuilder.IntervalUnit.SECOND) || this.repeatIntervalUnit.equals(DateBuilder.IntervalUnit.MINUTE) || this.repeatIntervalUnit.equals(DateBuilder.IntervalUnit.HOUR))) {
         if (this.repeatInterval < 1) {
            throw new SchedulerException("Repeat Interval cannot be zero.");
         } else {
            long secondsInHour = 86400L;
            if (this.repeatIntervalUnit == DateBuilder.IntervalUnit.SECOND && (long)this.repeatInterval > secondsInHour) {
               throw new SchedulerException("repeatInterval can not exceed 24 hours (" + secondsInHour + " seconds). Given " + this.repeatInterval);
            } else if (this.repeatIntervalUnit == DateBuilder.IntervalUnit.MINUTE && (long)this.repeatInterval > secondsInHour / 60L) {
               throw new SchedulerException("repeatInterval can not exceed 24 hours (" + secondsInHour / 60L + " minutes). Given " + this.repeatInterval);
            } else if (this.repeatIntervalUnit == DateBuilder.IntervalUnit.HOUR && this.repeatInterval > 24) {
               throw new SchedulerException("repeatInterval can not exceed 24 hours. Given " + this.repeatInterval + " hours.");
            } else if (this.getEndTimeOfDay() != null && !this.getStartTimeOfDay().before(this.getEndTimeOfDay())) {
               throw new SchedulerException("StartTimeOfDay " + this.startTimeOfDay + " should not come after endTimeOfDay " + this.endTimeOfDay);
            }
         }
      } else {
         throw new SchedulerException("Invalid repeat IntervalUnit (must be SECOND, MINUTE or HOUR).");
      }
   }

   public Set<Integer> getDaysOfWeek() {
      if (this.daysOfWeek == null) {
         this.daysOfWeek = DailyTimeIntervalScheduleBuilder.ALL_DAYS_OF_THE_WEEK;
      }

      return this.daysOfWeek;
   }

   public void setDaysOfWeek(Set<Integer> daysOfWeek) {
      if (daysOfWeek != null && daysOfWeek.size() != 0) {
         if (daysOfWeek.size() == 0) {
            throw new IllegalArgumentException("DaysOfWeek set must contain at least one day.");
         } else {
            this.daysOfWeek = daysOfWeek;
         }
      } else {
         throw new IllegalArgumentException("DaysOfWeek set must be a set that contains at least one day.");
      }
   }

   public TimeOfDay getStartTimeOfDay() {
      if (this.startTimeOfDay == null) {
         this.startTimeOfDay = new TimeOfDay(0, 0, 0);
      }

      return this.startTimeOfDay;
   }

   public void setStartTimeOfDay(TimeOfDay startTimeOfDay) {
      if (startTimeOfDay == null) {
         throw new IllegalArgumentException("Start time of day cannot be null");
      } else {
         TimeOfDay eTime = this.getEndTimeOfDay();
         if (eTime != null && eTime.before(startTimeOfDay)) {
            throw new IllegalArgumentException("End time of day cannot be before start time of day");
         } else {
            this.startTimeOfDay = startTimeOfDay;
         }
      }
   }

   public TimeOfDay getEndTimeOfDay() {
      return this.endTimeOfDay;
   }

   public void setEndTimeOfDay(TimeOfDay endTimeOfDay) {
      if (endTimeOfDay == null) {
         throw new IllegalArgumentException("End time of day cannot be null");
      } else {
         TimeOfDay sTime = this.getStartTimeOfDay();
         if (sTime != null && endTimeOfDay.before(endTimeOfDay)) {
            throw new IllegalArgumentException("End time of day cannot be before start time of day");
         } else {
            this.endTimeOfDay = endTimeOfDay;
         }
      }
   }

   public ScheduleBuilder<DailyTimeIntervalTrigger> getScheduleBuilder() {
      DailyTimeIntervalScheduleBuilder cb = DailyTimeIntervalScheduleBuilder.dailyTimeIntervalSchedule().withInterval(this.getRepeatInterval(), this.getRepeatIntervalUnit()).onDaysOfTheWeek(this.getDaysOfWeek()).startingDailyAt(this.getStartTimeOfDay()).endingDailyAt(this.getEndTimeOfDay());
      switch(this.getMisfireInstruction()) {
      case 1:
         cb.withMisfireHandlingInstructionFireAndProceed();
         break;
      case 2:
         cb.withMisfireHandlingInstructionDoNothing();
      }

      return cb;
   }

   public boolean hasAdditionalProperties() {
      return false;
   }

   public int getRepeatCount() {
      return this.repeatCount;
   }

   public void setRepeatCount(int repeatCount) {
      if (repeatCount < 0 && repeatCount != -1) {
         throw new IllegalArgumentException("Repeat count must be >= 0, use the constant REPEAT_INDEFINITELY for infinite.");
      } else {
         this.repeatCount = repeatCount;
      }
   }
}
