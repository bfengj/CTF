package org.quartz.plugins.history;

import java.text.MessageFormat;
import java.util.Date;
import org.quartz.JobExecutionContext;
import org.quartz.Matcher;
import org.quartz.Scheduler;
import org.quartz.SchedulerException;
import org.quartz.Trigger;
import org.quartz.TriggerListener;
import org.quartz.impl.matchers.EverythingMatcher;
import org.quartz.spi.ClassLoadHelper;
import org.quartz.spi.SchedulerPlugin;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

public class LoggingTriggerHistoryPlugin implements SchedulerPlugin, TriggerListener {
   private String name;
   private String triggerFiredMessage = "Trigger {1}.{0} fired job {6}.{5} at: {4, date, HH:mm:ss MM/dd/yyyy}";
   private String triggerMisfiredMessage = "Trigger {1}.{0} misfired job {6}.{5}  at: {4, date, HH:mm:ss MM/dd/yyyy}.  Should have fired at: {3, date, HH:mm:ss MM/dd/yyyy}";
   private String triggerCompleteMessage = "Trigger {1}.{0} completed firing job {6}.{5} at {4, date, HH:mm:ss MM/dd/yyyy} with resulting trigger instruction code: {9}";
   private final Logger log = LoggerFactory.getLogger(this.getClass());

   protected Logger getLog() {
      return this.log;
   }

   public String getTriggerCompleteMessage() {
      return this.triggerCompleteMessage;
   }

   public String getTriggerFiredMessage() {
      return this.triggerFiredMessage;
   }

   public String getTriggerMisfiredMessage() {
      return this.triggerMisfiredMessage;
   }

   public void setTriggerCompleteMessage(String triggerCompleteMessage) {
      this.triggerCompleteMessage = triggerCompleteMessage;
   }

   public void setTriggerFiredMessage(String triggerFiredMessage) {
      this.triggerFiredMessage = triggerFiredMessage;
   }

   public void setTriggerMisfiredMessage(String triggerMisfiredMessage) {
      this.triggerMisfiredMessage = triggerMisfiredMessage;
   }

   public void initialize(String pname, Scheduler scheduler, ClassLoadHelper classLoadHelper) throws SchedulerException {
      this.name = pname;
      scheduler.getListenerManager().addTriggerListener(this, (Matcher)EverythingMatcher.allTriggers());
   }

   public void start() {
   }

   public void shutdown() {
   }

   public String getName() {
      return this.name;
   }

   public void triggerFired(Trigger trigger, JobExecutionContext context) {
      if (this.getLog().isInfoEnabled()) {
         Object[] args = new Object[]{trigger.getKey().getName(), trigger.getKey().getGroup(), trigger.getPreviousFireTime(), trigger.getNextFireTime(), new Date(), context.getJobDetail().getKey().getName(), context.getJobDetail().getKey().getGroup(), context.getRefireCount()};
         this.getLog().info(MessageFormat.format(this.getTriggerFiredMessage(), args));
      }
   }

   public void triggerMisfired(Trigger trigger) {
      if (this.getLog().isInfoEnabled()) {
         Object[] args = new Object[]{trigger.getKey().getName(), trigger.getKey().getGroup(), trigger.getPreviousFireTime(), trigger.getNextFireTime(), new Date(), trigger.getJobKey().getName(), trigger.getJobKey().getGroup()};
         this.getLog().info(MessageFormat.format(this.getTriggerMisfiredMessage(), args));
      }
   }

   public void triggerComplete(Trigger trigger, JobExecutionContext context, Trigger.CompletedExecutionInstruction triggerInstructionCode) {
      if (this.getLog().isInfoEnabled()) {
         String instrCode = "UNKNOWN";
         if (triggerInstructionCode == Trigger.CompletedExecutionInstruction.DELETE_TRIGGER) {
            instrCode = "DELETE TRIGGER";
         } else if (triggerInstructionCode == Trigger.CompletedExecutionInstruction.NOOP) {
            instrCode = "DO NOTHING";
         } else if (triggerInstructionCode == Trigger.CompletedExecutionInstruction.RE_EXECUTE_JOB) {
            instrCode = "RE-EXECUTE JOB";
         } else if (triggerInstructionCode == Trigger.CompletedExecutionInstruction.SET_ALL_JOB_TRIGGERS_COMPLETE) {
            instrCode = "SET ALL OF JOB'S TRIGGERS COMPLETE";
         } else if (triggerInstructionCode == Trigger.CompletedExecutionInstruction.SET_TRIGGER_COMPLETE) {
            instrCode = "SET THIS TRIGGER COMPLETE";
         }

         Object[] args = new Object[]{trigger.getKey().getName(), trigger.getKey().getGroup(), trigger.getPreviousFireTime(), trigger.getNextFireTime(), new Date(), context.getJobDetail().getKey().getName(), context.getJobDetail().getKey().getGroup(), context.getRefireCount(), triggerInstructionCode.toString(), instrCode};
         this.getLog().info(MessageFormat.format(this.getTriggerCompleteMessage(), args));
      }
   }

   public boolean vetoJobExecution(Trigger trigger, JobExecutionContext context) {
      return false;
   }
}
