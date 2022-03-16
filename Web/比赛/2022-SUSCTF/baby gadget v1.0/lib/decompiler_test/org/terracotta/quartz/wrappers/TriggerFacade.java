package org.terracotta.quartz.wrappers;

import java.util.ArrayList;
import java.util.Collection;
import java.util.Iterator;
import java.util.List;
import java.util.Set;
import org.quartz.JobKey;
import org.quartz.TriggerKey;
import org.terracotta.quartz.collections.ToolkitDSHolder;
import org.terracotta.toolkit.store.ToolkitStore;

public class TriggerFacade {
   private final ToolkitStore<TriggerKey, TriggerWrapper> triggersByFQN;
   private final Set<String> allTriggersGroupNames;
   private final Set<String> pausedTriggerGroupNames;
   private final ToolkitStore<String, FiredTrigger> firedTriggers;

   public TriggerFacade(ToolkitDSHolder toolkitDSHolder) {
      this.triggersByFQN = toolkitDSHolder.getOrCreateTriggersMap();
      this.allTriggersGroupNames = toolkitDSHolder.getOrCreateAllTriggersGroupsSet();
      this.pausedTriggerGroupNames = toolkitDSHolder.getOrCreatePausedTriggerGroupsSet();
      this.firedTriggers = toolkitDSHolder.getOrCreateFiredTriggersMap();
   }

   public TriggerWrapper get(TriggerKey key) {
      return (TriggerWrapper)this.triggersByFQN.get(key);
   }

   public boolean containsKey(TriggerKey key) {
      return this.triggersByFQN.containsKey(key);
   }

   public void put(TriggerKey key, TriggerWrapper value) {
      this.triggersByFQN.putNoReturn(key, value);
   }

   public TriggerWrapper remove(TriggerKey key) {
      return (TriggerWrapper)this.triggersByFQN.remove(key);
   }

   public FiredTrigger getFiredTrigger(String key) {
      return (FiredTrigger)this.firedTriggers.get(key);
   }

   public boolean containsFiredTrigger(String key) {
      return this.firedTriggers.containsKey(key);
   }

   public void putFiredTrigger(String key, FiredTrigger value) {
      this.firedTriggers.putNoReturn(key, value);
   }

   public FiredTrigger removeFiredTrigger(String key) {
      return (FiredTrigger)this.firedTriggers.remove(key);
   }

   public boolean addGroup(String name) {
      return this.allTriggersGroupNames.add(name);
   }

   public boolean hasGroup(String name) {
      return this.allTriggersGroupNames.contains(name);
   }

   public boolean removeGroup(String name) {
      return this.allTriggersGroupNames.remove(name);
   }

   public boolean addPausedGroup(String name) {
      return this.pausedTriggerGroupNames.add(name);
   }

   public boolean pausedGroupsContain(String name) {
      return this.pausedTriggerGroupNames.contains(name);
   }

   public boolean removePausedGroup(String name) {
      return this.pausedTriggerGroupNames.remove(name);
   }

   public Set<String> allTriggersGroupNames() {
      return this.allTriggersGroupNames;
   }

   public Set<String> allPausedTriggersGroupNames() {
      return this.pausedTriggerGroupNames;
   }

   public Set<TriggerKey> allTriggerKeys() {
      return this.triggersByFQN.keySet();
   }

   public Collection<FiredTrigger> allFiredTriggers() {
      return this.firedTriggers.values();
   }

   public int numberOfTriggers() {
      return this.triggersByFQN.size();
   }

   public List<TriggerWrapper> getTriggerWrappersForJob(JobKey key) {
      List<TriggerWrapper> trigList = new ArrayList();
      Iterator i$ = this.triggersByFQN.keySet().iterator();

      while(i$.hasNext()) {
         TriggerKey triggerKey = (TriggerKey)i$.next();
         TriggerWrapper tw = (TriggerWrapper)this.triggersByFQN.get(triggerKey);
         if (tw.getJobKey().equals(key)) {
            trigList.add(tw);
         }
      }

      return trigList;
   }

   public List<TriggerWrapper> getTriggerWrappersForCalendar(String calName) {
      List<TriggerWrapper> trigList = new ArrayList();
      Iterator i$ = this.triggersByFQN.keySet().iterator();

      while(i$.hasNext()) {
         TriggerKey triggerKey = (TriggerKey)i$.next();
         TriggerWrapper tw = (TriggerWrapper)this.triggersByFQN.get(triggerKey);
         String tcalName = tw.getCalendarName();
         if (tcalName != null && tcalName.equals(calName)) {
            trigList.add(tw);
         }
      }

      return trigList;
   }

   public void removeAllPausedGroups(Collection<String> groups) {
      this.pausedTriggerGroupNames.removeAll(groups);
   }
}
