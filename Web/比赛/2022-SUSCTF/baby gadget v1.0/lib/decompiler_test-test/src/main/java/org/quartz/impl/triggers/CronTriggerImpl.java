package org.quartz.impl.triggers;

import java.text.ParseException;
import java.util.Calendar;
import java.util.Date;
import java.util.GregorianCalendar;
import java.util.TimeZone;
import org.quartz.CronExpression;
import org.quartz.CronScheduleBuilder;
import org.quartz.CronTrigger;
import org.quartz.ScheduleBuilder;

public class CronTriggerImpl extends AbstractTrigger<CronTrigger> implements CronTrigger, CoreTrigger {
   private static final long serialVersionUID = -8644953146451592766L;
   protected static final int YEAR_TO_GIVEUP_SCHEDULING_AT;
   private CronExpression cronEx;
   private Date startTime;
   private Date endTime;
   private Date nextFireTime;
   private Date previousFireTime;
   private transient TimeZone timeZone;

   public CronTriggerImpl() {
      this.cronEx = null;
      this.startTime = null;
      this.endTime = null;
      this.nextFireTime = null;
      this.previousFireTime = null;
      this.timeZone = null;
      this.setStartTime(new Date());
      this.setTimeZone(TimeZone.getDefault());
   }

   /** @deprecated */
   @Deprecated
   public CronTriggerImpl(String name) {
      this(name, (String)null);
   }

   /** @deprecated */
   @Deprecated
   public CronTriggerImpl(String name, String group) {
      super(name, group);
      this.cronEx = null;
      this.startTime = null;
      this.endTime = null;
      this.nextFireTime = null;
      this.previousFireTime = null;
      this.timeZone = null;
      this.setStartTime(new Date());
      this.setTimeZone(TimeZone.getDefault());
   }

   /** @deprecated */
   @Deprecated
   public CronTriggerImpl(String name, String group, String cronExpression) throws ParseException {
      super(name, group);
      this.cronEx = null;
      this.startTime = null;
      this.endTime = null;
      this.nextFireTime = null;
      this.previousFireTime = null;
      this.timeZone = null;
      this.setCronExpression(cronExpression);
      this.setStartTime(new Date());
      this.setTimeZone(TimeZone.getDefault());
   }

   /** @deprecated */
   @Deprecated
   public CronTriggerImpl(String name, String group, String jobName, String jobGroup) {
      super(name, group, jobName, jobGroup);
      this.cronEx = null;
      this.startTime = null;
      this.endTime = null;
      this.nextFireTime = null;
      this.previousFireTime = null;
      this.timeZone = null;
      this.setStartTime(new Date());
      this.setTimeZone(TimeZone.getDefault());
   }

   /** @deprecated */
   @Deprecated
   public CronTriggerImpl(String name, String group, String jobName, String jobGroup, String cronExpression) throws ParseException {
      this(name, group, jobName, jobGroup, (Date)null, (Date)null, cronExpression, TimeZone.getDefault());
   }

   /** @deprecated */
   @Deprecated
   public CronTriggerImpl(String name, String group, String jobName, String jobGroup, String cronExpression, TimeZone timeZone) throws ParseException {
      this(name, group, jobName, jobGroup, (Date)null, (Date)null, cronExpression, timeZone);
   }

   /** @deprecated */
   @Deprecated
   public CronTriggerImpl(String name, String group, String jobName, String jobGroup, Date startTime, Date endTime, String cronExpression) throws ParseException {
      super(name, group, jobName, jobGroup);
      this.cronEx = null;
      this.startTime = null;
      this.endTime = null;
      this.nextFireTime = null;
      this.previousFireTime = null;
      this.timeZone = null;
      this.setCronExpression(cronExpression);
      if (startTime == null) {
         startTime = new Date();
      }

      this.setStartTime(startTime);
      if (endTime != null) {
         this.setEndTime(endTime);
      }

      this.setTimeZone(TimeZone.getDefault());
   }

   /** @deprecated */
   @Deprecated
   public CronTriggerImpl(String name, String group, String jobName, String jobGroup, Date startTime, Date endTime, String cronExpression, TimeZone timeZone) throws ParseException {
      super(name, group, jobName, jobGroup);
      this.cronEx = null;
      this.startTime = null;
      this.endTime = null;
      this.nextFireTime = null;
      this.previousFireTime = null;
      this.timeZone = null;
      this.setCronExpression(cronExpression);
      if (startTime == null) {
         startTime = new Date();
      }

      this.setStartTime(startTime);
      if (endTime != null) {
         this.setEndTime(endTime);
      }

      if (timeZone == null) {
         this.setTimeZone(TimeZone.getDefault());
      } else {
         this.setTimeZone(timeZone);
      }

   }

   public Object clone() {
      CronTriggerImpl copy = (CronTriggerImpl)super.clone();
      if (this.cronEx != null) {
         copy.setCronExpression(new CronExpression(this.cronEx));
      }

      return copy;
   }

