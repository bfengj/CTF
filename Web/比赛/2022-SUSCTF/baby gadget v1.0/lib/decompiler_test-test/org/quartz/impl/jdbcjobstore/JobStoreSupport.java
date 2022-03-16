package org.quartz.impl.jdbcjobstore;

import java.io.IOException;
import java.lang.reflect.InvocationHandler;
import java.lang.reflect.Proxy;
import java.sql.Connection;
import java.sql.SQLException;
import java.util.ArrayList;
import java.util.Date;
import java.util.HashMap;
import java.util.HashSet;
import java.util.Iterator;
import java.util.LinkedList;
import java.util.List;
import java.util.Map;
import java.util.Set;
import org.quartz.Calendar;
import org.quartz.JobDataMap;
import org.quartz.JobDetail;
import org.quartz.JobKey;
import org.quartz.JobPersistenceException;
import org.quartz.ObjectAlreadyExistsException;
import org.quartz.SchedulerConfigException;
import org.quartz.SchedulerException;
import org.quartz.Trigger;
import org.quartz.TriggerKey;
import org.quartz.impl.DefaultThreadExecutor;
import org.quartz.impl.matchers.GroupMatcher;
import org.quartz.impl.matchers.StringMatcher;
import org.quartz.impl.triggers.SimpleTriggerImpl;
import org.quartz.spi.ClassLoadHelper;
import org.quartz.spi.JobStore;
import org.quartz.spi.OperableTrigger;
import org.quartz.spi.SchedulerSignaler;
import org.quartz.spi.ThreadExecutor;
import org.quartz.spi.TriggerFiredBundle;
import org.quartz.spi.TriggerFiredResult;
import org.quartz.utils.DBConnectionManager;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

public abstract class JobStoreSupport implements JobStore, Constants {
   protected static final String LOCK_TRIGGER_ACCESS = "TRIGGER_ACCESS";
   protected static final String LOCK_STATE_ACCESS = "STATE_ACCESS";
   protected String dsName;
   protected String tablePrefix = "QRTZ_";
   protected boolean useProperties = false;
   protected String instanceId;
   protected String instanceName;
   protected String delegateClassName;
   protected String delegateInitString;
   protected Class<? extends DriverDelegate> delegateClass = StdJDBCDelegate.class;
   protected HashMap<String, Calendar> calendarCache = new HashMap();
   private DriverDelegate delegate;
   private long misfireThreshold = 60000L;
   private boolean dontSetAutoCommitFalse = false;
   private boolean isClustered = false;
   private boolean useDBLocks = false;
   private boolean lockOnInsert = true;
   private Semaphore lockHandler = null;
   private String selectWithLockSQL = null;
   private long clusterCheckinInterval = 7500L;
   private JobStoreSupport.ClusterManager clusterManagementThread = null;
   private JobStoreSupport.MisfireHandler misfireHandler = null;
   private ClassLoadHelper classLoadHelper;
   private SchedulerSignaler schedSignaler;
   protected int maxToRecoverAtATime = 20;
   private boolean setTxIsolationLevelSequential = false;
   private boolean acquireTriggersWithinLock = false;
   private long dbRetryInterval = 15000L;
   private boolean makeThreadsDaemons = false;
   private boolean threadsInheritInitializersClassLoadContext = false;
   private ClassLoader initializersLoader = null;
   private boolean doubleCheckLockMisfireHandler = true;
   private final Logger log = LoggerFactory.getLogger(this.getClass());
   private ThreadExecutor threadExecutor = new DefaultThreadExecutor();
   private volatile boolean schedulerRunning = false;
   private volatile boolean shutdown = false;
   private static long ftrCtr = System.currentTimeMillis();
   protected ThreadLocal<Long> sigChangeForTxCompletion = new ThreadLocal();
   protected boolean firstCheckIn = true;
   protected long lastCheckin = System.currentTimeMillis();

   public void setDataSource(String dsName) {
      this.dsName = dsName;
   }

   public String getDataSource() {
      return this.dsName;
   }

   public void setTablePrefix(String prefix) {
      if (prefix == null) {
         prefix = "";
      }

      this.tablePrefix = prefix;
   }

   public String getTablePrefix() {
      return this.tablePrefix;
   }

   public void setUseProperties(String useProp) {
      if (useProp == null) {
         useProp = "false";
      }

      this.useProperties = Boolean.valueOf(useProp);
   }

   public boolean canUseProperties() {
      return this.useProperties;
   }

   public void setInstanceId(String instanceId) {
      this.instanceId = instanceId;
   }

   public String getInstanceId() {
      return this.instanceId;
   }

   public void setInstanceName(String instanceName) {
      this.instanceName = instanceName;
   }

   public void setThreadPoolSize(int poolSize) {
   }

   public void setThreadExecutor(ThreadExecutor threadExecutor) {
      this.threadExecutor = threadExecutor;
   }

   public ThreadExecutor getThreadExecutor() {
      return this.threadExecutor;
   }

   public String getInstanceName() {
      return this.instanceName;
   }

   public long getEstimatedTimeToReleaseAndAcquireTrigger() {
      return 70L;
   }

   public void setIsClustered(boolean isClustered) {
      this.isClustered = isClustered;
   }

   public boolean isClustered() {
      return this.isClustered;
   }

   public long getClusterCheckinInterval() {
      return this.clusterCheckinInterval;
   }

   public void setClusterCheckinInterval(long l) {
      this.clusterCheckinInterval = l;
   }

   public int getMaxMisfiresToHandleAtATime() {
      return this.maxToRecoverAtATime;
   }

   public void setMaxMisfiresToHandleAtATime(int maxToRecoverAtATime) {
      this.maxToRecoverAtATime = maxToRecoverAtATime;
   }

   public long getDbRetryInterval() {
      return this.dbRetryInterval;
   }

   public void setDbRetryInterval(long dbRetryInterval) {
      this.dbRetryInterval = dbRetryInterval;
   }

   public void setUseDBLocks(boolean useDBLocks) {
      this.useDBLocks = useDBLocks;
   }

   public boolean getUseDBLocks() {
      return this.useDBLocks;
   }

   public boolean isLockOnInsert() {
      return this.lockOnInsert;
   }

   public void setLockOnInsert(boolean lockOnInsert) {
      this.lockOnInsert = lockOnInsert;
   }

   public long getMisfireThreshold() {
      return this.misfireThreshold;
   }

   public void setMisfireThreshold(long misfireThreshold) {
      if (misfireThreshold < 1L) {
         throw new IllegalArgumentException("Misfirethreshold must be larger than 0");
      } else {
         this.misfireThreshold = misfireThreshold;
      }
   }

   public boolean isDontSetAutoCommitFalse() {
      return this.dontSetAutoCommitFalse;
   }

   public void setDontSetAutoCommitFalse(boolean b) {
      this.dontSetAutoCommitFalse = b;
   }

   public boolean isTxIsolationLevelSerializable() {
      return this.setTxIsolationLevelSequential;
   }

   public void setTxIsolationLevelSerializable(boolean b) {
      this.setTxIsolationLevelSequential = b;
   }

   public boolean isAcquireTriggersWithinLock() {
      return this.acquireTriggersWithinLock;
   }

   public void setAcquireTriggersWithinLock(boolean acquireTriggersWithinLock) {
      this.acquireTriggersWithinLock = acquireTriggersWithinLock;
   }

   public void setDriverDelegateClass(String delegateClassName) throws InvalidConfigurationException {
      synchronized(this) {
         this.delegateClassName = delegateClassName;
      }
   }

   public String getDriverDelegateClass() {
      return this.delegateClassName;
   }

   public void setDriverDelegateInitString(String delegateInitString) throws InvalidConfigurationException {
      this.delegateInitString = delegateInitString;
   }

   public String getDriverDelegateInitString() {
      return this.delegateInitString;
   }

   public String getSelectWithLockSQL() {
      return this.selectWithLockSQL;
   }

   public void setSelectWithLockSQL(String string) {
      this.selectWithLockSQL = string;
   }

   protected ClassLoadHelper getClassLoadHelper() {
      return this.classLoadHelper;
   }

   public boolean getMakeThreadsDaemons() {
      return this.makeThreadsDaemons;
   }

   public void setMakeThreadsDaemons(boolean makeThreadsDaemons) {
      this.makeThreadsDaemons = makeThreadsDaemons;
   }

   public boolean isThreadsInheritInitializersClassLoadContext() {
      return this.threadsInheritInitializersClassLoadContext;
   }

   public void setThreadsInheritInitializersClassLoadContext(boolean threadsInheritInitializersClassLoadContext) {
      this.threadsInheritInitializersClassLoadContext = threadsInheritInitializersClassLoadContext;
   }

   public boolean getDoubleCheckLockMisfireHandler() {
      return this.doubleCheckLockMisfireHandler;
   }

   public void setDoubleCheckLockMisfireHandler(boolean doubleCheckLockMisfireHandler) {
      this.doubleCheckLockMisfireHandler = doubleCheckLockMisfireHandler;
   }

   protected Logger getLog() {
      return this.log;
   }

   public void initialize(ClassLoadHelper loadHelper, SchedulerSignaler signaler) throws SchedulerConfigException {
      if (this.dsName == null) {
         throw new SchedulerConfigException("DataSource name not set.");
      } else {
         this.classLoadHelper = loadHelper;
         if (this.isThreadsInheritInitializersClassLoadContext()) {
            this.log.info("JDBCJobStore threads will inherit ContextClassLoader of thread: " + Thread.currentThread().getName());
            this.initializersLoader = Thread.currentThread().getContextClassLoader();
         }

         this.schedSignaler = signaler;
         if (this.getLockHandler() == null) {
            if (this.isClustered()) {
               this.setUseDBLocks(true);
            }

            if (this.getUseDBLocks()) {
               if (this.getDriverDelegateClass() != null && this.getDriverDelegateClass().equals(MSSQLDelegate.class.getName()) && this.getSelectWithLockSQL() == null) {
                  String msSqlDflt = "SELECT * FROM {0}LOCKS WITH (UPDLOCK,ROWLOCK) WHERE SCHED_NAME = {1} AND LOCK_NAME = ?";
                  this.getLog().info("Detected usage of MSSQLDelegate class - defaulting 'selectWithLockSQL' to '" + msSqlDflt + "'.");
                  this.setSelectWithLockSQL(msSqlDflt);
               }

               this.getLog().info("Using db table-based data access locking (synchronization).");
               this.setLockHandler(new StdRowLockSemaphore(this.getTablePrefix(), this.getInstanceName(), this.getSelectWithLockSQL()));
            } else {
               this.getLog().info("Using thread monitor-based data access locking (synchronization).");
               this.setLockHandler(new SimpleSemaphore());
            }
         }

      }
   }

   public void schedulerStarted() throws SchedulerException {
      if (this.isClustered()) {
         this.clusterManagementThread = new JobStoreSupport.ClusterManager();
         if (this.initializersLoader != null) {
            this.clusterManagementThread.setContextClassLoader(this.initializersLoader);
         }

         this.clusterManagementThread.initialize();
      } else {
         try {
            this.recoverJobs();
         } catch (SchedulerException var2) {
            throw new SchedulerConfigException("Failure occured during job recovery.", var2);
         }
      }

      this.misfireHandler = new JobStoreSupport.MisfireHandler();
      if (this.initializersLoader != null) {
         this.misfireHandler.setContextClassLoader(this.initializersLoader);
      }

      this.misfireHandler.initialize();
      this.schedulerRunning = true;
      this.getLog().debug("JobStore background threads started (as scheduler was started).");
   }

   public void schedulerPaused() {
      this.schedulerRunning = false;
   }

   public void schedulerResumed() {
      this.schedulerRunning = true;
   }

   public void shutdown() {
      this.shutdown = true;
      if (this.misfireHandler != null) {
         this.misfireHandler.shutdown();

         try {
            this.misfireHandler.join();
         } catch (InterruptedException var4) {
         }
      }

      if (this.clusterManagementThread != null) {
         this.clusterManagementThread.shutdown();

         try {
            this.clusterManagementThread.join();
         } catch (InterruptedException var3) {
         }
      }

      try {
         DBConnectionManager.getInstance().shutdown(this.getDataSource());
      } catch (SQLException var2) {
         this.getLog().warn("Database connection shutdown unsuccessful.", var2);
      }

      this.getLog().debug("JobStore background threads shutdown.");
   }

