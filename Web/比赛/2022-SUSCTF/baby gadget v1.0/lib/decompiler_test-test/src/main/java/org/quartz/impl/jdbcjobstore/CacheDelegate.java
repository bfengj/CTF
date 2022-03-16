package org.quartz.impl.jdbcjobstore;

import java.io.ByteArrayInputStream;
import java.io.ByteArrayOutputStream;
import java.io.IOException;
import java.io.InputStream;
import java.io.ObjectInputStream;
import java.sql.Blob;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;

public class CacheDelegate extends StdJDBCDelegate {
   protected void setBytes(PreparedStatement ps, int index, ByteArrayOutputStream baos) throws SQLException {
      ps.setObject(index, baos == null ? null : baos.toByteArray(), 2004);
   }

   protected Object getObjectFromBlob(ResultSet rs, String colName) throws ClassNotFoundException, IOException, SQLException {
      Blob blob = rs.getBlob(colName);
      if (blob == null) {
         return null;
      } else {
         Object var6;
         try {
            InputStream binaryInput;
            if (blob.length() == 0L) {
               binaryInput = null;
               return binaryInput;
            }

            binaryInput = blob.getBinaryStream();
            ObjectInputStream in;
            if (binaryInput == null) {
               in = null;
               return in;
            }

            if (binaryInput instanceof ByteArrayInputStream && ((ByteArrayInputStream)binaryInput).available() == 0) {
               in = null;
               return in;
            }

            in = new ObjectInputStream(binaryInput);

            try {
               var6 = in.readObject();
            } finally {
               in.close();
            }
         } finally {
            blob.free();
         }

         return var6;
      }
   }

   protected Object getJobDataFromBlob(ResultSet rs, String colName) throws ClassNotFoundException, IOException, SQLException {
      if (this.canUseProperties()) {
         Blob blob = rs.getBlob(colName);
         return blob == null ? null : new CacheDelegate.BlobFreeingStream(blob, blob.getBinaryStream());
      } else {
         return this.getObjectFromBlob(rs, colName);
      }
   }

   private static class BlobFreeingStream extends InputStream {
      private final Blob source;
      private final InputStream delegate;

      private BlobFreeingStream(Blob blob, InputStream stream) {
         this.source = blob;
         this.delegate = stream;
      }

      public int read() throws IOException {
         return this.delegate.read();
      }

      public int read(byte[] b) throws IOException {
         return this.delegate.read(b);
      }

      public int read(byte[] b, int off, int len) throws IOException {
         return this.delegate.read(b, off, len);
      }

      public long skip(long n) throws IOException {
         return this.delegate.skip(n);
      }

      public int available() throws IOException {
         return this.delegate.available();
      }

      public void close() throws IOException {
         try {
            this.delegate.close();
         } finally {
            try {
               this.source.free();
            } catch (SQLException var7) {
               throw new IOException(var7);
            }
         }

      }

      public synchronized void mark(int readlimit) {
         this.delegate.mark(readlimit);
      }

      public synchronized void reset() throws IOException {
         this.delegate.reset();
      }

      public boolean markSupported() {
         return this.delegate.markSupported();
      }

      // $FF: synthetic method
      BlobFreeingStream(Blob x0, InputStream x1, Object x2) {
         this(x0, x1);
      }
   }
}
