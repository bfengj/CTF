package org.quartz.impl.jdbcjobstore;

import java.io.IOException;
import java.sql.Connection;
import java.sql.SQLException;
import java.util.List;
import java.util.Set;
import org.quartz.Calendar;
import org.quartz.JobDataMap;
import org.quartz.JobDetail;
import org.quartz.JobKey;
import org.quartz.JobPersistenceException;
import org.quartz.TriggerKey;
import org.quartz.impl.matchers.GroupMatcher;
import org.quartz.spi.ClassLoadHelper;
import org.quartz.spi.OperableTrigger;
import org.quartz.utils.Key;
import org.slf4j.Logger;

public interface DriverDelegate {
   void initialize(Logger var1, String var2, String var3, String var4, ClassLoadHelper var5, boolean var6, String var7) throws NoSuchDelegateException;

   int updateTriggerStatesFromOtherStates(Connection var1, String var2, String var3, String var4) throws SQLException;

   List<TriggerKey> selectMisfiredTriggers(Connection var1, long var2) throws SQLException;

   List<TriggerKey> selectMisfiredTriggersInState(Connection var1, String var2, long var3) throws SQLException;

   boolean hasMisfiredTriggersInState(Connection var1, String var2, long var3, int var5, List<TriggerKey> var6) throws SQLException;

   int countMisfiredTriggersInState(Connection var1, String var2, long var3) throws SQLException;

   List<TriggerKey> selectMisfiredTriggersInGroupInState(Connection var1, String var2, String var3, long var4) throws SQLException;

   List<OperableTrigger> selectTriggersForRecoveringJobs(Connection var1) throws SQLException, IOException, ClassNotFoundException;

   int deleteFiredTriggers(Connection var1) throws SQLException;

   int deleteFiredTriggers(Connection var1, String var2) throws SQLException;

   int insertJobDetail(Connection var1, JobDetail var2) throws IOException, SQLException;

   int updateJobDetail(Connection var1, JobDetail var2) throws IOException, SQLException;

   List<TriggerKey> selectTriggerKeysForJob(Connection var1, JobKey var2) throws SQLException;

   int deleteJobDetail(Connection var1, JobKey var2) throws SQLException;

   boolean isJobNonConcurrent(Connection var1, JobKey var2) throws SQLException;

   boolean jobExists(Connection var1, JobKey var2) throws SQLException;

   int updateJobData(Connection var1, JobDetail var2) throws IOException, SQLException;

   JobDetail selectJobDetail(Connection var1, JobKey var2, ClassLoadHelper var3) throws ClassNotFoundException, IOException, SQLException;

   int selectNumJobs(Connection var1) throws SQLException;

   List<String> selectJobGroups(Connection var1) throws SQLException;

   Set<JobKey> selectJobsInGroup(Connection var1, GroupMatcher<JobKey> var2) throws SQLException;

   int insertTrigger(Connection var1, OperableTrigger var2, String var3, JobDetail var4) throws SQLException, IOException;

   int updateTrigger(Connection var1, OperableTrigger var2, String var3, JobDetail var4) throws SQLException, IOException;

   boolean triggerExists(Connection var1, TriggerKey var2) throws SQLException;

   int updateTriggerState(Connection var1, TriggerKey var2, String var3) throws SQLException;

   int updateTriggerStateFromOtherState(Connection var1, TriggerKey var2, String var3, String var4) throws SQLException;

   int updateTriggerStateFromOtherStates(Connection var1, TriggerKey var2, String var3, String var4, String var5, String var6) throws SQLException;

   int updateTriggerGroupStateFromOtherStates(Connection var1, GroupMatcher<TriggerKey> var2, String var3, String var4, String var5, String var6) throws SQLException;

   int updateTriggerGroupStateFromOtherState(Connection var1, GroupMatcher<TriggerKey> var2, String var3, String var4) throws SQLException;

   int updateTriggerStatesForJob(Connection var1, JobKey var2, String var3) throws SQLException;

   int updateTriggerStatesForJobFromOtherState(Connection var1, JobKey var2, String var3, String var4) throws SQLException;

   int deleteTrigger(Connection var1, TriggerKey var2) throws SQLException;

   int selectNumTriggersForJob(Connection var1, JobKey var2) throws SQLException;

   JobDetail selectJobForTrigger(Connection var1, ClassLoadHelper var2, TriggerKey var3) throws ClassNotFoundException, SQLException;

