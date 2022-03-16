package org.quartz.commonj;

import commonj.work.WorkManager;
import javax.naming.InitialContext;
import javax.naming.NamingException;
import org.quartz.spi.ThreadExecutor;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

public class WorkManagerThreadExecutor implements ThreadExecutor {
   private final Logger log = LoggerFactory.getLogger(this.getClass());
   private String workManagerName;
   private WorkManager workManager;

   public void execute(Thread thread) {
      DelegatingWork work = new DelegatingWork(thread);

      try {
         this.workManager.schedule(work);
      } catch (Exception var4) {
         this.log.error("Error attempting to schedule QuartzSchedulerThread: " + var4.getMessage(), var4);
      }

   }

   public void initialize() {
      try {
         this.workManager = (WorkManager)(new InitialContext()).lookup(this.workManagerName);
      } catch (NamingException var2) {
         throw new IllegalStateException("Could not locate WorkManager: " + var2.getMessage(), var2);
      }
   }

   public void setWorkManagerName(String workManagerName) {
      this.workManagerName = workManagerName;
   }
}
