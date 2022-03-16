package org.quartz.core;

import java.lang.reflect.Method;
import java.rmi.RemoteException;
import java.rmi.UnexpectedException;
import java.rmi.server.RemoteRef;
import java.rmi.server.RemoteStub;
import java.util.Date;
import java.util.List;
import java.util.Map;
import java.util.Set;
import org.quartz.Calendar;
import org.quartz.JobDataMap;
import org.quartz.JobDetail;
import org.quartz.JobKey;
import org.quartz.SchedulerContext;
import org.quartz.SchedulerException;
import org.quartz.Trigger;
import org.quartz.TriggerKey;
import org.quartz.UnableToInterruptJobException;
import org.quartz.impl.matchers.GroupMatcher;
import org.quartz.spi.OperableTrigger;

public final class QuartzScheduler_Stub extends RemoteStub implements RemotableQuartzScheduler {
   private static final long serialVersionUID = 2L;
   private static Method $method_addCalendar_0;
   private static Method $method_addJob_1;
   private static Method $method_addJob_2;
   private static Method $method_checkExists_3;
   private static Method $method_checkExists_4;
   private static Method $method_clear_5;
   private static Method $method_deleteCalendar_6;
   private static Method $method_deleteJob_7;
   private static Method $method_deleteJobs_8;
   private static Method $method_getCalendar_9;
   private static Method $method_getCalendarNames_10;
   private static Method $method_getCurrentlyExecutingJobs_11;
   private static Method $method_getJobDetail_12;
   private static Method $method_getJobGroupNames_13;
   private static Method $method_getJobKeys_14;
   private static Method $method_getJobStoreClass_15;
   private static Method $method_getPausedTriggerGroups_16;
   private static Method $method_getSchedulerContext_17;
   private static Method $method_getSchedulerInstanceId_18;
   private static Method $method_getSchedulerName_19;
   private static Method $method_getThreadPoolClass_20;
   private static Method $method_getThreadPoolSize_21;
   private static Method $method_getTrigger_22;
   private static Method $method_getTriggerGroupNames_23;
   private static Method $method_getTriggerKeys_24;
   private static Method $method_getTriggerState_25;
   private static Method $method_getTriggersOfJob_26;
   private static Method $method_getVersion_27;
   private static Method $method_interrupt_28;
   private static Method $method_interrupt_29;
   private static Method $method_isClustered_30;
   private static Method $method_isInStandbyMode_31;
   private static Method $method_isShutdown_32;
   private static Method $method_numJobsExecuted_33;
   private static Method $method_pauseAll_34;
   private static Method $method_pauseJob_35;
   private static Method $method_pauseJobs_36;
   private static Method $method_pauseTrigger_37;
   private static Method $method_pauseTriggers_38;
   private static Method $method_rescheduleJob_39;
   private static Method $method_resumeAll_40;
   private static Method $method_resumeJob_41;
   private static Method $method_resumeJobs_42;
   private static Method $method_resumeTrigger_43;
   private static Method $method_resumeTriggers_44;
   private static Method $method_runningSince_45;
   private static Method $method_scheduleJob_46;
   private static Method $method_scheduleJob_47;
   private static Method $method_scheduleJob_48;
   private static Method $method_scheduleJobs_49;
   private static Method $method_shutdown_50;
   private static Method $method_shutdown_51;
   private static Method $method_standby_52;
   private static Method $method_start_53;
   private static Method $method_startDelayed_54;
   private static Method $method_supportsPersistence_55;
   private static Method $method_triggerJob_56;
   private static Method $method_triggerJob_57;
   private static Method $method_unscheduleJob_58;
   private static Method $method_unscheduleJobs_59;
   // $FF: synthetic field
   static Class class$org$quartz$core$RemotableQuartzScheduler;
   // $FF: synthetic field
   static Class class$java$lang$String;
   // $FF: synthetic field
   static Class class$org$quartz$Calendar;
   // $FF: synthetic field
   static Class class$org$quartz$JobDetail;
   // $FF: synthetic field
   static Class class$org$quartz$JobKey;
   // $FF: synthetic field
   static Class class$org$quartz$TriggerKey;
   // $FF: synthetic field
   static Class class$java$util$List;
   // $FF: synthetic field
   static Class class$org$quartz$impl$matchers$GroupMatcher;
   // $FF: synthetic field
   static Class class$org$quartz$Trigger;
   // $FF: synthetic field
   static Class class$java$util$Set;
   // $FF: synthetic field
   static Class class$java$util$Map;
   // $FF: synthetic field
   static Class class$org$quartz$JobDataMap;
   // $FF: synthetic field
   static Class class$org$quartz$spi$OperableTrigger;

