package org.quartz.listeners;

import java.util.Collections;
import java.util.Iterator;
import java.util.LinkedList;
import java.util.List;
import org.quartz.JobExecutionContext;
import org.quartz.Trigger;
import org.quartz.TriggerListener;

public class BroadcastTriggerListener implements TriggerListener {
   private String name;
   private List<TriggerListener> listeners;

   public BroadcastTriggerListener(String name) {
      if (name == null) {
         throw new IllegalArgumentException("Listener name cannot be null!");
      } else {
         this.name = name;
         this.listeners = new LinkedList();
      }
   }

   public BroadcastTriggerListener(String name, List<TriggerListener> listeners) {
      this(name);
      this.listeners.addAll(listeners);
   }

   public String getName() {
      return this.name;
   }

   public void addListener(TriggerListener listener) {
      this.listeners.add(listener);
   }

   public boolean removeListener(TriggerListener listener) {
      return this.listeners.remove(listener);
   }

   public boolean removeListener(String listenerName) {
      Iterator itr = this.listeners.iterator();

      TriggerListener l;
      do {
         if (!itr.hasNext()) {
            return false;
         }

         l = (TriggerListener)itr.next();
      } while(!l.getName().equals(listenerName));

      itr.remove();
      return true;
   }

   public List<TriggerListener> getListeners() {
      return Collections.unmodifiableList(this.listeners);
   }

   public void triggerFired(Trigger trigger, JobExecutionContext context) {
      Iterator itr = this.listeners.iterator();

      while(itr.hasNext()) {
         TriggerListener l = (TriggerListener)itr.next();
         l.triggerFired(trigger, context);
      }

   }

   public boolean vetoJobExecution(Trigger trigger, JobExecutionContext context) {
      Iterator itr = this.listeners.iterator();

      TriggerListener l;
      do {
         if (!itr.hasNext()) {
            return false;
         }

         l = (TriggerListener)itr.next();
      } while(!l.vetoJobExecution(trigger, context));

      return true;
   }

   public void triggerMisfired(Trigger trigger) {
      Iterator itr = this.listeners.iterator();

      while(itr.hasNext()) {
         TriggerListener l = (TriggerListener)itr.next();
         l.triggerMisfired(trigger);
      }

   }

   public void triggerComplete(Trigger trigger, JobExecutionContext context, Trigger.CompletedExecutionInstruction triggerInstructionCode) {
      Iterator itr = this.listeners.iterator();

      while(itr.hasNext()) {
         TriggerListener l = (TriggerListener)itr.next();
         l.triggerComplete(trigger, context, triggerInstructionCode);
      }

   }
}
