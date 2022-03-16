package org.quartz.simpl;

import org.quartz.JobKey;
import org.quartz.TriggerKey;
import org.quartz.spi.OperableTrigger;

class TriggerWrapper {
   public final TriggerKey key;
   public final JobKey jobKey;
   public final OperableTrigger trigger;
   public int state = 0;
   public static final int STATE_WAITING = 0;
   public static final int STATE_ACQUIRED = 1;
   public static final int STATE_EXECUTING = 2;
   public static final int STATE_COMPLETE = 3;
   public static final int STATE_PAUSED = 4;
   public static final int STATE_BLOCKED = 5;
   public static final int STATE_PAUSED_BLOCKED = 6;
   public static final int STATE_ERROR = 7;

   TriggerWrapper(OperableTrigger trigger) {
      if (trigger == null) {
         throw new IllegalArgumentException("Trigger cannot be null!");
      } else {
         this.trigger = trigger;
         this.key = trigger.getKey();
         this.jobKey = trigger.getJobKey();
      }
   }

   public boolean equals(Object obj) {
      if (obj instanceof TriggerWrapper) {
         TriggerWrapper tw = (TriggerWrapper)obj;
         if (tw.key.equals(this.key)) {
            return true;
         }
      }

      return false;
   }

   public int hashCode() {
      return this.key.hashCode();
   }

   public OperableTrigger getTrigger() {
      return this.trigger;
   }
}
