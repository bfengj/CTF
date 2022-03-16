package org.quartz.impl.jdbcjobstore;

import java.sql.Connection;

public interface Semaphore {
   boolean obtainLock(Connection var1, String var2) throws LockException;

   void releaseLock(String var1) throws LockException;

   boolean requiresConnection();
}
