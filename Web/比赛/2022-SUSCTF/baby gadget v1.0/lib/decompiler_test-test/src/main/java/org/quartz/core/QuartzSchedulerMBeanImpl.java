package org.quartz.core;

import java.beans.BeanInfo;
import java.beans.IntrospectionException;
import java.beans.Introspector;
import java.beans.MethodDescriptor;
import java.lang.reflect.Field;
import java.lang.reflect.Method;
import java.text.ParseException;
import java.util.ArrayList;
import java.util.Date;
import java.util.HashMap;
import java.util.Iterator;
import java.util.List;
import java.util.Map;
import java.util.Set;
import java.util.Map.Entry;
import java.util.concurrent.atomic.AtomicLong;
import javax.management.ListenerNotFoundException;
import javax.management.MBeanNotificationInfo;
import javax.management.NotCompliantMBeanException;
import javax.management.Notification;
import javax.management.NotificationBroadcasterSupport;
import javax.management.NotificationEmitter;
import javax.management.NotificationFilter;
import javax.management.NotificationListener;
import javax.management.StandardMBean;
import javax.management.openmbean.CompositeData;
import javax.management.openmbean.TabularData;
import org.quartz.JobDataMap;
import org.quartz.JobDetail;
import org.quartz.JobExecutionContext;
import org.quartz.JobExecutionException;
import org.quartz.JobKey;
import org.quartz.JobListener;
import org.quartz.SchedulerException;
import org.quartz.SchedulerListener;
import org.quartz.Trigger;
import org.quartz.TriggerKey;
import org.quartz.core.jmx.JobDetailSupport;
import org.quartz.core.jmx.JobExecutionContextSupport;
import org.quartz.core.jmx.QuartzSchedulerMBean;
import org.quartz.core.jmx.TriggerSupport;
import org.quartz.impl.matchers.GroupMatcher;
import org.quartz.impl.triggers.AbstractTrigger;
import org.quartz.spi.OperableTrigger;

public class QuartzSchedulerMBeanImpl extends StandardMBean implements NotificationEmitter, QuartzSchedulerMBean, JobListener, SchedulerListener {
   private static final MBeanNotificationInfo[] NOTIFICATION_INFO;
   private final QuartzScheduler scheduler;
   private boolean sampledStatisticsEnabled;
   private SampledStatistics sampledStatistics;
   private static final SampledStatistics NULL_SAMPLED_STATISTICS = new NullSampledStatisticsImpl();
   protected final QuartzSchedulerMBeanImpl.Emitter emitter = new QuartzSchedulerMBeanImpl.Emitter();
   protected final AtomicLong sequenceNumber = new AtomicLong();

   protected QuartzSchedulerMBeanImpl(QuartzScheduler scheduler) throws NotCompliantMBeanException {
      super(QuartzSchedulerMBean.class);
      this.scheduler = scheduler;
      this.scheduler.addInternalJobListener(this);
      this.scheduler.addInternalSchedulerListener(this);
      this.sampledStatistics = NULL_SAMPLED_STATISTICS;
      this.sampledStatisticsEnabled = false;
   }

   public TabularData getCurrentlyExecutingJobs() throws Exception {
      try {
         List<JobExecutionContext> currentlyExecutingJobs = this.scheduler.getCurrentlyExecutingJobs();
         return JobExecutionContextSupport.toTabularData(currentlyExecutingJobs);
      } catch (Exception var2) {
         throw this.newPlainException(var2);
      }
   }

   public TabularData getAllJobDetails() throws Exception {
      try {
         List<JobDetail> detailList = new ArrayList();
         Iterator i$ = this.scheduler.getJobGroupNames().iterator();

         while(i$.hasNext()) {
            String jobGroupName = (String)i$.next();
            Iterator i$ = this.scheduler.getJobKeys(GroupMatcher.jobGroupEquals(jobGroupName)).iterator();

            while(i$.hasNext()) {
               JobKey jobKey = (JobKey)i$.next();
               detailList.add(this.scheduler.getJobDetail(jobKey));
            }
         }

         return JobDetailSupport.toTabularData((JobDetail[])detailList.toArray(new JobDetail[detailList.size()]));
      } catch (Exception var6) {
         throw this.newPlainException(var6);
      }
   }

