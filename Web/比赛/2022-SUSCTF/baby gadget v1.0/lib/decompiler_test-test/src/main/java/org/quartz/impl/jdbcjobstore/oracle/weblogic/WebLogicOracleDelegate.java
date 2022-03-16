package org.quartz.impl.jdbcjobstore.oracle.weblogic;

import java.lang.reflect.Method;
import java.sql.Blob;
import java.sql.ResultSet;
import java.sql.SQLException;
import org.quartz.impl.jdbcjobstore.oracle.OracleDelegate;
import weblogic.jdbc.vendor.oracle.OracleThinBlob;

public class WebLogicOracleDelegate extends OracleDelegate {
   protected Blob writeDataToBlob(ResultSet rs, int column, byte[] data) throws SQLException {
      Blob blob = rs.getBlob(column);
      if (blob == null) {
         throw new SQLException("Driver's Blob representation is null!");
      } else if (blob instanceof OracleThinBlob) {
         ((OracleThinBlob)blob).putBytes(1L, data);
         return blob;
      } else if (blob.getClass().getPackage().getName().startsWith("weblogic.")) {
         try {
            Method m = blob.getClass().getMethod("putBytes", Long.TYPE, byte[].class);
            m.invoke(blob, new Long(1L), data);
         } catch (Exception var8) {
            try {
               Method m = blob.getClass().getMethod("setBytes", Long.TYPE, byte[].class);
               m.invoke(blob, new Long(1L), data);
            } catch (Exception var7) {
               throw new SQLException("Unable to find putBytes(long,byte[]) or setBytes(long,byte[]) methods on blob: " + var7);
            }
         }

         return blob;
      } else {
         return super.writeDataToBlob(rs, column, data);
      }
   }
}