   public boolean supportsPersistence() {
      return true;
   }

   protected abstract Connection getNonManagedTXConnection() throws JobPersistenceException;

   protected Connection getAttributeRestoringConnection(Connection conn) {
      return (Connection)Proxy.newProxyInstance(Thread.currentThread().getContextClassLoader(), new Class[]{Connection.class}, new AttributeRestoringConnectionInvocationHandler(conn));
   }

   protected Connection getConnection() throws JobPersistenceException {
      Connection conn;
      try {
         conn = DBConnectionManager.getInstance().getConnection(this.getDataSource());
      } catch (SQLException var7) {
         throw new JobPersistenceException("Failed to obtain DB connection from data source '" + this.getDataSource() + "': " + var7.toString(), var7);
      } catch (Throwable var8) {
         throw new JobPersistenceException("Failed to obtain DB connection from data source '" + this.getDataSource() + "': " + var8.toString(), var8);
      }

      if (conn == null) {
         throw new JobPersistenceException("Could not get connection from DataSource '" + this.getDataSource() + "'");
      } else {
         conn = this.getAttributeRestoringConnection(conn);

         try {
            if (!this.isDontSetAutoCommitFalse()) {
               conn.setAutoCommit(false);
            }

            if (this.isTxIsolationLevelSerializable()) {
               conn.setTransactionIsolation(8);
            }
         } catch (SQLException var5) {
            this.getLog().warn("Failed to override connection auto commit/transaction isolation.", var5);
         } catch (Throwable var6) {
            try {
               conn.close();
            } catch (Throwable var4) {
            }

            throw new JobPersistenceException("Failure setting up connection.", var6);
         }

         return conn;
      }
   }

   protected void releaseLock(String lockName, boolean doIt) {
      if (doIt) {
         try {
            this.getLockHandler().releaseLock(lockName);
         } catch (LockException var4) {
            this.getLog().error("Error returning lock: " + var4.getMessage(), var4);
         }
      }

   }

   protected void recoverJobs() throws JobPersistenceException {
      this.executeInNonManagedTXLock("TRIGGER_ACCESS", new JobStoreSupport.VoidTransactionCallback() {
         public void executeVoid(Connection conn) throws JobPersistenceException {
            JobStoreSupport.this.recoverJobs(conn);
         }
      }, (JobStoreSupport.TransactionValidator)null);
   }

   protected void recoverJobs(Connection conn) throws JobPersistenceException {
      try {
         int rows = this.getDelegate().updateTriggerStatesFromOtherStates(conn, "WAITING", "ACQUIRED", "BLOCKED");
         rows += this.getDelegate().updateTriggerStatesFromOtherStates(conn, "PAUSED", "PAUSED_BLOCKED", "PAUSED_BLOCKED");
         this.getLog().info("Freed " + rows + " triggers from 'acquired' / 'blocked' state.");
         this.recoverMisfiredJobs(conn, true);
         List<OperableTrigger> recoveringJobTriggers = this.getDelegate().selectTriggersForRecoveringJobs(conn);
         this.getLog().info("Recovering " + recoveringJobTriggers.size() + " jobs that were in-progress at the time of the last shut-down.");
         Iterator i$ = recoveringJobTriggers.iterator();

         while(i$.hasNext()) {
            OperableTrigger recoveringJobTrigger = (OperableTrigger)i$.next();
            if (this.jobExists(conn, recoveringJobTrigger.getJobKey())) {
               recoveringJobTrigger.computeFirstFireTime((Calendar)null);
               this.storeTrigger(conn, recoveringJobTrigger, (JobDetail)null, false, "WAITING", false, true);
            }
         }

         this.getLog().info("Recovery complete.");
         List<TriggerKey> cts = this.getDelegate().selectTriggersInState(conn, "COMPLETE");
         Iterator i$ = cts.iterator();

         while(i$.hasNext()) {
            TriggerKey ct = (TriggerKey)i$.next();
            this.removeTrigger(conn, ct);
         }

         this.getLog().info("Removed " + cts.size() + " 'complete' triggers.");
         int n = this.getDelegate().deleteFiredTriggers(conn);
         this.getLog().info("Removed " + n + " stale fired job entries.");
      } catch (JobPersistenceException var7) {
         throw var7;
      } catch (Exception var8) {
         throw new JobPersistenceException("Couldn't recover jobs: " + var8.getMessage(), var8);
      }
   }

   protected long getMisfireTime() {
      long misfireTime = System.currentTimeMillis();
      if (this.getMisfireThreshold() > 0L) {
         misfireTime -= this.getMisfireThreshold();
      }

      return misfireTime > 0L ? misfireTime : 0L;
   }

   protected JobStoreSupport.RecoverMisfiredJobsResult recoverMisfiredJobs(Connection conn, boolean recovering) throws JobPersistenceException, SQLException {
      int maxMisfiresToHandleAtATime = recovering ? -1 : this.getMaxMisfiresToHandleAtATime();
      List<TriggerKey> misfiredTriggers = new LinkedList();
      long earliestNewTime = Long.MAX_VALUE;
      boolean hasMoreMisfiredTriggers = this.getDelegate().hasMisfiredTriggersInState(conn, "WAITING", this.getMisfireTime(), maxMisfiresToHandleAtATime, misfiredTriggers);
      if (hasMoreMisfiredTriggers) {
         this.getLog().info("Handling the first " + misfiredTriggers.size() + " triggers that missed their scheduled fire-time.  " + "More misfired triggers remain to be processed.");
      } else {
         if (misfiredTriggers.size() <= 0) {
            this.getLog().debug("Found 0 triggers that missed their scheduled fire-time.");
            return JobStoreSupport.RecoverMisfiredJobsResult.NO_OP;
         }

         this.getLog().info("Handling " + misfiredTriggers.size() + " trigger(s) that missed their scheduled fire-time.");
      }

      Iterator i$ = misfiredTriggers.iterator();

      while(i$.hasNext()) {
         TriggerKey triggerKey = (TriggerKey)i$.next();
         OperableTrigger trig = this.retrieveTrigger(conn, triggerKey);
         if (trig != null) {
            this.doUpdateOfMisfiredTrigger(conn, trig, false, "WAITING", recovering);
            if (trig.getNextFireTime() != null && trig.getNextFireTime().getTime() < earliestNewTime) {
               earliestNewTime = trig.getNextFireTime().getTime();
            }
         }
      }

      return new JobStoreSupport.RecoverMisfiredJobsResult(hasMoreMisfiredTriggers, misfiredTriggers.size(), earliestNewTime);
   }

   protected boolean updateMisfiredTrigger(Connection conn, TriggerKey triggerKey, String newStateIfNotComplete, boolean forceState) throws JobPersistenceException {
      try {
         OperableTrigger trig = this.retrieveTrigger(conn, triggerKey);
         long misfireTime = System.currentTimeMillis();
         if (this.getMisfireThreshold() > 0L) {
            misfireTime -= this.getMisfireThreshold();
         }

         if (trig.getNextFireTime().getTime() > misfireTime) {
            return false;
         } else {
            this.doUpdateOfMisfiredTrigger(conn, trig, forceState, newStateIfNotComplete, false);
            return true;
         }
      } catch (Exception var8) {
         throw new JobPersistenceException("Couldn't update misfired trigger '" + triggerKey + "': " + var8.getMessage(), var8);
      }
   }

   private void doUpdateOfMisfiredTrigger(Connection conn, OperableTrigger trig, boolean forceState, String newStateIfNotComplete, boolean recovering) throws JobPersistenceException {
      Calendar cal = null;
      if (trig.getCalendarName() != null) {
         cal = this.retrieveCalendar(conn, trig.getCalendarName());
      }

      this.schedSignaler.notifyTriggerListenersMisfired(trig);
      trig.updateAfterMisfire(cal);
      if (trig.getNextFireTime() == null) {
         this.storeTrigger(conn, trig, (JobDetail)null, true, "COMPLETE", forceState, recovering);
         this.schedSignaler.notifySchedulerListenersFinalized(trig);
      } else {
         this.storeTrigger(conn, trig, (JobDetail)null, true, newStateIfNotComplete, forceState, false);
      }

   }

   public void storeJobAndTrigger(final JobDetail newJob, final OperableTrigger newTrigger) throws JobPersistenceException {
      this.executeInLock(this.isLockOnInsert() ? "TRIGGER_ACCESS" : null, new JobStoreSupport.VoidTransactionCallback() {
         public void executeVoid(Connection conn) throws JobPersistenceException {
            JobStoreSupport.this.storeJob(conn, newJob, false);
            JobStoreSupport.this.storeTrigger(conn, newTrigger, newJob, false, "WAITING", false, false);
         }
      });
   }

   public void storeJob(final JobDetail newJob, final boolean replaceExisting) throws JobPersistenceException {
      this.executeInLock(!this.isLockOnInsert() && !replaceExisting ? null : "TRIGGER_ACCESS", new JobStoreSupport.VoidTransactionCallback() {
         public void executeVoid(Connection conn) throws JobPersistenceException {
            JobStoreSupport.this.storeJob(conn, newJob, replaceExisting);
         }
      });
   }

   protected void storeJob(Connection conn, JobDetail newJob, boolean replaceExisting) throws JobPersistenceException {
      boolean existingJob = this.jobExists(conn, newJob.getKey());

      try {
         if (existingJob) {
            if (!replaceExisting) {
               throw new ObjectAlreadyExistsException(newJob);
            }

            this.getDelegate().updateJobDetail(conn, newJob);
         } else {
            this.getDelegate().insertJobDetail(conn, newJob);
         }

      } catch (IOException var6) {
         throw new JobPersistenceException("Couldn't store job: " + var6.getMessage(), var6);
      } catch (SQLException var7) {
         throw new JobPersistenceException("Couldn't store job: " + var7.getMessage(), var7);
      }
   }

   protected boolean jobExists(Connection conn, JobKey jobKey) throws JobPersistenceException {
      try {
         return this.getDelegate().jobExists(conn, jobKey);
      } catch (SQLException var4) {
         throw new JobPersistenceException("Couldn't determine job existence (" + jobKey + "): " + var4.getMessage(), var4);
      }
   }

   public void storeTrigger(final OperableTrigger newTrigger, final boolean replaceExisting) throws JobPersistenceException {
      this.executeInLock(!this.isLockOnInsert() && !replaceExisting ? null : "TRIGGER_ACCESS", new JobStoreSupport.VoidTransactionCallback() {
         public void executeVoid(Connection conn) throws JobPersistenceException {
            JobStoreSupport.this.storeTrigger(conn, newTrigger, (JobDetail)null, replaceExisting, "WAITING", false, false);
         }
      });
   }

   protected void storeTrigger(Connection conn, OperableTrigger newTrigger, JobDetail job, boolean replaceExisting, String state, boolean forceState, boolean recovering) throws JobPersistenceException {
      boolean existingTrigger = this.triggerExists(conn, newTrigger.getKey());
      if (existingTrigger && !replaceExisting) {
         throw new ObjectAlreadyExistsException(newTrigger);
      } else {
         try {
            if (!forceState) {
               boolean shouldBepaused = this.getDelegate().isTriggerGroupPaused(conn, newTrigger.getKey().getGroup());
               if (!shouldBepaused) {
                  shouldBepaused = this.getDelegate().isTriggerGroupPaused(conn, "_$_ALL_GROUPS_PAUSED_$_");
                  if (shouldBepaused) {
                     this.getDelegate().insertPausedTriggerGroup(conn, newTrigger.getKey().getGroup());
                  }
               }

               if (shouldBepaused && (state.equals("WAITING") || state.equals("ACQUIRED"))) {
                  state = "PAUSED";
               }
            }

            if (job == null) {
               job = this.getDelegate().selectJobDetail(conn, newTrigger.getJobKey(), this.getClassLoadHelper());
            }

            if (job == null) {
               throw new JobPersistenceException("The job (" + newTrigger.getJobKey() + ") referenced by the trigger does not exist.");
            } else {
               if (job.isConcurrentExectionDisallowed() && !recovering) {
                  state = this.checkBlockedState(conn, job.getKey(), state);
               }

               if (existingTrigger) {
                  this.getDelegate().updateTrigger(conn, newTrigger, state, job);
               } else {
                  this.getDelegate().insertTrigger(conn, newTrigger, state, job);
               }

            }
         } catch (Exception var10) {
            throw new JobPersistenceException("Couldn't store trigger '" + newTrigger.getKey() + "' for '" + newTrigger.getJobKey() + "' job:" + var10.getMessage(), var10);
         }
      }
   }