   public List<CompositeData> getAllTriggers() throws Exception {
      try {
         List<Trigger> triggerList = new ArrayList();
         Iterator i$ = this.scheduler.getTriggerGroupNames().iterator();

         while(i$.hasNext()) {
            String triggerGroupName = (String)i$.next();
            Iterator i$ = this.scheduler.getTriggerKeys(GroupMatcher.triggerGroupEquals(triggerGroupName)).iterator();

            while(i$.hasNext()) {
               TriggerKey triggerKey = (TriggerKey)i$.next();
               triggerList.add(this.scheduler.getTrigger(triggerKey));
            }
         }

         return TriggerSupport.toCompositeList(triggerList);
      } catch (Exception var6) {
         throw this.newPlainException(var6);
      }
   }

   public void addJob(CompositeData jobDetail, boolean replace) throws Exception {
      try {
         this.scheduler.addJob(JobDetailSupport.newJobDetail(jobDetail), replace);
      } catch (Exception var4) {
         throw this.newPlainException(var4);
      }
   }

   private static void invokeSetter(Object target, String attribute, Object value) throws Exception {
      String setterName = "set" + Character.toUpperCase(attribute.charAt(0)) + attribute.substring(1);
      Class<?>[] argTypes = new Class[]{value.getClass()};
      Method setter = findMethod(target.getClass(), setterName, argTypes);
      if (setter != null) {
         setter.invoke(target, value);
      } else {
         throw new Exception("Unable to find setter for attribute '" + attribute + "' and value '" + value + "'");
      }
   }

   private static Class<?> getWrapperIfPrimitive(Class<?> c) {
      Class result = c;

      try {
         Field f = c.getField("TYPE");
         f.setAccessible(true);
         result = (Class)f.get((Object)null);
      } catch (Exception var3) {
      }

      return result;
   }

   private static Method findMethod(Class<?> targetType, String methodName, Class<?>[] argTypes) throws IntrospectionException {
      BeanInfo beanInfo = Introspector.getBeanInfo(targetType);
      if (beanInfo != null) {
         MethodDescriptor[] arr$ = beanInfo.getMethodDescriptors();
         int len$ = arr$.length;

         for(int i$ = 0; i$ < len$; ++i$) {
            MethodDescriptor methodDesc = arr$[i$];
            Method method = methodDesc.getMethod();
            Class<?>[] parameterTypes = method.getParameterTypes();
            if (methodName.equals(method.getName()) && argTypes.length == parameterTypes.length) {
               boolean matchedArgTypes = true;

               for(int i = 0; i < argTypes.length; ++i) {
                  if (getWrapperIfPrimitive(argTypes[i]) != parameterTypes[i]) {
                     matchedArgTypes = false;
                     break;
                  }
               }

               if (matchedArgTypes) {
                  return method;
               }
            }
         }
      }

      return null;
   }

   public void scheduleBasicJob(Map<String, Object> jobDetailInfo, Map<String, Object> triggerInfo) throws Exception {
      try {
         JobDetail jobDetail = JobDetailSupport.newJobDetail(jobDetailInfo);
         OperableTrigger trigger = TriggerSupport.newTrigger(triggerInfo);
         this.scheduler.deleteJob(jobDetail.getKey());
         this.scheduler.scheduleJob(jobDetail, trigger);
      } catch (ParseException var5) {
         throw var5;
      } catch (Exception var6) {
         throw this.newPlainException(var6);
      }
   }

