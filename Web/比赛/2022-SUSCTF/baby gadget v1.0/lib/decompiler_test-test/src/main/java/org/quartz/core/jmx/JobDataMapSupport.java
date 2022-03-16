package org.quartz.core.jmx;

import java.util.ArrayList;
import java.util.Iterator;
import java.util.Map;
import javax.management.openmbean.CompositeData;
import javax.management.openmbean.CompositeDataSupport;
import javax.management.openmbean.CompositeType;
import javax.management.openmbean.OpenDataException;
import javax.management.openmbean.OpenType;
import javax.management.openmbean.SimpleType;
import javax.management.openmbean.TabularData;
import javax.management.openmbean.TabularDataSupport;
import javax.management.openmbean.TabularType;
import org.quartz.JobDataMap;

public class JobDataMapSupport {
   private static final String typeName = "JobDataMap";
   private static final String[] keyValue = new String[]{"key", "value"};
   private static final OpenType[] openTypes;
   private static final CompositeType rowType;
   public static final TabularType TABULAR_TYPE;

   public static JobDataMap newJobDataMap(TabularData tabularData) {
      JobDataMap jobDataMap = new JobDataMap();
      if (tabularData != null) {
         Iterator pos = tabularData.values().iterator();

         while(pos.hasNext()) {
            CompositeData cData = (CompositeData)pos.next();
            jobDataMap.put((String)cData.get("key"), (String)cData.get("value"));
         }
      }

      return jobDataMap;
   }

   public static JobDataMap newJobDataMap(Map<String, Object> map) {
      JobDataMap jobDataMap = new JobDataMap();
      if (map != null) {
         Iterator pos = map.keySet().iterator();

         while(pos.hasNext()) {
            String key = (String)pos.next();
            jobDataMap.put(key, map.get(key));
         }
      }

      return jobDataMap;
   }

   public static CompositeData toCompositeData(String key, String value) {
      try {
         return new CompositeDataSupport(rowType, keyValue, new Object[]{key, value});
      } catch (OpenDataException var3) {
         throw new RuntimeException(var3);
      }
   }

   public static TabularData toTabularData(JobDataMap jobDataMap) {
      TabularData tData = new TabularDataSupport(TABULAR_TYPE);
      ArrayList<CompositeData> list = new ArrayList();
      Iterator iter = jobDataMap.keySet().iterator();

      while(iter.hasNext()) {
         String key = (String)iter.next();
         list.add(toCompositeData(key, String.valueOf(jobDataMap.get(key))));
      }

      tData.putAll((CompositeData[])list.toArray(new CompositeData[list.size()]));
      return tData;
   }

   static {
      openTypes = new OpenType[]{SimpleType.STRING, SimpleType.STRING};

      try {
         rowType = new CompositeType("JobDataMap", "JobDataMap", keyValue, keyValue, openTypes);
         TABULAR_TYPE = new TabularType("JobDataMap", "JobDataMap", rowType, new String[]{"key"});
      } catch (OpenDataException var1) {
         throw new RuntimeException(var1);
      }
   }
}
