package org.quartz.listeners;

import org.quartz.JobExecutionContext;
import org.quartz.Trigger;
import org.quartz.TriggerListener;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

public abstract class TriggerListenerSupport implements TriggerListener {
   private final Logger log = LoggerFactory.getLogger(this.getClass());

   protected Logger getLog() {
      return this.log;
   }

   public void triggerFired(Trigger trigger, JobExecutionContext context) {
   }

   public boolean vetoJobExecution(Trigger trigger, JobExecutionContext context) {
      return false;
   }

   public void triggerMisfired(Trigger trigger) {
   }

   public void triggerComplete(Trigger trigger, JobExecutionContext context, Trigger.CompletedExecutionInstruction triggerInstructionCode) {
   }
}