   public void scheduleJob(Map<String, Object> abstractJobInfo, Map<String, Object> abstractTriggerInfo) throws Exception {
      try {
         String triggerClassName = (String)abstractTriggerInfo.remove("triggerClass");
         if (triggerClassName == null) {
            throw new IllegalArgumentException("No triggerClass specified");
         } else {
            Class<?> triggerClass = Class.forName(triggerClassName);
            Trigger trigger = (Trigger)triggerClass.newInstance();
            String jobDetailClassName = (String)abstractJobInfo.remove("jobDetailClass");
            if (jobDetailClassName == null) {
               throw new IllegalArgumentException("No jobDetailClass specified");
            } else {
               Class<?> jobDetailClass = Class.forName(jobDetailClassName);
               JobDetail jobDetail = (JobDetail)jobDetailClass.newInstance();
               String jobClassName = (String)abstractJobInfo.remove("jobClass");
               if (jobClassName == null) {
                  throw new IllegalArgumentException("No jobClass specified");
               } else {
                  Class<?> jobClass = Class.forName(jobClassName);
                  abstractJobInfo.put("jobClass", jobClass);

                  Iterator i$;
                  Entry entry;
                  String key;
                  Object value;
                  for(i$ = abstractTriggerInfo.entrySet().iterator(); i$.hasNext(); invokeSetter(trigger, key, value)) {
                     entry = (Entry)i$.next();
                     key = (String)entry.getKey();
                     value = entry.getValue();
                     if ("jobDataMap".equals(key)) {
                        value = new JobDataMap((Map)value);
                     }
                  }

                  for(i$ = abstractJobInfo.entrySet().iterator(); i$.hasNext(); invokeSetter(jobDetail, key, value)) {
                     entry = (Entry)i$.next();
                     key = (String)entry.getKey();
                     value = entry.getValue();
                     if ("jobDataMap".equals(key)) {
                        value = new JobDataMap((Map)value);
                     }
                  }

                  AbstractTrigger<?> at = (AbstractTrigger)trigger;
                  at.setKey(new TriggerKey(at.getName(), at.getGroup()));
                  Date startDate = at.getStartTime();
                  if (startDate == null || startDate.before(new Date())) {
                     at.setStartTime(new Date());
                  }

                  this.scheduler.deleteJob(jobDetail.getKey());
                  this.scheduler.scheduleJob(jobDetail, trigger);
               }
            }
         }
      } catch (Exception var15) {
         throw this.newPlainException(var15);
      }
   }

   public void scheduleJob(String jobName, String jobGroup, Map<String, Object> abstractTriggerInfo) throws Exception {
      try {
         JobKey jobKey = new JobKey(jobName, jobGroup);
         JobDetail jobDetail = this.scheduler.getJobDetail(jobKey);
         if (jobDetail == null) {
            throw new IllegalArgumentException("No such job '" + jobKey + "'");
         } else {
            String triggerClassName = (String)abstractTriggerInfo.remove("triggerClass");
            if (triggerClassName == null) {
               throw new IllegalArgumentException("No triggerClass specified");
            } else {
               Class<?> triggerClass = Class.forName(triggerClassName);
               Trigger trigger = (Trigger)triggerClass.newInstance();

               String key;
               Object value;
               for(Iterator i$ = abstractTriggerInfo.entrySet().iterator(); i$.hasNext(); invokeSetter(trigger, key, value)) {
                  Entry<String, Object> entry = (Entry)i$.next();
                  key = (String)entry.getKey();
                  value = entry.getValue();
                  if ("jobDataMap".equals(key)) {
                     value = new JobDataMap((Map)value);
                  }
               }

               AbstractTrigger<?> at = (AbstractTrigger)trigger;
               at.setKey(new TriggerKey(at.getName(), at.getGroup()));
               Date startDate = at.getStartTime();
               if (startDate == null || startDate.before(new Date())) {
                  at.setStartTime(new Date());
               }

               this.scheduler.scheduleJob(trigger);
            }
         }
      } catch (Exception var13) {
         throw this.newPlainException(var13);
      }
   }

   public void addJob(Map<String, Object> abstractJobInfo, boolean replace) throws Exception {
      try {
         String jobDetailClassName = (String)abstractJobInfo.remove("jobDetailClass");
         if (jobDetailClassName == null) {
            throw new IllegalArgumentException("No jobDetailClass specified");
         } else {
            Class<?> jobDetailClass = Class.forName(jobDetailClassName);
            JobDetail jobDetail = (JobDetail)jobDetailClass.newInstance();
            String jobClassName = (String)abstractJobInfo.remove("jobClass");
            if (jobClassName == null) {
               throw new IllegalArgumentException("No jobClass specified");
            } else {
               Class<?> jobClass = Class.forName(jobClassName);
               abstractJobInfo.put("jobClass", jobClass);

               String key;
               Object value;
               for(Iterator i$ = abstractJobInfo.entrySet().iterator(); i$.hasNext(); invokeSetter(jobDetail, key, value)) {
                  Entry<String, Object> entry = (Entry)i$.next();
                  key = (String)entry.getKey();
                  value = entry.getValue();
                  if ("jobDataMap".equals(key)) {
                     value = new JobDataMap((Map)value);
                  }
               }

               this.scheduler.addJob(jobDetail, replace);
            }
         }
      } catch (Exception var12) {
         throw this.newPlainException(var12);
      }
   }

