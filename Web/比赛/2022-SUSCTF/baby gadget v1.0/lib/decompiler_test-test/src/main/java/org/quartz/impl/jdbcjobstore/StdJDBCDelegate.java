package org.quartz.impl.jdbcjobstore;

import java.io.ByteArrayInputStream;
import java.io.ByteArrayOutputStream;
import java.io.IOException;
import java.io.InputStream;
import java.io.NotSerializableException;
import java.io.ObjectInputStream;
import java.io.ObjectOutputStream;
import java.math.BigDecimal;
import java.sql.Blob;
import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;
import java.util.Date;
import java.util.HashMap;
import java.util.HashSet;
import java.util.Iterator;
import java.util.LinkedList;
import java.util.List;
import java.util.Map;
import java.util.Properties;
import java.util.Set;
import java.util.Map.Entry;
import org.quartz.Calendar;
import org.quartz.Job;
import org.quartz.JobDataMap;
import org.quartz.JobDetail;
import org.quartz.JobKey;
import org.quartz.JobPersistenceException;
import org.quartz.TriggerBuilder;
import org.quartz.TriggerKey;
import org.quartz.impl.JobDetailImpl;
import org.quartz.impl.matchers.GroupMatcher;
import org.quartz.impl.matchers.StringMatcher;
import org.quartz.impl.triggers.SimpleTriggerImpl;
import org.quartz.spi.ClassLoadHelper;
import org.quartz.spi.OperableTrigger;
import org.slf4j.Logger;

public class StdJDBCDelegate implements DriverDelegate, StdJDBCConstants {
   protected Logger logger = null;
   protected String tablePrefix = "QRTZ_";
   protected String instanceId;
   protected String schedName;
   protected boolean useProperties;
   protected ClassLoadHelper classLoadHelper;
   protected List<TriggerPersistenceDelegate> triggerPersistenceDelegates = new LinkedList();
   private String schedNameLiteral = null;

   public void initialize(Logger logger, String tablePrefix, String schedName, String instanceId, ClassLoadHelper classLoadHelper, boolean useProperties, String initString) throws NoSuchDelegateException {
      this.logger = logger;
      this.tablePrefix = tablePrefix;
      this.schedName = schedName;
      this.instanceId = instanceId;
      this.useProperties = useProperties;
      this.classLoadHelper = classLoadHelper;
      this.addDefaultTriggerPersistenceDelegates();
      if (initString != null) {
         String[] settings = initString.split("\\|");
         String[] arr$ = settings;
         int len$ = settings.length;

         for(int i$ = 0; i$ < len$; ++i$) {
            String setting = arr$[i$];
            String[] parts = setting.split("=");
            String name = parts[0];
            if (parts.length != 1 && parts[1] != null && !parts[1].equals("")) {
               if (!name.equals("triggerPersistenceDelegateClasses")) {
                  throw new NoSuchDelegateException("Unknown setting: '" + name + "'");
               }

               String[] trigDelegates = parts[1].split(",");
               String[] arr$ = trigDelegates;
               int len$ = trigDelegates.length;

               for(int i$ = 0; i$ < len$; ++i$) {
                  String trigDelClassName = arr$[i$];

                  try {
                     Class<?> trigDelClass = classLoadHelper.loadClass(trigDelClassName);
                     this.addTriggerPersistenceDelegate((TriggerPersistenceDelegate)trigDelClass.newInstance());
                  } catch (Exception var21) {
                     throw new NoSuchDelegateException("Error instantiating TriggerPersistenceDelegate of type: " + trigDelClassName, var21);
                  }
               }
            }
         }

      }
   }

   protected void addDefaultTriggerPersistenceDelegates() {
      this.addTriggerPersistenceDelegate(new SimpleTriggerPersistenceDelegate());
      this.addTriggerPersistenceDelegate(new CronTriggerPersistenceDelegate());
      this.addTriggerPersistenceDelegate(new CalendarIntervalTriggerPersistenceDelegate());
      this.addTriggerPersistenceDelegate(new DailyTimeIntervalTriggerPersistenceDelegate());
   }

   protected boolean canUseProperties() {
      return this.useProperties;
   }

   public void addTriggerPersistenceDelegate(TriggerPersistenceDelegate delegate) {
      this.logger.debug("Adding TriggerPersistenceDelegate of type: " + delegate.getClass().getCanonicalName());
      delegate.initialize(this.tablePrefix, this.schedName);
      this.triggerPersistenceDelegates.add(delegate);
   }

   public TriggerPersistenceDelegate findTriggerPersistenceDelegate(OperableTrigger trigger) {
      Iterator i$ = this.triggerPersistenceDelegates.iterator();

      TriggerPersistenceDelegate delegate;
      do {
         if (!i$.hasNext()) {
            return null;
         }

         delegate = (TriggerPersistenceDelegate)i$.next();
      } while(!delegate.canHandleTriggerType(trigger));

      return delegate;
   }

   public TriggerPersistenceDelegate findTriggerPersistenceDelegate(String discriminator) {
      Iterator i$ = this.triggerPersistenceDelegates.iterator();

      TriggerPersistenceDelegate delegate;
      do {
         if (!i$.hasNext()) {
            return null;
         }

         delegate = (TriggerPersistenceDelegate)i$.next();
      } while(!delegate.getHandledTriggerTypeDiscriminator().equals(discriminator));

      return delegate;
   }

   public int updateTriggerStatesFromOtherStates(Connection conn, String newState, String oldState1, String oldState2) throws SQLException {
      PreparedStatement ps = null;

      int var6;
      try {
         ps = conn.prepareStatement(this.rtp("UPDATE {0}TRIGGERS SET TRIGGER_STATE = ? WHERE SCHED_NAME = {1} AND (TRIGGER_STATE = ? OR TRIGGER_STATE = ?)"));
         ps.setString(1, newState);
         ps.setString(2, oldState1);
         ps.setString(3, oldState2);
         var6 = ps.executeUpdate();
      } finally {
         closeStatement(ps);
      }

      return var6;
   }

   public List<TriggerKey> selectMisfiredTriggers(Connection conn, long ts) throws SQLException {
      PreparedStatement ps = null;
      ResultSet rs = null;

      try {
         ps = conn.prepareStatement(this.rtp("SELECT * FROM {0}TRIGGERS WHERE SCHED_NAME = {1} AND NOT (MISFIRE_INSTR = -1) AND NEXT_FIRE_TIME < ? ORDER BY NEXT_FIRE_TIME ASC, PRIORITY DESC"));
         ps.setBigDecimal(1, new BigDecimal(String.valueOf(ts)));
         rs = ps.executeQuery();
         LinkedList list = new LinkedList();

         while(rs.next()) {
            String triggerName = rs.getString("TRIGGER_NAME");
            String groupName = rs.getString("TRIGGER_GROUP");
            list.add(TriggerKey.triggerKey(triggerName, groupName));
         }

         LinkedList var12 = list;
         return var12;
      } finally {
         closeResultSet(rs);
         closeStatement(ps);
      }
   }

   public List<TriggerKey> selectTriggersInState(Connection conn, String state) throws SQLException {
      PreparedStatement ps = null;
      ResultSet rs = null;

      try {
         ps = conn.prepareStatement(this.rtp("SELECT TRIGGER_NAME, TRIGGER_GROUP FROM {0}TRIGGERS WHERE SCHED_NAME = {1} AND TRIGGER_STATE = ?"));
         ps.setString(1, state);
         rs = ps.executeQuery();
         LinkedList list = new LinkedList();

         while(rs.next()) {
            list.add(TriggerKey.triggerKey(rs.getString(1), rs.getString(2)));
         }

         LinkedList var6 = list;
         return var6;
      } finally {
         closeResultSet(rs);
         closeStatement(ps);
      }
   }

   public List<TriggerKey> selectMisfiredTriggersInState(Connection conn, String state, long ts) throws SQLException {
      PreparedStatement ps = null;
      ResultSet rs = null;

      try {
         ps = conn.prepareStatement(this.rtp("SELECT TRIGGER_NAME, TRIGGER_GROUP FROM {0}TRIGGERS WHERE SCHED_NAME = {1} AND NOT (MISFIRE_INSTR = -1) AND NEXT_FIRE_TIME < ? AND TRIGGER_STATE = ? ORDER BY NEXT_FIRE_TIME ASC, PRIORITY DESC"));
         ps.setBigDecimal(1, new BigDecimal(String.valueOf(ts)));
         ps.setString(2, state);
         rs = ps.executeQuery();
         LinkedList list = new LinkedList();

         while(rs.next()) {
            String triggerName = rs.getString("TRIGGER_NAME");
            String groupName = rs.getString("TRIGGER_GROUP");
            list.add(TriggerKey.triggerKey(triggerName, groupName));
         }

         LinkedList var13 = list;
         return var13;
      } finally {
         closeResultSet(rs);
         closeStatement(ps);
      }
   }

   public boolean hasMisfiredTriggersInState(Connection conn, String state1, long ts, int count, List<TriggerKey> resultList) throws SQLException {
      PreparedStatement ps = null;
      ResultSet rs = null;

      try {
         ps = conn.prepareStatement(this.rtp("SELECT TRIGGER_NAME, TRIGGER_GROUP FROM {0}TRIGGERS WHERE SCHED_NAME = {1} AND NOT (MISFIRE_INSTR = -1) AND NEXT_FIRE_TIME < ? AND TRIGGER_STATE = ? ORDER BY NEXT_FIRE_TIME ASC, PRIORITY DESC"));
         ps.setBigDecimal(1, new BigDecimal(String.valueOf(ts)));
         ps.setString(2, state1);
         rs = ps.executeQuery();
         boolean hasReachedLimit = false;

         while(rs.next() && !hasReachedLimit) {
            if (resultList.size() == count) {
               hasReachedLimit = true;
            } else {
               String triggerName = rs.getString("TRIGGER_NAME");
               String groupName = rs.getString("TRIGGER_GROUP");
               resultList.add(TriggerKey.triggerKey(triggerName, groupName));
            }
         }

         boolean var15 = hasReachedLimit;
         return var15;
      } finally {
         closeResultSet(rs);
         closeStatement(ps);
      }
   }

   public int countMisfiredTriggersInState(Connection conn, String state1, long ts) throws SQLException {
      PreparedStatement ps = null;
      ResultSet rs = null;

      int var7;
      try {
         ps = conn.prepareStatement(this.rtp("SELECT COUNT(TRIGGER_NAME) FROM {0}TRIGGERS WHERE SCHED_NAME = {1} AND NOT (MISFIRE_INSTR = -1) AND NEXT_FIRE_TIME < ? AND TRIGGER_STATE = ?"));
         ps.setBigDecimal(1, new BigDecimal(String.valueOf(ts)));
         ps.setString(2, state1);
         rs = ps.executeQuery();
         if (!rs.next()) {
            throw new SQLException("No misfired trigger count returned.");
         }

         var7 = rs.getInt(1);
      } finally {
         closeResultSet(rs);
         closeStatement(ps);
      }

      return var7;
   }

   public List<TriggerKey> selectMisfiredTriggersInGroupInState(Connection conn, String groupName, String state, long ts) throws SQLException {
      PreparedStatement ps = null;
      ResultSet rs = null;

      try {
         ps = conn.prepareStatement(this.rtp("SELECT TRIGGER_NAME FROM {0}TRIGGERS WHERE SCHED_NAME = {1} AND NOT (MISFIRE_INSTR = -1) AND NEXT_FIRE_TIME < ? AND TRIGGER_GROUP = ? AND TRIGGER_STATE = ? ORDER BY NEXT_FIRE_TIME ASC, PRIORITY DESC"));
         ps.setBigDecimal(1, new BigDecimal(String.valueOf(ts)));
         ps.setString(2, groupName);
         ps.setString(3, state);
         rs = ps.executeQuery();
         LinkedList list = new LinkedList();

         while(rs.next()) {
            String triggerName = rs.getString("TRIGGER_NAME");
            list.add(TriggerKey.triggerKey(triggerName, groupName));
         }

         LinkedList var13 = list;
         return var13;
      } finally {
         closeResultSet(rs);
         closeStatement(ps);
      }
   }

