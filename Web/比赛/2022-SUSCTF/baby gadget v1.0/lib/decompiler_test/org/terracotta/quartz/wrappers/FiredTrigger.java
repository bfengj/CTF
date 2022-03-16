package org.terracotta.quartz.wrappers;

import java.io.Serializable;
import java.util.Date;
import org.quartz.TriggerKey;

public class FiredTrigger implements Serializable {
   private final String clientId;
   private final TriggerKey triggerKey;
   private final long scheduledFireTime;
   private final long fireTime;

   public FiredTrigger(String clientId, TriggerKey triggerKey, long scheduledFireTime) {
      this.clientId = clientId;
      this.triggerKey = triggerKey;
      this.scheduledFireTime = scheduledFireTime;
      this.fireTime = System.currentTimeMillis();
   }

   public String getClientId() {
      return this.clientId;
   }

   public TriggerKey getTriggerKey() {
      return this.triggerKey;
   }

   public long getScheduledFireTime() {
      return this.scheduledFireTime;
   }

   public long getFireTime() {
      return this.fireTime;
   }

   public String toString() {
      return this.getClass().getSimpleName() + "(" + this.triggerKey + ", " + this.getClientId() + ", " + new Date(this.fireTime) + ")";
   }

   public int hashCode() {
      int prime = true;
      int result = 1;
      int result = 31 * result + (this.clientId == null ? 0 : this.clientId.hashCode());
      result = 31 * result + (int)(this.scheduledFireTime ^ this.scheduledFireTime >>> 32);
      result = 31 * result + (int)(this.fireTime ^ this.fireTime >>> 32);
      result = 31 * result + (this.triggerKey == null ? 0 : this.triggerKey.hashCode());
      return result;
   }

   public boolean equals(Object obj) {
      if (this == obj) {
         return true;
      } else if (obj == null) {
         return false;
      } else if (this.getClass() != obj.getClass()) {
         return false;
      } else {
         FiredTrigger other = (FiredTrigger)obj;
         if (this.clientId == null) {
            if (other.clientId != null) {
               return false;
            }
         } else if (!this.clientId.equals(other.clientId)) {
            return false;
         }

         if (this.scheduledFireTime != other.scheduledFireTime) {
            return false;
         } else if (this.fireTime != other.fireTime) {
            return false;
         } else {
            if (this.triggerKey == null) {
               if (other.triggerKey != null) {
                  return false;
               }
            } else if (!this.triggerKey.equals(other.triggerKey)) {
               return false;
            }

            return true;
         }
      }
   }
}