   public void setCronExpression(String cronExpression) throws ParseException {
      TimeZone origTz = this.getTimeZone();
      this.cronEx = new CronExpression(cronExpression);
      this.cronEx.setTimeZone(origTz);
   }

   public String getCronExpression() {
      return this.cronEx == null ? null : this.cronEx.getCronExpression();
   }

   public void setCronExpression(CronExpression cronExpression) {
      this.cronEx = cronExpression;
      this.timeZone = cronExpression.getTimeZone();
   }

   public Date getStartTime() {
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
            Calendar cl = Calendar.getInstance();
            cl.setTime(startTime);
            cl.set(14, 0);
            this.startTime = cl.getTime();
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

   public TimeZone getTimeZone() {
      if (this.cronEx != null) {
         return this.cronEx.getTimeZone();
      } else {
         if (this.timeZone == null) {
            this.timeZone = TimeZone.getDefault();
         }

         return this.timeZone;
      }
   }

   public void setTimeZone(TimeZone timeZone) {
      if (this.cronEx != null) {
         this.cronEx.setTimeZone(timeZone);
      }

      this.timeZone = timeZone;
   }

   public Date getFireTimeAfter(Date afterTime) {
      if (afterTime == null) {
         afterTime = new Date();
      }

      if (this.getStartTime().after(afterTime)) {
         afterTime = new Date(this.getStartTime().getTime() - 1000L);
      }

      if (this.getEndTime() != null && afterTime.compareTo(this.getEndTime()) >= 0) {
         return null;
      } else {
         Date pot = this.getTimeAfter(afterTime);
         return this.getEndTime() != null && pot != null && pot.after(this.getEndTime()) ? null : pot;
      }
   }

   public Date getFinalFireTime() {
      Date resultTime;
      if (this.getEndTime() != null) {
         resultTime = this.getTimeBefore(new Date(this.getEndTime().getTime() + 1000L));
      } else {
         resultTime = this.cronEx == null ? null : this.cronEx.getFinalFireTime();
      }

      return resultTime != null && this.getStartTime() != null && resultTime.before(this.getStartTime()) ? null : resultTime;
   }

   public boolean mayFireAgain() {
      return this.getNextFireTime() != null;
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

   public boolean willFireOn(Calendar test) {
      return this.willFireOn(test, false);
   }

   public boolean willFireOn(Calendar test, boolean dayOnly) {
      test = (Calendar)test.clone();
      test.set(14, 0);
      if (dayOnly) {
         test.set(11, 0);
         test.set(12, 0);
         test.set(13, 0);
      }

      Date testTime = test.getTime();
      Date fta = this.getFireTimeAfter(new Date(test.getTime().getTime() - 1000L));
      if (fta == null) {
         return false;
      } else {
         Calendar p = Calendar.getInstance(test.getTimeZone());
         p.setTime(fta);
         int year = p.get(1);
         int month = p.get(2);
         int day = p.get(5);
         if (dayOnly) {
            return year == test.get(1) && month == test.get(2) && day == test.get(5);
         } else {
            while(fta.before(testTime)) {
               fta = this.getFireTimeAfter(fta);
            }

            return fta.equals(testTime);
         }
      }
   }

   public void triggered(org.quartz.Calendar calendar) {
      this.previousFireTime = this.nextFireTime;

      for(this.nextFireTime = this.getFireTimeAfter(this.nextFireTime); this.nextFireTime != null && calendar != null && !calendar.isTimeIncluded(this.nextFireTime.getTime()); this.nextFireTime = this.getFireTimeAfter(this.nextFireTime)) {
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

            Calendar c = new GregorianCalendar();
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
      for(this.nextFireTime = this.getFireTimeAfter(new Date(this.getStartTime().getTime() - 1000L)); this.nextFireTime != null && calendar != null && !calendar.isTimeIncluded(this.nextFireTime.getTime()); this.nextFireTime = this.getFireTimeAfter(this.nextFireTime)) {
      }

      return this.nextFireTime;
   }

   public String getExpressionSummary() {
      return this.cronEx == null ? null : this.cronEx.getExpressionSummary();
   }

   public boolean hasAdditionalProperties() {
      return false;
   }

   public ScheduleBuilder<CronTrigger> getScheduleBuilder() {
      CronScheduleBuilder cb = CronScheduleBuilder.cronSchedule(this.getCronExpression()).inTimeZone(this.getTimeZone());
      switch(this.getMisfireInstruction()) {
      case 1:
         cb.withMisfireHandlingInstructionFireAndProceed();
         break;
      case 2:
         cb.withMisfireHandlingInstructionDoNothing();
      }

      return cb;
   }

   protected Date getTimeAfter(Date afterTime) {
      return this.cronEx == null ? null : this.cronEx.getTimeAfter(afterTime);
   }

   protected Date getTimeBefore(Date eTime) {
      return this.cronEx == null ? null : this.cronEx.getTimeBefore(eTime);
   }

   static {
      YEAR_TO_GIVEUP_SCHEDULING_AT = CronExpression.MAX_YEAR;
   }
}
