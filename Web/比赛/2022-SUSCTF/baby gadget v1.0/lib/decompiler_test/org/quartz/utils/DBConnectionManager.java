package org.quartz.utils;

import java.sql.Connection;
import java.sql.SQLException;
import java.util.HashMap;

public class DBConnectionManager {
   public static final String DB_PROPS_PREFIX = "org.quartz.db.";
   private static DBConnectionManager instance = new DBConnectionManager();
   private HashMap<String, ConnectionProvider> providers = new HashMap();

   private DBConnectionManager() {
   }

   public void addConnectionProvider(String dataSourceName, ConnectionProvider provider) {
      this.providers.put(dataSourceName, provider);
   }

   public Connection getConnection(String dsName) throws SQLException {
      ConnectionProvider provider = (ConnectionProvider)this.providers.get(dsName);
      if (provider == null) {
         throw new SQLException("There is no DataSource named '" + dsName + "'");
      } else {
         return provider.getConnection();
      }
   }

   public static DBConnectionManager getInstance() {
      return instance;
   }

   public void shutdown(String dsName) throws SQLException {
      ConnectionProvider provider = (ConnectionProvider)this.providers.get(dsName);
      if (provider == null) {
         throw new SQLException("There is no DataSource named '" + dsName + "'");
      } else {
         provider.shutdown();
      }
   }
}
