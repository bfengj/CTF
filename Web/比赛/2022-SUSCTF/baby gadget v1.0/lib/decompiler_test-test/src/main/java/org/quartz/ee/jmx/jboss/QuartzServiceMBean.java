package org.quartz.ee.jmx.jboss;

import org.jboss.system.ServiceMBean;

public interface QuartzServiceMBean extends ServiceMBean {
   void setJndiName(String var1) throws Exception;

   String getJndiName();

   void setProperties(String var1);

   void setPropertiesFile(String var1);

   void setStartScheduler(boolean var1);
}
