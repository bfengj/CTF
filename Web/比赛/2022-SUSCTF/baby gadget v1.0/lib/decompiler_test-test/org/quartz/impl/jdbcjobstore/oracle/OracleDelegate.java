package org.quartz.impl.jdbcjobstore.oracle;

import java.io.ByteArrayOutputStream;
import java.io.IOException;
import java.io.InputStream;
import java.io.ObjectInputStream;
import java.math.BigDecimal;
import java.sql.Blob;
import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import oracle.sql.BLOB;
import org.quartz.Calendar;
import org.quartz.JobDetail;
import org.quartz.impl.jdbcjobstore.StdJDBCDelegate;
import org.quartz.impl.jdbcjobstore.TriggerPersistenceDelegate;
import org.quartz.spi.OperableTrigger;

public class OracleDelegate extends StdJDBCDelegate {
   public static final String INSERT_ORACLE_JOB_DETAIL = "INSERT INTO {0}JOB_DETAILS (SCHED_NAME, JOB_NAME, JOB_GROUP, DESCRIPTION, JOB_CLASS_NAME, IS_DURABLE, IS_NONCONCURRENT, IS_UPDATE_DATA, REQUESTS_RECOVERY, JOB_DATA)  VALUES({1}, ?, ?, ?, ?, ?, ?, ?, ?, EMPTY_BLOB())";
   public static final String UPDATE_ORACLE_JOB_DETAIL = "UPDATE {0}JOB_DETAILS SET DESCRIPTION = ?, JOB_CLASS_NAME = ?, IS_DURABLE = ?, IS_NONCONCURRENT = ?, IS_UPDATE_DATA = ?, REQUESTS_RECOVERY = ?, JOB_DATA = EMPTY_BLOB()  WHERE SCHED_NAME = {1} AND JOB_NAME = ? AND JOB_GROUP = ?";
   public static final String UPDATE_ORACLE_JOB_DETAIL_BLOB = "UPDATE {0}JOB_DETAILS SET JOB_DATA = ?  WHERE SCHED_NAME = {1} AND JOB_NAME = ? AND JOB_GROUP = ?";
   public static final String SELECT_ORACLE_JOB_DETAIL_BLOB = "SELECT JOB_DATA FROM {0}JOB_DETAILS WHERE SCHED_NAME = {1} AND JOB_NAME = ? AND JOB_GROUP = ? FOR UPDATE";
   public static final String UPDATE_ORACLE_TRIGGER = "UPDATE {0}TRIGGERS SET JOB_NAME = ?, JOB_GROUP = ?, DESCRIPTION = ?, NEXT_FIRE_TIME = ?, PREV_FIRE_TIME = ?, TRIGGER_STATE = ?, TRIGGER_TYPE = ?, START_TIME = ?, END_TIME = ?, CALENDAR_NAME = ?, MISFIRE_INSTR = ?, PRIORITY = ? WHERE SCHED_NAME = {1} AND TRIGGER_NAME = ? AND TRIGGER_GROUP = ?";
   public static final String SELECT_ORACLE_TRIGGER_JOB_DETAIL_BLOB = "SELECT JOB_DATA FROM {0}TRIGGERS WHERE SCHED_NAME = {1} AND TRIGGER_NAME = ? AND TRIGGER_GROUP = ? FOR UPDATE";
   public static final String UPDATE_ORACLE_TRIGGER_JOB_DETAIL_BLOB = "UPDATE {0}TRIGGERS SET JOB_DATA = ?  WHERE SCHED_NAME = {1} AND TRIGGER_NAME = ? AND TRIGGER_GROUP = ?";
   public static final String UPDATE_ORACLE_TRIGGER_JOB_DETAIL_EMPTY_BLOB = "UPDATE {0}TRIGGERS SET JOB_DATA = EMPTY_BLOB()  WHERE SCHED_NAME = {1} AND TRIGGER_NAME = ? AND TRIGGER_GROUP = ?";
   public static final String INSERT_ORACLE_CALENDAR = "INSERT INTO {0}CALENDARS (SCHED_NAME, CALENDAR_NAME, CALENDAR)  VALUES({1}, ?, EMPTY_BLOB())";
   public static final String SELECT_ORACLE_CALENDAR_BLOB = "SELECT CALENDAR FROM {0}CALENDARS WHERE SCHED_NAME = {1} AND CALENDAR_NAME = ? FOR UPDATE";
   public static final String UPDATE_ORACLE_CALENDAR_BLOB = "UPDATE {0}CALENDARS SET CALENDAR = ?  WHERE SCHED_NAME = {1} AND CALENDAR_NAME = ?";

