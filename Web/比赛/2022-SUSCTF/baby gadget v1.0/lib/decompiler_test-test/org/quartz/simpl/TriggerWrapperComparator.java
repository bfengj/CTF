package org.quartz.simpl;

import java.io.Serializable;
import java.util.Comparator;
import org.quartz.Trigger;

class TriggerWrapperComparator implements Comparator<TriggerWrapper>, Serializable {
   private static final long serialVersionUID = 8809557142191514261L;
   Trigger.TriggerTimeComparator ttc = new Trigger.TriggerTimeComparator();

   public int compare(TriggerWrapper trig1, TriggerWrapper trig2) {
      return this.ttc.compare((Trigger)trig1.trigger, (Trigger)trig2.trigger);
   }

   public boolean equals(Object obj) {
      return obj instanceof TriggerWrapperComparator;
   }

   public int hashCode() {
      return super.hashCode();
   }
}
