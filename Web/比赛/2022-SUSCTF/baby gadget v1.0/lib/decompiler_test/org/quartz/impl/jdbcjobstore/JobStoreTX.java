package org.quartz.impl.jdbcjobstore;

import java.sql.Connection;
import org.quartz.JobPersistenceException;
import org.quartz.SchedulerConfigException;
import org.quartz.spi.ClassLoadHelper;
import org.quartz.spi.SchedulerSignaler;

public class JobStoreTX extends JobStoreSupport {
   public void initialize(ClassLoadHelper classLoadHelper, SchedulerSignaler schedSignaler) throws SchedulerConfigException {
      super.initialize(classLoadHelper, schedSignaler);
      this.getLog().info("JobStoreTX initialized.");
   }

   protected Connection getNonManagedTXConnection() throws JobPersistenceException {
      return this.getConnection();
   }

   protected Object executeInLock(String lockName, JobStoreSupport.TransactionCallback txCallback) throws JobPersistenceException {
      return this.executeInNonManagedTXLock(lockName, txCallback, (JobStoreSupport.TransactionValidator)null);
   }
}
