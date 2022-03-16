package org.quartz.plugins;

import javax.transaction.UserTransaction;
import org.quartz.Scheduler;
import org.quartz.SchedulerException;
import org.quartz.ee.jta.UserTransactionHelper;
import org.quartz.spi.SchedulerPlugin;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

public abstract class SchedulerPluginWithUserTransactionSupport implements SchedulerPlugin {
   private String name;
   private Scheduler scheduler;
   private final Logger log = LoggerFactory.getLogger(this.getClass());
   private boolean wrapInUserTransaction = false;

   protected void start(UserTransaction userTransaction) {
   }

   protected void shutdown(UserTransaction userTransaction) {
   }

   protected Logger getLog() {
      return this.log;
   }

   protected String getName() {
      return this.name;
   }

   protected Scheduler getScheduler() {
      return this.scheduler;
   }

   public void initialize(String pname, Scheduler sched) throws SchedulerException {
      this.name = pname;
      this.scheduler = sched;
   }

   public boolean getWrapInUserTransaction() {
      return this.wrapInUserTransaction;
   }

   public void setWrapInUserTransaction(boolean wrapInUserTransaction) {
      this.wrapInUserTransaction = wrapInUserTransaction;
   }

   public void start() {
      UserTransaction userTransaction = this.startUserTransaction();

      try {
         this.start(userTransaction);
      } finally {
         this.resolveUserTransaction(userTransaction);
      }

   }

   public void shutdown() {
      UserTransaction userTransaction = this.startUserTransaction();

      try {
         this.shutdown(userTransaction);
      } finally {
         this.resolveUserTransaction(userTransaction);
      }

   }

   private UserTransaction startUserTransaction() {
      if (!this.wrapInUserTransaction) {
         return null;
      } else {
         UserTransaction userTransaction = null;

         try {
            userTransaction = UserTransactionHelper.lookupUserTransaction();
            userTransaction.begin();
         } catch (Throwable var3) {
            UserTransactionHelper.returnUserTransaction(userTransaction);
            userTransaction = null;
            this.getLog().error("Failed to start UserTransaction for plugin: " + this.getName(), var3);
         }

         return userTransaction;
      }
   }

   private void resolveUserTransaction(UserTransaction userTransaction) {
      if (userTransaction != null) {
         try {
            if (userTransaction.getStatus() == 1) {
               userTransaction.rollback();
            } else {
               userTransaction.commit();
            }
         } catch (Throwable var6) {
            this.getLog().error("Failed to resolve UserTransaction for plugin: " + this.getName(), var6);
         } finally {
            UserTransactionHelper.returnUserTransaction(userTransaction);
         }
      }

   }
}
