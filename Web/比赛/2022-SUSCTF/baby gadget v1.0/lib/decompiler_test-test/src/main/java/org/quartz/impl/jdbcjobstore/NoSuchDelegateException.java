package org.quartz.impl.jdbcjobstore;

import org.quartz.JobPersistenceException;

public class NoSuchDelegateException extends JobPersistenceException {
   private static final long serialVersionUID = -4255865028975822979L;

   public NoSuchDelegateException(String msg) {
      super(msg);
   }

   public NoSuchDelegateException(String msg, Throwable cause) {
      super(msg, cause);
   }
}