   private Exception newPlainException(Exception e) {
      String type = e.getClass().getName();
      if (!type.startsWith("java.") && !type.startsWith("javax.")) {
         Exception result = new Exception(e.getMessage());
         result.setStackTrace(e.getStackTrace());
         return result;
      } else {
         return e;
      }
   }

   public void deleteCalendar(String calendarName) throws Exception {
      try {
         this.scheduler.deleteCalendar(calendarName);
      } catch (Exception var3) {
         throw this.newPlainException(var3);
      }
   }

   public boolean deleteJob(String jobName, String jobGroupName) throws Exception {
      try {
         return this.scheduler.deleteJob(JobKey.jobKey(jobName, jobGroupName));
      } catch (Exception var4) {
         throw this.newPlainException(var4);
      }
   }

   public List<String> getCalendarNames() throws Exception {
      try {
         return this.scheduler.getCalendarNames();
      } catch (Exception var2) {
         throw this.newPlainException(var2);
      }
   }

   public CompositeData getJobDetail(String jobName, String jobGroupName) throws Exception {
      try {
         JobDetail jobDetail = this.scheduler.getJobDetail(JobKey.jobKey(jobName, jobGroupName));
         return JobDetailSupport.toCompositeData(jobDetail);
      } catch (Exception var4) {
         throw this.newPlainException(var4);
      }
   }

   public List<String> getJobGroupNames() throws Exception {
      try {
         return this.scheduler.getJobGroupNames();
      } catch (Exception var2) {
         throw this.newPlainException(var2);
      }
   }

   public List<String> getJobNames(String groupName) throws Exception {
      try {
         List<String> jobNames = new ArrayList();
         Iterator i$ = this.scheduler.getJobKeys(GroupMatcher.jobGroupEquals(groupName)).iterator();

         while(i$.hasNext()) {
            JobKey key = (JobKey)i$.next();
            jobNames.add(key.getName());
         }

         return jobNames;
      } catch (Exception var5) {
         throw this.newPlainException(var5);
      }
   }

   public String getJobStoreClassName() {
      return this.scheduler.getJobStoreClass().getName();
   }

   public Set<String> getPausedTriggerGroups() throws Exception {
      try {
         return this.scheduler.getPausedTriggerGroups();
      } catch (Exception var2) {
         throw this.newPlainException(var2);
      }
   }

   public CompositeData getTrigger(String name, String groupName) throws Exception {
      try {
         Trigger trigger = this.scheduler.getTrigger(TriggerKey.triggerKey(name, groupName));
         return TriggerSupport.toCompositeData(trigger);
      } catch (Exception var4) {
         throw this.newPlainException(var4);
      }
   }

   public List<String> getTriggerGroupNames() throws Exception {
      try {
         return this.scheduler.getTriggerGroupNames();
      } catch (Exception var2) {
         throw this.newPlainException(var2);
      }
   }

   public List<String> getTriggerNames(String groupName) throws Exception {
      try {
         List<String> triggerNames = new ArrayList();
         Iterator i$ = this.scheduler.getTriggerKeys(GroupMatcher.triggerGroupEquals(groupName)).iterator();

         while(i$.hasNext()) {
            TriggerKey key = (TriggerKey)i$.next();
            triggerNames.add(key.getName());
         }

         return triggerNames;
      } catch (Exception var5) {
         throw this.newPlainException(var5);
      }
   }

   public String getTriggerState(String triggerName, String triggerGroupName) throws Exception {
      try {
         TriggerKey triggerKey = TriggerKey.triggerKey(triggerName, triggerGroupName);
         Trigger.TriggerState ts = this.scheduler.getTriggerState(triggerKey);
         return ts.name();
      } catch (Exception var5) {
         throw this.newPlainException(var5);
      }
   }

   public List<CompositeData> getTriggersOfJob(String jobName, String jobGroupName) throws Exception {
      try {
         JobKey jobKey = JobKey.jobKey(jobName, jobGroupName);
         return TriggerSupport.toCompositeList(this.scheduler.getTriggersOfJob(jobKey));
      } catch (Exception var4) {
         throw this.newPlainException(var4);
      }
   }

