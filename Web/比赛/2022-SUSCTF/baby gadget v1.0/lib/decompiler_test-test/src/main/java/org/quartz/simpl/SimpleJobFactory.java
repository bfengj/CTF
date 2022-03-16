package org.quartz.simpl;

import org.quartz.Job;
import org.quartz.JobDetail;
import org.quartz.Scheduler;
import org.quartz.SchedulerException;
import org.quartz.spi.JobFactory;
import org.quartz.spi.TriggerFiredBundle;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

public class SimpleJobFactory implements JobFactory {
   private final Logger log = LoggerFactory.getLogger(this.getClass());

   protected Logger getLog() {
      return this.log;
   }

   public Job newJob(TriggerFiredBundle bundle, Scheduler Scheduler) throws SchedulerException {
      JobDetail jobDetail = bundle.getJobDetail();
      Class jobClass = jobDetail.getJobClass();

      try {
         if (this.log.isDebugEnabled()) {
            this.log.debug("Producing instance of Job '" + jobDetail.getKey() + "', class=" + jobClass.getName());
         }

         return (Job)jobClass.newInstance();
      } catch (Exception var7) {
         SchedulerException se = new SchedulerException("Problem instantiating class '" + jobDetail.getJobClass().getName() + "'", var7);
         throw se;
      }
   }
}
