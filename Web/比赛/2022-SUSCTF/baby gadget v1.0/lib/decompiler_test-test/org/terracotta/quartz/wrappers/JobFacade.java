package org.terracotta.quartz.wrappers;

import java.util.Set;
import org.quartz.JobKey;
import org.terracotta.quartz.collections.ToolkitDSHolder;
import org.terracotta.toolkit.store.ToolkitStore;

public class JobFacade {
   private final ToolkitStore<JobKey, JobWrapper> jobsByFQN;
   private final Set<String> allJobsGroupNames;
   private final Set<String> pausedJobGroups;
   private final Set<JobKey> blockedJobs;

   public JobFacade(ToolkitDSHolder toolkitDSHolder) {
      this.jobsByFQN = toolkitDSHolder.getOrCreateJobsMap();
      this.allJobsGroupNames = toolkitDSHolder.getOrCreateAllGroupsSet();
      this.pausedJobGroups = toolkitDSHolder.getOrCreatePausedGroupsSet();
      this.blockedJobs = toolkitDSHolder.getOrCreateBlockedJobsSet();
   }

   public JobWrapper get(JobKey jobKey) {
      return (JobWrapper)this.jobsByFQN.get(jobKey);
   }

   public void put(JobKey jobKey, JobWrapper jobWrapper) {
      this.jobsByFQN.putNoReturn(jobKey, jobWrapper);
   }

   public boolean containsKey(JobKey key) {
      return this.jobsByFQN.containsKey(key);
   }

   public boolean hasGroup(String name) {
      return this.allJobsGroupNames.contains(name);
   }

   public boolean addGroup(String name) {
      return this.allJobsGroupNames.add(name);
   }

   public boolean addPausedGroup(String name) {
      return this.pausedJobGroups.add(name);
   }

   public JobWrapper remove(JobKey jobKey) {
      return (JobWrapper)this.jobsByFQN.remove(jobKey);
   }

   public boolean removeGroup(String group) {
      return this.allJobsGroupNames.remove(group);
   }

   public boolean pausedGroupsContain(String group) {
      return this.pausedJobGroups.contains(group);
   }

   public boolean blockedJobsContain(JobKey jobKey) {
      return this.blockedJobs.contains(jobKey);
   }

   public int numberOfJobs() {
      return this.jobsByFQN.size();
   }

   public Set<String> getAllGroupNames() {
      return this.allJobsGroupNames;
   }

   public boolean removePausedJobGroup(String group) {
      return this.pausedJobGroups.remove(group);
   }

   public void clearPausedJobGroups() {
      this.pausedJobGroups.clear();
   }

   public void addBlockedJob(JobKey key) {
      this.blockedJobs.add(key);
   }

   public boolean removeBlockedJob(JobKey key) {
      return this.blockedJobs.remove(key);
   }
}