   protected boolean triggerExists(Connection conn, TriggerKey key) throws JobPersistenceException {
      try {
         return this.getDelegate().triggerExists(conn, key);
      } catch (SQLException var4) {
         throw new JobPersistenceException("Couldn't determine trigger existence (" + key + "): " + var4.getMessage(), var4);
      }
   }

   public boolean removeJob(final JobKey jobKey) throws JobPersistenceException {
      return (Boolean)this.executeInLock("TRIGGER_ACCESS", new JobStoreSupport.TransactionCallback() {
         public Object execute(Connection conn) throws JobPersistenceException {
            return JobStoreSupport.this.removeJob(conn, jobKey) ? Boolean.TRUE : Boolean.FALSE;
         }
      });
   }

   protected boolean removeJob(Connection conn, JobKey jobKey) throws JobPersistenceException {
      try {
         List<TriggerKey> jobTriggers = this.getDelegate().selectTriggerKeysForJob(conn, jobKey);
         Iterator i$ = jobTriggers.iterator();

         while(i$.hasNext()) {
            TriggerKey jobTrigger = (TriggerKey)i$.next();
            this.deleteTriggerAndChildren(conn, jobTrigger);
         }

         return this.deleteJobAndChildren(conn, jobKey);
      } catch (SQLException var6) {
         throw new JobPersistenceException("Couldn't remove job: " + var6.getMessage(), var6);
      }
   }

   public boolean removeJobs(final List<JobKey> jobKeys) throws JobPersistenceException {
      return (Boolean)this.executeInLock("TRIGGER_ACCESS", new JobStoreSupport.TransactionCallback() {
         public Object execute(Connection conn) throws JobPersistenceException {
            boolean allFound = true;

            JobKey jobKey;
            for(Iterator i$ = jobKeys.iterator(); i$.hasNext(); allFound = JobStoreSupport.this.removeJob(conn, jobKey) && allFound) {
               jobKey = (JobKey)i$.next();
            }

            return allFound ? Boolean.TRUE : Boolean.FALSE;
         }
      });
   }

   public boolean removeTriggers(final List<TriggerKey> triggerKeys) throws JobPersistenceException {
      return (Boolean)this.executeInLock("TRIGGER_ACCESS", new JobStoreSupport.TransactionCallback() {
         public Object execute(Connection conn) throws JobPersistenceException {
            boolean allFound = true;

            TriggerKey triggerKey;
            for(Iterator i$ = triggerKeys.iterator(); i$.hasNext(); allFound = JobStoreSupport.this.removeTrigger(conn, triggerKey) && allFound) {
               triggerKey = (TriggerKey)i$.next();
            }

            return allFound ? Boolean.TRUE : Boolean.FALSE;
         }
      });
   }

   public void storeJobsAndTriggers(final Map<JobDetail, Set<? extends Trigger>> triggersAndJobs, final boolean replace) throws JobPersistenceException {
      this.executeInLock(!this.isLockOnInsert() && !replace ? null : "TRIGGER_ACCESS", new JobStoreSupport.VoidTransactionCallback() {
         public void executeVoid(Connection conn) throws JobPersistenceException {
            Iterator i$ = triggersAndJobs.keySet().iterator();

            while(i$.hasNext()) {
               JobDetail job = (JobDetail)i$.next();
               JobStoreSupport.this.storeJob(conn, job, replace);
               Iterator i$x = ((Set)triggersAndJobs.get(job)).iterator();

               while(i$x.hasNext()) {
                  Trigger trigger = (Trigger)i$x.next();
                  JobStoreSupport.this.storeTrigger(conn, (OperableTrigger)trigger, job, replace, "WAITING", false, false);
               }
            }

         }
      });
   }

   private boolean deleteJobAndChildren(Connection conn, JobKey key) throws NoSuchDelegateException, SQLException {
      return this.getDelegate().deleteJobDetail(conn, key) > 0;
   }

   private boolean deleteTriggerAndChildren(Connection conn, TriggerKey key) throws SQLException, NoSuchDelegateException {
      return this.getDelegate().deleteTrigger(conn, key) > 0;
   }

   public JobDetail retrieveJob(final JobKey jobKey) throws JobPersistenceException {
      return (JobDetail)this.executeWithoutLock(new JobStoreSupport.TransactionCallback() {
         public Object execute(Connection conn) throws JobPersistenceException {
            return JobStoreSupport.this.retrieveJob(conn, jobKey);
         }
      });
   }

   protected JobDetail retrieveJob(Connection conn, JobKey key) throws JobPersistenceException {
      try {
         return this.getDelegate().selectJobDetail(conn, key, this.getClassLoadHelper());
      } catch (ClassNotFoundException var4) {
         throw new JobPersistenceException("Couldn't retrieve job because a required class was not found: " + var4.getMessage(), var4);
      } catch (IOException var5) {
         throw new JobPersistenceException("Couldn't retrieve job because the BLOB couldn't be deserialized: " + var5.getMessage(), var5);
      } catch (SQLException var6) {
         throw new JobPersistenceException("Couldn't retrieve job: " + var6.getMessage(), var6);
      }
   }

   public boolean removeTrigger(final TriggerKey triggerKey) throws JobPersistenceException {
      return (Boolean)this.executeInLock("TRIGGER_ACCESS", new JobStoreSupport.TransactionCallback() {
         public Object execute(Connection conn) throws JobPersistenceException {
            return JobStoreSupport.this.removeTrigger(conn, triggerKey) ? Boolean.TRUE : Boolean.FALSE;
         }
      });
   }

   protected boolean removeTrigger(Connection conn, TriggerKey key) throws JobPersistenceException {
      try {
         JobDetail job = this.getDelegate().selectJobForTrigger(conn, this.getClassLoadHelper(), key, false);
         boolean removedTrigger = this.deleteTriggerAndChildren(conn, key);
         if (null != job && !job.isDurable()) {
            int numTriggers = this.getDelegate().selectNumTriggersForJob(conn, job.getKey());
            if (numTriggers == 0) {
               this.deleteJobAndChildren(conn, job.getKey());
            }
         }

         return removedTrigger;
      } catch (ClassNotFoundException var6) {
         throw new JobPersistenceException("Couldn't remove trigger: " + var6.getMessage(), var6);
      } catch (SQLException var7) {
         throw new JobPersistenceException("Couldn't remove trigger: " + var7.getMessage(), var7);
      }
   }

   public boolean replaceTrigger(final TriggerKey triggerKey, final OperableTrigger newTrigger) throws JobPersistenceException {
      return (Boolean)this.executeInLock("TRIGGER_ACCESS", new JobStoreSupport.TransactionCallback() {
         public Object execute(Connection conn) throws JobPersistenceException {
            return JobStoreSupport.this.replaceTrigger(conn, triggerKey, newTrigger) ? Boolean.TRUE : Boolean.FALSE;
         }
      });
   }

   protected boolean replaceTrigger(Connection conn, TriggerKey key, OperableTrigger newTrigger) throws JobPersistenceException {
      try {
         JobDetail job = this.getDelegate().selectJobForTrigger(conn, this.getClassLoadHelper(), key);
         if (job == null) {
            return false;
         } else if (!newTrigger.getJobKey().equals(job.getKey())) {
            throw new JobPersistenceException("New trigger is not related to the same job as the old trigger.");
         } else {
            boolean removedTrigger = this.deleteTriggerAndChildren(conn, key);
            this.storeTrigger(conn, newTrigger, job, false, "WAITING", false, false);
            return removedTrigger;
         }
      } catch (ClassNotFoundException var6) {
         throw new JobPersistenceException("Couldn't remove trigger: " + var6.getMessage(), var6);
      } catch (SQLException var7) {
         throw new JobPersistenceException("Couldn't remove trigger: " + var7.getMessage(), var7);
      }
   }

   public OperableTrigger retrieveTrigger(final TriggerKey triggerKey) throws JobPersistenceException {
      return (OperableTrigger)this.executeWithoutLock(new JobStoreSupport.TransactionCallback() {
         public Object execute(Connection conn) throws JobPersistenceException {
            return JobStoreSupport.this.retrieveTrigger(conn, triggerKey);
         }
      });
   }

   protected OperableTrigger retrieveTrigger(Connection conn, TriggerKey key) throws JobPersistenceException {
      try {
         return this.getDelegate().selectTrigger(conn, key);
      } catch (Exception var4) {
         throw new JobPersistenceException("Couldn't retrieve trigger: " + var4.getMessage(), var4);
      }
   }

   public Trigger.TriggerState getTriggerState(final TriggerKey triggerKey) throws JobPersistenceException {
      return (Trigger.TriggerState)this.executeWithoutLock(new JobStoreSupport.TransactionCallback() {
         public Object execute(Connection conn) throws JobPersistenceException {
            return JobStoreSupport.this.getTriggerState(conn, triggerKey);
         }
      });
   }

   public Trigger.TriggerState getTriggerState(Connection conn, TriggerKey key) throws JobPersistenceException {
      try {
         String ts = this.getDelegate().selectTriggerState(conn, key);
         if (ts == null) {
            return Trigger.TriggerState.NONE;
         } else if (ts.equals("DELETED")) {
            return Trigger.TriggerState.NONE;
         } else if (ts.equals("COMPLETE")) {
            return Trigger.TriggerState.COMPLETE;
         } else if (ts.equals("PAUSED")) {
            return Trigger.TriggerState.PAUSED;
         } else if (ts.equals("PAUSED_BLOCKED")) {
            return Trigger.TriggerState.PAUSED;
         } else if (ts.equals("ERROR")) {
            return Trigger.TriggerState.ERROR;
         } else {
            return ts.equals("BLOCKED") ? Trigger.TriggerState.BLOCKED : Trigger.TriggerState.NORMAL;
         }
      } catch (SQLException var4) {
         throw new JobPersistenceException("Couldn't determine state of trigger (" + key + "): " + var4.getMessage(), var4);
      }
   }

   public void storeCalendar(final String calName, final Calendar calendar, final boolean replaceExisting, final boolean updateTriggers) throws JobPersistenceException {
      this.executeInLock(!this.isLockOnInsert() && !updateTriggers ? null : "TRIGGER_ACCESS", new JobStoreSupport.VoidTransactionCallback() {
         public void executeVoid(Connection conn) throws JobPersistenceException {
            JobStoreSupport.this.storeCalendar(conn, calName, calendar, replaceExisting, updateTriggers);
         }
      });
   }

   protected void storeCalendar(Connection conn, String calName, Calendar calendar, boolean replaceExisting, boolean updateTriggers) throws JobPersistenceException {
      try {
         boolean existingCal = this.calendarExists(conn, calName);
         if (existingCal && !replaceExisting) {
            throw new ObjectAlreadyExistsException("Calendar with name '" + calName + "' already exists.");
         } else {
            if (existingCal) {
               if (this.getDelegate().updateCalendar(conn, calName, calendar) < 1) {
                  throw new JobPersistenceException("Couldn't store calendar.  Update failed.");
               }

               if (updateTriggers) {
                  List<OperableTrigger> trigs = this.getDelegate().selectTriggersForCalendar(conn, calName);
                  Iterator i$ = trigs.iterator();

                  while(i$.hasNext()) {
                     OperableTrigger trigger = (OperableTrigger)i$.next();
                     trigger.updateWithNewCalendar(calendar, this.getMisfireThreshold());
                     this.storeTrigger(conn, trigger, (JobDetail)null, true, "WAITING", false, false);
                  }
               }
            } else if (this.getDelegate().insertCalendar(conn, calName, calendar) < 1) {
               throw new JobPersistenceException("Couldn't store calendar.  Insert failed.");
            }

            if (!this.isClustered) {
               this.calendarCache.put(calName, calendar);
            }

         }
      } catch (IOException var10) {
         throw new JobPersistenceException("Couldn't store calendar because the BLOB couldn't be serialized: " + var10.getMessage(), var10);
      } catch (ClassNotFoundException var11) {
         throw new JobPersistenceException("Couldn't store calendar: " + var11.getMessage(), var11);
      } catch (SQLException var12) {
         throw new JobPersistenceException("Couldn't store calendar: " + var12.getMessage(), var12);
      }
   }

