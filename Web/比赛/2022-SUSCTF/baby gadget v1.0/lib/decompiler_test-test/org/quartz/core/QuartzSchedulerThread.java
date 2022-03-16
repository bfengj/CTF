package org.quartz.core;

import java.util.ArrayList;
import java.util.Iterator;
import java.util.List;
import java.util.Random;
import java.util.concurrent.atomic.AtomicBoolean;
import org.quartz.JobPersistenceException;
import org.quartz.SchedulerException;
import org.quartz.Trigger;
import org.quartz.spi.OperableTrigger;
import org.quartz.spi.TriggerFiredBundle;
import org.quartz.spi.TriggerFiredResult;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

public class QuartzSchedulerThread extends Thread {
   private QuartzScheduler qs;
   private QuartzSchedulerResources qsRsrcs;
   private final Object sigLock;
   private boolean signaled;
   private long signaledNextFireTime;
   private boolean paused;
   private AtomicBoolean halted;
   private Random random;
   private static long DEFAULT_IDLE_WAIT_TIME = 30000L;
   private long idleWaitTime;
   private int idleWaitVariablness;
   private final Logger log;

   QuartzSchedulerThread(QuartzScheduler qs, QuartzSchedulerResources qsRsrcs) {
      this(qs, qsRsrcs, qsRsrcs.getMakeSchedulerThreadDaemon(), 5);
   }

   QuartzSchedulerThread(QuartzScheduler qs, QuartzSchedulerResources qsRsrcs, boolean setDaemon, int threadPrio) {
      super(qs.getSchedulerThreadGroup(), qsRsrcs.getThreadName());
      this.sigLock = new Object();
      this.random = new Random(System.currentTimeMillis());
      this.idleWaitTime = DEFAULT_IDLE_WAIT_TIME;
      this.idleWaitVariablness = 7000;
      this.log = LoggerFactory.getLogger(this.getClass());
      this.qs = qs;
      this.qsRsrcs = qsRsrcs;
      this.setDaemon(setDaemon);
      if (qsRsrcs.isThreadsInheritInitializersClassLoadContext()) {
         this.log.info("QuartzSchedulerThread Inheriting ContextClassLoader of thread: " + Thread.currentThread().getName());
         this.setContextClassLoader(Thread.currentThread().getContextClassLoader());
      }

      this.setPriority(threadPrio);
      this.paused = true;
      this.halted = new AtomicBoolean(false);
   }

   void setIdleWaitTime(long waitTime) {
      this.idleWaitTime = waitTime;
      this.idleWaitVariablness = (int)((double)waitTime * 0.2D);
   }

   private long getRandomizedIdleWaitTime() {
      return this.idleWaitTime - (long)this.random.nextInt(this.idleWaitVariablness);
   }

   void togglePause(boolean pause) {
      synchronized(this.sigLock) {
         this.paused = pause;
         if (this.paused) {
            this.signalSchedulingChange(0L);
         } else {
            this.sigLock.notifyAll();
         }

      }
   }

   void halt(boolean wait) {
      synchronized(this.sigLock) {
         this.halted.set(true);
         if (this.paused) {
            this.sigLock.notifyAll();
         } else {
            this.signalSchedulingChange(0L);
         }
      }

      if (wait) {
         boolean interrupted = false;

         try {
            while(true) {
               try {
                  this.join();
                  break;
               } catch (InterruptedException var9) {
                  interrupted = true;
               }
            }
         } finally {
            if (interrupted) {
               Thread.currentThread().interrupt();
            }

         }
      }

   }

   boolean isPaused() {
      return this.paused;
   }

   public void signalSchedulingChange(long candidateNewNextFireTime) {
      synchronized(this.sigLock) {
         this.signaled = true;
         this.signaledNextFireTime = candidateNewNextFireTime;
         this.sigLock.notifyAll();
      }
   }

   public void clearSignaledSchedulingChange() {
      synchronized(this.sigLock) {
         this.signaled = false;
         this.signaledNextFireTime = 0L;
      }
   }

   public boolean isScheduleChanged() {
      synchronized(this.sigLock) {
         return this.signaled;
      }
   }

   public long getSignaledNextFireTime() {
      synchronized(this.sigLock) {
         return this.signaledNextFireTime;
      }
   }

