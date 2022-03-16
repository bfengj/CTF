package org.quartz.impl.jdbcjobstore;

public class InvalidConfigurationException extends Exception {
   private static final long serialVersionUID = 1836325935209404611L;

   public InvalidConfigurationException(String msg) {
      super(msg);
   }

   public InvalidConfigurationException() {
   }
}
