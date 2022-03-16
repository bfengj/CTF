package org.quartz;

/** @deprecated */
@PersistJobDataAfterExecution
@DisallowConcurrentExecution
public interface StatefulJob extends Job {
}
