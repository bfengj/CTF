package org.quartz;

import java.util.Collection;

public interface SchedulerFactory {
   Scheduler getScheduler() throws SchedulerException;

   Scheduler getScheduler(String var1) throws SchedulerException;

   Collection<Scheduler> getAllSchedulers() throws SchedulerException;
}