   protected Object getObjectFromBlob(ResultSet rs, String colName) throws ClassNotFoundException, IOException, SQLException {
      Object obj = null;
      InputStream binaryInput = rs.getBinaryStream(colName);
      if (binaryInput != null) {
         ObjectInputStream in = new ObjectInputStream(binaryInput);

         try {
            obj = in.readObject();
         } finally {
            in.close();
         }
      }

      return obj;
   }

   public int insertJobDetail(Connection conn, JobDetail job) throws IOException, SQLException {
      ByteArrayOutputStream baos = this.serializeJobData(job.getJobDataMap());
      byte[] data = baos.toByteArray();
      PreparedStatement ps = null;
      ResultSet rs = null;

      byte var9;
      try {
         ps = conn.prepareStatement(this.rtp("INSERT INTO {0}JOB_DETAILS (SCHED_NAME, JOB_NAME, JOB_GROUP, DESCRIPTION, JOB_CLASS_NAME, IS_DURABLE, IS_NONCONCURRENT, IS_UPDATE_DATA, REQUESTS_RECOVERY, JOB_DATA)  VALUES({1}, ?, ?, ?, ?, ?, ?, ?, ?, EMPTY_BLOB())"));
         ps.setString(1, job.getKey().getName());
         ps.setString(2, job.getKey().getGroup());
         ps.setString(3, job.getDescription());
         ps.setString(4, job.getJobClass().getName());
         this.setBoolean(ps, 5, job.isDurable());
         this.setBoolean(ps, 6, job.isConcurrentExectionDisallowed());
         this.setBoolean(ps, 7, job.isPersistJobDataAfterExecution());
         this.setBoolean(ps, 8, job.requestsRecovery());
         ps.executeUpdate();
         ps.close();
         ps = conn.prepareStatement(this.rtp("SELECT JOB_DATA FROM {0}JOB_DETAILS WHERE SCHED_NAME = {1} AND JOB_NAME = ? AND JOB_GROUP = ? FOR UPDATE"));
         ps.setString(1, job.getKey().getName());
         ps.setString(2, job.getKey().getGroup());
         rs = ps.executeQuery();
         int res = 0;
         Blob dbBlob = null;
         if (rs.next()) {
            dbBlob = this.writeDataToBlob(rs, 1, data);
            rs.close();
            ps.close();
            ps = conn.prepareStatement(this.rtp("UPDATE {0}JOB_DETAILS SET JOB_DATA = ?  WHERE SCHED_NAME = {1} AND JOB_NAME = ? AND JOB_GROUP = ?"));
            ps.setBlob(1, dbBlob);
            ps.setString(2, job.getKey().getName());
            ps.setString(3, job.getKey().getGroup());
            int res = ps.executeUpdate();
            int var14 = res;
            return var14;
         }

         var9 = res;
      } finally {
         closeResultSet(rs);
         closeStatement(ps);
      }

      return var9;
   }

   protected Object getJobDataFromBlob(ResultSet rs, String colName) throws ClassNotFoundException, IOException, SQLException {
      if (this.canUseProperties()) {
         InputStream binaryInput = rs.getBinaryStream(colName);
         return binaryInput;
      } else {
         return this.getObjectFromBlob(rs, colName);
      }
   }