   static {
      try {
         $method_addCalendar_0 = (class$org$quartz$core$RemotableQuartzScheduler != null ? class$org$quartz$core$RemotableQuartzScheduler : (class$org$quartz$core$RemotableQuartzScheduler = class$("org.quartz.core.RemotableQuartzScheduler"))).getMethod("addCalendar", class$java$lang$String != null ? class$java$lang$String : (class$java$lang$String = class$("java.lang.String")), class$org$quartz$Calendar != null ? class$org$quartz$Calendar : (class$org$quartz$Calendar = class$("org.quartz.Calendar")), Boolean.TYPE, Boolean.TYPE);
         $method_addJob_1 = (class$org$quartz$core$RemotableQuartzScheduler != null ? class$org$quartz$core$RemotableQuartzScheduler : (class$org$quartz$core$RemotableQuartzScheduler = class$("org.quartz.core.RemotableQuartzScheduler"))).getMethod("addJob", class$org$quartz$JobDetail != null ? class$org$quartz$JobDetail : (class$org$quartz$JobDetail = class$("org.quartz.JobDetail")), Boolean.TYPE);
         $method_addJob_2 = (class$org$quartz$core$RemotableQuartzScheduler != null ? class$org$quartz$core$RemotableQuartzScheduler : (class$org$quartz$core$RemotableQuartzScheduler = class$("org.quartz.core.RemotableQuartzScheduler"))).getMethod("addJob", class$org$quartz$JobDetail != null ? class$org$quartz$JobDetail : (class$org$quartz$JobDetail = class$("org.quartz.JobDetail")), Boolean.TYPE, Boolean.TYPE);
         $method_checkExists_3 = (class$org$quartz$core$RemotableQuartzScheduler != null ? class$org$quartz$core$RemotableQuartzScheduler : (class$org$quartz$core$RemotableQuartzScheduler = class$("org.quartz.core.RemotableQuartzScheduler"))).getMethod("checkExists", class$org$quartz$JobKey != null ? class$org$quartz$JobKey : (class$org$quartz$JobKey = class$("org.quartz.JobKey")));
         $method_checkExists_4 = (class$org$quartz$core$RemotableQuartzScheduler != null ? class$org$quartz$core$RemotableQuartzScheduler : (class$org$quartz$core$RemotableQuartzScheduler = class$("org.quartz.core.RemotableQuartzScheduler"))).getMethod("checkExists", class$org$quartz$TriggerKey != null ? class$org$quartz$TriggerKey : (class$org$quartz$TriggerKey = class$("org.quartz.TriggerKey")));
         $method_clear_5 = (class$org$quartz$core$RemotableQuartzScheduler != null ? class$org$quartz$core$RemotableQuartzScheduler : (class$org$quartz$core$RemotableQuartzScheduler = class$("org.quartz.core.RemotableQuartzScheduler"))).getMethod("clear");
         $method_deleteCalendar_6 = (class$org$quartz$core$RemotableQuartzScheduler != null ? class$org$quartz$core$RemotableQuartzScheduler : (class$org$quartz$core$RemotableQuartzScheduler = class$("org.quartz.core.RemotableQuartzScheduler"))).getMethod("deleteCalendar", class$java$lang$String != null ? class$java$lang$String : (class$java$lang$String = class$("java.lang.String")));
         $method_deleteJob_7 = (class$org$quartz$core$RemotableQuartzScheduler != null ? class$org$quartz$core$RemotableQuartzScheduler : (class$org$quartz$core$RemotableQuartzScheduler = class$("org.quartz.core.RemotableQuartzScheduler"))).getMethod("deleteJob", class$org$quartz$JobKey != null ? class$org$quartz$JobKey : (class$org$quartz$JobKey = class$("org.quartz.JobKey")));
         $method_deleteJobs_8 = (class$org$quartz$core$RemotableQuartzScheduler != null ? class$org$quartz$core$RemotableQuartzScheduler : (class$org$quartz$core$RemotableQuartzScheduler = class$("org.quartz.core.RemotableQuartzScheduler"))).getMethod("deleteJobs", class$java$util$List != null ? class$java$util$List : (class$java$util$List = class$("java.util.List")));
         $method_getCalendar_9 = (class$org$quartz$core$RemotableQuartzScheduler != null ? class$org$quartz$core$RemotableQuartzScheduler : (class$org$quartz$core$RemotableQuartzScheduler = class$("org.quartz.core.RemotableQuartzScheduler"))).getMethod("getCalendar", class$java$lang$String != null ? class$java$lang$String : (class$java$lang$String = class$("java.lang.String")));
         $method_getCalendarNames_10 = (class$org$quartz$core$RemotableQuartzScheduler != null ? class$org$quartz$core$RemotableQuartzScheduler : (class$org$quartz$core$RemotableQuartzScheduler = class$("org.quartz.core.RemotableQuartzScheduler"))).getMethod("getCalendarNames");
         $method_getCurrentlyExecutingJobs_11 = (class$org$quartz$core$RemotableQuartzScheduler != null ? class$org$quartz$core$RemotableQuartzScheduler : (class$org$quartz$core$RemotableQuartzScheduler = class$("org.quartz.core.RemotableQuartzScheduler"))).getMethod("getCurrentlyExecutingJobs");
         $method_getJobDetail_12 = (class$org$quartz$core$RemotableQuartzScheduler != null ? class$org$quartz$core$RemotableQuartzScheduler : (class$org$quartz$core$RemotableQuartzScheduler = class$("org.quartz.core.RemotableQuartzScheduler"))).getMethod("getJobDetail", class$org$quartz$JobKey != null ? class$org$quartz$JobKey : (class$org$quartz$JobKey = class$("org.quartz.JobKey")));
         $method_getJobGroupNames_13 = (class$org$quartz$core$RemotableQuartzScheduler != null ? class$org$quartz$core$RemotableQuartzScheduler : (class$org$quartz$core$RemotableQuartzScheduler = class$("org.quartz.core.RemotableQuartzScheduler"))).getMethod("getJobGroupNames");
         $method_getJobKeys_14 = (class$org$quartz$core$RemotableQuartzScheduler != null ? class$org$quartz$core$RemotableQuartzScheduler : (class$org$quartz$core$RemotableQuartzScheduler = class$("org.quartz.core.RemotableQuartzScheduler"))).getMethod("getJobKeys", class$org$quartz$impl$matchers$GroupMatcher != null ? class$org$quartz$impl$matchers$GroupMatcher : (class$org$quartz$impl$matchers$GroupMatcher = class$("org.quartz.impl.matchers.GroupMatcher")));
         $method_getJobStoreClass_15 = (class$org$quartz$core$RemotableQuartzScheduler != null ? class$org$quartz$core$RemotableQuartzScheduler : (class$org$quartz$core$RemotableQuartzScheduler = class$("org.quartz.core.RemotableQuartzScheduler"))).getMethod("getJobStoreClass");
         $method_getPausedTriggerGroups_16 = (class$org$quartz$core$RemotableQuartzScheduler != null ? class$org$quartz$core$RemotableQuartzScheduler : (class$org$quartz$core$RemotableQuartzScheduler = class$("org.quartz.core.RemotableQuartzScheduler"))).getMethod("getPausedTriggerGroups");
         $method_getSchedulerContext_17 = (class$org$quartz$core$RemotableQuartzScheduler != null ? class$org$quartz$core$RemotableQuartzScheduler : (class$org$quartz$core$RemotableQuartzScheduler = class$("org.quartz.core.RemotableQuartzScheduler"))).getMethod("getSchedulerContext");
         $method_getSchedulerInstanceId_18 = (class$org$quartz$core$RemotableQuartzScheduler != null ? class$org$quartz$core$RemotableQuartzScheduler : (class$org$quartz$core$RemotableQuartzScheduler = class$("org.quartz.core.RemotableQuartzScheduler"))).getMethod("getSchedulerInstanceId");
         $method_getSchedulerName_19 = (class$org$quartz$core$RemotableQuartzScheduler != null ? class$org$quartz$core$RemotableQuartzScheduler : (class$org$quartz$core$RemotableQuartzScheduler = class$("org.quartz.core.RemotableQuartzScheduler"))).getMethod("getSchedulerName");
         $method_getThreadPoolClass_20 = (class$org$quartz$core$RemotableQuartzScheduler != null ? class$org$quartz$core$RemotableQuartzScheduler : (class$org$quartz$core$RemotableQuartzScheduler = class$("org.quartz.core.RemotableQuartzScheduler"))).getMethod("getThreadPoolClass");
         $method_getThreadPoolSize_21 = (class$org$quartz$core$RemotableQuartzScheduler != null ? class$org$quartz$core$RemotableQuartzScheduler : (class$org$quartz$core$RemotableQuartzScheduler = class$("org.quartz.core.RemotableQuartzScheduler"))).getMethod("getThreadPoolSize");
         $method_getTrigger_22 = (class$org$quartz$core$RemotableQuartzScheduler != null ? class$org$quartz$core$RemotableQuartzScheduler : (class$org$quartz$core$RemotableQuartzScheduler = class$("org.quartz.core.RemotableQuartzScheduler"))).getMethod("getTrigger", class$org$quartz$TriggerKey != null ? class$org$quartz$TriggerKey : (class$org$quartz$TriggerKey = class$("org.quartz.TriggerKey")));
         $method_getTriggerGroupNames_23 = (class$org$quartz$core$RemotableQuartzScheduler != null ? class$org$quartz$core$RemotableQuartzScheduler : (class$org$quartz$core$RemotableQuartzScheduler = class$("org.quartz.core.RemotableQuartzScheduler"))).getMethod("getTriggerGroupNames");
         $method_getTriggerKeys_24 = (class$org$quartz$core$RemotableQuartzScheduler != null ? class$org$quartz$core$RemotableQuartzScheduler : (class$org$quartz$core$RemotableQuartzScheduler = class$("org.quartz.core.RemotableQuartzScheduler"))).getMethod("getTriggerKeys", class$org$quartz$impl$matchers$GroupMatcher != null ? class$org$quartz$impl$matchers$GroupMatcher : (class$org$quartz$impl$matchers$GroupMatcher = class$("org.quartz.impl.matchers.GroupMatcher")));
         $method_getTriggerState_25 = (class$org$quartz$core$RemotableQuartzScheduler != null ? class$org$quartz$core$RemotableQuartzScheduler : (class$org$quartz$core$RemotableQuartzScheduler = class$("org.quartz.core.RemotableQuartzScheduler"))).getMethod("getTriggerState", class$org$quartz$TriggerKey != null ? class$org$quartz$TriggerKey : (class$org$quartz$TriggerKey = class$("org.quartz.TriggerKey")));
         $method_getTriggersOfJob_26 = (class$org$quartz$core$RemotableQuartzScheduler != null ? class$org$quartz$core$RemotableQuartzScheduler : (class$org$quartz$core$RemotableQuartzScheduler = class$("org.quartz.core.RemotableQuartzScheduler"))).getMethod("getTriggersOfJob", class$org$quartz$JobKey != null ? class$org$quartz$JobKey : (class$org$quartz$JobKey = class$("org.quartz.JobKey")));
         $method_getVersion_27 = (class$org$quartz$core$RemotableQuartzScheduler != null ? class$org$quartz$core$RemotableQuartzScheduler : (class$org$quartz$core$RemotableQuartzScheduler = class$("org.quartz.core.RemotableQuartzScheduler"))).getMethod("getVersion");
         $method_interrupt_28 = (class$org$quartz$core$RemotableQuartzScheduler != null ? class$org$quartz$core$RemotableQuartzScheduler : (class$org$quartz$core$RemotableQuartzScheduler = class$("org.quartz.core.RemotableQuartzScheduler"))).getMethod("interrupt", class$java$lang$String != null ? class$java$lang$String : (class$java$lang$String = class$("java.lang.String")));
         $method_interrupt_29 = (class$org$quartz$core$RemotableQuartzScheduler != null ? class$org$quartz$core$RemotableQuartzScheduler : (class$org$quartz$core$RemotableQuartzScheduler = class$("org.quartz.core.RemotableQuartzScheduler"))).getMethod("interrupt", class$org$quartz$JobKey != null ? class$org$quartz$JobKey : (class$org$quartz$JobKey = class$("org.quartz.JobKey")));
         $method_isClustered_30 = (class$org$quartz$core$RemotableQuartzScheduler != null ? class$org$quartz$core$RemotableQuartzScheduler : (class$org$quartz$core$RemotableQuartzScheduler = class$("org.quartz.core.RemotableQuartzScheduler"))).getMethod("isClustered");
         $method_isInStandbyMode_31 = (class$org$quartz$core$RemotableQuartzScheduler != null ? class$org$quartz$core$RemotableQuartzScheduler : (class$org$quartz$core$RemotableQuartzScheduler = class$("org.quartz.core.RemotableQuartzScheduler"))).getMethod("isInStandbyMode");
         $method_isShutdown_32 = (class$org$quartz$core$RemotableQuartzScheduler != null ? class$org$quartz$core$RemotableQuartzScheduler : (class$org$quartz$core$RemotableQuartzScheduler = class$("org.quartz.core.RemotableQuartzScheduler"))).getMethod("isShutdown");
         $method_numJobsExecuted_33 = (class$org$quartz$core$RemotableQuartzScheduler != null ? class$org$quartz$core$RemotableQuartzScheduler : (class$org$quartz$core$RemotableQuartzScheduler = class$("org.quartz.core.RemotableQuartzScheduler"))).getMethod("numJobsExecuted");
         $method_pauseAll_34 = (class$org$quartz$core$RemotableQuartzScheduler != null ? class$org$quartz$core$RemotableQuartzScheduler : (class$org$quartz$core$RemotableQuartzScheduler = class$("org.quartz.core.RemotableQuartzScheduler"))).getMethod("pauseAll");
         $method_pauseJob_35 = (class$org$quartz$core$RemotableQuartzScheduler != null ? class$org$quartz$core$RemotableQuartzScheduler : (class$org$quartz$core$RemotableQuartzScheduler = class$("org.quartz.core.RemotableQuartzScheduler"))).getMethod("pauseJob", class$org$quartz$JobKey != null ? class$org$quartz$JobKey : (class$org$quartz$JobKey = class$("org.quartz.JobKey")));
         $method_pauseJobs_36 = (class$org$quartz$core$RemotableQuartzScheduler != null ? class$org$quartz$core$RemotableQuartzScheduler : (class$org$quartz$core$RemotableQuartzScheduler = class$("org.quartz.core.RemotableQuartzScheduler"))).getMethod("pauseJobs", class$org$quartz$impl$matchers$GroupMatcher != null ? class$org$quartz$impl$matchers$GroupMatcher : (class$org$quartz$impl$matchers$GroupMatcher = class$("org.quartz.impl.matchers.GroupMatcher")));
         $method_pauseTrigger_37 = (class$org$quartz$core$RemotableQuartzScheduler != null ? class$org$quartz$core$RemotableQuartzScheduler : (class$org$quartz$core$RemotableQuartzScheduler = class$("org.quartz.core.RemotableQuartzScheduler"))).getMethod("pauseTrigger", class$org$quartz$TriggerKey != null ? class$org$quartz$TriggerKey : (class$org$quartz$TriggerKey = class$("org.quartz.TriggerKey")));
         $method_pauseTriggers_38 = (class$org$quartz$core$RemotableQuartzScheduler != null ? class$org$quartz$core$RemotableQuartzScheduler : (class$org$quartz$core$RemotableQuartzScheduler = class$("org.quartz.core.RemotableQuartzScheduler"))).getMethod("pauseTriggers", class$org$quartz$impl$matchers$GroupMatcher != null ? class$org$quartz$impl$matchers$GroupMatcher : (class$org$quartz$impl$matchers$GroupMatcher = class$("org.quartz.impl.matchers.GroupMatcher")));
         $method_rescheduleJob_39 = (class$org$quartz$core$RemotableQuartzScheduler != null ? class$org$quartz$core$RemotableQuartzScheduler : (class$org$quartz$core$RemotableQuartzScheduler = class$("org.quartz.core.RemotableQuartzScheduler"))).getMethod("rescheduleJob", class$org$quartz$TriggerKey != null ? class$org$quartz$TriggerKey : (class$org$quartz$TriggerKey = class$("org.quartz.TriggerKey")), class$org$quartz$Trigger != null ? class$org$quartz$Trigger : (class$org$quartz$Trigger = class$("org.quartz.Trigger")));
         $method_resumeAll_40 = (class$org$quartz$core$RemotableQuartzScheduler != null ? class$org$quartz$core$RemotableQuartzScheduler : (class$org$quartz$core$RemotableQuartzScheduler = class$("org.quartz.core.RemotableQuartzScheduler"))).getMethod("resumeAll");
         $method_resumeJob_41 = (class$org$quartz$core$RemotableQuartzScheduler != null ? class$org$quartz$core$RemotableQuartzScheduler : (class$org$quartz$core$RemotableQuartzScheduler = class$("org.quartz.core.RemotableQuartzScheduler"))).getMethod("resumeJob", class$org$quartz$JobKey != null ? class$org$quartz$JobKey : (class$org$quartz$JobKey = class$("org.quartz.JobKey")));
         $method_resumeJobs_42 = (class$org$quartz$core$RemotableQuartzScheduler != null ? class$org$quartz$core$RemotableQuartzScheduler : (class$org$quartz$core$RemotableQuartzScheduler = class$("org.quartz.core.RemotableQuartzScheduler"))).getMethod("resumeJobs", class$org$quartz$impl$matchers$GroupMatcher != null ? class$org$quartz$impl$matchers$GroupMatcher : (class$org$quartz$impl$matchers$GroupMatcher = class$("org.quartz.impl.matchers.GroupMatcher")));
         $method_resumeTrigger_43 = (class$org$quartz$core$RemotableQuartzScheduler != null ? class$org$quartz$core$RemotableQuartzScheduler : (class$org$quartz$core$RemotableQuartzScheduler = class$("org.quartz.core.RemotableQuartzScheduler"))).getMethod("resumeTrigger", class$org$quartz$TriggerKey != null ? class$org$quartz$TriggerKey : (class$org$quartz$TriggerKey = class$("org.quartz.TriggerKey")));
         $method_resumeTriggers_44 = (class$org$quartz$core$RemotableQuartzScheduler != null ? class$org$quartz$core$RemotableQuartzScheduler : (class$org$quartz$core$RemotableQuartzScheduler = class$("org.quartz.core.RemotableQuartzScheduler"))).getMethod("resumeTriggers", class$org$quartz$impl$matchers$GroupMatcher != null ? class$org$quartz$impl$matchers$GroupMatcher : (class$org$quartz$impl$matchers$GroupMatcher = class$("org.quartz.impl.matchers.GroupMatcher")));
         $method_runningSince_45 = (class$org$quartz$core$RemotableQuartzScheduler != null ? class$org$quartz$core$RemotableQuartzScheduler : (class$org$quartz$core$RemotableQuartzScheduler = class$("org.quartz.core.RemotableQuartzScheduler"))).getMethod("runningSince");
         $method_scheduleJob_46 = (class$org$quartz$core$RemotableQuartzScheduler != null ? class$org$quartz$core$RemotableQuartzScheduler : (class$org$quartz$core$RemotableQuartzScheduler = class$("org.quartz.core.RemotableQuartzScheduler"))).getMethod("scheduleJob", class$org$quartz$JobDetail != null ? class$org$quartz$JobDetail : (class$org$quartz$JobDetail = class$("org.quartz.JobDetail")), class$java$util$Set != null ? class$java$util$Set : (class$java$util$Set = class$("java.util.Set")), Boolean.TYPE);
         $method_scheduleJob_47 = (class$org$quartz$core$RemotableQuartzScheduler != null ? class$org$quartz$core$RemotableQuartzScheduler : (class$org$quartz$core$RemotableQuartzScheduler = class$("org.quartz.core.RemotableQuartzScheduler"))).getMethod("scheduleJob", class$org$quartz$JobDetail != null ? class$org$quartz$JobDetail : (class$org$quartz$JobDetail = class$("org.quartz.JobDetail")), class$org$quartz$Trigger != null ? class$org$quartz$Trigger : (class$org$quartz$Trigger = class$("org.quartz.Trigger")));
         $method_scheduleJob_48 = (class$org$quartz$core$RemotableQuartzScheduler != null ? class$org$quartz$core$RemotableQuartzScheduler : (class$org$quartz$core$RemotableQuartzScheduler = class$("org.quartz.core.RemotableQuartzScheduler"))).getMethod("scheduleJob", class$org$quartz$Trigger != null ? class$org$quartz$Trigger : (class$org$quartz$Trigger = class$("org.quartz.Trigger")));
         $method_scheduleJobs_49 = (class$org$quartz$core$RemotableQuartzScheduler != null ? class$org$quartz$core$RemotableQuartzScheduler : (class$org$quartz$core$RemotableQuartzScheduler = class$("org.quartz.core.RemotableQuartzScheduler"))).getMethod("scheduleJobs", class$java$util$Map != null ? class$java$util$Map : (class$java$util$Map = class$("java.util.Map")), Boolean.TYPE);
         $method_shutdown_50 = (class$org$quartz$core$RemotableQuartzScheduler != null ? class$org$quartz$core$RemotableQuartzScheduler : (class$org$quartz$core$RemotableQuartzScheduler = class$("org.quartz.core.RemotableQuartzScheduler"))).getMethod("shutdown");
         $method_shutdown_51 = (class$org$quartz$core$RemotableQuartzScheduler != null ? class$org$quartz$core$RemotableQuartzScheduler : (class$org$quartz$core$RemotableQuartzScheduler = class$("org.quartz.core.RemotableQuartzScheduler"))).getMethod("shutdown", Boolean.TYPE);
         $method_standby_52 = (class$org$quartz$core$RemotableQuartzScheduler != null ? class$org$quartz$core$RemotableQuartzScheduler : (class$org$quartz$core$RemotableQuartzScheduler = class$("org.quartz.core.RemotableQuartzScheduler"))).getMethod("standby");
         $method_start_53 = (class$org$quartz$core$RemotableQuartzScheduler != null ? class$org$quartz$core$RemotableQuartzScheduler : (class$org$quartz$core$RemotableQuartzScheduler = class$("org.quartz.core.RemotableQuartzScheduler"))).getMethod("start");
         $method_startDelayed_54 = (class$org$quartz$core$RemotableQuartzScheduler != null ? class$org$quartz$core$RemotableQuartzScheduler : (class$org$quartz$core$RemotableQuartzScheduler = class$("org.quartz.core.RemotableQuartzScheduler"))).getMethod("startDelayed", Integer.TYPE);
         $method_supportsPersistence_55 = (class$org$quartz$core$RemotableQuartzScheduler != null ? class$org$quartz$core$RemotableQuartzScheduler : (class$org$quartz$core$RemotableQuartzScheduler = class$("org.quartz.core.RemotableQuartzScheduler"))).getMethod("supportsPersistence");
         $method_triggerJob_56 = (class$org$quartz$core$RemotableQuartzScheduler != null ? class$org$quartz$core$RemotableQuartzScheduler : (class$org$quartz$core$RemotableQuartzScheduler = class$("org.quartz.core.RemotableQuartzScheduler"))).getMethod("triggerJob", class$org$quartz$JobKey != null ? class$org$quartz$JobKey : (class$org$quartz$JobKey = class$("org.quartz.JobKey")), class$org$quartz$JobDataMap != null ? class$org$quartz$JobDataMap : (class$org$quartz$JobDataMap = class$("org.quartz.JobDataMap")));
         $method_triggerJob_57 = (class$org$quartz$core$RemotableQuartzScheduler != null ? class$org$quartz$core$RemotableQuartzScheduler : (class$org$quartz$core$RemotableQuartzScheduler = class$("org.quartz.core.RemotableQuartzScheduler"))).getMethod("triggerJob", class$org$quartz$spi$OperableTrigger != null ? class$org$quartz$spi$OperableTrigger : (class$org$quartz$spi$OperableTrigger = class$("org.quartz.spi.OperableTrigger")));
         $method_unscheduleJob_58 = (class$org$quartz$core$RemotableQuartzScheduler != null ? class$org$quartz$core$RemotableQuartzScheduler : (class$org$quartz$core$RemotableQuartzScheduler = class$("org.quartz.core.RemotableQuartzScheduler"))).getMethod("unscheduleJob", class$org$quartz$TriggerKey != null ? class$org$quartz$TriggerKey : (class$org$quartz$TriggerKey = class$("org.quartz.TriggerKey")));
         $method_unscheduleJobs_59 = (class$org$quartz$core$RemotableQuartzScheduler != null ? class$org$quartz$core$RemotableQuartzScheduler : (class$org$quartz$core$RemotableQuartzScheduler = class$("org.quartz.core.RemotableQuartzScheduler"))).getMethod("unscheduleJobs", class$java$util$List != null ? class$java$util$List : (class$java$util$List = class$("java.util.List")));
      } catch (NoSuchMethodException var0) {
         throw new NoSuchMethodError("stub class initialization failed");
      }
   }

