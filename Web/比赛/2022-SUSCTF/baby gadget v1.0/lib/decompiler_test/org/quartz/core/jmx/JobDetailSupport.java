package org.quartz.core.jmx;

import java.util.ArrayList;
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
import org.quartz.JobDetail;
import org.quartz.impl.JobDetailImpl;

public class JobDetailSupport {
   private static final String COMPOSITE_TYPE_NAME = "JobDetail";
   private static final String COMPOSITE_TYPE_DESCRIPTION = "Job Execution Details";
   private static final String[] ITEM_NAMES = new String[]{"name", "group", "description", "jobClass", "jobDataMap", "durability", "shouldRecover"};
   private static final String[] ITEM_DESCRIPTIONS = new String[]{"name", "group", "description", "jobClass", "jobDataMap", "durability", "shouldRecover"};
   private static final OpenType[] ITEM_TYPES;
   private static final CompositeType COMPOSITE_TYPE;
   private static final String TABULAR_TYPE_NAME = "JobDetail collection";
   private static final String TABULAR_TYPE_DESCRIPTION = "JobDetail collection";
   private static final String[] INDEX_NAMES;
   private static final TabularType TABULAR_TYPE;

   public static JobDetail newJobDetail(CompositeData cData) throws ClassNotFoundException {
      JobDetailImpl jobDetail = new JobDetailImpl();
      int i = 0;
      int var5 = i + 1;
      jobDetail.setName((String)cData.get(ITEM_NAMES[i]));
      jobDetail.setGroup((String)cData.get(ITEM_NAMES[var5++]));
      jobDetail.setDescription((String)cData.get(ITEM_NAMES[var5++]));
      Class<?> jobClass = Class.forName((String)cData.get(ITEM_NAMES[var5++]));
      jobDetail.setJobClass(jobClass);
      jobDetail.setJobDataMap(JobDataMapSupport.newJobDataMap((TabularData)cData.get(ITEM_NAMES[var5++])));
      jobDetail.setDurability((Boolean)cData.get(ITEM_NAMES[var5++]));
      jobDetail.setRequestsRecovery((Boolean)cData.get(ITEM_NAMES[var5++]));
      return jobDetail;
   }

   public static JobDetail newJobDetail(Map<String, Object> attrMap) throws ClassNotFoundException {
      JobDetailImpl jobDetail = new JobDetailImpl();
      int i = 0;
      int i = i + 1;
      jobDetail.setName((String)attrMap.get(ITEM_NAMES[i]));
      jobDetail.setGroup((String)attrMap.get(ITEM_NAMES[i++]));
      jobDetail.setDescription((String)attrMap.get(ITEM_NAMES[i++]));
      Class<?> jobClass = Class.forName((String)attrMap.get(ITEM_NAMES[i++]));
      jobDetail.setJobClass(jobClass);
      if (attrMap.containsKey(ITEM_NAMES[i])) {
         Map<String, Object> map = (Map)attrMap.get(ITEM_NAMES[i]);
         jobDetail.setJobDataMap(JobDataMapSupport.newJobDataMap(map));
      }

      ++i;
      if (attrMap.containsKey(ITEM_NAMES[i])) {
         jobDetail.setDurability((Boolean)attrMap.get(ITEM_NAMES[i]));
      }

      ++i;
      if (attrMap.containsKey(ITEM_NAMES[i])) {
         jobDetail.setRequestsRecovery((Boolean)attrMap.get(ITEM_NAMES[i]));
      }

      ++i;
      return jobDetail;
   }

   public static CompositeData toCompositeData(JobDetail jobDetail) {
      try {
         return new CompositeDataSupport(COMPOSITE_TYPE, ITEM_NAMES, new Object[]{jobDetail.getKey().getName(), jobDetail.getKey().getGroup(), jobDetail.getDescription(), jobDetail.getJobClass().getName(), JobDataMapSupport.toTabularData(jobDetail.getJobDataMap()), jobDetail.isDurable(), jobDetail.requestsRecovery()});
      } catch (OpenDataException var2) {
         throw new RuntimeException(var2);
      }
   }

   public static TabularData toTabularData(JobDetail[] jobDetails) {
      TabularData tData = new TabularDataSupport(TABULAR_TYPE);
      if (jobDetails != null) {
         ArrayList<CompositeData> list = new ArrayList();
         JobDetail[] arr$ = jobDetails;
         int len$ = jobDetails.length;

         for(int i$ = 0; i$ < len$; ++i$) {
            JobDetail jobDetail = arr$[i$];
            list.add(toCompositeData(jobDetail));
         }

         tData.putAll((CompositeData[])list.toArray(new CompositeData[list.size()]));
      }

      return tData;
   }

   static {
      ITEM_TYPES = new OpenType[]{SimpleType.STRING, SimpleType.STRING, SimpleType.STRING, SimpleType.STRING, JobDataMapSupport.TABULAR_TYPE, SimpleType.BOOLEAN, SimpleType.BOOLEAN};
      INDEX_NAMES = new String[]{"name", "group"};

      try {
         COMPOSITE_TYPE = new CompositeType("JobDetail", "Job Execution Details", ITEM_NAMES, ITEM_DESCRIPTIONS, ITEM_TYPES);
         TABULAR_TYPE = new TabularType("JobDetail collection", "JobDetail collection", COMPOSITE_TYPE, INDEX_NAMES);
      } catch (OpenDataException var1) {
         throw new RuntimeException(var1);
      }
   }
}
