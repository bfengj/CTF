package org.terracotta.quartz.wrappers;

import java.io.Serializable;
import org.quartz.JobDataMap;
import org.quartz.JobDetail;
import org.quartz.JobKey;

public class JobWrapper implements Serializable {
   protected JobDetail jobDetail;

   protected JobWrapper(JobDetail jobDetail) {
      this.jobDetail = jobDetail;
   }

   public JobKey getKey() {
      return this.jobDetail.getKey();
   }

   public boolean equals(Object obj) {
      if (obj instanceof JobWrapper) {
         JobWrapper jw = (JobWrapper)obj;
         if (jw.jobDetail.getKey().equals(this.jobDetail.getKey())) {
            return true;
         }
      }

      return false;
   }

   public int hashCode() {
      return this.jobDetail.getKey().hashCode();
   }

   public String toString() {
      return "[" + this.jobDetail.toString() + "]";
   }

   public boolean requestsRecovery() {
      return this.jobDetail.requestsRecovery();
   }

   public boolean isPersistJobDataAfterExecution() {
      return this.jobDetail.isPersistJobDataAfterExecution();
   }

   public boolean isConcurrentExectionDisallowed() {
      return this.jobDetail.isConcurrentExectionDisallowed();
   }

   public boolean isDurable() {
      return this.jobDetail.isDurable();
   }

   public JobDetail getJobDetailClone() {
      return (JobDetail)this.jobDetail.clone();
   }

   public void setJobDataMap(JobDataMap newData, JobFacade jobFacade) {
      this.jobDetail = this.jobDetail.getJobBuilder().setJobData(newData).build();
      jobFacade.put(this.jobDetail.getKey(), this);
   }

   public JobDataMap getJobDataMapClone() {
      return (JobDataMap)this.jobDetail.getJobDataMap().clone();
   }

   public boolean isInstanceof(Class clazz) {
      return clazz.isInstance(this.jobDetail);
   }
}
