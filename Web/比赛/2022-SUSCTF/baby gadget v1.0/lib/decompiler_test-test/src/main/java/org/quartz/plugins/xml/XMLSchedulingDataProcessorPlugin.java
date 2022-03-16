package org.quartz.plugins.xml;

import java.io.File;
import java.io.FileInputStream;
import java.io.FileNotFoundException;
import java.io.IOException;
import java.io.InputStream;
import java.io.UnsupportedEncodingException;
import java.net.URL;
import java.net.URLDecoder;
import java.util.HashSet;
import java.util.Iterator;
import java.util.LinkedHashMap;
import java.util.Map;
import java.util.Set;
import java.util.StringTokenizer;
import javax.transaction.UserTransaction;
import org.quartz.JobBuilder;
import org.quartz.JobDetail;
import org.quartz.Scheduler;
import org.quartz.SchedulerException;
import org.quartz.SimpleScheduleBuilder;
import org.quartz.SimpleTrigger;
import org.quartz.TriggerBuilder;
import org.quartz.TriggerKey;
import org.quartz.jobs.FileScanJob;
import org.quartz.jobs.FileScanListener;
import org.quartz.plugins.SchedulerPluginWithUserTransactionSupport;
import org.quartz.spi.ClassLoadHelper;
import org.quartz.xml.XMLSchedulingDataProcessor;

public class XMLSchedulingDataProcessorPlugin extends SchedulerPluginWithUserTransactionSupport implements FileScanListener {
   private static final int MAX_JOB_TRIGGER_NAME_LEN = 80;
   private static final String JOB_INITIALIZATION_PLUGIN_NAME = "JobSchedulingDataLoaderPlugin";
   private static final String FILE_NAME_DELIMITERS = ",";
   private boolean failOnFileNotFound = true;
   private String fileNames = "quartz_data.xml";
   private Map<String, XMLSchedulingDataProcessorPlugin.JobFile> jobFiles = new LinkedHashMap();
   private long scanInterval = 0L;
   boolean started = false;
   protected ClassLoadHelper classLoadHelper = null;
   private Set<String> jobTriggerNameSet = new HashSet();

   public String getFileNames() {
      return this.fileNames;
   }

   public void setFileNames(String fileNames) {
      this.fileNames = fileNames;
   }

   public long getScanInterval() {
      return this.scanInterval / 1000L;
   }

   public void setScanInterval(long scanInterval) {
      this.scanInterval = scanInterval * 1000L;
   }

   public boolean isFailOnFileNotFound() {
      return this.failOnFileNotFound;
   }

   public void setFailOnFileNotFound(boolean failOnFileNotFound) {
      this.failOnFileNotFound = failOnFileNotFound;
   }

   public void initialize(String name, Scheduler scheduler, ClassLoadHelper schedulerFactoryClassLoadHelper) throws SchedulerException {
      super.initialize(name, scheduler);
      this.classLoadHelper = schedulerFactoryClassLoadHelper;
      this.getLog().info("Registering Quartz Job Initialization Plug-in.");
      StringTokenizer stok = new StringTokenizer(this.fileNames, ",");

      while(stok.hasMoreTokens()) {
         String fileName = stok.nextToken();
         XMLSchedulingDataProcessorPlugin.JobFile jobFile = new XMLSchedulingDataProcessorPlugin.JobFile(fileName);
         this.jobFiles.put(fileName, jobFile);
      }

   }

   public void start(UserTransaction userTransaction) {
      try {
         if (!this.jobFiles.isEmpty()) {
            if (this.scanInterval > 0L) {
               this.getScheduler().getContext().put("JobSchedulingDataLoaderPlugin_" + this.getName(), this);
            }

            XMLSchedulingDataProcessorPlugin.JobFile jobFile;
            for(Iterator iterator = this.jobFiles.values().iterator(); iterator.hasNext(); this.processFile(jobFile)) {
               jobFile = (XMLSchedulingDataProcessorPlugin.JobFile)iterator.next();
               if (this.scanInterval > 0L) {
                  String jobTriggerName = this.buildJobTriggerName(jobFile.getFileBasename());
                  TriggerKey tKey = new TriggerKey(jobTriggerName, "JobSchedulingDataLoaderPlugin");
                  this.getScheduler().unscheduleJob(tKey);
                  JobDetail job = JobBuilder.newJob().withIdentity(jobTriggerName, "JobSchedulingDataLoaderPlugin").ofType(FileScanJob.class).usingJobData("FILE_NAME", jobFile.getFileName()).usingJobData("FILE_SCAN_LISTENER_NAME", "JobSchedulingDataLoaderPlugin_" + this.getName()).build();
                  SimpleTrigger trig = (SimpleTrigger)TriggerBuilder.newTrigger().withIdentity(tKey).withSchedule(SimpleScheduleBuilder.simpleSchedule().repeatForever().withIntervalInMilliseconds(this.scanInterval)).forJob(job).build();
                  this.getScheduler().scheduleJob(job, trig);
                  this.getLog().debug("Scheduled file scan job for data file: {}, at interval: {}", jobFile.getFileName(), this.scanInterval);
               }
            }
         }
      } catch (SchedulerException var11) {
         this.getLog().error("Error starting background-task for watching jobs file.", var11);
      } finally {
         this.started = true;
      }

   }

