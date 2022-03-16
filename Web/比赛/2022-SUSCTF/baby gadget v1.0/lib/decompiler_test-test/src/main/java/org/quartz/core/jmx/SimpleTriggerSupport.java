package org.quartz.core.jmx;

import java.text.ParseException;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.Iterator;
import java.util.List;
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
import org.quartz.SimpleTrigger;
import org.quartz.impl.triggers.SimpleTriggerImpl;
import org.quartz.spi.OperableTrigger;

public class SimpleTriggerSupport {
   private static final String COMPOSITE_TYPE_NAME = "SimpleTrigger";
   private static final String COMPOSITE_TYPE_DESCRIPTION = "SimpleTrigger Details";
   private static final String[] ITEM_NAMES = new String[]{"repeatCount", "repeatInterval", "timesTriggered"};
   private static final String[] ITEM_DESCRIPTIONS = new String[]{"repeatCount", "repeatInterval", "timesTriggered"};
   private static final OpenType[] ITEM_TYPES;
   private static final CompositeType COMPOSITE_TYPE;
   private static final String TABULAR_TYPE_NAME = "SimpleTrigger collection";
   private static final String TABULAR_TYPE_DESCRIPTION = "SimpleTrigger collection";
   private static final TabularType TABULAR_TYPE;

   public static String[] getItemNames() {
      List<String> l = new ArrayList(Arrays.asList(ITEM_NAMES));
      l.addAll(Arrays.asList(TriggerSupport.getItemNames()));
      return (String[])l.toArray(new String[l.size()]);
   }

   public static String[] getItemDescriptions() {
      List<String> l = new ArrayList(Arrays.asList(ITEM_DESCRIPTIONS));
      l.addAll(Arrays.asList(TriggerSupport.getItemDescriptions()));
      return (String[])l.toArray(new String[l.size()]);
   }

   public static OpenType[] getItemTypes() {
      List<OpenType> l = new ArrayList(Arrays.asList(ITEM_TYPES));
      l.addAll(Arrays.asList(TriggerSupport.getItemTypes()));
      return (OpenType[])l.toArray(new OpenType[l.size()]);
   }

   public static CompositeData toCompositeData(SimpleTrigger trigger) {
      try {
         return new CompositeDataSupport(COMPOSITE_TYPE, ITEM_NAMES, new Object[]{trigger.getRepeatCount(), trigger.getRepeatInterval(), trigger.getTimesTriggered(), trigger.getKey().getName(), trigger.getKey().getGroup(), trigger.getJobKey().getName(), trigger.getJobKey().getGroup(), trigger.getDescription(), JobDataMapSupport.toTabularData(trigger.getJobDataMap()), trigger.getCalendarName(), ((OperableTrigger)trigger).getFireInstanceId(), trigger.getMisfireInstruction(), trigger.getPriority(), trigger.getStartTime(), trigger.getEndTime(), trigger.getNextFireTime(), trigger.getPreviousFireTime(), trigger.getFinalFireTime()});
      } catch (OpenDataException var2) {
         throw new RuntimeException(var2);
      }
   }

   public static TabularData toTabularData(List<? extends SimpleTrigger> triggers) {
      TabularData tData = new TabularDataSupport(TABULAR_TYPE);
      if (triggers != null) {
         ArrayList<CompositeData> list = new ArrayList();
         Iterator i$ = triggers.iterator();

         while(i$.hasNext()) {
            SimpleTrigger trigger = (SimpleTrigger)i$.next();
            list.add(toCompositeData(trigger));
         }

         tData.putAll((CompositeData[])list.toArray(new CompositeData[list.size()]));
      }

      return tData;
   }

   public static OperableTrigger newTrigger(CompositeData cData) throws ParseException {
      SimpleTriggerImpl result = new SimpleTriggerImpl();
      result.setRepeatCount((Integer)cData.get("repeatCount"));
      result.setRepeatInterval((Long)cData.get("repeatInterval"));
      result.setTimesTriggered((Integer)cData.get("timesTriggered"));
      TriggerSupport.initializeTrigger(result, (CompositeData)cData);
      return result;
   }

   public static OperableTrigger newTrigger(Map<String, Object> attrMap) throws ParseException {
      SimpleTriggerImpl result = new SimpleTriggerImpl();
      if (attrMap.containsKey("repeatCount")) {
         result.setRepeatCount((Integer)attrMap.get("repeatCount"));
      }

      if (attrMap.containsKey("repeatInterval")) {
         result.setRepeatInterval((Long)attrMap.get("repeatInterval"));
      }

      if (attrMap.containsKey("timesTriggered")) {
         result.setTimesTriggered((Integer)attrMap.get("timesTriggered"));
      }

      TriggerSupport.initializeTrigger(result, (Map)attrMap);
      return result;
   }

   static {
      ITEM_TYPES = new OpenType[]{SimpleType.INTEGER, SimpleType.LONG, SimpleType.INTEGER};

      try {
         COMPOSITE_TYPE = new CompositeType("SimpleTrigger", "SimpleTrigger Details", getItemNames(), getItemDescriptions(), getItemTypes());
         TABULAR_TYPE = new TabularType("SimpleTrigger collection", "SimpleTrigger collection", COMPOSITE_TYPE, getItemNames());
      } catch (OpenDataException var1) {
         throw new RuntimeException(var1);
      }
   }
}