   protected boolean calendarExists(Connection conn, String calName) throws JobPersistenceException {
      try {
         return this.getDelegate().calendarExists(conn, calName);
      } catch (SQLException var4) {
         throw new JobPersistenceException("Couldn't determine calendar existence (" + calName + "): " + var4.getMessage(), var4);
      }
   }

   public boolean removeCalendar(final String calName) throws JobPersistenceException {
      return (Boolean)this.executeInLock("TRIGGER_ACCESS", new JobStoreSupport.TransactionCallback() {
         public Object execute(Connection conn) throws JobPersistenceException {
            return JobStoreSupport.this.removeCalendar(conn, calName) ? Boolean.TRUE : Boolean.FALSE;
         }
      });
   }

   protected boolean removeCalendar(Connection conn, String calName) throws JobPersistenceException {
      try {
         if (this.getDelegate().calendarIsReferenced(conn, calName)) {
            throw new JobPersistenceException("Calender cannot be removed if it referenced by a trigger!");
         } else {
            if (!this.isClustered) {
               this.calendarCache.remove(calName);
            }

            return this.getDelegate().deleteCalendar(conn, calName) > 0;
         }
      } catch (SQLException var4) {
         throw new JobPersistenceException("Couldn't remove calendar: " + var4.getMessage(), var4);
      }
   }

   public Calendar retrieveCalendar(final String calName) throws JobPersistenceException {
      return (Calendar)this.executeWithoutLock(new JobStoreSupport.TransactionCallback() {
         public Object execute(Connection conn) throws JobPersistenceException {
            return JobStoreSupport.this.retrieveCalendar(conn, calName);
         }
      });
   }

   protected Calendar retrieveCalendar(Connection conn, String calName) throws JobPersistenceException {
      Calendar cal = this.isClustered ? null : (Calendar)this.calendarCache.get(calName);
      if (cal != null) {
         return cal;
      } else {
         try {
            cal = this.getDelegate().selectCalendar(conn, calName);
            if (!this.isClustered) {
               this.calendarCache.put(calName, cal);
            }

            return cal;
         } catch (ClassNotFoundException var5) {
            throw new JobPersistenceException("Couldn't retrieve calendar because a required class was not found: " + var5.getMessage(), var5);
         } catch (IOException var6) {
            throw new JobPersistenceException("Couldn't retrieve calendar because the BLOB couldn't be deserialized: " + var6.getMessage(), var6);
         } catch (SQLException var7) {
            throw new JobPersistenceException("Couldn't retrieve calendar: " + var7.getMessage(), var7);
         }
      }
   }

   public int getNumberOfJobs() throws JobPersistenceException {
      return (Integer)this.executeWithoutLock(new JobStoreSupport.TransactionCallback() {
         public Object execute(Connection conn) throws JobPersistenceException {
            return JobStoreSupport.this.getNumberOfJobs(conn);
         }
      });
   }

   protected int getNumberOfJobs(Connection conn) throws JobPersistenceException {
      try {
         return this.getDelegate().selectNumJobs(conn);
      } catch (SQLException var3) {
         throw new JobPersistenceException("Couldn't obtain number of jobs: " + var3.getMessage(), var3);
      }
   }

   public int getNumberOfTriggers() throws JobPersistenceException {
      return (Integer)this.executeWithoutLock(new JobStoreSupport.TransactionCallback() {
         public Object execute(Connection conn) throws JobPersistenceException {
            return JobStoreSupport.this.getNumberOfTriggers(conn);
         }
      });
   }

   protected int getNumberOfTriggers(Connection conn) throws JobPersistenceException {
      try {
         return this.getDelegate().selectNumTriggers(conn);
      } catch (SQLException var3) {
         throw new JobPersistenceException("Couldn't obtain number of triggers: " + var3.getMessage(), var3);
      }
   }

   public int getNumberOfCalendars() throws JobPersistenceException {
      return (Integer)this.executeWithoutLock(new JobStoreSupport.TransactionCallback() {
         public Object execute(Connection conn) throws JobPersistenceException {
            return JobStoreSupport.this.getNumberOfCalendars(conn);
         }
      });
   }

   protected int getNumberOfCalendars(Connection conn) throws JobPersistenceException {
      try {
         return this.getDelegate().selectNumCalendars(conn);
      } catch (SQLException var3) {
         throw new JobPersistenceException("Couldn't obtain number of calendars: " + var3.getMessage(), var3);
      }
   }

   public Set<JobKey> getJobKeys(final GroupMatcher<JobKey> matcher) throws JobPersistenceException {
      return (Set)this.executeWithoutLock(new JobStoreSupport.TransactionCallback() {
         public Object execute(Connection conn) throws JobPersistenceException {
            return JobStoreSupport.this.getJobNames(conn, matcher);
         }
      });
   }

   protected Set<JobKey> getJobNames(Connection conn, GroupMatcher<JobKey> matcher) throws JobPersistenceException {
      try {
         Set<JobKey> jobNames = this.getDelegate().selectJobsInGroup(conn, matcher);
         return jobNames;
      } catch (SQLException var5) {
         throw new JobPersistenceException("Couldn't obtain job names: " + var5.getMessage(), var5);
      }
   }

   public boolean checkExists(final JobKey jobKey) throws JobPersistenceException {
      return (Boolean)this.executeWithoutLock(new JobStoreSupport.TransactionCallback() {
         public Object execute(Connection conn) throws JobPersistenceException {
            return JobStoreSupport.this.checkExists(conn, jobKey);
         }
      });
   }

   protected boolean checkExists(Connection conn, JobKey jobKey) throws JobPersistenceException {
      try {
         return this.getDelegate().jobExists(conn, jobKey);
      } catch (SQLException var4) {
         throw new JobPersistenceException("Couldn't check for existence of job: " + var4.getMessage(), var4);
      }
   }

   public boolean checkExists(final TriggerKey triggerKey) throws JobPersistenceException {
      return (Boolean)this.executeWithoutLock(new JobStoreSupport.TransactionCallback() {
         public Object execute(Connection conn) throws JobPersistenceException {
            return JobStoreSupport.this.checkExists(conn, triggerKey);
         }
      });
   }

   protected boolean checkExists(Connection conn, TriggerKey triggerKey) throws JobPersistenceException {
      try {
         return this.getDelegate().triggerExists(conn, triggerKey);
      } catch (SQLException var4) {
         throw new JobPersistenceException("Couldn't check for existence of job: " + var4.getMessage(), var4);
      }
   }

   public void clearAllSchedulingData() throws JobPersistenceException {
      this.executeInLock("TRIGGER_ACCESS", new JobStoreSupport.VoidTransactionCallback() {
         public void executeVoid(Connection conn) throws JobPersistenceException {
            JobStoreSupport.this.clearAllSchedulingData(conn);
         }
      });
   }

   protected void clearAllSchedulingData(Connection conn) throws JobPersistenceException {
      try {
         this.getDelegate().clearData(conn);
      } catch (SQLException var3) {
         throw new JobPersistenceException("Error clearing scheduling data: " + var3.getMessage(), var3);
      }
   }

   public Set<TriggerKey> getTriggerKeys(final GroupMatcher<TriggerKey> matcher) throws JobPersistenceException {
      return (Set)this.executeWithoutLock(new JobStoreSupport.TransactionCallback() {
         public Object execute(Connection conn) throws JobPersistenceException {
            return JobStoreSupport.this.getTriggerNames(conn, matcher);
         }
      });
   }

   protected Set<TriggerKey> getTriggerNames(Connection conn, GroupMatcher<TriggerKey> matcher) throws JobPersistenceException {
      try {
         Set<TriggerKey> trigNames = this.getDelegate().selectTriggersInGroup(conn, matcher);
         return trigNames;
      } catch (SQLException var5) {
         throw new JobPersistenceException("Couldn't obtain trigger names: " + var5.getMessage(), var5);
      }
   }

   public List<String> getJobGroupNames() throws JobPersistenceException {
      return (List)this.executeWithoutLock(new JobStoreSupport.TransactionCallback() {
         public Object execute(Connection conn) throws JobPersistenceException {
            return JobStoreSupport.this.getJobGroupNames(conn);
         }
      });
   }

   protected List<String> getJobGroupNames(Connection conn) throws JobPersistenceException {
      try {
         List<String> groupNames = this.getDelegate().selectJobGroups(conn);
         return groupNames;
      } catch (SQLException var4) {
         throw new JobPersistenceException("Couldn't obtain job groups: " + var4.getMessage(), var4);
      }
   }

   public List<String> getTriggerGroupNames() throws JobPersistenceException {
      return (List)this.executeWithoutLock(new JobStoreSupport.TransactionCallback() {
         public Object execute(Connection conn) throws JobPersistenceException {
            return JobStoreSupport.this.getTriggerGroupNames(conn);
         }
      });
   }

   protected List<String> getTriggerGroupNames(Connection conn) throws JobPersistenceException {
      try {
         List<String> groupNames = this.getDelegate().selectTriggerGroups(conn);
         return groupNames;
      } catch (SQLException var4) {
         throw new JobPersistenceException("Couldn't obtain trigger groups: " + var4.getMessage(), var4);
      }
   }

   public List<String> getCalendarNames() throws JobPersistenceException {
      return (List)this.executeWithoutLock(new JobStoreSupport.TransactionCallback() {
         public Object execute(Connection conn) throws JobPersistenceException {
            return JobStoreSupport.this.getCalendarNames(conn);
         }
      });
   }

   protected List<String> getCalendarNames(Connection conn) throws JobPersistenceException {
      try {
         return this.getDelegate().selectCalendars(conn);
      } catch (SQLException var3) {
         throw new JobPersistenceException("Couldn't obtain trigger groups: " + var3.getMessage(), var3);
      }
   }

   public List<OperableTrigger> getTriggersForJob(final JobKey jobKey) throws JobPersistenceException {
      return (List)this.executeWithoutLock(new JobStoreSupport.TransactionCallback() {
         public Object execute(Connection conn) throws JobPersistenceException {
            return JobStoreSupport.this.getTriggersForJob(conn, jobKey);
         }
      });
   }

   protected List<OperableTrigger> getTriggersForJob(Connection conn, JobKey key) throws JobPersistenceException {
      try {
         List<OperableTrigger> list = this.getDelegate().selectTriggersForJob(conn, key);
         return list;
      } catch (Exception var5) {
         throw new JobPersistenceException("Couldn't obtain triggers for job: " + var5.getMessage(), var5);
      }
   }

   public void pauseTrigger(final TriggerKey triggerKey) throws JobPersistenceException {
      this.executeInLock("TRIGGER_ACCESS", new JobStoreSupport.VoidTransactionCallback() {
         public void executeVoid(Connection conn) throws JobPersistenceException {
            JobStoreSupport.this.pauseTrigger(conn, triggerKey);
         }
      });
   }

   public void pauseTrigger(Connection conn, TriggerKey triggerKey) throws JobPersistenceException {
      try {
         String oldState = this.getDelegate().selectTriggerState(conn, triggerKey);
         if (!oldState.equals("WAITING") && !oldState.equals("ACQUIRED")) {
            if (oldState.equals("BLOCKED")) {
               this.getDelegate().updateTriggerState(conn, triggerKey, "PAUSED_BLOCKED");
            }
         } else {
            this.getDelegate().updateTriggerState(conn, triggerKey, "PAUSED");
         }

      } catch (SQLException var4) {
         throw new JobPersistenceException("Couldn't pause trigger '" + triggerKey + "': " + var4.getMessage(), var4);
      }
   }

   public void pauseJob(final JobKey jobKey) throws JobPersistenceException {
      this.executeInLock("TRIGGER_ACCESS", new JobStoreSupport.VoidTransactionCallback() {
         public void executeVoid(Connection conn) throws JobPersistenceException {
            List<OperableTrigger> triggers = JobStoreSupport.this.getTriggersForJob(conn, jobKey);
            Iterator i$ = triggers.iterator();

            while(i$.hasNext()) {
               OperableTrigger trigger = (OperableTrigger)i$.next();
               JobStoreSupport.this.pauseTrigger(conn, trigger.getKey());
            }

         }
      });
   }

