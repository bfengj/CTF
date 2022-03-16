package org.quartz.impl.calendar;

import java.io.Serializable;
import java.util.Date;
import java.util.TimeZone;
import org.quartz.Calendar;

public class BaseCalendar implements Calendar, Serializable, Cloneable {
   static final long serialVersionUID = 3106623404629760239L;
   private Calendar baseCalendar;
   private String description;
   private TimeZone timeZone;

   public BaseCalendar() {
   }

   public BaseCalendar(Calendar baseCalendar) {
      this.setBaseCalendar(baseCalendar);
   }

   public BaseCalendar(TimeZone timeZone) {
      this.setTimeZone(timeZone);
   }

   public BaseCalendar(Calendar baseCalendar, TimeZone timeZone) {
      this.setBaseCalendar(baseCalendar);
      this.setTimeZone(timeZone);
   }

   public Object clone() {
      try {
         BaseCalendar clone = (BaseCalendar)super.clone();
         if (this.getBaseCalendar() != null) {
            clone.baseCalendar = (Calendar)this.getBaseCalendar().clone();
         }

         if (this.getTimeZone() != null) {
            clone.timeZone = (TimeZone)this.getTimeZone().clone();
         }

         return clone;
      } catch (CloneNotSupportedException var2) {
         throw new IncompatibleClassChangeError("Not Cloneable.");
      }
   }

   public void setBaseCalendar(Calendar baseCalendar) {
      this.baseCalendar = baseCalendar;
   }

   public Calendar getBaseCalendar() {
      return this.baseCalendar;
   }

   public String getDescription() {
      return this.description;
   }

   public void setDescription(String description) {
      this.description = description;
   }

   public TimeZone getTimeZone() {
      return this.timeZone;
   }

   public void setTimeZone(TimeZone timeZone) {
      this.timeZone = timeZone;
   }

   public boolean isTimeIncluded(long timeStamp) {
      if (timeStamp <= 0L) {
         throw new IllegalArgumentException("timeStamp must be greater 0");
      } else {
         return this.baseCalendar == null || this.baseCalendar.isTimeIncluded(timeStamp);
      }
   }

   public long getNextIncludedTime(long timeStamp) {
      if (timeStamp <= 0L) {
         throw new IllegalArgumentException("timeStamp must be greater 0");
      } else {
         return this.baseCalendar != null ? this.baseCalendar.getNextIncludedTime(timeStamp) : timeStamp;
      }
   }

   protected java.util.Calendar createJavaCalendar(long timeStamp) {
      java.util.Calendar calendar = this.createJavaCalendar();
      calendar.setTime(new Date(timeStamp));
      return calendar;
   }

   protected java.util.Calendar createJavaCalendar() {
      return this.getTimeZone() == null ? java.util.Calendar.getInstance() : java.util.Calendar.getInstance(this.getTimeZone());
   }

   protected java.util.Calendar getStartOfDayJavaCalendar(long timeInMillis) {
      java.util.Calendar startOfDay = this.createJavaCalendar(timeInMillis);
      startOfDay.set(11, 0);
      startOfDay.set(12, 0);
      startOfDay.set(13, 0);
      startOfDay.set(14, 0);
      return startOfDay;
   }

   protected java.util.Calendar getEndOfDayJavaCalendar(long timeInMillis) {
      java.util.Calendar endOfDay = this.createJavaCalendar(timeInMillis);
      endOfDay.set(11, 23);
      endOfDay.set(12, 59);
      endOfDay.set(13, 59);
      endOfDay.set(14, 999);
      return endOfDay;
   }
}
