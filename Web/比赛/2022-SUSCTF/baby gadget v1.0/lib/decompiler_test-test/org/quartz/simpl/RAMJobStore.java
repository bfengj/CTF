package org.quartz.simpl;

import java.util.ArrayList;
import java.util.Collection;
import java.util.Collections;
import java.util.Date;
import java.util.HashMap;
import java.util.HashSet;
import java.util.Iterator;
import java.util.LinkedList;
import java.util.List;
import java.util.Map;
import java.util.NoSuchElementException;
import java.util.Set;
import java.util.TreeSet;
import java.util.Map.Entry;
import java.util.concurrent.atomic.AtomicLong;
import org.quartz.Calendar;
import org.quartz.JobDataMap;
import org.quartz.JobDetail;
import org.quartz.JobKey;
import org.quartz.JobPersistenceException;
import org.quartz.ObjectAlreadyExistsException;
import org.quartz.Trigger;
import org.quartz.TriggerKey;
import org.quartz.impl.matchers.GroupMatcher;
import org.quartz.impl.matchers.StringMatcher;
import org.quartz.spi.ClassLoadHelper;
import org.quartz.spi.JobStore;
import org.quartz.spi.OperableTrigger;
import org.quartz.spi.SchedulerSignaler;
import org.quartz.spi.TriggerFiredBundle;
import org.quartz.spi.TriggerFiredResult;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

public class RAMJobStore implements JobStore {
   protected HashMap<JobKey, JobWrapper> jobsByKey = new HashMap(1000);
   protected HashMap<TriggerKey, TriggerWrapper> triggersByKey = new HashMap(1000);
   protected HashMap<String, HashMap<JobKey, JobWrapper>> jobsByGroup = new HashMap(25);
   protected HashMap<String, HashMap<TriggerKey, TriggerWrapper>> triggersByGroup = new HashMap(25);
   protected TreeSet<TriggerWrapper> timeTriggers = new TreeSet(new TriggerWrapperComparator());
   protected HashMap<String, Calendar> calendarsByName = new HashMap(25);
   protected ArrayList<TriggerWrapper> triggers = new ArrayList(1000);
   protected final Object lock = new Object();
   protected HashSet<String> pausedTriggerGroups = new HashSet();
   protected HashSet<String> pausedJobGroups = new HashSet();
   protected HashSet<JobKey> blockedJobs = new HashSet();
   protected long misfireThreshold = 5000L;
   protected SchedulerSignaler signaler;
   private final Logger log = LoggerFactory.getLogger(this.getClass());
   private static final AtomicLong ftrCtr = new AtomicLong(System.currentTimeMillis());

   protected Logger getLog() {
      return this.log;
   }

   public void initialize(ClassLoadHelper loadHelper, SchedulerSignaler schedSignaler) {
      this.signaler = schedSignaler;
      this.getLog().info("RAMJobStore initialized.");
   }

   public void schedulerStarted() {
   }

   public void schedulerPaused() {
   }

   public void schedulerResumed() {
   }

   public long getMisfireThreshold() {
      return this.misfireThreshold;
   }

   public void setMisfireThreshold(long misfireThreshold) {
      if (misfireThreshold < 1L) {
         throw new IllegalArgumentException("Misfire threshold must be larger than 0");
      } else {
         this.misfireThreshold = misfireThreshold;
      }
   }

   public void shutdown() {
   }

   public boolean supportsPersistence() {
      return false;
   }

   public void clearAllSchedulingData() throws JobPersistenceException {
      synchronized(this.lock) {
         List<String> lst = this.getTriggerGroupNames();
         Iterator i$ = lst.iterator();

         String name;
         Set keys;
         Iterator i$;
         while(i$.hasNext()) {
            name = (String)i$.next();
            keys = this.getTriggerKeys(GroupMatcher.triggerGroupEquals(name));
            i$ = keys.iterator();

            while(i$.hasNext()) {
               TriggerKey key = (TriggerKey)i$.next();
               this.removeTrigger(key);
            }
         }

         lst = this.getJobGroupNames();
         i$ = lst.iterator();

         while(i$.hasNext()) {
            name = (String)i$.next();
            keys = this.getJobKeys(GroupMatcher.jobGroupEquals(name));
            i$ = keys.iterator();

            while(i$.hasNext()) {
               JobKey key = (JobKey)i$.next();
               this.removeJob(key);
            }
         }

         lst = this.getCalendarNames();
         i$ = lst.iterator();

         while(i$.hasNext()) {
            name = (String)i$.next();
            this.removeCalendar(name);
         }

      }
   }

   public void storeJobAndTrigger(JobDetail newJob, OperableTrigger newTrigger) throws JobPersistenceException {
      this.storeJob(newJob, false);
      this.storeTrigger(newTrigger, false);
   }

