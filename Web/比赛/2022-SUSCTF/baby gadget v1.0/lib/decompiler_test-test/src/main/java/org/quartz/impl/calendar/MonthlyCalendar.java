package org.quartz.impl.calendar;

import java.io.Serializable;
import java.util.TimeZone;
import org.quartz.Calendar;

public class MonthlyCalendar extends BaseCalendar implements Calendar, Serializable {
   static final long serialVersionUID = 419164961091807944L;
   private static final int MAX_DAYS_IN_MONTH = 31;
   private boolean[] excludeDays;
   private boolean excludeAll;

   public MonthlyCalendar() {
      this((Calendar)null, (TimeZone)null);
   }

   public MonthlyCalendar(Calendar baseCalendar) {
      this(baseCalendar, (TimeZone)null);
   }

   public MonthlyCalendar(TimeZone timeZone) {
      this((Calendar)null, timeZone);
   }

   public MonthlyCalendar(Calendar baseCalendar, TimeZone timeZone) {
      super(baseCalendar, timeZone);
      this.excludeDays = new boolean[31];
      this.excludeAll = false;
      this.excludeAll = this.areAllDaysExcluded();
   }

   public Object clone() {
      MonthlyCalendar clone = (MonthlyCalendar)super.clone();
      clone.excludeDays = (boolean[])this.excludeDays.clone();
      return clone;
   }

   public boolean[] getDaysExcluded() {
      return this.excludeDays;
   }

   public boolean isDayExcluded(int day) {
      if (day >= 1 && day <= 31) {
         return this.excludeDays[day - 1];
      } else {
         throw new IllegalArgumentException("The day parameter must be in the range of 1 to 31");
      }
   }

   public void setDaysExcluded(boolean[] days) {
      if (days == null) {
         throw new IllegalArgumentException("The days parameter cannot be null.");
      } else if (days.length < 31) {
         throw new IllegalArgumentException("The days parameter must have a length of at least 31 elements.");
      } else {
         this.excludeDays = days;
         this.excludeAll = this.areAllDaysExcluded();
      }
   }

   public void setDayExcluded(int day, boolean exclude) {
      if (day >= 1 && day <= 31) {
         this.excludeDays[day - 1] = exclude;
         this.excludeAll = this.areAllDaysExcluded();
      } else {
         throw new IllegalArgumentException("The day parameter must be in the range of 1 to 31");
      }
   }

   public boolean areAllDaysExcluded() {
      for(int i = 1; i <= 31; ++i) {
         if (!this.isDayExcluded(i)) {
            return false;
         }
      }

      return true;
   }

   public boolean isTimeIncluded(long timeStamp) {
      if (this.excludeAll) {
         return false;
      } else if (!super.isTimeIncluded(timeStamp)) {
         return false;
      } else {
         java.util.Calendar cl = this.createJavaCalendar(timeStamp);
         int day = cl.get(5);
         return !this.isDayExcluded(day);
      }
   }

   public long getNextIncludedTime(long timeStamp) {
      if (this.excludeAll) {
         return 0L;
      } else {
         long baseTime = super.getNextIncludedTime(timeStamp);
         if (baseTime > 0L && baseTime > timeStamp) {
            timeStamp = baseTime;
         }

         java.util.Calendar cl = this.getStartOfDayJavaCalendar(timeStamp);
         int day = cl.get(5);
         if (!this.isDayExcluded(day)) {
            return timeStamp;
         } else {
            while(this.isDayExcluded(day)) {
               cl.add(5, 1);
               day = cl.get(5);
            }

            return cl.getTime().getTime();
         }
      }
   }
}