   private String buildJobTriggerName(String fileBasename) {
      String jobTriggerName = "JobSchedulingDataLoaderPlugin_" + this.getName() + '_' + fileBasename.replace('.', '_');
      if (jobTriggerName.length() > 80) {
         jobTriggerName = jobTriggerName.substring(0, 80);
      }

      String numericSuffix;
      for(int currentIndex = 1; !this.jobTriggerNameSet.add(jobTriggerName); jobTriggerName = jobTriggerName + numericSuffix) {
         if (currentIndex > 1) {
            jobTriggerName = jobTriggerName.substring(0, jobTriggerName.lastIndexOf(95));
         }

         numericSuffix = "_" + currentIndex++;
         if (jobTriggerName.length() > 80 - numericSuffix.length()) {
            jobTriggerName = jobTriggerName.substring(0, 80 - numericSuffix.length());
         }
      }

      return jobTriggerName;
   }

   public void shutdown() {
   }

   private void processFile(XMLSchedulingDataProcessorPlugin.JobFile jobFile) {
      if (jobFile != null && jobFile.getFileFound()) {
         try {
            XMLSchedulingDataProcessor processor = new XMLSchedulingDataProcessor(this.classLoadHelper);
            processor.addJobGroupToNeverDelete("JobSchedulingDataLoaderPlugin");
            processor.addTriggerGroupToNeverDelete("JobSchedulingDataLoaderPlugin");
            processor.processFileAndScheduleJobs(jobFile.getFileName(), jobFile.getFileName(), this.getScheduler());
         } catch (Exception var3) {
            this.getLog().error("Error scheduling jobs: " + var3.getMessage(), var3);
         }

      }
   }

   public void processFile(String filePath) {
      this.processFile((XMLSchedulingDataProcessorPlugin.JobFile)this.jobFiles.get(filePath));
   }

   public void fileUpdated(String fileName) {
      if (this.started) {
         this.processFile(fileName);
      }

   }

   class JobFile {
      private String fileName;
      private String filePath;
      private String fileBasename;
      private boolean fileFound;

      protected JobFile(String fileName) throws SchedulerException {
         this.fileName = fileName;
         this.initialize();
      }

      protected String getFileName() {
         return this.fileName;
      }

      protected boolean getFileFound() {
         return this.fileFound;
      }

      protected String getFilePath() {
         return this.filePath;
      }

      protected String getFileBasename() {
         return this.fileBasename;
      }

      private void initialize() throws SchedulerException {
         Object f = null;

         try {
            String furl = null;
            File file = new File(this.getFileName());
            if (!file.exists()) {
               URL url = XMLSchedulingDataProcessorPlugin.this.classLoadHelper.getResource(this.getFileName());
               if (url != null) {
                  try {
                     furl = URLDecoder.decode(url.getPath(), "UTF-8");
                  } catch (UnsupportedEncodingException var17) {
                     furl = url.getPath();
                  }

                  file = new File(furl);

                  try {
                     f = url.openStream();
                  } catch (IOException var16) {
                  }
               }
            } else {
               try {
                  f = new FileInputStream(file);
               } catch (FileNotFoundException var15) {
               }
            }

            if (f == null) {
               if (XMLSchedulingDataProcessorPlugin.this.isFailOnFileNotFound()) {
                  throw new SchedulerException("File named '" + this.getFileName() + "' does not exist.");
               }

               XMLSchedulingDataProcessorPlugin.this.getLog().warn("File named '" + this.getFileName() + "' does not exist.");
            } else {
               this.fileFound = true;
            }

            this.filePath = furl != null ? furl : file.getAbsolutePath();
            this.fileBasename = file.getName();
         } finally {
            try {
               if (f != null) {
                  ((InputStream)f).close();
               }
            } catch (IOException var14) {
               XMLSchedulingDataProcessorPlugin.this.getLog().warn("Error closing jobs file " + this.getFileName(), var14);
            }

         }

      }
   }
}