   public QuartzScheduler_Stub(RemoteRef var1) {
      super(var1);
   }

   public void addCalendar(String var1, Calendar var2, boolean var3, boolean var4) throws RemoteException, SchedulerException {
      try {
         super.ref.invoke(this, $method_addCalendar_0, new Object[]{var1, var2, var3 ? Boolean.TRUE : Boolean.FALSE, var4 ? Boolean.TRUE : Boolean.FALSE}, 8855052307177792680L);
      } catch (RuntimeException var6) {
         throw var6;
      } catch (RemoteException var7) {
         throw var7;
      } catch (SchedulerException var8) {
         throw var8;
      } catch (Exception var9) {
         throw new UnexpectedException("undeclared checked exception", var9);
      }
   }

   public void addJob(JobDetail var1, boolean var2) throws RemoteException, SchedulerException {
      try {
         super.ref.invoke(this, $method_addJob_1, new Object[]{var1, var2 ? Boolean.TRUE : Boolean.FALSE}, -7729650160006632870L);
      } catch (RuntimeException var4) {
         throw var4;
      } catch (RemoteException var5) {
         throw var5;
      } catch (SchedulerException var6) {
         throw var6;
      } catch (Exception var7) {
         throw new UnexpectedException("undeclared checked exception", var7);
      }
   }

