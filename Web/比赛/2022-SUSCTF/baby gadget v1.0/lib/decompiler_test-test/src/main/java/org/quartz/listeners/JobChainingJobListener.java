package org.quartz.listeners;

import java.util.HashMap;
import java.util.Map;
import org.quartz.JobExecutionContext;
import org.quartz.JobExecutionException;
import org.quartz.JobKey;
import org.quartz.SchedulerException;

public class JobChainingJobListener extends JobListenerSupport {
   private String name;
   private Map<JobKey, JobKey> chainLinks;

   public JobChainingJobListener(String name) {
      if (name == null) {
         throw new IllegalArgumentException("Listener name cannot be null!");
      } else {
         this.name = name;
         this.chainLinks = new HashMap();
      }
   }

   public String getName() {
      return this.name;
   }

   public void addJobChainLink(JobKey firstJob, JobKey secondJob) {
      if (firstJob != null && secondJob != null) {
         if (firstJob.getName() != null && secondJob.getName() != null) {
            this.chainLinks.put(firstJob, secondJob);
         } else {
            throw new IllegalArgumentException("Key cannot have a null name!");
         }
      } else {
         throw new IllegalArgumentException("Key cannot be null!");
      }
   }

   public void jobWasExecuted(JobExecutionContext context, JobExecutionException jobException) {
      JobKey sj = (JobKey)this.chainLinks.get(context.getJobDetail().getKey());
      if (sj != null) {
         this.getLog().info("Job '" + context.getJobDetail().getKey() + "' will now chain to Job '" + sj + "'");

         try {
            context.getScheduler().triggerJob(sj);
         } catch (SchedulerException var5) {
            this.getLog().error("Error encountered during chaining to Job '" + sj + "'", var5);
         }

      }
   }
}
