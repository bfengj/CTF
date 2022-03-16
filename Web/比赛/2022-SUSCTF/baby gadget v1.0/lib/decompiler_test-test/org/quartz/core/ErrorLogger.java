package org.quartz.core;

import org.quartz.SchedulerException;
import org.quartz.listeners.SchedulerListenerSupport;

class ErrorLogger extends SchedulerListenerSupport {
   public void schedulerError(String msg, SchedulerException cause) {
      this.getLog().error(msg, cause);
   }
}
