package org.quartz.impl.triggers;

import java.util.Calendar;
import java.util.Date;
import java.util.TimeZone;
import org.quartz.CalendarIntervalScheduleBuilder;
import org.quartz.CalendarIntervalTrigger;
import org.quartz.DateBuilder;
import org.quartz.ScheduleBuilder;
import org.quartz.SchedulerException;

public class CalendarIntervalTriggerImpl extends AbstractTrigger<CalendarIntervalTrigger> implements CalendarIntervalTrigger, CoreTrigger {
   private static final long serialVersionUID = -2635982274232850343L;
   private static final int YEAR_TO_GIVEUP_SCHEDULING_AT = Calendar.getInstance().get(1) + 100;
   private Date startTime;
   private Date endTime;
   private Date nextFireTime;
   private Date previousFireTime;
   private int repeatInterval;
   private DateBuilder.IntervalUnit repeatIntervalUnit;
   private TimeZone timeZone;
   private boolean preserveHourOfDayAcrossDaylightSavings;
   private boolean skipDayIfHourDoesNotExist;
   private int timesTriggered;
   private boolean complete;

   public CalendarIntervalTriggerImpl() {
      this.startTime = null;
      this.endTime = null;
      this.nextFireTime = null;
      this.previousFireTime = null;
      this.repeatInterval = 0;
      this.repeatIntervalUnit = DateBuilder.IntervalUnit.DAY;
      this.preserveHourOfDayAcrossDaylightSavings = false;
      this.skipDayIfHourDoesNotExist = false;
      this.timesTriggered = 0;
      this.complete = false;
   }

   public CalendarIntervalTriggerImpl(String name, DateBuilder.IntervalUnit intervalUnit, int repeatInterval) {
      this(name, (String)null, intervalUnit, repeatInterval);
   }

   public CalendarIntervalTriggerImpl(String name, String group, DateBuilder.IntervalUnit intervalUnit, int repeatInterval) {
      this(name, group, new Date(), (Date)null, intervalUnit, repeatInterval);
   }

   public CalendarIntervalTriggerImpl(String name, Date startTime, Date endTime, DateBuilder.IntervalUnit intervalUnit, int repeatInterval) {
      this(name, (String)null, startTime, endTime, intervalUnit, repeatInterval);
   }

   public CalendarIntervalTriggerImpl(String name, String group, Date startTime, Date endTime, DateBuilder.IntervalUnit intervalUnit, int repeatInterval) {
      super(name, group);
      this.startTime = null;
      this.endTime = null;
      this.nextFireTime = null;
      this.previousFireTime = null;
      this.repeatInterval = 0;
      this.repeatIntervalUnit = DateBuilder.IntervalUnit.DAY;
      this.preserveHourOfDayAcrossDaylightSavings = false;
      this.skipDayIfHourDoesNotExist = false;
      this.timesTriggered = 0;
      this.complete = false;
      this.setStartTime(startTime);
      this.setEndTime(endTime);
      this.setRepeatIntervalUnit(intervalUnit);
      this.setRepeatInterval(repeatInterval);
   }