   public boolean interruptJob(String jobName, String jobGroupName) throws Exception {
      try {
         return this.scheduler.interrupt(JobKey.jobKey(jobName, jobGroupName));
      } catch (Exception var4) {
         throw this.newPlainException(var4);
      }
   }

   public boolean interruptJob(String fireInstanceId) throws Exception {
      try {
         return this.scheduler.interrupt(fireInstanceId);
      } catch (Exception var3) {
         throw this.newPlainException(var3);
      }
   }

   public Date scheduleJob(String jobName, String jobGroup, String triggerName, String triggerGroup) throws Exception {
      try {
         JobKey jobKey = JobKey.jobKey(jobName, jobGroup);
         JobDetail jobDetail = this.scheduler.getJobDetail(jobKey);
         if (jobDetail == null) {
            throw new IllegalArgumentException("No such job: " + jobKey);
         } else {
            TriggerKey triggerKey = TriggerKey.triggerKey(triggerName, triggerGroup);
            Trigger trigger = this.scheduler.getTrigger(triggerKey);
            if (trigger == null) {
               throw new IllegalArgumentException("No such trigger: " + triggerKey);
            } else {
               return this.scheduler.scheduleJob(jobDetail, trigger);
            }
         }
      } catch (Exception var9) {
         throw this.newPlainException(var9);
      }
   }

   public boolean unscheduleJob(String triggerName, String triggerGroup) throws Exception {
      try {
         return this.scheduler.unscheduleJob(TriggerKey.triggerKey(triggerName, triggerGroup));
      } catch (Exception var4) {
         throw this.newPlainException(var4);
      }
   }

   public void clear() throws Exception {
      try {
         this.scheduler.clear();
      } catch (Exception var2) {
         throw this.newPlainException(var2);
      }
   }

   public String getVersion() {
      return this.scheduler.getVersion();
   }

   public boolean isShutdown() {
      return this.scheduler.isShutdown();
   }

   public boolean isStarted() {
      return this.scheduler.isStarted();
   }

   public void start() throws Exception {
      try {
         this.scheduler.start();
      } catch (Exception var2) {
         throw this.newPlainException(var2);
      }
   }

   public void shutdown() {
      this.scheduler.shutdown();
   }

   public void standby() {
      this.scheduler.standby();
   }

   public boolean isStandbyMode() {
      return this.scheduler.isInStandbyMode();
   }

   public String getSchedulerName() {
      return this.scheduler.getSchedulerName();
   }

   public String getSchedulerInstanceId() {
      return this.scheduler.getSchedulerInstanceId();
   }

   public String getThreadPoolClassName() {
      return this.scheduler.getThreadPoolClass().getName();
   }

   public int getThreadPoolSize() {
      return this.scheduler.getThreadPoolSize();
   }

   public void pauseJob(String jobName, String jobGroup) throws Exception {
      try {
         this.scheduler.pauseJob(JobKey.jobKey(jobName, jobGroup));
      } catch (Exception var4) {
         throw this.newPlainException(var4);
      }
   }

   public void pauseJobs(GroupMatcher<JobKey> matcher) throws Exception {
      try {
         this.scheduler.pauseJobs(matcher);
      } catch (Exception var3) {
         throw this.newPlainException(var3);
      }
   }

   public void pauseJobGroup(String jobGroup) throws Exception {
      this.pauseJobs(GroupMatcher.groupEquals(jobGroup));
   }

   public void pauseJobsStartingWith(String jobGroupPrefix) throws Exception {
      this.pauseJobs(GroupMatcher.groupStartsWith(jobGroupPrefix));
   }

   public void pauseJobsEndingWith(String jobGroupSuffix) throws Exception {
      this.pauseJobs(GroupMatcher.groupEndsWith(jobGroupSuffix));
   }

   public void pauseJobsContaining(String jobGroupToken) throws Exception {
      this.pauseJobs(GroupMatcher.groupContains(jobGroupToken));
   }

   public void pauseJobsAll() throws Exception {
      this.pauseJobs(GroupMatcher.anyJobGroup());
   }

   public void pauseAllTriggers() throws Exception {
      try {
         this.scheduler.pauseAll();
      } catch (Exception var2) {
         throw this.newPlainException(var2);
      }
   }

