package org.quartz.listeners;

import java.util.Collections;
import java.util.Iterator;
import java.util.LinkedList;
import java.util.List;
import org.quartz.JobDetail;
import org.quartz.JobKey;
import org.quartz.SchedulerException;
import org.quartz.SchedulerListener;
import org.quartz.Trigger;
import org.quartz.TriggerKey;

public class BroadcastSchedulerListener implements SchedulerListener {
   private List<SchedulerListener> listeners;

   public BroadcastSchedulerListener() {
      this.listeners = new LinkedList();
   }

   public BroadcastSchedulerListener(List<SchedulerListener> listeners) {
      this();
      this.listeners.addAll(listeners);
   }

   public void addListener(SchedulerListener listener) {
      this.listeners.add(listener);
   }

   public boolean removeListener(SchedulerListener listener) {
      return this.listeners.remove(listener);
   }

   public List<SchedulerListener> getListeners() {
      return Collections.unmodifiableList(this.listeners);
   }

   public void jobAdded(JobDetail jobDetail) {
      Iterator itr = this.listeners.iterator();

      while(itr.hasNext()) {
         SchedulerListener l = (SchedulerListener)itr.next();
         l.jobAdded(jobDetail);
      }

   }

   public void jobDeleted(JobKey jobKey) {
      Iterator itr = this.listeners.iterator();

      while(itr.hasNext()) {
         SchedulerListener l = (SchedulerListener)itr.next();
         l.jobDeleted(jobKey);
      }

   }

   public void jobScheduled(Trigger trigger) {
      Iterator itr = this.listeners.iterator();

      while(itr.hasNext()) {
         SchedulerListener l = (SchedulerListener)itr.next();
         l.jobScheduled(trigger);
      }

   }

   public void jobUnscheduled(TriggerKey triggerKey) {
      Iterator itr = this.listeners.iterator();

      while(itr.hasNext()) {
         SchedulerListener l = (SchedulerListener)itr.next();
         l.jobUnscheduled(triggerKey);
      }

   }

   public void triggerFinalized(Trigger trigger) {
      Iterator itr = this.listeners.iterator();

      while(itr.hasNext()) {
         SchedulerListener l = (SchedulerListener)itr.next();
         l.triggerFinalized(trigger);
      }

   }

   public void triggerPaused(TriggerKey key) {
      Iterator itr = this.listeners.iterator();

      while(itr.hasNext()) {
         SchedulerListener l = (SchedulerListener)itr.next();
         l.triggerPaused(key);
      }

   }

   public void triggersPaused(String triggerGroup) {
      Iterator itr = this.listeners.iterator();

      while(itr.hasNext()) {
         SchedulerListener l = (SchedulerListener)itr.next();
         l.triggersPaused(triggerGroup);
      }

   }

   public void triggerResumed(TriggerKey key) {
      Iterator itr = this.listeners.iterator();

      while(itr.hasNext()) {
         SchedulerListener l = (SchedulerListener)itr.next();
         l.triggerResumed(key);
      }

   }

   public void triggersResumed(String triggerGroup) {
      Iterator itr = this.listeners.iterator();

      while(itr.hasNext()) {
         SchedulerListener l = (SchedulerListener)itr.next();
         l.triggersResumed(triggerGroup);
      }

   }

   public void schedulingDataCleared() {
      Iterator itr = this.listeners.iterator();

      while(itr.hasNext()) {
         SchedulerListener l = (SchedulerListener)itr.next();
         l.schedulingDataCleared();
      }

   }

   public void jobPaused(JobKey key) {
      Iterator itr = this.listeners.iterator();

      while(itr.hasNext()) {
         SchedulerListener l = (SchedulerListener)itr.next();
         l.jobPaused(key);
      }

   }

   public void jobsPaused(String jobGroup) {
      Iterator itr = this.listeners.iterator();

      while(itr.hasNext()) {
         SchedulerListener l = (SchedulerListener)itr.next();
         l.jobsPaused(jobGroup);
      }

   }

   public void jobResumed(JobKey key) {
      Iterator itr = this.listeners.iterator();

      while(itr.hasNext()) {
         SchedulerListener l = (SchedulerListener)itr.next();
         l.jobResumed(key);
      }

   }

   public void jobsResumed(String jobGroup) {
      Iterator itr = this.listeners.iterator();

      while(itr.hasNext()) {
         SchedulerListener l = (SchedulerListener)itr.next();
         l.jobsResumed(jobGroup);
      }

   }

   public void schedulerError(String msg, SchedulerException cause) {
      Iterator itr = this.listeners.iterator();

      while(itr.hasNext()) {
         SchedulerListener l = (SchedulerListener)itr.next();
         l.schedulerError(msg, cause);
      }

   }

   public void schedulerStarted() {
      Iterator itr = this.listeners.iterator();

      while(itr.hasNext()) {
         SchedulerListener l = (SchedulerListener)itr.next();
         l.schedulerStarted();
      }

   }

   public void schedulerStarting() {
      Iterator itr = this.listeners.iterator();

      while(itr.hasNext()) {
         SchedulerListener l = (SchedulerListener)itr.next();
         l.schedulerStarting();
      }

   }

   public void schedulerInStandbyMode() {
      Iterator itr = this.listeners.iterator();

      while(itr.hasNext()) {
         SchedulerListener l = (SchedulerListener)itr.next();
         l.schedulerInStandbyMode();
      }

   }

   public void schedulerShutdown() {
      Iterator itr = this.listeners.iterator();

      while(itr.hasNext()) {
         SchedulerListener l = (SchedulerListener)itr.next();
         l.schedulerShutdown();
      }

   }

   public void schedulerShuttingdown() {
      Iterator itr = this.listeners.iterator();

      while(itr.hasNext()) {
         SchedulerListener l = (SchedulerListener)itr.next();
         l.schedulerShuttingdown();
      }

   }
}
