package org.terracotta.quartz.collections;

import java.util.HashMap;
import java.util.Map;
import java.util.Set;
import java.util.concurrent.atomic.AtomicReference;
import org.quartz.Calendar;
import org.quartz.JobKey;
import org.quartz.TriggerKey;
import org.terracotta.quartz.wrappers.FiredTrigger;
import org.terracotta.quartz.wrappers.JobWrapper;
import org.terracotta.quartz.wrappers.TriggerWrapper;
import org.terracotta.toolkit.Toolkit;
import org.terracotta.toolkit.builder.ToolkitStoreConfigBuilder;
import org.terracotta.toolkit.collections.ToolkitSet;
import org.terracotta.toolkit.concurrent.locks.ToolkitLock;
import org.terracotta.toolkit.internal.ToolkitInternal;
import org.terracotta.toolkit.internal.concurrent.locks.ToolkitLockTypeInternal;
import org.terracotta.toolkit.store.ToolkitStore;
import org.terracotta.toolkit.store.ToolkitConfigFields.Consistency;

public class ToolkitDSHolder {
   private static final String JOBS_MAP_PREFIX = "_tc_quartz_jobs";
   private static final String ALL_JOBS_GROUP_NAMES_SET_PREFIX = "_tc_quartz_grp_names";
   private static final String PAUSED_GROUPS_SET_PREFIX = "_tc_quartz_grp_paused_names";
   private static final String BLOCKED_JOBS_SET_PREFIX = "_tc_quartz_blocked_jobs";
   private static final String JOBS_GROUP_MAP_PREFIX = "_tc_quartz_grp_jobs_";
   private static final String TRIGGERS_MAP_PREFIX = "_tc_quartz_triggers";
   private static final String TRIGGERS_GROUP_MAP_PREFIX = "_tc_quartz_grp_triggers_";
   private static final String ALL_TRIGGERS_GROUP_NAMES_SET_PREFIX = "_tc_quartz_grp_names_triggers";
   private static final String PAUSED_TRIGGER_GROUPS_SET_PREFIX = "_tc_quartz_grp_paused_trogger_names";
   private static final String TIME_TRIGGER_SORTED_SET_PREFIX = "_tc_time_trigger_sorted_set";
   private static final String FIRED_TRIGGER_MAP_PREFIX = "_tc_quartz_fired_trigger";
   private static final String CALENDAR_WRAPPER_MAP_PREFIX = "_tc_quartz_calendar_wrapper";
   private static final String SINGLE_LOCK_NAME_PREFIX = "_tc_quartz_single_lock";
   private static final String DELIMETER = "|";
   private final String jobStoreName;
   protected final Toolkit toolkit;
   private final AtomicReference<SerializedToolkitStore<JobKey, JobWrapper>> jobsMapReference = new AtomicReference();
   private final AtomicReference<SerializedToolkitStore<TriggerKey, TriggerWrapper>> triggersMapReference = new AtomicReference();
   private final AtomicReference<ToolkitSet<String>> allGroupsReference = new AtomicReference();
   private final AtomicReference<ToolkitSet<String>> allTriggersGroupsReference = new AtomicReference();
   private final AtomicReference<ToolkitSet<String>> pausedGroupsReference = new AtomicReference();
   private final AtomicReference<ToolkitSet<JobKey>> blockedJobsReference = new AtomicReference();
   private final Map<String, ToolkitSet<String>> jobsGroupSet = new HashMap();
   private final Map<String, ToolkitSet<String>> triggersGroupSet = new HashMap();
   private final AtomicReference<ToolkitSet<String>> pausedTriggerGroupsReference = new AtomicReference();
   private final AtomicReference<ToolkitStore<String, FiredTrigger>> firedTriggersMapReference = new AtomicReference();
   private final AtomicReference<ToolkitStore<String, Calendar>> calendarWrapperMapReference = new AtomicReference();
   private final AtomicReference<TimeTriggerSet> timeTriggerSetReference = new AtomicReference();
   private final Map<String, ToolkitStore<?, ?>> toolkitMaps = new HashMap();

   public ToolkitDSHolder(String jobStoreName, Toolkit toolkit) {
      this.jobStoreName = jobStoreName;
      this.toolkit = toolkit;
   }

   protected final String generateName(String prefix) {
      return prefix + "|" + this.jobStoreName;
   }

   public SerializedToolkitStore<JobKey, JobWrapper> getOrCreateJobsMap() {
      String jobsMapName = this.generateName("_tc_quartz_jobs");
      SerializedToolkitStore<JobKey, JobWrapper> temp = new SerializedToolkitStore(this.createStore(jobsMapName));
      this.jobsMapReference.compareAndSet((Object)null, temp);
      return (SerializedToolkitStore)this.jobsMapReference.get();
   }

   protected ToolkitStore<?, ?> toolkitMap(String nameOfMap) {
      ToolkitStore<?, ?> map = (ToolkitStore)this.toolkitMaps.get(nameOfMap);
      if (map != null && !map.isDestroyed()) {
         return map;
      } else {
         map = this.createStore(nameOfMap);
         this.toolkitMaps.put(nameOfMap, map);
         return map;
      }
   }

   private ToolkitStore createStore(String nameOfMap) {
      ToolkitStoreConfigBuilder builder = new ToolkitStoreConfigBuilder();
      return this.toolkit.getStore(nameOfMap, builder.consistency(Consistency.STRONG).concurrency(1).build(), (Class)null);
   }

