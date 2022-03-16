package org.terracotta.quartz.collections;

import java.io.ByteArrayInputStream;
import java.io.ByteArrayOutputStream;
import java.io.IOException;
import java.io.InputStream;
import java.io.ObjectInputStream;
import java.io.ObjectOutputStream;
import java.io.OutputStream;

public class SerializationHelper {
   private static final char MARKER = '\ufffe';

   public static byte[] serialize(Object obj) {
      try {
         ByteArrayOutputStream baos = new ByteArrayOutputStream();
         ObjectOutputStream oos = new ObjectOutputStream(baos);
         oos.writeObject(obj);
         oos.close();
         return baos.toByteArray();
      } catch (IOException var3) {
         throw new RuntimeException("error serializing " + obj, var3);
      }
   }

   public static Object deserialize(byte[] bytes) {
      try {
         ByteArrayInputStream bais = new ByteArrayInputStream(bytes);
         ObjectInputStream ois = new ObjectInputStream(bais);
         Object obj = ois.readObject();
         ois.close();
         return obj;
      } catch (Exception var4) {
         throw new RuntimeException("error deserializing " + bytes, var4);
      }
   }

   public static Object deserializeFromString(String key) throws IOException, ClassNotFoundException {
      if (key.length() >= 1 && key.charAt(0) == '\ufffe') {
         ObjectInputStream ois = new ObjectInputStream(new SerializationHelper.StringSerializedObjectInputStream(key));
         return ois.readObject();
      } else {
         return key;
      }
   }

   public static String serializeToString(Object key) throws IOException {
      if (key instanceof String) {
         String stringKey = (String)key;
         if (stringKey.length() >= 1 && stringKey.charAt(0) == '\ufffe') {
            throw new IOException("Illegal string key: " + stringKey);
         } else {
            return stringKey;
         }
      } else {
         SerializationHelper.StringSerializedObjectOutputStream out = new SerializationHelper.StringSerializedObjectOutputStream();
         ObjectOutputStream oos = new ObjectOutputStream(out);
         writeStringKey(key, oos);
         oos.close();
         return out.toString();
      }
   }

   private static void writeStringKey(Object key, ObjectOutputStream oos) throws IOException {
      oos.writeObject(key);
   }

   private static class StringSerializedObjectInputStream extends InputStream {
      private final String source;
      private final int length;
      private int index;

      StringSerializedObjectInputStream(String source) {
         this.source = source;
         this.length = source.length();
         this.read();
      }

      public int read() {
         return this.index == this.length ? -1 : this.source.charAt(this.index++) & 255;
      }
   }

   private static class StringSerializedObjectOutputStream extends OutputStream {
      private int count;
      private char[] buf;

      StringSerializedObjectOutputStream() {
         this(16);
      }

      StringSerializedObjectOutputStream(int size) {
         size = Math.max(1, size);
         this.buf = new char[size];
         this.buf[this.count++] = '\ufffe';
      }

      public void write(int b) {
         if (this.count + 1 > this.buf.length) {
            char[] newbuf = new char[this.buf.length << 1];
            System.arraycopy(this.buf, 0, newbuf, 0, this.count);
            this.buf = newbuf;
         }

         this.writeChar(b);
      }

      private void writeChar(int b) {
         this.buf[this.count++] = (char)(b & 255);
      }

      public void write(byte[] b, int off, int len) {
         if (off >= 0 && off <= b.length && len >= 0 && off + len <= b.length && off + len >= 0) {
            if (len != 0) {
               int newcount = this.count + len;
               if (newcount > this.buf.length) {
                  char[] newbuf = new char[Math.max(this.buf.length << 1, newcount)];
                  System.arraycopy(this.buf, 0, newbuf, 0, this.count);
                  this.buf = newbuf;
               }

               for(int i = 0; i < len; ++i) {
                  this.writeChar(b[off + i]);
               }

            }
         } else {
            throw new IndexOutOfBoundsException();
         }
      }

      public String toString() {
         return new String(this.buf, 0, this.count);
      }
   }
}
