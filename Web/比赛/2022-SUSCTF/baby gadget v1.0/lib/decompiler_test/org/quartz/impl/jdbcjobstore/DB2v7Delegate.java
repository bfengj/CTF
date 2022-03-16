package org.quartz.impl.jdbcjobstore;

import java.io.ByteArrayOutputStream;
import java.sql.PreparedStatement;
import java.sql.SQLException;

public class DB2v7Delegate extends StdJDBCDelegate {
   protected void setBytes(PreparedStatement ps, int index, ByteArrayOutputStream baos) throws SQLException {
      ps.setObject(index, baos == null ? null : baos.toByteArray(), 2004);
   }

   protected void setBoolean(PreparedStatement ps, int index, boolean val) throws SQLException {
      ps.setString(index, val ? "1" : "0");
   }
}
