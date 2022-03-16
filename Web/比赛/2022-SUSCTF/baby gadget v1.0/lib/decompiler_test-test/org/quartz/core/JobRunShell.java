package org.quartz.core;

import org.quartz.Job;
import org.quartz.JobDetail;
import org.quartz.JobExecutionContext;
import org.quartz.JobExecutionException;
import org.quartz.Scheduler;
import org.quartz.SchedulerException;
import org.quartz.Trigger;
import org.quartz.impl.JobExecutionContextImpl;
import org.quartz.listeners.SchedulerListenerSupport;
import org.quartz.spi.OperableTrigger;
import org.quartz.spi.TriggerFiredBundle;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

public class JobRunShell extends SchedulerListenerSupport implements Runnable {
   protected JobExecutionContextImpl jec = null;
   protected QuartzScheduler qs = null;
   protected TriggerFiredBundle firedTriggerBundle = null;
   protected Scheduler scheduler = null;
   protected volatile boolean shutdownRequested = false;
   private final Logger log = LoggerFactory.getLogger(this.getClass());

   public JobRunShell(Scheduler scheduler, TriggerFiredBundle bndle) {
      this.scheduler = scheduler;
      this.firedTriggerBundle = bndle;
   }

   public void schedulerShuttingdown() {
      this.requestShutdown();
   }

   protected Logger getLog() {
      return this.log;
   }

   public void initialize(QuartzScheduler sched) throws SchedulerException {
      this.qs = sched;
      Job job = null;
      JobDetail jobDetail = this.firedTriggerBundle.getJobDetail();

      try {
         job = sched.getJobFactory().newJob(this.firedTriggerBundle, this.scheduler);
      } catch (SchedulerException var6) {
         sched.notifySchedulerListenersError("An error occured instantiating job to be executed. job= '" + jobDetail.getKey() + "'", var6);
         throw var6;
      } catch (Throwable var7) {
         SchedulerException se = new SchedulerException("Problem instantiating class '" + jobDetail.getJobClass().getName() + "' - ", var7);
         sched.notifySchedulerListenersError("An error occured instantiating job to be executed. job= '" + jobDetail.getKey() + "'", se);
         throw se;
      }

      this.jec = new JobExecutionContextImpl(this.scheduler, this.firedTriggerBundle, job);
   }

   public void requestShutdown() {
      this.shutdownRequested = true;
   }

   public void run() {
      this.qs.addInternalSchedulerListener(this);

      try {
         OperableTrigger trigger = (OperableTrigger)this.jec.getTrigger();
         JobDetail jobDetail = this.jec.getJobDetail();

         Trigger.CompletedExecutionInstruction instCode;
         label157:
         while(true) {
            while(true) {
               JobExecutionException jobExEx = null;
               Job job = this.jec.getJobInstance();

               try {
                  this.begin();
               } catch (SchedulerException var28) {
                  this.qs.notifySchedulerListenersError("Error executing Job (" + this.jec.getJobDetail().getKey() + ": couldn't begin execution.", var28);
                  return;
               }

               try {
                  if (!this.notifyListenersBeginning(this.jec)) {
                     return;
                  }
               } catch (JobRunShell.VetoedException var27) {
                  try {
                     Trigger.CompletedExecutionInstruction instCode = trigger.executionComplete(this.jec, (JobExecutionException)null);
                     this.qs.notifyJobStoreJobVetoed(trigger, jobDetail, instCode);
                     if (this.jec.getTrigger().getNextFireTime() == null) {
                        this.qs.notifySchedulerListenersFinalized(this.jec.getTrigger());
                     }

                     this.complete(true);
                  } catch (SchedulerException var22) {
                     this.qs.notifySchedulerListenersError("Error during veto of Job (" + this.jec.getJobDetail().getKey() + ": couldn't finalize execution.", var22);
                  }

                  return;
               }

               long startTime = System.currentTimeMillis();

               long endTime;
               try {
                  this.log.debug("Calling execute on job " + jobDetail.getKey());
                  job.execute(this.jec);
                  endTime = System.currentTimeMillis();
               } catch (JobExecutionException var25) {
                  endTime = System.currentTimeMillis();
                  jobExEx = var25;
                  this.getLog().info("Job " + jobDetail.getKey() + " threw a JobExecutionException: ", var25);
               } catch (Throwable var26) {
                  endTime = System.currentTimeMillis();
                  this.getLog().error("Job " + jobDetail.getKey() + " threw an unhandled Exception: ", var26);
                  SchedulerException se = new SchedulerException("Job threw an unhandled exception.", var26);
                  this.qs.notifySchedulerListenersError("Job (" + this.jec.getJobDetail().getKey() + " threw an exception.", se);
                  jobExEx = new JobExecutionException(se, false);
               }

               this.jec.setJobRunTime(endTime - startTime);
               if (!this.notifyJobListenersComplete(this.jec, jobExEx)) {
                  return;
               }

               instCode = Trigger.CompletedExecutionInstruction.NOOP;

               try {
                  instCode = trigger.executionComplete(this.jec, jobExEx);
               } catch (Exception var24) {
                  SchedulerException se = new SchedulerException("Trigger threw an unhandled exception.", var24);
                  this.qs.notifySchedulerListenersError("Please report this error to the Quartz developers.", se);
               }

               if (!this.notifyTriggerListenersComplete(this.jec, instCode)) {
                  return;
               }

               if (instCode != Trigger.CompletedExecutionInstruction.RE_EXECUTE_JOB) {
                  try {
                     this.complete(true);
                     break label157;
                  } catch (SchedulerException var29) {
                     this.qs.notifySchedulerListenersError("Error executing Job (" + this.jec.getJobDetail().getKey() + ": couldn't finalize execution.", var29);
                  }
               } else {
                  this.jec.incrementRefireCount();

                  try {
                     this.complete(false);
                  } catch (SchedulerException var23) {
                     this.qs.notifySchedulerListenersError("Error executing Job (" + this.jec.getJobDetail().getKey() + ": couldn't finalize execution.", var23);
                  }
               }
            }
         }

         this.qs.notifyJobStoreJobComplete(trigger, jobDetail, instCode);
      } finally {
         this.qs.removeInternalSchedulerListener(this);
      }

   }

