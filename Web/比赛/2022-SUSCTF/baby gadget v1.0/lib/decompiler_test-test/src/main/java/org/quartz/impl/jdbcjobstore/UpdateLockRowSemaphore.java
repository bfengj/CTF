package org.quartz.impl.jdbcjobstore;

import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.SQLException;

public class UpdateLockRowSemaphore extends DBSemaphore {
   public static final String UPDATE_FOR_LOCK = "UPDATE {0}LOCKS SET LOCK_NAME = LOCK_NAME WHERE SCHED_NAME = {1} AND LOCK_NAME = ? ";
   public static final String INSERT_LOCK = "INSERT INTO {0}LOCKS(SCHED_NAME, LOCK_NAME) VALUES ({1}, ?)";
   private static final int RETRY_COUNT = 2;

   public UpdateLockRowSemaphore() {
      super("QRTZ_", (String)null, "UPDATE {0}LOCKS SET LOCK_NAME = LOCK_NAME WHERE SCHED_NAME = {1} AND LOCK_NAME = ? ", "INSERT INTO {0}LOCKS(SCHED_NAME, LOCK_NAME) VALUES ({1}, ?)");
   }

   protected void executeSQL(Connection conn, String lockName, String expandedSQL, String expandedInsertSQL) throws LockException {
      SQLException lastFailure = null;
      int i = 0;

      while(i < 2) {
         try {
            if (!this.lockViaUpdate(conn, lockName, expandedSQL)) {
               this.lockViaInsert(conn, lockName, expandedInsertSQL);
            }

            return;
         } catch (SQLException var10) {
            lastFailure = var10;
            if (i + 1 == 2) {
               this.getLog().debug("Lock '{}' was not obtained by: {}", lockName, Thread.currentThread().getName());
            } else {
               this.getLog().debug("Lock '{}' was not obtained by: {} - will try again.", lockName, Thread.currentThread().getName());
            }

            try {
               Thread.sleep(1000L);
            } catch (InterruptedException var9) {
               Thread.currentThread().interrupt();
            }

            ++i;
         }
      }

      throw new LockException("Failure obtaining db row lock: " + lastFailure.getMessage(), lastFailure);
   }

   protected String getUpdateLockRowSQL() {
      return this.getSQL();
   }

   public void setUpdateLockRowSQL(String updateLockRowSQL) {
      this.setSQL(updateLockRowSQL);
   }

   private boolean lockViaUpdate(Connection conn, String lockName, String sql) throws SQLException {
      PreparedStatement ps = conn.prepareStatement(sql);

      boolean var5;
      try {
         ps.setString(1, lockName);
         this.getLog().debug("Lock '" + lockName + "' is being obtained: " + Thread.currentThread().getName());
         var5 = ps.executeUpdate() >= 1;
      } finally {
         ps.close();
      }

      return var5;
   }

   private void lockViaInsert(Connection conn, String lockName, String sql) throws SQLException {
      this.getLog().debug("Inserting new lock row for lock: '" + lockName + "' being obtained by thread: " + Thread.currentThread().getName());
      PreparedStatement ps = conn.prepareStatement(sql);

      try {
         ps.setString(1, lockName);
         if (ps.executeUpdate() != 1) {
            throw new SQLException(Util.rtp("No row exists, and one could not be inserted in table {0}LOCKS for lock named: " + lockName, this.getTablePrefix(), this.getSchedulerNameLiteral()));
         }
      } finally {
         ps.close();
      }

   }
}
