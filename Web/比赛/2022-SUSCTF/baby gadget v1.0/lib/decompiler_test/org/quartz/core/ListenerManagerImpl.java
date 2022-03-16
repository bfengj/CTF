package org.quartz.core;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.Collections;
import java.util.LinkedHashMap;
import java.util.LinkedList;
import java.util.List;
import java.util.Map;
import org.quartz.JobKey;
import org.quartz.JobListener;
import org.quartz.ListenerManager;
import org.quartz.Matcher;
import org.quartz.SchedulerListener;
import org.quartz.TriggerKey;
import org.quartz.TriggerListener;
import org.quartz.impl.matchers.EverythingMatcher;

public class ListenerManagerImpl implements ListenerManager {
   private Map<String, JobListener> globalJobListeners = new LinkedHashMap(10);
   private Map<String, TriggerListener> globalTriggerListeners = new LinkedHashMap(10);
   private Map<String, List<Matcher<JobKey>>> globalJobListenersMatchers = new LinkedHashMap(10);
   private Map<String, List<Matcher<TriggerKey>>> globalTriggerListenersMatchers = new LinkedHashMap(10);
   private ArrayList<SchedulerListener> schedulerListeners = new ArrayList(10);

   public void addJobListener(JobListener jobListener, Matcher<JobKey>... matchers) {
      this.addJobListener(jobListener, Arrays.asList(matchers));
   }

   public void addJobListener(JobListener jobListener, List<Matcher<JobKey>> matchers) {
      if (jobListener.getName() != null && jobListener.getName().length() != 0) {
         synchronized(this.globalJobListeners) {
            this.globalJobListeners.put(jobListener.getName(), jobListener);
            LinkedList<Matcher<JobKey>> matchersL = new LinkedList();
            if (matchers != null && matchers.size() > 0) {
               matchersL.addAll(matchers);
            } else {
               matchersL.add(EverythingMatcher.allJobs());
            }

            this.globalJobListenersMatchers.put(jobListener.getName(), matchersL);
         }
      } else {
         throw new IllegalArgumentException("JobListener name cannot be empty.");
      }
   }

   public void addJobListener(JobListener jobListener) {
      this.addJobListener(jobListener, (Matcher)EverythingMatcher.allJobs());
   }

   public void addJobListener(JobListener jobListener, Matcher<JobKey> matcher) {
      if (jobListener.getName() != null && jobListener.getName().length() != 0) {
         synchronized(this.globalJobListeners) {
            this.globalJobListeners.put(jobListener.getName(), jobListener);
            LinkedList<Matcher<JobKey>> matchersL = new LinkedList();
            if (matcher != null) {
               matchersL.add(matcher);
            } else {
               matchersL.add(EverythingMatcher.allJobs());
            }

            this.globalJobListenersMatchers.put(jobListener.getName(), matchersL);
         }
      } else {
         throw new IllegalArgumentException("JobListener name cannot be empty.");
      }
   }

   public boolean addJobListenerMatcher(String listenerName, Matcher<JobKey> matcher) {
      if (matcher == null) {
         throw new IllegalArgumentException("Null value not acceptable.");
      } else {
         synchronized(this.globalJobListeners) {
            List<Matcher<JobKey>> matchers = (List)this.globalJobListenersMatchers.get(listenerName);
            if (matchers == null) {
               return false;
            } else {
               matchers.add(matcher);
               return true;
            }
         }
      }
   }

   public boolean removeJobListenerMatcher(String listenerName, Matcher<JobKey> matcher) {
      if (matcher == null) {
         throw new IllegalArgumentException("Non-null value not acceptable.");
      } else {
         synchronized(this.globalJobListeners) {
            List<Matcher<JobKey>> matchers = (List)this.globalJobListenersMatchers.get(listenerName);
            return matchers == null ? false : matchers.remove(matcher);
         }
      }
   }

   public List<Matcher<JobKey>> getJobListenerMatchers(String listenerName) {
      synchronized(this.globalJobListeners) {
         List<Matcher<JobKey>> matchers = (List)this.globalJobListenersMatchers.get(listenerName);
         return matchers == null ? null : Collections.unmodifiableList(matchers);
      }
   }

   public boolean setJobListenerMatchers(String listenerName, List<Matcher<JobKey>> matchers) {
      if (matchers == null) {
         throw new IllegalArgumentException("Non-null value not acceptable.");
      } else {
         synchronized(this.globalJobListeners) {
            List<Matcher<JobKey>> oldMatchers = (List)this.globalJobListenersMatchers.get(listenerName);
            if (oldMatchers == null) {
               return false;
            } else {
               this.globalJobListenersMatchers.put(listenerName, matchers);
               return true;
            }
         }
      }
   }

   public boolean removeJobListener(String name) {
      synchronized(this.globalJobListeners) {
         return this.globalJobListeners.remove(name) != null;
      }
   }

   public List<JobListener> getJobListeners() {
      synchronized(this.globalJobListeners) {
         return Collections.unmodifiableList(new LinkedList(this.globalJobListeners.values()));
      }
   }

