package org.quartz.impl.jdbcjobstore;

import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;

public class StdRowLockSemaphore extends DBSemaphore {
   public static final String SELECT_FOR_LOCK = "SELECT * FROM {0}LOCKS WHERE SCHED_NAME = {1} AND LOCK_NAME = ? FOR UPDATE";
   public static final String INSERT_LOCK = "INSERT INTO {0}LOCKS(SCHED_NAME, LOCK_NAME) VALUES ({1}, ?)";

   public StdRowLockSemaphore() {
      super("QRTZ_", (String)null, "SELECT * FROM {0}LOCKS WHERE SCHED_NAME = {1} AND LOCK_NAME = ? FOR UPDATE", "INSERT INTO {0}LOCKS(SCHED_NAME, LOCK_NAME) VALUES ({1}, ?)");
   }

   public StdRowLockSemaphore(String tablePrefix, String schedName, String selectWithLockSQL) {
      super(tablePrefix, schedName, selectWithLockSQL != null ? selectWithLockSQL : "SELECT * FROM {0}LOCKS WHERE SCHED_NAME = {1} AND LOCK_NAME = ? FOR UPDATE", "INSERT INTO {0}LOCKS(SCHED_NAME, LOCK_NAME) VALUES ({1}, ?)");
   }

   protected void executeSQL(Connection conn, String lockName, String expandedSQL, String expandedInsertSQL) throws LockException {
      PreparedStatement ps = null;
      ResultSet rs = null;
      SQLException initCause = null;
      int count = 0;

      while(true) {
         ++count;

         try {
            ps = conn.prepareStatement(expandedSQL);
            ps.setString(1, lockName);
            if (this.getLog().isDebugEnabled()) {
               this.getLog().debug("Lock '" + lockName + "' is being obtained: " + Thread.currentThread().getName());
            }

            rs = ps.executeQuery();
            if (rs.next()) {
               break;
            }

            this.getLog().debug("Inserting new lock row for lock: '" + lockName + "' being obtained by thread: " + Thread.currentThread().getName());
            rs.close();
            rs = null;
            ps.close();
            ps = null;
            ps = conn.prepareStatement(expandedInsertSQL);
            ps.setString(1, lockName);
            int res = ps.executeUpdate();
            if (res == 1) {
               break;
            }

            if (count >= 3) {
               throw new SQLException(Util.rtp("No row exists, and one could not be inserted in table {0}LOCKS for lock named: " + lockName, this.getTablePrefix(), this.getSchedulerNameLiteral()));
            }

            try {
               Thread.sleep(1000L);
            } catch (InterruptedException var28) {
               Thread.currentThread().interrupt();
            }
         } catch (SQLException var29) {
            if (initCause == null) {
               initCause = var29;
            }

            if (this.getLog().isDebugEnabled()) {
               this.getLog().debug("Lock '" + lockName + "' was not obtained by: " + Thread.currentThread().getName() + (count < 3 ? " - will try again." : ""));
            }

            if (count >= 3) {
               throw new LockException("Failure obtaining db row lock: " + var29.getMessage(), var29);
            }

            try {
               Thread.sleep(1000L);
            } catch (InterruptedException var27) {
               Thread.currentThread().interrupt();
            }
         } finally {
            if (rs != null) {
               try {
                  rs.close();
               } catch (Exception var26) {
               }
            }

            if (ps != null) {
               try {
                  ps.close();
               } catch (Exception var25) {
               }
            }

         }

         if (count >= 4) {
            throw new LockException("Failure obtaining db row lock, reached maximum number of attempts. Initial exception (if any) attached as root cause.", initCause);
         }
      }

   }

   protected String getSelectWithLockSQL() {
      return this.getSQL();
   }

   public void setSelectWithLockSQL(String selectWithLockSQL) {
      this.setSQL(selectWithLockSQL);
   }
}
