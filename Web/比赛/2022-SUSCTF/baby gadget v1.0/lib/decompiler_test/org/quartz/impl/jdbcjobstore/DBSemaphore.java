package org.quartz.impl.jdbcjobstore;

import java.sql.Connection;
import java.util.HashSet;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

public abstract class DBSemaphore implements Semaphore, Constants, StdJDBCConstants, TablePrefixAware {
   private final Logger log = LoggerFactory.getLogger(this.getClass());
   ThreadLocal<HashSet<String>> lockOwners = new ThreadLocal();
   private String sql;
   private String insertSql;
   private String tablePrefix;
   private String schedName;
   private String expandedSQL;
   private String expandedInsertSQL;
   private String schedNameLiteral = null;

   public DBSemaphore(String tablePrefix, String schedName, String defaultSQL, String defaultInsertSQL) {
      this.tablePrefix = tablePrefix;
      this.schedName = schedName;
      this.setSQL(defaultSQL);
      this.setInsertSQL(defaultInsertSQL);
   }

   protected Logger getLog() {
      return this.log;
   }

   private HashSet<String> getThreadLocks() {
      HashSet<String> threadLocks = (HashSet)this.lockOwners.get();
      if (threadLocks == null) {
         threadLocks = new HashSet();
         this.lockOwners.set(threadLocks);
      }

      return threadLocks;
   }

   protected abstract void executeSQL(Connection var1, String var2, String var3, String var4) throws LockException;

   public boolean obtainLock(Connection conn, String lockName) throws LockException {
      if (this.log.isDebugEnabled()) {
         this.log.debug("Lock '" + lockName + "' is desired by: " + Thread.currentThread().getName());
      }

      if (!this.isLockOwner(lockName)) {
         this.executeSQL(conn, lockName, this.expandedSQL, this.expandedInsertSQL);
         if (this.log.isDebugEnabled()) {
            this.log.debug("Lock '" + lockName + "' given to: " + Thread.currentThread().getName());
         }

         this.getThreadLocks().add(lockName);
      } else if (this.log.isDebugEnabled()) {
         this.log.debug("Lock '" + lockName + "' Is already owned by: " + Thread.currentThread().getName());
      }

      return true;
   }

   public void releaseLock(String lockName) {
      if (this.isLockOwner(lockName)) {
         if (this.getLog().isDebugEnabled()) {
            this.getLog().debug("Lock '" + lockName + "' returned by: " + Thread.currentThread().getName());
         }

         this.getThreadLocks().remove(lockName);
      } else if (this.getLog().isDebugEnabled()) {
         this.getLog().warn("Lock '" + lockName + "' attempt to return by: " + Thread.currentThread().getName() + " -- but not owner!", new Exception("stack-trace of wrongful returner"));
      }

   }

   public boolean isLockOwner(String lockName) {
      return this.getThreadLocks().contains(lockName);
   }

   public boolean requiresConnection() {
      return true;
   }

   protected String getSQL() {
      return this.sql;
   }

   protected void setSQL(String sql) {
      if (sql != null && sql.trim().length() != 0) {
         this.sql = sql.trim();
      }

      this.setExpandedSQL();
   }

   protected void setInsertSQL(String insertSql) {
      if (insertSql != null && insertSql.trim().length() != 0) {
         this.insertSql = insertSql.trim();
      }

      this.setExpandedSQL();
   }

   private void setExpandedSQL() {
      if (this.getTablePrefix() != null && this.getSchedName() != null && this.sql != null && this.insertSql != null) {
         this.expandedSQL = Util.rtp(this.sql, this.getTablePrefix(), this.getSchedulerNameLiteral());
         this.expandedInsertSQL = Util.rtp(this.insertSql, this.getTablePrefix(), this.getSchedulerNameLiteral());
      }

   }

   protected String getSchedulerNameLiteral() {
      if (this.schedNameLiteral == null) {
         this.schedNameLiteral = "'" + this.schedName + "'";
      }

      return this.schedNameLiteral;
   }

   public String getSchedName() {
      return this.schedName;
   }

   public void setSchedName(String schedName) {
      this.schedName = schedName;
      this.setExpandedSQL();
   }

   protected String getTablePrefix() {
      return this.tablePrefix;
   }

   public void setTablePrefix(String tablePrefix) {
      this.tablePrefix = tablePrefix;
      this.setExpandedSQL();
   }
}