   public List<OperableTrigger> selectTriggersForRecoveringJobs(Connection conn) throws SQLException, IOException, ClassNotFoundException {
      PreparedStatement ps = null;
      ResultSet rs = null;

      try {
         ps = conn.prepareStatement(this.rtp("SELECT * FROM {0}FIRED_TRIGGERS WHERE SCHED_NAME = {1} AND INSTANCE_NAME = ? AND REQUESTS_RECOVERY = ?"));
         ps.setString(1, this.instanceId);
         this.setBoolean(ps, 2, true);
         rs = ps.executeQuery();
         long dumId = System.currentTimeMillis();
         LinkedList list = new LinkedList();

         while(rs.next()) {
            String jobName = rs.getString("JOB_NAME");
            String jobGroup = rs.getString("JOB_GROUP");
            String trigName = rs.getString("TRIGGER_NAME");
            String trigGroup = rs.getString("TRIGGER_GROUP");
            long firedTime = rs.getLong("FIRED_TIME");
            long scheduledTime = rs.getLong("SCHED_TIME");
            int priority = rs.getInt("PRIORITY");
            SimpleTriggerImpl rcvryTrig = new SimpleTriggerImpl("recover_" + this.instanceId + "_" + dumId++, "RECOVERING_JOBS", new Date(scheduledTime));
            rcvryTrig.setJobName(jobName);
            rcvryTrig.setJobGroup(jobGroup);
            rcvryTrig.setPriority(priority);
            rcvryTrig.setMisfireInstruction(-1);
            JobDataMap jd = this.selectTriggerJobDataMap(conn, trigName, trigGroup);
            jd.put("QRTZ_FAILED_JOB_ORIG_TRIGGER_NAME", trigName);
            jd.put("QRTZ_FAILED_JOB_ORIG_TRIGGER_GROUP", trigGroup);
            jd.put("QRTZ_FAILED_JOB_ORIG_TRIGGER_FIRETIME_IN_MILLISECONDS_AS_STRING", String.valueOf(firedTime));
            jd.put("QRTZ_FAILED_JOB_ORIG_TRIGGER_SCHEDULED_FIRETIME_IN_MILLISECONDS_AS_STRING", String.valueOf(scheduledTime));
            rcvryTrig.setJobDataMap(jd);
            list.add(rcvryTrig);
         }

         LinkedList var21 = list;
         return var21;
      } finally {
         closeResultSet(rs);
         closeStatement(ps);
      }
   }

   public int deleteFiredTriggers(Connection conn) throws SQLException {
      PreparedStatement ps = null;

      int var3;
      try {
         ps = conn.prepareStatement(this.rtp("DELETE FROM {0}FIRED_TRIGGERS WHERE SCHED_NAME = {1}"));
         var3 = ps.executeUpdate();
      } finally {
         closeStatement(ps);
      }

      return var3;
   }

   public int deleteFiredTriggers(Connection conn, String theInstanceId) throws SQLException {
      PreparedStatement ps = null;

      int var4;
      try {
         ps = conn.prepareStatement(this.rtp("DELETE FROM {0}FIRED_TRIGGERS WHERE SCHED_NAME = {1} AND INSTANCE_NAME = ?"));
         ps.setString(1, theInstanceId);
         var4 = ps.executeUpdate();
      } finally {
         closeStatement(ps);
      }

      return var4;
   }

   public void clearData(Connection conn) throws SQLException {
      PreparedStatement ps = null;

      try {
         ps = conn.prepareStatement(this.rtp("DELETE FROM {0}SIMPLE_TRIGGERS  WHERE SCHED_NAME = {1}"));
         ps.executeUpdate();
         ps.close();
         ps = conn.prepareStatement(this.rtp("DELETE FROM {0}SIMPROP_TRIGGERS  WHERE SCHED_NAME = {1}"));
         ps.executeUpdate();
         ps.close();
         ps = conn.prepareStatement(this.rtp("DELETE FROM {0}CRON_TRIGGERS WHERE SCHED_NAME = {1}"));
         ps.executeUpdate();
         ps.close();
         ps = conn.prepareStatement(this.rtp("DELETE FROM {0}BLOB_TRIGGERS WHERE SCHED_NAME = {1}"));
         ps.executeUpdate();
         ps.close();
         ps = conn.prepareStatement(this.rtp("DELETE FROM {0}TRIGGERS WHERE SCHED_NAME = {1}"));
         ps.executeUpdate();
         ps.close();
         ps = conn.prepareStatement(this.rtp("DELETE FROM {0}JOB_DETAILS WHERE SCHED_NAME = {1}"));
         ps.executeUpdate();
         ps.close();
         ps = conn.prepareStatement(this.rtp("DELETE FROM {0}CALENDARS WHERE SCHED_NAME = {1}"));
         ps.executeUpdate();
         ps.close();
         ps = conn.prepareStatement(this.rtp("DELETE FROM {0}PAUSED_TRIGGER_GRPS WHERE SCHED_NAME = {1}"));
         ps.executeUpdate();
      } finally {
         closeStatement(ps);
      }

   }

   public int insertJobDetail(Connection conn, JobDetail job) throws IOException, SQLException {
      ByteArrayOutputStream baos = this.serializeJobData(job.getJobDataMap());
      PreparedStatement ps = null;
      boolean var5 = false;

      int insertResult;
      try {
         ps = conn.prepareStatement(this.rtp("INSERT INTO {0}JOB_DETAILS (SCHED_NAME, JOB_NAME, JOB_GROUP, DESCRIPTION, JOB_CLASS_NAME, IS_DURABLE, IS_NONCONCURRENT, IS_UPDATE_DATA, REQUESTS_RECOVERY, JOB_DATA)  VALUES({1}, ?, ?, ?, ?, ?, ?, ?, ?, ?)"));
         ps.setString(1, job.getKey().getName());
         ps.setString(2, job.getKey().getGroup());
         ps.setString(3, job.getDescription());
         ps.setString(4, job.getJobClass().getName());
         this.setBoolean(ps, 5, job.isDurable());
         this.setBoolean(ps, 6, job.isConcurrentExectionDisallowed());
         this.setBoolean(ps, 7, job.isPersistJobDataAfterExecution());
         this.setBoolean(ps, 8, job.requestsRecovery());
         this.setBytes(ps, 9, baos);
         insertResult = ps.executeUpdate();
      } finally {
         closeStatement(ps);
      }

      return insertResult;
   }

   public int updateJobDetail(Connection conn, JobDetail job) throws IOException, SQLException {
      ByteArrayOutputStream baos = this.serializeJobData(job.getJobDataMap());
      PreparedStatement ps = null;
      boolean var5 = false;

      int insertResult;
      try {
         ps = conn.prepareStatement(this.rtp("UPDATE {0}JOB_DETAILS SET DESCRIPTION = ?, JOB_CLASS_NAME = ?, IS_DURABLE = ?, IS_NONCONCURRENT = ?, IS_UPDATE_DATA = ?, REQUESTS_RECOVERY = ?, JOB_DATA = ?  WHERE SCHED_NAME = {1} AND JOB_NAME = ? AND JOB_GROUP = ?"));
         ps.setString(1, job.getDescription());
         ps.setString(2, job.getJobClass().getName());
         this.setBoolean(ps, 3, job.isDurable());
         this.setBoolean(ps, 4, job.isConcurrentExectionDisallowed());
         this.setBoolean(ps, 5, job.isPersistJobDataAfterExecution());
         this.setBoolean(ps, 6, job.requestsRecovery());
         this.setBytes(ps, 7, baos);
         ps.setString(8, job.getKey().getName());
         ps.setString(9, job.getKey().getGroup());
         insertResult = ps.executeUpdate();
      } finally {
         closeStatement(ps);
      }

      return insertResult;
   }

   public List<TriggerKey> selectTriggerKeysForJob(Connection conn, JobKey jobKey) throws SQLException {
      PreparedStatement ps = null;
      ResultSet rs = null;

      try {
         ps = conn.prepareStatement(this.rtp("SELECT TRIGGER_NAME, TRIGGER_GROUP FROM {0}TRIGGERS WHERE SCHED_NAME = {1} AND JOB_NAME = ? AND JOB_GROUP = ?"));
         ps.setString(1, jobKey.getName());
         ps.setString(2, jobKey.getGroup());
         rs = ps.executeQuery();
         LinkedList list = new LinkedList();

         while(rs.next()) {
            String trigName = rs.getString("TRIGGER_NAME");
            String trigGroup = rs.getString("TRIGGER_GROUP");
            list.add(TriggerKey.triggerKey(trigName, trigGroup));
         }

         LinkedList var11 = list;
         return var11;
      } finally {
         closeResultSet(rs);
         closeStatement(ps);
      }
   }

   public int deleteJobDetail(Connection conn, JobKey jobKey) throws SQLException {
      PreparedStatement ps = null;

      int var4;
      try {
         if (this.logger.isDebugEnabled()) {
            this.logger.debug("Deleting job: " + jobKey);
         }

         ps = conn.prepareStatement(this.rtp("DELETE FROM {0}JOB_DETAILS WHERE SCHED_NAME = {1} AND JOB_NAME = ? AND JOB_GROUP = ?"));
         ps.setString(1, jobKey.getName());
         ps.setString(2, jobKey.getGroup());
         var4 = ps.executeUpdate();
      } finally {
         closeStatement(ps);
      }

      return var4;
   }

   public boolean isJobNonConcurrent(Connection conn, JobKey jobKey) throws SQLException {
      PreparedStatement ps = null;
      ResultSet rs = null;

      boolean var5;
      try {
         ps = conn.prepareStatement(this.rtp("SELECT IS_NONCONCURRENT FROM {0}JOB_DETAILS WHERE SCHED_NAME = {1} AND JOB_NAME = ? AND JOB_GROUP = ?"));
         ps.setString(1, jobKey.getName());
         ps.setString(2, jobKey.getGroup());
         rs = ps.executeQuery();
         if (!rs.next()) {
            var5 = false;
            return var5;
         }

         var5 = this.getBoolean(rs, "IS_NONCONCURRENT");
      } finally {
         closeResultSet(rs);
         closeStatement(ps);
      }

      return var5;
   }

   public boolean jobExists(Connection conn, JobKey jobKey) throws SQLException {
      PreparedStatement ps = null;
      ResultSet rs = null;

      boolean var5;
      try {
         ps = conn.prepareStatement(this.rtp("SELECT JOB_NAME FROM {0}JOB_DETAILS WHERE SCHED_NAME = {1} AND JOB_NAME = ? AND JOB_GROUP = ?"));
         ps.setString(1, jobKey.getName());
         ps.setString(2, jobKey.getGroup());
         rs = ps.executeQuery();
         if (!rs.next()) {
            var5 = false;
            return var5;
         }

         var5 = true;
      } finally {
         closeResultSet(rs);
         closeStatement(ps);
      }

      return var5;
   }

