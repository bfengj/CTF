package org.terracotta.quartz.collections;

import java.util.Iterator;
import org.quartz.TriggerKey;
import org.terracotta.quartz.wrappers.TriggerWrapper;
import org.terracotta.toolkit.collections.ToolkitSortedSet;

public class TimeTriggerSet {
   private final ToolkitSortedSet<TimeTrigger> timeTriggers;

   public TimeTriggerSet(ToolkitSortedSet<TimeTrigger> timeTriggers) {
      this.timeTriggers = timeTriggers;
   }

   public boolean add(TriggerWrapper wrapper) {
      TimeTrigger timeTrigger = new TimeTrigger(wrapper.getKey(), wrapper.getNextFireTime(), wrapper.getPriority());
      return this.timeTriggers.add(timeTrigger);
   }

   public boolean remove(TriggerWrapper wrapper) {
      TimeTrigger timeTrigger = new TimeTrigger(wrapper.getKey(), wrapper.getNextFireTime(), wrapper.getPriority());
      boolean result = this.timeTriggers.remove(timeTrigger);
      return result;
   }

   public TriggerKey removeFirst() {
      Iterator<TimeTrigger> iter = this.timeTriggers.iterator();
      TimeTrigger tt = null;
      if (iter.hasNext()) {
         tt = (TimeTrigger)iter.next();
         iter.remove();
      }

      return tt == null ? null : tt.getTriggerKey();
   }

   public boolean isDestroyed() {
      return this.timeTriggers.isDestroyed();
   }

   public void destroy() {
      this.timeTriggers.destroy();
   }

   public int size() {
      return this.timeTriggers.size();
   }
}
