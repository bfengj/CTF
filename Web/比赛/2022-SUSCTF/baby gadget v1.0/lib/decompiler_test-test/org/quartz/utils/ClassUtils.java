package org.quartz.utils;

import java.lang.annotation.Annotation;
import java.util.Arrays;
import java.util.LinkedList;
import java.util.Queue;

public class ClassUtils {
   public static boolean isAnnotationPresent(Class<?> clazz, Class<? extends Annotation> a) {
      for(Class c = clazz; c != null; c = c.getSuperclass()) {
         if (c.isAnnotationPresent(a)) {
            return true;
         }

         if (isAnnotationPresentOnInterfaces(c, a)) {
            return true;
         }
      }

      return false;
   }

   private static boolean isAnnotationPresentOnInterfaces(Class<?> clazz, Class<? extends Annotation> a) {
      Class[] arr$ = clazz.getInterfaces();
      int len$ = arr$.length;

      for(int i$ = 0; i$ < len$; ++i$) {
         Class<?> i = arr$[i$];
         if (i.isAnnotationPresent(a)) {
            return true;
         }

         if (isAnnotationPresentOnInterfaces(i, a)) {
            return true;
         }
      }

      return false;
   }

   public static <T extends Annotation> T getAnnotation(Class<?> clazz, Class<T> aClazz) {
      for(Class c = clazz; c != null; c = c.getSuperclass()) {
         T anno = c.getAnnotation(aClazz);
         if (anno != null) {
            return anno;
         }
      }

      Queue<Class<?>> q = new LinkedList();
      q.add(clazz);

      while(!q.isEmpty()) {
         Class<?> c = (Class)q.remove();
         if (c != null) {
            if (c.isInterface()) {
               T anno = c.getAnnotation(aClazz);
               if (anno != null) {
                  return anno;
               }
            } else {
               q.add(c.getSuperclass());
            }

            q.addAll(Arrays.asList(c.getInterfaces()));
         }
      }

      return null;
   }
}
