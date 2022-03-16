package org.quartz.xml;

import java.io.File;
import java.io.FileInputStream;
import java.io.FileNotFoundException;
import java.io.IOException;
import java.io.InputStream;
import java.io.UnsupportedEncodingException;
import java.net.URL;
import java.net.URLDecoder;
import java.text.ParseException;
import java.util.ArrayList;
import java.util.Collection;
import java.util.Collections;
import java.util.Date;
import java.util.HashMap;
import java.util.Iterator;
import java.util.LinkedList;
import java.util.List;
import java.util.Map;
import java.util.TimeZone;
import javax.xml.bind.DatatypeConverter;
import javax.xml.namespace.NamespaceContext;
import javax.xml.parsers.DocumentBuilder;
import javax.xml.parsers.DocumentBuilderFactory;
import javax.xml.parsers.ParserConfigurationException;
import javax.xml.xpath.XPath;
import javax.xml.xpath.XPathConstants;
import javax.xml.xpath.XPathException;
import javax.xml.xpath.XPathExpressionException;
import javax.xml.xpath.XPathFactory;
import org.quartz.CalendarIntervalScheduleBuilder;
import org.quartz.CronScheduleBuilder;
import org.quartz.DateBuilder;
import org.quartz.Job;
import org.quartz.JobBuilder;
import org.quartz.JobDetail;
import org.quartz.JobKey;
import org.quartz.JobPersistenceException;
import org.quartz.ObjectAlreadyExistsException;
import org.quartz.ScheduleBuilder;
import org.quartz.Scheduler;
import org.quartz.SchedulerException;
import org.quartz.SimpleScheduleBuilder;
import org.quartz.Trigger;
import org.quartz.TriggerBuilder;
import org.quartz.TriggerKey;
import org.quartz.impl.matchers.GroupMatcher;
import org.quartz.spi.ClassLoadHelper;
import org.quartz.spi.MutableTrigger;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import org.w3c.dom.Document;
import org.w3c.dom.Node;
import org.w3c.dom.NodeList;
import org.xml.sax.ErrorHandler;
import org.xml.sax.InputSource;
import org.xml.sax.SAXException;
import org.xml.sax.SAXParseException;

public class XMLSchedulingDataProcessor implements ErrorHandler {
   public static final String QUARTZ_NS = "http://www.quartz-scheduler.org/xml/JobSchedulingData";
   public static final String QUARTZ_SCHEMA_WEB_URL = "http://www.quartz-scheduler.org/xml/job_scheduling_data_2_0.xsd";
   public static final String QUARTZ_XSD_PATH_IN_JAR = "org/quartz/xml/job_scheduling_data_2_0.xsd";
   public static final String QUARTZ_XML_DEFAULT_FILE_NAME = "quartz_data.xml";
   public static final String QUARTZ_SYSTEM_ID_JAR_PREFIX = "jar:";
   protected List<String> jobGroupsToDelete = new LinkedList();
   protected List<String> triggerGroupsToDelete = new LinkedList();
   protected List<JobKey> jobsToDelete = new LinkedList();
   protected List<TriggerKey> triggersToDelete = new LinkedList();
   protected List<JobDetail> loadedJobs = new LinkedList();
   protected List<MutableTrigger> loadedTriggers = new LinkedList();
   private boolean overWriteExistingData = true;
   private boolean ignoreDuplicates = false;
   protected Collection<Exception> validationExceptions = new ArrayList();
   protected ClassLoadHelper classLoadHelper;
   protected List<String> jobGroupsToNeverDelete = new LinkedList();
   protected List<String> triggerGroupsToNeverDelete = new LinkedList();
   private DocumentBuilder docBuilder = null;
   private XPath xpath = null;
   private final Logger log = LoggerFactory.getLogger(this.getClass());

   public XMLSchedulingDataProcessor(ClassLoadHelper clh) throws ParserConfigurationException {
      this.classLoadHelper = clh;
      this.initDocumentParser();
   }

   protected void initDocumentParser() throws ParserConfigurationException {
      DocumentBuilderFactory docBuilderFactory = DocumentBuilderFactory.newInstance();
      docBuilderFactory.setNamespaceAware(true);
      docBuilderFactory.setValidating(true);
      docBuilderFactory.setAttribute("http://java.sun.com/xml/jaxp/properties/schemaLanguage", "http://www.w3.org/2001/XMLSchema");
      docBuilderFactory.setAttribute("http://java.sun.com/xml/jaxp/properties/schemaSource", this.resolveSchemaSource());
      this.docBuilder = docBuilderFactory.newDocumentBuilder();
      this.docBuilder.setErrorHandler(this);
      NamespaceContext nsContext = new NamespaceContext() {
         public String getNamespaceURI(String prefix) {
            if (prefix == null) {
               throw new IllegalArgumentException("Null prefix");
            } else if ("xml".equals(prefix)) {
               return "http://www.w3.org/XML/1998/namespace";
            } else if ("xmlns".equals(prefix)) {
               return "http://www.w3.org/2000/xmlns/";
            } else {
               return "q".equals(prefix) ? "http://www.quartz-scheduler.org/xml/JobSchedulingData" : "";
            }
         }

         public Iterator<?> getPrefixes(String namespaceURI) {
            throw new UnsupportedOperationException();
         }

         public String getPrefix(String namespaceURI) {
            throw new UnsupportedOperationException();
         }
      };
      this.xpath = XPathFactory.newInstance().newXPath();
      this.xpath.setNamespaceContext(nsContext);
   }

