package org.quartz.plugins.history;

import java.text.MessageFormat;
import java.util.Date;
import org.quartz.JobExecutionContext;
import org.quartz.JobExecutionException;
import org.quartz.JobListener;
import org.quartz.Matcher;
import org.quartz.Scheduler;
import org.quartz.SchedulerException;
import org.quartz.Trigger;
import org.quartz.impl.matchers.EverythingMatcher;
import org.quartz.spi.ClassLoadHelper;
import org.quartz.spi.SchedulerPlugin;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

public class LoggingJobHistoryPlugin implements SchedulerPlugin, JobListener {
   private String name;
   private String jobToBeFiredMessage = "Job {1}.{0} fired (by trigger {4}.{3}) at: {2, date, HH:mm:ss MM/dd/yyyy}";
   private String jobSuccessMessage = "Job {1}.{0} execution complete at {2, date, HH:mm:ss MM/dd/yyyy} and reports: {8}";
   private String jobFailedMessage = "Job {1}.{0} execution failed at {2, date, HH:mm:ss MM/dd/yyyy} and reports: {8}";
   private String jobWasVetoedMessage = "Job {1}.{0} was vetoed.  It was to be fired (by trigger {4}.{3}) at: {2, date, HH:mm:ss MM/dd/yyyy}";
   private final Logger log = LoggerFactory.getLogger(this.getClass());

   protected Logger getLog() {
      return this.log;
   }

   public String getJobSuccessMessage() {
      return this.jobSuccessMessage;
   }

   public String getJobFailedMessage() {
      return this.jobFailedMessage;
   }

   public String getJobToBeFiredMessage() {
      return this.jobToBeFiredMessage;
   }

   public void setJobSuccessMessage(String jobSuccessMessage) {
      this.jobSuccessMessage = jobSuccessMessage;
   }

   public void setJobFailedMessage(String jobFailedMessage) {
      this.jobFailedMessage = jobFailedMessage;
   }

   public void setJobToBeFiredMessage(String jobToBeFiredMessage) {
      this.jobToBeFiredMessage = jobToBeFiredMessage;
   }

   public String getJobWasVetoedMessage() {
      return this.jobWasVetoedMessage;
   }

   public void setJobWasVetoedMessage(String jobWasVetoedMessage) {
      this.jobWasVetoedMessage = jobWasVetoedMessage;
   }

   public void initialize(String pname, Scheduler scheduler, ClassLoadHelper classLoadHelper) throws SchedulerException {
      this.name = pname;
      scheduler.getListenerManager().addJobListener(this, (Matcher)EverythingMatcher.allJobs());
   }

   public void start() {
   }

   public void shutdown() {
   }

   public String getName() {
      return this.name;
   }

   public void jobToBeExecuted(JobExecutionContext context) {
      if (this.getLog().isInfoEnabled()) {
         Trigger trigger = context.getTrigger();
         Object[] args = new Object[]{context.getJobDetail().getKey().getName(), context.getJobDetail().getKey().getGroup(), new Date(), trigger.getKey().getName(), trigger.getKey().getGroup(), trigger.getPreviousFireTime(), trigger.getNextFireTime(), context.getRefireCount()};
         this.getLog().info(MessageFormat.format(this.getJobToBeFiredMessage(), args));
      }
   }

   public void jobWasExecuted(JobExecutionContext context, JobExecutionException jobException) {
      Trigger trigger = context.getTrigger();
      Object[] args = null;
      String errMsg;
      if (jobException != null) {
         if (!this.getLog().isWarnEnabled()) {
            return;
         }

         errMsg = jobException.getMessage();
         args = new Object[]{context.getJobDetail().getKey().getName(), context.getJobDetail().getKey().getGroup(), new Date(), trigger.getKey().getName(), trigger.getKey().getGroup(), trigger.getPreviousFireTime(), trigger.getNextFireTime(), context.getRefireCount(), errMsg};
         this.getLog().warn(MessageFormat.format(this.getJobFailedMessage(), args), jobException);
      } else {
         if (!this.getLog().isInfoEnabled()) {
            return;
         }

         errMsg = String.valueOf(context.getResult());
         args = new Object[]{context.getJobDetail().getKey().getName(), context.getJobDetail().getKey().getGroup(), new Date(), trigger.getKey().getName(), trigger.getKey().getGroup(), trigger.getPreviousFireTime(), trigger.getNextFireTime(), context.getRefireCount(), errMsg};
         this.getLog().info(MessageFormat.format(this.getJobSuccessMessage(), args));
      }

   }

   public void jobExecutionVetoed(JobExecutionContext context) {
      if (this.getLog().isInfoEnabled()) {
         Trigger trigger = context.getTrigger();
         Object[] args = new Object[]{context.getJobDetail().getKey().getName(), context.getJobDetail().getKey().getGroup(), new Date(), trigger.getKey().getName(), trigger.getKey().getGroup(), trigger.getPreviousFireTime(), trigger.getNextFireTime(), context.getRefireCount()};
         this.getLog().info(MessageFormat.format(this.getJobWasVetoedMessage(), args));
      }
   }
}
