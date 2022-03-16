package org.quartz.impl.jdbcjobstore;

import java.io.ByteArrayInputStream;
import java.io.ByteArrayOutputStream;
import java.io.IOException;
import java.io.InputStream;
import java.io.ObjectInputStream;
import java.math.BigDecimal;
import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import org.quartz.Calendar;
import org.quartz.JobDetail;
import org.quartz.spi.OperableTrigger;

public class PointbaseDelegate extends StdJDBCDelegate {
   public int insertJobDetail(Connection conn, JobDetail job) throws IOException, SQLException {
      ByteArrayOutputStream baos = this.serializeJobData(job.getJobDataMap());
      int len = baos.toByteArray().length;
      ByteArrayInputStream bais = new ByteArrayInputStream(baos.toByteArray());
      PreparedStatement ps = null;
      boolean var7 = false;

      int insertResult;
      try {
         ps = conn.prepareStatement(this.rtp("INSERT INTO {0}JOB_DETAILS (SCHED_NAME, JOB_NAME, JOB_GROUP, DESCRIPTION, JOB_CLASS_NAME, IS_DURABLE, IS_NONCONCURRENT, IS_UPDATE_DATA, REQUESTS_RECOVERY, JOB_DATA)  VALUES({1}, ?, ?, ?, ?, ?, ?, ?, ?, ?)"));
         ps.setString(1, job.getKey().getName());
         ps.setString(2, job.getKey().getGroup());
         ps.setString(3, job.getDescription());
         ps.setString(4, job.getJobClass().getName());
         this.setBoolean(ps, 5, job.isDurable());
         this.setBoolean(ps, 6, job.isConcurrentExectionDisallowed());
         this.setBoolean(ps, 7, job.isPersistJobDataAfterExecution());
         this.setBoolean(ps, 8, job.requestsRecovery());
         ps.setBinaryStream(9, bais, len);
         insertResult = ps.executeUpdate();
      } finally {
         closeStatement(ps);
      }

      return insertResult;
   }

   public int updateJobDetail(Connection conn, JobDetail job) throws IOException, SQLException {
      ByteArrayOutputStream baos = this.serializeJobData(job.getJobDataMap());
      int len = baos.toByteArray().length;
      ByteArrayInputStream bais = new ByteArrayInputStream(baos.toByteArray());
      PreparedStatement ps = null;
      boolean var7 = false;

      int insertResult;
      try {
         ps = conn.prepareStatement(this.rtp("UPDATE {0}JOB_DETAILS SET DESCRIPTION = ?, JOB_CLASS_NAME = ?, IS_DURABLE = ?, IS_NONCONCURRENT = ?, IS_UPDATE_DATA = ?, REQUESTS_RECOVERY = ?, JOB_DATA = ?  WHERE SCHED_NAME = {1} AND JOB_NAME = ? AND JOB_GROUP = ?"));
         ps.setString(1, job.getDescription());
         ps.setString(2, job.getJobClass().getName());
         this.setBoolean(ps, 3, job.isDurable());
         this.setBoolean(ps, 4, job.isConcurrentExectionDisallowed());
         this.setBoolean(ps, 5, job.isPersistJobDataAfterExecution());
         this.setBoolean(ps, 6, job.requestsRecovery());
         ps.setBinaryStream(7, bais, len);
         ps.setString(8, job.getKey().getName());
         ps.setString(9, job.getKey().getGroup());
         insertResult = ps.executeUpdate();
      } finally {
         closeStatement(ps);
      }

      return insertResult;
   }

   public int insertTrigger(Connection conn, OperableTrigger trigger, String state, JobDetail jobDetail) throws SQLException, IOException {
      ByteArrayOutputStream baos = this.serializeJobData(trigger.getJobDataMap());
      int len = baos.toByteArray().length;
      ByteArrayInputStream bais = new ByteArrayInputStream(baos.toByteArray());
      PreparedStatement ps = null;
      boolean var9 = false;

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
         ps.setBinaryStream(14, bais, len);
         ps.setInt(15, trigger.getPriority());
         insertResult = ps.executeUpdate();
         if (tDel == null) {
            this.insertBlobTrigger(conn, trigger);
         } else {
            tDel.insertExtendedTriggerProperties(conn, trigger, state, jobDetail);
         }
      } finally {
         closeStatement(ps);
      }

