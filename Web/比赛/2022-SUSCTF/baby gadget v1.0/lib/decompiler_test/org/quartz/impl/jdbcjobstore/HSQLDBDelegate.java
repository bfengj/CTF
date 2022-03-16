package org.quartz.impl.jdbcjobstore;

import java.io.IOException;
import java.io.InputStream;
import java.io.ObjectInputStream;
import java.sql.ResultSet;
import java.sql.SQLException;

public class HSQLDBDelegate extends StdJDBCDelegate {
   protected Object getObjectFromBlob(ResultSet rs, String colName) throws ClassNotFoundException, IOException, SQLException {
      InputStream binaryInput = rs.getBinaryStream(colName);
      if (binaryInput != null && binaryInput.available() != 0) {
         Object obj = null;
         ObjectInputStream in = new ObjectInputStream(binaryInput);

         try {
            obj = in.readObject();
         } finally {
            in.close();
         }

         return obj;
      } else {
         return null;
      }
   }

   protected Object getJobDataFromBlob(ResultSet rs, String colName) throws ClassNotFoundException, IOException, SQLException {
      if (this.canUseProperties()) {
         InputStream binaryInput = rs.getBinaryStream(colName);
         return binaryInput;
      } else {
         return this.getObjectFromBlob(rs, colName);
      }
   }
}
