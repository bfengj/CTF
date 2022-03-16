package org.quartz.simpl;

import java.net.InetAddress;
import org.quartz.SchedulerException;
import org.quartz.spi.InstanceIdGenerator;

public class HostnameInstanceIdGenerator implements InstanceIdGenerator {
   public String generateInstanceId() throws SchedulerException {
      try {
         return InetAddress.getLocalHost().getHostName();
      } catch (Exception var2) {
         throw new SchedulerException("Couldn't get host name!", var2);
      }
   }
}
