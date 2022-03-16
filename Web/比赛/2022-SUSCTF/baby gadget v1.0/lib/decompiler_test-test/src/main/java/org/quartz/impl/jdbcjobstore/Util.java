package org.quartz.impl.jdbcjobstore;

import java.beans.BeanInfo;
import java.beans.Introspector;
import java.beans.PropertyDescriptor;
import java.lang.reflect.Method;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;
import java.text.MessageFormat;
import java.util.Locale;
import org.quartz.JobPersistenceException;

public final class Util {
   private Util() {
   }

   public static String rtp(String query, String tablePrefix, String schedNameLiteral) {
      return MessageFormat.format(query, tablePrefix, schedNameLiteral);
   }

   static String getJobNameKey(String jobName, String groupName) {
      return (groupName + "_$x$x$_" + jobName).intern();
   }

   static String getTriggerNameKey(String triggerName, String groupName) {
      return (groupName + "_$x$x$_" + triggerName).intern();
   }

   public static void closeResultSet(ResultSet rs) {
      if (null != rs) {
         try {
            rs.close();
         } catch (SQLException var2) {
         }
      }

   }

   public static void closeStatement(Statement statement) {
      if (null != statement) {
         try {
            statement.close();
         } catch (SQLException var2) {
         }
      }

   }

   public static void setBeanProps(Object obj, String[] propNames, Object[] propValues) throws JobPersistenceException {
      if (propNames != null && propNames.length != 0) {
         if (propNames.length != propValues.length) {
            throw new IllegalArgumentException("propNames[].lenght != propValues[].length");
         } else {
            String name = null;

            try {
               BeanInfo bi = Introspector.getBeanInfo(obj.getClass());
               PropertyDescriptor[] propDescs = bi.getPropertyDescriptors();

               for(int i = 0; i < propNames.length; ++i) {
                  name = propNames[i];
                  String c = name.substring(0, 1).toUpperCase(Locale.US);
                  String methName = "set" + c + name.substring(1);
                  Method setMeth = getSetMethod(methName, propDescs);
                  if (setMeth == null) {
                     throw new NoSuchMethodException("No setter for property '" + name + "'");
                  }

                  Class<?>[] params = setMeth.getParameterTypes();
                  if (params.length != 1) {
                     throw new NoSuchMethodException("No 1-argument setter for property '" + name + "'");
                  }

                  setMeth.invoke(obj, propValues[i]);
               }

            } catch (Exception var11) {
               throw new JobPersistenceException("Unable to set property named: " + name + " of object of type: " + obj.getClass().getCanonicalName(), var11);
            }
         }
      }
   }

   private static Method getSetMethod(String name, PropertyDescriptor[] props) {
      for(int i = 0; i < props.length; ++i) {
         Method wMeth = props[i].getWriteMethod();
         if (wMeth != null && wMeth.getName().equals(name)) {
            return wMeth;
         }
      }

      return null;
   }
}