   public JobListener getJobListener(String name) {
      synchronized(this.globalJobListeners) {
         return (JobListener)this.globalJobListeners.get(name);
      }
   }

   public void addTriggerListener(TriggerListener triggerListener, Matcher<TriggerKey>... matchers) {
      this.addTriggerListener(triggerListener, Arrays.asList(matchers));
   }

   public void addTriggerListener(TriggerListener triggerListener, List<Matcher<TriggerKey>> matchers) {
      if (triggerListener.getName() != null && triggerListener.getName().length() != 0) {
         synchronized(this.globalTriggerListeners) {
            this.globalTriggerListeners.put(triggerListener.getName(), triggerListener);
            LinkedList<Matcher<TriggerKey>> matchersL = new LinkedList();
            if (matchers != null && matchers.size() > 0) {
               matchersL.addAll(matchers);
            } else {
               matchersL.add(EverythingMatcher.allTriggers());
            }

            this.globalTriggerListenersMatchers.put(triggerListener.getName(), matchersL);
         }
      } else {
         throw new IllegalArgumentException("TriggerListener name cannot be empty.");
      }
   }

   public void addTriggerListener(TriggerListener triggerListener) {
      this.addTriggerListener(triggerListener, (Matcher)EverythingMatcher.allTriggers());
   }

   public void addTriggerListener(TriggerListener triggerListener, Matcher<TriggerKey> matcher) {
      if (matcher == null) {
         throw new IllegalArgumentException("Null value not acceptable for matcher.");
      } else if (triggerListener.getName() != null && triggerListener.getName().length() != 0) {
         synchronized(this.globalTriggerListeners) {
            this.globalTriggerListeners.put(triggerListener.getName(), triggerListener);
            List<Matcher<TriggerKey>> matchers = new LinkedList();
            matchers.add(matcher);
            this.globalTriggerListenersMatchers.put(triggerListener.getName(), matchers);
         }
      } else {
         throw new IllegalArgumentException("TriggerListener name cannot be empty.");
      }
   }

   public boolean addTriggerListenerMatcher(String listenerName, Matcher<TriggerKey> matcher) {
      if (matcher == null) {
         throw new IllegalArgumentException("Non-null value not acceptable.");
      } else {
         synchronized(this.globalTriggerListeners) {
            List<Matcher<TriggerKey>> matchers = (List)this.globalTriggerListenersMatchers.get(listenerName);
            if (matchers == null) {
               return false;
            } else {
               matchers.add(matcher);
               return true;
            }
         }
      }
   }

   public boolean removeTriggerListenerMatcher(String listenerName, Matcher<TriggerKey> matcher) {
      if (matcher == null) {
         throw new IllegalArgumentException("Non-null value not acceptable.");
      } else {
         synchronized(this.globalTriggerListeners) {
            List<Matcher<TriggerKey>> matchers = (List)this.globalTriggerListenersMatchers.get(listenerName);
            return matchers == null ? false : matchers.remove(matcher);
         }
      }
   }

   public List<Matcher<TriggerKey>> getTriggerListenerMatchers(String listenerName) {
      synchronized(this.globalTriggerListeners) {
         List<Matcher<TriggerKey>> matchers = (List)this.globalTriggerListenersMatchers.get(listenerName);
         return matchers == null ? null : Collections.unmodifiableList(matchers);
      }
   }

   public boolean setTriggerListenerMatchers(String listenerName, List<Matcher<TriggerKey>> matchers) {
      if (matchers == null) {
         throw new IllegalArgumentException("Non-null value not acceptable.");
      } else {
         synchronized(this.globalTriggerListeners) {
            List<Matcher<TriggerKey>> oldMatchers = (List)this.globalTriggerListenersMatchers.get(listenerName);
            if (oldMatchers == null) {
               return false;
            } else {
               this.globalTriggerListenersMatchers.put(listenerName, matchers);
               return true;
            }
         }
      }
   }

   public boolean removeTriggerListener(String name) {
      synchronized(this.globalTriggerListeners) {
         return this.globalTriggerListeners.remove(name) != null;
      }
   }

   public List<TriggerListener> getTriggerListeners() {
      synchronized(this.globalTriggerListeners) {
         return Collections.unmodifiableList(new LinkedList(this.globalTriggerListeners.values()));
      }
   }

   public TriggerListener getTriggerListener(String name) {
      synchronized(this.globalTriggerListeners) {
         return (TriggerListener)this.globalTriggerListeners.get(name);
      }
   }

   public void addSchedulerListener(SchedulerListener schedulerListener) {
      synchronized(this.schedulerListeners) {
         this.schedulerListeners.add(schedulerListener);
      }
   }

   public boolean removeSchedulerListener(SchedulerListener schedulerListener) {
      synchronized(this.schedulerListeners) {
         return this.schedulerListeners.remove(schedulerListener);
      }
   }

   public List<SchedulerListener> getSchedulerListeners() {
      synchronized(this.schedulerListeners) {
         return Collections.unmodifiableList(new ArrayList(this.schedulerListeners));
      }
   }
}
