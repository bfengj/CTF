package org.quartz;

import org.quartz.utils.Key;

public final class TriggerKey extends Key<TriggerKey> {
   private static final long serialVersionUID = 8070357886703449660L;

   public TriggerKey(String name) {
      super(name, (String)null);
   }

   public TriggerKey(String name, String group) {
      super(name, group);
   }

   public static TriggerKey triggerKey(String name) {
      return new TriggerKey(name, (String)null);
   }

   public static TriggerKey triggerKey(String name, String group) {
      return new TriggerKey(name, group);
   }
}