      return insertResult;
   }

   public int updateTrigger(Connection conn, OperableTrigger trigger, String state, JobDetail jobDetail) throws SQLException, IOException {
      ByteArrayOutputStream baos = this.serializeJobData(trigger.getJobDataMap());
      int len = baos.toByteArray().length;
      ByteArrayInputStream bais = new ByteArrayInputStream(baos.toByteArray());
      PreparedStatement ps = null;
      boolean var9 = false;

      int insertResult;
      try {
         ps = conn.prepareStatement(this.rtp("UPDATE {0}TRIGGERS SET JOB_NAME = ?, JOB_GROUP = ?, DESCRIPTION = ?, NEXT_FIRE_TIME = ?, PREV_FIRE_TIME = ?, TRIGGER_STATE = ?, TRIGGER_TYPE = ?, START_TIME = ?, END_TIME = ?, CALENDAR_NAME = ?, MISFIRE_INSTR = ?, PRIORITY = ?, JOB_DATA = ? WHERE SCHED_NAME = {1} AND TRIGGER_NAME = ? AND TRIGGER_GROUP = ?"));
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
         ps.setBinaryStream(13, bais, len);
         ps.setString(14, trigger.getKey().getName());
         ps.setString(15, trigger.getKey().getGroup());
         insertResult = ps.executeUpdate();
         if (tDel == null) {
            this.updateBlobTrigger(conn, trigger);
         } else {
            tDel.updateExtendedTriggerProperties(conn, trigger, state, jobDetail);
         }
      } finally {
         closeStatement(ps);
      }

      return insertResult;
   }

   public int updateJobData(Connection conn, JobDetail job) throws IOException, SQLException {
      ByteArrayOutputStream baos = this.serializeJobData(job.getJobDataMap());
      int len = baos.toByteArray().length;
      ByteArrayInputStream bais = new ByteArrayInputStream(baos.toByteArray());
      PreparedStatement ps = null;

      int var7;
      try {
         ps = conn.prepareStatement(this.rtp("UPDATE {0}JOB_DETAILS SET JOB_DATA = ?  WHERE SCHED_NAME = {1} AND JOB_NAME = ? AND JOB_GROUP = ?"));
         ps.setBinaryStream(1, bais, len);
         ps.setString(2, job.getKey().getName());
         ps.setString(3, job.getKey().getGroup());
         var7 = ps.executeUpdate();
      } finally {
         closeStatement(ps);
      }

      return var7;
   }

   public int insertCalendar(Connection conn, String calendarName, Calendar calendar) throws IOException, SQLException {
      ByteArrayOutputStream baos = this.serializeObject(calendar);
      byte[] buf = baos.toByteArray();
      ByteArrayInputStream bais = new ByteArrayInputStream(buf);
      PreparedStatement ps = null;

      int var8;
      try {
         ps = conn.prepareStatement(this.rtp("INSERT INTO {0}CALENDARS (SCHED_NAME, CALENDAR_NAME, CALENDAR)  VALUES({1}, ?, ?)"));
         ps.setString(1, calendarName);
         ps.setBinaryStream(2, bais, buf.length);
         var8 = ps.executeUpdate();
      } finally {
         closeStatement(ps);
      }

      return var8;
   }

   public int updateCalendar(Connection conn, String calendarName, Calendar calendar) throws IOException, SQLException {
      ByteArrayOutputStream baos = this.serializeObject(calendar);
      byte[] buf = baos.toByteArray();
      ByteArrayInputStream bais = new ByteArrayInputStream(buf);
      PreparedStatement ps = null;

      int var8;
      try {
         ps = conn.prepareStatement(this.rtp("UPDATE {0}CALENDARS SET CALENDAR = ?  WHERE SCHED_NAME = {1} AND CALENDAR_NAME = ?"));
         ps.setBinaryStream(1, bais, buf.length);
         ps.setString(2, calendarName);
         var8 = ps.executeUpdate();
      } finally {
         closeStatement(ps);
      }

      return var8;
   }

   protected Object getObjectFromBlob(ResultSet rs, String colName) throws ClassNotFoundException, IOException, SQLException {
      Object obj = null;
      byte[] binaryData = rs.getBytes(colName);
      InputStream binaryInput = new ByteArrayInputStream(binaryData);
      if (null != binaryInput && binaryInput.available() != 0) {
         ObjectInputStream in = new ObjectInputStream(binaryInput);

         try {
            obj = in.readObject();
         } finally {
            in.close();
         }
      }

      return obj;
   }

   protected Object getJobDataFromBlob(ResultSet rs, String colName) throws ClassNotFoundException, IOException, SQLException {
      if (this.canUseProperties()) {
         byte[] data = rs.getBytes(colName);
         if (data == null) {
            return null;
         } else {
            InputStream binaryInput = new ByteArrayInputStream(data);
            return binaryInput;
         }
      } else {
         return this.getObjectFromBlob(rs, colName);
      }
   }
}
