package org.quartz.impl.jdbcjobstore;

import java.util.HashSet;
import java.util.Iterator;
import java.util.Set;
import org.quartz.DailyTimeIntervalScheduleBuilder;
import org.quartz.DailyTimeIntervalTrigger;
import org.quartz.DateBuilder;
import org.quartz.TimeOfDay;
import org.quartz.impl.triggers.DailyTimeIntervalTriggerImpl;
import org.quartz.spi.OperableTrigger;

public class DailyTimeIntervalTriggerPersistenceDelegate extends SimplePropertiesTriggerPersistenceDelegateSupport {
   public boolean canHandleTriggerType(OperableTrigger trigger) {
      return trigger instanceof DailyTimeIntervalTrigger && !((DailyTimeIntervalTriggerImpl)trigger).hasAdditionalProperties();
   }

   public String getHandledTriggerTypeDiscriminator() {
      return "DAILY_I";
   }

   protected SimplePropertiesTriggerProperties getTriggerProperties(OperableTrigger trigger) {
      DailyTimeIntervalTriggerImpl dailyTrigger = (DailyTimeIntervalTriggerImpl)trigger;
      SimplePropertiesTriggerProperties props = new SimplePropertiesTriggerProperties();
      props.setInt1(dailyTrigger.getRepeatInterval());
      props.setString1(dailyTrigger.getRepeatIntervalUnit().name());
      props.setInt2(dailyTrigger.getTimesTriggered());
      Set<Integer> days = dailyTrigger.getDaysOfWeek();
      String daysStr = this.join(days, ",");
      props.setString2(daysStr);
      StringBuilder timeOfDayBuffer = new StringBuilder();
      TimeOfDay startTimeOfDay = dailyTrigger.getStartTimeOfDay();
      if (startTimeOfDay != null) {
         timeOfDayBuffer.append(startTimeOfDay.getHour()).append(",");
         timeOfDayBuffer.append(startTimeOfDay.getMinute()).append(",");
         timeOfDayBuffer.append(startTimeOfDay.getSecond()).append(",");
      } else {
         timeOfDayBuffer.append(",,,");
      }

      TimeOfDay endTimeOfDay = dailyTrigger.getEndTimeOfDay();
      if (endTimeOfDay != null) {
         timeOfDayBuffer.append(endTimeOfDay.getHour()).append(",");
         timeOfDayBuffer.append(endTimeOfDay.getMinute()).append(",");
         timeOfDayBuffer.append(endTimeOfDay.getSecond());
      } else {
         timeOfDayBuffer.append(",,,");
      }

      props.setString3(timeOfDayBuffer.toString());
      props.setLong1((long)dailyTrigger.getRepeatCount());
      return props;
   }

   private String join(Set<Integer> days, String sep) {
      StringBuilder sb = new StringBuilder();
      if (days != null && days.size() > 0) {
         Iterator<Integer> itr = days.iterator();
         sb.append(itr.next());

         while(itr.hasNext()) {
            sb.append(sep).append(itr.next());
         }

         return sb.toString();
      } else {
         return "";
      }
   }

   protected TriggerPersistenceDelegate.TriggerPropertyBundle getTriggerPropertyBundle(SimplePropertiesTriggerProperties props) {
      int repeatCount = (int)props.getLong1();
      int interval = props.getInt1();
      String intervalUnitStr = props.getString1();
      String daysOfWeekStr = props.getString2();
      String timeOfDayStr = props.getString3();
      DateBuilder.IntervalUnit intervalUnit = DateBuilder.IntervalUnit.valueOf(intervalUnitStr);
      DailyTimeIntervalScheduleBuilder scheduleBuilder = DailyTimeIntervalScheduleBuilder.dailyTimeIntervalSchedule().withInterval(interval, intervalUnit).withRepeatCount(repeatCount);
      String[] statePropertyNames;
      int hour;
      int min;
      if (daysOfWeekStr != null) {
         Set<Integer> daysOfWeek = new HashSet();
         statePropertyNames = daysOfWeekStr.split(",");
         if (statePropertyNames.length > 0) {
            String[] arr$ = statePropertyNames;
            hour = statePropertyNames.length;

            for(min = 0; min < hour; ++min) {
               String num = arr$[min];
               daysOfWeek.add(Integer.parseInt(num));
            }

            scheduleBuilder.onDaysOfTheWeek((Set)daysOfWeek);
         }
      } else {
         scheduleBuilder.onDaysOfTheWeek(DailyTimeIntervalScheduleBuilder.ALL_DAYS_OF_THE_WEEK);
      }

      if (timeOfDayStr != null) {
         String[] nums = timeOfDayStr.split(",");
         TimeOfDay startTimeOfDay;
         if (nums.length >= 3) {
            int hour = Integer.parseInt(nums[0]);
            hour = Integer.parseInt(nums[1]);
            min = Integer.parseInt(nums[2]);
            startTimeOfDay = new TimeOfDay(hour, hour, min);
         } else {
            startTimeOfDay = TimeOfDay.hourMinuteAndSecondOfDay(0, 0, 0);
         }

         scheduleBuilder.startingDailyAt(startTimeOfDay);
         TimeOfDay endTimeOfDay;
         if (nums.length >= 6) {
            hour = Integer.parseInt(nums[3]);
            min = Integer.parseInt(nums[4]);
            int sec = Integer.parseInt(nums[5]);
            endTimeOfDay = new TimeOfDay(hour, min, sec);
         } else {
            endTimeOfDay = TimeOfDay.hourMinuteAndSecondOfDay(23, 59, 59);
         }

         scheduleBuilder.endingDailyAt(endTimeOfDay);
      } else {
         scheduleBuilder.startingDailyAt(TimeOfDay.hourMinuteAndSecondOfDay(0, 0, 0));
         scheduleBuilder.endingDailyAt(TimeOfDay.hourMinuteAndSecondOfDay(23, 59, 59));
      }

      int timesTriggered = props.getInt2();
      statePropertyNames = new String[]{"timesTriggered"};
      Object[] statePropertyValues = new Object[]{timesTriggered};
      return new TriggerPersistenceDelegate.TriggerPropertyBundle(scheduleBuilder, statePropertyNames, statePropertyValues);
   }
}