   public int updateJobDetail(Connection conn, JobDetail job) throws IOException, SQLException {
      ByteArrayOutputStream baos = this.serializeJobData(job.getJobDataMap());
      byte[] data = baos.toByteArray();
      PreparedStatement ps = null;
      PreparedStatement ps2 = null;
      ResultSet rs = null;

      int var13;
      try {
         ps = conn.prepareStatement(this.rtp("UPDATE {0}JOB_DETAILS SET DESCRIPTION = ?, JOB_CLASS_NAME = ?, IS_DURABLE = ?, IS_NONCONCURRENT = ?, IS_UPDATE_DATA = ?, REQUESTS_RECOVERY = ?, JOB_DATA = EMPTY_BLOB()  WHERE SCHED_NAME = {1} AND JOB_NAME = ? AND JOB_GROUP = ?"));
         ps.setString(1, job.getDescription());
         ps.setString(2, job.getJobClass().getName());
         this.setBoolean(ps, 3, job.isDurable());
         this.setBoolean(ps, 4, job.isConcurrentExectionDisallowed());
         this.setBoolean(ps, 5, job.isPersistJobDataAfterExecution());
         this.setBoolean(ps, 6, job.requestsRecovery());
         ps.setString(7, job.getKey().getName());
         ps.setString(8, job.getKey().getGroup());
         ps.executeUpdate();
         ps.close();
         ps = conn.prepareStatement(this.rtp("SELECT JOB_DATA FROM {0}JOB_DETAILS WHERE SCHED_NAME = {1} AND JOB_NAME = ? AND JOB_GROUP = ? FOR UPDATE"));
         ps.setString(1, job.getKey().getName());
         ps.setString(2, job.getKey().getGroup());
         rs = ps.executeQuery();
         int res = 0;
         if (rs.next()) {
            Blob dbBlob = this.writeDataToBlob(rs, 1, data);
            ps2 = conn.prepareStatement(this.rtp("UPDATE {0}JOB_DETAILS SET JOB_DATA = ?  WHERE SCHED_NAME = {1} AND JOB_NAME = ? AND JOB_GROUP = ?"));
            ps2.setBlob(1, dbBlob);
            ps2.setString(2, job.getKey().getName());
            ps2.setString(3, job.getKey().getGroup());
            res = ps2.executeUpdate();
         }

         var13 = res;
      } finally {
         closeResultSet(rs);
         closeStatement(ps);
         closeStatement(ps2);
      }

      return var13;
   }

