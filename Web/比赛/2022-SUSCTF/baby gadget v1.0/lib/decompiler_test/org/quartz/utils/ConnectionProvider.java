package org.quartz.utils;

import java.sql.Connection;
import java.sql.SQLException;

public interface ConnectionProvider {
   Connection getConnection() throws SQLException;

   void shutdown() throws SQLException;

   void initialize() throws SQLException;
}
