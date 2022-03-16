package org.terracotta.quartz.wrappers;

import java.io.Serializable;
import java.util.Date;
import org.quartz.Calendar;
import org.quartz.JobKey;
import org.quartz.TriggerKey;
import org.quartz.spi.OperableTrigger;

public class TriggerWrapper implements Serializable {
   private final boolean jobDisallowsConcurrence;
   private volatile String lastTerracotaClientId = null;
   private volatile TriggerWrapper.TriggerState state;
   protected final OperableTrigger trigger;

   protected TriggerWrapper(OperableTrigger trigger, boolean jobDisallowsConcurrence) {
      this.state = TriggerWrapper.TriggerState.WAITING;
      this.trigger = trigger;
      this.jobDisallowsConcurrence = jobDisallowsConcurrence;
   }

   public boolean jobDisallowsConcurrence() {
      return this.jobDisallowsConcurrence;
   }

   public String getLastTerracotaClientId() {
      return this.lastTerracotaClientId;
   }

   public int hashCode() {
      return this.getKey().hashCode();
   }

   public String toString() {
      return this.getClass().getSimpleName() + "( " + this.state + ", lastTC=" + this.lastTerracotaClientId + ", " + this.trigger + ")";
   }

   public TriggerKey getKey() {
      return this.trigger.getKey();
   }

   public JobKey getJobKey() {
      return this.trigger.getJobKey();
   }

   public void setState(TriggerWrapper.TriggerState state, String terracottaId, TriggerFacade triggerFacade) {
      if (terracottaId == null) {
         throw new NullPointerException();
      } else {
         this.state = state;
         this.lastTerracotaClientId = terracottaId;
         this.rePut(triggerFacade);
      }
   }

   public TriggerWrapper.TriggerState getState() {
      return this.state;
   }

   public Date getNextFireTime() {
      return this.trigger.getNextFireTime();
   }

   public int getPriority() {
      return this.trigger.getPriority();
   }

   public boolean mayFireAgain() {
      return this.trigger.mayFireAgain();
   }

   public OperableTrigger getTriggerClone() {
      return (OperableTrigger)this.trigger.clone();
   }

   public void updateWithNewCalendar(Calendar cal, long misfireThreshold, TriggerFacade triggerFacade) {
      this.trigger.updateWithNewCalendar(cal, misfireThreshold);
      this.rePut(triggerFacade);
   }

   public String getCalendarName() {
      return this.trigger.getCalendarName();
   }

   public int getMisfireInstruction() {
      return this.trigger.getMisfireInstruction();
   }

   public void updateAfterMisfire(Calendar cal, TriggerFacade triggerFacade) {
      this.trigger.updateAfterMisfire(cal);
      this.rePut(triggerFacade);
   }

   public void setFireInstanceId(String firedInstanceId, TriggerFacade triggerFacade) {
      this.trigger.setFireInstanceId(firedInstanceId);
      this.rePut(triggerFacade);
   }

   public void triggered(Calendar cal, TriggerFacade triggerFacade) {
      this.trigger.triggered(cal);
      this.rePut(triggerFacade);
   }

   private void rePut(TriggerFacade triggerFacade) {
      triggerFacade.put(this.trigger.getKey(), this);
   }

   public boolean isInstanceof(Class clazz) {
      return clazz.isInstance(this.trigger);
   }

   public static enum TriggerState {
      WAITING,
      ACQUIRED,
      COMPLETE,
      PAUSED,
      BLOCKED,
      PAUSED_BLOCKED,
      ERROR;
   }
}
