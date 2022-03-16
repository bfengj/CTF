package org.quartz.impl.jdbcjobstore;

import java.io.Serializable;

public class SchedulerStateRecord implements Serializable {
   private static final long serialVersionUID = -715704959016191445L;
   private String schedulerInstanceId;
   private long checkinTimestamp;
   private long checkinInterval;

   public long getCheckinInterval() {
      return this.checkinInterval;
   }

   public long getCheckinTimestamp() {
      return this.checkinTimestamp;
   }

   public String getSchedulerInstanceId() {
      return this.schedulerInstanceId;
   }

   public void setCheckinInterval(long l) {
      this.checkinInterval = l;
   }

   public void setCheckinTimestamp(long l) {
      this.checkinTimestamp = l;
   }

   public void setSchedulerInstanceId(String string) {
      this.schedulerInstanceId = string;
   }
}