   public int insertTrigger(Connection conn, OperableTrigger trigger, String state, JobDetail jobDetail) throws SQLException, IOException {
      byte[] data = null;
      if (trigger.getJobDataMap().size() > 0) {
         data = this.serializeJobData(trigger.getJobDataMap()).toByteArray();
      }

      PreparedStatement ps = null;
      ResultSet rs = null;
      boolean var8 = false;

      int insertResult;
      try {
         ps = conn.prepareStatement(this.rtp("INSERT INTO {0}TRIGGERS (SCHED_NAME, TRIGGER_NAME, TRIGGER_GROUP, JOB_NAME, JOB_GROUP, DESCRIPTION, NEXT_FIRE_TIME, PREV_FIRE_TIME, TRIGGER_STATE, TRIGGER_TYPE, START_TIME, END_TIME, CALENDAR_NAME, MISFIRE_INSTR, JOB_DATA, PRIORITY)  VALUES({1}, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"));
         ps.setString(1, trigger.getKey().getName());
         ps.setString(2, trigger.getKey().getGroup());
         ps.setString(3, trigger.getJobKey().getName());
         ps.setString(4, trigger.getJobKey().getGroup());
         ps.setString(5, trigger.getDescription());
         ps.setBigDecimal(6, new BigDecimal(String.valueOf(trigger.getNextFireTime().getTime())));
         long prevFireTime = -1L;
         if (trigger.getPreviousFireTime() != null) {
            prevFireTime = trigger.getPreviousFireTime().getTime();
         }

         ps.setBigDecimal(7, new BigDecimal(String.valueOf(prevFireTime)));
         ps.setString(8, state);
         TriggerPersistenceDelegate tDel = this.findTriggerPersistenceDelegate(trigger);
         String type = "BLOB";
         if (tDel != null) {
            type = tDel.getHandledTriggerTypeDiscriminator();
         }

         ps.setString(9, type);
         ps.setBigDecimal(10, new BigDecimal(String.valueOf(trigger.getStartTime().getTime())));
         long endTime = 0L;
         if (trigger.getEndTime() != null) {
            endTime = trigger.getEndTime().getTime();
         }

         ps.setBigDecimal(11, new BigDecimal(String.valueOf(endTime)));
         ps.setString(12, trigger.getCalendarName());
         ps.setInt(13, trigger.getMisfireInstruction());
         ps.setBinaryStream(14, (InputStream)null, 0);
         ps.setInt(15, trigger.getPriority());
         insertResult = ps.executeUpdate();
         if (data != null) {
            ps.close();
            ps = conn.prepareStatement(this.rtp("UPDATE {0}TRIGGERS SET JOB_DATA = EMPTY_BLOB()  WHERE SCHED_NAME = {1} AND TRIGGER_NAME = ? AND TRIGGER_GROUP = ?"));
            ps.setString(1, trigger.getKey().getName());
            ps.setString(2, trigger.getKey().getGroup());
            ps.executeUpdate();
            ps.close();
            ps = conn.prepareStatement(this.rtp("SELECT JOB_DATA FROM {0}TRIGGERS WHERE SCHED_NAME = {1} AND TRIGGER_NAME = ? AND TRIGGER_GROUP = ? FOR UPDATE"));
            ps.setString(1, trigger.getKey().getName());
            ps.setString(2, trigger.getKey().getGroup());
            rs = ps.executeQuery();
            Blob dbBlob = null;
            if (!rs.next()) {
               byte var16 = 0;
               return var16;
            }

            dbBlob = this.writeDataToBlob(rs, 1, data);
            rs.close();
            ps.close();
            ps = conn.prepareStatement(this.rtp("UPDATE {0}TRIGGERS SET JOB_DATA = ?  WHERE SCHED_NAME = {1} AND TRIGGER_NAME = ? AND TRIGGER_GROUP = ?"));
            ps.setBlob(1, dbBlob);
            ps.setString(2, trigger.getKey().getName());
            ps.setString(3, trigger.getKey().getGroup());
            ps.executeUpdate();
         }

         if (tDel == null) {
            this.insertBlobTrigger(conn, trigger);
         } else {
            tDel.insertExtendedTriggerProperties(conn, trigger, state, jobDetail);
         }
      } finally {
         closeResultSet(rs);
         closeStatement(ps);
      }

      return insertResult;
   }

