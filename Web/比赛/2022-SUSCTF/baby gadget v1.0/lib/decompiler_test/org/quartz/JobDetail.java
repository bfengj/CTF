package org.quartz;

import java.io.Serializable;

public interface JobDetail extends Serializable, Cloneable {
   JobKey getKey();

   String getDescription();

   Class<? extends Job> getJobClass();

   JobDataMap getJobDataMap();

   boolean isDurable();

   boolean isPersistJobDataAfterExecution();

   boolean isConcurrentExectionDisallowed();

   boolean requestsRecovery();

   Object clone();

   JobBuilder getJobBuilder();
}
