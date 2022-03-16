package org.quartz.ee.jta;

import javax.transaction.SystemException;
import javax.transaction.UserTransaction;
import org.quartz.Scheduler;
import org.quartz.SchedulerException;
import org.quartz.core.JobRunShell;
import org.quartz.spi.TriggerFiredBundle;

public class JTAJobRunShell extends JobRunShell {
   private final Integer transactionTimeout;
   private UserTransaction ut;

   public JTAJobRunShell(Scheduler scheduler, TriggerFiredBundle bndle) {
      super(scheduler, bndle);
      this.transactionTimeout = null;
   }

   public JTAJobRunShell(Scheduler scheduler, TriggerFiredBundle bndle, int timeout) {
      super(scheduler, bndle);
      this.transactionTimeout = timeout;
   }

   protected void begin() throws SchedulerException {
      this.cleanupUserTransaction();
      boolean beganSuccessfully = false;

      try {
         this.getLog().debug("Looking up UserTransaction.");
         this.ut = UserTransactionHelper.lookupUserTransaction();
         if (this.transactionTimeout != null) {
            this.ut.setTransactionTimeout(this.transactionTimeout);
         }

         this.getLog().debug("Beginning UserTransaction.");
         this.ut.begin();
         beganSuccessfully = true;
      } catch (SchedulerException var7) {
         throw var7;
      } catch (Exception var8) {
         throw new SchedulerException("JTAJobRunShell could not start UserTransaction.", var8);
      } finally {
         if (!beganSuccessfully) {
            this.cleanupUserTransaction();
         }

      }

   }

   protected void complete(boolean successfulExecution) throws SchedulerException {
      if (this.ut != null) {
         try {
            try {
               if (this.ut.getStatus() == 1) {
                  this.getLog().debug("UserTransaction marked for rollback only.");
                  successfulExecution = false;
               }
            } catch (SystemException var10) {
               throw new SchedulerException("JTAJobRunShell could not read UserTransaction status.", var10);
            }

            if (successfulExecution) {
               try {
                  this.getLog().debug("Committing UserTransaction.");
                  this.ut.commit();
               } catch (Exception var9) {
                  throw new SchedulerException("JTAJobRunShell could not commit UserTransaction.", var9);
               }
            } else {
               try {
                  this.getLog().debug("Rolling-back UserTransaction.");
                  this.ut.rollback();
               } catch (Exception var8) {
                  throw new SchedulerException("JTAJobRunShell could not rollback UserTransaction.", var8);
               }
            }
         } finally {
            this.cleanupUserTransaction();
         }

      }
   }

   public void passivate() {
      this.cleanupUserTransaction();
      super.passivate();
   }

   private void cleanupUserTransaction() {
      if (this.ut != null) {
         UserTransactionHelper.returnUserTransaction(this.ut);
         this.ut = null;
      }

   }
}