   public void run() {
      boolean lastAcquireFailed = false;

      label214:
      while(!this.halted.get()) {
         try {
            synchronized(this.sigLock) {
               while(this.paused && !this.halted.get()) {
                  try {
                     this.sigLock.wait(1000L);
                  } catch (InterruptedException var23) {
                  }
               }

               if (this.halted.get()) {
                  break;
               }
            }

            int availThreadCount = this.qsRsrcs.getThreadPool().blockForAvailableThreads();
            if (availThreadCount > 0) {
               List<OperableTrigger> triggers = null;
               long now = System.currentTimeMillis();
               this.clearSignaledSchedulingChange();

               try {
                  triggers = this.qsRsrcs.getJobStore().acquireNextTriggers(now + this.idleWaitTime, Math.min(availThreadCount, this.qsRsrcs.getMaxBatchSize()), this.qsRsrcs.getBatchTimeWindow());
                  lastAcquireFailed = false;
                  if (this.log.isDebugEnabled()) {
                     this.log.debug("batch acquisition of " + (triggers == null ? 0 : triggers.size()) + " triggers");
                  }
               } catch (JobPersistenceException var25) {
                  if (!lastAcquireFailed) {
                     this.qs.notifySchedulerListenersError("An error occurred while scanning for the next triggers to fire.", var25);
                  }

                  lastAcquireFailed = true;
                  continue;
               } catch (RuntimeException var26) {
                  if (!lastAcquireFailed) {
                     this.getLog().error("quartzSchedulerThreadLoop: RuntimeException " + var26.getMessage(), var26);
                  }

                  lastAcquireFailed = true;
                  continue;
               }

               if (triggers != null && !triggers.isEmpty()) {
                  now = System.currentTimeMillis();
                  long triggerTime = ((OperableTrigger)triggers.get(0)).getNextFireTime().getTime();

                  for(long timeUntilTrigger = triggerTime - now; timeUntilTrigger > 2L; timeUntilTrigger = triggerTime - now) {
                     synchronized(this.sigLock) {
                        if (this.halted.get()) {
                           break;
                        }

                        if (!this.isCandidateNewTimeEarlierWithinReason(triggerTime, false)) {
                           try {
                              now = System.currentTimeMillis();
                              timeUntilTrigger = triggerTime - now;
                              if (timeUntilTrigger >= 1L) {
                                 this.sigLock.wait(timeUntilTrigger);
                              }
                           } catch (InterruptedException var22) {
                           }
                        }
                     }

                     if (this.releaseIfScheduleChangedSignificantly(triggers, triggerTime)) {
                        break;
                     }

                     now = System.currentTimeMillis();
                  }

                  if (!triggers.isEmpty()) {
                     List<TriggerFiredResult> bndles = new ArrayList();
                     boolean goAhead = true;
                     synchronized(this.sigLock) {
                        goAhead = !this.halted.get();
                     }

                     if (goAhead) {
                        try {
                           List<TriggerFiredResult> res = this.qsRsrcs.getJobStore().triggersFired(triggers);
                           if (res != null) {
                              bndles = res;
                           }
                        } catch (SchedulerException var24) {
                           this.qs.notifySchedulerListenersError("An error occurred while firing triggers '" + triggers + "'", var24);
                           int i = 0;

                           while(true) {
                              if (i >= triggers.size()) {
                                 continue label214;
                              }

                              this.qsRsrcs.getJobStore().releaseAcquiredTrigger((OperableTrigger)triggers.get(i));
                              ++i;
                           }
                        }
                     }

                     for(int i = 0; i < ((List)bndles).size(); ++i) {
                        TriggerFiredResult result = (TriggerFiredResult)((List)bndles).get(i);
                        TriggerFiredBundle bndle = result.getTriggerFiredBundle();
                        Exception exception = result.getException();
                        if (exception instanceof RuntimeException) {
                           this.getLog().error("RuntimeException while firing trigger " + triggers.get(i), exception);
                           this.qsRsrcs.getJobStore().releaseAcquiredTrigger((OperableTrigger)triggers.get(i));
                        } else if (bndle == null) {
                           this.qsRsrcs.getJobStore().releaseAcquiredTrigger((OperableTrigger)triggers.get(i));
                        } else {
                           JobRunShell shell = null;

                           try {
                              shell = this.qsRsrcs.getJobRunShellFactory().createJobRunShell(bndle);
                              shell.initialize(this.qs);
                           } catch (SchedulerException var27) {
                              this.qsRsrcs.getJobStore().triggeredJobComplete((OperableTrigger)triggers.get(i), bndle.getJobDetail(), Trigger.CompletedExecutionInstruction.SET_ALL_JOB_TRIGGERS_ERROR);
                              continue;
                           }

                           if (!this.qsRsrcs.getThreadPool().runInThread(shell)) {
                              this.getLog().error("ThreadPool.runInThread() return false!");
                              this.qsRsrcs.getJobStore().triggeredJobComplete((OperableTrigger)triggers.get(i), bndle.getJobDetail(), Trigger.CompletedExecutionInstruction.SET_ALL_JOB_TRIGGERS_ERROR);
                           }
                        }
                     }
                  }
               } else {
                  long now = System.currentTimeMillis();
                  long waitTime = now + this.getRandomizedIdleWaitTime();
                  long timeUntilContinue = waitTime - now;
                  synchronized(this.sigLock) {
                     try {
                        if (!this.halted.get() && !this.isScheduleChanged()) {
                           this.sigLock.wait(timeUntilContinue);
                        }
                     } catch (InterruptedException var19) {
                     }
                  }
               }
            }
         } catch (RuntimeException var30) {
            this.getLog().error("Runtime error occurred in main trigger firing loop.", var30);
         }
      }

      this.qs = null;
      this.qsRsrcs = null;
   }

   private boolean releaseIfScheduleChangedSignificantly(List<OperableTrigger> triggers, long triggerTime) {
      if (!this.isCandidateNewTimeEarlierWithinReason(triggerTime, true)) {
         return false;
      } else {
         Iterator i$ = triggers.iterator();

         while(i$.hasNext()) {
            OperableTrigger trigger = (OperableTrigger)i$.next();
            this.qsRsrcs.getJobStore().releaseAcquiredTrigger(trigger);
         }

         triggers.clear();
         return true;
      }
   }

   private boolean isCandidateNewTimeEarlierWithinReason(long oldTime, boolean clearSignal) {
      synchronized(this.sigLock) {
         if (!this.isScheduleChanged()) {
            return false;
         } else {
            boolean earlier = false;
            if (this.getSignaledNextFireTime() == 0L) {
               earlier = true;
            } else if (this.getSignaledNextFireTime() < oldTime) {
               earlier = true;
            }

            if (earlier) {
               long diff = oldTime - System.currentTimeMillis();
               if (diff < (this.qsRsrcs.getJobStore().supportsPersistence() ? 70L : 7L)) {
                  earlier = false;
               }
            }

            if (clearSignal) {
               this.clearSignaledSchedulingChange();
            }

            return earlier;
         }
      }
   }

   public Logger getLog() {
      return this.log;
   }
}
