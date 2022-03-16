package org.quartz.impl.jdbcjobstore;

import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import org.quartz.JobKey;

public class DB2v6Delegate extends StdJDBCDelegate {
   public static final String SELECT_NUM_JOBS = "SELECT COUNT(*) FROM {0}JOB_DETAILS WHERE SCHED_NAME = {1}";
   public static final String SELECT_NUM_TRIGGERS_FOR_JOB = "SELECT COUNT(*) FROM {0}TRIGGERS WHERE SCHED_NAME = {1} AND JOB_NAME = ? AND JOB_GROUP = ?";
   public static final String SELECT_NUM_TRIGGERS = "SELECT COUNT(*) FROM {0}TRIGGERS WHERE SCHED_NAME = {1}";
   public static final String SELECT_NUM_CALENDARS = "SELECT COUNT(*) FROM {0}CALENDARS WHERE SCHED_NAME = {1}";

   public int selectNumJobs(Connection conn) throws SQLException {
      PreparedStatement ps = null;
      ResultSet rs = null;

      int var5;
      try {
         int count = 0;
         ps = conn.prepareStatement(this.rtp("SELECT COUNT(*) FROM {0}JOB_DETAILS WHERE SCHED_NAME = {1}"));
         rs = ps.executeQuery();
         if (rs.next()) {
            count = rs.getInt(1);
         }

         var5 = count;
      } finally {
         closeResultSet(rs);
         closeStatement(ps);
      }

      return var5;
   }

   public int selectNumTriggersForJob(Connection conn, JobKey jobKey) throws SQLException {
      PreparedStatement ps = null;
      ResultSet rs = null;

      byte var5;
      try {
         ps = conn.prepareStatement(this.rtp("SELECT COUNT(*) FROM {0}TRIGGERS WHERE SCHED_NAME = {1} AND JOB_NAME = ? AND JOB_GROUP = ?"));
         ps.setString(1, jobKey.getName());
         ps.setString(2, jobKey.getGroup());
         rs = ps.executeQuery();
         if (rs.next()) {
            int var9 = rs.getInt(1);
            return var9;
         }

         var5 = 0;
      } finally {
         closeResultSet(rs);
         closeStatement(ps);
      }

      return var5;
   }

   public int selectNumTriggers(Connection conn) throws SQLException {
      PreparedStatement ps = null;
      ResultSet rs = null;

      int var5;
      try {
         int count = 0;
         ps = conn.prepareStatement(this.rtp("SELECT COUNT(*) FROM {0}TRIGGERS WHERE SCHED_NAME = {1}"));
         rs = ps.executeQuery();
         if (rs.next()) {
            count = rs.getInt(1);
         }

         var5 = count;
      } finally {
         closeResultSet(rs);
         closeStatement(ps);
      }

      return var5;
   }

   public int selectNumCalendars(Connection conn) throws SQLException {
      PreparedStatement ps = null;
      ResultSet rs = null;

      int var5;
      try {
         int count = 0;
         ps = conn.prepareStatement(this.rtp("SELECT COUNT(*) FROM {0}CALENDARS WHERE SCHED_NAME = {1}"));
         rs = ps.executeQuery();
         if (rs.next()) {
            count = rs.getInt(1);
         }

         var5 = count;
      } finally {
         closeResultSet(rs);
         closeStatement(ps);
      }

      return var5;
   }
}