   public Set<String> pauseJobs(final GroupMatcher<JobKey> matcher) throws JobPersistenceException {
      return (Set)this.executeInLock("TRIGGER_ACCESS", new JobStoreSupport.TransactionCallback() {
         public Set<String> execute(Connection conn) throws JobPersistenceException {
            Set<String> groupNames = new HashSet();
            Set<JobKey> jobNames = JobStoreSupport.this.getJobNames(conn, matcher);
            Iterator i$x = jobNames.iterator();

            while(i$x.hasNext()) {
               JobKey jobKey = (JobKey)i$x.next();
               List<OperableTrigger> triggers = JobStoreSupport.this.getTriggersForJob(conn, jobKey);
               Iterator i$ = triggers.iterator();

               while(i$.hasNext()) {
                  OperableTrigger trigger = (OperableTrigger)i$.next();
                  JobStoreSupport.this.pauseTrigger(conn, trigger.getKey());
               }

               groupNames.add(jobKey.getGroup());
            }

            return groupNames;
         }
      });
   }

   protected String checkBlockedState(Connection conn, JobKey jobKey, String currentState) throws JobPersistenceException {
      if (!currentState.equals("WAITING") && !currentState.equals("PAUSED")) {
         return currentState;
      } else {
         try {
            List<FiredTriggerRecord> lst = this.getDelegate().selectFiredTriggerRecordsByJob(conn, jobKey.getName(), jobKey.getGroup());
            if (lst.size() > 0) {
               FiredTriggerRecord rec = (FiredTriggerRecord)lst.get(0);
               if (rec.isJobDisallowsConcurrentExecution()) {
                  return "PAUSED".equals(currentState) ? "PAUSED_BLOCKED" : "BLOCKED";
               }
            }

            return currentState;
         } catch (SQLException var6) {
            throw new JobPersistenceException("Couldn't determine if trigger should be in a blocked state '" + jobKey + "': " + var6.getMessage(), var6);
         }
      }
   }

   public void resumeTrigger(final TriggerKey triggerKey) throws JobPersistenceException {
      this.executeInLock("TRIGGER_ACCESS", new JobStoreSupport.VoidTransactionCallback() {
         public void executeVoid(Connection conn) throws JobPersistenceException {
            JobStoreSupport.this.resumeTrigger(conn, triggerKey);
         }
      });
   }

   public void resumeTrigger(Connection conn, TriggerKey key) throws JobPersistenceException {
      try {
         TriggerStatus status = this.getDelegate().selectTriggerStatus(conn, key);
         if (status != null && status.getNextFireTime() != null) {
            boolean blocked = false;
            if ("PAUSED_BLOCKED".equals(status.getStatus())) {
               blocked = true;
            }

            String newState = this.checkBlockedState(conn, status.getJobKey(), "WAITING");
            boolean misfired = false;
            if (this.schedulerRunning && status.getNextFireTime().before(new Date())) {
               misfired = this.updateMisfiredTrigger(conn, key, newState, true);
            }

            if (!misfired) {
               if (blocked) {
                  this.getDelegate().updateTriggerStateFromOtherState(conn, key, newState, "PAUSED_BLOCKED");
               } else {
                  this.getDelegate().updateTriggerStateFromOtherState(conn, key, newState, "PAUSED");
               }
            }

         }
      } catch (SQLException var7) {
         throw new JobPersistenceException("Couldn't resume trigger '" + key + "': " + var7.getMessage(), var7);
      }
   }

   public void resumeJob(final JobKey jobKey) throws JobPersistenceException {
      this.executeInLock("TRIGGER_ACCESS", new JobStoreSupport.VoidTransactionCallback() {
         public void executeVoid(Connection conn) throws JobPersistenceException {
            List<OperableTrigger> triggers = JobStoreSupport.this.getTriggersForJob(conn, jobKey);
            Iterator i$ = triggers.iterator();

            while(i$.hasNext()) {
               OperableTrigger trigger = (OperableTrigger)i$.next();
               JobStoreSupport.this.resumeTrigger(conn, trigger.getKey());
            }

         }
      });
   }

   public Set<String> resumeJobs(final GroupMatcher<JobKey> matcher) throws JobPersistenceException {
      return (Set)this.executeInLock("TRIGGER_ACCESS", new JobStoreSupport.TransactionCallback() {
         public Set<String> execute(Connection conn) throws JobPersistenceException {
            Set<JobKey> jobKeys = JobStoreSupport.this.getJobNames(conn, matcher);
            Set<String> groupNames = new HashSet();
            Iterator i$x = jobKeys.iterator();

            while(i$x.hasNext()) {
               JobKey jobKey = (JobKey)i$x.next();
               List<OperableTrigger> triggers = JobStoreSupport.this.getTriggersForJob(conn, jobKey);
               Iterator i$ = triggers.iterator();

               while(i$.hasNext()) {
                  OperableTrigger trigger = (OperableTrigger)i$.next();
                  JobStoreSupport.this.resumeTrigger(conn, trigger.getKey());
               }

               groupNames.add(jobKey.getGroup());
            }

            return groupNames;
         }
      });
   }

   public Set<String> pauseTriggers(final GroupMatcher<TriggerKey> matcher) throws JobPersistenceException {
      return (Set)this.executeInLock("TRIGGER_ACCESS", new JobStoreSupport.TransactionCallback() {
         public Set<String> execute(Connection conn) throws JobPersistenceException {
            return JobStoreSupport.this.pauseTriggerGroup(conn, matcher);
         }
      });
   }

   public Set<String> pauseTriggerGroup(Connection conn, GroupMatcher<TriggerKey> matcher) throws JobPersistenceException {
      try {
         this.getDelegate().updateTriggerGroupStateFromOtherStates(conn, matcher, "PAUSED", "ACQUIRED", "WAITING", "WAITING");
         this.getDelegate().updateTriggerGroupStateFromOtherState(conn, matcher, "PAUSED_BLOCKED", "BLOCKED");
         List<String> groups = this.getDelegate().selectTriggerGroups(conn, matcher);
         StringMatcher.StringOperatorName operator = matcher.getCompareWithOperator();
         if (operator.equals(StringMatcher.StringOperatorName.EQUALS) && !groups.contains(matcher.getCompareToValue())) {
            groups.add(matcher.getCompareToValue());
         }

         Iterator i$ = groups.iterator();

         while(i$.hasNext()) {
            String group = (String)i$.next();
            if (!this.getDelegate().isTriggerGroupPaused(conn, group)) {
               this.getDelegate().insertPausedTriggerGroup(conn, group);
            }
         }

         return new HashSet(groups);
      } catch (SQLException var7) {
         throw new JobPersistenceException("Couldn't pause trigger group '" + matcher + "': " + var7.getMessage(), var7);
      }
   }

   public Set<String> getPausedTriggerGroups() throws JobPersistenceException {
      return (Set)this.executeWithoutLock(new JobStoreSupport.TransactionCallback() {
         public Object execute(Connection conn) throws JobPersistenceException {
            return JobStoreSupport.this.getPausedTriggerGroups(conn);
         }
      });
   }

   public Set<String> getPausedTriggerGroups(Connection conn) throws JobPersistenceException {
      try {
         return this.getDelegate().selectPausedTriggerGroups(conn);
      } catch (SQLException var3) {
         throw new JobPersistenceException("Couldn't determine paused trigger groups: " + var3.getMessage(), var3);
      }
   }

   public Set<String> resumeTriggers(final GroupMatcher<TriggerKey> matcher) throws JobPersistenceException {
      return (Set)this.executeInLock("TRIGGER_ACCESS", new JobStoreSupport.TransactionCallback() {
         public Set<String> execute(Connection conn) throws JobPersistenceException {
            return JobStoreSupport.this.resumeTriggerGroup(conn, matcher);
         }
      });
   }

   public Set<String> resumeTriggerGroup(Connection conn, GroupMatcher<TriggerKey> matcher) throws JobPersistenceException {
      try {
         this.getDelegate().deletePausedTriggerGroup(conn, matcher);
         HashSet<String> groups = new HashSet();
         Set<TriggerKey> keys = this.getDelegate().selectTriggersInGroup(conn, matcher);
         Iterator i$ = keys.iterator();

         while(i$.hasNext()) {
            TriggerKey key = (TriggerKey)i$.next();
            this.resumeTrigger(conn, key);
            groups.add(key.getGroup());
         }

         return groups;
      } catch (SQLException var7) {
         throw new JobPersistenceException("Couldn't pause trigger group '" + matcher + "': " + var7.getMessage(), var7);
      }
   }

   public void pauseAll() throws JobPersistenceException {
      this.executeInLock("TRIGGER_ACCESS", new JobStoreSupport.VoidTransactionCallback() {
         public void executeVoid(Connection conn) throws JobPersistenceException {
            JobStoreSupport.this.pauseAll(conn);
         }
      });
   }

   public void pauseAll(Connection conn) throws JobPersistenceException {
      List<String> names = this.getTriggerGroupNames(conn);
      Iterator i$ = names.iterator();

      while(i$.hasNext()) {
         String name = (String)i$.next();
         this.pauseTriggerGroup(conn, GroupMatcher.triggerGroupEquals(name));
      }

      try {
         if (!this.getDelegate().isTriggerGroupPaused(conn, "_$_ALL_GROUPS_PAUSED_$_")) {
            this.getDelegate().insertPausedTriggerGroup(conn, "_$_ALL_GROUPS_PAUSED_$_");
         }

      } catch (SQLException var5) {
         throw new JobPersistenceException("Couldn't pause all trigger groups: " + var5.getMessage(), var5);
      }
   }

   public void resumeAll() throws JobPersistenceException {
      this.executeInLock("TRIGGER_ACCESS", new JobStoreSupport.VoidTransactionCallback() {
         public void executeVoid(Connection conn) throws JobPersistenceException {
            JobStoreSupport.this.resumeAll(conn);
         }
      });
   }

   public void resumeAll(Connection conn) throws JobPersistenceException {
      List<String> names = this.getTriggerGroupNames(conn);
      Iterator i$ = names.iterator();

      while(i$.hasNext()) {
         String name = (String)i$.next();
         this.resumeTriggerGroup(conn, GroupMatcher.triggerGroupEquals(name));
      }

      try {
         this.getDelegate().deletePausedTriggerGroup(conn, "_$_ALL_GROUPS_PAUSED_$_");
      } catch (SQLException var5) {
         throw new JobPersistenceException("Couldn't resume all trigger groups: " + var5.getMessage(), var5);
      }
   }

   protected synchronized String getFiredTriggerRecordId() {
      return this.getInstanceId() + ftrCtr++;
   }

   public List<OperableTrigger> acquireNextTriggers(final long noLaterThan, final int maxCount, final long timeWindow) throws JobPersistenceException {
      String lockName;
      if (!this.isAcquireTriggersWithinLock() && maxCount <= 1) {
         lockName = null;
      } else {
         lockName = "TRIGGER_ACCESS";
      }

      return (List)this.executeInNonManagedTXLock(lockName, new JobStoreSupport.TransactionCallback<List<OperableTrigger>>() {
         public List<OperableTrigger> execute(Connection conn) throws JobPersistenceException {
            return JobStoreSupport.this.acquireNextTrigger(conn, noLaterThan, maxCount, timeWindow);
         }
      }, new JobStoreSupport.TransactionValidator<List<OperableTrigger>>() {
         public Boolean validate(Connection conn, List<OperableTrigger> result) throws JobPersistenceException {
            try {
               List<FiredTriggerRecord> acquired = JobStoreSupport.this.getDelegate().selectInstancesFiredTriggerRecords(conn, JobStoreSupport.this.getInstanceId());
               Set<String> fireInstanceIds = new HashSet();
               Iterator i$ = acquired.iterator();

               while(i$.hasNext()) {
                  FiredTriggerRecord ft = (FiredTriggerRecord)i$.next();
                  fireInstanceIds.add(ft.getFireInstanceId());
               }

               i$ = result.iterator();

               OperableTrigger tr;
               do {
                  if (!i$.hasNext()) {
                     return false;
                  }

                  tr = (OperableTrigger)i$.next();
               } while(!fireInstanceIds.contains(tr.getFireInstanceId()));

               return true;
            } catch (SQLException var7) {
               throw new JobPersistenceException("error validating trigger acquisition", var7);
            }
         }
      });
   }