   protected Object resolveSchemaSource() {
      InputStream is = null;

      InputSource inputSource;
      try {
         is = this.classLoadHelper.getResourceAsStream("org/quartz/xml/job_scheduling_data_2_0.xsd");
      } finally {
         if (is == null) {
            this.log.info("Unable to load local schema packaged in quartz distribution jar. Utilizing schema online at http://www.quartz-scheduler.org/xml/job_scheduling_data_2_0.xsd");
            return "http://www.quartz-scheduler.org/xml/job_scheduling_data_2_0.xsd";
         }

         inputSource = new InputSource(is);
         inputSource.setSystemId("http://www.quartz-scheduler.org/xml/job_scheduling_data_2_0.xsd");
         this.log.debug("Utilizing schema packaged in local quartz distribution jar.");
      }

      return inputSource;
   }

   public boolean isOverWriteExistingData() {
      return this.overWriteExistingData;
   }

   protected void setOverWriteExistingData(boolean overWriteExistingData) {
      this.overWriteExistingData = overWriteExistingData;
   }

   public boolean isIgnoreDuplicates() {
      return this.ignoreDuplicates;
   }

   public void setIgnoreDuplicates(boolean ignoreDuplicates) {
      this.ignoreDuplicates = ignoreDuplicates;
   }

   public void addJobGroupToNeverDelete(String group) {
      if (group != null) {
         this.jobGroupsToNeverDelete.add(group);
      }

   }

   public boolean removeJobGroupToNeverDelete(String group) {
      return group != null && this.jobGroupsToNeverDelete.remove(group);
   }

   public List<String> getJobGroupsToNeverDelete() {
      return Collections.unmodifiableList(this.jobGroupsToDelete);
   }

   public void addTriggerGroupToNeverDelete(String group) {
      if (group != null) {
         this.triggerGroupsToNeverDelete.add(group);
      }

   }

   public boolean removeTriggerGroupToNeverDelete(String group) {
      return group != null ? this.triggerGroupsToNeverDelete.remove(group) : false;
   }

   public List<String> getTriggerGroupsToNeverDelete() {
      return Collections.unmodifiableList(this.triggerGroupsToDelete);
   }

   protected void processFile() throws Exception {
      this.processFile("quartz_data.xml");
   }

   protected void processFile(String fileName) throws Exception {
      this.processFile(fileName, this.getSystemIdForFileName(fileName));
   }

   protected String getSystemIdForFileName(String fileName) {
      Object fileInputStream = null;

      String var21;
      try {
         String urlPath = null;
         File file = new File(fileName);
         if (!file.exists()) {
            URL url = this.getURL(fileName);
            if (url != null) {
               try {
                  urlPath = URLDecoder.decode(url.getPath(), "UTF-8");
               } catch (UnsupportedEncodingException var19) {
                  this.log.warn("Unable to decode file path URL", var19);
               }

               try {
                  fileInputStream = url.openStream();
               } catch (IOException var18) {
               }
            }
         } else {
            try {
               fileInputStream = new FileInputStream(file);
            } catch (FileNotFoundException var17) {
            }
         }

         if (fileInputStream == null) {
            this.log.debug("Unable to resolve '" + fileName + "' to full path, so using it as is for system id.");
            var21 = fileName;
            return var21;
         }

         var21 = urlPath != null ? urlPath : file.getAbsolutePath();
      } finally {
         try {
            if (fileInputStream != null) {
               ((InputStream)fileInputStream).close();
            }
         } catch (IOException var16) {
            this.log.warn("Error closing jobs file: " + fileName, var16);
         }

      }

      return var21;
   }

   protected URL getURL(String fileName) {
      return this.classLoadHelper.getResource(fileName);
   }

   protected void prepForProcessing() {
      this.clearValidationExceptions();
      this.setOverWriteExistingData(true);
      this.setIgnoreDuplicates(false);
      this.jobGroupsToDelete.clear();
      this.jobsToDelete.clear();
      this.triggerGroupsToDelete.clear();
      this.triggersToDelete.clear();
      this.loadedJobs.clear();
      this.loadedTriggers.clear();
   }

