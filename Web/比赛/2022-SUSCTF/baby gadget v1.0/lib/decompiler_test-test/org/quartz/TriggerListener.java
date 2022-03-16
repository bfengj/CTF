package org.quartz;

public interface TriggerListener {
   String getName();

   void triggerFired(Trigger var1, JobExecutionContext var2);

   boolean vetoJobExecution(Trigger var1, JobExecutionContext var2);

   void triggerMisfired(Trigger var1);

   void triggerComplete(Trigger var1, JobExecutionContext var2, Trigger.CompletedExecutionInstruction var3);
}