   public void storeJob(JobDetail newJob, boolean replaceExisting) throws ObjectAlreadyExistsException {
      JobWrapper jw = new JobWrapper((JobDetail)newJob.clone());
      boolean repl = false;
      synchronized(this.lock) {
         if (this.jobsByKey.get(jw.key) != null) {
            if (!replaceExisting) {
               throw new ObjectAlreadyExistsException(newJob);
            }

            repl = true;
         }

         if (!repl) {
            HashMap<JobKey, JobWrapper> grpMap = (HashMap)this.jobsByGroup.get(newJob.getKey().getGroup());
            if (grpMap == null) {
               grpMap = new HashMap(100);
               this.jobsByGroup.put(newJob.getKey().getGroup(), grpMap);
            }

            grpMap.put(newJob.getKey(), jw);
            this.jobsByKey.put(jw.key, jw);
         } else {
            JobWrapper orig = (JobWrapper)this.jobsByKey.get(jw.key);
            orig.jobDetail = jw.jobDetail;
         }

      }
   }

   public boolean removeJob(JobKey jobKey) {
      boolean found = false;
      synchronized(this.lock) {
         List<OperableTrigger> triggersOfJob = this.getTriggersForJob(jobKey);

         for(Iterator i$ = triggersOfJob.iterator(); i$.hasNext(); found = true) {
            OperableTrigger trig = (OperableTrigger)i$.next();
            this.removeTrigger(trig.getKey());
         }

         found |= this.jobsByKey.remove(jobKey) != null;
         if (found) {
            HashMap<JobKey, JobWrapper> grpMap = (HashMap)this.jobsByGroup.get(jobKey.getGroup());
            if (grpMap != null) {
               grpMap.remove(jobKey);
               if (grpMap.size() == 0) {
                  this.jobsByGroup.remove(jobKey.getGroup());
               }
            }
         }

         return found;
      }
   }

   public boolean removeJobs(List<JobKey> jobKeys) throws JobPersistenceException {
      boolean allFound = true;
      synchronized(this.lock) {
         JobKey key;
         for(Iterator i$ = jobKeys.iterator(); i$.hasNext(); allFound = this.removeJob(key) && allFound) {
            key = (JobKey)i$.next();
         }

         return allFound;
      }
   }

   public boolean removeTriggers(List<TriggerKey> triggerKeys) throws JobPersistenceException {
      boolean allFound = true;
      synchronized(this.lock) {
         TriggerKey key;
         for(Iterator i$ = triggerKeys.iterator(); i$.hasNext(); allFound = this.removeTrigger(key) && allFound) {
            key = (TriggerKey)i$.next();
         }

         return allFound;
      }
   }

   public void storeJobsAndTriggers(Map<JobDetail, Set<? extends Trigger>> triggersAndJobs, boolean replace) throws JobPersistenceException {
      synchronized(this.lock) {
         Iterator i$;
         Entry e;
         Iterator i$;
         Trigger trigger;
         if (!replace) {
            i$ = triggersAndJobs.entrySet().iterator();

            while(i$.hasNext()) {
               e = (Entry)i$.next();
               if (this.checkExists(((JobDetail)e.getKey()).getKey())) {
                  throw new ObjectAlreadyExistsException((JobDetail)e.getKey());
               }

               i$ = ((Set)e.getValue()).iterator();

               while(i$.hasNext()) {
                  trigger = (Trigger)i$.next();
                  if (this.checkExists(trigger.getKey())) {
                     throw new ObjectAlreadyExistsException(trigger);
                  }
               }
            }
         }

         i$ = triggersAndJobs.entrySet().iterator();

         while(i$.hasNext()) {
            e = (Entry)i$.next();
            this.storeJob((JobDetail)e.getKey(), true);
            i$ = ((Set)e.getValue()).iterator();

            while(i$.hasNext()) {
               trigger = (Trigger)i$.next();
               this.storeTrigger((OperableTrigger)trigger, true);
            }
         }

      }
   }

   public void storeTrigger(OperableTrigger newTrigger, boolean replaceExisting) throws JobPersistenceException {
      TriggerWrapper tw = new TriggerWrapper((OperableTrigger)newTrigger.clone());
      synchronized(this.lock) {
         if (this.triggersByKey.get(tw.key) != null) {
            if (!replaceExisting) {
               throw new ObjectAlreadyExistsException(newTrigger);
            }

            this.removeTrigger(newTrigger.getKey(), false);
         }

         if (this.retrieveJob(newTrigger.getJobKey()) == null) {
            throw new JobPersistenceException("The job (" + newTrigger.getJobKey() + ") referenced by the trigger does not exist.");
         } else {
            this.triggers.add(tw);
            HashMap<TriggerKey, TriggerWrapper> grpMap = (HashMap)this.triggersByGroup.get(newTrigger.getKey().getGroup());
            if (grpMap == null) {
               grpMap = new HashMap(100);
               this.triggersByGroup.put(newTrigger.getKey().getGroup(), grpMap);
            }

            grpMap.put(newTrigger.getKey(), tw);
            this.triggersByKey.put(tw.key, tw);
            if (!this.pausedTriggerGroups.contains(newTrigger.getKey().getGroup()) && !this.pausedJobGroups.contains(newTrigger.getJobKey().getGroup())) {
               if (this.blockedJobs.contains(tw.jobKey)) {
                  tw.state = 5;
               } else {
                  this.timeTriggers.add(tw);
               }
            } else {
               tw.state = 4;
               if (this.blockedJobs.contains(tw.jobKey)) {
                  tw.state = 6;
               }
            }

         }
      }
   }