   protected void begin() throws SchedulerException {
   }

   protected void complete(boolean successfulExecution) throws SchedulerException {
   }

   public void passivate() {
      this.jec = null;
      this.qs = null;
   }

   private boolean notifyListenersBeginning(JobExecutionContext jobExCtxt) throws JobRunShell.VetoedException {
      boolean vetoed = false;

      try {
         vetoed = this.qs.notifyTriggerListenersFired(jobExCtxt);
      } catch (SchedulerException var6) {
         this.qs.notifySchedulerListenersError("Unable to notify TriggerListener(s) while firing trigger (Trigger and Job will NOT be fired!). trigger= " + jobExCtxt.getTrigger().getKey() + " job= " + jobExCtxt.getJobDetail().getKey(), var6);
         return false;
      }

      if (vetoed) {
         try {
            this.qs.notifyJobListenersWasVetoed(jobExCtxt);
         } catch (SchedulerException var4) {
            this.qs.notifySchedulerListenersError("Unable to notify JobListener(s) of vetoed execution while firing trigger (Trigger and Job will NOT be fired!). trigger= " + jobExCtxt.getTrigger().getKey() + " job= " + jobExCtxt.getJobDetail().getKey(), var4);
         }

         throw new JobRunShell.VetoedException();
      } else {
         try {
            this.qs.notifyJobListenersToBeExecuted(jobExCtxt);
            return true;
         } catch (SchedulerException var5) {
            this.qs.notifySchedulerListenersError("Unable to notify JobListener(s) of Job to be executed: (Job will NOT be executed!). trigger= " + jobExCtxt.getTrigger().getKey() + " job= " + jobExCtxt.getJobDetail().getKey(), var5);
            return false;
         }
      }
   }

   private boolean notifyJobListenersComplete(JobExecutionContext jobExCtxt, JobExecutionException jobExEx) {
      try {
         this.qs.notifyJobListenersWasExecuted(jobExCtxt, jobExEx);
         return true;
      } catch (SchedulerException var4) {
         this.qs.notifySchedulerListenersError("Unable to notify JobListener(s) of Job that was executed: (error will be ignored). trigger= " + jobExCtxt.getTrigger().getKey() + " job= " + jobExCtxt.getJobDetail().getKey(), var4);
         return false;
      }
   }

   private boolean notifyTriggerListenersComplete(JobExecutionContext jobExCtxt, Trigger.CompletedExecutionInstruction instCode) {
      try {
         this.qs.notifyTriggerListenersComplete(jobExCtxt, instCode);
      } catch (SchedulerException var4) {
         this.qs.notifySchedulerListenersError("Unable to notify TriggerListener(s) of Job that was executed: (error will be ignored). trigger= " + jobExCtxt.getTrigger().getKey() + " job= " + jobExCtxt.getJobDetail().getKey(), var4);
         return false;
      }

      if (jobExCtxt.getTrigger().getNextFireTime() == null) {
         this.qs.notifySchedulerListenersFinalized(jobExCtxt.getTrigger());
      }

      return true;
   }

   static class VetoedException extends Exception {
      private static final long serialVersionUID = 1539955697495918463L;

      public VetoedException() {
      }
   }
}
