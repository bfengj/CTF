package org.quartz.simpl;

import java.io.InputStream;
import java.net.URL;
import java.util.Iterator;
import java.util.LinkedList;
import org.quartz.spi.ClassLoadHelper;

public class CascadingClassLoadHelper implements ClassLoadHelper {
   private LinkedList<ClassLoadHelper> loadHelpers;
   private ClassLoadHelper bestCandidate;

   public void initialize() {
      this.loadHelpers = new LinkedList();
      this.loadHelpers.add(new LoadingLoaderClassLoadHelper());
      this.loadHelpers.add(new SimpleClassLoadHelper());
      this.loadHelpers.add(new ThreadContextClassLoadHelper());
      this.loadHelpers.add(new InitThreadContextClassLoadHelper());
      Iterator i$ = this.loadHelpers.iterator();

      while(i$.hasNext()) {
         ClassLoadHelper loadHelper = (ClassLoadHelper)i$.next();
         loadHelper.initialize();
      }

   }

   public Class<?> loadClass(String name) throws ClassNotFoundException {
      if (this.bestCandidate != null) {
         try {
            return this.bestCandidate.loadClass(name);
         } catch (Throwable var8) {
            this.bestCandidate = null;
         }
      }

      Throwable throwable = null;
      Class<?> clazz = null;
      ClassLoadHelper loadHelper = null;
      Iterator iter = this.loadHelpers.iterator();

      while(iter.hasNext()) {
         loadHelper = (ClassLoadHelper)iter.next();

         try {
            clazz = loadHelper.loadClass(name);
            break;
         } catch (Throwable var7) {
            throwable = var7;
         }
      }

      if (clazz == null) {
         if (throwable instanceof ClassNotFoundException) {
            throw (ClassNotFoundException)throwable;
         } else {
            throw new ClassNotFoundException(String.format("Unable to load class %s by any known loaders.", name), throwable);
         }
      } else {
         this.bestCandidate = loadHelper;
         return clazz;
      }
   }

   public <T> Class<? extends T> loadClass(String name, Class<T> clazz) throws ClassNotFoundException {
      return this.loadClass(name);
   }

   public URL getResource(String name) {
      URL result = null;
      if (this.bestCandidate != null) {
         result = this.bestCandidate.getResource(name);
         if (result != null) {
            return result;
         }

         this.bestCandidate = null;
      }

      ClassLoadHelper loadHelper = null;
      Iterator iter = this.loadHelpers.iterator();

      while(iter.hasNext()) {
         loadHelper = (ClassLoadHelper)iter.next();
         result = loadHelper.getResource(name);
         if (result != null) {
            break;
         }
      }

      this.bestCandidate = loadHelper;
      return result;
   }

   public InputStream getResourceAsStream(String name) {
      InputStream result = null;
      if (this.bestCandidate != null) {
         result = this.bestCandidate.getResourceAsStream(name);
         if (result != null) {
            return result;
         }

         this.bestCandidate = null;
      }

      ClassLoadHelper loadHelper = null;
      Iterator iter = this.loadHelpers.iterator();

      while(iter.hasNext()) {
         loadHelper = (ClassLoadHelper)iter.next();
         result = loadHelper.getResourceAsStream(name);
         if (result != null) {
            break;
         }
      }

      this.bestCandidate = loadHelper;
      return result;
   }

   public ClassLoader getClassLoader() {
      return this.bestCandidate == null ? Thread.currentThread().getContextClassLoader() : this.bestCandidate.getClassLoader();
   }
}
