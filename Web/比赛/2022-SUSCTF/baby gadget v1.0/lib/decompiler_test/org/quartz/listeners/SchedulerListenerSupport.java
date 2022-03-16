package org.quartz.listeners;

import org.quartz.JobDetail;
import org.quartz.JobKey;
import org.quartz.SchedulerException;
import org.quartz.SchedulerListener;
import org.quartz.Trigger;
import org.quartz.TriggerKey;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

public abstract class SchedulerListenerSupport implements SchedulerListener {
   private final Logger log = LoggerFactory.getLogger(this.getClass());

   protected Logger getLog() {
      return this.log;
   }

   public void jobAdded(JobDetail jobDetail) {
   }

   public void jobDeleted(JobKey jobKey) {
   }

   public void jobPaused(JobKey jobKey) {
   }

   public void jobResumed(JobKey jobKey) {
   }

   public void jobScheduled(Trigger trigger) {
   }

   public void jobsPaused(String jobGroup) {
   }

   public void jobsResumed(String jobGroup) {
   }

   public void jobUnscheduled(TriggerKey triggerKey) {
   }

   public void schedulerError(String msg, SchedulerException cause) {
   }

   public void schedulerInStandbyMode() {
   }

   public void schedulerShutdown() {
   }

   public void schedulerShuttingdown() {
   }

   public void schedulerStarted() {
   }

   public void schedulerStarting() {
   }

   public void triggerFinalized(Trigger trigger) {
   }

   public void triggerPaused(TriggerKey triggerKey) {
   }

   public void triggerResumed(TriggerKey triggerKey) {
   }

   public void triggersPaused(String triggerGroup) {
   }

   public void triggersResumed(String triggerGroup) {
   }

   public void schedulingDataCleared() {
   }
}
