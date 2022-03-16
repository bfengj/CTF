package org.quartz.simpl;

import java.io.InputStream;
import java.lang.reflect.AccessibleObject;
import java.lang.reflect.Method;
import java.net.URL;
import org.quartz.spi.ClassLoadHelper;

public class SimpleClassLoadHelper implements ClassLoadHelper {
   public void initialize() {
   }

   public Class<?> loadClass(String name) throws ClassNotFoundException {
      return Class.forName(name);
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
      try {
         ClassLoader cl = this.getClass().getClassLoader();
         Method mthd = ClassLoader.class.getDeclaredMethod("getCallerClassLoader");
         AccessibleObject.setAccessible(new AccessibleObject[]{mthd}, true);
         return (ClassLoader)mthd.invoke(cl);
      } catch (Throwable var3) {
         return this.getClass().getClassLoader();
      }
   }
}
