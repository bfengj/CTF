package org.quartz.impl.jdbcjobstore;

import java.io.IOException;
import java.io.InputStream;
import java.io.ObjectInputStream;
import java.sql.Blob;
import java.sql.ResultSet;
import java.sql.SQLException;

public class WebLogicDelegate extends StdJDBCDelegate {
   protected Object getObjectFromBlob(ResultSet rs, String colName) throws ClassNotFoundException, IOException, SQLException {
      Object obj = null;
      Blob blobLocator = rs.getBlob(colName);
      InputStream binaryInput = null;

      try {
         if (null != blobLocator && blobLocator.length() > 0L) {
            binaryInput = blobLocator.getBinaryStream();
         }
      } catch (Exception var11) {
      }

      if (null != binaryInput) {
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
         Blob blobLocator = rs.getBlob(colName);
         InputStream binaryInput = null;

         try {
            if (null != blobLocator && blobLocator.length() > 0L) {
               binaryInput = blobLocator.getBinaryStream();
            }
         } catch (Exception var6) {
         }

         return binaryInput;
      } else {
         return this.getObjectFromBlob(rs, colName);
      }
   }
}
