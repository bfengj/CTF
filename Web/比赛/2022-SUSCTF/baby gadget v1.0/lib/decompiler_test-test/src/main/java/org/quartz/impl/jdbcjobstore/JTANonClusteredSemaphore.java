package org.quartz.impl.jdbcjobstore;

import java.sql.Connection;
import java.util.HashSet;
import javax.naming.InitialContext;
import javax.naming.NamingException;
import javax.transaction.Synchronization;
import javax.transaction.SystemException;
import javax.transaction.Transaction;
import javax.transaction.TransactionManager;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

public class JTANonClusteredSemaphore implements Semaphore {
   public static final String DEFAULT_TRANSACTION_MANANGER_LOCATION = "java:TransactionManager";
   ThreadLocal<HashSet<String>> lockOwners = new ThreadLocal();
   HashSet<String> locks = new HashSet();
   private final Logger log = LoggerFactory.getLogger(this.getClass());
   private String transactionManagerJNDIName = "java:TransactionManager";

   protected Logger getLog() {
      return this.log;
   }

   public void setTransactionManagerJNDIName(String transactionManagerJNDIName) {
      this.transactionManagerJNDIName = transactionManagerJNDIName;
   }

   private HashSet<String> getThreadLocks() {
      HashSet<String> threadLocks = (HashSet)this.lockOwners.get();
      if (threadLocks == null) {
         threadLocks = new HashSet();
         this.lockOwners.set(threadLocks);
      }

      return threadLocks;
   }

   public synchronized boolean obtainLock(Connection conn, String lockName) throws LockException {
      lockName = lockName.intern();
      if (this.log.isDebugEnabled()) {
         this.log.debug("Lock '" + lockName + "' is desired by: " + Thread.currentThread().getName());
      }

      if (!this.isLockOwner(conn, lockName)) {
         if (this.log.isDebugEnabled()) {
            this.log.debug("Lock '" + lockName + "' is being obtained: " + Thread.currentThread().getName());
         }

         while(this.locks.contains(lockName)) {
            try {
               this.wait();
            } catch (InterruptedException var6) {
               if (this.log.isDebugEnabled()) {
                  this.log.debug("Lock '" + lockName + "' was not obtained by: " + Thread.currentThread().getName());
               }
            }
         }

         Transaction t = this.getTransaction();
         if (t != null) {
            try {
               t.registerSynchronization(new JTANonClusteredSemaphore.SemaphoreSynchronization(lockName));
            } catch (Exception var5) {
               throw new LockException("Failed to register semaphore with Transaction.", var5);
            }
         }

         if (this.log.isDebugEnabled()) {
            this.log.debug("Lock '" + lockName + "' given to: " + Thread.currentThread().getName());
         }

         this.getThreadLocks().add(lockName);
         this.locks.add(lockName);
      } else if (this.log.isDebugEnabled()) {
         this.log.debug("Lock '" + lockName + "' already owned by: " + Thread.currentThread().getName() + " -- but not owner!", new Exception("stack-trace of wrongful returner"));
      }

      return true;
   }

   public Transaction getTransaction() throws LockException {
      InitialContext ic = null;

      Transaction var3;
      try {
         ic = new InitialContext();
         TransactionManager tm = (TransactionManager)ic.lookup(this.transactionManagerJNDIName);
         var3 = tm.getTransaction();
      } catch (SystemException var12) {
         throw new LockException("Failed to get Transaction from TransactionManager", var12);
      } catch (NamingException var13) {
         throw new LockException("Failed to find TransactionManager in JNDI under name: " + this.transactionManagerJNDIName, var13);
      } finally {
         if (ic != null) {
            try {
               ic.close();
            } catch (NamingException var11) {
            }
         }

      }

      return var3;
   }

   public synchronized void releaseLock(String lockName) throws LockException {
      this.releaseLock(lockName, false);
   }

   protected synchronized void releaseLock(String lockName, boolean fromSynchronization) throws LockException {
      lockName = lockName.intern();
      if (this.isLockOwner((Connection)null, lockName)) {
         if (!fromSynchronization) {
            Transaction t = this.getTransaction();
            if (t != null) {
               if (this.getLog().isDebugEnabled()) {
                  this.getLog().debug("Lock '" + lockName + "' is in a JTA transaction.  Return deferred by: " + Thread.currentThread().getName());
               }

               return;
            }
         }

         if (this.getLog().isDebugEnabled()) {
            this.getLog().debug("Lock '" + lockName + "' returned by: " + Thread.currentThread().getName());
         }

         this.getThreadLocks().remove(lockName);
         this.locks.remove(lockName);
         this.notify();
      } else if (this.getLog().isDebugEnabled()) {
         this.getLog().debug("Lock '" + lockName + "' attempt to return by: " + Thread.currentThread().getName() + " -- but not owner!", new Exception("stack-trace of wrongful returner"));
      }

   }

   public synchronized boolean isLockOwner(Connection conn, String lockName) {
      lockName = lockName.intern();
      return this.getThreadLocks().contains(lockName);
   }

   public boolean requiresConnection() {
      return false;
   }

   private class SemaphoreSynchronization implements Synchronization {
      private String lockName;

      public SemaphoreSynchronization(String lockName) {
         this.lockName = lockName;
      }

      public void beforeCompletion() {
      }

      public void afterCompletion(int status) {
         try {
            JTANonClusteredSemaphore.this.releaseLock(this.lockName, true);
         } catch (LockException var3) {
         }

      }
   }
}
