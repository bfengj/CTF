package org.quartz.ee.servlet;

import javax.servlet.ServletContext;
import javax.servlet.ServletContextEvent;
import javax.servlet.ServletContextListener;
import org.quartz.Scheduler;
import org.quartz.SchedulerException;
import org.quartz.impl.StdSchedulerFactory;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

public class QuartzInitializerListener implements ServletContextListener {
   public static final String QUARTZ_FACTORY_KEY = "org.quartz.impl.StdSchedulerFactory.KEY";
   private boolean performShutdown = true;
   private boolean waitOnShutdown = false;
   private Scheduler scheduler = null;
   private final Logger log = LoggerFactory.getLogger(this.getClass());

   public void contextInitialized(ServletContextEvent sce) {
      this.log.info("Quartz Initializer Servlet loaded, initializing Scheduler...");
      ServletContext servletContext = sce.getServletContext();

      try {
         String configFile = servletContext.getInitParameter("quartz:config-file");
         if (configFile == null) {
            configFile = servletContext.getInitParameter("config-file");
         }

         String shutdownPref = servletContext.getInitParameter("quartz:shutdown-on-unload");
         if (shutdownPref == null) {
            shutdownPref = servletContext.getInitParameter("shutdown-on-unload");
         }

         if (shutdownPref != null) {
            this.performShutdown = Boolean.valueOf(shutdownPref);
         }

         String shutdownWaitPref = servletContext.getInitParameter("quartz:wait-on-shutdown");
         if (shutdownPref != null) {
            this.waitOnShutdown = Boolean.valueOf(shutdownWaitPref);
         }

         StdSchedulerFactory factory = this.getSchedulerFactory(configFile);
         this.scheduler = factory.getScheduler();
         String startOnLoad = servletContext.getInitParameter("quartz:start-on-load");
         if (startOnLoad == null) {
            startOnLoad = servletContext.getInitParameter("start-scheduler-on-load");
         }

         int startDelay = 0;
         String startDelayS = servletContext.getInitParameter("quartz:start-delay-seconds");
         if (startDelayS == null) {
            startDelayS = servletContext.getInitParameter("start-delay-seconds");
         }

         try {
            if (startDelayS != null && startDelayS.trim().length() > 0) {
               startDelay = Integer.parseInt(startDelayS);
            }
         } catch (Exception var12) {
            this.log.error("Cannot parse value of 'start-delay-seconds' to an integer: " + startDelayS + ", defaulting to 5 seconds.");
            startDelay = 5;
         }

         if (startOnLoad != null && !Boolean.valueOf(startOnLoad)) {
            this.log.info("Scheduler has not been started. Use scheduler.start()");
         } else if (startDelay <= 0) {
            this.scheduler.start();
            this.log.info("Scheduler has been started...");
         } else {
            this.scheduler.startDelayed(startDelay);
            this.log.info("Scheduler will start in " + startDelay + " seconds.");
         }

         String factoryKey = servletContext.getInitParameter("quartz:servlet-context-factory-key");
         if (factoryKey == null) {
            factoryKey = servletContext.getInitParameter("servlet-context-factory-key");
         }

         if (factoryKey == null) {
            factoryKey = "org.quartz.impl.StdSchedulerFactory.KEY";
         }

         this.log.info("Storing the Quartz Scheduler Factory in the servlet context at key: " + factoryKey);
         servletContext.setAttribute(factoryKey, factory);
         String servletCtxtKey = servletContext.getInitParameter("quartz:scheduler-context-servlet-context-key");
         if (servletCtxtKey == null) {
            servletCtxtKey = servletContext.getInitParameter("scheduler-context-servlet-context-key");
         }

         if (servletCtxtKey != null) {
            this.log.info("Storing the ServletContext in the scheduler context at key: " + servletCtxtKey);
            this.scheduler.getContext().put(servletCtxtKey, servletContext);
         }
      } catch (Exception var13) {
         this.log.error("Quartz Scheduler failed to initialize: " + var13.toString());
         var13.printStackTrace();
      }

   }

   protected StdSchedulerFactory getSchedulerFactory(String configFile) throws SchedulerException {
      StdSchedulerFactory factory;
      if (configFile != null) {
         factory = new StdSchedulerFactory(configFile);
      } else {
         factory = new StdSchedulerFactory();
      }

      return factory;
   }

   public void contextDestroyed(ServletContextEvent sce) {
      if (this.performShutdown) {
         try {
            if (this.scheduler != null) {
               this.scheduler.shutdown(this.waitOnShutdown);
            }
         } catch (Exception var3) {
            this.log.error("Quartz Scheduler failed to shutdown cleanly: " + var3.toString());
            var3.printStackTrace();
         }

         this.log.info("Quartz Scheduler successful shutdown.");
      }
   }
}