   protected void processFile(String fileName, String systemId) throws ValidationException, ParserConfigurationException, SAXException, IOException, SchedulerException, ClassNotFoundException, ParseException, XPathException {
      this.prepForProcessing();
      this.log.info("Parsing XML file: " + fileName + " with systemId: " + systemId);
      InputSource is = new InputSource(this.getInputStream(fileName));
      is.setSystemId(systemId);
      this.process(is);
      this.maybeThrowValidationException();
   }

   public void processStreamAndScheduleJobs(InputStream stream, String systemId, Scheduler sched) throws ValidationException, ParserConfigurationException, SAXException, XPathException, IOException, SchedulerException, ClassNotFoundException, ParseException {
      this.prepForProcessing();
      this.log.info("Parsing XML from stream with systemId: " + systemId);
      InputSource is = new InputSource(stream);
      is.setSystemId(systemId);
      this.process(is);
      this.executePreProcessCommands(sched);
      this.scheduleJobs(sched);
      this.maybeThrowValidationException();
   }

   protected void process(InputSource is) throws SAXException, IOException, ParseException, XPathException, ClassNotFoundException {
      Document document = this.docBuilder.parse(is);
      NodeList deleteJobGroupNodes = (NodeList)this.xpath.evaluate("/q:job-scheduling-data/q:pre-processing-commands/q:delete-jobs-in-group", document, XPathConstants.NODESET);
      this.log.debug("Found " + deleteJobGroupNodes.getLength() + " delete job group commands.");

      for(int i = 0; i < deleteJobGroupNodes.getLength(); ++i) {
         Node node = deleteJobGroupNodes.item(i);
         String t = node.getTextContent();
         if (t != null && (t = t.trim()).length() != 0) {
            this.jobGroupsToDelete.add(t);
         }
      }

      NodeList deleteTriggerGroupNodes = (NodeList)this.xpath.evaluate("/q:job-scheduling-data/q:pre-processing-commands/q:delete-triggers-in-group", document, XPathConstants.NODESET);
      this.log.debug("Found " + deleteTriggerGroupNodes.getLength() + " delete trigger group commands.");

      for(int i = 0; i < deleteTriggerGroupNodes.getLength(); ++i) {
         Node node = deleteTriggerGroupNodes.item(i);
         String t = node.getTextContent();
         if (t != null && (t = t.trim()).length() != 0) {
            this.triggerGroupsToDelete.add(t);
         }
      }

      NodeList deleteJobNodes = (NodeList)this.xpath.evaluate("/q:job-scheduling-data/q:pre-processing-commands/q:delete-job", document, XPathConstants.NODESET);
      this.log.debug("Found " + deleteJobNodes.getLength() + " delete job commands.");

      String name;
      for(int i = 0; i < deleteJobNodes.getLength(); ++i) {
         Node node = deleteJobNodes.item(i);
         String name = this.getTrimmedToNullString(this.xpath, "q:name", node);
         name = this.getTrimmedToNullString(this.xpath, "q:group", node);
         if (name == null) {
            throw new ParseException("Encountered a 'delete-job' command without a name specified.", -1);
         }

         this.jobsToDelete.add(new JobKey(name, name));
      }

      NodeList deleteTriggerNodes = (NodeList)this.xpath.evaluate("/q:job-scheduling-data/q:pre-processing-commands/q:delete-trigger", document, XPathConstants.NODESET);
      this.log.debug("Found " + deleteTriggerNodes.getLength() + " delete trigger commands.");

      for(int i = 0; i < deleteTriggerNodes.getLength(); ++i) {
         Node node = deleteTriggerNodes.item(i);
         name = this.getTrimmedToNullString(this.xpath, "q:name", node);
         String group = this.getTrimmedToNullString(this.xpath, "q:group", node);
         if (name == null) {
            throw new ParseException("Encountered a 'delete-trigger' command without a name specified.", -1);
         }

         this.triggersToDelete.add(new TriggerKey(name, group));
      }

      Boolean overWrite = this.getBoolean(this.xpath, "/q:job-scheduling-data/q:processing-directives/q:overwrite-existing-data", document);
      if (overWrite == null) {
         this.log.debug("Directive 'overwrite-existing-data' not specified, defaulting to " + this.isOverWriteExistingData());
      } else {
         this.log.debug("Directive 'overwrite-existing-data' specified as: " + overWrite);
         this.setOverWriteExistingData(overWrite);
      }

      Boolean ignoreDupes = this.getBoolean(this.xpath, "/q:job-scheduling-data/q:processing-directives/q:ignore-duplicates", document);
      if (ignoreDupes == null) {
         this.log.debug("Directive 'ignore-duplicates' not specified, defaulting to " + this.isIgnoreDuplicates());
      } else {
         this.log.debug("Directive 'ignore-duplicates' specified as: " + ignoreDupes);
         this.setIgnoreDuplicates(ignoreDupes);
      }

      NodeList jobNodes = (NodeList)this.xpath.evaluate("/q:job-scheduling-data/q:schedule/q:job", document, XPathConstants.NODESET);
      this.log.debug("Found " + jobNodes.getLength() + " job definitions.");

      String triggerName;
      String triggerGroup;
      String triggerDescription;
      String triggerMisfireInstructionConst;
      String endTimeString;
      for(int i = 0; i < jobNodes.getLength(); ++i) {
         Node jobDetailNode = jobNodes.item(i);
         String t = null;
         triggerName = this.getTrimmedToNullString(this.xpath, "q:name", jobDetailNode);
         triggerGroup = this.getTrimmedToNullString(this.xpath, "q:group", jobDetailNode);
         triggerDescription = this.getTrimmedToNullString(this.xpath, "q:description", jobDetailNode);
         triggerMisfireInstructionConst = this.getTrimmedToNullString(this.xpath, "q:job-class", jobDetailNode);
         t = this.getTrimmedToNullString(this.xpath, "q:durability", jobDetailNode);
         boolean jobDurability = t != null && t.equals("true");
         t = this.getTrimmedToNullString(this.xpath, "q:recover", jobDetailNode);
         boolean jobRecoveryRequested = t != null && t.equals("true");
         Class<? extends Job> jobClass = this.classLoadHelper.loadClass(triggerMisfireInstructionConst, Job.class);
         JobDetail jobDetail = JobBuilder.newJob(jobClass).withIdentity(triggerName, triggerGroup).withDescription(triggerDescription).storeDurably(jobDurability).requestRecovery(jobRecoveryRequested).build();
         NodeList jobDataEntries = (NodeList)this.xpath.evaluate("q:job-data-map/q:entry", jobDetailNode, XPathConstants.NODESET);

         for(int k = 0; k < jobDataEntries.getLength(); ++k) {
            Node entryNode = jobDataEntries.item(k);
            endTimeString = this.getTrimmedToNullString(this.xpath, "q:key", entryNode);
            String value = this.getTrimmedToNullString(this.xpath, "q:value", entryNode);
            jobDetail.getJobDataMap().put(endTimeString, value);
         }

         if (this.log.isDebugEnabled()) {
            this.log.debug("Parsed job definition: " + jobDetail);
         }

         this.addJobToSchedule(jobDetail);
      }

      NodeList triggerEntries = (NodeList)this.xpath.evaluate("/q:job-scheduling-data/q:schedule/q:trigger/*", document, XPathConstants.NODESET);
      this.log.debug("Found " + triggerEntries.getLength() + " trigger definitions.");

      for(int j = 0; j < triggerEntries.getLength(); ++j) {
         Node triggerNode = triggerEntries.item(j);
         triggerName = this.getTrimmedToNullString(this.xpath, "q:name", triggerNode);
         triggerGroup = this.getTrimmedToNullString(this.xpath, "q:group", triggerNode);
         triggerDescription = this.getTrimmedToNullString(this.xpath, "q:description", triggerNode);
         triggerMisfireInstructionConst = this.getTrimmedToNullString(this.xpath, "q:misfire-instruction", triggerNode);
         String triggerPriorityString = this.getTrimmedToNullString(this.xpath, "q:priority", triggerNode);
         String triggerCalendarRef = this.getTrimmedToNullString(this.xpath, "q:calendar-name", triggerNode);
         String triggerJobName = this.getTrimmedToNullString(this.xpath, "q:job-name", triggerNode);
         String triggerJobGroup = this.getTrimmedToNullString(this.xpath, "q:job-group", triggerNode);
         int triggerPriority = 5;
         if (triggerPriorityString != null) {
            triggerPriority = Integer.valueOf(triggerPriorityString);
         }

         String startTimeString = this.getTrimmedToNullString(this.xpath, "q:start-time", triggerNode);
         String startTimeFutureSecsString = this.getTrimmedToNullString(this.xpath, "q:start-time-seconds-in-future", triggerNode);
         endTimeString = this.getTrimmedToNullString(this.xpath, "q:end-time", triggerNode);
         Date triggerStartTime;
         if (startTimeFutureSecsString != null) {
            triggerStartTime = new Date(System.currentTimeMillis() + Long.valueOf(startTimeFutureSecsString) * 1000L);
         } else {
            triggerStartTime = startTimeString != null && startTimeString.length() != 0 ? DatatypeConverter.parseDateTime(startTimeString).getTime() : new Date();
         }

         Date triggerEndTime = endTimeString != null && endTimeString.length() != 0 ? DatatypeConverter.parseDateTime(endTimeString).getTime() : null;
         TriggerKey triggerKey = TriggerKey.triggerKey(triggerName, triggerGroup);
         Object sched;
         String repeatIntervalString;
         String repeatUnitString;
         int k;
         if (triggerNode.getNodeName().equals("simple")) {
            repeatIntervalString = this.getTrimmedToNullString(this.xpath, "q:repeat-count", triggerNode);
            repeatUnitString = this.getTrimmedToNullString(this.xpath, "q:repeat-interval", triggerNode);
            k = repeatIntervalString == null ? 0 : Integer.parseInt(repeatIntervalString);
            long repeatInterval = repeatUnitString == null ? 0L : Long.parseLong(repeatUnitString);
            sched = SimpleScheduleBuilder.simpleSchedule().withIntervalInMilliseconds(repeatInterval).withRepeatCount(k);
            if (triggerMisfireInstructionConst != null && triggerMisfireInstructionConst.length() != 0) {
               if (triggerMisfireInstructionConst.equals("MISFIRE_INSTRUCTION_FIRE_NOW")) {
                  ((SimpleScheduleBuilder)sched).withMisfireHandlingInstructionFireNow();
               } else if (triggerMisfireInstructionConst.equals("MISFIRE_INSTRUCTION_RESCHEDULE_NEXT_WITH_EXISTING_COUNT")) {
                  ((SimpleScheduleBuilder)sched).withMisfireHandlingInstructionNextWithExistingCount();
               } else if (triggerMisfireInstructionConst.equals("MISFIRE_INSTRUCTION_RESCHEDULE_NEXT_WITH_REMAINING_COUNT")) {
                  ((SimpleScheduleBuilder)sched).withMisfireHandlingInstructionNextWithRemainingCount();
               } else if (triggerMisfireInstructionConst.equals("MISFIRE_INSTRUCTION_RESCHEDULE_NOW_WITH_EXISTING_REPEAT_COUNT")) {
                  ((SimpleScheduleBuilder)sched).withMisfireHandlingInstructionNowWithExistingCount();
               } else if (triggerMisfireInstructionConst.equals("MISFIRE_INSTRUCTION_RESCHEDULE_NOW_WITH_REMAINING_REPEAT_COUNT")) {
                  ((SimpleScheduleBuilder)sched).withMisfireHandlingInstructionNowWithRemainingCount();
               } else if (!triggerMisfireInstructionConst.equals("MISFIRE_INSTRUCTION_SMART_POLICY")) {
                  throw new ParseException("Unexpected/Unhandlable Misfire Instruction encountered '" + triggerMisfireInstructionConst + "', for trigger: " + triggerKey, -1);
               }
            }
         } else if (triggerNode.getNodeName().equals("cron")) {
            repeatIntervalString = this.getTrimmedToNullString(this.xpath, "q:cron-expression", triggerNode);
            repeatUnitString = this.getTrimmedToNullString(this.xpath, "q:time-zone", triggerNode);
            TimeZone tz = repeatUnitString == null ? null : TimeZone.getTimeZone(repeatUnitString);
            sched = CronScheduleBuilder.cronSchedule(repeatIntervalString).inTimeZone(tz);
            if (triggerMisfireInstructionConst != null && triggerMisfireInstructionConst.length() != 0) {
               if (triggerMisfireInstructionConst.equals("MISFIRE_INSTRUCTION_DO_NOTHING")) {
                  ((CronScheduleBuilder)sched).withMisfireHandlingInstructionDoNothing();
               } else if (triggerMisfireInstructionConst.equals("MISFIRE_INSTRUCTION_FIRE_ONCE_NOW")) {
                  ((CronScheduleBuilder)sched).withMisfireHandlingInstructionFireAndProceed();
               } else if (!triggerMisfireInstructionConst.equals("MISFIRE_INSTRUCTION_SMART_POLICY")) {
                  throw new ParseException("Unexpected/Unhandlable Misfire Instruction encountered '" + triggerMisfireInstructionConst + "', for trigger: " + triggerKey, -1);
               }
            }
         } else {
            if (!triggerNode.getNodeName().equals("calendar-interval")) {
               throw new ParseException("Unknown trigger type: " + triggerNode.getNodeName(), -1);
            }

            repeatIntervalString = this.getTrimmedToNullString(this.xpath, "q:repeat-interval", triggerNode);
            repeatUnitString = this.getTrimmedToNullString(this.xpath, "q:repeat-interval-unit", triggerNode);
            k = Integer.parseInt(repeatIntervalString);
            DateBuilder.IntervalUnit repeatUnit = DateBuilder.IntervalUnit.valueOf(repeatUnitString);
            sched = CalendarIntervalScheduleBuilder.calendarIntervalSchedule().withInterval(k, repeatUnit);
            if (triggerMisfireInstructionConst != null && triggerMisfireInstructionConst.length() != 0) {
               if (triggerMisfireInstructionConst.equals("MISFIRE_INSTRUCTION_DO_NOTHING")) {
                  ((CalendarIntervalScheduleBuilder)sched).withMisfireHandlingInstructionDoNothing();
               } else if (triggerMisfireInstructionConst.equals("MISFIRE_INSTRUCTION_FIRE_ONCE_NOW")) {
                  ((CalendarIntervalScheduleBuilder)sched).withMisfireHandlingInstructionFireAndProceed();
               } else if (!triggerMisfireInstructionConst.equals("MISFIRE_INSTRUCTION_SMART_POLICY")) {
                  throw new ParseException("Unexpected/Unhandlable Misfire Instruction encountered '" + triggerMisfireInstructionConst + "', for trigger: " + triggerKey, -1);
               }
            }
         }

         MutableTrigger trigger = (MutableTrigger)TriggerBuilder.newTrigger().withIdentity(triggerName, triggerGroup).withDescription(triggerDescription).forJob(triggerJobName, triggerJobGroup).startAt(triggerStartTime).endAt(triggerEndTime).withPriority(triggerPriority).modifiedByCalendar(triggerCalendarRef).withSchedule((ScheduleBuilder)sched).build();
         NodeList jobDataEntries = (NodeList)this.xpath.evaluate("q:job-data-map/q:entry", triggerNode, XPathConstants.NODESET);

         for(k = 0; k < jobDataEntries.getLength(); ++k) {
            Node entryNode = jobDataEntries.item(k);
            String key = this.getTrimmedToNullString(this.xpath, "q:key", entryNode);
            String value = this.getTrimmedToNullString(this.xpath, "q:value", entryNode);
            trigger.getJobDataMap().put(key, value);
         }

         if (this.log.isDebugEnabled()) {
            this.log.debug("Parsed trigger definition: " + trigger);
         }

         this.addTriggerToSchedule(trigger);
      }

   }

