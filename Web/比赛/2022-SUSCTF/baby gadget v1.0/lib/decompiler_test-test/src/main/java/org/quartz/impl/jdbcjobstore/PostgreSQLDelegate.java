package org.quartz.impl.jdbcjobstore;

import java.io.ByteArrayInputStream;
import java.io.IOException;
import java.io.InputStream;
import java.io.ObjectInputStream;
import java.sql.ResultSet;
import java.sql.SQLException;

public class PostgreSQLDelegate extends StdJDBCDelegate {
   protected Object getObjectFromBlob(ResultSet rs, String colName) throws ClassNotFoundException, IOException, SQLException {
      InputStream binaryInput = null;
      byte[] bytes = rs.getBytes(colName);
      Object obj = null;
      if (bytes != null && bytes.length != 0) {
         binaryInput = new ByteArrayInputStream(bytes);
         ObjectInputStream in = new ObjectInputStream(binaryInput);

         try {
            obj = in.readObject();
         } finally {
            in.close();
         }
      }

      return obj;
   }

   protected Object getJobDataFromBlob(ResultSet rs, String colName) throws ClassNotFoundException, IOException, SQLException {
      if (this.canUseProperties()) {
         InputStream binaryInput = null;
         byte[] bytes = rs.getBytes(colName);
         if (bytes != null && bytes.length != 0) {
            binaryInput = new ByteArrayInputStream(bytes);
            return binaryInput;
         } else {
            return null;
         }
      } else {
         return this.getObjectFromBlob(rs, colName);
      }
   }
}
