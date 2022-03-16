package org.quartz.impl.jdbcjobstore;

import java.sql.Connection;
import java.sql.SQLException;
import org.quartz.JobPersistenceException;
import org.quartz.SchedulerConfigException;
import org.quartz.spi.ClassLoadHelper;
import org.quartz.spi.SchedulerSignaler;
import org.quartz.utils.DBConnectionManager;

public class JobStoreCMT extends JobStoreSupport {
   protected String nonManagedTxDsName;
   protected boolean dontSetNonManagedTXConnectionAutoCommitFalse = false;
   protected boolean setTxIsolationLevelReadCommitted = false;

   public void setNonManagedTXDataSource(String nonManagedTxDsName) {
      this.nonManagedTxDsName = nonManagedTxDsName;
   }

   public String getNonManagedTXDataSource() {
      return this.nonManagedTxDsName;
   }

   public boolean isDontSetNonManagedTXConnectionAutoCommitFalse() {
      return this.dontSetNonManagedTXConnectionAutoCommitFalse;
   }

   public void setDontSetNonManagedTXConnectionAutoCommitFalse(boolean b) {
      this.dontSetNonManagedTXConnectionAutoCommitFalse = b;
   }

   public boolean isTxIsolationLevelReadCommitted() {
      return this.setTxIsolationLevelReadCommitted;
   }

   public void setTxIsolationLevelReadCommitted(boolean b) {
      this.setTxIsolationLevelReadCommitted = b;
   }

   public void initialize(ClassLoadHelper loadHelper, SchedulerSignaler signaler) throws SchedulerConfigException {
      if (this.nonManagedTxDsName == null) {
         throw new SchedulerConfigException("Non-ManagedTX DataSource name not set!  If your 'org.quartz.jobStore.dataSource' is XA, then set 'org.quartz.jobStore.nonManagedTXDataSource' to a non-XA datasource (for the same DB).  Otherwise, you can set them to be the same.");
      } else {
         if (this.getLockHandler() == null) {
            this.setUseDBLocks(true);
         }

         super.initialize(loadHelper, signaler);
         this.getLog().info("JobStoreCMT initialized.");
      }
   }

   public void shutdown() {
      super.shutdown();

      try {
         DBConnectionManager.getInstance().shutdown(this.getNonManagedTXDataSource());
      } catch (SQLException var2) {
         this.getLog().warn("Database connection shutdown unsuccessful.", var2);
      }

   }

   protected Connection getNonManagedTXConnection() throws JobPersistenceException {
      Connection conn = null;

      try {
         conn = DBConnectionManager.getInstance().getConnection(this.getNonManagedTXDataSource());
      } catch (SQLException var7) {
         throw new JobPersistenceException("Failed to obtain DB connection from data source '" + this.getNonManagedTXDataSource() + "': " + var7.toString(), var7);
      } catch (Throwable var8) {
         throw new JobPersistenceException("Failed to obtain DB connection from data source '" + this.getNonManagedTXDataSource() + "': " + var8.toString(), var8);
      }

      if (conn == null) {
         throw new JobPersistenceException("Could not get connection from DataSource '" + this.getNonManagedTXDataSource() + "'");
      } else {
         conn = this.getAttributeRestoringConnection(conn);

         try {
            if (!this.isDontSetNonManagedTXConnectionAutoCommitFalse()) {
               conn.setAutoCommit(false);
            }

            if (this.isTxIsolationLevelReadCommitted()) {
               conn.setTransactionIsolation(2);
            }
         } catch (SQLException var5) {
            this.getLog().warn("Failed to override connection auto commit/transaction isolation.", var5);
         } catch (Throwable var6) {
            try {
               conn.close();
            } catch (Throwable var4) {
            }

            throw new JobPersistenceException("Failure setting up connection.", var6);
         }

         return conn;
      }
   }

   protected Object executeInLock(String lockName, JobStoreSupport.TransactionCallback txCallback) throws JobPersistenceException {
      boolean transOwner = false;
      Connection conn = null;

      Object var5;
      try {
         if (lockName != null) {
            if (this.getLockHandler().requiresConnection()) {
               conn = this.getConnection();
            }

            transOwner = this.getLockHandler().obtainLock(conn, lockName);
         }

         if (conn == null) {
            conn = this.getConnection();
         }

         var5 = txCallback.execute(conn);
      } finally {
         try {
            this.releaseLock("TRIGGER_ACCESS", transOwner);
         } finally {
            this.cleanupConnection(conn);
         }
      }

      return var5;
   }
}
