package org.quartz.impl.jdbcjobstore;

import java.io.IOException;
import java.math.BigDecimal;
import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import org.quartz.JobDetail;
import org.quartz.SimpleScheduleBuilder;
import org.quartz.SimpleTrigger;
import org.quartz.TriggerKey;
import org.quartz.impl.triggers.SimpleTriggerImpl;
import org.quartz.spi.OperableTrigger;

public class SimpleTriggerPersistenceDelegate implements TriggerPersistenceDelegate, StdJDBCConstants {
   protected String tablePrefix;
   protected String schedNameLiteral;

   public void initialize(String theTablePrefix, String schedName) {
      this.tablePrefix = theTablePrefix;
      this.schedNameLiteral = "'" + schedName + "'";
   }

   public String getHandledTriggerTypeDiscriminator() {
      return "SIMPLE";
   }

   public boolean canHandleTriggerType(OperableTrigger trigger) {
      return trigger instanceof SimpleTriggerImpl && !((SimpleTriggerImpl)trigger).hasAdditionalProperties();
   }

   public int deleteExtendedTriggerProperties(Connection conn, TriggerKey triggerKey) throws SQLException {
      PreparedStatement ps = null;

      int var4;
      try {
         ps = conn.prepareStatement(Util.rtp("DELETE FROM {0}SIMPLE_TRIGGERS WHERE SCHED_NAME = {1} AND TRIGGER_NAME = ? AND TRIGGER_GROUP = ?", this.tablePrefix, this.schedNameLiteral));
         ps.setString(1, triggerKey.getName());
         ps.setString(2, triggerKey.getGroup());
         var4 = ps.executeUpdate();
      } finally {
         Util.closeStatement(ps);
      }

      return var4;
   }

   public int insertExtendedTriggerProperties(Connection conn, OperableTrigger trigger, String state, JobDetail jobDetail) throws SQLException, IOException {
      SimpleTrigger simpleTrigger = (SimpleTrigger)trigger;
      PreparedStatement ps = null;

      int var7;
      try {
         ps = conn.prepareStatement(Util.rtp("INSERT INTO {0}SIMPLE_TRIGGERS (SCHED_NAME, TRIGGER_NAME, TRIGGER_GROUP, REPEAT_COUNT, REPEAT_INTERVAL, TIMES_TRIGGERED)  VALUES({1}, ?, ?, ?, ?, ?)", this.tablePrefix, this.schedNameLiteral));
         ps.setString(1, trigger.getKey().getName());
         ps.setString(2, trigger.getKey().getGroup());
         ps.setInt(3, simpleTrigger.getRepeatCount());
         ps.setBigDecimal(4, new BigDecimal(String.valueOf(simpleTrigger.getRepeatInterval())));
         ps.setInt(5, simpleTrigger.getTimesTriggered());
         var7 = ps.executeUpdate();
      } finally {
         Util.closeStatement(ps);
      }

      return var7;
   }

   public TriggerPersistenceDelegate.TriggerPropertyBundle loadExtendedTriggerProperties(Connection conn, TriggerKey triggerKey) throws SQLException {
      PreparedStatement ps = null;
      ResultSet rs = null;

      TriggerPersistenceDelegate.TriggerPropertyBundle var12;
      try {
         ps = conn.prepareStatement(Util.rtp("SELECT * FROM {0}SIMPLE_TRIGGERS WHERE SCHED_NAME = {1} AND TRIGGER_NAME = ? AND TRIGGER_GROUP = ?", this.tablePrefix, this.schedNameLiteral));
         ps.setString(1, triggerKey.getName());
         ps.setString(2, triggerKey.getGroup());
         rs = ps.executeQuery();
         if (!rs.next()) {
            throw new IllegalStateException("No record found for selection of Trigger with key: '" + triggerKey + "' and statement: " + Util.rtp("SELECT * FROM {0}SIMPLE_TRIGGERS WHERE SCHED_NAME = {1} AND TRIGGER_NAME = ? AND TRIGGER_GROUP = ?", this.tablePrefix, this.schedNameLiteral));
         }

         int repeatCount = rs.getInt("REPEAT_COUNT");
         long repeatInterval = rs.getLong("REPEAT_INTERVAL");
         int timesTriggered = rs.getInt("TIMES_TRIGGERED");
         SimpleScheduleBuilder sb = SimpleScheduleBuilder.simpleSchedule().withRepeatCount(repeatCount).withIntervalInMilliseconds(repeatInterval);
         String[] statePropertyNames = new String[]{"timesTriggered"};
         Object[] statePropertyValues = new Object[]{timesTriggered};
         var12 = new TriggerPersistenceDelegate.TriggerPropertyBundle(sb, statePropertyNames, statePropertyValues);
      } finally {
         Util.closeResultSet(rs);
         Util.closeStatement(ps);
      }

      return var12;
   }

   public int updateExtendedTriggerProperties(Connection conn, OperableTrigger trigger, String state, JobDetail jobDetail) throws SQLException, IOException {
      SimpleTrigger simpleTrigger = (SimpleTrigger)trigger;
      PreparedStatement ps = null;

      int var7;
      try {
         ps = conn.prepareStatement(Util.rtp("UPDATE {0}SIMPLE_TRIGGERS SET REPEAT_COUNT = ?, REPEAT_INTERVAL = ?, TIMES_TRIGGERED = ? WHERE SCHED_NAME = {1} AND TRIGGER_NAME = ? AND TRIGGER_GROUP = ?", this.tablePrefix, this.schedNameLiteral));
         ps.setInt(1, simpleTrigger.getRepeatCount());
         ps.setBigDecimal(2, new BigDecimal(String.valueOf(simpleTrigger.getRepeatInterval())));
         ps.setInt(3, simpleTrigger.getTimesTriggered());
         ps.setString(4, simpleTrigger.getKey().getName());
         ps.setString(5, simpleTrigger.getKey().getGroup());
         var7 = ps.executeUpdate();
      } finally {
         Util.closeStatement(ps);
      }

      return var7;
   }
}
