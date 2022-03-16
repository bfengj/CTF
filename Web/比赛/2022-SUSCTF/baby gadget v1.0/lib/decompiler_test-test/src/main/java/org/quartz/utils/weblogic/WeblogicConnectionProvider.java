package org.quartz.utils.weblogic;

import java.sql.Connection;
import java.sql.SQLException;
import java.util.Properties;
import org.quartz.utils.ConnectionProvider;
import weblogic.jdbc.jts.Driver;

public class WeblogicConnectionProvider implements ConnectionProvider {
   private String poolName;
   private Driver driver;

   public WeblogicConnectionProvider(String poolName) {
      this.poolName = poolName;
   }

   public Connection getConnection() throws SQLException {
      return this.driver.connect("jdbc:weblogic:jts:" + this.poolName, (Properties)null);
   }

   public void initialize() throws SQLException {
      try {
         this.driver = (Driver)Driver.class.newInstance();
      } catch (Exception var2) {
         throw new SQLException("Could not get weblogic pool connection with name '" + this.poolName + "': " + var2.getClass().getName() + ": " + var2.getMessage());
      }
   }

   public void shutdown() throws SQLException {
   }
}
