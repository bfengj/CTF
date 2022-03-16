package org.quartz.ee.servlet;

import java.io.IOException;
import javax.servlet.ServletConfig;
import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import org.quartz.Scheduler;
import org.quartz.SchedulerException;
import org.quartz.impl.StdSchedulerFactory;

public class QuartzInitializerServlet extends HttpServlet {
   private static final long serialVersionUID = 1L;
   public static final String QUARTZ_FACTORY_KEY = "org.quartz.impl.StdSchedulerFactory.KEY";
   private boolean performShutdown = true;
   private boolean waitOnShutdown = false;
   private transient Scheduler scheduler = null;

   public void init(ServletConfig cfg) throws ServletException {
      super.init(cfg);
      this.log("Quartz Initializer Servlet loaded, initializing Scheduler...");

      try {
         String configFile = cfg.getInitParameter("config-file");
         String shutdownPref = cfg.getInitParameter("shutdown-on-unload");
         if (shutdownPref != null) {
            this.performShutdown = Boolean.valueOf(shutdownPref);
         }

         String shutdownWaitPref = cfg.getInitParameter("wait-on-shutdown");
         if (shutdownPref != null) {
            this.waitOnShutdown = Boolean.valueOf(shutdownWaitPref);
         }

         StdSchedulerFactory factory = this.getSchedulerFactory(configFile);
         this.scheduler = factory.getScheduler();
         String startOnLoad = cfg.getInitParameter("start-scheduler-on-load");
         int startDelay = 0;
         String startDelayS = cfg.getInitParameter("start-delay-seconds");

         try {
            if (startDelayS != null && startDelayS.trim().length() > 0) {
               startDelay = Integer.parseInt(startDelayS);
            }
         } catch (Exception var11) {
            this.log("Cannot parse value of 'start-delay-seconds' to an integer: " + startDelayS + ", defaulting to 5 seconds.", var11);
            startDelay = 5;
         }

         if (startOnLoad != null && !Boolean.valueOf(startOnLoad)) {
            this.log("Scheduler has not been started. Use scheduler.start()");
         } else if (startDelay <= 0) {
            this.scheduler.start();
            this.log("Scheduler has been started...");
         } else {
            this.scheduler.startDelayed(startDelay);
            this.log("Scheduler will start in " + startDelay + " seconds.");
         }

         String factoryKey = cfg.getInitParameter("servlet-context-factory-key");
         if (factoryKey == null) {
            factoryKey = "org.quartz.impl.StdSchedulerFactory.KEY";
         }

         this.log("Storing the Quartz Scheduler Factory in the servlet context at key: " + factoryKey);
         cfg.getServletContext().setAttribute(factoryKey, factory);
         String servletCtxtKey = cfg.getInitParameter("scheduler-context-servlet-context-key");
         if (servletCtxtKey != null) {
            this.log("Storing the ServletContext in the scheduler context at key: " + servletCtxtKey);
            this.scheduler.getContext().put(servletCtxtKey, cfg.getServletContext());
         }

      } catch (Exception var12) {
         this.log("Quartz Scheduler failed to initialize: " + var12.toString());
         throw new ServletException(var12);
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

   public void destroy() {
      if (this.performShutdown) {
         try {
            if (this.scheduler != null) {
               this.scheduler.shutdown(this.waitOnShutdown);
            }
         } catch (Exception var2) {
            this.log("Quartz Scheduler failed to shutdown cleanly: " + var2.toString());
            var2.printStackTrace();
         }

         this.log("Quartz Scheduler successful shutdown.");
      }
   }

   public void doPost(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
      response.sendError(403);
   }

   public void doGet(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
      response.sendError(403);
   }
}
