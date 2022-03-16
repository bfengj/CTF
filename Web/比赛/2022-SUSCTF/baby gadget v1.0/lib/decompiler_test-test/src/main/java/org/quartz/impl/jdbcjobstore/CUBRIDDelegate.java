package org.quartz.impl.jdbcjobstore;

import com.mchange.v2.c3p0.C3P0ProxyConnection;
import java.io.ByteArrayInputStream;
import java.io.ByteArrayOutputStream;
import java.io.IOException;
import java.io.InputStream;
import java.io.ObjectInputStream;
import java.lang.reflect.Method;
import java.sql.Blob;
import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;

public class CUBRIDDelegate extends StdJDBCDelegate {
   protected Object getObjectFromBlob(ResultSet rs, String colName) throws ClassNotFoundException, IOException, SQLException {
      Object obj = null;
      Blob blob = rs.getBlob(colName);
      byte[] bytes = blob.getBytes(1L, (int)blob.length());
      if (bytes != null && bytes.length != 0) {
         InputStream binaryInput = new ByteArrayInputStream(bytes);
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
         Blob blob = rs.getBlob(colName);
         byte[] bytes = blob.getBytes(1L, (int)blob.length());
         if (bytes != null && bytes.length != 0) {
            InputStream binaryInput = new ByteArrayInputStream(bytes);
            return binaryInput;
         } else {
            return null;
         }
      } else {
         return this.getObjectFromBlob(rs, colName);
      }
   }

   protected void setBytes(PreparedStatement ps, int index, ByteArrayOutputStream baos) throws SQLException {
      byte[] byteArray;
      if (baos == null) {
         byteArray = new byte[0];
      } else {
         byteArray = baos.toByteArray();
      }

      Connection conn = ps.getConnection();
      if (conn instanceof C3P0ProxyConnection) {
         try {
            C3P0ProxyConnection c3p0Conn = (C3P0ProxyConnection)conn;
            Method m = Connection.class.getMethod("createBlob");
            Object[] args = new Object[0];
            Blob blob = (Blob)c3p0Conn.rawConnectionOperation(m, C3P0ProxyConnection.RAW_CONNECTION, args);
            blob.setBytes(1L, byteArray);
            ps.setBlob(index, blob);
         } catch (Exception var10) {
            var10.printStackTrace();
         }
      } else {
         Blob blob = ps.getConnection().createBlob();
         blob.setBytes(1L, byteArray);
         ps.setBlob(index, blob);
      }

   }
}