   public int updateTrigger(Connection conn, OperableTrigger trigger, String state, JobDetail jobDetail) throws SQLException, IOException {
      boolean updateJobData = trigger.getJobDataMap().isDirty();
      byte[] data = null;
      if (updateJobData && trigger.getJobDataMap().size() > 0) {
         data = this.serializeJobData(trigger.getJobDataMap()).toByteArray();
      }

      PreparedStatement ps = null;
      PreparedStatement ps2 = null;
      ResultSet rs = null;
      boolean var10 = false;

      int insertResult;
      try {
         ps = conn.prepareStatement(this.rtp("UPDATE {0}TRIGGERS SET JOB_NAME = ?, JOB_GROUP = ?, DESCRIPTION = ?, NEXT_FIRE_TIME = ?, PREV_FIRE_TIME = ?, TRIGGER_STATE = ?, TRIGGER_TYPE = ?, START_TIME = ?, END_TIME = ?, CALENDAR_NAME = ?, MISFIRE_INSTR = ?, PRIORITY = ? WHERE SCHED_NAME = {1} AND TRIGGER_NAME = ? AND TRIGGER_GROUP = ?"));
         ps.setString(1, trigger.getJobKey().getName());
         ps.setString(2, trigger.getJobKey().getGroup());
         ps.setString(3, trigger.getDescription());
         long nextFireTime = -1L;
         if (trigger.getNextFireTime() != null) {
            nextFireTime = trigger.getNextFireTime().getTime();
         }

         ps.setBigDecimal(4, new BigDecimal(String.valueOf(nextFireTime)));
         long prevFireTime = -1L;
         if (trigger.getPreviousFireTime() != null) {
            prevFireTime = trigger.getPreviousFireTime().getTime();
         }

         ps.setBigDecimal(5, new BigDecimal(String.valueOf(prevFireTime)));
         ps.setString(6, state);
         TriggerPersistenceDelegate tDel = this.findTriggerPersistenceDelegate(trigger);
         String type = "BLOB";
         if (tDel != null) {
            type = tDel.getHandledTriggerTypeDiscriminator();
         }

         ps.setString(7, type);
         ps.setBigDecimal(8, new BigDecimal(String.valueOf(trigger.getStartTime().getTime())));
         long endTime = 0L;
         if (trigger.getEndTime() != null) {
            endTime = trigger.getEndTime().getTime();
         }

         ps.setBigDecimal(9, new BigDecimal(String.valueOf(endTime)));
         ps.setString(10, trigger.getCalendarName());
         ps.setInt(11, trigger.getMisfireInstruction());
         ps.setInt(12, trigger.getPriority());
         ps.setString(13, trigger.getKey().getName());
         ps.setString(14, trigger.getKey().getGroup());
         insertResult = ps.executeUpdate();
         if (updateJobData) {
            ps.close();
            ps = conn.prepareStatement(this.rtp("UPDATE {0}TRIGGERS SET JOB_DATA = EMPTY_BLOB()  WHERE SCHED_NAME = {1} AND TRIGGER_NAME = ? AND TRIGGER_GROUP = ?"));
            ps.setString(1, trigger.getKey().getName());
            ps.setString(2, trigger.getKey().getGroup());
            ps.executeUpdate();
            ps.close();
            ps = conn.prepareStatement(this.rtp("SELECT JOB_DATA FROM {0}TRIGGERS WHERE SCHED_NAME = {1} AND TRIGGER_NAME = ? AND TRIGGER_GROUP = ? FOR UPDATE"));
            ps.setString(1, trigger.getKey().getName());
            ps.setString(2, trigger.getKey().getGroup());
            rs = ps.executeQuery();
            if (rs.next()) {
               Blob dbBlob = this.writeDataToBlob(rs, 1, data);
               ps2 = conn.prepareStatement(this.rtp("UPDATE {0}TRIGGERS SET JOB_DATA = ?  WHERE SCHED_NAME = {1} AND TRIGGER_NAME = ? AND TRIGGER_GROUP = ?"));
               ps2.setBlob(1, dbBlob);
               ps2.setString(2, trigger.getKey().getName());
               ps2.setString(3, trigger.getKey().getGroup());
               ps2.executeUpdate();
            }
         }

         if (tDel == null) {
            this.updateBlobTrigger(conn, trigger);
         } else {
            tDel.updateExtendedTriggerProperties(conn, trigger, state, jobDetail);
         }
      } finally {
         closeResultSet(rs);
         closeStatement(ps);
         closeStatement(ps2);
      }

      return insertResult;
   }

