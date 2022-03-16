package org.quartz.impl.jdbcjobstore;

import java.io.IOException;
import java.sql.Connection;
import java.sql.SQLException;
import org.quartz.JobDetail;
import org.quartz.ScheduleBuilder;
import org.quartz.TriggerKey;
import org.quartz.spi.OperableTrigger;

public interface TriggerPersistenceDelegate {
   void initialize(String var1, String var2);

   boolean canHandleTriggerType(OperableTrigger var1);

   String getHandledTriggerTypeDiscriminator();

   int insertExtendedTriggerProperties(Connection var1, OperableTrigger var2, String var3, JobDetail var4) throws SQLException, IOException;

   int updateExtendedTriggerProperties(Connection var1, OperableTrigger var2, String var3, JobDetail var4) throws SQLException, IOException;

   int deleteExtendedTriggerProperties(Connection var1, TriggerKey var2) throws SQLException;

   TriggerPersistenceDelegate.TriggerPropertyBundle loadExtendedTriggerProperties(Connection var1, TriggerKey var2) throws SQLException;

   public static class TriggerPropertyBundle {
      private ScheduleBuilder<?> sb;
      private String[] statePropertyNames;
      private Object[] statePropertyValues;

      public TriggerPropertyBundle(ScheduleBuilder<?> sb, String[] statePropertyNames, Object[] statePropertyValues) {
         this.sb = sb;
         this.statePropertyNames = statePropertyNames;
         this.statePropertyValues = statePropertyValues;
      }

      public ScheduleBuilder<?> getScheduleBuilder() {
         return this.sb;
      }

      public String[] getStatePropertyNames() {
         return this.statePropertyNames;
      }

      public Object[] getStatePropertyValues() {
         return this.statePropertyValues;
      }
   }
}
