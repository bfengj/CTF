package org.quartz.impl.jdbcjobstore;

import java.util.Date;
import org.quartz.JobKey;
import org.quartz.TriggerKey;

public class TriggerStatus {
   private TriggerKey key;
   private JobKey jobKey;
   private String status;
   private Date nextFireTime;

   public TriggerStatus(String status, Date nextFireTime) {
      this.status = status;
      this.nextFireTime = nextFireTime;
   }

   public JobKey getJobKey() {
      return this.jobKey;
   }

   public void setJobKey(JobKey jobKey) {
      this.jobKey = jobKey;
   }

   public TriggerKey getKey() {
      return this.key;
   }

   public void setKey(TriggerKey key) {
      this.key = key;
   }

   public String getStatus() {
      return this.status;
   }

   public Date getNextFireTime() {
      return this.nextFireTime;
   }

   public String toString() {
      return "status: " + this.getStatus() + ", next Fire = " + this.getNextFireTime();
   }
}