   public int updateJobData(Connection conn, JobDetail job) throws IOException, SQLException {
      ByteArrayOutputStream baos = this.serializeJobData(job.getJobDataMap());
      PreparedStatement ps = null;

      int var5;
      try {
         ps = conn.prepareStatement(this.rtp("UPDATE {0}JOB_DETAILS SET JOB_DATA = ?  WHERE SCHED_NAME = {1} AND JOB_NAME = ? AND JOB_GROUP = ?"));
         this.setBytes(ps, 1, baos);
         ps.setString(2, job.getKey().getName());
         ps.setString(3, job.getKey().getGroup());
         var5 = ps.executeUpdate();
      } finally {
         closeStatement(ps);
      }

      return var5;
   }

   public JobDetail selectJobDetail(Connection conn, JobKey jobKey, ClassLoadHelper loadHelper) throws ClassNotFoundException, IOException, SQLException {
      PreparedStatement ps = null;
      ResultSet rs = null;

      JobDetailImpl var11;
      try {
         ps = conn.prepareStatement(this.rtp("SELECT * FROM {0}JOB_DETAILS WHERE SCHED_NAME = {1} AND JOB_NAME = ? AND JOB_GROUP = ?"));
         ps.setString(1, jobKey.getName());
         ps.setString(2, jobKey.getGroup());
         rs = ps.executeQuery();
         JobDetailImpl job = null;
         if (rs.next()) {
            job = new JobDetailImpl();
            job.setName(rs.getString("JOB_NAME"));
            job.setGroup(rs.getString("JOB_GROUP"));
            job.setDescription(rs.getString("DESCRIPTION"));
            job.setJobClass(loadHelper.loadClass(rs.getString("JOB_CLASS_NAME"), Job.class));
            job.setDurability(this.getBoolean(rs, "IS_DURABLE"));
            job.setRequestsRecovery(this.getBoolean(rs, "REQUESTS_RECOVERY"));
            Map<?, ?> map = null;
            if (this.canUseProperties()) {
               map = this.getMapFromProperties(rs);
            } else {
               map = (Map)this.getObjectFromBlob(rs, "JOB_DATA");
            }

            if (null != map) {
               job.setJobDataMap(new JobDataMap(map));
            }
         }

         var11 = job;
      } finally {
         closeResultSet(rs);
         closeStatement(ps);
      }

      return var11;
   }

   private Map<?, ?> getMapFromProperties(ResultSet rs) throws ClassNotFoundException, IOException, SQLException {
      InputStream is = (InputStream)this.getJobDataFromBlob(rs, "JOB_DATA");
      if (is == null) {
         return null;
      } else {
         Properties properties = new Properties();
         if (is != null) {
            try {
               properties.load(is);
            } finally {
               is.close();
            }
         }

         Map<?, ?> map = this.convertFromProperty(properties);
         return map;
      }
   }

   public int selectNumJobs(Connection conn) throws SQLException {
      PreparedStatement ps = null;
      ResultSet rs = null;

      int var5;
      try {
         int count = 0;
         ps = conn.prepareStatement(this.rtp("SELECT COUNT(JOB_NAME)  FROM {0}JOB_DETAILS WHERE SCHED_NAME = {1}"));
         rs = ps.executeQuery();
         if (rs.next()) {
            count = rs.getInt(1);
         }

         var5 = count;
      } finally {
         closeResultSet(rs);
         closeStatement(ps);
      }

      return var5;
   }

   public List<String> selectJobGroups(Connection conn) throws SQLException {
      PreparedStatement ps = null;
      ResultSet rs = null;

      try {
         ps = conn.prepareStatement(this.rtp("SELECT DISTINCT(JOB_GROUP) FROM {0}JOB_DETAILS WHERE SCHED_NAME = {1}"));
         rs = ps.executeQuery();
         LinkedList list = new LinkedList();

         while(rs.next()) {
            list.add(rs.getString(1));
         }

         LinkedList var5 = list;
         return var5;
      } finally {
         closeResultSet(rs);
         closeStatement(ps);
      }
   }

   public Set<JobKey> selectJobsInGroup(Connection conn, GroupMatcher<JobKey> matcher) throws SQLException {
      PreparedStatement ps = null;
      ResultSet rs = null;

      try {
         if (this.isMatcherEquals(matcher)) {
            ps = conn.prepareStatement(this.rtp("SELECT JOB_NAME, JOB_GROUP FROM {0}JOB_DETAILS WHERE SCHED_NAME = {1} AND JOB_GROUP = ?"));
            ps.setString(1, this.toSqlEqualsClause(matcher));
         } else {
            ps = conn.prepareStatement(this.rtp("SELECT JOB_NAME, JOB_GROUP FROM {0}JOB_DETAILS WHERE SCHED_NAME = {1} AND JOB_GROUP LIKE ?"));
            ps.setString(1, this.toSqlLikeClause(matcher));
         }

         rs = ps.executeQuery();
         LinkedList list = new LinkedList();

         while(rs.next()) {
            list.add(JobKey.jobKey(rs.getString(1), rs.getString(2)));
         }

         HashSet var6 = new HashSet(list);
         return var6;
      } finally {
         closeResultSet(rs);
         closeStatement(ps);
      }
   }

   protected boolean isMatcherEquals(GroupMatcher<?> matcher) {
      return matcher.getCompareWithOperator().equals(StringMatcher.StringOperatorName.EQUALS);
   }

   protected String toSqlEqualsClause(GroupMatcher<?> matcher) {
      return matcher.getCompareToValue();
   }

   protected String toSqlLikeClause(GroupMatcher<?> matcher) {
      String groupName;
      switch(matcher.getCompareWithOperator()) {
      case EQUALS:
         groupName = matcher.getCompareToValue();
         break;
      case CONTAINS:
         groupName = "%" + matcher.getCompareToValue() + "%";
         break;
      case ENDS_WITH:
         groupName = "%" + matcher.getCompareToValue();
         break;
      case STARTS_WITH:
         groupName = matcher.getCompareToValue() + "%";
         break;
      case ANYTHING:
         groupName = "%";
         break;
      default:
         throw new UnsupportedOperationException("Don't know how to translate " + matcher.getCompareWithOperator() + " into SQL");
      }

      return groupName;
   }

   public int insertTrigger(Connection conn, OperableTrigger trigger, String state, JobDetail jobDetail) throws SQLException, IOException {
      ByteArrayOutputStream baos = null;
      if (trigger.getJobDataMap().size() > 0) {
         baos = this.serializeJobData(trigger.getJobDataMap());
      }

      PreparedStatement ps = null;
      boolean var7 = false;

      int insertResult;
      try {
         ps = conn.prepareStatement(this.rtp("INSERT INTO {0}TRIGGERS (SCHED_NAME, TRIGGER_NAME, TRIGGER_GROUP, JOB_NAME, JOB_GROUP, DESCRIPTION, NEXT_FIRE_TIME, PREV_FIRE_TIME, TRIGGER_STATE, TRIGGER_TYPE, START_TIME, END_TIME, CALENDAR_NAME, MISFIRE_INSTR, JOB_DATA, PRIORITY)  VALUES({1}, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"));
         ps.setString(1, trigger.getKey().getName());
         ps.setString(2, trigger.getKey().getGroup());
         ps.setString(3, trigger.getJobKey().getName());
         ps.setString(4, trigger.getJobKey().getGroup());
         ps.setString(5, trigger.getDescription());
         if (trigger.getNextFireTime() != null) {
            ps.setBigDecimal(6, new BigDecimal(String.valueOf(trigger.getNextFireTime().getTime())));
         } else {
            ps.setBigDecimal(6, (BigDecimal)null);
         }

         long prevFireTime = -1L;
         if (trigger.getPreviousFireTime() != null) {
            prevFireTime = trigger.getPreviousFireTime().getTime();
         }

         ps.setBigDecimal(7, new BigDecimal(String.valueOf(prevFireTime)));
         ps.setString(8, state);
         TriggerPersistenceDelegate tDel = this.findTriggerPersistenceDelegate(trigger);
         String type = "BLOB";
         if (tDel != null) {
            type = tDel.getHandledTriggerTypeDiscriminator();
         }

         ps.setString(9, type);
         ps.setBigDecimal(10, new BigDecimal(String.valueOf(trigger.getStartTime().getTime())));
         long endTime = 0L;
         if (trigger.getEndTime() != null) {
            endTime = trigger.getEndTime().getTime();
         }

         ps.setBigDecimal(11, new BigDecimal(String.valueOf(endTime)));
         ps.setString(12, trigger.getCalendarName());
         ps.setInt(13, trigger.getMisfireInstruction());
         this.setBytes(ps, 14, baos);
         ps.setInt(15, trigger.getPriority());
         insertResult = ps.executeUpdate();
         if (tDel == null) {
            this.insertBlobTrigger(conn, trigger);
         } else {
            tDel.insertExtendedTriggerProperties(conn, trigger, state, jobDetail);
         }
      } finally {
         closeStatement(ps);
      }

      return insertResult;
   }

   public int insertBlobTrigger(Connection conn, OperableTrigger trigger) throws SQLException, IOException {
      PreparedStatement ps = null;
      ByteArrayOutputStream os = null;

      int var8;
      try {
         os = new ByteArrayOutputStream();
         ObjectOutputStream oos = new ObjectOutputStream(os);
         oos.writeObject(trigger);
         oos.close();
         byte[] buf = os.toByteArray();
         ByteArrayInputStream is = new ByteArrayInputStream(buf);
         ps = conn.prepareStatement(this.rtp("INSERT INTO {0}BLOB_TRIGGERS (SCHED_NAME, TRIGGER_NAME, TRIGGER_GROUP, BLOB_DATA)  VALUES({1}, ?, ?, ?)"));
         ps.setString(1, trigger.getKey().getName());
         ps.setString(2, trigger.getKey().getGroup());
         ps.setBinaryStream(3, is, buf.length);
         var8 = ps.executeUpdate();
      } finally {
         closeStatement(ps);
      }

      return var8;
   }

   public int updateTrigger(Connection conn, OperableTrigger trigger, String state, JobDetail jobDetail) throws SQLException, IOException {
      boolean updateJobData = trigger.getJobDataMap().isDirty();
      ByteArrayOutputStream baos = null;
      if (updateJobData && trigger.getJobDataMap().size() > 0) {
         baos = this.serializeJobData(trigger.getJobDataMap());
      }

      PreparedStatement ps = null;
      boolean var8 = false;

      int insertResult;
      try {
         if (updateJobData) {
            ps = conn.prepareStatement(this.rtp("UPDATE {0}TRIGGERS SET JOB_NAME = ?, JOB_GROUP = ?, DESCRIPTION = ?, NEXT_FIRE_TIME = ?, PREV_FIRE_TIME = ?, TRIGGER_STATE = ?, TRIGGER_TYPE = ?, START_TIME = ?, END_TIME = ?, CALENDAR_NAME = ?, MISFIRE_INSTR = ?, PRIORITY = ?, JOB_DATA = ? WHERE SCHED_NAME = {1} AND TRIGGER_NAME = ? AND TRIGGER_GROUP = ?"));
         } else {
            ps = conn.prepareStatement(this.rtp("UPDATE {0}TRIGGERS SET JOB_NAME = ?, JOB_GROUP = ?, DESCRIPTION = ?, NEXT_FIRE_TIME = ?, PREV_FIRE_TIME = ?, TRIGGER_STATE = ?, TRIGGER_TYPE = ?, START_TIME = ?, END_TIME = ?, CALENDAR_NAME = ?, MISFIRE_INSTR = ?, PRIORITY = ? WHERE SCHED_NAME = {1} AND TRIGGER_NAME = ? AND TRIGGER_GROUP = ?"));
         }

         ps.setString(1, trigger.getJobKey().getName());
         ps.setString(2, trigger.getJobKey().getGroup());
         ps.setString(3, trigger.getDescription());
         long nextFireTime = -1L;
         if (trigger.getNextFireTime() != null) {
            nextFireTime = trigger.getNextFireTime().getTime();
         }

         ps.setBigDecimal(4, new BigDecimal(String.valueOf(nextFireTime)));
         long prevFireTime = -1L;
         if (trigger.getPreviousFireTime() != null) {
            prevFireTime = trigger.getPreviousFireTime().getTime();
         }

         ps.setBigDecimal(5, new BigDecimal(String.valueOf(prevFireTime)));
         ps.setString(6, state);
         TriggerPersistenceDelegate tDel = this.findTriggerPersistenceDelegate(trigger);
         String type = "BLOB";
         if (tDel != null) {
            type = tDel.getHandledTriggerTypeDiscriminator();
         }

         ps.setString(7, type);
         ps.setBigDecimal(8, new BigDecimal(String.valueOf(trigger.getStartTime().getTime())));
         long endTime = 0L;
         if (trigger.getEndTime() != null) {
            endTime = trigger.getEndTime().getTime();
         }

         ps.setBigDecimal(9, new BigDecimal(String.valueOf(endTime)));
         ps.setString(10, trigger.getCalendarName());
         ps.setInt(11, trigger.getMisfireInstruction());
         ps.setInt(12, trigger.getPriority());
         if (updateJobData) {
            this.setBytes(ps, 13, baos);
            ps.setString(14, trigger.getKey().getName());
            ps.setString(15, trigger.getKey().getGroup());
         } else {
            ps.setString(13, trigger.getKey().getName());
            ps.setString(14, trigger.getKey().getGroup());
         }

         insertResult = ps.executeUpdate();
         if (tDel == null) {
            this.updateBlobTrigger(conn, trigger);
         } else {
            tDel.updateExtendedTriggerProperties(conn, trigger, state, jobDetail);
         }
      } finally {
         closeStatement(ps);
      }

      return insertResult;
   }

