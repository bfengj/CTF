package org.quartz.impl.calendar;

import java.text.ParseException;
import java.util.Date;
import java.util.TimeZone;
import org.quartz.Calendar;
import org.quartz.CronExpression;

public class CronCalendar extends BaseCalendar {
   static final long serialVersionUID = -8172103999750856831L;
   CronExpression cronExpression;

   public CronCalendar(String expression) throws ParseException {
      this((Calendar)null, expression, (TimeZone)null);
   }

   public CronCalendar(Calendar baseCalendar, String expression) throws ParseException {
      this(baseCalendar, expression, (TimeZone)null);
   }

   public CronCalendar(Calendar baseCalendar, String expression, TimeZone timeZone) throws ParseException {
      super(baseCalendar);
      this.cronExpression = new CronExpression(expression);
      this.cronExpression.setTimeZone(timeZone);
   }

   public Object clone() {
      CronCalendar clone = (CronCalendar)super.clone();
      clone.cronExpression = new CronExpression(this.cronExpression);
      return clone;
   }

   public TimeZone getTimeZone() {
      return this.cronExpression.getTimeZone();
   }

   public void setTimeZone(TimeZone timeZone) {
      this.cronExpression.setTimeZone(timeZone);
   }

   public boolean isTimeIncluded(long timeInMillis) {
      if (this.getBaseCalendar() != null && !this.getBaseCalendar().isTimeIncluded(timeInMillis)) {
         return false;
      } else {
         return !this.cronExpression.isSatisfiedBy(new Date(timeInMillis));
      }
   }

   public long getNextIncludedTime(long timeInMillis) {
      long nextIncludedTime = timeInMillis + 1L;

      while(true) {
         while(!this.isTimeIncluded(nextIncludedTime)) {
            if (this.cronExpression.isSatisfiedBy(new Date(nextIncludedTime))) {
               nextIncludedTime = this.cronExpression.getNextInvalidTimeAfter(new Date(nextIncludedTime)).getTime();
            } else if (this.getBaseCalendar() != null && !this.getBaseCalendar().isTimeIncluded(nextIncludedTime)) {
               nextIncludedTime = this.getBaseCalendar().getNextIncludedTime(nextIncludedTime);
            } else {
               ++nextIncludedTime;
            }
         }

         return nextIncludedTime;
      }
   }

   public String toString() {
      StringBuffer buffer = new StringBuffer();
      buffer.append("base calendar: [");
      if (this.getBaseCalendar() != null) {
         buffer.append(this.getBaseCalendar().toString());
      } else {
         buffer.append("null");
      }

      buffer.append("], excluded cron expression: '");
      buffer.append(this.cronExpression);
      buffer.append("'");
      return buffer.toString();
   }

   public CronExpression getCronExpression() {
      return this.cronExpression;
   }

   public void setCronExpression(String expression) throws ParseException {
      CronExpression newExp = new CronExpression(expression);
      this.cronExpression = newExp;
   }

   public void setCronExpression(CronExpression expression) {
      if (expression == null) {
         throw new IllegalArgumentException("expression cannot be null");
      } else {
         this.cronExpression = expression;
      }
   }
}
