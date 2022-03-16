package org.quartz.impl.triggers;

import java.util.Calendar;
import java.util.Date;
import org.quartz.ScheduleBuilder;
import org.quartz.SchedulerException;
import org.quartz.SimpleScheduleBuilder;
import org.quartz.SimpleTrigger;

public class SimpleTriggerImpl extends AbstractTrigger<SimpleTrigger> implements SimpleTrigger, CoreTrigger {
   private static final long serialVersionUID = -3735980074222850397L;
   private static final int YEAR_TO_GIVEUP_SCHEDULING_AT = Calendar.getInstance().get(1) + 100;
   private Date startTime;
   private Date endTime;
   private Date nextFireTime;
   private Date previousFireTime;
   private int repeatCount;
   private long repeatInterval;
   private int timesTriggered;
   private boolean complete;

   public SimpleTriggerImpl() {
      this.startTime = null;
      this.endTime = null;
      this.nextFireTime = null;
      this.previousFireTime = null;
      this.repeatCount = 0;
      this.repeatInterval = 0L;
      this.timesTriggered = 0;
      this.complete = false;
   }

   /** @deprecated */
   @Deprecated
   public SimpleTriggerImpl(String name) {
      this(name, (String)null);
   }

   /** @deprecated */
   @Deprecated
   public SimpleTriggerImpl(String name, String group) {
      this(name, group, new Date(), (Date)null, 0, 0L);
   }

   /** @deprecated */
   @Deprecated
   public SimpleTriggerImpl(String name, int repeatCount, long repeatInterval) {
      this(name, (String)null, repeatCount, repeatInterval);
   }

   /** @deprecated */
   @Deprecated
   public SimpleTriggerImpl(String name, String group, int repeatCount, long repeatInterval) {
      this(name, group, new Date(), (Date)null, repeatCount, repeatInterval);
   }

   /** @deprecated */
   @Deprecated
   public SimpleTriggerImpl(String name, Date startTime) {
      this(name, (String)null, startTime);
   }

   /** @deprecated */
   @Deprecated
   public SimpleTriggerImpl(String name, String group, Date startTime) {
      this(name, group, startTime, (Date)null, 0, 0L);
   }

   /** @deprecated */
   @Deprecated
   public SimpleTriggerImpl(String name, Date startTime, Date endTime, int repeatCount, long repeatInterval) {
      this(name, (String)null, startTime, endTime, repeatCount, repeatInterval);
   }

   /** @deprecated */
   @Deprecated
   public SimpleTriggerImpl(String name, String group, Date startTime, Date endTime, int repeatCount, long repeatInterval) {
      super(name, group);
      this.startTime = null;
      this.endTime = null;
      this.nextFireTime = null;
      this.previousFireTime = null;
      this.repeatCount = 0;
      this.repeatInterval = 0L;
      this.timesTriggered = 0;
      this.complete = false;
      this.setStartTime(startTime);
      this.setEndTime(endTime);
      this.setRepeatCount(repeatCount);
      this.setRepeatInterval(repeatInterval);
   }

   /** @deprecated */
   @Deprecated
   public SimpleTriggerImpl(String name, String group, String jobName, String jobGroup, Date startTime, Date endTime, int repeatCount, long repeatInterval) {
      super(name, group, jobName, jobGroup);
      this.startTime = null;
      this.endTime = null;
      this.nextFireTime = null;
      this.previousFireTime = null;
      this.repeatCount = 0;
      this.repeatInterval = 0L;
      this.timesTriggered = 0;
      this.complete = false;
      this.setStartTime(startTime);
      this.setEndTime(endTime);
      this.setRepeatCount(repeatCount);
      this.setRepeatInterval(repeatInterval);
   }

   public Date getStartTime() {
      return this.startTime;
   }

