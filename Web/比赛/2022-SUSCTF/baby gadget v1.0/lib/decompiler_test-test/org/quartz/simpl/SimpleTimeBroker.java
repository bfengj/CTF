package org.quartz.simpl;

import java.util.Date;
import org.quartz.SchedulerConfigException;
import org.quartz.spi.TimeBroker;

public class SimpleTimeBroker implements TimeBroker {
   public Date getCurrentTime() {
      return new Date();
   }

   public void initialize() throws SchedulerConfigException {
   }

   public void shutdown() {
   }
}