   public void addJob(JobDetail var1, boolean var2, boolean var3) throws RemoteException, SchedulerException {
      try {
         super.ref.invoke(this, $method_addJob_2, new Object[]{var1, var2 ? Boolean.TRUE : Boolean.FALSE, var3 ? Boolean.TRUE : Boolean.FALSE}, 1129496936115180762L);
      } catch (RuntimeException var5) {
         throw var5;
      } catch (RemoteException var6) {
         throw var6;
      } catch (SchedulerException var7) {
         throw var7;
      } catch (Exception var8) {
         throw new UnexpectedException("undeclared checked exception", var8);
      }
   }

   public boolean checkExists(JobKey var1) throws RemoteException, SchedulerException {
      try {
         Object var2 = super.ref.invoke(this, $method_checkExists_3, new Object[]{var1}, -5409554300431077992L);
         return (Boolean)var2;
      } catch (RuntimeException var3) {
         throw var3;
      } catch (RemoteException var4) {
         throw var4;
      } catch (SchedulerException var5) {
         throw var5;
      } catch (Exception var6) {
         throw new UnexpectedException("undeclared checked exception", var6);
      }
   }

   public boolean checkExists(TriggerKey var1) throws RemoteException, SchedulerException {
      try {
         Object var2 = super.ref.invoke(this, $method_checkExists_4, new Object[]{var1}, 57742068790347073L);
         return (Boolean)var2;
      } catch (RuntimeException var3) {
         throw var3;
      } catch (RemoteException var4) {
         throw var4;
      } catch (SchedulerException var5) {
         throw var5;
      } catch (Exception var6) {
         throw new UnexpectedException("undeclared checked exception", var6);
      }
   }

