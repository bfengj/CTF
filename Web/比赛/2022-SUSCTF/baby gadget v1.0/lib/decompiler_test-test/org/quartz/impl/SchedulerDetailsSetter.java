package org.quartz.impl;

import java.lang.reflect.InvocationTargetException;
import java.lang.reflect.Method;
import java.lang.reflect.Modifier;
import org.quartz.SchedulerException;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

class SchedulerDetailsSetter {
   private static final Logger LOGGER = LoggerFactory.getLogger(SchedulerDetailsSetter.class);

   private SchedulerDetailsSetter() {
   }

   static void setDetails(Object target, String schedulerName, String schedulerId) throws SchedulerException {
      set(target, "setInstanceName", schedulerName);
      set(target, "setInstanceId", schedulerId);
   }

   private static void set(Object target, String method, String value) throws SchedulerException {
      Method setter;
      try {
         setter = target.getClass().getMethod(method, String.class);
      } catch (SecurityException var7) {
         LOGGER.error("A SecurityException occured: " + var7.getMessage(), var7);
         return;
      } catch (NoSuchMethodException var8) {
         LOGGER.warn(target.getClass().getName() + " does not contain public method " + method + "(String)");
         return;
      }

      if (Modifier.isAbstract(setter.getModifiers())) {
         LOGGER.warn(target.getClass().getName() + " does not implement " + method + "(String)");
      } else {
         try {
            setter.invoke(target, value);
         } catch (InvocationTargetException var5) {
            throw new SchedulerException(var5.getTargetException());
         } catch (Exception var6) {
            throw new SchedulerException(var6);
         }
      }
   }
}
