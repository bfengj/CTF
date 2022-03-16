package org.quartz;

public class JobExecutionException extends SchedulerException {
   private static final long serialVersionUID = 1326342535829043325L;
   private boolean refire = false;
   private boolean unscheduleTrigg = false;
   private boolean unscheduleAllTriggs = false;

   public JobExecutionException() {
   }

   public JobExecutionException(Throwable cause) {
      super(cause);
   }

   public JobExecutionException(String msg) {
      super(msg);
   }

   public JobExecutionException(boolean refireImmediately) {
      this.refire = refireImmediately;
   }

   public JobExecutionException(Throwable cause, boolean refireImmediately) {
      super(cause);
      this.refire = refireImmediately;
   }

   public JobExecutionException(String msg, Throwable cause) {
      super(msg, cause);
   }

   public JobExecutionException(String msg, Throwable cause, boolean refireImmediately) {
      super(msg, cause);
      this.refire = refireImmediately;
   }

   public JobExecutionException(String msg, boolean refireImmediately) {
      super(msg);
      this.refire = refireImmediately;
   }

   public void setRefireImmediately(boolean refire) {
      this.refire = refire;
   }

   public boolean refireImmediately() {
      return this.refire;
   }

   public void setUnscheduleFiringTrigger(boolean unscheduleTrigg) {
      this.unscheduleTrigg = unscheduleTrigg;
   }

   public boolean unscheduleFiringTrigger() {
      return this.unscheduleTrigg;
   }

   public void setUnscheduleAllTriggers(boolean unscheduleAllTriggs) {
      this.unscheduleAllTriggs = unscheduleAllTriggs;
   }

   public boolean unscheduleAllTriggers() {
      return this.unscheduleAllTriggs;
   }
}
