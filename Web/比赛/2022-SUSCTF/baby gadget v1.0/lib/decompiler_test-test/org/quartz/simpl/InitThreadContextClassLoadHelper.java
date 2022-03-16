package org.quartz.simpl;

import java.io.InputStream;
import java.net.URL;
import org.quartz.spi.ClassLoadHelper;

public class InitThreadContextClassLoadHelper implements ClassLoadHelper {
   private ClassLoader initClassLoader;

   public void initialize() {
      this.initClassLoader = Thread.currentThread().getContextClassLoader();
   }

   public Class<?> loadClass(String name) throws ClassNotFoundException {
      return this.initClassLoader.loadClass(name);
   }

   public <T> Class<? extends T> loadClass(String name, Class<T> clazz) throws ClassNotFoundException {
      return this.loadClass(name);
   }

   public URL getResource(String name) {
      return this.initClassLoader.getResource(name);
   }

   public InputStream getResourceAsStream(String name) {
      return this.initClassLoader.getResourceAsStream(name);
   }

   public ClassLoader getClassLoader() {
      return this.initClassLoader;
   }
}