   // $FF: synthetic method
   static Class class$(String var0) {
      try {
         return Class.forName(var0);
      } catch (ClassNotFoundException var2) {
         throw new NoClassDefFoundError(var2.getMessage());
      }
   }

   public void clear() throws RemoteException, SchedulerException {
      try {
         super.ref.invoke(this, $method_clear_5, (Object[])null, -7475254351993695499L);
      } catch (RuntimeException var2) {
         throw var2;
      } catch (RemoteException var3) {
         throw var3;
      } catch (SchedulerException var4) {
         throw var4;
      } catch (Exception var5) {
         throw new UnexpectedException("undeclared checked exception", var5);
      }
   }

   public boolean deleteCalendar(String var1) throws RemoteException, SchedulerException {
      try {
         Object var2 = super.ref.invoke(this, $method_deleteCalendar_6, new Object[]{var1}, 4621799193941576495L);
         return (Boolean)var2;
      } catch (RuntimeException var3) {
         throw var3;
      } catch (RemoteException var4) {
         throw var4;
      } catch (SchedulerException var5) {
         throw var5;
      } catch (Exception var6) {
         throw new UnexpectedException("undeclared checked exception", var6);
      }
   }

   public boolean deleteJob(JobKey var1) throws RemoteException, SchedulerException {
      try {
         Object var2 = super.ref.invoke(this, $method_deleteJob_7, new Object[]{var1}, -3057293324488607018L);
         return (Boolean)var2;
      } catch (RuntimeException var3) {
         throw var3;
      } catch (RemoteException var4) {
         throw var4;
      } catch (SchedulerException var5) {
         throw var5;
      } catch (Exception var6) {
         throw new UnexpectedException("undeclared checked exception", var6);
      }
   }

   public boolean deleteJobs(List var1) throws RemoteException, SchedulerException {
      try {
         Object var2 = super.ref.invoke(this, $method_deleteJobs_8, new Object[]{var1}, 7613446947728959209L);
         return (Boolean)var2;
      } catch (RuntimeException var3) {
         throw var3;
      } catch (RemoteException var4) {
         throw var4;
      } catch (SchedulerException var5) {
         throw var5;
      } catch (Exception var6) {
         throw new UnexpectedException("undeclared checked exception", var6);
      }
   }

   public Calendar getCalendar(String var1) throws RemoteException, SchedulerException {
      try {
         Object var2 = super.ref.invoke(this, $method_getCalendar_9, new Object[]{var1}, 7476199188467217146L);
         return (Calendar)var2;
      } catch (RuntimeException var3) {
         throw var3;
      } catch (RemoteException var4) {
         throw var4;
      } catch (SchedulerException var5) {
         throw var5;
      } catch (Exception var6) {
         throw new UnexpectedException("undeclared checked exception", var6);
      }
   }

   public List getCalendarNames() throws RemoteException, SchedulerException {
      try {
         Object var1 = super.ref.invoke(this, $method_getCalendarNames_10, (Object[])null, -4042711865985645589L);
         return (List)var1;
      } catch (RuntimeException var2) {
         throw var2;
      } catch (RemoteException var3) {
         throw var3;
      } catch (SchedulerException var4) {
         throw var4;
      } catch (Exception var5) {
         throw new UnexpectedException("undeclared checked exception", var5);
      }
   }

   public List getCurrentlyExecutingJobs() throws RemoteException, SchedulerException {
      try {
         Object var1 = super.ref.invoke(this, $method_getCurrentlyExecutingJobs_11, (Object[])null, 5767551841304860517L);
         return (List)var1;
      } catch (RuntimeException var2) {
         throw var2;
      } catch (RemoteException var3) {
         throw var3;
      } catch (SchedulerException var4) {
         throw var4;
      } catch (Exception var5) {
         throw new UnexpectedException("undeclared checked exception", var5);
      }
   }

   public JobDetail getJobDetail(JobKey var1) throws RemoteException, SchedulerException {
      try {
         Object var2 = super.ref.invoke(this, $method_getJobDetail_12, new Object[]{var1}, -5890147489272798972L);
         return (JobDetail)var2;
      } catch (RuntimeException var3) {
         throw var3;
      } catch (RemoteException var4) {
         throw var4;
      } catch (SchedulerException var5) {
         throw var5;
      } catch (Exception var6) {
         throw new UnexpectedException("undeclared checked exception", var6);
      }
   }

