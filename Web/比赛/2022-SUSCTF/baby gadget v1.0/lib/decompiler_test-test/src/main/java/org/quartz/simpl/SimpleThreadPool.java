package org.quartz.simpl;

import java.util.Iterator;
import java.util.LinkedList;
import java.util.List;
import java.util.concurrent.atomic.AtomicBoolean;
import org.quartz.SchedulerConfigException;
import org.quartz.spi.ThreadPool;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

public class SimpleThreadPool implements ThreadPool {
   private int count = -1;
   private int prio = 5;
   private boolean isShutdown = false;
   private boolean handoffPending = false;
   private boolean inheritLoader = false;
   private boolean inheritGroup = true;
   private boolean makeThreadsDaemons = false;
   private ThreadGroup threadGroup;
   private final Object nextRunnableLock = new Object();
   private List<SimpleThreadPool.WorkerThread> workers;
   private LinkedList<SimpleThreadPool.WorkerThread> availWorkers = new LinkedList();
   private LinkedList<SimpleThreadPool.WorkerThread> busyWorkers = new LinkedList();
   private String threadNamePrefix;
   private final Logger log = LoggerFactory.getLogger(this.getClass());
   private String schedulerInstanceName;

   public SimpleThreadPool() {
   }

   public SimpleThreadPool(int threadCount, int threadPriority) {
      this.setThreadCount(threadCount);
      this.setThreadPriority(threadPriority);
   }

   public Logger getLog() {
      return this.log;
   }

   public int getPoolSize() {
      return this.getThreadCount();
   }

   public void setThreadCount(int count) {
      this.count = count;
   }

   public int getThreadCount() {
      return this.count;
   }

   public void setThreadPriority(int prio) {
      this.prio = prio;
   }

   public int getThreadPriority() {
      return this.prio;
   }

   public void setThreadNamePrefix(String prfx) {
      this.threadNamePrefix = prfx;
   }

   public String getThreadNamePrefix() {
      return this.threadNamePrefix;
   }

   public boolean isThreadsInheritContextClassLoaderOfInitializingThread() {
      return this.inheritLoader;
   }

   public void setThreadsInheritContextClassLoaderOfInitializingThread(boolean inheritLoader) {
      this.inheritLoader = inheritLoader;
   }

   public boolean isThreadsInheritGroupOfInitializingThread() {
      return this.inheritGroup;
   }

   public void setThreadsInheritGroupOfInitializingThread(boolean inheritGroup) {
      this.inheritGroup = inheritGroup;
   }

   public boolean isMakeThreadsDaemons() {
      return this.makeThreadsDaemons;
   }

   public void setMakeThreadsDaemons(boolean makeThreadsDaemons) {
      this.makeThreadsDaemons = makeThreadsDaemons;
   }

   public void setInstanceId(String schedInstId) {
   }

   public void setInstanceName(String schedName) {
      this.schedulerInstanceName = schedName;
   }

   public void initialize() throws SchedulerConfigException {
      if (this.workers == null || this.workers.size() <= 0) {
         if (this.count <= 0) {
            throw new SchedulerConfigException("Thread count must be > 0");
         } else if (this.prio > 0 && this.prio <= 9) {
            if (this.isThreadsInheritGroupOfInitializingThread()) {
               this.threadGroup = Thread.currentThread().getThreadGroup();
            } else {
               this.threadGroup = Thread.currentThread().getThreadGroup();

               ThreadGroup parent;
               for(parent = this.threadGroup; !parent.getName().equals("main"); parent = this.threadGroup.getParent()) {
                  this.threadGroup = parent;
               }

               this.threadGroup = new ThreadGroup(parent, this.schedulerInstanceName + "-SimpleThreadPool");
               if (this.isMakeThreadsDaemons()) {
                  this.threadGroup.setDaemon(true);
               }
            }

            if (this.isThreadsInheritContextClassLoaderOfInitializingThread()) {
               this.getLog().info("Job execution threads will use class loader of thread: " + Thread.currentThread().getName());
            }

            Iterator workerThreads = this.createWorkerThreads(this.count).iterator();

            while(workerThreads.hasNext()) {
               SimpleThreadPool.WorkerThread wt = (SimpleThreadPool.WorkerThread)workerThreads.next();
               wt.start();
               this.availWorkers.add(wt);
            }

         } else {
            throw new SchedulerConfigException("Thread priority must be > 0 and <= 9");
         }
      }
   }

