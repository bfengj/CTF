package org.quartz.simpl;

import org.quartz.JobDetail;
import org.quartz.JobKey;

class JobWrapper {
   public JobKey key;
   public JobDetail jobDetail;

   JobWrapper(JobDetail jobDetail) {
      this.jobDetail = jobDetail;
      this.key = jobDetail.getKey();
   }

   public boolean equals(Object obj) {
      if (obj instanceof JobWrapper) {
         JobWrapper jw = (JobWrapper)obj;
         if (jw.key.equals(this.key)) {
            return true;
         }
      }

      return false;
   }

   public int hashCode() {
      return this.key.hashCode();
   }
}