   public void setStartTime(Date startTime) {
      if (startTime == null) {
         throw new IllegalArgumentException("Start time cannot be null");
      } else {
         Date eTime = this.getEndTime();
         if (eTime != null && startTime != null && eTime.before(startTime)) {
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

   public long getRepeatInterval() {
      return this.repeatInterval;
   }

   public void setRepeatInterval(long repeatInterval) {
      if (repeatInterval < 0L) {
         throw new IllegalArgumentException("Repeat interval must be >= 0");
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
      if (misfireInstruction < -1) {
         return false;
      } else {
         return misfireInstruction <= 5;
      }
   }

   public void updateAfterMisfire(org.quartz.Calendar cal) {
      int instr = this.getMisfireInstruction();
      if (instr != -1) {
         if (instr == 0) {
            if (this.getRepeatCount() == 0) {
               instr = 1;
            } else if (this.getRepeatCount() == -1) {
               instr = 4;
            } else {
               instr = 2;
            }
         } else if (instr == 1 && this.getRepeatCount() != 0) {
            instr = 3;
         }

         if (instr == 1) {
            this.setNextFireTime(new Date());
         } else {
            Date newFireTime;
            Calendar c;
            if (instr == 5) {
               newFireTime = this.getFireTimeAfter(new Date());

               while(newFireTime != null && cal != null && !cal.isTimeIncluded(newFireTime.getTime())) {
                  newFireTime = this.getFireTimeAfter(newFireTime);
                  if (newFireTime == null) {
                     break;
                  }

                  c = Calendar.getInstance();
                  c.setTime(newFireTime);
                  if (c.get(1) > YEAR_TO_GIVEUP_SCHEDULING_AT) {
                     newFireTime = null;
                  }
               }

               this.setNextFireTime(newFireTime);
            } else {
               int timesMissed;
               if (instr == 4) {
                  newFireTime = this.getFireTimeAfter(new Date());

                  while(newFireTime != null && cal != null && !cal.isTimeIncluded(newFireTime.getTime())) {
                     newFireTime = this.getFireTimeAfter(newFireTime);
                     if (newFireTime == null) {
                        break;
                     }

                     c = Calendar.getInstance();
                     c.setTime(newFireTime);
                     if (c.get(1) > YEAR_TO_GIVEUP_SCHEDULING_AT) {
                        newFireTime = null;
                     }
                  }

                  if (newFireTime != null) {
                     timesMissed = this.computeNumTimesFiredBetween(this.nextFireTime, newFireTime);
                     this.setTimesTriggered(this.getTimesTriggered() + timesMissed);
                  }

                  this.setNextFireTime(newFireTime);
               } else if (instr == 2) {
                  newFireTime = new Date();
                  if (this.repeatCount != 0 && this.repeatCount != -1) {
                     this.setRepeatCount(this.getRepeatCount() - this.getTimesTriggered());
                     this.setTimesTriggered(0);
                  }

                  if (this.getEndTime() != null && this.getEndTime().before(newFireTime)) {
                     this.setNextFireTime((Date)null);
                  } else {
                     this.setStartTime(newFireTime);
                     this.setNextFireTime(newFireTime);
                  }
               } else if (instr == 3) {
                  newFireTime = new Date();
                  timesMissed = this.computeNumTimesFiredBetween(this.nextFireTime, newFireTime);
                  if (this.repeatCount != 0 && this.repeatCount != -1) {
                     int remainingCount = this.getRepeatCount() - (this.getTimesTriggered() + timesMissed);
                     if (remainingCount <= 0) {
                        remainingCount = 0;
                     }

                     this.setRepeatCount(remainingCount);
                     this.setTimesTriggered(0);
                  }

                  if (this.getEndTime() != null && this.getEndTime().before(newFireTime)) {
                     this.setNextFireTime((Date)null);
                  } else {
                     this.setStartTime(newFireTime);
                     this.setNextFireTime(newFireTime);
                  }
               }
            }
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
      if (this.complete) {
         return null;
      } else if (this.timesTriggered > this.repeatCount && this.repeatCount != -1) {
         return null;
      } else {
         if (afterTime == null) {
            afterTime = new Date();
         }

         if (this.repeatCount == 0 && afterTime.compareTo(this.getStartTime()) >= 0) {
            return null;
         } else {
            long startMillis = this.getStartTime().getTime();
            long afterMillis = afterTime.getTime();
            long endMillis = this.getEndTime() == null ? Long.MAX_VALUE : this.getEndTime().getTime();
            if (endMillis <= afterMillis) {
               return null;
            } else if (afterMillis < startMillis) {
               return new Date(startMillis);
            } else {
               long numberOfTimesExecuted = (afterMillis - startMillis) / this.repeatInterval + 1L;
               if (numberOfTimesExecuted > (long)this.repeatCount && this.repeatCount != -1) {
                  return null;
               } else {
                  Date time = new Date(startMillis + numberOfTimesExecuted * this.repeatInterval);
                  return endMillis <= time.getTime() ? null : time;
               }
            }
         }
      }
   }

   public Date getFireTimeBefore(Date end) {
      if (end.getTime() < this.getStartTime().getTime()) {
         return null;
      } else {
         int numFires = this.computeNumTimesFiredBetween(this.getStartTime(), end);
         return new Date(this.getStartTime().getTime() + (long)numFires * this.repeatInterval);
      }
   }

   public int computeNumTimesFiredBetween(Date start, Date end) {
      if (this.repeatInterval < 1L) {
         return 0;
      } else {
         long time = end.getTime() - start.getTime();
         return (int)(time / this.repeatInterval);
      }
   }

   public Date getFinalFireTime() {
      if (this.repeatCount == 0) {
         return this.startTime;
      } else if (this.repeatCount == -1) {
         return this.getEndTime() == null ? null : this.getFireTimeBefore(this.getEndTime());
      } else {
         long lastTrigger = this.startTime.getTime() + (long)this.repeatCount * this.repeatInterval;
         return this.getEndTime() != null && lastTrigger >= this.getEndTime().getTime() ? this.getFireTimeBefore(this.getEndTime()) : new Date(lastTrigger);
      }
   }

   public boolean mayFireAgain() {
      return this.getNextFireTime() != null;
   }

   public void validate() throws SchedulerException {
      super.validate();
      if (this.repeatCount != 0 && this.repeatInterval < 1L) {
         throw new SchedulerException("Repeat Interval cannot be zero.");
      }
   }

   public boolean hasAdditionalProperties() {
      return false;
   }

   public ScheduleBuilder<SimpleTrigger> getScheduleBuilder() {
      SimpleScheduleBuilder sb = SimpleScheduleBuilder.simpleSchedule().withIntervalInMilliseconds(this.getRepeatInterval()).withRepeatCount(this.getRepeatCount());
      switch(this.getMisfireInstruction()) {
      case 1:
         sb.withMisfireHandlingInstructionFireNow();
         break;
      case 2:
         sb.withMisfireHandlingInstructionNowWithExistingCount();
         break;
      case 3:
         sb.withMisfireHandlingInstructionNowWithRemainingCount();
         break;
      case 4:
         sb.withMisfireHandlingInstructionNextWithRemainingCount();
         break;
      case 5:
         sb.withMisfireHandlingInstructionNextWithExistingCount();
      }

      return sb;
   }
}