   public boolean removeTrigger(TriggerKey triggerKey) {
      return this.removeTrigger(triggerKey, true);
   }

   private boolean removeTrigger(TriggerKey key, boolean removeOrphanedJob) {
      synchronized(this.lock) {
         boolean found = this.triggersByKey.remove(key) != null;
         if (found) {
            TriggerWrapper tw = null;
            HashMap<TriggerKey, TriggerWrapper> grpMap = (HashMap)this.triggersByGroup.get(key.getGroup());
            if (grpMap != null) {
               grpMap.remove(key);
               if (grpMap.size() == 0) {
                  this.triggersByGroup.remove(key.getGroup());
               }
            }

            Iterator tgs = this.triggers.iterator();

            while(tgs.hasNext()) {
               tw = (TriggerWrapper)tgs.next();
               if (key.equals(tw.key)) {
                  tgs.remove();
                  break;
               }
            }

            this.timeTriggers.remove(tw);
            if (removeOrphanedJob) {
               JobWrapper jw = (JobWrapper)this.jobsByKey.get(tw.jobKey);
               List<OperableTrigger> trigs = this.getTriggersForJob(tw.jobKey);
               if ((trigs == null || trigs.size() == 0) && !jw.jobDetail.isDurable() && this.removeJob(jw.key)) {
                  this.signaler.notifySchedulerListenersJobDeleted(jw.key);
               }
            }
         }

         return found;
      }
   }

   public boolean replaceTrigger(TriggerKey triggerKey, OperableTrigger newTrigger) throws JobPersistenceException {
      synchronized(this.lock) {
         TriggerWrapper tw = (TriggerWrapper)this.triggersByKey.remove(triggerKey);
         boolean found = tw != null;
         if (found) {
            if (!tw.getTrigger().getJobKey().equals(newTrigger.getJobKey())) {
               throw new JobPersistenceException("New trigger is not related to the same job as the old trigger.");
            }

            tw = null;
            HashMap<TriggerKey, TriggerWrapper> grpMap = (HashMap)this.triggersByGroup.get(triggerKey.getGroup());
            if (grpMap != null) {
               grpMap.remove(triggerKey);
               if (grpMap.size() == 0) {
                  this.triggersByGroup.remove(triggerKey.getGroup());
               }
            }

            Iterator tgs = this.triggers.iterator();

            while(tgs.hasNext()) {
               tw = (TriggerWrapper)tgs.next();
               if (triggerKey.equals(tw.key)) {
                  tgs.remove();
                  break;
               }
            }

            this.timeTriggers.remove(tw);

            try {
               this.storeTrigger(newTrigger, false);
            } catch (JobPersistenceException var10) {
               this.storeTrigger(tw.getTrigger(), false);
               throw var10;
            }
         }

         return found;
      }
   }

   public JobDetail retrieveJob(JobKey jobKey) {
      synchronized(this.lock) {
         JobWrapper jw = (JobWrapper)this.jobsByKey.get(jobKey);
         return jw != null ? (JobDetail)jw.jobDetail.clone() : null;
      }
   }

   public OperableTrigger retrieveTrigger(TriggerKey triggerKey) {
      synchronized(this.lock) {
         TriggerWrapper tw = (TriggerWrapper)this.triggersByKey.get(triggerKey);
         return tw != null ? (OperableTrigger)tw.getTrigger().clone() : null;
      }
   }

   public boolean checkExists(JobKey jobKey) throws JobPersistenceException {
      synchronized(this.lock) {
         JobWrapper jw = (JobWrapper)this.jobsByKey.get(jobKey);
         return jw != null;
      }
   }

   public boolean checkExists(TriggerKey triggerKey) throws JobPersistenceException {
      synchronized(this.lock) {
         TriggerWrapper tw = (TriggerWrapper)this.triggersByKey.get(triggerKey);
         return tw != null;
      }
   }

   public Trigger.TriggerState getTriggerState(TriggerKey triggerKey) throws JobPersistenceException {
      synchronized(this.lock) {
         TriggerWrapper tw = (TriggerWrapper)this.triggersByKey.get(triggerKey);
         if (tw == null) {
            return Trigger.TriggerState.NONE;
         } else if (tw.state == 3) {
            return Trigger.TriggerState.COMPLETE;
         } else if (tw.state == 4) {
            return Trigger.TriggerState.PAUSED;
         } else if (tw.state == 6) {
            return Trigger.TriggerState.PAUSED;
         } else if (tw.state == 5) {
            return Trigger.TriggerState.BLOCKED;
         } else {
            return tw.state == 7 ? Trigger.TriggerState.ERROR : Trigger.TriggerState.NORMAL;
         }
      }
   }

