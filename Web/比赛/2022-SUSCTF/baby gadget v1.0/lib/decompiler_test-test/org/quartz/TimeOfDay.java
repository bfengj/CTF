package org.quartz;

import java.io.Serializable;
import java.util.Date;
import java.util.TimeZone;

public class TimeOfDay implements Serializable {
   private static final long serialVersionUID = 2964774315889061771L;
   private final int hour;
   private final int minute;
   private final int second;

   public TimeOfDay(int hour, int minute, int second) {
      this.hour = hour;
      this.minute = minute;
      this.second = second;
      this.validate();
   }

   public TimeOfDay(int hour, int minute) {
      this.hour = hour;
      this.minute = minute;
      this.second = 0;
      this.validate();
   }

   private void validate() {
      if (this.hour >= 0 && this.hour <= 23) {
         if (this.minute >= 0 && this.minute <= 59) {
            if (this.second < 0 || this.second > 59) {
               throw new IllegalArgumentException("Second must be from 0 to 59");
            }
         } else {
            throw new IllegalArgumentException("Minute must be from 0 to 59");
         }
      } else {
         throw new IllegalArgumentException("Hour must be from 0 to 23");
      }
   }

   public static TimeOfDay hourMinuteAndSecondOfDay(int hour, int minute, int second) {
      return new TimeOfDay(hour, minute, second);
   }

   public static TimeOfDay hourAndMinuteOfDay(int hour, int minute) {
      return new TimeOfDay(hour, minute);
   }

   public int getHour() {
      return this.hour;
   }

   public int getMinute() {
      return this.minute;
   }

   public int getSecond() {
      return this.second;
   }

   public boolean before(TimeOfDay timeOfDay) {
      if (timeOfDay.hour > this.hour) {
         return true;
      } else if (timeOfDay.hour < this.hour) {
         return false;
      } else if (timeOfDay.minute > this.minute) {
         return true;
      } else if (timeOfDay.minute < this.minute) {
         return false;
      } else if (timeOfDay.second > this.second) {
         return true;
      } else {
         return timeOfDay.second < this.second ? false : false;
      }
   }

   public boolean equals(Object obj) {
      if (!(obj instanceof TimeOfDay)) {
         return false;
      } else {
         TimeOfDay other = (TimeOfDay)obj;
         return other.hour == this.hour && other.minute == this.minute && other.second == this.second;
      }
   }

   public int hashCode() {
      return this.hour + 1 ^ this.minute + 1 ^ this.second + 1;
   }

   public Date getTimeOfDayForDate(Date dateTime) {
      if (dateTime == null) {
         return null;
      } else {
         java.util.Calendar cal = java.util.Calendar.getInstance();
         cal.setTime(dateTime);
         cal.set(11, this.hour);
         cal.set(12, this.minute);
         cal.set(13, this.second);
         cal.clear(14);
         return cal.getTime();
      }
   }

   public static TimeOfDay hourAndMinuteAndSecondFromDate(Date dateTime) {
      return hourAndMinuteAndSecondFromDate(dateTime, (TimeZone)null);
   }

   public static TimeOfDay hourAndMinuteAndSecondFromDate(Date dateTime, TimeZone tz) {
      if (dateTime == null) {
         return null;
      } else {
         java.util.Calendar cal = java.util.Calendar.getInstance();
         cal.setTime(dateTime);
         if (tz != null) {
            cal.setTimeZone(tz);
         }

         return new TimeOfDay(cal.get(11), cal.get(12), cal.get(13));
      }
   }

   public static TimeOfDay hourAndMinuteFromDate(Date dateTime) {
      return hourAndMinuteFromDate(dateTime, (TimeZone)null);
   }

   public static TimeOfDay hourAndMinuteFromDate(Date dateTime, TimeZone tz) {
      if (dateTime == null) {
         return null;
      } else {
         java.util.Calendar cal = java.util.Calendar.getInstance();
         cal.setTime(dateTime);
         if (tz != null) {
            cal.setTimeZone(tz);
         }

         return new TimeOfDay(cal.get(11), cal.get(12));
      }
   }

   public String toString() {
      return "TimeOfDay[" + this.hour + ":" + this.minute + ":" + this.second + "]";
   }
}