   protected String getTrimmedToNullString(XPath xpathToElement, String elementName, Node parentNode) throws XPathExpressionException {
      String str = (String)xpathToElement.evaluate(elementName, parentNode, XPathConstants.STRING);
      if (str != null) {
         str = str.trim();
      }

      if (str != null && str.length() == 0) {
         str = null;
      }

      return str;
   }

   protected Boolean getBoolean(XPath xpathToElement, String elementName, Document document) throws XPathExpressionException {
      Node directive = (Node)xpathToElement.evaluate(elementName, document, XPathConstants.NODE);
      if (directive != null && directive.getTextContent() != null) {
         String val = directive.getTextContent();
         return !val.equalsIgnoreCase("true") && !val.equalsIgnoreCase("yes") && !val.equalsIgnoreCase("y") ? Boolean.FALSE : Boolean.TRUE;
      } else {
         return null;
      }
   }

   public void processFileAndScheduleJobs(Scheduler sched, boolean overWriteExistingJobs) throws Exception {
      String fileName = "quartz_data.xml";
      this.processFile(fileName, this.getSystemIdForFileName(fileName));
      this.setOverWriteExistingData(overWriteExistingJobs);
      this.executePreProcessCommands(sched);
      this.scheduleJobs(sched);
   }

   public void processFileAndScheduleJobs(String fileName, Scheduler sched) throws Exception {
      this.processFileAndScheduleJobs(fileName, this.getSystemIdForFileName(fileName), sched);
   }