   JobDetail selectJobForTrigger(Connection var1, ClassLoadHelper var2, TriggerKey var3, boolean var4) throws ClassNotFoundException, SQLException;

   List<OperableTrigger> selectTriggersForJob(Connection var1, JobKey var2) throws SQLException, ClassNotFoundException, IOException, JobPersistenceException;

   List<OperableTrigger> selectTriggersForCalendar(Connection var1, String var2) throws SQLException, ClassNotFoundException, IOException, JobPersistenceException;

   OperableTrigger selectTrigger(Connection var1, TriggerKey var2) throws SQLException, ClassNotFoundException, IOException, JobPersistenceException;

   JobDataMap selectTriggerJobDataMap(Connection var1, String var2, String var3) throws SQLException, ClassNotFoundException, IOException;

   String selectTriggerState(Connection var1, TriggerKey var2) throws SQLException;

   TriggerStatus selectTriggerStatus(Connection var1, TriggerKey var2) throws SQLException;

   int selectNumTriggers(Connection var1) throws SQLException;

   List<String> selectTriggerGroups(Connection var1) throws SQLException;

   List<String> selectTriggerGroups(Connection var1, GroupMatcher<TriggerKey> var2) throws SQLException;

   Set<TriggerKey> selectTriggersInGroup(Connection var1, GroupMatcher<TriggerKey> var2) throws SQLException;

   List<TriggerKey> selectTriggersInState(Connection var1, String var2) throws SQLException;

   int insertPausedTriggerGroup(Connection var1, String var2) throws SQLException;

   int deletePausedTriggerGroup(Connection var1, String var2) throws SQLException;

   int deletePausedTriggerGroup(Connection var1, GroupMatcher<TriggerKey> var2) throws SQLException;

   int deleteAllPausedTriggerGroups(Connection var1) throws SQLException;

   boolean isTriggerGroupPaused(Connection var1, String var2) throws SQLException;

   Set<String> selectPausedTriggerGroups(Connection var1) throws SQLException;

   boolean isExistingTriggerGroup(Connection var1, String var2) throws SQLException;

   int insertCalendar(Connection var1, String var2, Calendar var3) throws IOException, SQLException;

   int updateCalendar(Connection var1, String var2, Calendar var3) throws IOException, SQLException;

   boolean calendarExists(Connection var1, String var2) throws SQLException;

   Calendar selectCalendar(Connection var1, String var2) throws ClassNotFoundException, IOException, SQLException;

   boolean calendarIsReferenced(Connection var1, String var2) throws SQLException;

   int deleteCalendar(Connection var1, String var2) throws SQLException;

   int selectNumCalendars(Connection var1) throws SQLException;

   List<String> selectCalendars(Connection var1) throws SQLException;

   /** @deprecated */
   long selectNextFireTime(Connection var1) throws SQLException;

   Key<?> selectTriggerForFireTime(Connection var1, long var2) throws SQLException;

   /** @deprecated */
   List<TriggerKey> selectTriggerToAcquire(Connection var1, long var2, long var4) throws SQLException;

   List<TriggerKey> selectTriggerToAcquire(Connection var1, long var2, long var4, int var6) throws SQLException;

   int insertFiredTrigger(Connection var1, OperableTrigger var2, String var3, JobDetail var4) throws SQLException;

   int updateFiredTrigger(Connection var1, OperableTrigger var2, String var3, JobDetail var4) throws SQLException;

   List<FiredTriggerRecord> selectFiredTriggerRecords(Connection var1, String var2, String var3) throws SQLException;

   List<FiredTriggerRecord> selectFiredTriggerRecordsByJob(Connection var1, String var2, String var3) throws SQLException;

   List<FiredTriggerRecord> selectInstancesFiredTriggerRecords(Connection var1, String var2) throws SQLException;

   Set<String> selectFiredTriggerInstanceNames(Connection var1) throws SQLException;

   int deleteFiredTrigger(Connection var1, String var2) throws SQLException;

   int selectJobExecutionCount(Connection var1, JobKey var2) throws SQLException;

   int insertSchedulerState(Connection var1, String var2, long var3, long var5) throws SQLException;

   int deleteSchedulerState(Connection var1, String var2) throws SQLException;

   int updateSchedulerState(Connection var1, String var2, long var3) throws SQLException;

   List<SchedulerStateRecord> selectSchedulerStateRecords(Connection var1, String var2) throws SQLException;

   void clearData(Connection var1) throws SQLException;
}