   public int updateBlobTrigger(Connection conn, OperableTrigger trigger) throws SQLException, IOException {
      PreparedStatement ps = null;
      ByteArrayOutputStream os = null;

      int var8;
      try {
         os = new ByteArrayOutputStream();
         ObjectOutputStream oos = new ObjectOutputStream(os);
         oos.writeObject(trigger);
         oos.close();
         byte[] buf = os.toByteArray();
         ByteArrayInputStream is = new ByteArrayInputStream(buf);
         ps = conn.prepareStatement(this.rtp("UPDATE {0}BLOB_TRIGGERS SET BLOB_DATA = ? WHERE SCHED_NAME = {1} AND TRIGGER_NAME = ? AND TRIGGER_GROUP = ?"));
         ps.setBinaryStream(1, is, buf.length);
         ps.setString(2, trigger.getKey().getName());
         ps.setString(3, trigger.getKey().getGroup());
         var8 = ps.executeUpdate();
      } finally {
         closeStatement(ps);
         if (os != null) {
            os.close();
         }

      }

      return var8;
   }

   public boolean triggerExists(Connection conn, TriggerKey triggerKey) throws SQLException {
      PreparedStatement ps = null;
      ResultSet rs = null;

      boolean var5;
      try {
         ps = conn.prepareStatement(this.rtp("SELECT TRIGGER_NAME FROM {0}TRIGGERS WHERE SCHED_NAME = {1} AND TRIGGER_NAME = ? AND TRIGGER_GROUP = ?"));
         ps.setString(1, triggerKey.getName());
         ps.setString(2, triggerKey.getGroup());
         rs = ps.executeQuery();
         if (rs.next()) {
            var5 = true;
            return var5;
         }

         var5 = false;
      } finally {
         closeResultSet(rs);
         closeStatement(ps);
      }

      return var5;
   }

   public int updateTriggerState(Connection conn, TriggerKey triggerKey, String state) throws SQLException {
      PreparedStatement ps = null;

      int var5;
      try {
         ps = conn.prepareStatement(this.rtp("UPDATE {0}TRIGGERS SET TRIGGER_STATE = ? WHERE SCHED_NAME = {1} AND TRIGGER_NAME = ? AND TRIGGER_GROUP = ?"));
         ps.setString(1, state);
         ps.setString(2, triggerKey.getName());
         ps.setString(3, triggerKey.getGroup());
         var5 = ps.executeUpdate();
      } finally {
         closeStatement(ps);
      }

      return var5;
   }

   public int updateTriggerStateFromOtherStates(Connection conn, TriggerKey triggerKey, String newState, String oldState1, String oldState2, String oldState3) throws SQLException {
      PreparedStatement ps = null;

      int var8;
      try {
         ps = conn.prepareStatement(this.rtp("UPDATE {0}TRIGGERS SET TRIGGER_STATE = ? WHERE SCHED_NAME = {1} AND TRIGGER_NAME = ? AND TRIGGER_GROUP = ? AND (TRIGGER_STATE = ? OR TRIGGER_STATE = ? OR TRIGGER_STATE = ?)"));
         ps.setString(1, newState);
         ps.setString(2, triggerKey.getName());
         ps.setString(3, triggerKey.getGroup());
         ps.setString(4, oldState1);
         ps.setString(5, oldState2);
         ps.setString(6, oldState3);
         var8 = ps.executeUpdate();
      } finally {
         closeStatement(ps);
      }

      return var8;
   }

   public int updateTriggerGroupStateFromOtherStates(Connection conn, GroupMatcher<TriggerKey> matcher, String newState, String oldState1, String oldState2, String oldState3) throws SQLException {
      PreparedStatement ps = null;

      int var8;
      try {
         ps = conn.prepareStatement(this.rtp("UPDATE {0}TRIGGERS SET TRIGGER_STATE = ? WHERE SCHED_NAME = {1} AND TRIGGER_GROUP LIKE ? AND (TRIGGER_STATE = ? OR TRIGGER_STATE = ? OR TRIGGER_STATE = ?)"));
         ps.setString(1, newState);
         ps.setString(2, this.toSqlLikeClause(matcher));
         ps.setString(3, oldState1);
         ps.setString(4, oldState2);
         ps.setString(5, oldState3);
         var8 = ps.executeUpdate();
      } finally {
         closeStatement(ps);
      }

      return var8;
   }

   public int updateTriggerStateFromOtherState(Connection conn, TriggerKey triggerKey, String newState, String oldState) throws SQLException {
      PreparedStatement ps = null;

      int var6;
      try {
         ps = conn.prepareStatement(this.rtp("UPDATE {0}TRIGGERS SET TRIGGER_STATE = ? WHERE SCHED_NAME = {1} AND TRIGGER_NAME = ? AND TRIGGER_GROUP = ? AND TRIGGER_STATE = ?"));
         ps.setString(1, newState);
         ps.setString(2, triggerKey.getName());
         ps.setString(3, triggerKey.getGroup());
         ps.setString(4, oldState);
         var6 = ps.executeUpdate();
      } finally {
         closeStatement(ps);
      }

      return var6;
   }

   public int updateTriggerGroupStateFromOtherState(Connection conn, GroupMatcher<TriggerKey> matcher, String newState, String oldState) throws SQLException {
      PreparedStatement ps = null;

      int var6;
      try {
         ps = conn.prepareStatement(this.rtp("UPDATE {0}TRIGGERS SET TRIGGER_STATE = ? WHERE SCHED_NAME = {1} AND TRIGGER_GROUP LIKE ? AND TRIGGER_STATE = ?"));
         ps.setString(1, newState);
         ps.setString(2, this.toSqlLikeClause(matcher));
         ps.setString(3, oldState);
         var6 = ps.executeUpdate();
      } finally {
         closeStatement(ps);
      }

      return var6;
   }

   public int updateTriggerStatesForJob(Connection conn, JobKey jobKey, String state) throws SQLException {
      PreparedStatement ps = null;

      int var5;
      try {
         ps = conn.prepareStatement(this.rtp("UPDATE {0}TRIGGERS SET TRIGGER_STATE = ? WHERE SCHED_NAME = {1} AND JOB_NAME = ? AND JOB_GROUP = ?"));
         ps.setString(1, state);
         ps.setString(2, jobKey.getName());
         ps.setString(3, jobKey.getGroup());
         var5 = ps.executeUpdate();
      } finally {
         closeStatement(ps);
      }

      return var5;
   }

   public int updateTriggerStatesForJobFromOtherState(Connection conn, JobKey jobKey, String state, String oldState) throws SQLException {
      PreparedStatement ps = null;

      int var6;
      try {
         ps = conn.prepareStatement(this.rtp("UPDATE {0}TRIGGERS SET TRIGGER_STATE = ? WHERE SCHED_NAME = {1} AND JOB_NAME = ? AND JOB_GROUP = ? AND TRIGGER_STATE = ?"));
         ps.setString(1, state);
         ps.setString(2, jobKey.getName());
         ps.setString(3, jobKey.getGroup());
         ps.setString(4, oldState);
         var6 = ps.executeUpdate();
      } finally {
         closeStatement(ps);
      }

      return var6;
   }

   public int deleteBlobTrigger(Connection conn, TriggerKey triggerKey) throws SQLException {
      PreparedStatement ps = null;

      int var4;
      try {
         ps = conn.prepareStatement(this.rtp("DELETE FROM {0}BLOB_TRIGGERS WHERE SCHED_NAME = {1} AND TRIGGER_NAME = ? AND TRIGGER_GROUP = ?"));
         ps.setString(1, triggerKey.getName());
         ps.setString(2, triggerKey.getGroup());
         var4 = ps.executeUpdate();
      } finally {
         closeStatement(ps);
      }

      return var4;
   }

   public int deleteTrigger(Connection conn, TriggerKey triggerKey) throws SQLException {
      PreparedStatement ps = null;
      this.deleteTriggerExtension(conn, triggerKey);

      int var4;
      try {
         ps = conn.prepareStatement(this.rtp("DELETE FROM {0}TRIGGERS WHERE SCHED_NAME = {1} AND TRIGGER_NAME = ? AND TRIGGER_GROUP = ?"));
         ps.setString(1, triggerKey.getName());
         ps.setString(2, triggerKey.getGroup());
         var4 = ps.executeUpdate();
      } finally {
         closeStatement(ps);
      }

      return var4;
   }

   protected void deleteTriggerExtension(Connection conn, TriggerKey triggerKey) throws SQLException {
      Iterator i$ = this.triggerPersistenceDelegates.iterator();

      TriggerPersistenceDelegate tDel;
      do {
         if (!i$.hasNext()) {
            this.deleteBlobTrigger(conn, triggerKey);
            return;
         }

         tDel = (TriggerPersistenceDelegate)i$.next();
      } while(tDel.deleteExtendedTriggerProperties(conn, triggerKey) <= 0);

   }

   public int selectNumTriggersForJob(Connection conn, JobKey jobKey) throws SQLException {
      PreparedStatement ps = null;
      ResultSet rs = null;

      int var5;
      try {
         ps = conn.prepareStatement(this.rtp("SELECT COUNT(TRIGGER_NAME) FROM {0}TRIGGERS WHERE SCHED_NAME = {1} AND JOB_NAME = ? AND JOB_GROUP = ?"));
         ps.setString(1, jobKey.getName());
         ps.setString(2, jobKey.getGroup());
         rs = ps.executeQuery();
         if (!rs.next()) {
            byte var9 = 0;
            return var9;
         }

         var5 = rs.getInt(1);
      } finally {
         closeResultSet(rs);
         closeStatement(ps);
      }

      return var5;
   }

   public JobDetail selectJobForTrigger(Connection conn, ClassLoadHelper loadHelper, TriggerKey triggerKey) throws ClassNotFoundException, SQLException {
      return this.selectJobForTrigger(conn, loadHelper, triggerKey, true);
   }