   public void storeCalendar(String name, Calendar calendar, boolean replaceExisting, boolean updateTriggers) throws ObjectAlreadyExistsException {
      calendar = (Calendar)calendar.clone();
      synchronized(this.lock) {
         Object obj = this.calendarsByName.get(name);
         if (obj != null && !replaceExisting) {
            throw new ObjectAlreadyExistsException("Calendar with name '" + name + "' already exists.");
         } else {
            if (obj != null) {
               this.calendarsByName.remove(name);
            }

            this.calendarsByName.put(name, calendar);
            if (obj != null && updateTriggers) {
               Iterator i$ = this.getTriggerWrappersForCalendar(name).iterator();

               while(i$.hasNext()) {
                  TriggerWrapper tw = (TriggerWrapper)i$.next();
                  OperableTrigger trig = tw.getTrigger();
                  boolean removed = this.timeTriggers.remove(tw);
                  trig.updateWithNewCalendar(calendar, this.getMisfireThreshold());
                  if (removed) {
                     this.timeTriggers.add(tw);
                  }
               }
            }

         }
      }
   }

   public boolean removeCalendar(String calName) throws JobPersistenceException {
      int numRefs = 0;
      synchronized(this.lock) {
         Iterator i$ = this.triggers.iterator();

         while(i$.hasNext()) {
            TriggerWrapper trigger = (TriggerWrapper)i$.next();
            OperableTrigger trigg = trigger.trigger;
            if (trigg.getCalendarName() != null && trigg.getCalendarName().equals(calName)) {
               ++numRefs;
            }
         }
      }

      if (numRefs > 0) {
         throw new JobPersistenceException("Calender cannot be removed if it referenced by a Trigger!");
      } else {
         return this.calendarsByName.remove(calName) != null;
      }
   }

   public Calendar retrieveCalendar(String calName) {
      synchronized(this.lock) {
         Calendar cal = (Calendar)this.calendarsByName.get(calName);
         return cal != null ? (Calendar)cal.clone() : null;
      }
   }

   public int getNumberOfJobs() {
      synchronized(this.lock) {
         return this.jobsByKey.size();
      }
   }

   public int getNumberOfTriggers() {
      synchronized(this.lock) {
         return this.triggers.size();
      }
   }

   public int getNumberOfCalendars() {
      synchronized(this.lock) {
         return this.calendarsByName.size();
      }
   }

   public Set<JobKey> getJobKeys(GroupMatcher<JobKey> matcher) {
      Set<JobKey> outList = null;
      synchronized(this.lock) {
         StringMatcher.StringOperatorName operator = matcher.getCompareWithOperator();
         String compareToValue = matcher.getCompareToValue();
         Iterator i$;
         switch(operator) {
         case EQUALS:
            HashMap<JobKey, JobWrapper> grpMap = (HashMap)this.jobsByGroup.get(compareToValue);
            if (grpMap == null) {
               return (Set)(outList == null ? Collections.emptySet() : outList);
            }

            outList = new HashSet();
            i$ = grpMap.values().iterator();

            while(i$.hasNext()) {
               JobWrapper jw = (JobWrapper)i$.next();
               if (jw != null) {
                  outList.add(jw.jobDetail.getKey());
               }
            }

            return (Set)(outList == null ? Collections.emptySet() : outList);
         default:
            i$ = this.jobsByGroup.entrySet().iterator();

            while(true) {
               Entry entry;
               do {
                  do {
                     if (!i$.hasNext()) {
                        return (Set)(outList == null ? Collections.emptySet() : outList);
                     }

                     entry = (Entry)i$.next();
                  } while(!operator.evaluate((String)entry.getKey(), compareToValue));
               } while(entry.getValue() == null);

               if (outList == null) {
                  outList = new HashSet();
               }

               Iterator i$ = ((HashMap)entry.getValue()).values().iterator();

               while(i$.hasNext()) {
                  JobWrapper jobWrapper = (JobWrapper)i$.next();
                  if (jobWrapper != null) {
                     outList.add(jobWrapper.jobDetail.getKey());
                  }
               }
            }
         }
      }
   }

   public List<String> getCalendarNames() {
      synchronized(this.lock) {
         return new LinkedList(this.calendarsByName.keySet());
      }
   }

   public Set<TriggerKey> getTriggerKeys(GroupMatcher<TriggerKey> matcher) {
      Set<TriggerKey> outList = null;
      synchronized(this.lock) {
         StringMatcher.StringOperatorName operator = matcher.getCompareWithOperator();
         String compareToValue = matcher.getCompareToValue();
         Iterator i$;
         switch(operator) {
         case EQUALS:
            HashMap<TriggerKey, TriggerWrapper> grpMap = (HashMap)this.triggersByGroup.get(compareToValue);
            if (grpMap == null) {
               return (Set)(outList == null ? Collections.emptySet() : outList);
            }

            outList = new HashSet();
            i$ = grpMap.values().iterator();

            while(i$.hasNext()) {
               TriggerWrapper tw = (TriggerWrapper)i$.next();
               if (tw != null) {
                  outList.add(tw.trigger.getKey());
               }
            }

            return (Set)(outList == null ? Collections.emptySet() : outList);
         default:
            i$ = this.triggersByGroup.entrySet().iterator();

            while(true) {
               Entry entry;
               do {
                  do {
                     if (!i$.hasNext()) {
                        return (Set)(outList == null ? Collections.emptySet() : outList);
                     }

                     entry = (Entry)i$.next();
                  } while(!operator.evaluate((String)entry.getKey(), compareToValue));
               } while(entry.getValue() == null);

               if (outList == null) {
                  outList = new HashSet();
               }

               Iterator i$ = ((HashMap)entry.getValue()).values().iterator();

               while(i$.hasNext()) {
                  TriggerWrapper triggerWrapper = (TriggerWrapper)i$.next();
                  if (triggerWrapper != null) {
                     outList.add(triggerWrapper.trigger.getKey());
                  }
               }
            }
         }
      }
   }