   private void pauseTriggers(GroupMatcher<TriggerKey> matcher) throws Exception {
      try {
         this.scheduler.pauseTriggers(matcher);
      } catch (Exception var3) {
         throw this.newPlainException(var3);
      }
   }

   public void pauseTriggerGroup(String triggerGroup) throws Exception {
      this.pauseTriggers(GroupMatcher.groupEquals(triggerGroup));
   }

   public void pauseTriggersStartingWith(String triggerGroupPrefix) throws Exception {
      this.pauseTriggers(GroupMatcher.groupStartsWith(triggerGroupPrefix));
   }

   public void pauseTriggersEndingWith(String triggerGroupSuffix) throws Exception {
      this.pauseTriggers(GroupMatcher.groupEndsWith(triggerGroupSuffix));
   }

   public void pauseTriggersContaining(String triggerGroupToken) throws Exception {
      this.pauseTriggers(GroupMatcher.groupContains(triggerGroupToken));
   }

   public void pauseTriggersAll() throws Exception {
      this.pauseTriggers(GroupMatcher.anyTriggerGroup());
   }

   public void pauseTrigger(String triggerName, String triggerGroup) throws Exception {
      try {
         this.scheduler.pauseTrigger(TriggerKey.triggerKey(triggerName, triggerGroup));
      } catch (Exception var4) {
         throw this.newPlainException(var4);
      }
   }

   public void resumeAllTriggers() throws Exception {
      try {
         this.scheduler.resumeAll();
      } catch (Exception var2) {
         throw this.newPlainException(var2);
      }
   }

   public void resumeJob(String jobName, String jobGroup) throws Exception {
      try {
         this.scheduler.resumeJob(JobKey.jobKey(jobName, jobGroup));
      } catch (Exception var4) {
         throw this.newPlainException(var4);
      }
   }

   public void resumeJobs(GroupMatcher<JobKey> matcher) throws Exception {
      try {
         this.scheduler.resumeJobs(matcher);
      } catch (Exception var3) {
         throw this.newPlainException(var3);
      }
   }

   public void resumeJobGroup(String jobGroup) throws Exception {
      this.resumeJobs(GroupMatcher.groupEquals(jobGroup));
   }

   public void resumeJobsStartingWith(String jobGroupPrefix) throws Exception {
      this.resumeJobs(GroupMatcher.groupStartsWith(jobGroupPrefix));
   }

   public void resumeJobsEndingWith(String jobGroupSuffix) throws Exception {
      this.resumeJobs(GroupMatcher.groupEndsWith(jobGroupSuffix));
   }

   public void resumeJobsContaining(String jobGroupToken) throws Exception {
      this.resumeJobs(GroupMatcher.groupContains(jobGroupToken));
   }

   public void resumeJobsAll() throws Exception {
      this.resumeJobs(GroupMatcher.anyJobGroup());
   }

   public void resumeTrigger(String triggerName, String triggerGroup) throws Exception {
      try {
         this.scheduler.resumeTrigger(TriggerKey.triggerKey(triggerName, triggerGroup));
      } catch (Exception var4) {
         throw this.newPlainException(var4);
      }
   }

   private void resumeTriggers(GroupMatcher<TriggerKey> matcher) throws Exception {
      try {
         this.scheduler.resumeTriggers(matcher);
      } catch (Exception var3) {
         throw this.newPlainException(var3);
      }
   }

   public void resumeTriggerGroup(String triggerGroup) throws Exception {
      this.resumeTriggers(GroupMatcher.groupEquals(triggerGroup));
   }

   public void resumeTriggersStartingWith(String triggerGroupPrefix) throws Exception {
      this.resumeTriggers(GroupMatcher.groupStartsWith(triggerGroupPrefix));
   }

   public void resumeTriggersEndingWith(String triggerGroupSuffix) throws Exception {
      this.resumeTriggers(GroupMatcher.groupEndsWith(triggerGroupSuffix));
   }

   public void resumeTriggersContaining(String triggerGroupToken) throws Exception {
      this.resumeTriggers(GroupMatcher.groupContains(triggerGroupToken));
   }

   public void resumeTriggersAll() throws Exception {
      this.resumeTriggers(GroupMatcher.anyTriggerGroup());
   }

