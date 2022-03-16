package org.quartz;

import java.util.Collections;
import java.util.Date;
import java.util.LinkedList;
import java.util.List;
import org.quartz.spi.OperableTrigger;

public class TriggerUtils {
   private TriggerUtils() {
   }

   public static List<Date> computeFireTimes(OperableTrigger trigg, Calendar cal, int numTimes) {
      LinkedList<Date> lst = new LinkedList();
      OperableTrigger t = (OperableTrigger)trigg.clone();
      if (t.getNextFireTime() == null) {
         t.computeFirstFireTime(cal);
      }

      for(int i = 0; i < numTimes; ++i) {
         Date d = t.getNextFireTime();
         if (d == null) {
            break;
         }

         lst.add(d);
         t.triggered(cal);
      }

      return Collections.unmodifiableList(lst);
   }

   public static Date computeEndTimeToAllowParticularNumberOfFirings(OperableTrigger trigg, Calendar cal, int numTimes) {
      OperableTrigger t = (OperableTrigger)trigg.clone();
      if (t.getNextFireTime() == null) {
         t.computeFirstFireTime(cal);
      }

      int c = 0;
      Date endTime = null;

      for(int i = 0; i < numTimes; ++i) {
         Date d = t.getNextFireTime();
         if (d == null) {
            break;
         }

         ++c;
         t.triggered(cal);
         if (c == numTimes) {
            endTime = d;
         }
      }

      if (endTime == null) {
         return null;
      } else {
         endTime = new Date(endTime.getTime() + 1000L);
         return endTime;
      }
   }

   public static List<Date> computeFireTimesBetween(OperableTrigger trigg, Calendar cal, Date from, Date to) {
      LinkedList<Date> lst = new LinkedList();
      OperableTrigger t = (OperableTrigger)trigg.clone();
      if (t.getNextFireTime() == null) {
         t.setStartTime(from);
         t.setEndTime(to);
         t.computeFirstFireTime(cal);
      }

      while(true) {
         Date d = t.getNextFireTime();
         if (d == null) {
            break;
         }

         if (d.before(from)) {
            t.triggered(cal);
         } else {
            if (d.after(to)) {
               break;
            }

            lst.add(d);
            t.triggered(cal);
         }
      }

      return Collections.unmodifiableList(lst);
   }
}