   public void processFileAndScheduleJobs(String fileName, String systemId, Scheduler sched) throws Exception {
      this.processFile(fileName, systemId);
      this.executePreProcessCommands(sched);
      this.scheduleJobs(sched);
   }

   protected List<JobDetail> getLoadedJobs() {
      return Collections.unmodifiableList(this.loadedJobs);
   }

   protected List<MutableTrigger> getLoadedTriggers() {
      return Collections.unmodifiableList(this.loadedTriggers);
   }

   protected InputStream getInputStream(String fileName) {
      return this.classLoadHelper.getResourceAsStream(fileName);
   }

   protected void addJobToSchedule(JobDetail job) {
      this.loadedJobs.add(job);
   }

   protected void addTriggerToSchedule(MutableTrigger trigger) {
      this.loadedTriggers.add(trigger);
   }

   private Map<JobKey, List<MutableTrigger>> buildTriggersByFQJobNameMap(List<MutableTrigger> triggers) {
      Map<JobKey, List<MutableTrigger>> triggersByFQJobName = new HashMap();

      MutableTrigger trigger;
      Object triggersOfJob;
      for(Iterator i$ = triggers.iterator(); i$.hasNext(); ((List)triggersOfJob).add(trigger)) {
         trigger = (MutableTrigger)i$.next();
         triggersOfJob = (List)triggersByFQJobName.get(trigger.getJobKey());
         if (triggersOfJob == null) {
            triggersOfJob = new LinkedList();
            triggersByFQJobName.put(trigger.getJobKey(), triggersOfJob);
         }
      }

      return triggersByFQJobName;
   }