   protected List<OperableTrigger> acquireNextTrigger(Connection conn, long noLaterThan, int maxCount, long timeWindow) throws JobPersistenceException {
      if (timeWindow < 0L) {
         throw new IllegalArgumentException();
      } else {
         List<OperableTrigger> acquiredTriggers = new ArrayList();
         Set<JobKey> acquiredJobKeysForNoConcurrentExec = new HashSet();
         int MAX_DO_LOOP_RETRY = true;
         int currentLoopCount = 0;
         long firstAcquiredTriggerFireTime = 0L;

         label62:
         while(true) {
            ++currentLoopCount;

            try {
               List<TriggerKey> keys = this.getDelegate().selectTriggerToAcquire(conn, noLaterThan + timeWindow, this.getMisfireTime(), maxCount);
               if (keys != null && keys.size() != 0) {
                  Iterator i$ = keys.iterator();

                  while(true) {
                     TriggerKey triggerKey;
                     OperableTrigger nextTrigger;
                     while(true) {
                        do {
                           if (!i$.hasNext()) {
                              if (acquiredTriggers.size() == 0 && currentLoopCount < 3) {
                                 continue label62;
                              }

                              return acquiredTriggers;
                           }

                           triggerKey = (TriggerKey)i$.next();
                           nextTrigger = this.retrieveTrigger(conn, triggerKey);
                        } while(nextTrigger == null);

                        JobKey jobKey = nextTrigger.getJobKey();
                        JobDetail job = this.getDelegate().selectJobDetail(conn, jobKey, this.getClassLoadHelper());
                        if (!job.isConcurrentExectionDisallowed()) {
                           break;
                        }

                        if (!acquiredJobKeysForNoConcurrentExec.contains(jobKey)) {
                           acquiredJobKeysForNoConcurrentExec.add(jobKey);
                           break;
                        }
                     }

                     int rowsUpdated = this.getDelegate().updateTriggerStateFromOtherState(conn, triggerKey, "ACQUIRED", "WAITING");
                     if (rowsUpdated > 0) {
                        nextTrigger.setFireInstanceId(this.getFiredTriggerRecordId());
                        this.getDelegate().insertFiredTrigger(conn, nextTrigger, "ACQUIRED", (JobDetail)null);
                        acquiredTriggers.add(nextTrigger);
                        if (firstAcquiredTriggerFireTime == 0L) {
                           firstAcquiredTriggerFireTime = nextTrigger.getNextFireTime().getTime();
                        }
                     }
                  }
               }

               return acquiredTriggers;
            } catch (Exception var20) {
               throw new JobPersistenceException("Couldn't acquire next trigger: " + var20.getMessage(), var20);
            }
         }
      }
   }

   public void releaseAcquiredTrigger(final OperableTrigger trigger) {
      this.retryExecuteInNonManagedTXLock("TRIGGER_ACCESS", new JobStoreSupport.VoidTransactionCallback() {
         public void executeVoid(Connection conn) throws JobPersistenceException {
            JobStoreSupport.this.releaseAcquiredTrigger(conn, trigger);
         }
      });
   }

   protected void releaseAcquiredTrigger(Connection conn, OperableTrigger trigger) throws JobPersistenceException {
      try {
         this.getDelegate().updateTriggerStateFromOtherState(conn, trigger.getKey(), "WAITING", "ACQUIRED");
         this.getDelegate().deleteFiredTrigger(conn, trigger.getFireInstanceId());
      } catch (SQLException var4) {
         throw new JobPersistenceException("Couldn't release acquired trigger: " + var4.getMessage(), var4);
      }
   }

   public List<TriggerFiredResult> triggersFired(final List<OperableTrigger> triggers) throws JobPersistenceException {
      return (List)this.executeInNonManagedTXLock("TRIGGER_ACCESS", new JobStoreSupport.TransactionCallback<List<TriggerFiredResult>>() {
         public List<TriggerFiredResult> execute(Connection conn) throws JobPersistenceException {
            List<TriggerFiredResult> results = new ArrayList();

            TriggerFiredResult result;
            for(Iterator i$ = triggers.iterator(); i$.hasNext(); results.add(result)) {
               OperableTrigger trigger = (OperableTrigger)i$.next();

               try {
                  TriggerFiredBundle bundle = JobStoreSupport.this.triggerFired(conn, trigger);
                  result = new TriggerFiredResult(bundle);
               } catch (JobPersistenceException var7) {
                  result = new TriggerFiredResult(var7);
               } catch (RuntimeException var8) {
                  result = new TriggerFiredResult(var8);
               }
            }

            return results;
         }
      }, new JobStoreSupport.TransactionValidator<List<TriggerFiredResult>>() {
         public Boolean validate(Connection conn, List<TriggerFiredResult> result) throws JobPersistenceException {
            try {
               List<FiredTriggerRecord> acquired = JobStoreSupport.this.getDelegate().selectInstancesFiredTriggerRecords(conn, JobStoreSupport.this.getInstanceId());
               Set<String> executingTriggers = new HashSet();
               Iterator i$ = acquired.iterator();

               while(i$.hasNext()) {
                  FiredTriggerRecord ft = (FiredTriggerRecord)i$.next();
                  if ("EXECUTING".equals(ft.getFireInstanceState())) {
                     executingTriggers.add(ft.getFireInstanceId());
                  }
               }

               i$ = result.iterator();

               TriggerFiredResult tr;
               do {
                  if (!i$.hasNext()) {
                     return false;
                  }

                  tr = (TriggerFiredResult)i$.next();
               } while(tr.getTriggerFiredBundle() == null || !executingTriggers.contains(tr.getTriggerFiredBundle().getTrigger().getFireInstanceId()));

               return true;
            } catch (SQLException var7) {
               throw new JobPersistenceException("error validating trigger acquisition", var7);
            }
         }
      });
   }

   protected TriggerFiredBundle triggerFired(Connection conn, OperableTrigger trigger) throws JobPersistenceException {
      Calendar cal = null;

      try {
         String state = this.getDelegate().selectTriggerState(conn, trigger.getKey());
         if (!state.equals("ACQUIRED")) {
            return null;
         }
      } catch (SQLException var12) {
         throw new JobPersistenceException("Couldn't select trigger state: " + var12.getMessage(), var12);
      }

      JobDetail job;
      try {
         job = this.retrieveJob(conn, trigger.getJobKey());
         if (job == null) {
            return null;
         }
      } catch (JobPersistenceException var13) {
         JobPersistenceException jpe = var13;

         try {
            this.getLog().error("Error retrieving job, setting trigger state to ERROR.", jpe);
            this.getDelegate().updateTriggerState(conn, trigger.getKey(), "ERROR");
         } catch (SQLException var9) {
            this.getLog().error("Unable to set trigger state to ERROR.", var9);
         }

         throw var13;
      }

      if (trigger.getCalendarName() != null) {
         cal = this.retrieveCalendar(conn, trigger.getCalendarName());
         if (cal == null) {
            return null;
         }
      }

      try {
         this.getDelegate().updateFiredTrigger(conn, trigger, "EXECUTING", job);
      } catch (SQLException var11) {
         throw new JobPersistenceException("Couldn't insert fired trigger: " + var11.getMessage(), var11);
      }

      Date prevFireTime = trigger.getPreviousFireTime();
      trigger.triggered(cal);
      String state = "WAITING";
      boolean force = true;
      if (job.isConcurrentExectionDisallowed()) {
         state = "BLOCKED";
         force = false;

         try {
            this.getDelegate().updateTriggerStatesForJobFromOtherState(conn, job.getKey(), "BLOCKED", "WAITING");
            this.getDelegate().updateTriggerStatesForJobFromOtherState(conn, job.getKey(), "BLOCKED", "ACQUIRED");
            this.getDelegate().updateTriggerStatesForJobFromOtherState(conn, job.getKey(), "PAUSED_BLOCKED", "PAUSED");
         } catch (SQLException var10) {
            throw new JobPersistenceException("Couldn't update states of blocked triggers: " + var10.getMessage(), var10);
         }
      }

      if (trigger.getNextFireTime() == null) {
         state = "COMPLETE";
         force = true;
      }

      this.storeTrigger(conn, trigger, job, true, state, force, false);
      job.getJobDataMap().clearDirtyFlag();
      return new TriggerFiredBundle(job, trigger, cal, trigger.getKey().getGroup().equals("RECOVERING_JOBS"), new Date(), trigger.getPreviousFireTime(), prevFireTime, trigger.getNextFireTime());
   }

   public void triggeredJobComplete(final OperableTrigger trigger, final JobDetail jobDetail, final Trigger.CompletedExecutionInstruction triggerInstCode) {
      this.retryExecuteInNonManagedTXLock("TRIGGER_ACCESS", new JobStoreSupport.VoidTransactionCallback() {
         public void executeVoid(Connection conn) throws JobPersistenceException {
            JobStoreSupport.this.triggeredJobComplete(conn, trigger, jobDetail, triggerInstCode);
         }
      });
   }

   protected void triggeredJobComplete(Connection conn, OperableTrigger trigger, JobDetail jobDetail, Trigger.CompletedExecutionInstruction triggerInstCode) throws JobPersistenceException {
      try {
         if (triggerInstCode == Trigger.CompletedExecutionInstruction.DELETE_TRIGGER) {
            if (trigger.getNextFireTime() == null) {
               TriggerStatus stat = this.getDelegate().selectTriggerStatus(conn, trigger.getKey());
               if (stat != null && stat.getNextFireTime() == null) {
                  this.removeTrigger(conn, trigger.getKey());
               }
            } else {
               this.removeTrigger(conn, trigger.getKey());
               this.signalSchedulingChangeOnTxCompletion(0L);
            }
         } else if (triggerInstCode == Trigger.CompletedExecutionInstruction.SET_TRIGGER_COMPLETE) {
            this.getDelegate().updateTriggerState(conn, trigger.getKey(), "COMPLETE");
            this.signalSchedulingChangeOnTxCompletion(0L);
         } else if (triggerInstCode == Trigger.CompletedExecutionInstruction.SET_TRIGGER_ERROR) {
            this.getLog().info("Trigger " + trigger.getKey() + " set to ERROR state.");
            this.getDelegate().updateTriggerState(conn, trigger.getKey(), "ERROR");
            this.signalSchedulingChangeOnTxCompletion(0L);
         } else if (triggerInstCode == Trigger.CompletedExecutionInstruction.SET_ALL_JOB_TRIGGERS_COMPLETE) {
            this.getDelegate().updateTriggerStatesForJob(conn, trigger.getJobKey(), "COMPLETE");
            this.signalSchedulingChangeOnTxCompletion(0L);
         } else if (triggerInstCode == Trigger.CompletedExecutionInstruction.SET_ALL_JOB_TRIGGERS_ERROR) {
            this.getLog().info("All triggers of Job " + trigger.getKey() + " set to ERROR state.");
            this.getDelegate().updateTriggerStatesForJob(conn, trigger.getJobKey(), "ERROR");
            this.signalSchedulingChangeOnTxCompletion(0L);
         }

         if (jobDetail.isConcurrentExectionDisallowed()) {
            this.getDelegate().updateTriggerStatesForJobFromOtherState(conn, jobDetail.getKey(), "WAITING", "BLOCKED");
            this.getDelegate().updateTriggerStatesForJobFromOtherState(conn, jobDetail.getKey(), "PAUSED", "PAUSED_BLOCKED");
            this.signalSchedulingChangeOnTxCompletion(0L);
         }

         if (jobDetail.isPersistJobDataAfterExecution()) {
            try {
               if (jobDetail.getJobDataMap().isDirty()) {
                  this.getDelegate().updateJobData(conn, jobDetail);
               }
            } catch (IOException var7) {
               throw new JobPersistenceException("Couldn't serialize job data: " + var7.getMessage(), var7);
            } catch (SQLException var8) {
               throw new JobPersistenceException("Couldn't update job data: " + var8.getMessage(), var8);
            }
         }
      } catch (SQLException var9) {
         throw new JobPersistenceException("Couldn't update trigger state(s): " + var9.getMessage(), var9);
      }

      try {
         this.getDelegate().deleteFiredTrigger(conn, trigger.getFireInstanceId());
      } catch (SQLException var6) {
         throw new JobPersistenceException("Couldn't delete fired trigger: " + var6.getMessage(), var6);
      }
   }