   public List<String> getJobGroupNames() {
      synchronized(this.lock) {
         List<String> outList = new LinkedList(this.jobsByGroup.keySet());
         return outList;
      }
   }

   public List<String> getTriggerGroupNames() {
      synchronized(this.lock) {
         LinkedList<String> outList = new LinkedList(this.triggersByGroup.keySet());
         return outList;
      }
   }

   public List<OperableTrigger> getTriggersForJob(JobKey jobKey) {
      ArrayList<OperableTrigger> trigList = new ArrayList();
      synchronized(this.lock) {
         Iterator i$ = this.triggers.iterator();

         while(i$.hasNext()) {
            TriggerWrapper tw = (TriggerWrapper)i$.next();
            if (tw.jobKey.equals(jobKey)) {
               trigList.add((OperableTrigger)tw.trigger.clone());
            }
         }

         return trigList;
      }
   }

   protected ArrayList<TriggerWrapper> getTriggerWrappersForJob(JobKey jobKey) {
      ArrayList<TriggerWrapper> trigList = new ArrayList();
      synchronized(this.lock) {
         Iterator i$ = this.triggers.iterator();

         while(i$.hasNext()) {
            TriggerWrapper trigger = (TriggerWrapper)i$.next();
            if (trigger.jobKey.equals(jobKey)) {
               trigList.add(trigger);
            }
         }

         return trigList;
      }
   }

   protected ArrayList<TriggerWrapper> getTriggerWrappersForCalendar(String calName) {
      ArrayList<TriggerWrapper> trigList = new ArrayList();
      synchronized(this.lock) {
         Iterator i$ = this.triggers.iterator();

         while(i$.hasNext()) {
            TriggerWrapper tw = (TriggerWrapper)i$.next();
            String tcalName = tw.getTrigger().getCalendarName();
            if (tcalName != null && tcalName.equals(calName)) {
               trigList.add(tw);
            }
         }

         return trigList;
      }
   }

   public void pauseTrigger(TriggerKey triggerKey) {
      synchronized(this.lock) {
         TriggerWrapper tw = (TriggerWrapper)this.triggersByKey.get(triggerKey);
         if (tw != null && tw.trigger != null) {
            if (tw.state != 3) {
               if (tw.state == 5) {
                  tw.state = 6;
               } else {
                  tw.state = 4;
               }

               this.timeTriggers.remove(tw);
            }
         }
      }
   }

   public List<String> pauseTriggers(GroupMatcher<TriggerKey> matcher) {
      synchronized(this.lock) {
         List<String> pausedGroups = new LinkedList();
         StringMatcher.StringOperatorName operator = matcher.getCompareWithOperator();
         Iterator i$;
         String pausedGroup;
         switch(operator) {
         case EQUALS:
            if (this.pausedTriggerGroups.add(matcher.getCompareToValue())) {
               pausedGroups.add(matcher.getCompareToValue());
            }
            break;
         default:
            i$ = this.triggersByGroup.keySet().iterator();

            while(i$.hasNext()) {
               pausedGroup = (String)i$.next();
               if (operator.evaluate(pausedGroup, matcher.getCompareToValue()) && this.pausedTriggerGroups.add(matcher.getCompareToValue())) {
                  pausedGroups.add(pausedGroup);
               }
            }
         }

         i$ = pausedGroups.iterator();

         while(i$.hasNext()) {
            pausedGroup = (String)i$.next();
            Set<TriggerKey> keys = this.getTriggerKeys(GroupMatcher.triggerGroupEquals(pausedGroup));
            Iterator i$ = keys.iterator();

            while(i$.hasNext()) {
               TriggerKey key = (TriggerKey)i$.next();
               this.pauseTrigger(key);
            }
         }

         return pausedGroups;
      }
   }

   public void pauseJob(JobKey jobKey) {
      synchronized(this.lock) {
         List<OperableTrigger> triggersOfJob = this.getTriggersForJob(jobKey);
         Iterator i$ = triggersOfJob.iterator();

         while(i$.hasNext()) {
            OperableTrigger trigger = (OperableTrigger)i$.next();
            this.pauseTrigger(trigger.getKey());
         }

      }
   }