   protected void executePreProcessCommands(Scheduler scheduler) throws SchedulerException {
      Iterator i$ = this.jobGroupsToDelete.iterator();

      while(true) {
         String group;
         Iterator i$;
         Iterator i$;
         String groupName;
         label97:
         while(i$.hasNext()) {
            group = (String)i$.next();
            if (group.equals("*")) {
               this.log.info("Deleting all jobs in ALL groups.");
               i$ = scheduler.getJobGroupNames().iterator();

               while(true) {
                  do {
                     if (!i$.hasNext()) {
                        continue label97;
                     }

                     groupName = (String)i$.next();
                  } while(this.jobGroupsToNeverDelete.contains(groupName));

                  i$ = scheduler.getJobKeys(GroupMatcher.jobGroupEquals(groupName)).iterator();

                  while(i$.hasNext()) {
                     JobKey key = (JobKey)i$.next();
                     scheduler.deleteJob(key);
                  }
               }
            } else if (!this.jobGroupsToNeverDelete.contains(group)) {
               this.log.info("Deleting all jobs in group: {}", group);
               i$ = scheduler.getJobKeys(GroupMatcher.jobGroupEquals(group)).iterator();

               while(i$.hasNext()) {
                  JobKey key = (JobKey)i$.next();
                  scheduler.deleteJob(key);
               }
            }
         }

         i$ = this.triggerGroupsToDelete.iterator();

         while(true) {
            label68:
            while(i$.hasNext()) {
               group = (String)i$.next();
               if (group.equals("*")) {
                  this.log.info("Deleting all triggers in ALL groups.");
                  i$ = scheduler.getTriggerGroupNames().iterator();

                  while(true) {
                     do {
                        if (!i$.hasNext()) {
                           continue label68;
                        }

                        groupName = (String)i$.next();
                     } while(this.triggerGroupsToNeverDelete.contains(groupName));

                     i$ = scheduler.getTriggerKeys(GroupMatcher.triggerGroupEquals(groupName)).iterator();

                     while(i$.hasNext()) {
                        TriggerKey key = (TriggerKey)i$.next();
                        scheduler.unscheduleJob(key);
                     }
                  }
               } else if (!this.triggerGroupsToNeverDelete.contains(group)) {
                  this.log.info("Deleting all triggers in group: {}", group);
                  i$ = scheduler.getTriggerKeys(GroupMatcher.triggerGroupEquals(group)).iterator();

                  while(i$.hasNext()) {
                     TriggerKey key = (TriggerKey)i$.next();
                     scheduler.unscheduleJob(key);
                  }
               }
            }

            i$ = this.jobsToDelete.iterator();

            while(i$.hasNext()) {
               JobKey key = (JobKey)i$.next();
               if (!this.jobGroupsToNeverDelete.contains(key.getGroup())) {
                  this.log.info("Deleting job: {}", key);
                  scheduler.deleteJob(key);
               }
            }

            i$ = this.triggersToDelete.iterator();

            while(i$.hasNext()) {
               TriggerKey key = (TriggerKey)i$.next();
               if (!this.triggerGroupsToNeverDelete.contains(key.getGroup())) {
                  this.log.info("Deleting trigger: {}", key);
                  scheduler.unscheduleJob(key);
               }
            }

            return;
         }
      }
   }