   protected DriverDelegate getDelegate() throws NoSuchDelegateException {
      synchronized(this) {
         if (null == this.delegate) {
            try {
               if (this.delegateClassName != null) {
                  this.delegateClass = this.getClassLoadHelper().loadClass(this.delegateClassName, DriverDelegate.class);
               }

               this.delegate = (DriverDelegate)this.delegateClass.newInstance();
               this.delegate.initialize(this.getLog(), this.tablePrefix, this.instanceName, this.instanceId, this.getClassLoadHelper(), this.canUseProperties(), this.getDriverDelegateInitString());
            } catch (InstantiationException var4) {
               throw new NoSuchDelegateException("Couldn't create delegate: " + var4.getMessage(), var4);
            } catch (IllegalAccessException var5) {
               throw new NoSuchDelegateException("Couldn't create delegate: " + var5.getMessage(), var5);
            } catch (ClassNotFoundException var6) {
               throw new NoSuchDelegateException("Couldn't load delegate class: " + var6.getMessage(), var6);
            }
         }

         return this.delegate;
      }
   }

   protected Semaphore getLockHandler() {
      return this.lockHandler;
   }

   public void setLockHandler(Semaphore lockHandler) {
      this.lockHandler = lockHandler;
   }

   protected JobStoreSupport.RecoverMisfiredJobsResult doRecoverMisfires() throws JobPersistenceException {
      boolean transOwner = false;
      Connection conn = this.getNonManagedTXConnection();

      JobStoreSupport.RecoverMisfiredJobsResult var5;
      try {
         JobStoreSupport.RecoverMisfiredJobsResult result = JobStoreSupport.RecoverMisfiredJobsResult.NO_OP;
         int misfireCount = this.getDoubleCheckLockMisfireHandler() ? this.getDelegate().countMisfiredTriggersInState(conn, "WAITING", this.getMisfireTime()) : Integer.MAX_VALUE;
         if (misfireCount == 0) {
            this.getLog().debug("Found 0 triggers that missed their scheduled fire-time.");
         } else {
            transOwner = this.getLockHandler().obtainLock(conn, "TRIGGER_ACCESS");
            result = this.recoverMisfiredJobs(conn, false);
         }

         this.commitConnection(conn);
         var5 = result;
      } catch (JobPersistenceException var28) {
         this.rollbackConnection(conn);
         throw var28;
      } catch (SQLException var29) {
         this.rollbackConnection(conn);
         throw new JobPersistenceException("Database error recovering from misfires.", var29);
      } catch (RuntimeException var30) {
         this.rollbackConnection(conn);
         throw new JobPersistenceException("Unexpected runtime exception: " + var30.getMessage(), var30);
      } finally {
         try {
            this.releaseLock("TRIGGER_ACCESS", transOwner);
         } finally {
            this.cleanupConnection(conn);
         }
      }

      return var5;
   }

   protected void signalSchedulingChangeOnTxCompletion(long candidateNewNextFireTime) {
      Long sigTime = (Long)this.sigChangeForTxCompletion.get();
      if (sigTime == null && candidateNewNextFireTime >= 0L) {
         this.sigChangeForTxCompletion.set(candidateNewNextFireTime);
      } else if (sigTime == null || candidateNewNextFireTime < sigTime) {
         this.sigChangeForTxCompletion.set(candidateNewNextFireTime);
      }

   }

   protected Long clearAndGetSignalSchedulingChangeOnTxCompletion() {
      Long t = (Long)this.sigChangeForTxCompletion.get();
      this.sigChangeForTxCompletion.set((Object)null);
      return t;
   }

   protected void signalSchedulingChangeImmediately(long candidateNewNextFireTime) {
      this.schedSignaler.signalSchedulingChange(candidateNewNextFireTime);
   }

   protected boolean doCheckin() throws JobPersistenceException {
      boolean transOwner = false;
      boolean transStateOwner = false;
      boolean recovered = false;
      Connection conn = this.getNonManagedTXConnection();

      try {
         List<SchedulerStateRecord> failedRecords = null;
         if (!this.firstCheckIn) {
            failedRecords = this.clusterCheckIn(conn);
            this.commitConnection(conn);
         }

         if (this.firstCheckIn || failedRecords.size() > 0) {
            this.getLockHandler().obtainLock(conn, "STATE_ACCESS");
            transStateOwner = true;
            failedRecords = this.firstCheckIn ? this.clusterCheckIn(conn) : this.findFailedInstances(conn);
            if (failedRecords.size() > 0) {
               this.getLockHandler().obtainLock(conn, "TRIGGER_ACCESS");
               transOwner = true;
               this.clusterRecover(conn, failedRecords);
               recovered = true;
            }
         }

         this.commitConnection(conn);
      } catch (JobPersistenceException var66) {
         this.rollbackConnection(conn);
         throw var66;
      } finally {
         try {
            this.releaseLock("TRIGGER_ACCESS", transOwner);
         } finally {
            try {
               this.releaseLock("STATE_ACCESS", transStateOwner);
            } finally {
               this.cleanupConnection(conn);
            }
         }
      }

      this.firstCheckIn = false;
      return recovered;
   }

   protected List<SchedulerStateRecord> findFailedInstances(Connection conn) throws JobPersistenceException {
      try {
         List<SchedulerStateRecord> failedInstances = new LinkedList();
         boolean foundThisScheduler = false;
         long timeNow = System.currentTimeMillis();
         List<SchedulerStateRecord> states = this.getDelegate().selectSchedulerStateRecords(conn, (String)null);
         Iterator i$ = states.iterator();

         while(i$.hasNext()) {
            SchedulerStateRecord rec = (SchedulerStateRecord)i$.next();
            if (rec.getSchedulerInstanceId().equals(this.getInstanceId())) {
               foundThisScheduler = true;
               if (this.firstCheckIn) {
                  failedInstances.add(rec);
               }
            } else if (this.calcFailedIfAfter(rec) < timeNow) {
               failedInstances.add(rec);
            }
         }

         if (this.firstCheckIn) {
            failedInstances.addAll(this.findOrphanedFailedInstances(conn, states));
         }

         if (!foundThisScheduler && !this.firstCheckIn) {
            this.getLog().warn("This scheduler instance (" + this.getInstanceId() + ") is still " + "active but was recovered by another instance in the cluster.  " + "This may cause inconsistent behavior.");
         }

         return failedInstances;
      } catch (Exception var9) {
         this.lastCheckin = System.currentTimeMillis();
         throw new JobPersistenceException("Failure identifying failed instances when checking-in: " + var9.getMessage(), var9);
      }
   }

   private List<SchedulerStateRecord> findOrphanedFailedInstances(Connection conn, List<SchedulerStateRecord> schedulerStateRecords) throws SQLException, NoSuchDelegateException {
      List<SchedulerStateRecord> orphanedInstances = new ArrayList();
      Set<String> allFiredTriggerInstanceNames = this.getDelegate().selectFiredTriggerInstanceNames(conn);
      if (!allFiredTriggerInstanceNames.isEmpty()) {
         Iterator i$ = schedulerStateRecords.iterator();

         while(i$.hasNext()) {
            SchedulerStateRecord rec = (SchedulerStateRecord)i$.next();
            allFiredTriggerInstanceNames.remove(rec.getSchedulerInstanceId());
         }

         i$ = allFiredTriggerInstanceNames.iterator();

         while(i$.hasNext()) {
            String inst = (String)i$.next();
            SchedulerStateRecord orphanedInstance = new SchedulerStateRecord();
            orphanedInstance.setSchedulerInstanceId(inst);
            orphanedInstances.add(orphanedInstance);
            this.getLog().warn("Found orphaned fired triggers for instance: " + orphanedInstance.getSchedulerInstanceId());
         }
      }

      return orphanedInstances;
   }

   protected long calcFailedIfAfter(SchedulerStateRecord rec) {
      return rec.getCheckinTimestamp() + Math.max(rec.getCheckinInterval(), System.currentTimeMillis() - this.lastCheckin) + 7500L;
   }

   protected List<SchedulerStateRecord> clusterCheckIn(Connection conn) throws JobPersistenceException {
      List failedInstances = this.findFailedInstances(conn);

      try {
         this.lastCheckin = System.currentTimeMillis();
         if (this.getDelegate().updateSchedulerState(conn, this.getInstanceId(), this.lastCheckin) == 0) {
            this.getDelegate().insertSchedulerState(conn, this.getInstanceId(), this.lastCheckin, this.getClusterCheckinInterval());
         }

         return failedInstances;
      } catch (Exception var4) {
         throw new JobPersistenceException("Failure updating scheduler state when checking-in: " + var4.getMessage(), var4);
      }
   }

   protected void clusterRecover(Connection conn, List<SchedulerStateRecord> failedInstances) throws JobPersistenceException {
      if (failedInstances.size() > 0) {
         long recoverIds = System.currentTimeMillis();
         this.logWarnIfNonZero(failedInstances.size(), "ClusterManager: detected " + failedInstances.size() + " failed or restarted instances.");

         try {
            Iterator i$ = failedInstances.iterator();

            while(i$.hasNext()) {
               SchedulerStateRecord rec = (SchedulerStateRecord)i$.next();
               this.getLog().info("ClusterManager: Scanning for instance \"" + rec.getSchedulerInstanceId() + "\"'s failed in-progress jobs.");
               List<FiredTriggerRecord> firedTriggerRecs = this.getDelegate().selectInstancesFiredTriggerRecords(conn, rec.getSchedulerInstanceId());
               int acquiredCount = 0;
               int recoveredCount = 0;
               int otherCount = 0;
               Set<TriggerKey> triggerKeys = new HashSet();
               Iterator i$ = firedTriggerRecs.iterator();

               TriggerKey tKey;
               while(i$.hasNext()) {
                  FiredTriggerRecord ftRec = (FiredTriggerRecord)i$.next();
                  tKey = ftRec.getTriggerKey();
                  JobKey jKey = ftRec.getJobKey();
                  triggerKeys.add(tKey);
                  if (ftRec.getFireInstanceState().equals("BLOCKED")) {
                     this.getDelegate().updateTriggerStatesForJobFromOtherState(conn, jKey, "WAITING", "BLOCKED");
                  } else if (ftRec.getFireInstanceState().equals("PAUSED_BLOCKED")) {
                     this.getDelegate().updateTriggerStatesForJobFromOtherState(conn, jKey, "PAUSED", "PAUSED_BLOCKED");
                  }

                  if (ftRec.getFireInstanceState().equals("ACQUIRED")) {
                     this.getDelegate().updateTriggerStateFromOtherState(conn, tKey, "WAITING", "ACQUIRED");
                     ++acquiredCount;
                  } else if (ftRec.isJobRequestsRecovery()) {
                     if (this.jobExists(conn, jKey)) {
                        SimpleTriggerImpl rcvryTrig = new SimpleTriggerImpl("recover_" + rec.getSchedulerInstanceId() + "_" + recoverIds++, "RECOVERING_JOBS", new Date(ftRec.getScheduleTimestamp()));
                        rcvryTrig.setJobName(jKey.getName());
                        rcvryTrig.setJobGroup(jKey.getGroup());
                        rcvryTrig.setMisfireInstruction(-1);
                        rcvryTrig.setPriority(ftRec.getPriority());
                        JobDataMap jd = this.getDelegate().selectTriggerJobDataMap(conn, tKey.getName(), tKey.getGroup());
                        jd.put("QRTZ_FAILED_JOB_ORIG_TRIGGER_NAME", tKey.getName());
                        jd.put("QRTZ_FAILED_JOB_ORIG_TRIGGER_GROUP", tKey.getGroup());
                        jd.put("QRTZ_FAILED_JOB_ORIG_TRIGGER_FIRETIME_IN_MILLISECONDS_AS_STRING", String.valueOf(ftRec.getFireTimestamp()));
                        jd.put("QRTZ_FAILED_JOB_ORIG_TRIGGER_SCHEDULED_FIRETIME_IN_MILLISECONDS_AS_STRING", String.valueOf(ftRec.getScheduleTimestamp()));
                        rcvryTrig.setJobDataMap(jd);
                        rcvryTrig.computeFirstFireTime((Calendar)null);
                        this.storeTrigger(conn, rcvryTrig, (JobDetail)null, false, "WAITING", false, true);
                        ++recoveredCount;
                     } else {
                        this.getLog().warn("ClusterManager: failed job '" + jKey + "' no longer exists, cannot schedule recovery.");
                        ++otherCount;
                     }
                  } else {
                     ++otherCount;
                  }

                  if (ftRec.isJobDisallowsConcurrentExecution()) {
                     this.getDelegate().updateTriggerStatesForJobFromOtherState(conn, jKey, "WAITING", "BLOCKED");
                     this.getDelegate().updateTriggerStatesForJobFromOtherState(conn, jKey, "PAUSED", "PAUSED_BLOCKED");
                  }
               }

               this.getDelegate().deleteFiredTriggers(conn, rec.getSchedulerInstanceId());
               int completeCount = 0;
               Iterator i$ = triggerKeys.iterator();

               while(i$.hasNext()) {
                  tKey = (TriggerKey)i$.next();
                  if (this.getDelegate().selectTriggerState(conn, tKey).equals("COMPLETE")) {
                     List<FiredTriggerRecord> firedTriggers = this.getDelegate().selectFiredTriggerRecords(conn, tKey.getName(), tKey.getGroup());
                     if (firedTriggers.isEmpty() && this.removeTrigger(conn, tKey)) {
                        ++completeCount;
                     }
                  }
               }

               this.logWarnIfNonZero(acquiredCount, "ClusterManager: ......Freed " + acquiredCount + " acquired trigger(s).");
               this.logWarnIfNonZero(completeCount, "ClusterManager: ......Deleted " + completeCount + " complete triggers(s).");
               this.logWarnIfNonZero(recoveredCount, "ClusterManager: ......Scheduled " + recoveredCount + " recoverable job(s) for recovery.");
               this.logWarnIfNonZero(otherCount, "ClusterManager: ......Cleaned-up " + otherCount + " other failed job(s).");
               if (!rec.getSchedulerInstanceId().equals(this.getInstanceId())) {
                  this.getDelegate().deleteSchedulerState(conn, rec.getSchedulerInstanceId());
               }
            }
         } catch (Throwable var18) {
            throw new JobPersistenceException("Failure recovering jobs: " + var18.getMessage(), var18);
         }
      }

   }