   public JobDetail selectJobForTrigger(Connection conn, ClassLoadHelper loadHelper, TriggerKey triggerKey, boolean loadJobClass) throws ClassNotFoundException, SQLException {
      PreparedStatement ps = null;
      ResultSet rs = null;

      JobDetailImpl var8;
      try {
         ps = conn.prepareStatement(this.rtp("SELECT J.JOB_NAME, J.JOB_GROUP, J.IS_DURABLE, J.JOB_CLASS_NAME, J.REQUESTS_RECOVERY FROM {0}TRIGGERS T, {0}JOB_DETAILS J WHERE T.SCHED_NAME = {1} AND J.SCHED_NAME = {1} AND T.TRIGGER_NAME = ? AND T.TRIGGER_GROUP = ? AND T.JOB_NAME = J.JOB_NAME AND T.JOB_GROUP = J.JOB_GROUP"));
         ps.setString(1, triggerKey.getName());
         ps.setString(2, triggerKey.getGroup());
         rs = ps.executeQuery();
         JobDetailImpl job;
         if (!rs.next()) {
            if (this.logger.isDebugEnabled()) {
               this.logger.debug("No job for trigger '" + triggerKey + "'.");
            }

            job = null;
            return job;
         }

         job = new JobDetailImpl();
         job.setName(rs.getString(1));
         job.setGroup(rs.getString(2));
         job.setDurability(this.getBoolean(rs, 3));
         if (loadJobClass) {
            job.setJobClass(loadHelper.loadClass(rs.getString(4), Job.class));
         }

         job.setRequestsRecovery(this.getBoolean(rs, 5));
         var8 = job;
      } finally {
         closeResultSet(rs);
         closeStatement(ps);
      }

      return var8;
   }

   public List<OperableTrigger> selectTriggersForJob(Connection conn, JobKey jobKey) throws SQLException, ClassNotFoundException, IOException, JobPersistenceException {
      LinkedList<OperableTrigger> trigList = new LinkedList();
      PreparedStatement ps = null;
      ResultSet rs = null;

      try {
         ps = conn.prepareStatement(this.rtp("SELECT TRIGGER_NAME, TRIGGER_GROUP FROM {0}TRIGGERS WHERE SCHED_NAME = {1} AND JOB_NAME = ? AND JOB_GROUP = ?"));
         ps.setString(1, jobKey.getName());
         ps.setString(2, jobKey.getGroup());
         rs = ps.executeQuery();

         while(rs.next()) {
            OperableTrigger t = this.selectTrigger(conn, TriggerKey.triggerKey(rs.getString("TRIGGER_NAME"), rs.getString("TRIGGER_GROUP")));
            if (t != null) {
               trigList.add(t);
            }
         }
      } finally {
         closeResultSet(rs);
         closeStatement(ps);
      }

      return trigList;
   }

   public List<OperableTrigger> selectTriggersForCalendar(Connection conn, String calName) throws SQLException, ClassNotFoundException, IOException, JobPersistenceException {
      LinkedList<OperableTrigger> trigList = new LinkedList();
      PreparedStatement ps = null;
      ResultSet rs = null;

      try {
         ps = conn.prepareStatement(this.rtp("SELECT TRIGGER_NAME, TRIGGER_GROUP FROM {0}TRIGGERS WHERE SCHED_NAME = {1} AND CALENDAR_NAME = ?"));
         ps.setString(1, calName);
         rs = ps.executeQuery();

         while(rs.next()) {
            trigList.add(this.selectTrigger(conn, TriggerKey.triggerKey(rs.getString("TRIGGER_NAME"), rs.getString("TRIGGER_GROUP"))));
         }
      } finally {
         closeResultSet(rs);
         closeStatement(ps);
      }

      return trigList;
   }

   public OperableTrigger selectTrigger(Connection conn, TriggerKey triggerKey) throws SQLException, ClassNotFoundException, IOException, JobPersistenceException {
      PreparedStatement ps = null;
      ResultSet rs = null;

      try {
         OperableTrigger trigger = null;
         ps = conn.prepareStatement(this.rtp("SELECT * FROM {0}TRIGGERS WHERE SCHED_NAME = {1} AND TRIGGER_NAME = ? AND TRIGGER_GROUP = ?"));
         ps.setString(1, triggerKey.getName());
         ps.setString(2, triggerKey.getGroup());
         rs = ps.executeQuery();
         if (rs.next()) {
            String jobName = rs.getString("JOB_NAME");
            String jobGroup = rs.getString("JOB_GROUP");
            String description = rs.getString("DESCRIPTION");
            long nextFireTime = rs.getLong("NEXT_FIRE_TIME");
            long prevFireTime = rs.getLong("PREV_FIRE_TIME");
            String triggerType = rs.getString("TRIGGER_TYPE");
            long startTime = rs.getLong("START_TIME");
            long endTime = rs.getLong("END_TIME");
            String calendarName = rs.getString("CALENDAR_NAME");
            int misFireInstr = rs.getInt("MISFIRE_INSTR");
            int priority = rs.getInt("PRIORITY");
            Map<?, ?> map = null;
            if (this.canUseProperties()) {
               map = this.getMapFromProperties(rs);
            } else {
               map = (Map)this.getObjectFromBlob(rs, "JOB_DATA");
            }

            Date nft = null;
            if (nextFireTime > 0L) {
               nft = new Date(nextFireTime);
            }

            Date pft = null;
            if (prevFireTime > 0L) {
               pft = new Date(prevFireTime);
            }

            Date startTimeD = new Date(startTime);
            Date endTimeD = null;
            if (endTime > 0L) {
               endTimeD = new Date(endTime);
            }

            if (triggerType.equals("BLOB")) {
               rs.close();
               rs = null;
               ps.close();
               ps = null;
               ps = conn.prepareStatement(this.rtp("SELECT * FROM {0}BLOB_TRIGGERS WHERE SCHED_NAME = {1} AND TRIGGER_NAME = ? AND TRIGGER_GROUP = ?"));
               ps.setString(1, triggerKey.getName());
               ps.setString(2, triggerKey.getGroup());
               rs = ps.executeQuery();
               if (rs.next()) {
                  trigger = (OperableTrigger)this.getObjectFromBlob(rs, "BLOB_DATA");
               }
            } else {
               TriggerPersistenceDelegate tDel = this.findTriggerPersistenceDelegate(triggerType);
               if (tDel == null) {
                  throw new JobPersistenceException("No TriggerPersistenceDelegate for trigger discriminator type: " + triggerType);
               }

               TriggerPersistenceDelegate.TriggerPropertyBundle triggerProps = null;

               try {
                  triggerProps = tDel.loadExtendedTriggerProperties(conn, triggerKey);
               } catch (IllegalStateException var33) {
                  if (this.isTriggerStillPresent(ps)) {
                     throw var33;
                  }

                  Object var29 = null;
                  return (OperableTrigger)var29;
               }

               TriggerBuilder<?> tb = TriggerBuilder.newTrigger().withDescription(description).withPriority(priority).startAt(startTimeD).endAt(endTimeD).withIdentity(triggerKey).modifiedByCalendar(calendarName).withSchedule(triggerProps.getScheduleBuilder()).forJob(JobKey.jobKey(jobName, jobGroup));
               if (null != map) {
                  tb.usingJobData(new JobDataMap(map));
               }

               trigger = (OperableTrigger)tb.build();
               trigger.setMisfireInstruction(misFireInstr);
               trigger.setNextFireTime(nft);
               trigger.setPreviousFireTime(pft);
               this.setTriggerStateProperties(trigger, triggerProps);
            }
         }

         OperableTrigger var35 = trigger;
         return var35;
      } finally {
         closeResultSet(rs);
         closeStatement(ps);
      }
   }

   private boolean isTriggerStillPresent(PreparedStatement ps) throws SQLException {
      ResultSet rs = null;

      boolean var3;
      try {
         rs = ps.executeQuery();
         var3 = rs.next();
      } finally {
         closeResultSet(rs);
      }

      return var3;
   }

   private void setTriggerStateProperties(OperableTrigger trigger, TriggerPersistenceDelegate.TriggerPropertyBundle props) throws JobPersistenceException {
      if (props.getStatePropertyNames() != null) {
         Util.setBeanProps(trigger, props.getStatePropertyNames(), props.getStatePropertyValues());
      }
   }

   public JobDataMap selectTriggerJobDataMap(Connection conn, String triggerName, String groupName) throws SQLException, ClassNotFoundException, IOException {
      PreparedStatement ps = null;
      ResultSet rs = null;

      try {
         ps = conn.prepareStatement(this.rtp("SELECT JOB_DATA FROM {0}TRIGGERS WHERE SCHED_NAME = {1} AND TRIGGER_NAME = ? AND TRIGGER_GROUP = ?"));
         ps.setString(1, triggerName);
         ps.setString(2, groupName);
         rs = ps.executeQuery();
         if (rs.next()) {
            Map<?, ?> map = null;
            if (this.canUseProperties()) {
               map = this.getMapFromProperties(rs);
            } else {
               map = (Map)this.getObjectFromBlob(rs, "JOB_DATA");
            }

            rs.close();
            ps.close();
            if (null != map) {
               JobDataMap var7 = new JobDataMap(map);
               return var7;
            }
         }
      } finally {
         closeResultSet(rs);
         closeStatement(ps);
      }

      return new JobDataMap();
   }

   public String selectTriggerState(Connection conn, TriggerKey triggerKey) throws SQLException {
      PreparedStatement ps = null;
      ResultSet rs = null;

      String var6;
      try {
         String state = null;
         ps = conn.prepareStatement(this.rtp("SELECT TRIGGER_STATE FROM {0}TRIGGERS WHERE SCHED_NAME = {1} AND TRIGGER_NAME = ? AND TRIGGER_GROUP = ?"));
         ps.setString(1, triggerKey.getName());
         ps.setString(2, triggerKey.getGroup());
         rs = ps.executeQuery();
         if (rs.next()) {
            state = rs.getString("TRIGGER_STATE");
         } else {
            state = "DELETED";
         }

         var6 = state.intern();
      } finally {
         closeResultSet(rs);
         closeStatement(ps);
      }

      return var6;
   }

   public TriggerStatus selectTriggerStatus(Connection conn, TriggerKey triggerKey) throws SQLException {
      PreparedStatement ps = null;
      ResultSet rs = null;

      TriggerStatus var15;
      try {
         TriggerStatus status = null;
         ps = conn.prepareStatement(this.rtp("SELECT TRIGGER_STATE, NEXT_FIRE_TIME, JOB_NAME, JOB_GROUP FROM {0}TRIGGERS WHERE SCHED_NAME = {1} AND TRIGGER_NAME = ? AND TRIGGER_GROUP = ?"));
         ps.setString(1, triggerKey.getName());
         ps.setString(2, triggerKey.getGroup());
         rs = ps.executeQuery();
         if (rs.next()) {
            String state = rs.getString("TRIGGER_STATE");
            long nextFireTime = rs.getLong("NEXT_FIRE_TIME");
            String jobName = rs.getString("JOB_NAME");
            String jobGroup = rs.getString("JOB_GROUP");
            Date nft = null;
            if (nextFireTime > 0L) {
               nft = new Date(nextFireTime);
            }

            status = new TriggerStatus(state, nft);
            status.setKey(triggerKey);
            status.setJobKey(JobKey.jobKey(jobName, jobGroup));
         }

         var15 = status;
      } finally {
         closeResultSet(rs);
         closeStatement(ps);
      }

      return var15;
   }

