package org.quartz.simpl;

import java.io.InputStream;
import java.net.URL;
import org.quartz.spi.ClassLoadHelper;

public class LoadingLoaderClassLoadHelper implements ClassLoadHelper {
   public void initialize() {
   }

   public Class<?> loadClass(String name) throws ClassNotFoundException {
      return this.getClassLoader().loadClass(name);
   }

   public <T> Class<? extends T> loadClass(String name, Class<T> clazz) throws ClassNotFoundException {
      return this.loadClass(name);
   }

   public URL getResource(String name) {
      return this.getClassLoader().getResource(name);
   }

   public InputStream getResourceAsStream(String name) {
      return this.getClassLoader().getResourceAsStream(name);
   }

   public ClassLoader getClassLoader() {
      return this.getClass().getClassLoader();
   }
}