   public CalendarIntervalTriggerImpl(String name, String group, String jobName, String jobGroup, Date startTime, Date endTime, DateBuilder.IntervalUnit intervalUnit, int repeatInterval) {
      super(name, group, jobName, jobGroup);
      this.startTime = null;
      this.endTime = null;
      this.nextFireTime = null;
      this.previousFireTime = null;
      this.repeatInterval = 0;
      this.repeatIntervalUnit = DateBuilder.IntervalUnit.DAY;
      this.preserveHourOfDayAcrossDaylightSavings = false;
      this.skipDayIfHourDoesNotExist = false;
      this.timesTriggered = 0;
      this.complete = false;
      this.setStartTime(startTime);
      this.setEndTime(endTime);
      this.setRepeatIntervalUnit(intervalUnit);
      this.setRepeatInterval(repeatInterval);
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
      this.repeatIntervalUnit = intervalUnit;
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

   public TimeZone getTimeZone() {
      if (this.timeZone == null) {
         this.timeZone = TimeZone.getDefault();
      }

      return this.timeZone;
   }

   public void setTimeZone(TimeZone timeZone) {
      this.timeZone = timeZone;
   }

   public boolean isPreserveHourOfDayAcrossDaylightSavings() {
      return this.preserveHourOfDayAcrossDaylightSavings;
   }

   public void setPreserveHourOfDayAcrossDaylightSavings(boolean preserveHourOfDayAcrossDaylightSavings) {
      this.preserveHourOfDayAcrossDaylightSavings = preserveHourOfDayAcrossDaylightSavings;
   }

   public boolean isSkipDayIfHourDoesNotExist() {
      return this.skipDayIfHourDoesNotExist;
   }

   public void setSkipDayIfHourDoesNotExist(boolean skipDayIfHourDoesNotExist) {
      this.skipDayIfHourDoesNotExist = skipDayIfHourDoesNotExist;
   }

   public int getTimesTriggered() {
      return this.timesTriggered;
   }

   public void setTimesTriggered(int timesTriggered) {
      this.timesTriggered = timesTriggered;
   }

   protected boolean validateMisfireInstruction(int misfireInstruction) {
      if (misfireInstruction < -1) {
         return false;
      } else {
         return misfireInstruction <= 2;
      }
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
      this.nextFireTime = this.getStartTime();

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
      return this.getFireTimeAfter(afterTime, false);
   }

   protected Date getFireTimeAfter(Date afterTime, boolean ignoreEndTime) {
      if (this.complete) {
         return null;
      } else {
         if (afterTime == null) {
            afterTime = new Date();
         }

         long startMillis = this.getStartTime().getTime();
         long afterMillis = afterTime.getTime();
         long endMillis = this.getEndTime() == null ? Long.MAX_VALUE : this.getEndTime().getTime();
         if (!ignoreEndTime && endMillis <= afterMillis) {
            return null;
         } else if (afterMillis < startMillis) {
            return new Date(startMillis);
         } else {
            long secondsAfterStart = 1L + (afterMillis - startMillis) / 1000L;
            Date time = null;
            long repeatLong = (long)this.getRepeatInterval();
            Calendar aTime = Calendar.getInstance();
            aTime.setTime(afterTime);
            Calendar sTime = Calendar.getInstance();
            if (this.timeZone != null) {
               sTime.setTimeZone(this.timeZone);
            }

            sTime.setTime(this.getStartTime());
            sTime.setLenient(true);
            long jumpCount;
            if (this.getRepeatIntervalUnit().equals(DateBuilder.IntervalUnit.SECOND)) {
               jumpCount = secondsAfterStart / repeatLong;
               if (secondsAfterStart % repeatLong != 0L) {
                  ++jumpCount;
               }

               sTime.add(13, this.getRepeatInterval() * (int)jumpCount);
               time = sTime.getTime();
            } else if (this.getRepeatIntervalUnit().equals(DateBuilder.IntervalUnit.MINUTE)) {
               jumpCount = secondsAfterStart / (repeatLong * 60L);
               if (secondsAfterStart % (repeatLong * 60L) != 0L) {
                  ++jumpCount;
               }

               sTime.add(12, this.getRepeatInterval() * (int)jumpCount);
               time = sTime.getTime();
            } else if (this.getRepeatIntervalUnit().equals(DateBuilder.IntervalUnit.HOUR)) {
               jumpCount = secondsAfterStart / (repeatLong * 60L * 60L);
               if (secondsAfterStart % (repeatLong * 60L * 60L) != 0L) {
                  ++jumpCount;
               }

               sTime.add(11, this.getRepeatInterval() * (int)jumpCount);
               time = sTime.getTime();
            } else {
               int initialHourOfDay = sTime.get(11);
               long jumpCount;
               if (this.getRepeatIntervalUnit().equals(DateBuilder.IntervalUnit.DAY)) {
                  sTime.setLenient(true);
                  jumpCount = secondsAfterStart / (repeatLong * 24L * 60L * 60L);
                  if (jumpCount > 20L) {
                     if (jumpCount < 50L) {
                        jumpCount = (long)((double)jumpCount * 0.8D);
                     } else if (jumpCount < 500L) {
                        jumpCount = (long)((double)jumpCount * 0.9D);
                     } else {
                        jumpCount = (long)((double)jumpCount * 0.95D);
                     }

                     sTime.add(6, (int)((long)this.getRepeatInterval() * jumpCount));
                  }

                  while(!sTime.getTime().after(afterTime) && sTime.get(1) < YEAR_TO_GIVEUP_SCHEDULING_AT) {
                     sTime.add(6, this.getRepeatInterval());
                  }

                  while(this.daylightSavingHourShiftOccurredAndAdvanceNeeded(sTime, initialHourOfDay, afterTime) && sTime.get(1) < YEAR_TO_GIVEUP_SCHEDULING_AT) {
                     sTime.add(6, this.getRepeatInterval());
                  }

                  time = sTime.getTime();
               } else if (this.getRepeatIntervalUnit().equals(DateBuilder.IntervalUnit.WEEK)) {
                  sTime.setLenient(true);
                  jumpCount = secondsAfterStart / (repeatLong * 7L * 24L * 60L * 60L);
                  if (jumpCount > 20L) {
                     if (jumpCount < 50L) {
                        jumpCount = (long)((double)jumpCount * 0.8D);
                     } else if (jumpCount < 500L) {
                        jumpCount = (long)((double)jumpCount * 0.9D);
                     } else {
                        jumpCount = (long)((double)jumpCount * 0.95D);
                     }

                     sTime.add(3, (int)((long)this.getRepeatInterval() * jumpCount));
                  }

                  while(!sTime.getTime().after(afterTime) && sTime.get(1) < YEAR_TO_GIVEUP_SCHEDULING_AT) {
                     sTime.add(3, this.getRepeatInterval());
                  }

                  while(this.daylightSavingHourShiftOccurredAndAdvanceNeeded(sTime, initialHourOfDay, afterTime) && sTime.get(1) < YEAR_TO_GIVEUP_SCHEDULING_AT) {
                     sTime.add(3, this.getRepeatInterval());
                  }

                  time = sTime.getTime();
               } else if (this.getRepeatIntervalUnit().equals(DateBuilder.IntervalUnit.MONTH)) {
                  sTime.setLenient(true);

                  while(!sTime.getTime().after(afterTime) && sTime.get(1) < YEAR_TO_GIVEUP_SCHEDULING_AT) {
                     sTime.add(2, this.getRepeatInterval());
                  }

                  while(this.daylightSavingHourShiftOccurredAndAdvanceNeeded(sTime, initialHourOfDay, afterTime) && sTime.get(1) < YEAR_TO_GIVEUP_SCHEDULING_AT) {
                     sTime.add(2, this.getRepeatInterval());
                  }

                  time = sTime.getTime();
               } else if (this.getRepeatIntervalUnit().equals(DateBuilder.IntervalUnit.YEAR)) {
                  while(!sTime.getTime().after(afterTime) && sTime.get(1) < YEAR_TO_GIVEUP_SCHEDULING_AT) {
                     sTime.add(1, this.getRepeatInterval());
                  }

                  while(this.daylightSavingHourShiftOccurredAndAdvanceNeeded(sTime, initialHourOfDay, afterTime) && sTime.get(1) < YEAR_TO_GIVEUP_SCHEDULING_AT) {
                     sTime.add(1, this.getRepeatInterval());
                  }

                  time = sTime.getTime();
               }
            }

            return !ignoreEndTime && endMillis <= time.getTime() ? null : time;
         }
      }
   }

   private boolean daylightSavingHourShiftOccurredAndAdvanceNeeded(Calendar newTime, int initialHourOfDay, Date afterTime) {
      if (this.isPreserveHourOfDayAcrossDaylightSavings() && newTime.get(11) != initialHourOfDay) {
         newTime.set(11, initialHourOfDay);
         if (newTime.get(11) != initialHourOfDay) {
            return this.isSkipDayIfHourDoesNotExist();
         } else {
            return !newTime.getTime().after(afterTime);
         }
      } else {
         return false;
      }
   }

   public Date getFinalFireTime() {
      if (!this.complete && this.getEndTime() != null) {
         Date fTime = new Date(this.getEndTime().getTime() - 1000L);
         fTime = this.getFireTimeAfter(fTime, true);
         if (fTime.equals(this.getEndTime())) {
            return fTime;
         } else {
            Calendar lTime = Calendar.getInstance();
            if (this.timeZone != null) {
               lTime.setTimeZone(this.timeZone);
            }

            lTime.setTime(fTime);
            lTime.setLenient(true);
            if (this.getRepeatIntervalUnit().equals(DateBuilder.IntervalUnit.SECOND)) {
               lTime.add(13, -1 * this.getRepeatInterval());
            } else if (this.getRepeatIntervalUnit().equals(DateBuilder.IntervalUnit.MINUTE)) {
               lTime.add(12, -1 * this.getRepeatInterval());
            } else if (this.getRepeatIntervalUnit().equals(DateBuilder.IntervalUnit.HOUR)) {
               lTime.add(11, -1 * this.getRepeatInterval());
            } else if (this.getRepeatIntervalUnit().equals(DateBuilder.IntervalUnit.DAY)) {
               lTime.add(6, -1 * this.getRepeatInterval());
            } else if (this.getRepeatIntervalUnit().equals(DateBuilder.IntervalUnit.WEEK)) {
               lTime.add(3, -1 * this.getRepeatInterval());
            } else if (this.getRepeatIntervalUnit().equals(DateBuilder.IntervalUnit.MONTH)) {
               lTime.add(2, -1 * this.getRepeatInterval());
            } else if (this.getRepeatIntervalUnit().equals(DateBuilder.IntervalUnit.YEAR)) {
               lTime.add(1, -1 * this.getRepeatInterval());
            }

            return lTime.getTime();
         }
      } else {
         return null;
      }
   }

   public boolean mayFireAgain() {
      return this.getNextFireTime() != null;
   }

   public void validate() throws SchedulerException {
      super.validate();
      if (this.repeatInterval < 1) {
         throw new SchedulerException("Repeat Interval cannot be zero.");
      }
   }

   public ScheduleBuilder<CalendarIntervalTrigger> getScheduleBuilder() {
      CalendarIntervalScheduleBuilder cb = CalendarIntervalScheduleBuilder.calendarIntervalSchedule().withInterval(this.getRepeatInterval(), this.getRepeatIntervalUnit());
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
}
