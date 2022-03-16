package org.quartz;

public class UnableToInterruptJobException extends SchedulerException {
   private static final long serialVersionUID = -490863760696463776L;

   public UnableToInterruptJobException(String msg) {
      super(msg);
   }

   public UnableToInterruptJobException(Throwable cause) {
      super(cause);
   }
}