   protected void scheduleJobs(Scheduler sched) throws SchedulerException {
      List<JobDetail> jobs = new LinkedList(this.getLoadedJobs());
      List<MutableTrigger> triggers = new LinkedList(this.getLoadedTriggers());
      this.log.info("Adding " + jobs.size() + " jobs, " + triggers.size() + " triggers.");
      Map<JobKey, List<MutableTrigger>> triggersByFQJobName = this.buildTriggersByFQJobNameMap(triggers);
      Iterator itr = jobs.iterator();

      label177:
      while(itr.hasNext()) {
         JobDetail detail = (JobDetail)itr.next();
         itr.remove();
         JobDetail dupeJ = null;

         try {
            dupeJ = sched.getJobDetail(detail.getKey());
         } catch (JobPersistenceException var16) {
            if (!(var16.getCause() instanceof ClassNotFoundException) || !this.isOverWriteExistingData()) {
               throw var16;
            }

            this.log.info("Removing job: " + detail.getKey());
            sched.deleteJob(detail.getKey());
         }

         if (dupeJ != null) {
            if (!this.isOverWriteExistingData() && this.isIgnoreDuplicates()) {
               this.log.info("Not overwriting existing job: " + dupeJ.getKey());
               continue;
            }

            if (!this.isOverWriteExistingData() && !this.isIgnoreDuplicates()) {
               throw new ObjectAlreadyExistsException(detail);
            }
         }

         if (dupeJ != null) {
            this.log.info("Replacing job: " + detail.getKey());
         } else {
            this.log.info("Adding job: " + detail.getKey());
         }

         List<MutableTrigger> triggersOfJob = (List)triggersByFQJobName.get(detail.getKey());
         if (!detail.isDurable() && (triggersOfJob == null || triggersOfJob.size() == 0)) {
            if (dupeJ == null) {
               throw new SchedulerException("A new job defined without any triggers must be durable: " + detail.getKey());
            }

            if (dupeJ.isDurable() && sched.getTriggersOfJob(detail.getKey()).size() == 0) {
               throw new SchedulerException("Can't change existing durable job without triggers to non-durable: " + detail.getKey());
            }
         }

         if (dupeJ == null && !detail.isDurable()) {
            boolean addJobWithFirstSchedule = true;
            Iterator i$ = triggersOfJob.iterator();

            while(true) {
               while(true) {
                  if (!i$.hasNext()) {
                     continue label177;
                  }

                  MutableTrigger trigger = (MutableTrigger)i$.next();
                  triggers.remove(trigger);
                  if (trigger.getStartTime() == null) {
                     trigger.setStartTime(new Date());
                  }

                  Trigger dupeT = sched.getTrigger(trigger.getKey());
                  if (dupeT != null) {
                     if (this.isOverWriteExistingData()) {
                        if (this.log.isDebugEnabled()) {
                           this.log.debug("Rescheduling job: " + trigger.getJobKey() + " with updated trigger: " + trigger.getKey());
                        }

                        if (!dupeT.getJobKey().equals(trigger.getJobKey())) {
                           this.log.warn("Possibly duplicately named ({}) triggers in jobs xml file! ", trigger.getKey());
                        }

                        sched.rescheduleJob(trigger.getKey(), trigger);
                     } else {
                        if (!this.isIgnoreDuplicates()) {
                           throw new ObjectAlreadyExistsException(trigger);
                        }

                        this.log.info("Not overwriting existing trigger: " + dupeT.getKey());
                     }
                  } else {
                     if (this.log.isDebugEnabled()) {
                        this.log.debug("Scheduling job: " + trigger.getJobKey() + " with trigger: " + trigger.getKey());
                     }

                     try {
                        if (addJobWithFirstSchedule) {
                           sched.scheduleJob(detail, trigger);
                           addJobWithFirstSchedule = false;
                        } else {
                           sched.scheduleJob(trigger);
                        }
                     } catch (ObjectAlreadyExistsException var15) {
                        if (this.log.isDebugEnabled()) {
                           this.log.debug("Adding trigger: " + trigger.getKey() + " for job: " + detail.getKey() + " failed because the trigger already existed.  " + "This is likely due to a race condition between multiple instances " + "in the cluster.  Will try to reschedule instead.");
                        }

                        sched.rescheduleJob(trigger.getKey(), trigger);
                     }
                  }
               }
            }
         } else if (triggersOfJob != null && triggersOfJob.size() > 0) {
            sched.addJob(detail, true, true);
         } else {
            sched.addJob(detail, true, false);
         }
      }

      Iterator i$ = triggers.iterator();

      while(true) {
         while(i$.hasNext()) {
            MutableTrigger trigger = (MutableTrigger)i$.next();
            if (trigger.getStartTime() == null) {
               trigger.setStartTime(new Date());
            }

            Trigger dupeT = sched.getTrigger(trigger.getKey());
            if (dupeT != null) {
               if (this.isOverWriteExistingData()) {
                  if (this.log.isDebugEnabled()) {
                     this.log.debug("Rescheduling job: " + trigger.getJobKey() + " with updated trigger: " + trigger.getKey());
                  }

                  if (!dupeT.getJobKey().equals(trigger.getJobKey())) {
                     this.log.warn("Possibly duplicately named ({}) triggers in jobs xml file! ", trigger.getKey());
                  }

                  sched.rescheduleJob(trigger.getKey(), trigger);
               } else {
                  if (!this.isIgnoreDuplicates()) {
                     throw new ObjectAlreadyExistsException(trigger);
                  }

                  this.log.info("Not overwriting existing trigger: " + dupeT.getKey());
               }
            } else {
               if (this.log.isDebugEnabled()) {
                  this.log.debug("Scheduling job: " + trigger.getJobKey() + " with trigger: " + trigger.getKey());
               }

               try {
                  sched.scheduleJob(trigger);
               } catch (ObjectAlreadyExistsException var14) {
                  if (this.log.isDebugEnabled()) {
                     this.log.debug("Adding trigger: " + trigger.getKey() + " for job: " + trigger.getJobKey() + " failed because the trigger already existed.  " + "This is likely due to a race condition between multiple instances " + "in the cluster.  Will try to reschedule instead.");
                  }

                  sched.rescheduleJob(trigger.getKey(), trigger);
               }
            }
         }

         return;
      }
   }

   public void warning(SAXParseException e) throws SAXException {
      this.addValidationException(e);
   }

   public void error(SAXParseException e) throws SAXException {
      this.addValidationException(e);
   }

   public void fatalError(SAXParseException e) throws SAXException {
      this.addValidationException(e);
   }

   protected void addValidationException(SAXException e) {
      this.validationExceptions.add(e);
   }

   protected void clearValidationExceptions() {
      this.validationExceptions.clear();
   }

   protected void maybeThrowValidationException() throws ValidationException {
      if (this.validationExceptions.size() > 0) {
         throw new ValidationException("Encountered " + this.validationExceptions.size() + " validation exceptions.", this.validationExceptions);
      }
   }
}