   public List<String> pauseJobs(GroupMatcher<JobKey> matcher) {
      List<String> pausedGroups = new LinkedList();
      synchronized(this.lock) {
         StringMatcher.StringOperatorName operator = matcher.getCompareWithOperator();
         Iterator i$;
         String groupName;
         switch(operator) {
         case EQUALS:
            if (this.pausedJobGroups.add(matcher.getCompareToValue())) {
               pausedGroups.add(matcher.getCompareToValue());
            }
            break;
         default:
            i$ = this.jobsByGroup.keySet().iterator();

            while(i$.hasNext()) {
               groupName = (String)i$.next();
               if (operator.evaluate(groupName, matcher.getCompareToValue()) && this.pausedJobGroups.add(groupName)) {
                  pausedGroups.add(groupName);
               }
            }
         }

         i$ = pausedGroups.iterator();

         while(i$.hasNext()) {
            groupName = (String)i$.next();
            Iterator i$ = this.getJobKeys(GroupMatcher.jobGroupEquals(groupName)).iterator();

            while(i$.hasNext()) {
               JobKey jobKey = (JobKey)i$.next();
               List<OperableTrigger> triggersOfJob = this.getTriggersForJob(jobKey);
               Iterator i$ = triggersOfJob.iterator();

               while(i$.hasNext()) {
                  OperableTrigger trigger = (OperableTrigger)i$.next();
                  this.pauseTrigger(trigger.getKey());
               }
            }
         }

         return pausedGroups;
      }
   }

   public void resumeTrigger(TriggerKey triggerKey) {
      synchronized(this.lock) {
         TriggerWrapper tw = (TriggerWrapper)this.triggersByKey.get(triggerKey);
         if (tw != null && tw.trigger != null) {
            OperableTrigger trig = tw.getTrigger();
            if (tw.state == 4 || tw.state == 6) {
               if (this.blockedJobs.contains(trig.getJobKey())) {
                  tw.state = 5;
               } else {
                  tw.state = 0;
               }

               this.applyMisfire(tw);
               if (tw.state == 0) {
                  this.timeTriggers.add(tw);
               }

            }
         }
      }
   }

   public List<String> resumeTriggers(GroupMatcher<TriggerKey> matcher) {
      Set<String> groups = new HashSet();
      synchronized(this.lock) {
         Set<TriggerKey> keys = this.getTriggerKeys(matcher);
         Iterator i$ = keys.iterator();

         while(true) {
            if (!i$.hasNext()) {
               i$ = groups.iterator();

               while(i$.hasNext()) {
                  String group = (String)i$.next();
                  this.pausedTriggerGroups.remove(group);
               }
               break;
            }

            TriggerKey triggerKey = (TriggerKey)i$.next();
            groups.add(triggerKey.getGroup());
            if (this.triggersByKey.get(triggerKey) != null) {
               String jobGroup = ((TriggerWrapper)this.triggersByKey.get(triggerKey)).jobKey.getGroup();
               if (this.pausedJobGroups.contains(jobGroup)) {
                  continue;
               }
            }

            this.resumeTrigger(triggerKey);
         }
      }

      return new ArrayList(groups);
   }

   public void resumeJob(JobKey jobKey) {
      synchronized(this.lock) {
         List<OperableTrigger> triggersOfJob = this.getTriggersForJob(jobKey);
         Iterator i$ = triggersOfJob.iterator();

         while(i$.hasNext()) {
            OperableTrigger trigger = (OperableTrigger)i$.next();
            this.resumeTrigger(trigger.getKey());
         }

      }
   }

   public Collection<String> resumeJobs(GroupMatcher<JobKey> matcher) {
      Set<String> resumedGroups = new HashSet();
      synchronized(this.lock) {
         Set<JobKey> keys = this.getJobKeys(matcher);
         Iterator i$ = this.pausedJobGroups.iterator();

         String resumedGroup;
         while(i$.hasNext()) {
            resumedGroup = (String)i$.next();
            if (matcher.getCompareWithOperator().evaluate(resumedGroup, matcher.getCompareToValue())) {
               resumedGroups.add(resumedGroup);
            }
         }

         i$ = resumedGroups.iterator();

         while(i$.hasNext()) {
            resumedGroup = (String)i$.next();
            this.pausedJobGroups.remove(resumedGroup);
         }

         i$ = keys.iterator();

         while(i$.hasNext()) {
            JobKey key = (JobKey)i$.next();
            List<OperableTrigger> triggersOfJob = this.getTriggersForJob(key);
            Iterator i$ = triggersOfJob.iterator();

            while(i$.hasNext()) {
               OperableTrigger trigger = (OperableTrigger)i$.next();
               this.resumeTrigger(trigger.getKey());
            }
         }

         return resumedGroups;
      }
   }

   public void pauseAll() {
      synchronized(this.lock) {
         List<String> names = this.getTriggerGroupNames();
         Iterator i$ = names.iterator();

         while(i$.hasNext()) {
            String name = (String)i$.next();
            this.pauseTriggers(GroupMatcher.triggerGroupEquals(name));
         }

      }
   }

   public void resumeAll() {
      synchronized(this.lock) {
         this.pausedJobGroups.clear();
         this.resumeTriggers(GroupMatcher.anyTriggerGroup());
      }
   }

   protected boolean applyMisfire(TriggerWrapper tw) {
      long misfireTime = System.currentTimeMillis();
      if (this.getMisfireThreshold() > 0L) {
         misfireTime -= this.getMisfireThreshold();
      }

      Date tnft = tw.trigger.getNextFireTime();
      if (tnft != null && tnft.getTime() <= misfireTime && tw.trigger.getMisfireInstruction() != -1) {
         Calendar cal = null;
         if (tw.trigger.getCalendarName() != null) {
            cal = this.retrieveCalendar(tw.trigger.getCalendarName());
         }

         this.signaler.notifyTriggerListenersMisfired((OperableTrigger)tw.trigger.clone());
         tw.trigger.updateAfterMisfire(cal);
         if (tw.trigger.getNextFireTime() == null) {
            tw.state = 3;
            this.signaler.notifySchedulerListenersFinalized(tw.trigger);
            synchronized(this.lock) {
               this.timeTriggers.remove(tw);
            }
         } else if (tnft.equals(tw.trigger.getNextFireTime())) {
            return false;
         }

         return true;
      } else {
         return false;
      }
   }

