package org.quartz;

import java.util.List;

public interface ListenerManager {
   void addJobListener(JobListener var1);

   void addJobListener(JobListener var1, Matcher<JobKey> var2);

   void addJobListener(JobListener var1, Matcher<JobKey>... var2);

   void addJobListener(JobListener var1, List<Matcher<JobKey>> var2);

   boolean addJobListenerMatcher(String var1, Matcher<JobKey> var2);

   boolean removeJobListenerMatcher(String var1, Matcher<JobKey> var2);

   boolean setJobListenerMatchers(String var1, List<Matcher<JobKey>> var2);

   List<Matcher<JobKey>> getJobListenerMatchers(String var1);

   boolean removeJobListener(String var1);

   List<JobListener> getJobListeners();

   JobListener getJobListener(String var1);

   void addTriggerListener(TriggerListener var1);

   void addTriggerListener(TriggerListener var1, Matcher<TriggerKey> var2);

   void addTriggerListener(TriggerListener var1, Matcher<TriggerKey>... var2);

   void addTriggerListener(TriggerListener var1, List<Matcher<TriggerKey>> var2);

   boolean addTriggerListenerMatcher(String var1, Matcher<TriggerKey> var2);

   boolean removeTriggerListenerMatcher(String var1, Matcher<TriggerKey> var2);

   boolean setTriggerListenerMatchers(String var1, List<Matcher<TriggerKey>> var2);

   List<Matcher<TriggerKey>> getTriggerListenerMatchers(String var1);

   boolean removeTriggerListener(String var1);

   List<TriggerListener> getTriggerListeners();

   TriggerListener getTriggerListener(String var1);

   void addSchedulerListener(SchedulerListener var1);

   boolean removeSchedulerListener(SchedulerListener var1);

   List<SchedulerListener> getSchedulerListeners();
}
