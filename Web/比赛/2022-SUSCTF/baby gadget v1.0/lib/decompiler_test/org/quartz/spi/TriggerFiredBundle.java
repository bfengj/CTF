package org.quartz.spi;

import java.io.Serializable;
import java.util.Date;
import org.quartz.Calendar;
import org.quartz.JobDetail;

public class TriggerFiredBundle implements Serializable {
   private static final long serialVersionUID = -6414106108306999265L;
   private JobDetail job;
   private OperableTrigger trigger;
   private Calendar cal;
   private boolean jobIsRecovering;
   private Date fireTime;
   private Date scheduledFireTime;
   private Date prevFireTime;
   private Date nextFireTime;

   public TriggerFiredBundle(JobDetail job, OperableTrigger trigger, Calendar cal, boolean jobIsRecovering, Date fireTime, Date scheduledFireTime, Date prevFireTime, Date nextFireTime) {
      this.job = job;
      this.trigger = trigger;
      this.cal = cal;
      this.jobIsRecovering = jobIsRecovering;
      this.fireTime = fireTime;
      this.scheduledFireTime = scheduledFireTime;
      this.prevFireTime = prevFireTime;
      this.nextFireTime = nextFireTime;
   }

   public JobDetail getJobDetail() {
      return this.job;
   }

   public OperableTrigger getTrigger() {
      return this.trigger;
   }

   public Calendar getCalendar() {
      return this.cal;
   }

   public boolean isRecovering() {
      return this.jobIsRecovering;
   }

   public Date getFireTime() {
      return this.fireTime;
   }

   public Date getNextFireTime() {
      return this.nextFireTime;
   }

   public Date getPrevFireTime() {
      return this.prevFireTime;
   }

   public Date getScheduledFireTime() {
      return this.scheduledFireTime;
   }
}
