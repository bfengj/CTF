package org.quartz;

public interface InterruptableJob extends Job {
   void interrupt() throws UnableToInterruptJobException;
}
