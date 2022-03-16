package org.quartz.impl.calendar;

import java.io.Serializable;
import java.util.TimeZone;
import org.quartz.Calendar;

public class WeeklyCalendar extends BaseCalendar implements Calendar, Serializable {
   static final long serialVersionUID = -6809298821229007586L;
   private boolean[] excludeDays;
   private boolean excludeAll;

   public WeeklyCalendar() {
      this((Calendar)null, (TimeZone)null);
   }

   public WeeklyCalendar(Calendar baseCalendar) {
      this(baseCalendar, (TimeZone)null);
   }

   public WeeklyCalendar(TimeZone timeZone) {
      super((Calendar)null, timeZone);
      this.excludeDays = new boolean[8];
      this.excludeAll = false;
   }

   public WeeklyCalendar(Calendar baseCalendar, TimeZone timeZone) {
      super(baseCalendar, timeZone);
      this.excludeDays = new boolean[8];
      this.excludeAll = false;
      this.excludeDays[1] = true;
      this.excludeDays[7] = true;
      this.excludeAll = this.areAllDaysExcluded();
   }

   public Object clone() {
      WeeklyCalendar clone = (WeeklyCalendar)super.clone();
      clone.excludeDays = (boolean[])this.excludeDays.clone();
      return clone;
   }

   public boolean[] getDaysExcluded() {
      return this.excludeDays;
   }

   public boolean isDayExcluded(int wday) {
      return this.excludeDays[wday];
   }

   public void setDaysExcluded(boolean[] weekDays) {
      if (weekDays != null) {
         this.excludeDays = weekDays;
         this.excludeAll = this.areAllDaysExcluded();
      }
   }

   public void setDayExcluded(int wday, boolean exclude) {
      this.excludeDays[wday] = exclude;
      this.excludeAll = this.areAllDaysExcluded();
   }

   public boolean areAllDaysExcluded() {
      return this.isDayExcluded(1) && this.isDayExcluded(2) && this.isDayExcluded(3) && this.isDayExcluded(4) && this.isDayExcluded(5) && this.isDayExcluded(6) && this.isDayExcluded(7);
   }

   public boolean isTimeIncluded(long timeStamp) {
      if (this.excludeAll) {
         return false;
      } else if (!super.isTimeIncluded(timeStamp)) {
         return false;
      } else {
         java.util.Calendar cl = this.createJavaCalendar(timeStamp);
         int wday = cl.get(7);
         return !this.isDayExcluded(wday);
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
         int wday = cl.get(7);
         if (!this.isDayExcluded(wday)) {
            return timeStamp;
         } else {
            while(this.isDayExcluded(wday)) {
               cl.add(5, 1);
               wday = cl.get(7);
            }

            return cl.getTime().getTime();
         }
      }
   }
}
