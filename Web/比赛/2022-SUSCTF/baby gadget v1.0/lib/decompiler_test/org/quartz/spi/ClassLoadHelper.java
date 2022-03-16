package org.quartz.spi;

import java.io.InputStream;
import java.net.URL;

public interface ClassLoadHelper {
   void initialize();

   Class<?> loadClass(String var1) throws ClassNotFoundException;

   <T> Class<? extends T> loadClass(String var1, Class<T> var2) throws ClassNotFoundException;

   URL getResource(String var1);

   InputStream getResourceAsStream(String var1);

   ClassLoader getClassLoader();
}
