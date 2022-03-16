package org.quartz.impl.jdbcjobstore;

import java.sql.PreparedStatement;
import java.sql.SQLException;

public class DB2v8Delegate extends StdJDBCDelegate {
   protected void setBoolean(PreparedStatement ps, int index, boolean val) throws SQLException {
      ps.setInt(index, val ? 1 : 0);
   }
}
