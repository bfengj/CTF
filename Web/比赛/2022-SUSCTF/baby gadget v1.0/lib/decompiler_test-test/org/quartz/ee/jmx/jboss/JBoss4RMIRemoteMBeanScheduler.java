package org.quartz.ee.jmx.jboss;

import java.util.Arrays;
import java.util.Properties;
import javax.management.AttributeList;
import javax.management.MBeanServerConnection;
import javax.naming.InitialContext;
import javax.naming.NamingException;
import org.quartz.SchedulerException;
import org.quartz.impl.RemoteMBeanScheduler;

public class JBoss4RMIRemoteMBeanScheduler extends RemoteMBeanScheduler {
   private static final String DEFAULT_PROVIDER_URL = "jnp://localhost:1099";
   private static final String RMI_ADAPTOR_JNDI_NAME = "jmx/rmi/RMIAdaptor";
   private MBeanServerConnection server = null;
   private String providerURL = "jnp://localhost:1099";

   public JBoss4RMIRemoteMBeanScheduler() throws SchedulerException {
   }

   public void setProviderURL(String providerURL) {
      this.providerURL = providerURL;
   }

   public void initialize() throws SchedulerException {
      InitialContext ctx = null;

      try {
         ctx = new InitialContext(this.getContextProperties());
         this.server = (MBeanServerConnection)ctx.lookup("jmx/rmi/RMIAdaptor");
      } catch (Exception var10) {
         throw new SchedulerException("Failed to lookup JBoss JMX RMI Adaptor.", var10);
      } finally {
         if (ctx != null) {
            try {
               ctx.close();
            } catch (NamingException var9) {
            }
         }

      }

   }

   protected Properties getContextProperties() {
      Properties props = new Properties();
      props.put("java.naming.factory.initial", "org.jnp.interfaces.NamingContextFactory");
      props.put("java.naming.factory.url.pkgs", "org.jboss.naming:org.jnp.interfaces");
      props.put("java.naming.provider.url", this.providerURL);
      return props;
   }

   protected Object getAttribute(String attribute) throws SchedulerException {
      try {
         return this.server.getAttribute(this.getSchedulerObjectName(), attribute);
      } catch (Exception var3) {
         throw new SchedulerException("Failed to get Scheduler MBean attribute: " + attribute, var3);
      }
   }

   protected AttributeList getAttributes(String[] attributes) throws SchedulerException {
      try {
         return this.server.getAttributes(this.getSchedulerObjectName(), attributes);
      } catch (Exception var3) {
         throw new SchedulerException("Failed to get Scheduler MBean attributes: " + Arrays.asList(attributes), var3);
      }
   }

   protected Object invoke(String operationName, Object[] params, String[] signature) throws SchedulerException {
      try {
         return this.server.invoke(this.getSchedulerObjectName(), operationName, params, signature);
      } catch (Exception var5) {
         throw new SchedulerException("Failed to invoke Scheduler MBean operation: " + operationName, var5);
      }
   }
}
