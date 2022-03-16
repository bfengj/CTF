package org.quartz.ee.jmx.jboss;

import java.io.ByteArrayInputStream;
import java.io.ByteArrayOutputStream;
import java.io.File;
import java.io.IOException;
import java.util.Properties;
import javax.naming.InitialContext;
import javax.naming.Name;
import javax.naming.NamingException;
import org.jboss.naming.NonSerializableFactory;
import org.jboss.system.ServiceMBeanSupport;
import org.quartz.Scheduler;
import org.quartz.SchedulerConfigException;
import org.quartz.SchedulerException;
import org.quartz.impl.StdSchedulerFactory;

public class QuartzService extends ServiceMBeanSupport implements QuartzServiceMBean {
   private Properties properties = new Properties();
   private StdSchedulerFactory schedulerFactory;
   private String jndiName = "Quartz";
   private String propertiesFile = "";
   private boolean error = false;
   private boolean useProperties = false;
   private boolean usePropertiesFile = false;
   private boolean startScheduler = true;

   public void setJndiName(String jndiName) throws Exception {
      String oldName = this.jndiName;
      this.jndiName = jndiName;
      if (super.getState() == 3) {
         this.unbind(oldName);

         try {
            this.rebind();
         } catch (NamingException var4) {
            this.log.error("Failed to rebind Scheduler", var4);
            throw new SchedulerConfigException("Failed to rebind Scheduler - ", var4);
         }
      }

   }

   public String getJndiName() {
      return this.jndiName;
   }

   public String getName() {
      return "QuartzService(" + this.jndiName + ")";
   }

   public void setProperties(String properties) {
      if (this.usePropertiesFile) {
         this.log.error("Must specify only one of 'Properties' or 'PropertiesFile'");
         this.error = true;
      } else {
         this.useProperties = true;

         try {
            properties = properties.replace(File.separator, "/");
            ByteArrayInputStream bais = new ByteArrayInputStream(properties.getBytes());
            this.properties = new Properties();
            this.properties.load(bais);
         } catch (IOException var3) {
         }

      }
   }

   public String getProperties() {
      try {
         ByteArrayOutputStream baos = new ByteArrayOutputStream();
         this.properties.store(baos, "");
         return new String(baos.toByteArray());
      } catch (IOException var2) {
         return "";
      }
   }

   public void setPropertiesFile(String propertiesFile) {
      if (this.useProperties) {
         this.log.error("Must specify only one of 'Properties' or 'PropertiesFile'");
         this.error = true;
      } else {
         this.usePropertiesFile = true;
         this.propertiesFile = propertiesFile;
      }
   }

   public String getPropertiesFile() {
      return this.propertiesFile;
   }

   public void setStartScheduler(boolean startScheduler) {
      this.startScheduler = startScheduler;
   }

   public boolean getStartScheduler() {
      return this.startScheduler;
   }

   public void createService() throws Exception {
      this.log.info("Create QuartzService(" + this.jndiName + ")...");
      if (this.error) {
         this.log.error("Must specify only one of 'Properties' or 'PropertiesFile'");
         throw new Exception("Must specify only one of 'Properties' or 'PropertiesFile'");
      } else {
         this.schedulerFactory = new StdSchedulerFactory();

         try {
            if (this.useProperties) {
               this.schedulerFactory.initialize(this.properties);
            }

            if (this.usePropertiesFile) {
               this.schedulerFactory.initialize(this.propertiesFile);
            }
         } catch (Exception var2) {
            this.log.error("Failed to initialize Scheduler", var2);
            throw new SchedulerConfigException("Failed to initialize Scheduler - ", var2);
         }

         this.log.info("QuartzService(" + this.jndiName + ") created.");
      }
   }

   public void destroyService() throws Exception {
      this.log.info("Destroy QuartzService(" + this.jndiName + ")...");
      this.schedulerFactory = null;
      this.log.info("QuartzService(" + this.jndiName + ") destroyed.");
   }

   public void startService() throws Exception {
      this.log.info("Start QuartzService(" + this.jndiName + ")...");

      try {
         this.rebind();
      } catch (NamingException var3) {
         this.log.error("Failed to rebind Scheduler", var3);
         throw new SchedulerConfigException("Failed to rebind Scheduler - ", var3);
      }

      try {
         Scheduler scheduler = this.schedulerFactory.getScheduler();
         if (this.startScheduler) {
            scheduler.start();
         } else {
            this.log.info("Skipping starting the scheduler (will not run jobs).");
         }
      } catch (Exception var2) {
         this.log.error("Failed to start Scheduler", var2);
         throw new SchedulerConfigException("Failed to start Scheduler - ", var2);
      }

      this.log.info("QuartzService(" + this.jndiName + ") started.");
   }

   public void stopService() throws Exception {
      this.log.info("Stop QuartzService(" + this.jndiName + ")...");

      try {
         Scheduler scheduler = this.schedulerFactory.getScheduler();
         scheduler.shutdown();
      } catch (Exception var2) {
         this.log.error("Failed to shutdown Scheduler", var2);
         throw new SchedulerConfigException("Failed to shutdown Scheduler - ", var2);
      }

      this.unbind(this.jndiName);
      this.log.info("QuartzService(" + this.jndiName + ") stopped.");
   }

   private void rebind() throws NamingException, SchedulerException {
      InitialContext rootCtx = null;

      try {
         rootCtx = new InitialContext();
         Name fullName = rootCtx.getNameParser("").parse(this.jndiName);
         Scheduler scheduler = this.schedulerFactory.getScheduler();
         NonSerializableFactory.rebind(fullName, scheduler, true);
      } finally {
         if (rootCtx != null) {
            try {
               rootCtx.close();
            } catch (NamingException var9) {
            }
         }

      }

   }

   private void unbind(String name) {
      InitialContext rootCtx = null;

      try {
         rootCtx = new InitialContext();
         rootCtx.unbind(name);
         NonSerializableFactory.unbind(name);
      } catch (NamingException var12) {
         this.log.warn("Failed to unbind scheduler with jndiName: " + name, var12);
      } finally {
         if (rootCtx != null) {
            try {
               rootCtx.close();
            } catch (NamingException var11) {
            }
         }

      }

   }
}