   public List getJobGroupNames() throws RemoteException, SchedulerException {
      try {
         Object var1 = super.ref.invoke(this, $method_getJobGroupNames_13, (Object[])null, -8455486033245212483L);
         return (List)var1;
      } catch (RuntimeException var2) {
         throw var2;
      } catch (RemoteException var3) {
         throw var3;
      } catch (SchedulerException var4) {
         throw var4;
      } catch (Exception var5) {
         throw new UnexpectedException("undeclared checked exception", var5);
      }
   }

   public Set getJobKeys(GroupMatcher var1) throws RemoteException, SchedulerException {
      try {
         Object var2 = super.ref.invoke(this, $method_getJobKeys_14, new Object[]{var1}, 5516129892023529995L);
         return (Set)var2;
      } catch (RuntimeException var3) {
         throw var3;
      } catch (RemoteException var4) {
         throw var4;
      } catch (SchedulerException var5) {
         throw var5;
      } catch (Exception var6) {
         throw new UnexpectedException("undeclared checked exception", var6);
      }
   }

   public Class getJobStoreClass() throws RemoteException {
      try {
         Object var1 = super.ref.invoke(this, $method_getJobStoreClass_15, (Object[])null, 6705397913929502666L);
         return (Class)var1;
      } catch (RuntimeException var2) {
         throw var2;
      } catch (RemoteException var3) {
         throw var3;
      } catch (Exception var4) {
         throw new UnexpectedException("undeclared checked exception", var4);
      }
   }

   public Set getPausedTriggerGroups() throws RemoteException, SchedulerException {
      try {
         Object var1 = super.ref.invoke(this, $method_getPausedTriggerGroups_16, (Object[])null, -3055688590637594456L);
         return (Set)var1;
      } catch (RuntimeException var2) {
         throw var2;
      } catch (RemoteException var3) {
         throw var3;
      } catch (SchedulerException var4) {
         throw var4;
      } catch (Exception var5) {
         throw new UnexpectedException("undeclared checked exception", var5);
      }
   }

   public SchedulerContext getSchedulerContext() throws RemoteException, SchedulerException {
      try {
         Object var1 = super.ref.invoke(this, $method_getSchedulerContext_17, (Object[])null, 2814359591403475563L);
         return (SchedulerContext)var1;
      } catch (RuntimeException var2) {
         throw var2;
      } catch (RemoteException var3) {
         throw var3;
      } catch (SchedulerException var4) {
         throw var4;
      } catch (Exception var5) {
         throw new UnexpectedException("undeclared checked exception", var5);
      }
   }

   public String getSchedulerInstanceId() throws RemoteException {
      try {
         Object var1 = super.ref.invoke(this, $method_getSchedulerInstanceId_18, (Object[])null, -2454925768252868567L);
         return (String)var1;
      } catch (RuntimeException var2) {
         throw var2;
      } catch (RemoteException var3) {
         throw var3;
      } catch (Exception var4) {
         throw new UnexpectedException("undeclared checked exception", var4);
      }
   }

   public String getSchedulerName() throws RemoteException {
      try {
         Object var1 = super.ref.invoke(this, $method_getSchedulerName_19, (Object[])null, 1038196595245667445L);
         return (String)var1;
      } catch (RuntimeException var2) {
         throw var2;
      } catch (RemoteException var3) {
         throw var3;
      } catch (Exception var4) {
         throw new UnexpectedException("undeclared checked exception", var4);
      }
   }

   public Class getThreadPoolClass() throws RemoteException {
      try {
         Object var1 = super.ref.invoke(this, $method_getThreadPoolClass_20, (Object[])null, -706336661940287388L);
         return (Class)var1;
      } catch (RuntimeException var2) {
         throw var2;
      } catch (RemoteException var3) {
         throw var3;
      } catch (Exception var4) {
         throw new UnexpectedException("undeclared checked exception", var4);
      }
   }

   public int getThreadPoolSize() throws RemoteException {
      try {
         Object var1 = super.ref.invoke(this, $method_getThreadPoolSize_21, (Object[])null, 6528392066641712137L);
         return (Integer)var1;
      } catch (RuntimeException var2) {
         throw var2;
      } catch (RemoteException var3) {
         throw var3;
      } catch (Exception var4) {
         throw new UnexpectedException("undeclared checked exception", var4);
      }
   }

   public Trigger getTrigger(TriggerKey var1) throws RemoteException, SchedulerException {
      try {
         Object var2 = super.ref.invoke(this, $method_getTrigger_22, new Object[]{var1}, -8135458059745415503L);
         return (Trigger)var2;
      } catch (RuntimeException var3) {
         throw var3;
      } catch (RemoteException var4) {
         throw var4;
      } catch (SchedulerException var5) {
         throw var5;
      } catch (Exception var6) {
         throw new UnexpectedException("undeclared checked exception", var6);
      }
   }

   public List getTriggerGroupNames() throws RemoteException, SchedulerException {
      try {
         Object var1 = super.ref.invoke(this, $method_getTriggerGroupNames_23, (Object[])null, -1425625447055098000L);
         return (List)var1;
      } catch (RuntimeException var2) {
         throw var2;
      } catch (RemoteException var3) {
         throw var3;
      } catch (SchedulerException var4) {
         throw var4;
      } catch (Exception var5) {
         throw new UnexpectedException("undeclared checked exception", var5);
      }
   }

   public Set getTriggerKeys(GroupMatcher var1) throws RemoteException, SchedulerException {
      try {
         Object var2 = super.ref.invoke(this, $method_getTriggerKeys_24, new Object[]{var1}, -833881061725726505L);
         return (Set)var2;
      } catch (RuntimeException var3) {
         throw var3;
      } catch (RemoteException var4) {
         throw var4;
      } catch (SchedulerException var5) {
         throw var5;
      } catch (Exception var6) {
         throw new UnexpectedException("undeclared checked exception", var6);
      }
   }

   public Trigger.TriggerState getTriggerState(TriggerKey var1) throws RemoteException, SchedulerException {
      try {
         Object var2 = super.ref.invoke(this, $method_getTriggerState_25, new Object[]{var1}, -5299675517853200699L);
         return (Trigger.TriggerState)var2;
      } catch (RuntimeException var3) {
         throw var3;
      } catch (RemoteException var4) {
         throw var4;
      } catch (SchedulerException var5) {
         throw var5;
      } catch (Exception var6) {
         throw new UnexpectedException("undeclared checked exception", var6);
      }
   }

   public List getTriggersOfJob(JobKey var1) throws RemoteException, SchedulerException {
      try {
         Object var2 = super.ref.invoke(this, $method_getTriggersOfJob_26, new Object[]{var1}, 4987568461050139134L);
         return (List)var2;
      } catch (RuntimeException var3) {
         throw var3;
      } catch (RemoteException var4) {
         throw var4;
      } catch (SchedulerException var5) {
         throw var5;
      } catch (Exception var6) {
         throw new UnexpectedException("undeclared checked exception", var6);
      }
   }

   public String getVersion() throws RemoteException {
      try {
         Object var1 = super.ref.invoke(this, $method_getVersion_27, (Object[])null, -8081107751519807347L);
         return (String)var1;
      } catch (RuntimeException var2) {
         throw var2;
      } catch (RemoteException var3) {
         throw var3;
      } catch (Exception var4) {
         throw new UnexpectedException("undeclared checked exception", var4);
      }
   }

   public boolean interrupt(String var1) throws RemoteException, UnableToInterruptJobException {
      try {
         Object var2 = super.ref.invoke(this, $method_interrupt_28, new Object[]{var1}, 256262298724115780L);
         return (Boolean)var2;
      } catch (RuntimeException var3) {
         throw var3;
      } catch (RemoteException var4) {
         throw var4;
      } catch (UnableToInterruptJobException var5) {
         throw var5;
      } catch (Exception var6) {
         throw new UnexpectedException("undeclared checked exception", var6);
      }
   }

