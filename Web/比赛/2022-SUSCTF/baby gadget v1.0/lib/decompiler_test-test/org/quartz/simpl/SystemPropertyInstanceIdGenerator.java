package org.quartz.simpl;

import org.quartz.SchedulerException;
import org.quartz.spi.InstanceIdGenerator;

public class SystemPropertyInstanceIdGenerator implements InstanceIdGenerator {
   public static final String SYSTEM_PROPERTY = "org.quartz.scheduler.instanceId";
   private String prepend = null;
   private String postpend = null;
   private String systemPropertyName = "org.quartz.scheduler.instanceId";

   public String generateInstanceId() throws SchedulerException {
      String property = System.getProperty(this.getSystemPropertyName());
      if (property == null) {
         throw new SchedulerException("No value for 'org.quartz.scheduler.instanceId' system property found, please configure your environment accordingly!");
      } else {
         if (this.getPrepend() != null) {
            property = this.getPrepend() + property;
         }

         if (this.getPostpend() != null) {
            property = property + this.getPostpend();
         }

         return property;
      }
   }

   public String getPrepend() {
      return this.prepend;
   }

   public void setPrepend(String prepend) {
      this.prepend = prepend == null ? null : prepend.trim();
   }

   public String getPostpend() {
      return this.postpend;
   }

   public void setPostpend(String postpend) {
      this.postpend = postpend == null ? null : postpend.trim();
   }

   public String getSystemPropertyName() {
      return this.systemPropertyName;
   }

   public void setSystemPropertyName(String systemPropertyName) {
      this.systemPropertyName = systemPropertyName == null ? "org.quartz.scheduler.instanceId" : systemPropertyName.trim();
   }
}