   protected String getFiredTriggerRecordId() {
      return String.valueOf(ftrCtr.incrementAndGet());
   }

   public List<OperableTrigger> acquireNextTriggers(long noLaterThan, int maxCount, long timeWindow) {
      synchronized(this.lock) {
         List<OperableTrigger> result = new ArrayList();
         Set<JobKey> acquiredJobKeysForNoConcurrentExec = new HashSet();
         Set<TriggerWrapper> excludedTriggers = new HashSet();
         long firstAcquiredTriggerFireTime = 0L;
         if (this.timeTriggers.size() == 0) {
            return result;
         } else {
            while(true) {
               TriggerWrapper tw;
               try {
                  tw = (TriggerWrapper)this.timeTriggers.first();
                  if (tw == null) {
                     break;
                  }

                  this.timeTriggers.remove(tw);
               } catch (NoSuchElementException var17) {
                  break;
               }

               if (tw.trigger.getNextFireTime() != null) {
                  if (this.applyMisfire(tw)) {
                     if (tw.trigger.getNextFireTime() != null) {
                        this.timeTriggers.add(tw);
                     }
                  } else {
                     if (tw.getTrigger().getNextFireTime().getTime() > noLaterThan + timeWindow) {
                        this.timeTriggers.add(tw);
                        break;
                     }

                     JobKey jobKey = tw.trigger.getJobKey();
                     JobDetail job = ((JobWrapper)this.jobsByKey.get(tw.trigger.getJobKey())).jobDetail;
                     if (job.isConcurrentExectionDisallowed()) {
                        if (acquiredJobKeysForNoConcurrentExec.contains(jobKey)) {
                           excludedTriggers.add(tw);
                           continue;
                        }

                        acquiredJobKeysForNoConcurrentExec.add(jobKey);
                     }

                     tw.state = 1;
                     tw.trigger.setFireInstanceId(this.getFiredTriggerRecordId());
                     OperableTrigger trig = (OperableTrigger)tw.trigger.clone();
                     result.add(trig);
                     if (firstAcquiredTriggerFireTime == 0L) {
                        firstAcquiredTriggerFireTime = tw.trigger.getNextFireTime().getTime();
                     }

                     if (result.size() == maxCount) {
                        break;
                     }
                  }
               }
            }

            if (excludedTriggers.size() > 0) {
               this.timeTriggers.addAll(excludedTriggers);
            }

            return result;
         }
      }
   }

   public void releaseAcquiredTrigger(OperableTrigger trigger) {
      synchronized(this.lock) {
         TriggerWrapper tw = (TriggerWrapper)this.triggersByKey.get(trigger.getKey());
         if (tw != null && tw.state == 1) {
            tw.state = 0;
            this.timeTriggers.add(tw);
         }

      }
   }

   public List<TriggerFiredResult> triggersFired(List<OperableTrigger> firedTriggers) {
      synchronized(this.lock) {
         List<TriggerFiredResult> results = new ArrayList();
         Iterator i$ = firedTriggers.iterator();

         while(true) {
            OperableTrigger trigger;
            TriggerWrapper tw;
            Calendar cal;
            do {
               do {
                  do {
                     do {
                        if (!i$.hasNext()) {
                           return results;
                        }

                        trigger = (OperableTrigger)i$.next();
                        tw = (TriggerWrapper)this.triggersByKey.get(trigger.getKey());
                     } while(tw == null);
                  } while(tw.trigger == null);
               } while(tw.state != 1);

               cal = null;
               if (tw.trigger.getCalendarName() == null) {
                  break;
               }

               cal = this.retrieveCalendar(tw.trigger.getCalendarName());
            } while(cal == null);

            Date prevFireTime = trigger.getPreviousFireTime();
            this.timeTriggers.remove(tw);
            tw.trigger.triggered(cal);
            trigger.triggered(cal);
            tw.state = 0;
            TriggerFiredBundle bndle = new TriggerFiredBundle(this.retrieveJob(tw.jobKey), trigger, cal, false, new Date(), trigger.getPreviousFireTime(), prevFireTime, trigger.getNextFireTime());
            JobDetail job = bndle.getJobDetail();
            if (!job.isConcurrentExectionDisallowed()) {
               if (tw.trigger.getNextFireTime() != null) {
                  synchronized(this.lock) {
                     this.timeTriggers.add(tw);
                  }
               }
            } else {
               ArrayList<TriggerWrapper> trigs = this.getTriggerWrappersForJob(job.getKey());

               TriggerWrapper ttw;
               for(Iterator i$ = trigs.iterator(); i$.hasNext(); this.timeTriggers.remove(ttw)) {
                  ttw = (TriggerWrapper)i$.next();
                  if (ttw.state == 0) {
                     ttw.state = 5;
                  }

                  if (ttw.state == 4) {
                     ttw.state = 6;
                  }
               }

               this.blockedJobs.add(job.getKey());
            }

            results.add(new TriggerFiredResult(bndle));
         }
      }
   }