   public int selectNumTriggers(Connection conn) throws SQLException {
      PreparedStatement ps = null;
      ResultSet rs = null;

      int var5;
      try {
         int count = 0;
         ps = conn.prepareStatement(this.rtp("SELECT COUNT(TRIGGER_NAME)  FROM {0}TRIGGERS WHERE SCHED_NAME = {1}"));
         rs = ps.executeQuery();
         if (rs.next()) {
            count = rs.getInt(1);
         }

         var5 = count;
      } finally {
         closeResultSet(rs);
         closeStatement(ps);
      }

      return var5;
   }

   public List<String> selectTriggerGroups(Connection conn) throws SQLException {
      PreparedStatement ps = null;
      ResultSet rs = null;

      try {
         ps = conn.prepareStatement(this.rtp("SELECT DISTINCT(TRIGGER_GROUP) FROM {0}TRIGGERS WHERE SCHED_NAME = {1}"));
         rs = ps.executeQuery();
         LinkedList list = new LinkedList();

         while(rs.next()) {
            list.add(rs.getString(1));
         }

         LinkedList var5 = list;
         return var5;
      } finally {
         closeResultSet(rs);
         closeStatement(ps);
      }
   }

   public List<String> selectTriggerGroups(Connection conn, GroupMatcher<TriggerKey> matcher) throws SQLException {
      PreparedStatement ps = null;
      ResultSet rs = null;

      try {
         ps = conn.prepareStatement(this.rtp("SELECT DISTINCT(TRIGGER_GROUP) FROM {0}TRIGGERS WHERE SCHED_NAME = {1} AND TRIGGER_GROUP LIKE ?"));
         ps.setString(1, this.toSqlLikeClause(matcher));
         rs = ps.executeQuery();
         LinkedList list = new LinkedList();

         while(rs.next()) {
            list.add(rs.getString(1));
         }

         LinkedList var6 = list;
         return var6;
      } finally {
         closeResultSet(rs);
         closeStatement(ps);
      }
   }

   public Set<TriggerKey> selectTriggersInGroup(Connection conn, GroupMatcher<TriggerKey> matcher) throws SQLException {
      PreparedStatement ps = null;
      ResultSet rs = null;

      try {
         if (this.isMatcherEquals(matcher)) {
            ps = conn.prepareStatement(this.rtp("SELECT TRIGGER_NAME, TRIGGER_GROUP FROM {0}TRIGGERS WHERE SCHED_NAME = {1} AND TRIGGER_GROUP = ?"));
            ps.setString(1, this.toSqlEqualsClause(matcher));
         } else {
            ps = conn.prepareStatement(this.rtp("SELECT TRIGGER_NAME, TRIGGER_GROUP FROM {0}TRIGGERS WHERE SCHED_NAME = {1} AND TRIGGER_GROUP LIKE ?"));
            ps.setString(1, this.toSqlLikeClause(matcher));
         }

         rs = ps.executeQuery();
         HashSet keys = new HashSet();

         while(rs.next()) {
            keys.add(TriggerKey.triggerKey(rs.getString(1), rs.getString(2)));
         }

         HashSet var6 = keys;
         return var6;
      } finally {
         closeResultSet(rs);
         closeStatement(ps);
      }
   }

   public int insertPausedTriggerGroup(Connection conn, String groupName) throws SQLException {
      PreparedStatement ps = null;

      int var5;
      try {
         ps = conn.prepareStatement(this.rtp("INSERT INTO {0}PAUSED_TRIGGER_GRPS (SCHED_NAME, TRIGGER_GROUP) VALUES({1}, ?)"));
         ps.setString(1, groupName);
         int rows = ps.executeUpdate();
         var5 = rows;
      } finally {
         closeStatement(ps);
      }

      return var5;
   }

   public int deletePausedTriggerGroup(Connection conn, String groupName) throws SQLException {
      PreparedStatement ps = null;

      int var5;
      try {
         ps = conn.prepareStatement(this.rtp("DELETE FROM {0}PAUSED_TRIGGER_GRPS WHERE SCHED_NAME = {1} AND TRIGGER_GROUP LIKE ?"));
         ps.setString(1, groupName);
         int rows = ps.executeUpdate();
         var5 = rows;
      } finally {
         closeStatement(ps);
      }

      return var5;
   }

   public int deletePausedTriggerGroup(Connection conn, GroupMatcher<TriggerKey> matcher) throws SQLException {
      PreparedStatement ps = null;

      int var5;
      try {
         ps = conn.prepareStatement(this.rtp("DELETE FROM {0}PAUSED_TRIGGER_GRPS WHERE SCHED_NAME = {1} AND TRIGGER_GROUP LIKE ?"));
         ps.setString(1, this.toSqlLikeClause(matcher));
         int rows = ps.executeUpdate();
         var5 = rows;
      } finally {
         closeStatement(ps);
      }

      return var5;
   }

   public int deleteAllPausedTriggerGroups(Connection conn) throws SQLException {
      PreparedStatement ps = null;

      int var4;
      try {
         ps = conn.prepareStatement(this.rtp("DELETE FROM {0}PAUSED_TRIGGER_GRPS WHERE SCHED_NAME = {1}"));
         int rows = ps.executeUpdate();
         var4 = rows;
      } finally {
         closeStatement(ps);
      }

      return var4;
   }

   public boolean isTriggerGroupPaused(Connection conn, String groupName) throws SQLException {
      PreparedStatement ps = null;
      ResultSet rs = null;

      boolean var5;
      try {
         ps = conn.prepareStatement(this.rtp("SELECT TRIGGER_GROUP FROM {0}PAUSED_TRIGGER_GRPS WHERE SCHED_NAME = {1} AND TRIGGER_GROUP = ?"));
         ps.setString(1, groupName);
         rs = ps.executeQuery();
         var5 = rs.next();
      } finally {
         closeResultSet(rs);
         closeStatement(ps);
      }

      return var5;
   }

   public boolean isExistingTriggerGroup(Connection conn, String groupName) throws SQLException {
      PreparedStatement ps = null;
      ResultSet rs = null;

      boolean var5;
      try {
         ps = conn.prepareStatement(this.rtp("SELECT COUNT(TRIGGER_NAME)  FROM {0}TRIGGERS WHERE SCHED_NAME = {1} AND TRIGGER_GROUP = ?"));
         ps.setString(1, groupName);
         rs = ps.executeQuery();
         if (!rs.next()) {
            var5 = false;
            return var5;
         }

         var5 = rs.getInt(1) > 0;
      } finally {
         closeResultSet(rs);
         closeStatement(ps);
      }

      return var5;
   }

   public int insertCalendar(Connection conn, String calendarName, Calendar calendar) throws IOException, SQLException {
      ByteArrayOutputStream baos = this.serializeObject(calendar);
      PreparedStatement ps = null;

      int var6;
      try {
         ps = conn.prepareStatement(this.rtp("INSERT INTO {0}CALENDARS (SCHED_NAME, CALENDAR_NAME, CALENDAR)  VALUES({1}, ?, ?)"));
         ps.setString(1, calendarName);
         this.setBytes(ps, 2, baos);
         var6 = ps.executeUpdate();
      } finally {
         closeStatement(ps);
      }

      return var6;
   }

   public int updateCalendar(Connection conn, String calendarName, Calendar calendar) throws IOException, SQLException {
      ByteArrayOutputStream baos = this.serializeObject(calendar);
      PreparedStatement ps = null;

      int var6;
      try {
         ps = conn.prepareStatement(this.rtp("UPDATE {0}CALENDARS SET CALENDAR = ?  WHERE SCHED_NAME = {1} AND CALENDAR_NAME = ?"));
         this.setBytes(ps, 1, baos);
         ps.setString(2, calendarName);
         var6 = ps.executeUpdate();
      } finally {
         closeStatement(ps);
      }

      return var6;
   }

   public boolean calendarExists(Connection conn, String calendarName) throws SQLException {
      PreparedStatement ps = null;
      ResultSet rs = null;

      boolean var5;
      try {
         ps = conn.prepareStatement(this.rtp("SELECT CALENDAR_NAME FROM {0}CALENDARS WHERE SCHED_NAME = {1} AND CALENDAR_NAME = ?"));
         ps.setString(1, calendarName);
         rs = ps.executeQuery();
         if (!rs.next()) {
            var5 = false;
            return var5;
         }

         var5 = true;
      } finally {
         closeResultSet(rs);
         closeStatement(ps);
      }

      return var5;
   }

   public Calendar selectCalendar(Connection conn, String calendarName) throws ClassNotFoundException, IOException, SQLException {
      PreparedStatement ps = null;
      ResultSet rs = null;

      Calendar var7;
      try {
         String selCal = this.rtp("SELECT * FROM {0}CALENDARS WHERE SCHED_NAME = {1} AND CALENDAR_NAME = ?");
         ps = conn.prepareStatement(selCal);
         ps.setString(1, calendarName);
         rs = ps.executeQuery();
         Calendar cal = null;
         if (rs.next()) {
            cal = (Calendar)this.getObjectFromBlob(rs, "CALENDAR");
         }

         if (null == cal) {
            this.logger.warn("Couldn't find calendar with name '" + calendarName + "'.");
         }

         var7 = cal;
      } finally {
         closeResultSet(rs);
         closeStatement(ps);
      }

      return var7;
   }

   public boolean calendarIsReferenced(Connection conn, String calendarName) throws SQLException {
      PreparedStatement ps = null;
      ResultSet rs = null;

      boolean var5;
      try {
         ps = conn.prepareStatement(this.rtp("SELECT CALENDAR_NAME FROM {0}TRIGGERS WHERE SCHED_NAME = {1} AND CALENDAR_NAME = ?"));
         ps.setString(1, calendarName);
         rs = ps.executeQuery();
         if (rs.next()) {
            var5 = true;
            return var5;
         }

         var5 = false;
      } finally {
         closeResultSet(rs);
         closeStatement(ps);
      }

      return var5;
   }

   public int deleteCalendar(Connection conn, String calendarName) throws SQLException {
      PreparedStatement ps = null;

      int var4;
      try {
         ps = conn.prepareStatement(this.rtp("DELETE FROM {0}CALENDARS WHERE SCHED_NAME = {1} AND CALENDAR_NAME = ?"));
         ps.setString(1, calendarName);
         var4 = ps.executeUpdate();
      } finally {
         closeStatement(ps);
      }

      return var4;
   }

   public int selectNumCalendars(Connection conn) throws SQLException {
      PreparedStatement ps = null;
      ResultSet rs = null;

      int var5;
      try {
         int count = 0;
         ps = conn.prepareStatement(this.rtp("SELECT COUNT(CALENDAR_NAME)  FROM {0}CALENDARS WHERE SCHED_NAME = {1}"));
         rs = ps.executeQuery();
         if (rs.next()) {
            count = rs.getInt(1);
         }

         var5 = count;
      } finally {
         closeResultSet(rs);
         closeStatement(ps);
      }

      return var5;
   }

   public List<String> selectCalendars(Connection conn) throws SQLException {
      PreparedStatement ps = null;
      ResultSet rs = null;

      try {
         ps = conn.prepareStatement(this.rtp("SELECT CALENDAR_NAME FROM {0}CALENDARS WHERE SCHED_NAME = {1}"));
         rs = ps.executeQuery();
         LinkedList list = new LinkedList();

         while(rs.next()) {
            list.add(rs.getString(1));
         }

         LinkedList var5 = list;
         return var5;
      } finally {
         closeResultSet(rs);
         closeStatement(ps);
      }
   }