   public boolean interrupt(JobKey var1) throws RemoteException, UnableToInterruptJobException {
      try {
         Object var2 = super.ref.invoke(this, $method_interrupt_29, new Object[]{var1}, -4185636327079289011L);
         return (Boolean)var2;
      } catch (RuntimeException var3) {
         throw var3;
      } catch (RemoteException var4) {
         throw var4;
      } catch (UnableToInterruptJobException var5) {
         throw var5;
      } catch (Exception var6) {
         throw new UnexpectedException("undeclared checked exception", var6);
      }
   }

   public boolean isClustered() throws RemoteException {
      try {
         Object var1 = super.ref.invoke(this, $method_isClustered_30, (Object[])null, 8772462407279794129L);
         return (Boolean)var1;
      } catch (RuntimeException var2) {
         throw var2;
      } catch (RemoteException var3) {
         throw var3;
      } catch (Exception var4) {
         throw new UnexpectedException("undeclared checked exception", var4);
      }
   }

   public boolean isInStandbyMode() throws RemoteException {
      try {
         Object var1 = super.ref.invoke(this, $method_isInStandbyMode_31, (Object[])null, 809977841435240287L);
         return (Boolean)var1;
      } catch (RuntimeException var2) {
         throw var2;
      } catch (RemoteException var3) {
         throw var3;
      } catch (Exception var4) {
         throw new UnexpectedException("undeclared checked exception", var4);
      }
   }

   public boolean isShutdown() throws RemoteException {
      try {
         Object var1 = super.ref.invoke(this, $method_isShutdown_32, (Object[])null, 6424449119484905518L);
         return (Boolean)var1;
      } catch (RuntimeException var2) {
         throw var2;
      } catch (RemoteException var3) {
         throw var3;
      } catch (Exception var4) {
         throw new UnexpectedException("undeclared checked exception", var4);
      }
   }

   public int numJobsExecuted() throws RemoteException {
      try {
         Object var1 = super.ref.invoke(this, $method_numJobsExecuted_33, (Object[])null, 3699847707830503805L);
         return (Integer)var1;
      } catch (RuntimeException var2) {
         throw var2;
      } catch (RemoteException var3) {
         throw var3;
      } catch (Exception var4) {
         throw new UnexpectedException("undeclared checked exception", var4);
      }
   }

   public void pauseAll() throws RemoteException, SchedulerException {
      try {
         super.ref.invoke(this, $method_pauseAll_34, (Object[])null, 5457255371237476599L);
      } catch (RuntimeException var2) {
         throw var2;
      } catch (RemoteException var3) {
         throw var3;
      } catch (SchedulerException var4) {
         throw var4;
      } catch (Exception var5) {
         throw new UnexpectedException("undeclared checked exception", var5);
      }
   }

   public void pauseJob(JobKey var1) throws RemoteException, SchedulerException {
      try {
         super.ref.invoke(this, $method_pauseJob_35, new Object[]{var1}, 8209397623379863913L);
      } catch (RuntimeException var3) {
         throw var3;
      } catch (RemoteException var4) {
         throw var4;
      } catch (SchedulerException var5) {
         throw var5;
      } catch (Exception var6) {
         throw new UnexpectedException("undeclared checked exception", var6);
      }
   }

   public void pauseJobs(GroupMatcher var1) throws RemoteException, SchedulerException {
      try {
         super.ref.invoke(this, $method_pauseJobs_36, new Object[]{var1}, 8348393716035813534L);
      } catch (RuntimeException var3) {
         throw var3;
      } catch (RemoteException var4) {
         throw var4;
      } catch (SchedulerException var5) {
         throw var5;
      } catch (Exception var6) {
         throw new UnexpectedException("undeclared checked exception", var6);
      }
   }

   public void pauseTrigger(TriggerKey var1) throws RemoteException, SchedulerException {
      try {
         super.ref.invoke(this, $method_pauseTrigger_37, new Object[]{var1}, -1556555911706012384L);
      } catch (RuntimeException var3) {
         throw var3;
      } catch (RemoteException var4) {
         throw var4;
      } catch (SchedulerException var5) {
         throw var5;
      } catch (Exception var6) {
         throw new UnexpectedException("undeclared checked exception", var6);
      }
   }

   public void pauseTriggers(GroupMatcher var1) throws RemoteException, SchedulerException {
      try {
         super.ref.invoke(this, $method_pauseTriggers_38, new Object[]{var1}, -7673129639132463315L);
      } catch (RuntimeException var3) {
         throw var3;
      } catch (RemoteException var4) {
         throw var4;
      } catch (SchedulerException var5) {
         throw var5;
      } catch (Exception var6) {
         throw new UnexpectedException("undeclared checked exception", var6);
      }
   }

   public Date rescheduleJob(TriggerKey var1, Trigger var2) throws RemoteException, SchedulerException {
      try {
         Object var3 = super.ref.invoke(this, $method_rescheduleJob_39, new Object[]{var1, var2}, -6542935860087805349L);
         return (Date)var3;
      } catch (RuntimeException var4) {
         throw var4;
      } catch (RemoteException var5) {
         throw var5;
      } catch (SchedulerException var6) {
         throw var6;
      } catch (Exception var7) {
         throw new UnexpectedException("undeclared checked exception", var7);
      }
   }

   public void resumeAll() throws RemoteException, SchedulerException {
      try {
         super.ref.invoke(this, $method_resumeAll_40, (Object[])null, 6544465639644633234L);
      } catch (RuntimeException var2) {
         throw var2;
      } catch (RemoteException var3) {
         throw var3;
      } catch (SchedulerException var4) {
         throw var4;
      } catch (Exception var5) {
         throw new UnexpectedException("undeclared checked exception", var5);
      }
   }

   public void resumeJob(JobKey var1) throws RemoteException, SchedulerException {
      try {
         super.ref.invoke(this, $method_resumeJob_41, new Object[]{var1}, 85405606979760311L);
      } catch (RuntimeException var3) {
         throw var3;
      } catch (RemoteException var4) {
         throw var4;
      } catch (SchedulerException var5) {
         throw var5;
      } catch (Exception var6) {
         throw new UnexpectedException("undeclared checked exception", var6);
      }
   }

   public void resumeJobs(GroupMatcher var1) throws RemoteException, SchedulerException {
      try {
         super.ref.invoke(this, $method_resumeJobs_42, new Object[]{var1}, 7080691189565323939L);
      } catch (RuntimeException var3) {
         throw var3;
      } catch (RemoteException var4) {
         throw var4;
      } catch (SchedulerException var5) {
         throw var5;
      } catch (Exception var6) {
         throw new UnexpectedException("undeclared checked exception", var6);
      }
   }

   public void resumeTrigger(TriggerKey var1) throws RemoteException, SchedulerException {
      try {
         super.ref.invoke(this, $method_resumeTrigger_43, new Object[]{var1}, 1103652291697918174L);
      } catch (RuntimeException var3) {
         throw var3;
      } catch (RemoteException var4) {
         throw var4;
      } catch (SchedulerException var5) {
         throw var5;
      } catch (Exception var6) {
         throw new UnexpectedException("undeclared checked exception", var6);
      }
   }