   public SerializedToolkitStore<TriggerKey, TriggerWrapper> getOrCreateTriggersMap() {
      String triggersMapName = this.generateName("_tc_quartz_triggers");
      SerializedToolkitStore<TriggerKey, TriggerWrapper> temp = new SerializedToolkitStore(this.createStore(triggersMapName));
      this.triggersMapReference.compareAndSet((Object)null, temp);
      return (SerializedToolkitStore)this.triggersMapReference.get();
   }

   public ToolkitStore<String, FiredTrigger> getOrCreateFiredTriggersMap() {
      String firedTriggerMapName = this.generateName("_tc_quartz_fired_trigger");
      ToolkitStore<String, FiredTrigger> temp = this.createStore(firedTriggerMapName);
      this.firedTriggersMapReference.compareAndSet((Object)null, temp);
      return (ToolkitStore)this.firedTriggersMapReference.get();
   }

   public ToolkitStore<String, Calendar> getOrCreateCalendarWrapperMap() {
      String calendarWrapperName = this.generateName("_tc_quartz_calendar_wrapper");
      ToolkitStore<String, Calendar> temp = this.createStore(calendarWrapperName);
      this.calendarWrapperMapReference.compareAndSet((Object)null, temp);
      return (ToolkitStore)this.calendarWrapperMapReference.get();
   }

   public Set<String> getOrCreateAllGroupsSet() {
      String allGrpSetNames = this.generateName("_tc_quartz_grp_names");
      ToolkitSet<String> temp = this.toolkit.getSet(allGrpSetNames, String.class);
      this.allGroupsReference.compareAndSet((Object)null, temp);
      return (Set)this.allGroupsReference.get();
   }

   public Set<JobKey> getOrCreateBlockedJobsSet() {
      String blockedJobsSetName = this.generateName("_tc_quartz_blocked_jobs");
      ToolkitSet<JobKey> temp = this.toolkit.getSet(blockedJobsSetName, JobKey.class);
      this.blockedJobsReference.compareAndSet((Object)null, temp);
      return (Set)this.blockedJobsReference.get();
   }

   public Set<String> getOrCreatePausedGroupsSet() {
      String pausedGrpsSetName = this.generateName("_tc_quartz_grp_paused_names");
      ToolkitSet<String> temp = this.toolkit.getSet(pausedGrpsSetName, String.class);
      this.pausedGroupsReference.compareAndSet((Object)null, temp);
      return (Set)this.pausedGroupsReference.get();
   }

   public Set<String> getOrCreatePausedTriggerGroupsSet() {
      String pausedGrpsSetName = this.generateName("_tc_quartz_grp_paused_trogger_names");
      ToolkitSet<String> temp = this.toolkit.getSet(pausedGrpsSetName, String.class);
      this.pausedTriggerGroupsReference.compareAndSet((Object)null, temp);
      return (Set)this.pausedTriggerGroupsReference.get();
   }

   public Set<String> getOrCreateJobsGroupMap(String name) {
      ToolkitSet<String> set = (ToolkitSet)this.jobsGroupSet.get(name);
      if (set != null && !set.isDestroyed()) {
         return set;
      } else {
         String nameForMap = this.generateName("_tc_quartz_grp_jobs_" + name);
         set = this.toolkit.getSet(nameForMap, String.class);
         this.jobsGroupSet.put(name, set);
         return set;
      }
   }

   public void removeJobsGroupMap(String name) {
      ToolkitSet<String> set = (ToolkitSet)this.jobsGroupSet.remove(name);
      if (set != null) {
         set.destroy();
      }

   }

   public Set<String> getOrCreateTriggersGroupMap(String name) {
      ToolkitSet<String> set = (ToolkitSet)this.triggersGroupSet.get(name);
      if (set != null && !set.isDestroyed()) {
         return set;
      } else {
         String nameForMap = this.generateName("_tc_quartz_grp_triggers_" + name);
         set = this.toolkit.getSet(nameForMap, String.class);
         this.triggersGroupSet.put(name, set);
         return set;
      }
   }

   public void removeTriggersGroupMap(String name) {
      ToolkitSet<String> set = (ToolkitSet)this.triggersGroupSet.remove(name);
      if (set != null) {
         set.destroy();
      }

   }

   public Set<String> getOrCreateAllTriggersGroupsSet() {
      String allTriggersGrpsName = this.generateName("_tc_quartz_grp_names_triggers");
      ToolkitSet<String> temp = this.toolkit.getSet(allTriggersGrpsName, String.class);
      this.allTriggersGroupsReference.compareAndSet((Object)null, temp);
      return (Set)this.allTriggersGroupsReference.get();
   }

   public TimeTriggerSet getOrCreateTimeTriggerSet() {
      String triggerSetName = this.generateName("_tc_time_trigger_sorted_set");
      TimeTriggerSet set = new TimeTriggerSet(this.toolkit.getSortedSet(triggerSetName, TimeTrigger.class));
      this.timeTriggerSetReference.compareAndSet((Object)null, set);
      return (TimeTriggerSet)this.timeTriggerSetReference.get();
   }

   public ToolkitLock getLock(ToolkitLockTypeInternal lockType) {
      String lockName = this.generateName("_tc_quartz_single_lock");
      return ((ToolkitInternal)this.toolkit).getLock(lockName, lockType);
   }
}
