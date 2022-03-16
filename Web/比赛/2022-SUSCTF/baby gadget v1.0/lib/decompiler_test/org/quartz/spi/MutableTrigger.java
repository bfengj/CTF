package org.quartz.spi;

import java.util.Date;
import org.quartz.JobDataMap;
import org.quartz.JobKey;
import org.quartz.Trigger;
import org.quartz.TriggerKey;

public interface MutableTrigger extends Trigger {
   void setKey(TriggerKey var1);

   void setJobKey(JobKey var1);

   void setDescription(String var1);

   void setCalendarName(String var1);

   void setJobDataMap(JobDataMap var1);

   void setPriority(int var1);

   void setStartTime(Date var1);

   void setEndTime(Date var1);

   void setMisfireInstruction(int var1);

   Object clone();
}