   protected List<SimpleThreadPool.WorkerThread> createWorkerThreads(int createCount) {
      this.workers = new LinkedList();

      for(int i = 1; i <= createCount; ++i) {
         String threadPrefix = this.getThreadNamePrefix();
         if (threadPrefix == null) {
            threadPrefix = this.schedulerInstanceName + "_Worker";
         }

         SimpleThreadPool.WorkerThread wt = new SimpleThreadPool.WorkerThread(this, this.threadGroup, threadPrefix + "-" + i, this.getThreadPriority(), this.isMakeThreadsDaemons());
         if (this.isThreadsInheritContextClassLoaderOfInitializingThread()) {
            wt.setContextClassLoader(Thread.currentThread().getContextClassLoader());
         }

         this.workers.add(wt);
      }

      return this.workers;
   }

   public void shutdown() {
      this.shutdown(true);
   }

   public void shutdown(boolean waitForJobsToComplete) {
      synchronized(this.nextRunnableLock) {
         this.getLog().debug("Shutting down threadpool...");
         this.isShutdown = true;
         if (this.workers != null) {
            Iterator workerThreads = this.workers.iterator();

            while(workerThreads.hasNext()) {
               SimpleThreadPool.WorkerThread wt = (SimpleThreadPool.WorkerThread)workerThreads.next();
               wt.shutdown();
               this.availWorkers.remove(wt);
            }

            this.nextRunnableLock.notifyAll();
            if (waitForJobsToComplete) {
               boolean interrupted = false;

               try {
                  while(this.handoffPending) {
                     try {
                        this.nextRunnableLock.wait(100L);
                     } catch (InterruptedException var16) {
                        interrupted = true;
                     }
                  }

                  SimpleThreadPool.WorkerThread wt;
                  while(this.busyWorkers.size() > 0) {
                     wt = (SimpleThreadPool.WorkerThread)this.busyWorkers.getFirst();

                     try {
                        this.getLog().debug("Waiting for thread " + wt.getName() + " to shut down");
                        this.nextRunnableLock.wait(2000L);
                     } catch (InterruptedException var15) {
                        interrupted = true;
                     }
                  }

                  workerThreads = this.workers.iterator();

                  while(workerThreads.hasNext()) {
                     wt = (SimpleThreadPool.WorkerThread)workerThreads.next();

                     try {
                        wt.join();
                        workerThreads.remove();
                     } catch (InterruptedException var14) {
                        interrupted = true;
                     }
                  }
               } finally {
                  if (interrupted) {
                     Thread.currentThread().interrupt();
                  }

               }

               this.getLog().debug("No executing jobs remaining, all threads stopped.");
            }

            this.getLog().debug("Shutdown of threadpool complete.");
         }
      }
   }

   public boolean runInThread(Runnable runnable) {
      if (runnable == null) {
         return false;
      } else {
         synchronized(this.nextRunnableLock) {
            this.handoffPending = true;

            while(this.availWorkers.size() < 1 && !this.isShutdown) {
               try {
                  this.nextRunnableLock.wait(500L);
               } catch (InterruptedException var5) {
               }
            }

            SimpleThreadPool.WorkerThread wt;
            if (!this.isShutdown) {
               wt = (SimpleThreadPool.WorkerThread)this.availWorkers.removeFirst();
               this.busyWorkers.add(wt);
               wt.run(runnable);
            } else {
               wt = new SimpleThreadPool.WorkerThread(this, this.threadGroup, "WorkerThread-LastJob", this.prio, this.isMakeThreadsDaemons(), runnable);
               this.busyWorkers.add(wt);
               this.workers.add(wt);
               wt.start();
            }

            this.nextRunnableLock.notifyAll();
            this.handoffPending = false;
            return true;
         }
      }
   }

   public int blockForAvailableThreads() {
      synchronized(this.nextRunnableLock) {
         while((this.availWorkers.size() < 1 || this.handoffPending) && !this.isShutdown) {
            try {
               this.nextRunnableLock.wait(500L);
            } catch (InterruptedException var4) {
            }
         }

         return this.availWorkers.size();
      }
   }

   protected void makeAvailable(SimpleThreadPool.WorkerThread wt) {
      synchronized(this.nextRunnableLock) {
         if (!this.isShutdown) {
            this.availWorkers.add(wt);
         }

         this.busyWorkers.remove(wt);
         this.nextRunnableLock.notifyAll();
      }
   }

   protected void clearFromBusyWorkersList(SimpleThreadPool.WorkerThread wt) {
      synchronized(this.nextRunnableLock) {
         this.busyWorkers.remove(wt);
         this.nextRunnableLock.notifyAll();
      }
   }

