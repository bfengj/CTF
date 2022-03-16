package org.quartz.impl.calendar;

import java.io.Serializable;
import java.util.ArrayList;
import java.util.Collections;
import java.util.Iterator;
import java.util.TimeZone;
import org.quartz.Calendar;

public class AnnualCalendar extends BaseCalendar implements Calendar, Serializable {
   static final long serialVersionUID = 7346867105876610961L;
   private ArrayList<java.util.Calendar> excludeDays = new ArrayList();
   private boolean dataSorted = false;

   public AnnualCalendar() {
   }

   public AnnualCalendar(Calendar baseCalendar) {
      super(baseCalendar);
   }

   public AnnualCalendar(TimeZone timeZone) {
      super(timeZone);
   }

   public AnnualCalendar(Calendar baseCalendar, TimeZone timeZone) {
      super(baseCalendar, timeZone);
   }

   public Object clone() {
      AnnualCalendar clone = (AnnualCalendar)super.clone();
      clone.excludeDays = new ArrayList(this.excludeDays);
      return clone;
   }

   public ArrayList<java.util.Calendar> getDaysExcluded() {
      return this.excludeDays;
   }

   public boolean isDayExcluded(java.util.Calendar day) {
      if (day == null) {
         throw new IllegalArgumentException("Parameter day must not be null");
      } else if (!super.isTimeIncluded(day.getTime().getTime())) {
         return true;
      } else {
         int dmonth = day.get(2);
         int dday = day.get(5);
         if (!this.dataSorted) {
            Collections.sort(this.excludeDays, new CalendarComparator());
            this.dataSorted = true;
         }

         Iterator iter = this.excludeDays.iterator();

         java.util.Calendar cl;
         do {
            if (!iter.hasNext()) {
               return false;
            }

            cl = (java.util.Calendar)iter.next();
            if (dmonth < cl.get(2)) {
               return false;
            }
         } while(dday != cl.get(5) || dmonth != cl.get(2));

         return true;
      }
   }

   public void setDaysExcluded(ArrayList<java.util.Calendar> days) {
      if (days == null) {
         this.excludeDays = new ArrayList();
      } else {
         this.excludeDays = days;
      }

      this.dataSorted = false;
   }

   public void setDayExcluded(java.util.Calendar day, boolean exclude) {
      if (exclude) {
         if (this.isDayExcluded(day)) {
            return;
         }

         this.excludeDays.add(day);
         this.dataSorted = false;
      } else {
         if (!this.isDayExcluded(day)) {
            return;
         }

         this.removeExcludedDay(day, true);
      }

   }

   public void removeExcludedDay(java.util.Calendar day) {
      this.removeExcludedDay(day, false);
   }

   private void removeExcludedDay(java.util.Calendar day, boolean isChecked) {
      if (isChecked || this.isDayExcluded(day)) {
         if (!this.excludeDays.remove(day)) {
            int dmonth = day.get(2);
            int dday = day.get(5);
            Iterator iter = this.excludeDays.iterator();

            while(iter.hasNext()) {
               java.util.Calendar cl = (java.util.Calendar)iter.next();
               if (dmonth == cl.get(2) && dday == cl.get(5)) {
                  day = cl;
                  break;
               }
            }

            this.excludeDays.remove(day);
         }
      }
   }

   public boolean isTimeIncluded(long timeStamp) {
      if (!super.isTimeIncluded(timeStamp)) {
         return false;
      } else {
         java.util.Calendar day = this.createJavaCalendar(timeStamp);
         return !this.isDayExcluded(day);
      }
   }

   public long getNextIncludedTime(long timeStamp) {
      long baseTime = super.getNextIncludedTime(timeStamp);
      if (baseTime > 0L && baseTime > timeStamp) {
         timeStamp = baseTime;
      }

      java.util.Calendar day = this.getStartOfDayJavaCalendar(timeStamp);
      if (!this.isDayExcluded(day)) {
         return timeStamp;
      } else {
         while(this.isDayExcluded(day)) {
            day.add(5, 1);
         }

         return day.getTime().getTime();
      }
   }
}