   /** @deprecated */
   public long selectNextFireTime(Connection conn) throws SQLException {
      PreparedStatement ps = null;
      ResultSet rs = null;

      long var4;
      try {
         ps = conn.prepareStatement(this.rtp("SELECT MIN(NEXT_FIRE_TIME) AS ALIAS_NXT_FR_TM FROM {0}TRIGGERS WHERE SCHED_NAME = {1} AND TRIGGER_STATE = ? AND NEXT_FIRE_TIME >= 0"));
         ps.setString(1, "WAITING");
         rs = ps.executeQuery();
         if (!rs.next()) {
            var4 = 0L;
            return var4;
         }

         var4 = rs.getLong("ALIAS_NXT_FR_TM");
      } finally {
         closeResultSet(rs);
         closeStatement(ps);
      }

      return var4;
   }

   public TriggerKey selectTriggerForFireTime(Connection conn, long fireTime) throws SQLException {
      PreparedStatement ps = null;
      ResultSet rs = null;

      TriggerKey var6;
      try {
         ps = conn.prepareStatement(this.rtp("SELECT TRIGGER_NAME, TRIGGER_GROUP FROM {0}TRIGGERS WHERE SCHED_NAME = {1} AND TRIGGER_STATE = ? AND NEXT_FIRE_TIME = ?"));
         ps.setString(1, "WAITING");
         ps.setBigDecimal(2, new BigDecimal(String.valueOf(fireTime)));
         rs = ps.executeQuery();
         if (!rs.next()) {
            var6 = null;
            return var6;
         }

         var6 = new TriggerKey(rs.getString("TRIGGER_NAME"), rs.getString("TRIGGER_GROUP"));
      } finally {
         closeResultSet(rs);
         closeStatement(ps);
      }

      return var6;
   }

   /** @deprecated */
   public List<TriggerKey> selectTriggerToAcquire(Connection conn, long noLaterThan, long noEarlierThan) throws SQLException {
      return this.selectTriggerToAcquire(conn, noLaterThan, noEarlierThan, 1);
   }

   public List<TriggerKey> selectTriggerToAcquire(Connection conn, long noLaterThan, long noEarlierThan, int maxCount) throws SQLException {
      PreparedStatement ps = null;
      ResultSet rs = null;
      LinkedList nextTriggers = new LinkedList();

      try {
         ps = conn.prepareStatement(this.rtp("SELECT TRIGGER_NAME, TRIGGER_GROUP, NEXT_FIRE_TIME, PRIORITY FROM {0}TRIGGERS WHERE SCHED_NAME = {1} AND TRIGGER_STATE = ? AND NEXT_FIRE_TIME <= ? AND (MISFIRE_INSTR = -1 OR (MISFIRE_INSTR != -1 AND NEXT_FIRE_TIME >= ?)) ORDER BY NEXT_FIRE_TIME ASC, PRIORITY DESC"));
         if (maxCount < 1) {
            maxCount = 1;
         }

         ps.setMaxRows(maxCount);
         ps.setFetchSize(maxCount);
         ps.setString(1, "WAITING");
         ps.setBigDecimal(2, new BigDecimal(String.valueOf(noLaterThan)));
         ps.setBigDecimal(3, new BigDecimal(String.valueOf(noEarlierThan)));
         rs = ps.executeQuery();

         while(rs.next() && nextTriggers.size() <= maxCount) {
            nextTriggers.add(TriggerKey.triggerKey(rs.getString("TRIGGER_NAME"), rs.getString("TRIGGER_GROUP")));
         }

         LinkedList var10 = nextTriggers;
         return var10;
      } finally {
         closeResultSet(rs);
         closeStatement(ps);
      }
   }

   public int insertFiredTrigger(Connection conn, OperableTrigger trigger, String state, JobDetail job) throws SQLException {
      PreparedStatement ps = null;

      int var6;
      try {
         ps = conn.prepareStatement(this.rtp("INSERT INTO {0}FIRED_TRIGGERS (SCHED_NAME, ENTRY_ID, TRIGGER_NAME, TRIGGER_GROUP, INSTANCE_NAME, FIRED_TIME, SCHED_TIME, STATE, JOB_NAME, JOB_GROUP, IS_NONCONCURRENT, REQUESTS_RECOVERY, PRIORITY) VALUES({1}, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"));
         ps.setString(1, trigger.getFireInstanceId());
         ps.setString(2, trigger.getKey().getName());
         ps.setString(3, trigger.getKey().getGroup());
         ps.setString(4, this.instanceId);
         ps.setBigDecimal(5, new BigDecimal(String.valueOf(System.currentTimeMillis())));
         ps.setBigDecimal(6, new BigDecimal(String.valueOf(trigger.getNextFireTime().getTime())));
         ps.setString(7, state);
         if (job != null) {
            ps.setString(8, trigger.getJobKey().getName());
            ps.setString(9, trigger.getJobKey().getGroup());
            this.setBoolean(ps, 10, job.isConcurrentExectionDisallowed());
            this.setBoolean(ps, 11, job.requestsRecovery());
         } else {
            ps.setString(8, (String)null);
            ps.setString(9, (String)null);
            this.setBoolean(ps, 10, false);
            this.setBoolean(ps, 11, false);
         }

         ps.setInt(12, trigger.getPriority());
         var6 = ps.executeUpdate();
      } finally {
         closeStatement(ps);
      }

      return var6;
   }

   public int updateFiredTrigger(Connection conn, OperableTrigger trigger, String state, JobDetail job) throws SQLException {
      PreparedStatement ps = null;

      int var6;
      try {
         ps = conn.prepareStatement(this.rtp("UPDATE {0}FIRED_TRIGGERS SET INSTANCE_NAME = ?, FIRED_TIME = ?, SCHED_TIME = ?, STATE = ?, JOB_NAME = ?, JOB_GROUP = ?, IS_NONCONCURRENT = ?, REQUESTS_RECOVERY = ? WHERE SCHED_NAME = {1} AND ENTRY_ID = ?"));
         ps.setString(1, this.instanceId);
         ps.setBigDecimal(2, new BigDecimal(String.valueOf(System.currentTimeMillis())));
         ps.setBigDecimal(3, new BigDecimal(String.valueOf(trigger.getNextFireTime().getTime())));
         ps.setString(4, state);
         if (job != null) {
            ps.setString(5, trigger.getJobKey().getName());
            ps.setString(6, trigger.getJobKey().getGroup());
            this.setBoolean(ps, 7, job.isConcurrentExectionDisallowed());
            this.setBoolean(ps, 8, job.requestsRecovery());
         } else {
            ps.setString(5, (String)null);
            ps.setString(6, (String)null);
            this.setBoolean(ps, 7, false);
            this.setBoolean(ps, 8, false);
         }

         ps.setString(9, trigger.getFireInstanceId());
         var6 = ps.executeUpdate();
      } finally {
         closeStatement(ps);
      }

      return var6;
   }

   public List<FiredTriggerRecord> selectFiredTriggerRecords(Connection conn, String triggerName, String groupName) throws SQLException {
      PreparedStatement ps = null;
      ResultSet rs = null;

      try {
         List<FiredTriggerRecord> lst = new LinkedList();
         if (triggerName != null) {
            ps = conn.prepareStatement(this.rtp("SELECT * FROM {0}FIRED_TRIGGERS WHERE SCHED_NAME = {1} AND TRIGGER_NAME = ? AND TRIGGER_GROUP = ?"));
            ps.setString(1, triggerName);
            ps.setString(2, groupName);
         } else {
            ps = conn.prepareStatement(this.rtp("SELECT * FROM {0}FIRED_TRIGGERS WHERE SCHED_NAME = {1} AND TRIGGER_GROUP = ?"));
            ps.setString(1, groupName);
         }

         FiredTriggerRecord rec;
         for(rs = ps.executeQuery(); rs.next(); lst.add(rec)) {
            rec = new FiredTriggerRecord();
            rec.setFireInstanceId(rs.getString("ENTRY_ID"));
            rec.setFireInstanceState(rs.getString("STATE"));
            rec.setFireTimestamp(rs.getLong("FIRED_TIME"));
            rec.setScheduleTimestamp(rs.getLong("SCHED_TIME"));
            rec.setPriority(rs.getInt("PRIORITY"));
            rec.setSchedulerInstanceId(rs.getString("INSTANCE_NAME"));
            rec.setTriggerKey(TriggerKey.triggerKey(rs.getString("TRIGGER_NAME"), rs.getString("TRIGGER_GROUP")));
            if (!rec.getFireInstanceState().equals("ACQUIRED")) {
               rec.setJobDisallowsConcurrentExecution(this.getBoolean(rs, "IS_NONCONCURRENT"));
               rec.setJobRequestsRecovery(rs.getBoolean("REQUESTS_RECOVERY"));
               rec.setJobKey(JobKey.jobKey(rs.getString("JOB_NAME"), rs.getString("JOB_GROUP")));
            }
         }

         LinkedList var11 = lst;
         return var11;
      } finally {
         closeResultSet(rs);
         closeStatement(ps);
      }
   }

   public List<FiredTriggerRecord> selectFiredTriggerRecordsByJob(Connection conn, String jobName, String groupName) throws SQLException {
      PreparedStatement ps = null;
      ResultSet rs = null;

      try {
         List<FiredTriggerRecord> lst = new LinkedList();
         if (jobName != null) {
            ps = conn.prepareStatement(this.rtp("SELECT * FROM {0}FIRED_TRIGGERS WHERE SCHED_NAME = {1} AND JOB_NAME = ? AND JOB_GROUP = ?"));
            ps.setString(1, jobName);
            ps.setString(2, groupName);
         } else {
            ps = conn.prepareStatement(this.rtp("SELECT * FROM {0}FIRED_TRIGGERS WHERE SCHED_NAME = {1} AND JOB_GROUP = ?"));
            ps.setString(1, groupName);
         }

         FiredTriggerRecord rec;
         for(rs = ps.executeQuery(); rs.next(); lst.add(rec)) {
            rec = new FiredTriggerRecord();
            rec.setFireInstanceId(rs.getString("ENTRY_ID"));
            rec.setFireInstanceState(rs.getString("STATE"));
            rec.setFireTimestamp(rs.getLong("FIRED_TIME"));
            rec.setScheduleTimestamp(rs.getLong("SCHED_TIME"));
            rec.setPriority(rs.getInt("PRIORITY"));
            rec.setSchedulerInstanceId(rs.getString("INSTANCE_NAME"));
            rec.setTriggerKey(TriggerKey.triggerKey(rs.getString("TRIGGER_NAME"), rs.getString("TRIGGER_GROUP")));
            if (!rec.getFireInstanceState().equals("ACQUIRED")) {
               rec.setJobDisallowsConcurrentExecution(this.getBoolean(rs, "IS_NONCONCURRENT"));
               rec.setJobRequestsRecovery(rs.getBoolean("REQUESTS_RECOVERY"));
               rec.setJobKey(JobKey.jobKey(rs.getString("JOB_NAME"), rs.getString("JOB_GROUP")));
            }
         }

         LinkedList var11 = lst;
         return var11;
      } finally {
         closeResultSet(rs);
         closeStatement(ps);
      }
   }

