package org.quartz.impl;

import java.util.Collection;
import java.util.Collections;
import java.util.HashMap;
import org.quartz.Scheduler;
import org.quartz.SchedulerException;

public class SchedulerRepository {
   private HashMap<String, Scheduler> schedulers = new HashMap();
   private static SchedulerRepository inst;

   private SchedulerRepository() {
   }

   public static synchronized SchedulerRepository getInstance() {
      if (inst == null) {
         inst = new SchedulerRepository();
      }

      return inst;
   }

   public synchronized void bind(Scheduler sched) throws SchedulerException {
      if ((Scheduler)this.schedulers.get(sched.getSchedulerName()) != null) {
         throw new SchedulerException("Scheduler with name '" + sched.getSchedulerName() + "' already exists.");
      } else {
         this.schedulers.put(sched.getSchedulerName(), sched);
      }
   }

   public synchronized boolean remove(String schedName) {
      return this.schedulers.remove(schedName) != null;
   }

   public synchronized Scheduler lookup(String schedName) {
      return (Scheduler)this.schedulers.get(schedName);
   }

   public synchronized Collection<Scheduler> lookupAll() {
      return Collections.unmodifiableCollection(this.schedulers.values());
   }
}
