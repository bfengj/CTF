package org.quartz.impl.jdbcjobstore;

import java.io.IOException;
import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.TimeZone;
import org.quartz.CronScheduleBuilder;
import org.quartz.CronTrigger;
import org.quartz.JobDetail;
import org.quartz.TriggerKey;
import org.quartz.impl.triggers.CronTriggerImpl;
import org.quartz.spi.OperableTrigger;

public class CronTriggerPersistenceDelegate implements TriggerPersistenceDelegate, StdJDBCConstants {
   protected String tablePrefix;
   protected String schedNameLiteral;

   public void initialize(String theTablePrefix, String schedName) {
      this.tablePrefix = theTablePrefix;
      this.schedNameLiteral = "'" + schedName + "'";
   }

   public String getHandledTriggerTypeDiscriminator() {
      return "CRON";
   }

   public boolean canHandleTriggerType(OperableTrigger trigger) {
      return trigger instanceof CronTriggerImpl && !((CronTriggerImpl)trigger).hasAdditionalProperties();
   }

   public int deleteExtendedTriggerProperties(Connection conn, TriggerKey triggerKey) throws SQLException {
      PreparedStatement ps = null;

      int var4;
      try {
         ps = conn.prepareStatement(Util.rtp("DELETE FROM {0}CRON_TRIGGERS WHERE SCHED_NAME = {1} AND TRIGGER_NAME = ? AND TRIGGER_GROUP = ?", this.tablePrefix, this.schedNameLiteral));
         ps.setString(1, triggerKey.getName());
         ps.setString(2, triggerKey.getGroup());
         var4 = ps.executeUpdate();
      } finally {
         Util.closeStatement(ps);
      }

      return var4;
   }

   public int insertExtendedTriggerProperties(Connection conn, OperableTrigger trigger, String state, JobDetail jobDetail) throws SQLException, IOException {
      CronTrigger cronTrigger = (CronTrigger)trigger;
      PreparedStatement ps = null;

      int var7;
      try {
         ps = conn.prepareStatement(Util.rtp("INSERT INTO {0}CRON_TRIGGERS (SCHED_NAME, TRIGGER_NAME, TRIGGER_GROUP, CRON_EXPRESSION, TIME_ZONE_ID)  VALUES({1}, ?, ?, ?, ?)", this.tablePrefix, this.schedNameLiteral));
         ps.setString(1, trigger.getKey().getName());
         ps.setString(2, trigger.getKey().getGroup());
         ps.setString(3, cronTrigger.getCronExpression());
         ps.setString(4, cronTrigger.getTimeZone().getID());
         var7 = ps.executeUpdate();
      } finally {
         Util.closeStatement(ps);
      }

      return var7;
   }

   public TriggerPersistenceDelegate.TriggerPropertyBundle loadExtendedTriggerProperties(Connection conn, TriggerKey triggerKey) throws SQLException {
      PreparedStatement ps = null;
      ResultSet rs = null;

      TriggerPersistenceDelegate.TriggerPropertyBundle var8;
      try {
         ps = conn.prepareStatement(Util.rtp("SELECT * FROM {0}CRON_TRIGGERS WHERE SCHED_NAME = {1} AND TRIGGER_NAME = ? AND TRIGGER_GROUP = ?", this.tablePrefix, this.schedNameLiteral));
         ps.setString(1, triggerKey.getName());
         ps.setString(2, triggerKey.getGroup());
         rs = ps.executeQuery();
         if (!rs.next()) {
            throw new IllegalStateException("No record found for selection of Trigger with key: '" + triggerKey + "' and statement: " + Util.rtp("SELECT * FROM {0}CRON_TRIGGERS WHERE SCHED_NAME = {1} AND TRIGGER_NAME = ? AND TRIGGER_GROUP = ?", this.tablePrefix, this.schedNameLiteral));
         }

         String cronExpr = rs.getString("CRON_EXPRESSION");
         String timeZoneId = rs.getString("TIME_ZONE_ID");
         CronScheduleBuilder cb = CronScheduleBuilder.cronSchedule(cronExpr);
         if (timeZoneId != null) {
            cb.inTimeZone(TimeZone.getTimeZone(timeZoneId));
         }

         var8 = new TriggerPersistenceDelegate.TriggerPropertyBundle(cb, (String[])null, (Object[])null);
      } finally {
         Util.closeResultSet(rs);
         Util.closeStatement(ps);
      }

      return var8;
   }

   public int updateExtendedTriggerProperties(Connection conn, OperableTrigger trigger, String state, JobDetail jobDetail) throws SQLException, IOException {
      CronTrigger cronTrigger = (CronTrigger)trigger;
      PreparedStatement ps = null;

      int var7;
      try {
         ps = conn.prepareStatement(Util.rtp("UPDATE {0}CRON_TRIGGERS SET CRON_EXPRESSION = ?, TIME_ZONE_ID = ? WHERE SCHED_NAME = {1} AND TRIGGER_NAME = ? AND TRIGGER_GROUP = ?", this.tablePrefix, this.schedNameLiteral));
         ps.setString(1, cronTrigger.getCronExpression());
         ps.setString(2, cronTrigger.getTimeZone().getID());
         ps.setString(3, trigger.getKey().getName());
         ps.setString(4, trigger.getKey().getGroup());
         var7 = ps.executeUpdate();
      } finally {
         Util.closeStatement(ps);
      }

      return var7;
   }
}
