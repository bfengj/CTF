package org.quartz.impl;

import java.io.Serializable;
import org.quartz.DisallowConcurrentExecution;
import org.quartz.Job;
import org.quartz.JobBuilder;
import org.quartz.JobDataMap;
import org.quartz.JobDetail;
import org.quartz.JobKey;
import org.quartz.PersistJobDataAfterExecution;
import org.quartz.utils.ClassUtils;

public class JobDetailImpl implements Cloneable, Serializable, JobDetail {
   private static final long serialVersionUID = -6069784757781506897L;
   private String name;
   private String group;
   private String description;
   private Class<? extends Job> jobClass;
   private JobDataMap jobDataMap;
   private boolean durability;
   private boolean shouldRecover;
   private transient JobKey key;

   public JobDetailImpl() {
      this.group = "DEFAULT";
      this.durability = false;
      this.shouldRecover = false;
      this.key = null;
   }

   /** @deprecated */
   public JobDetailImpl(String name, Class<? extends Job> jobClass) {
      this(name, (String)null, jobClass);
   }

   /** @deprecated */
   public JobDetailImpl(String name, String group, Class<? extends Job> jobClass) {
      this.group = "DEFAULT";
      this.durability = false;
      this.shouldRecover = false;
      this.key = null;
      this.setName(name);
      this.setGroup(group);
      this.setJobClass(jobClass);
   }

   /** @deprecated */
   public JobDetailImpl(String name, String group, Class<? extends Job> jobClass, boolean durability, boolean recover) {
      this.group = "DEFAULT";
      this.durability = false;
      this.shouldRecover = false;
      this.key = null;
      this.setName(name);
      this.setGroup(group);
      this.setJobClass(jobClass);
      this.setDurability(durability);
      this.setRequestsRecovery(recover);
   }

   public String getName() {
      return this.name;
   }

   public void setName(String name) {
      if (name != null && name.trim().length() != 0) {
         this.name = name;
         this.key = null;
      } else {
         throw new IllegalArgumentException("Job name cannot be empty.");
      }
   }

   public String getGroup() {
      return this.group;
   }

   public void setGroup(String group) {
      if (group != null && group.trim().length() == 0) {
         throw new IllegalArgumentException("Group name cannot be empty.");
      } else {
         if (group == null) {
            group = "DEFAULT";
         }

         this.group = group;
         this.key = null;
      }
   }

   public String getFullName() {
      return this.group + "." + this.name;
   }

   public JobKey getKey() {
      if (this.key == null) {
         if (this.getName() == null) {
            return null;
         }

         this.key = new JobKey(this.getName(), this.getGroup());
      }

      return this.key;
   }

   public void setKey(JobKey key) {
      if (key == null) {
         throw new IllegalArgumentException("Key cannot be null!");
      } else {
         this.setName(key.getName());
         this.setGroup(key.getGroup());
         this.key = key;
      }
   }

   public String getDescription() {
      return this.description;
   }

   public void setDescription(String description) {
      this.description = description;
   }

   public Class<? extends Job> getJobClass() {
      return this.jobClass;
   }

   public void setJobClass(Class<? extends Job> jobClass) {
      if (jobClass == null) {
         throw new IllegalArgumentException("Job class cannot be null.");
      } else if (!Job.class.isAssignableFrom(jobClass)) {
         throw new IllegalArgumentException("Job class must implement the Job interface.");
      } else {
         this.jobClass = jobClass;
      }
   }

   public JobDataMap getJobDataMap() {
      if (this.jobDataMap == null) {
         this.jobDataMap = new JobDataMap();
      }

      return this.jobDataMap;
   }

   public void setJobDataMap(JobDataMap jobDataMap) {
      this.jobDataMap = jobDataMap;
   }

   public void setDurability(boolean durability) {
      this.durability = durability;
   }

   public void setRequestsRecovery(boolean shouldRecover) {
      this.shouldRecover = shouldRecover;
   }

   public boolean isDurable() {
      return this.durability;
   }

   public boolean isPersistJobDataAfterExecution() {
      return ClassUtils.isAnnotationPresent(this.jobClass, PersistJobDataAfterExecution.class);
   }

   public boolean isConcurrentExectionDisallowed() {
      return ClassUtils.isAnnotationPresent(this.jobClass, DisallowConcurrentExecution.class);
   }

   public boolean requestsRecovery() {
      return this.shouldRecover;
   }

   public String toString() {
      return "JobDetail '" + this.getFullName() + "':  jobClass: '" + (this.getJobClass() == null ? null : this.getJobClass().getName()) + " concurrentExectionDisallowed: " + this.isConcurrentExectionDisallowed() + " persistJobDataAfterExecution: " + this.isPersistJobDataAfterExecution() + " isDurable: " + this.isDurable() + " requestsRecovers: " + this.requestsRecovery();
   }

   public boolean equals(Object obj) {
      if (!(obj instanceof JobDetail)) {
         return false;
      } else {
         JobDetail other = (JobDetail)obj;
         if (other.getKey() != null && this.getKey() != null) {
            return other.getKey().equals(this.getKey());
         } else {
            return false;
         }
      }
   }

   public int hashCode() {
      return this.getKey().hashCode();
   }

   public Object clone() {
      try {
         JobDetailImpl copy = (JobDetailImpl)super.clone();
         if (this.jobDataMap != null) {
            copy.jobDataMap = (JobDataMap)this.jobDataMap.clone();
         }

         return copy;
      } catch (CloneNotSupportedException var3) {
         throw new IncompatibleClassChangeError("Not Cloneable.");
      }
   }

   public JobBuilder getJobBuilder() {
      JobBuilder b = JobBuilder.newJob().ofType(this.getJobClass()).requestRecovery(this.requestsRecovery()).storeDurably(this.isDurable()).usingJobData(this.getJobDataMap()).withDescription(this.getDescription()).withIdentity(this.getKey());
      return b;
   }
}