   public int insertCalendar(Connection conn, String calendarName, Calendar calendar) throws IOException, SQLException {
      ByteArrayOutputStream baos = this.serializeObject(calendar);
      PreparedStatement ps = null;
      PreparedStatement ps2 = null;
      ResultSet rs = null;

      byte var8;
      try {
         ps = conn.prepareStatement(this.rtp("INSERT INTO {0}CALENDARS (SCHED_NAME, CALENDAR_NAME, CALENDAR)  VALUES({1}, ?, EMPTY_BLOB())"));
         ps.setString(1, calendarName);
         ps.executeUpdate();
         ps.close();
         ps = conn.prepareStatement(this.rtp("SELECT CALENDAR FROM {0}CALENDARS WHERE SCHED_NAME = {1} AND CALENDAR_NAME = ? FOR UPDATE"));
         ps.setString(1, calendarName);
         rs = ps.executeQuery();
         if (rs.next()) {
            Blob dbBlob = this.writeDataToBlob(rs, 1, baos.toByteArray());
            ps2 = conn.prepareStatement(this.rtp("UPDATE {0}CALENDARS SET CALENDAR = ?  WHERE SCHED_NAME = {1} AND CALENDAR_NAME = ?"));
            ps2.setBlob(1, dbBlob);
            ps2.setString(2, calendarName);
            int var9 = ps2.executeUpdate();
            return var9;
         }

         var8 = 0;
      } finally {
         closeResultSet(rs);
         closeStatement(ps);
         closeStatement(ps2);
      }

      return var8;
   }

   public int updateCalendar(Connection conn, String calendarName, Calendar calendar) throws IOException, SQLException {
      ByteArrayOutputStream baos = this.serializeObject(calendar);
      PreparedStatement ps = null;
      PreparedStatement ps2 = null;
      ResultSet rs = null;

      int var9;
      try {
         ps = conn.prepareStatement(this.rtp("SELECT CALENDAR FROM {0}CALENDARS WHERE SCHED_NAME = {1} AND CALENDAR_NAME = ? FOR UPDATE"));
         ps.setString(1, calendarName);
         rs = ps.executeQuery();
         if (!rs.next()) {
            byte var13 = 0;
            return var13;
         }

         Blob dbBlob = this.writeDataToBlob(rs, 1, baos.toByteArray());
         ps2 = conn.prepareStatement(this.rtp("UPDATE {0}CALENDARS SET CALENDAR = ?  WHERE SCHED_NAME = {1} AND CALENDAR_NAME = ?"));
         ps2.setBlob(1, dbBlob);
         ps2.setString(2, calendarName);
         var9 = ps2.executeUpdate();
      } finally {
         closeResultSet(rs);
         closeStatement(ps);
         closeStatement(ps2);
      }

      return var9;
   }

   public int updateJobData(Connection conn, JobDetail job) throws IOException, SQLException {
      ByteArrayOutputStream baos = this.serializeJobData(job.getJobDataMap());
      byte[] data = baos.toByteArray();
      PreparedStatement ps = null;
      PreparedStatement ps2 = null;
      ResultSet rs = null;

      int var13;
      try {
         ps = conn.prepareStatement(this.rtp("SELECT JOB_DATA FROM {0}JOB_DETAILS WHERE SCHED_NAME = {1} AND JOB_NAME = ? AND JOB_GROUP = ? FOR UPDATE"));
         ps.setString(1, job.getKey().getName());
         ps.setString(2, job.getKey().getGroup());
         rs = ps.executeQuery();
         int res = 0;
         if (rs.next()) {
            Blob dbBlob = this.writeDataToBlob(rs, 1, data);
            ps2 = conn.prepareStatement(this.rtp("UPDATE {0}JOB_DETAILS SET JOB_DATA = ?  WHERE SCHED_NAME = {1} AND JOB_NAME = ? AND JOB_GROUP = ?"));
            ps2.setBlob(1, dbBlob);
            ps2.setString(2, job.getKey().getName());
            ps2.setString(3, job.getKey().getGroup());
            res = ps2.executeUpdate();
         }

         var13 = res;
      } finally {
         closeResultSet(rs);
         closeStatement(ps);
         closeStatement(ps2);
      }

      return var13;
   }

   protected Blob writeDataToBlob(ResultSet rs, int column, byte[] data) throws SQLException {
      Blob blob = rs.getBlob(column);
      if (blob == null) {
         throw new SQLException("Driver's Blob representation is null!");
      } else if (blob instanceof BLOB) {
         ((BLOB)blob).putBytes(1L, data);
         ((BLOB)blob).trim((long)data.length);
         return blob;
      } else {
         throw new SQLException("Driver's Blob representation is of an unsupported type: " + blob.getClass().getName());
      }
   }
}