   public void resumeTriggers(GroupMatcher var1) throws RemoteException, SchedulerException {
      try {
         super.ref.invoke(this, $method_resumeTriggers_44, new Object[]{var1}, 316892067472367515L);
      } catch (RuntimeException var3) {
         throw var3;
      } catch (RemoteException var4) {
         throw var4;
      } catch (SchedulerException var5) {
         throw var5;
      } catch (Exception var6) {
         throw new UnexpectedException("undeclared checked exception", var6);
      }
   }

   public Date runningSince() throws RemoteException {
      try {
         Object var1 = super.ref.invoke(this, $method_runningSince_45, (Object[])null, -1739625058868381113L);
         return (Date)var1;
      } catch (RuntimeException var2) {
         throw var2;
      } catch (RemoteException var3) {
         throw var3;
      } catch (Exception var4) {
         throw new UnexpectedException("undeclared checked exception", var4);
      }
   }

   public void scheduleJob(JobDetail var1, Set var2, boolean var3) throws RemoteException, SchedulerException {
      try {
         super.ref.invoke(this, $method_scheduleJob_46, new Object[]{var1, var2, var3 ? Boolean.TRUE : Boolean.FALSE}, -2860300690822357486L);
      } catch (RuntimeException var5) {
         throw var5;
      } catch (RemoteException var6) {
         throw var6;
      } catch (SchedulerException var7) {
         throw var7;
      } catch (Exception var8) {
         throw new UnexpectedException("undeclared checked exception", var8);
      }
   }

   public Date scheduleJob(JobDetail var1, Trigger var2) throws RemoteException, SchedulerException {
      try {
         Object var3 = super.ref.invoke(this, $method_scheduleJob_47, new Object[]{var1, var2}, 4944457543332629245L);
         return (Date)var3;
      } catch (RuntimeException var4) {
         throw var4;
      } catch (RemoteException var5) {
         throw var5;
      } catch (SchedulerException var6) {
         throw var6;
      } catch (Exception var7) {
         throw new UnexpectedException("undeclared checked exception", var7);
      }
   }

   public Date scheduleJob(Trigger var1) throws RemoteException, SchedulerException {
      try {
         Object var2 = super.ref.invoke(this, $method_scheduleJob_48, new Object[]{var1}, -6865148385642356285L);
         return (Date)var2;
      } catch (RuntimeException var3) {
         throw var3;
      } catch (RemoteException var4) {
         throw var4;
      } catch (SchedulerException var5) {
         throw var5;
      } catch (Exception var6) {
         throw new UnexpectedException("undeclared checked exception", var6);
      }
   }

   public void scheduleJobs(Map var1, boolean var2) throws RemoteException, SchedulerException {
      try {
         super.ref.invoke(this, $method_scheduleJobs_49, new Object[]{var1, var2 ? Boolean.TRUE : Boolean.FALSE}, 2404438458719160003L);
      } catch (RuntimeException var4) {
         throw var4;
      } catch (RemoteException var5) {
         throw var5;
      } catch (SchedulerException var6) {
         throw var6;
      } catch (Exception var7) {
         throw new UnexpectedException("undeclared checked exception", var7);
      }
   }

   public void shutdown() throws RemoteException {
      try {
         super.ref.invoke(this, $method_shutdown_50, (Object[])null, -7207851917985848402L);
      } catch (RuntimeException var2) {
         throw var2;
      } catch (RemoteException var3) {
         throw var3;
      } catch (Exception var4) {
         throw new UnexpectedException("undeclared checked exception", var4);
      }
   }

   public void shutdown(boolean var1) throws RemoteException {
      try {
         super.ref.invoke(this, $method_shutdown_51, new Object[]{var1 ? Boolean.TRUE : Boolean.FALSE}, -7158426071079062438L);
      } catch (RuntimeException var3) {
         throw var3;
      } catch (RemoteException var4) {
         throw var4;
      } catch (Exception var5) {
         throw new UnexpectedException("undeclared checked exception", var5);
      }
   }

   public void standby() throws RemoteException {
      try {
         super.ref.invoke(this, $method_standby_52, (Object[])null, 7161048918451732526L);
      } catch (RuntimeException var2) {
         throw var2;
      } catch (RemoteException var3) {
         throw var3;
      } catch (Exception var4) {
         throw new UnexpectedException("undeclared checked exception", var4);
      }
   }

   public void start() throws RemoteException, SchedulerException {
      try {
         super.ref.invoke(this, $method_start_53, (Object[])null, -8025343665958530775L);
      } catch (RuntimeException var2) {
         throw var2;
      } catch (RemoteException var3) {
         throw var3;
      } catch (SchedulerException var4) {
         throw var4;
      } catch (Exception var5) {
         throw new UnexpectedException("undeclared checked exception", var5);
      }
   }

   public void startDelayed(int var1) throws RemoteException, SchedulerException {
      try {
         super.ref.invoke(this, $method_startDelayed_54, new Object[]{new Integer(var1)}, -1476976461109028800L);
      } catch (RuntimeException var3) {
         throw var3;
      } catch (RemoteException var4) {
         throw var4;
      } catch (SchedulerException var5) {
         throw var5;
      } catch (Exception var6) {
         throw new UnexpectedException("undeclared checked exception", var6);
      }
   }

   public boolean supportsPersistence() throws RemoteException {
      try {
         Object var1 = super.ref.invoke(this, $method_supportsPersistence_55, (Object[])null, -5767630451452602400L);
         return (Boolean)var1;
      } catch (RuntimeException var2) {
         throw var2;
      } catch (RemoteException var3) {
         throw var3;
      } catch (Exception var4) {
         throw new UnexpectedException("undeclared checked exception", var4);
      }
   }

   public void triggerJob(JobKey var1, JobDataMap var2) throws RemoteException, SchedulerException {
      try {
         super.ref.invoke(this, $method_triggerJob_56, new Object[]{var1, var2}, -1585175841511357332L);
      } catch (RuntimeException var4) {
         throw var4;
      } catch (RemoteException var5) {
         throw var5;
      } catch (SchedulerException var6) {
         throw var6;
      } catch (Exception var7) {
         throw new UnexpectedException("undeclared checked exception", var7);
      }
   }

   public void triggerJob(OperableTrigger var1) throws RemoteException, SchedulerException {
      try {
         super.ref.invoke(this, $method_triggerJob_57, new Object[]{var1}, 5598451830209081494L);
      } catch (RuntimeException var3) {
         throw var3;
      } catch (RemoteException var4) {
         throw var4;
      } catch (SchedulerException var5) {
         throw var5;
      } catch (Exception var6) {
         throw new UnexpectedException("undeclared checked exception", var6);
      }
   }

   public boolean unscheduleJob(TriggerKey var1) throws RemoteException, SchedulerException {
      try {
         Object var2 = super.ref.invoke(this, $method_unscheduleJob_58, new Object[]{var1}, -4592142908438852383L);
         return (Boolean)var2;
      } catch (RuntimeException var3) {
         throw var3;
      } catch (RemoteException var4) {
         throw var4;
      } catch (SchedulerException var5) {
         throw var5;
      } catch (Exception var6) {
         throw new UnexpectedException("undeclared checked exception", var6);
      }
   }

   public boolean unscheduleJobs(List var1) throws RemoteException, SchedulerException {
      try {
         Object var2 = super.ref.invoke(this, $method_unscheduleJobs_59, new Object[]{var1}, 1385849655203364760L);
         return (Boolean)var2;
      } catch (RuntimeException var3) {
         throw var3;
      } catch (RemoteException var4) {
         throw var4;
      } catch (SchedulerException var5) {
         throw var5;
      } catch (Exception var6) {
         throw new UnexpectedException("undeclared checked exception", var6);
      }
   }
}