   protected void logWarnIfNonZero(int val, String warning) {
      if (val > 0) {
         this.getLog().info(warning);
      } else {
         this.getLog().debug(warning);
      }

   }

   protected void cleanupConnection(Connection conn) {
      if (conn != null) {
         if (conn instanceof Proxy) {
            Proxy connProxy = (Proxy)conn;
            InvocationHandler invocationHandler = Proxy.getInvocationHandler(connProxy);
            if (invocationHandler instanceof AttributeRestoringConnectionInvocationHandler) {
               AttributeRestoringConnectionInvocationHandler connHandler = (AttributeRestoringConnectionInvocationHandler)invocationHandler;
               connHandler.restoreOriginalAtributes();
               this.closeConnection(connHandler.getWrappedConnection());
               return;
            }
         }

         this.closeConnection(conn);
      }

   }

   protected void closeConnection(Connection conn) {
      if (conn != null) {
         try {
            conn.close();
         } catch (SQLException var3) {
            this.getLog().error("Failed to close Connection", var3);
         } catch (Throwable var4) {
            this.getLog().error("Unexpected exception closing Connection.  This is often due to a Connection being returned after or during shutdown.", var4);
         }
      }

   }

   protected void rollbackConnection(Connection conn) {
      if (conn != null) {
         try {
            conn.rollback();
         } catch (SQLException var3) {
            this.getLog().error("Couldn't rollback jdbc connection. " + var3.getMessage(), var3);
         }
      }

   }

   protected void commitConnection(Connection conn) throws JobPersistenceException {
      if (conn != null) {
         try {
            conn.commit();
         } catch (SQLException var3) {
            throw new JobPersistenceException("Couldn't commit jdbc connection. " + var3.getMessage(), var3);
         }
      }

   }

   public <T> T executeWithoutLock(JobStoreSupport.TransactionCallback<T> txCallback) throws JobPersistenceException {
      return this.executeInLock((String)null, txCallback);
   }

   protected abstract <T> T executeInLock(String var1, JobStoreSupport.TransactionCallback<T> var2) throws JobPersistenceException;

   protected <T> T retryExecuteInNonManagedTXLock(String lockName, JobStoreSupport.TransactionCallback<T> txCallback) {
      for(int retry = 1; !this.shutdown; ++retry) {
         try {
            return this.executeInNonManagedTXLock(lockName, txCallback, (JobStoreSupport.TransactionValidator)null);
         } catch (JobPersistenceException var6) {
            if (retry % 4 == 0) {
               this.schedSignaler.notifySchedulerListenersError("An error occurred while " + txCallback, var6);
            }
         } catch (RuntimeException var7) {
            this.getLog().error("retryExecuteInNonManagedTXLock: RuntimeException " + var7.getMessage(), var7);
         }

         try {
            Thread.sleep(this.getDbRetryInterval());
         } catch (InterruptedException var5) {
            throw new IllegalStateException("Received interrupted exception", var5);
         }
      }

      throw new IllegalStateException("JobStore is shutdown - aborting retry");
   }

   protected <T> T executeInNonManagedTXLock(String lockName, JobStoreSupport.TransactionCallback<T> txCallback, final JobStoreSupport.TransactionValidator<T> txValidator) throws JobPersistenceException {
      boolean transOwner = false;
      Connection conn = null;

      Object var8;
      try {
         if (lockName != null) {
            if (this.getLockHandler().requiresConnection()) {
               conn = this.getNonManagedTXConnection();
            }

            transOwner = this.getLockHandler().obtainLock(conn, lockName);
         }

         if (conn == null) {
            conn = this.getNonManagedTXConnection();
         }

         final Object result = txCallback.execute(conn);

         try {
            this.commitConnection(conn);
         } catch (JobPersistenceException var31) {
            this.rollbackConnection(conn);
            if (txValidator == null || !(Boolean)this.retryExecuteInNonManagedTXLock(lockName, new JobStoreSupport.TransactionCallback<Boolean>() {
               public Boolean execute(Connection conn) throws JobPersistenceException {
                  return txValidator.validate(conn, result);
               }
            })) {
               throw var31;
            }
         }

         Long sigTime = this.clearAndGetSignalSchedulingChangeOnTxCompletion();
         if (sigTime != null && sigTime >= 0L) {
            this.signalSchedulingChangeImmediately(sigTime);
         }

         var8 = result;
      } catch (JobPersistenceException var32) {
         this.rollbackConnection(conn);
         throw var32;
      } catch (RuntimeException var33) {
         this.rollbackConnection(conn);
         throw new JobPersistenceException("Unexpected runtime exception: " + var33.getMessage(), var33);
      } finally {
         try {
            this.releaseLock(lockName, transOwner);
         } finally {
            this.cleanupConnection(conn);
         }
      }

      return var8;
   }

   class MisfireHandler extends Thread {
      private volatile boolean shutdown = false;
      private int numFails = 0;

      MisfireHandler() {
         this.setName("QuartzScheduler_" + JobStoreSupport.this.instanceName + "-" + JobStoreSupport.this.instanceId + "_MisfireHandler");
         this.setDaemon(JobStoreSupport.this.getMakeThreadsDaemons());
      }

      public void initialize() {
         ThreadExecutor executor = JobStoreSupport.this.getThreadExecutor();
         executor.execute(this);
      }

      public void shutdown() {
         this.shutdown = true;
         this.interrupt();
      }

      private JobStoreSupport.RecoverMisfiredJobsResult manage() {
         try {
            JobStoreSupport.this.getLog().debug("MisfireHandler: scanning for misfires...");
            JobStoreSupport.RecoverMisfiredJobsResult res = JobStoreSupport.this.doRecoverMisfires();
            this.numFails = 0;
            return res;
         } catch (Exception var2) {
            if (this.numFails % 4 == 0) {
               JobStoreSupport.this.getLog().error("MisfireHandler: Error handling misfires: " + var2.getMessage(), var2);
            }

            ++this.numFails;
            return JobStoreSupport.RecoverMisfiredJobsResult.NO_OP;
         }
      }

      public void run() {
         while(!this.shutdown) {
            long sTime = System.currentTimeMillis();
            JobStoreSupport.RecoverMisfiredJobsResult recoverMisfiredJobsResult = this.manage();
            if (recoverMisfiredJobsResult.getProcessedMisfiredTriggerCount() > 0) {
               JobStoreSupport.this.signalSchedulingChangeImmediately(recoverMisfiredJobsResult.getEarliestNewTime());
            }

            if (!this.shutdown) {
               long timeToSleep = 50L;
               if (!recoverMisfiredJobsResult.hasMoreMisfiredTriggers()) {
                  timeToSleep = JobStoreSupport.this.getMisfireThreshold() - (System.currentTimeMillis() - sTime);
                  if (timeToSleep <= 0L) {
                     timeToSleep = 50L;
                  }

                  if (this.numFails > 0) {
                     timeToSleep = Math.max(JobStoreSupport.this.getDbRetryInterval(), timeToSleep);
                  }
               }

               try {
                  Thread.sleep(timeToSleep);
               } catch (Exception var7) {
               }
            }
         }

      }
   }

   class ClusterManager extends Thread {
      private volatile boolean shutdown = false;
      private int numFails = 0;

      ClusterManager() {
         this.setPriority(7);
         this.setName("QuartzScheduler_" + JobStoreSupport.this.instanceName + "-" + JobStoreSupport.this.instanceId + "_ClusterManager");
         this.setDaemon(JobStoreSupport.this.getMakeThreadsDaemons());
      }

      public void initialize() {
         this.manage();
         ThreadExecutor executor = JobStoreSupport.this.getThreadExecutor();
         executor.execute(this);
      }

      public void shutdown() {
         this.shutdown = true;
         this.interrupt();
      }

      private boolean manage() {
         boolean res = false;

         try {
            res = JobStoreSupport.this.doCheckin();
            this.numFails = 0;
            JobStoreSupport.this.getLog().debug("ClusterManager: Check-in complete.");
         } catch (Exception var3) {
            if (this.numFails % 4 == 0) {
               JobStoreSupport.this.getLog().error("ClusterManager: Error managing cluster: " + var3.getMessage(), var3);
            }

            ++this.numFails;
         }

         return res;
      }

      public void run() {
         while(!this.shutdown) {
            if (!this.shutdown) {
               long timeToSleep = JobStoreSupport.this.getClusterCheckinInterval();
               long transpiredTime = System.currentTimeMillis() - JobStoreSupport.this.lastCheckin;
               timeToSleep -= transpiredTime;
               if (timeToSleep <= 0L) {
                  timeToSleep = 100L;
               }

               if (this.numFails > 0) {
                  timeToSleep = Math.max(JobStoreSupport.this.getDbRetryInterval(), timeToSleep);
               }

               try {
                  Thread.sleep(timeToSleep);
               } catch (Exception var6) {
               }
            }

            if (!this.shutdown && this.manage()) {
               JobStoreSupport.this.signalSchedulingChangeImmediately(0L);
            }
         }

      }
   }

   protected abstract class VoidTransactionCallback implements JobStoreSupport.TransactionCallback<Void> {
      public final Void execute(Connection conn) throws JobPersistenceException {
         this.executeVoid(conn);
         return null;
      }

      abstract void executeVoid(Connection var1) throws JobPersistenceException;
   }

   protected interface TransactionValidator<T> {
      Boolean validate(Connection var1, T var2) throws JobPersistenceException;
   }

   protected interface TransactionCallback<T> {
      T execute(Connection var1) throws JobPersistenceException;
   }

   protected static class RecoverMisfiredJobsResult {
      public static final JobStoreSupport.RecoverMisfiredJobsResult NO_OP = new JobStoreSupport.RecoverMisfiredJobsResult(false, 0, Long.MAX_VALUE);
      private boolean _hasMoreMisfiredTriggers;
      private int _processedMisfiredTriggerCount;
      private long _earliestNewTime;

      public RecoverMisfiredJobsResult(boolean hasMoreMisfiredTriggers, int processedMisfiredTriggerCount, long earliestNewTime) {
         this._hasMoreMisfiredTriggers = hasMoreMisfiredTriggers;
         this._processedMisfiredTriggerCount = processedMisfiredTriggerCount;
         this._earliestNewTime = earliestNewTime;
      }

      public boolean hasMoreMisfiredTriggers() {
         return this._hasMoreMisfiredTriggers;
      }

      public int getProcessedMisfiredTriggerCount() {
         return this._processedMisfiredTriggerCount;
      }

      public long getEarliestNewTime() {
         return this._earliestNewTime;
      }
   }
}
