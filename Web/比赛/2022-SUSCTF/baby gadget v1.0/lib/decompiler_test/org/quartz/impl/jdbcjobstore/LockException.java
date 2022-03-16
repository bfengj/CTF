package org.quartz.impl.jdbcjobstore;

import org.quartz.JobPersistenceException;

public class LockException extends JobPersistenceException {
   private static final long serialVersionUID = 3993800462589137228L;

   public LockException(String msg) {
      super(msg);
   }

   public LockException(String msg, Throwable cause) {
      super(msg, cause);
   }
}