   class WorkerThread extends Thread {
      private final Object lock;
      private AtomicBoolean run;
      private SimpleThreadPool tp;
      private Runnable runnable;
      private boolean runOnce;

      WorkerThread(SimpleThreadPool tp, ThreadGroup threadGroup, String name, int prio, boolean isDaemon) {
         this(tp, threadGroup, name, prio, isDaemon, (Runnable)null);
      }

      WorkerThread(SimpleThreadPool tp, ThreadGroup threadGroup, String name, int prio, boolean isDaemon, Runnable runnable) {
         super(threadGroup, name);
         this.lock = new Object();
         this.run = new AtomicBoolean(true);
         this.runnable = null;
         this.runOnce = false;
         this.tp = tp;
         this.runnable = runnable;
         if (runnable != null) {
            this.runOnce = true;
         }

         this.setPriority(prio);
         this.setDaemon(isDaemon);
      }

      void shutdown() {
         this.run.set(false);
      }

      public void run(Runnable newRunnable) {
         synchronized(this.lock) {
            if (this.runnable != null) {
               throw new IllegalStateException("Already running a Runnable!");
            } else {
               this.runnable = newRunnable;
               this.lock.notifyAll();
            }
         }
      }

      public void run() {
         boolean ran = false;

         while(this.run.get()) {
            boolean var21 = false;

            label288: {
               label289: {
                  try {
                     var21 = true;
                     synchronized(this.lock) {
                        while(this.runnable == null && this.run.get()) {
                           this.lock.wait(500L);
                        }

                        if (this.runnable != null) {
                           ran = true;
                           this.runnable.run();
                        }

                        var21 = false;
                        break label288;
                     }
                  } catch (InterruptedException var30) {
                     InterruptedException unblock = var30;

                     try {
                        SimpleThreadPool.this.getLog().error("Worker thread was interrupt()'ed.", unblock);
                        var21 = false;
                     } catch (Exception var27) {
                        var21 = false;
                     }
                     break label289;
                  } catch (Throwable var31) {
                     Throwable exceptionInRunnable = var31;

                     try {
                        SimpleThreadPool.this.getLog().error("Error while executing the Runnable: ", exceptionInRunnable);
                        var21 = false;
                     } catch (Exception var25) {
                        var21 = false;
                     }
                  } finally {
                     if (var21) {
                        synchronized(this.lock) {
                           this.runnable = null;
                        }

                        if (this.getPriority() != this.tp.getThreadPriority()) {
                           this.setPriority(this.tp.getThreadPriority());
                        }

                        if (this.runOnce) {
                           this.run.set(false);
                           SimpleThreadPool.this.clearFromBusyWorkersList(this);
                        } else if (ran) {
                           ran = false;
                           SimpleThreadPool.this.makeAvailable(this);
                        }

                     }
                  }

                  synchronized(this.lock) {
                     this.runnable = null;
                  }

                  if (this.getPriority() != this.tp.getThreadPriority()) {
                     this.setPriority(this.tp.getThreadPriority());
                  }

                  if (this.runOnce) {
                     this.run.set(false);
                     SimpleThreadPool.this.clearFromBusyWorkersList(this);
                  } else if (ran) {
                     ran = false;
                     SimpleThreadPool.this.makeAvailable(this);
                  }
                  continue;
               }

               synchronized(this.lock) {
                  this.runnable = null;
               }

               if (this.getPriority() != this.tp.getThreadPriority()) {
                  this.setPriority(this.tp.getThreadPriority());
               }

               if (this.runOnce) {
                  this.run.set(false);
                  SimpleThreadPool.this.clearFromBusyWorkersList(this);
               } else if (ran) {
                  ran = false;
                  SimpleThreadPool.this.makeAvailable(this);
               }
               continue;
            }

            synchronized(this.lock) {
               this.runnable = null;
            }

            if (this.getPriority() != this.tp.getThreadPriority()) {
               this.setPriority(this.tp.getThreadPriority());
            }

            if (this.runOnce) {
               this.run.set(false);
               SimpleThreadPool.this.clearFromBusyWorkersList(this);
            } else if (ran) {
               ran = false;
               SimpleThreadPool.this.makeAvailable(this);
            }
         }

         try {
            SimpleThreadPool.this.getLog().debug("WorkerThread is shut down.");
         } catch (Exception var23) {
         }

      }
   }
}