   public void triggeredJobComplete(OperableTrigger trigger, JobDetail jobDetail, Trigger.CompletedExecutionInstruction triggerInstCode) {
      synchronized(this.lock) {
         JobWrapper jw = (JobWrapper)this.jobsByKey.get(jobDetail.getKey());
         TriggerWrapper tw = (TriggerWrapper)this.triggersByKey.get(trigger.getKey());
         if (jw != null) {
            JobDetail jd = jw.jobDetail;
            if (jd.isPersistJobDataAfterExecution()) {
               JobDataMap newData = jobDetail.getJobDataMap();
               if (newData != null) {
                  newData = (JobDataMap)newData.clone();
                  newData.clearDirtyFlag();
               }

               jd = jd.getJobBuilder().setJobData(newData).build();
               jw.jobDetail = jd;
            }

            if (jd.isConcurrentExectionDisallowed()) {
               this.blockedJobs.remove(jd.getKey());
               ArrayList<TriggerWrapper> trigs = this.getTriggerWrappersForJob(jd.getKey());
               Iterator i$ = trigs.iterator();

               while(i$.hasNext()) {
                  TriggerWrapper ttw = (TriggerWrapper)i$.next();
                  if (ttw.state == 5) {
                     ttw.state = 0;
                     this.timeTriggers.add(ttw);
                  }

                  if (ttw.state == 6) {
                     ttw.state = 4;
                  }
               }

               this.signaler.signalSchedulingChange(0L);
            }
         } else {
            this.blockedJobs.remove(jobDetail.getKey());
         }

         if (tw != null) {
            if (triggerInstCode == Trigger.CompletedExecutionInstruction.DELETE_TRIGGER) {
               if (trigger.getNextFireTime() == null) {
                  if (tw.getTrigger().getNextFireTime() == null) {
                     this.removeTrigger(trigger.getKey());
                  }
               } else {
                  this.removeTrigger(trigger.getKey());
                  this.signaler.signalSchedulingChange(0L);
               }
            } else if (triggerInstCode == Trigger.CompletedExecutionInstruction.SET_TRIGGER_COMPLETE) {
               tw.state = 3;
               this.timeTriggers.remove(tw);
               this.signaler.signalSchedulingChange(0L);
            } else if (triggerInstCode == Trigger.CompletedExecutionInstruction.SET_TRIGGER_ERROR) {
               this.getLog().info("Trigger " + trigger.getKey() + " set to ERROR state.");
               tw.state = 7;
               this.signaler.signalSchedulingChange(0L);
            } else if (triggerInstCode == Trigger.CompletedExecutionInstruction.SET_ALL_JOB_TRIGGERS_ERROR) {
               this.getLog().info("All triggers of Job " + trigger.getJobKey() + " set to ERROR state.");
               this.setAllTriggersOfJobToState(trigger.getJobKey(), 7);
               this.signaler.signalSchedulingChange(0L);
            } else if (triggerInstCode == Trigger.CompletedExecutionInstruction.SET_ALL_JOB_TRIGGERS_COMPLETE) {
               this.setAllTriggersOfJobToState(trigger.getJobKey(), 3);
               this.signaler.signalSchedulingChange(0L);
            }
         }

      }
   }

   protected void setAllTriggersOfJobToState(JobKey jobKey, int state) {
      ArrayList<TriggerWrapper> tws = this.getTriggerWrappersForJob(jobKey);
      Iterator i$ = tws.iterator();

      while(i$.hasNext()) {
         TriggerWrapper tw = (TriggerWrapper)i$.next();
         tw.state = state;
         if (state != 0) {
            this.timeTriggers.remove(tw);
         }
      }

   }

   protected String peekTriggers() {
      StringBuilder str = new StringBuilder();
      Iterator i$;
      TriggerWrapper timeTrigger;
      synchronized(this.lock) {
         i$ = this.triggersByKey.values().iterator();

         while(true) {
            if (!i$.hasNext()) {
               break;
            }

            timeTrigger = (TriggerWrapper)i$.next();
            str.append(timeTrigger.trigger.getKey().getName());
            str.append("/");
         }
      }

      str.append(" | ");
      synchronized(this.lock) {
         i$ = this.timeTriggers.iterator();

         while(i$.hasNext()) {
            timeTrigger = (TriggerWrapper)i$.next();
            str.append(timeTrigger.trigger.getKey().getName());
            str.append("->");
         }

         return str.toString();
      }
   }

   public Set<String> getPausedTriggerGroups() throws JobPersistenceException {
      HashSet<String> set = new HashSet();
      set.addAll(this.pausedTriggerGroups);
      return set;
   }

   public void setInstanceId(String schedInstId) {
   }

   public void setInstanceName(String schedName) {
   }

   public void setThreadPoolSize(int poolSize) {
   }

   public long getEstimatedTimeToReleaseAndAcquireTrigger() {
      return 5L;
   }

   public boolean isClustered() {
      return false;
   }
}