   public List<FiredTriggerRecord> selectInstancesFiredTriggerRecords(Connection conn, String instanceName) throws SQLException {
      PreparedStatement ps = null;
      ResultSet rs = null;

      LinkedList var10;
      try {
         List<FiredTriggerRecord> lst = new LinkedList();
         ps = conn.prepareStatement(this.rtp("SELECT * FROM {0}FIRED_TRIGGERS WHERE SCHED_NAME = {1} AND INSTANCE_NAME = ?"));
         ps.setString(1, instanceName);
         rs = ps.executeQuery();

         while(rs.next()) {
            FiredTriggerRecord rec = new FiredTriggerRecord();
            rec.setFireInstanceId(rs.getString("ENTRY_ID"));
            rec.setFireInstanceState(rs.getString("STATE"));
            rec.setFireTimestamp(rs.getLong("FIRED_TIME"));
            rec.setScheduleTimestamp(rs.getLong("SCHED_TIME"));
            rec.setSchedulerInstanceId(rs.getString("INSTANCE_NAME"));
            rec.setTriggerKey(TriggerKey.triggerKey(rs.getString("TRIGGER_NAME"), rs.getString("TRIGGER_GROUP")));
            if (!rec.getFireInstanceState().equals("ACQUIRED")) {
               rec.setJobDisallowsConcurrentExecution(this.getBoolean(rs, "IS_NONCONCURRENT"));
               rec.setJobRequestsRecovery(rs.getBoolean("REQUESTS_RECOVERY"));
               rec.setJobKey(JobKey.jobKey(rs.getString("JOB_NAME"), rs.getString("JOB_GROUP")));
            }

            rec.setPriority(rs.getInt("PRIORITY"));
            lst.add(rec);
         }

         var10 = lst;
      } finally {
         closeResultSet(rs);
         closeStatement(ps);
      }

      return var10;
   }

   public Set<String> selectFiredTriggerInstanceNames(Connection conn) throws SQLException {
      PreparedStatement ps = null;
      ResultSet rs = null;

      try {
         Set<String> instanceNames = new HashSet();
         ps = conn.prepareStatement(this.rtp("SELECT DISTINCT INSTANCE_NAME FROM {0}FIRED_TRIGGERS WHERE SCHED_NAME = {1}"));
         rs = ps.executeQuery();

         while(rs.next()) {
            instanceNames.add(rs.getString("INSTANCE_NAME"));
         }

         HashSet var5 = instanceNames;
         return var5;
      } finally {
         closeResultSet(rs);
         closeStatement(ps);
      }
   }

   public int deleteFiredTrigger(Connection conn, String entryId) throws SQLException {
      PreparedStatement ps = null;

      int var4;
      try {
         ps = conn.prepareStatement(this.rtp("DELETE FROM {0}FIRED_TRIGGERS WHERE SCHED_NAME = {1} AND ENTRY_ID = ?"));
         ps.setString(1, entryId);
         var4 = ps.executeUpdate();
      } finally {
         closeStatement(ps);
      }

      return var4;
   }

   public int selectJobExecutionCount(Connection conn, JobKey jobKey) throws SQLException {
      PreparedStatement ps = null;
      ResultSet rs = null;

      int var5;
      try {
         ps = conn.prepareStatement(this.rtp("SELECT COUNT(TRIGGER_NAME) FROM {0}FIRED_TRIGGERS WHERE SCHED_NAME = {1} AND JOB_NAME = ? AND JOB_GROUP = ?"));
         ps.setString(1, jobKey.getName());
         ps.setString(2, jobKey.getGroup());
         rs = ps.executeQuery();
         var5 = rs.next() ? rs.getInt(1) : 0;
      } finally {
         closeResultSet(rs);
         closeStatement(ps);
      }

      return var5;
   }

   public int insertSchedulerState(Connection conn, String theInstanceId, long checkInTime, long interval) throws SQLException {
      PreparedStatement ps = null;

      int var8;
      try {
         ps = conn.prepareStatement(this.rtp("INSERT INTO {0}SCHEDULER_STATE (SCHED_NAME, INSTANCE_NAME, LAST_CHECKIN_TIME, CHECKIN_INTERVAL) VALUES({1}, ?, ?, ?)"));
         ps.setString(1, theInstanceId);
         ps.setLong(2, checkInTime);
         ps.setLong(3, interval);
         var8 = ps.executeUpdate();
      } finally {
         closeStatement(ps);
      }

      return var8;
   }

   public int deleteSchedulerState(Connection conn, String theInstanceId) throws SQLException {
      PreparedStatement ps = null;

      int var4;
      try {
         ps = conn.prepareStatement(this.rtp("DELETE FROM {0}SCHEDULER_STATE WHERE SCHED_NAME = {1} AND INSTANCE_NAME = ?"));
         ps.setString(1, theInstanceId);
         var4 = ps.executeUpdate();
      } finally {
         closeStatement(ps);
      }

      return var4;
   }

   public int updateSchedulerState(Connection conn, String theInstanceId, long checkInTime) throws SQLException {
      PreparedStatement ps = null;

      int var6;
      try {
         ps = conn.prepareStatement(this.rtp("UPDATE {0}SCHEDULER_STATE SET LAST_CHECKIN_TIME = ? WHERE SCHED_NAME = {1} AND INSTANCE_NAME = ?"));
         ps.setLong(1, checkInTime);
         ps.setString(2, theInstanceId);
         var6 = ps.executeUpdate();
      } finally {
         closeStatement(ps);
      }

      return var6;
   }

   public List<SchedulerStateRecord> selectSchedulerStateRecords(Connection conn, String theInstanceId) throws SQLException {
      PreparedStatement ps = null;
      ResultSet rs = null;

      try {
         List<SchedulerStateRecord> lst = new LinkedList();
         if (theInstanceId != null) {
            ps = conn.prepareStatement(this.rtp("SELECT * FROM {0}SCHEDULER_STATE WHERE SCHED_NAME = {1} AND INSTANCE_NAME = ?"));
            ps.setString(1, theInstanceId);
         } else {
            ps = conn.prepareStatement(this.rtp("SELECT * FROM {0}SCHEDULER_STATE WHERE SCHED_NAME = {1}"));
         }

         rs = ps.executeQuery();

         while(rs.next()) {
            SchedulerStateRecord rec = new SchedulerStateRecord();
            rec.setSchedulerInstanceId(rs.getString("INSTANCE_NAME"));
            rec.setCheckinTimestamp(rs.getLong("LAST_CHECKIN_TIME"));
            rec.setCheckinInterval(rs.getLong("CHECKIN_INTERVAL"));
            lst.add(rec);
         }

         LinkedList var10 = lst;
         return var10;
      } finally {
         closeResultSet(rs);
         closeStatement(ps);
      }
   }

   protected final String rtp(String query) {
      return Util.rtp(query, this.tablePrefix, this.getSchedulerNameLiteral());
   }

   protected String getSchedulerNameLiteral() {
      if (this.schedNameLiteral == null) {
         this.schedNameLiteral = "'" + this.schedName + "'";
      }

      return this.schedNameLiteral;
   }

   protected ByteArrayOutputStream serializeObject(Object obj) throws IOException {
      ByteArrayOutputStream baos = new ByteArrayOutputStream();
      if (null != obj) {
         ObjectOutputStream out = new ObjectOutputStream(baos);
         out.writeObject(obj);
         out.flush();
      }

      return baos;
   }

   protected ByteArrayOutputStream serializeJobData(JobDataMap data) throws IOException {
      if (this.canUseProperties()) {
         return this.serializeProperties(data);
      } else {
         try {
            return this.serializeObject(data);
         } catch (NotSerializableException var3) {
            throw new NotSerializableException("Unable to serialize JobDataMap for insertion into database because the value of property '" + this.getKeyOfNonSerializableValue(data) + "' is not serializable: " + var3.getMessage());
         }
      }
   }

   protected Object getKeyOfNonSerializableValue(Map<?, ?> data) {
      Iterator entryIter = data.entrySet().iterator();

      while(entryIter.hasNext()) {
         Entry<?, ?> entry = (Entry)entryIter.next();
         ByteArrayOutputStream baos = null;

         Object var6;
         try {
            baos = this.serializeObject(entry.getValue());
            continue;
         } catch (IOException var16) {
            var6 = entry.getKey();
         } finally {
            if (baos != null) {
               try {
                  baos.close();
               } catch (IOException var15) {
               }
            }

         }

         return var6;
      }

      return null;
   }

   private ByteArrayOutputStream serializeProperties(JobDataMap data) throws IOException {
      ByteArrayOutputStream ba = new ByteArrayOutputStream();
      if (null != data) {
         Properties properties = this.convertToProperty(data.getWrappedMap());
         properties.store(ba, "");
      }

      return ba;
   }

   protected Map<?, ?> convertFromProperty(Properties properties) throws IOException {
      return new HashMap(properties);
   }

   protected Properties convertToProperty(Map<?, ?> data) throws IOException {
      Properties properties = new Properties();
      Iterator entryIter = data.entrySet().iterator();

      while(entryIter.hasNext()) {
         Entry<?, ?> entry = (Entry)entryIter.next();
         Object key = entry.getKey();
         Object val = entry.getValue() == null ? "" : entry.getValue();
         if (!(key instanceof String)) {
            throw new IOException("JobDataMap keys/values must be Strings when the 'useProperties' property is set.  offending Key: " + key);
         }

         if (!(val instanceof String)) {
            throw new IOException("JobDataMap values must be Strings when the 'useProperties' property is set.  Key of offending value: " + key);
         }

         properties.put(key, val);
      }

      return properties;
   }

   protected Object getObjectFromBlob(ResultSet rs, String colName) throws ClassNotFoundException, IOException, SQLException {
      Object obj = null;
      Blob blobLocator = rs.getBlob(colName);
      if (blobLocator != null && blobLocator.length() != 0L) {
         InputStream binaryInput = blobLocator.getBinaryStream();
         if (null != binaryInput && (!(binaryInput instanceof ByteArrayInputStream) || ((ByteArrayInputStream)binaryInput).available() != 0)) {
            ObjectInputStream in = new ObjectInputStream(binaryInput);

            try {
               obj = in.readObject();
            } finally {
               in.close();
            }
         }
      }

      return obj;
   }

   protected Object getJobDataFromBlob(ResultSet rs, String colName) throws ClassNotFoundException, IOException, SQLException {
      if (this.canUseProperties()) {
         Blob blobLocator = rs.getBlob(colName);
         if (blobLocator != null) {
            InputStream binaryInput = blobLocator.getBinaryStream();
            return binaryInput;
         } else {
            return null;
         }
      } else {
         return this.getObjectFromBlob(rs, colName);
      }
   }

   public Set<String> selectPausedTriggerGroups(Connection conn) throws SQLException {
      PreparedStatement ps = null;
      ResultSet rs = null;
      HashSet set = new HashSet();

      try {
         ps = conn.prepareStatement(this.rtp("SELECT TRIGGER_GROUP FROM {0}PAUSED_TRIGGER_GRPS WHERE SCHED_NAME = {1}"));
         rs = ps.executeQuery();

         while(rs.next()) {
            String groupName = rs.getString("TRIGGER_GROUP");
            set.add(groupName);
         }

         HashSet var9 = set;
         return var9;
      } finally {
         closeResultSet(rs);
         closeStatement(ps);
      }
   }

   protected static void closeResultSet(ResultSet rs) {
      if (null != rs) {
         try {
            rs.close();
         } catch (SQLException var2) {
         }
      }

   }

   protected static void closeStatement(Statement statement) {
      if (null != statement) {
         try {
            statement.close();
         } catch (SQLException var2) {
         }
      }

   }

   protected void setBoolean(PreparedStatement ps, int index, boolean val) throws SQLException {
      ps.setBoolean(index, val);
   }

   protected boolean getBoolean(ResultSet rs, String columnName) throws SQLException {
      return rs.getBoolean(columnName);
   }

   protected boolean getBoolean(ResultSet rs, int columnIndex) throws SQLException {
      return rs.getBoolean(columnIndex);
   }

   protected void setBytes(PreparedStatement ps, int index, ByteArrayOutputStream baos) throws SQLException {
      ps.setBytes(index, baos == null ? new byte[0] : baos.toByteArray());
   }
}
