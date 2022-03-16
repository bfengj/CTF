package org.quartz;

import java.io.Serializable;
import java.util.Comparator;
import java.util.Date;

public interface Trigger extends Serializable, Cloneable, Comparable<Trigger> {
   long serialVersionUID = -3904243490805975570L;
   int MISFIRE_INSTRUCTION_SMART_POLICY = 0;
   int MISFIRE_INSTRUCTION_IGNORE_MISFIRE_POLICY = -1;
   int DEFAULT_PRIORITY = 5;

   TriggerKey getKey();

   JobKey getJobKey();

   String getDescription();

   String getCalendarName();

   JobDataMap getJobDataMap();

   int getPriority();

   boolean mayFireAgain();

   Date getStartTime();

   Date getEndTime();

   Date getNextFireTime();

   Date getPreviousFireTime();

   Date getFireTimeAfter(Date var1);

   Date getFinalFireTime();

   int getMisfireInstruction();

   TriggerBuilder<? extends Trigger> getTriggerBuilder();

   ScheduleBuilder<? extends Trigger> getScheduleBuilder();

   boolean equals(Object var1);

   int compareTo(Trigger var1);

   public static class TriggerTimeComparator implements Comparator<Trigger>, Serializable {
      private static final long serialVersionUID = -3904243490805975570L;

      public static int compare(Date nextFireTime1, int priority1, TriggerKey key1, Date nextFireTime2, int priority2, TriggerKey key2) {
         if (nextFireTime1 != null || nextFireTime2 != null) {
            if (nextFireTime1 == null) {
               return 1;
            }

            if (nextFireTime2 == null) {
               return -1;
            }

            if (nextFireTime1.before(nextFireTime2)) {
               return -1;
            }

            if (nextFireTime1.after(nextFireTime2)) {
               return 1;
            }
         }

         int comp = priority2 - priority1;
         return comp != 0 ? comp : key1.compareTo(key2);
      }

      public int compare(Trigger t1, Trigger t2) {
         return compare(t1.getNextFireTime(), t1.getPriority(), t1.getKey(), t2.getNextFireTime(), t2.getPriority(), t2.getKey());
      }
   }

   public static enum CompletedExecutionInstruction {
      NOOP,
      RE_EXECUTE_JOB,
      SET_TRIGGER_COMPLETE,
      DELETE_TRIGGER,
      SET_ALL_JOB_TRIGGERS_COMPLETE,
      SET_TRIGGER_ERROR,
      SET_ALL_JOB_TRIGGERS_ERROR;
   }

   public static enum TriggerState {
      NONE,
      NORMAL,
      PAUSED,
      COMPLETE,
      ERROR,
      BLOCKED;
   }
}
