package org.quartz;

import org.quartz.impl.JobDetailImpl;
import org.quartz.utils.Key;

public class JobBuilder {
   private JobKey key;
   private String description;
   private Class<? extends Job> jobClass;
   private boolean durability;
   private boolean shouldRecover;
   private JobDataMap jobDataMap = new JobDataMap();

   protected JobBuilder() {
   }

   public static JobBuilder newJob() {
      return new JobBuilder();
   }

   public static JobBuilder newJob(Class<? extends Job> jobClass) {
      JobBuilder b = new JobBuilder();
      b.ofType(jobClass);
      return b;
   }

   public JobDetail build() {
      JobDetailImpl job = new JobDetailImpl();
      job.setJobClass(this.jobClass);
      job.setDescription(this.description);
      if (this.key == null) {
         this.key = new JobKey(Key.createUniqueName((String)null), (String)null);
      }

      job.setKey(this.key);
      job.setDurability(this.durability);
      job.setRequestsRecovery(this.shouldRecover);
      if (!this.jobDataMap.isEmpty()) {
         job.setJobDataMap(this.jobDataMap);
      }

      return job;
   }

   public JobBuilder withIdentity(String name) {
      this.key = new JobKey(name, (String)null);
      return this;
   }

   public JobBuilder withIdentity(String name, String group) {
      this.key = new JobKey(name, group);
      return this;
   }

   public JobBuilder withIdentity(JobKey jobKey) {
      this.key = jobKey;
      return this;
   }

   public JobBuilder withDescription(String jobDescription) {
      this.description = jobDescription;
      return this;
   }

   public JobBuilder ofType(Class<? extends Job> jobClazz) {
      this.jobClass = jobClazz;
      return this;
   }

   public JobBuilder requestRecovery() {
      this.shouldRecover = true;
      return this;
   }

   public JobBuilder requestRecovery(boolean jobShouldRecover) {
      this.shouldRecover = jobShouldRecover;
      return this;
   }

   public JobBuilder storeDurably() {
      this.durability = true;
      return this;
   }

   public JobBuilder storeDurably(boolean jobDurability) {
      this.durability = jobDurability;
      return this;
   }

   public JobBuilder usingJobData(String dataKey, String value) {
      this.jobDataMap.put(dataKey, value);
      return this;
   }

   public JobBuilder usingJobData(String dataKey, Integer value) {
      this.jobDataMap.put(dataKey, value);
      return this;
   }

   public JobBuilder usingJobData(String dataKey, Long value) {
      this.jobDataMap.put(dataKey, value);
      return this;
   }

   public JobBuilder usingJobData(String dataKey, Float value) {
      this.jobDataMap.put(dataKey, value);
      return this;
   }

   public JobBuilder usingJobData(String dataKey, Double value) {
      this.jobDataMap.put(dataKey, value);
      return this;
   }

   public JobBuilder usingJobData(String dataKey, Boolean value) {
      this.jobDataMap.put(dataKey, value);
      return this;
   }

   public JobBuilder usingJobData(JobDataMap newJobDataMap) {
      this.jobDataMap.putAll(newJobDataMap);
      return this;
   }

   public JobBuilder setJobData(JobDataMap newJobDataMap) {
      this.jobDataMap = newJobDataMap;
      return this;
   }
}
