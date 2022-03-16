package org.quartz.listeners;

import java.util.Collections;
import java.util.Iterator;
import java.util.LinkedList;
import java.util.List;
import org.quartz.JobExecutionContext;
import org.quartz.JobExecutionException;
import org.quartz.JobListener;

public class BroadcastJobListener implements JobListener {
   private String name;
   private List<JobListener> listeners;

   public BroadcastJobListener(String name) {
      if (name == null) {
         throw new IllegalArgumentException("Listener name cannot be null!");
      } else {
         this.name = name;
         this.listeners = new LinkedList();
      }
   }

   public BroadcastJobListener(String name, List<JobListener> listeners) {
      this(name);
      this.listeners.addAll(listeners);
   }

   public String getName() {
      return this.name;
   }

   public void addListener(JobListener listener) {
      this.listeners.add(listener);
   }

   public boolean removeListener(JobListener listener) {
      return this.listeners.remove(listener);
   }

   public boolean removeListener(String listenerName) {
      Iterator itr = this.listeners.iterator();

      JobListener jl;
      do {
         if (!itr.hasNext()) {
            return false;
         }

         jl = (JobListener)itr.next();
      } while(!jl.getName().equals(listenerName));

      itr.remove();
      return true;
   }

   public List<JobListener> getListeners() {
      return Collections.unmodifiableList(this.listeners);
   }

   public void jobToBeExecuted(JobExecutionContext context) {
      Iterator itr = this.listeners.iterator();

      while(itr.hasNext()) {
         JobListener jl = (JobListener)itr.next();
         jl.jobToBeExecuted(context);
      }

   }

   public void jobExecutionVetoed(JobExecutionContext context) {
      Iterator itr = this.listeners.iterator();

      while(itr.hasNext()) {
         JobListener jl = (JobListener)itr.next();
         jl.jobExecutionVetoed(context);
      }

   }

   public void jobWasExecuted(JobExecutionContext context, JobExecutionException jobException) {
      Iterator itr = this.listeners.iterator();

      while(itr.hasNext()) {
         JobListener jl = (JobListener)itr.next();
         jl.jobWasExecuted(context, jobException);
      }

   }
}