   public void triggerJob(String jobName, String jobGroup, Map<String, String> jobDataMap) throws Exception {
      try {
         this.scheduler.triggerJob(JobKey.jobKey(jobName, jobGroup), new JobDataMap(jobDataMap));
      } catch (Exception var5) {
         throw this.newPlainException(var5);
      }
   }

   public void triggerJob(CompositeData trigger) throws Exception {
      try {
         this.scheduler.triggerJob(TriggerSupport.newTrigger(trigger));
      } catch (Exception var3) {
         throw this.newPlainException(var3);
      }
   }

   public void jobAdded(JobDetail jobDetail) {
      this.sendNotification("jobAdded", JobDetailSupport.toCompositeData(jobDetail));
   }

   public void jobDeleted(JobKey jobKey) {
      Map<String, String> map = new HashMap();
      map.put("jobName", jobKey.getName());
      map.put("jobGroup", jobKey.getGroup());
      this.sendNotification("jobDeleted", map);
   }

   public void jobScheduled(Trigger trigger) {
      this.sendNotification("jobScheduled", TriggerSupport.toCompositeData(trigger));
   }

   public void jobUnscheduled(TriggerKey triggerKey) {
      Map<String, String> map = new HashMap();
      map.put("triggerName", triggerKey.getName());
      map.put("triggerGroup", triggerKey.getGroup());
      this.sendNotification("jobUnscheduled", map);
   }

   public void schedulingDataCleared() {
      this.sendNotification("schedulingDataCleared");
   }

   public void jobPaused(JobKey jobKey) {
      Map<String, String> map = new HashMap();
      map.put("jobName", jobKey.getName());
      map.put("jobGroup", jobKey.getGroup());
      this.sendNotification("jobsPaused", map);
   }

   public void jobsPaused(String jobGroup) {
      Map<String, String> map = new HashMap();
      map.put("jobName", (Object)null);
      map.put("jobGroup", jobGroup);
      this.sendNotification("jobsPaused", map);
   }

   public void jobsResumed(String jobGroup) {
      Map<String, String> map = new HashMap();
      map.put("jobName", (Object)null);
      map.put("jobGroup", jobGroup);
      this.sendNotification("jobsResumed", map);
   }

   public void jobResumed(JobKey jobKey) {
      Map<String, String> map = new HashMap();
      map.put("jobName", jobKey.getName());
      map.put("jobGroup", jobKey.getGroup());
      this.sendNotification("jobsResumed", map);
   }

   public void schedulerError(String msg, SchedulerException cause) {
      this.sendNotification("schedulerError", cause.getMessage());
   }

   public void schedulerStarted() {
      this.sendNotification("schedulerStarted");
   }

   public void schedulerStarting() {
   }

   public void schedulerInStandbyMode() {
      this.sendNotification("schedulerPaused");
   }

   public void schedulerShutdown() {
      this.scheduler.removeInternalSchedulerListener(this);
      this.scheduler.removeInternalJobListener(this.getName());
      this.sendNotification("schedulerShutdown");
   }

   public void schedulerShuttingdown() {
   }

   public void triggerFinalized(Trigger trigger) {
      Map<String, String> map = new HashMap();
      map.put("triggerName", trigger.getKey().getName());
      map.put("triggerGroup", trigger.getKey().getGroup());
      this.sendNotification("triggerFinalized", map);
   }

   public void triggersPaused(String triggerGroup) {
      Map<String, String> map = new HashMap();
      map.put("triggerName", (Object)null);
      map.put("triggerGroup", triggerGroup);
      this.sendNotification("triggersPaused", map);
   }

   public void triggerPaused(TriggerKey triggerKey) {
      Map<String, String> map = new HashMap();
      if (triggerKey != null) {
         map.put("triggerName", triggerKey.getName());
         map.put("triggerGroup", triggerKey.getGroup());
      }

      this.sendNotification("triggersPaused", map);
   }

   public void triggersResumed(String triggerGroup) {
      Map<String, String> map = new HashMap();
      map.put("triggerName", (Object)null);
      map.put("triggerGroup", triggerGroup);
      this.sendNotification("triggersResumed", map);
   }

   public void triggerResumed(TriggerKey triggerKey) {
      Map<String, String> map = new HashMap();
      if (triggerKey != null) {
         map.put("triggerName", triggerKey.getName());
         map.put("triggerGroup", triggerKey.getGroup());
      }

      this.sendNotification("triggersResumed", map);
   }

