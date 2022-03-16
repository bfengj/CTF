package org.quartz;

import java.io.Serializable;

public interface Calendar extends Serializable, Cloneable {
   int MONTH = 0;

   void setBaseCalendar(Calendar var1);

   Calendar getBaseCalendar();

   boolean isTimeIncluded(long var1);

   long getNextIncludedTime(long var1);

   String getDescription();

   void setDescription(String var1);

   Object clone();
}