   public String getName() {
      return "QuartzSchedulerMBeanImpl.listener";
   }

   public void jobExecutionVetoed(JobExecutionContext context) {
      try {
         this.sendNotification("jobExecutionVetoed", JobExecutionContextSupport.toCompositeData(context));
      } catch (Exception var3) {
         throw new RuntimeException(this.newPlainException(var3));
      }
   }

   public void jobToBeExecuted(JobExecutionContext context) {
      try {
         this.sendNotification("jobToBeExecuted", JobExecutionContextSupport.toCompositeData(context));
      } catch (Exception var3) {
         throw new RuntimeException(this.newPlainException(var3));
      }
   }

   public void jobWasExecuted(JobExecutionContext context, JobExecutionException jobException) {
      try {
         this.sendNotification("jobWasExecuted", JobExecutionContextSupport.toCompositeData(context));
      } catch (Exception var4) {
         throw new RuntimeException(this.newPlainException(var4));
      }
   }

   public void sendNotification(String eventType) {
      this.sendNotification(eventType, (Object)null, (String)null);
   }

   public void sendNotification(String eventType, Object data) {
      this.sendNotification(eventType, data, (String)null);
   }

   public void sendNotification(String eventType, Object data, String msg) {
      Notification notif = new Notification(eventType, this, this.sequenceNumber.incrementAndGet(), System.currentTimeMillis(), msg);
      if (data != null) {
         notif.setUserData(data);
      }

      this.emitter.sendNotification(notif);
   }

   public void addNotificationListener(NotificationListener notif, NotificationFilter filter, Object callBack) {
      this.emitter.addNotificationListener(notif, filter, callBack);
   }

   public MBeanNotificationInfo[] getNotificationInfo() {
      return NOTIFICATION_INFO;
   }

   public void removeNotificationListener(NotificationListener listener) throws ListenerNotFoundException {
      this.emitter.removeNotificationListener(listener);
   }

   public void removeNotificationListener(NotificationListener notif, NotificationFilter filter, Object callBack) throws ListenerNotFoundException {
      this.emitter.removeNotificationListener(notif, filter, callBack);
   }

   public synchronized boolean isSampledStatisticsEnabled() {
      return this.sampledStatisticsEnabled;
   }

   public void setSampledStatisticsEnabled(boolean enabled) {
      if (enabled != this.sampledStatisticsEnabled) {
         this.sampledStatisticsEnabled = enabled;
         if (enabled) {
            this.sampledStatistics = new SampledStatisticsImpl(this.scheduler);
         } else {
            this.sampledStatistics.shutdown();
            this.sampledStatistics = NULL_SAMPLED_STATISTICS;
         }

         this.sendNotification("sampledStatisticsEnabled", enabled);
      }

   }

   public long getJobsCompletedMostRecentSample() {
      return this.sampledStatistics.getJobsCompletedMostRecentSample();
   }

   public long getJobsExecutedMostRecentSample() {
      return this.sampledStatistics.getJobsExecutingMostRecentSample();
   }

   public long getJobsScheduledMostRecentSample() {
      return this.sampledStatistics.getJobsScheduledMostRecentSample();
   }

   public Map<String, Long> getPerformanceMetrics() {
      Map<String, Long> result = new HashMap();
      result.put("JobsCompleted", this.getJobsCompletedMostRecentSample());
      result.put("JobsExecuted", this.getJobsExecutedMostRecentSample());
      result.put("JobsScheduled", this.getJobsScheduledMostRecentSample());
      return result;
   }

   static {
      String[] notifTypes = new String[]{"schedulerStarted", "schedulerPaused", "schedulerShutdown"};
      String name = Notification.class.getName();
      String description = "QuartzScheduler JMX Event";
      NOTIFICATION_INFO = new MBeanNotificationInfo[]{new MBeanNotificationInfo(notifTypes, name, "QuartzScheduler JMX Event")};
   }

   private class Emitter extends NotificationBroadcasterSupport {
      private Emitter() {
      }

      public MBeanNotificationInfo[] getNotificationInfo() {
         return QuartzSchedulerMBeanImpl.this.getNotificationInfo();
      }

      // $FF: synthetic method
      Emitter(Object x1) {
         this();
      }
   }
}
