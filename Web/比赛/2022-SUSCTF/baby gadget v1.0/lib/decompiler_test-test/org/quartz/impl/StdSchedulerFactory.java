package org.quartz.impl;

import java.beans.BeanInfo;
import java.beans.IntrospectionException;
import java.beans.Introspector;
import java.beans.PropertyDescriptor;
import java.io.BufferedInputStream;
import java.io.File;
import java.io.FileInputStream;
import java.io.IOException;
import java.io.InputStream;
import java.lang.reflect.InvocationTargetException;
import java.lang.reflect.Method;
import java.security.AccessControlException;
import java.sql.SQLException;
import java.util.Collection;
import java.util.Enumeration;
import java.util.Iterator;
import java.util.Locale;
import java.util.Properties;
import org.quartz.JobListener;
import org.quartz.Matcher;
import org.quartz.Scheduler;
import org.quartz.SchedulerConfigException;
import org.quartz.SchedulerException;
import org.quartz.SchedulerFactory;
import org.quartz.TriggerListener;
import org.quartz.core.JobRunShellFactory;
import org.quartz.core.QuartzScheduler;
import org.quartz.core.QuartzSchedulerResources;
import org.quartz.ee.jta.JTAAnnotationAwareJobRunShellFactory;
import org.quartz.ee.jta.JTAJobRunShellFactory;
import org.quartz.ee.jta.UserTransactionHelper;
import org.quartz.impl.jdbcjobstore.JobStoreSupport;
import org.quartz.impl.jdbcjobstore.Semaphore;
import org.quartz.impl.jdbcjobstore.TablePrefixAware;
import org.quartz.impl.matchers.EverythingMatcher;
import org.quartz.management.ManagementRESTServiceConfiguration;
import org.quartz.simpl.RAMJobStore;
import org.quartz.simpl.SimpleThreadPool;
import org.quartz.spi.ClassLoadHelper;
import org.quartz.spi.InstanceIdGenerator;
import org.quartz.spi.JobFactory;
import org.quartz.spi.JobStore;
import org.quartz.spi.SchedulerPlugin;
import org.quartz.spi.ThreadExecutor;
import org.quartz.spi.ThreadPool;
import org.quartz.utils.ConnectionProvider;
import org.quartz.utils.DBConnectionManager;
import org.quartz.utils.JNDIConnectionProvider;
import org.quartz.utils.PoolingConnectionProvider;
import org.quartz.utils.PropertiesParser;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

public class StdSchedulerFactory implements SchedulerFactory {
   public static final String PROPERTIES_FILE = "org.quartz.properties";
   public static final String PROP_SCHED_INSTANCE_NAME = "org.quartz.scheduler.instanceName";
   public static final String PROP_SCHED_INSTANCE_ID = "org.quartz.scheduler.instanceId";
   public static final String PROP_SCHED_INSTANCE_ID_GENERATOR_PREFIX = "org.quartz.scheduler.instanceIdGenerator";
   public static final String PROP_SCHED_INSTANCE_ID_GENERATOR_CLASS = "org.quartz.scheduler.instanceIdGenerator.class";
   public static final String PROP_SCHED_THREAD_NAME = "org.quartz.scheduler.threadName";
   public static final String PROP_SCHED_SKIP_UPDATE_CHECK = "org.quartz.scheduler.skipUpdateCheck";
   public static final String PROP_SCHED_BATCH_TIME_WINDOW = "org.quartz.scheduler.batchTriggerAcquisitionFireAheadTimeWindow";
   public static final String PROP_SCHED_MAX_BATCH_SIZE = "org.quartz.scheduler.batchTriggerAcquisitionMaxCount";
   public static final String PROP_SCHED_JMX_EXPORT = "org.quartz.scheduler.jmx.export";
   public static final String PROP_SCHED_JMX_OBJECT_NAME = "org.quartz.scheduler.jmx.objectName";
   public static final String PROP_SCHED_JMX_PROXY = "org.quartz.scheduler.jmx.proxy";
   public static final String PROP_SCHED_JMX_PROXY_CLASS = "org.quartz.scheduler.jmx.proxy.class";
   public static final String PROP_SCHED_RMI_EXPORT = "org.quartz.scheduler.rmi.export";
   public static final String PROP_SCHED_RMI_PROXY = "org.quartz.scheduler.rmi.proxy";
   public static final String PROP_SCHED_RMI_HOST = "org.quartz.scheduler.rmi.registryHost";
   public static final String PROP_SCHED_RMI_PORT = "org.quartz.scheduler.rmi.registryPort";
   public static final String PROP_SCHED_RMI_SERVER_PORT = "org.quartz.scheduler.rmi.serverPort";
   public static final String PROP_SCHED_RMI_CREATE_REGISTRY = "org.quartz.scheduler.rmi.createRegistry";
   public static final String PROP_SCHED_RMI_BIND_NAME = "org.quartz.scheduler.rmi.bindName";
   public static final String PROP_SCHED_WRAP_JOB_IN_USER_TX = "org.quartz.scheduler.wrapJobExecutionInUserTransaction";
   public static final String PROP_SCHED_USER_TX_URL = "org.quartz.scheduler.userTransactionURL";
   public static final String PROP_SCHED_IDLE_WAIT_TIME = "org.quartz.scheduler.idleWaitTime";
   public static final String PROP_SCHED_DB_FAILURE_RETRY_INTERVAL = "org.quartz.scheduler.dbFailureRetryInterval";
   public static final String PROP_SCHED_MAKE_SCHEDULER_THREAD_DAEMON = "org.quartz.scheduler.makeSchedulerThreadDaemon";
   public static final String PROP_SCHED_SCHEDULER_THREADS_INHERIT_CONTEXT_CLASS_LOADER_OF_INITIALIZING_THREAD = "org.quartz.scheduler.threadsInheritContextClassLoaderOfInitializer";
   public static final String PROP_SCHED_CLASS_LOAD_HELPER_CLASS = "org.quartz.scheduler.classLoadHelper.class";
   public static final String PROP_SCHED_JOB_FACTORY_CLASS = "org.quartz.scheduler.jobFactory.class";
   public static final String PROP_SCHED_JOB_FACTORY_PREFIX = "org.quartz.scheduler.jobFactory";
   public static final String PROP_SCHED_INTERRUPT_JOBS_ON_SHUTDOWN = "org.quartz.scheduler.interruptJobsOnShutdown";
   public static final String PROP_SCHED_INTERRUPT_JOBS_ON_SHUTDOWN_WITH_WAIT = "org.quartz.scheduler.interruptJobsOnShutdownWithWait";
   public static final String PROP_SCHED_CONTEXT_PREFIX = "org.quartz.context.key";
   public static final String PROP_THREAD_POOL_PREFIX = "org.quartz.threadPool";
   public static final String PROP_THREAD_POOL_CLASS = "org.quartz.threadPool.class";
   public static final String PROP_JOB_STORE_PREFIX = "org.quartz.jobStore";
   public static final String PROP_JOB_STORE_LOCK_HANDLER_PREFIX = "org.quartz.jobStore.lockHandler";
   public static final String PROP_JOB_STORE_LOCK_HANDLER_CLASS = "org.quartz.jobStore.lockHandler.class";
   public static final String PROP_TABLE_PREFIX = "tablePrefix";
   public static final String PROP_SCHED_NAME = "schedName";
   public static final String PROP_JOB_STORE_CLASS = "org.quartz.jobStore.class";
   public static final String PROP_JOB_STORE_USE_PROP = "org.quartz.jobStore.useProperties";
   public static final String PROP_DATASOURCE_PREFIX = "org.quartz.dataSource";
   public static final String PROP_CONNECTION_PROVIDER_CLASS = "connectionProvider.class";
   /** @deprecated */
   @Deprecated
   public static final String PROP_DATASOURCE_DRIVER = "driver";
   /** @deprecated */
   @Deprecated
   public static final String PROP_DATASOURCE_URL = "URL";
   /** @deprecated */
   @Deprecated
   public static final String PROP_DATASOURCE_USER = "user";
   /** @deprecated */
   @Deprecated
   public static final String PROP_DATASOURCE_PASSWORD = "password";
   /** @deprecated */
   @Deprecated
   public static final String PROP_DATASOURCE_MAX_CONNECTIONS = "maxConnections";
   /** @deprecated */
   @Deprecated
   public static final String PROP_DATASOURCE_VALIDATION_QUERY = "validationQuery";
   public static final String PROP_DATASOURCE_JNDI_URL = "jndiURL";
   public static final String PROP_DATASOURCE_JNDI_ALWAYS_LOOKUP = "jndiAlwaysLookup";
   public static final String PROP_DATASOURCE_JNDI_INITIAL = "java.naming.factory.initial";
   public static final String PROP_DATASOURCE_JNDI_PROVDER = "java.naming.provider.url";
   public static final String PROP_DATASOURCE_JNDI_PRINCIPAL = "java.naming.security.principal";
   public static final String PROP_DATASOURCE_JNDI_CREDENTIALS = "java.naming.security.credentials";
   public static final String PROP_PLUGIN_PREFIX = "org.quartz.plugin";
   public static final String PROP_PLUGIN_CLASS = "class";
   public static final String PROP_JOB_LISTENER_PREFIX = "org.quartz.jobListener";
   public static final String PROP_TRIGGER_LISTENER_PREFIX = "org.quartz.triggerListener";
   public static final String PROP_LISTENER_CLASS = "class";
   public static final String DEFAULT_INSTANCE_ID = "NON_CLUSTERED";
   public static final String AUTO_GENERATE_INSTANCE_ID = "AUTO";
   public static final String PROP_THREAD_EXECUTOR = "org.quartz.threadExecutor";
   public static final String PROP_THREAD_EXECUTOR_CLASS = "org.quartz.threadExecutor.class";
   public static final String SYSTEM_PROPERTY_AS_INSTANCE_ID = "SYS_PROP";
   public static final String MANAGEMENT_REST_SERVICE_ENABLED = "org.quartz.managementRESTService.enabled";
   public static final String MANAGEMENT_REST_SERVICE_HOST_PORT = "org.quartz.managementRESTService.bind";
   private SchedulerException initException = null;
   private String propSrc = null;
   private PropertiesParser cfg;
   private final Logger log = LoggerFactory.getLogger(this.getClass());

   public StdSchedulerFactory() {
   }

   public StdSchedulerFactory(Properties props) throws SchedulerException {
      this.initialize(props);
   }

   public StdSchedulerFactory(String fileName) throws SchedulerException {
      this.initialize(fileName);
   }

   public Logger getLog() {
      return this.log;
   }

   public void initialize() throws SchedulerException {
      if (this.cfg == null) {
         if (this.initException != null) {
            throw this.initException;
         } else {
            String requestedFile = System.getProperty("org.quartz.properties");
            String propFileName = requestedFile != null ? requestedFile : "quartz.properties";
            File propFile = new File(propFileName);
            Properties props = new Properties();
            Object in = null;

            try {
               if (propFile.exists()) {
                  try {
                     if (requestedFile != null) {
                        this.propSrc = "specified file: '" + requestedFile + "'";
                     } else {
                        this.propSrc = "default file in current working dir: 'quartz.properties'";
                     }

                     in = new BufferedInputStream(new FileInputStream(propFileName));
                     props.load((InputStream)in);
                  } catch (IOException var19) {
                     this.initException = new SchedulerException("Properties file: '" + propFileName + "' could not be read.", var19);
                     throw this.initException;
                  }
               } else if (requestedFile != null) {
                  in = Thread.currentThread().getContextClassLoader().getResourceAsStream(requestedFile);
                  if (in == null) {
                     this.initException = new SchedulerException("Properties file: '" + requestedFile + "' could not be found.");
                     throw this.initException;
                  }

                  this.propSrc = "specified file: '" + requestedFile + "' in the class resource path.";
                  in = new BufferedInputStream((InputStream)in);

                  try {
                     props.load((InputStream)in);
                  } catch (IOException var18) {
                     this.initException = new SchedulerException("Properties file: '" + requestedFile + "' could not be read.", var18);
                     throw this.initException;
                  }
               } else {
                  this.propSrc = "default resource file in Quartz package: 'quartz.properties'";
                  ClassLoader cl = this.getClass().getClassLoader();
                  if (cl == null) {
                     cl = this.findClassloader();
                  }

                  if (cl == null) {
                     throw new SchedulerConfigException("Unable to find a class loader on the current thread or class.");
                  }

                  in = cl.getResourceAsStream("quartz.properties");
                  if (in == null) {
                     in = cl.getResourceAsStream("/quartz.properties");
                  }

                  if (in == null) {
                     in = cl.getResourceAsStream("org/quartz/quartz.properties");
                  }

                  if (in == null) {
                     this.initException = new SchedulerException("Default quartz.properties not found in class path");
                     throw this.initException;
                  }

                  try {
                     props.load((InputStream)in);
                  } catch (IOException var17) {
                     this.initException = new SchedulerException("Resource properties file: 'org/quartz/quartz.properties' could not be read from the classpath.", var17);
                     throw this.initException;
                  }
               }
            } finally {
               if (in != null) {
                  try {
                     ((InputStream)in).close();
                  } catch (IOException var16) {
                  }
               }

            }

            this.initialize(this.overrideWithSysProps(props));
         }
      }
   }

   private Properties overrideWithSysProps(Properties props) {
      Properties sysProps = null;

      try {
         sysProps = System.getProperties();
      } catch (AccessControlException var4) {
         this.getLog().warn("Skipping overriding quartz properties with System properties during initialization because of an AccessControlException.  This is likely due to not having read/write access for java.util.PropertyPermission as required by java.lang.System.getProperties().  To resolve this warning, either add this permission to your policy file or use a non-default version of initialize().", var4);
      }

      if (sysProps != null) {
         props.putAll(sysProps);
      }

      return props;
   }

   public void initialize(String filename) throws SchedulerException {
      if (this.cfg == null) {
         if (this.initException != null) {
            throw this.initException;
         } else {
            InputStream is = null;
            Properties props = new Properties();
            is = Thread.currentThread().getContextClassLoader().getResourceAsStream(filename);

            try {
               if (is != null) {
                  is = new BufferedInputStream((InputStream)is);
                  this.propSrc = "the specified file : '" + filename + "' from the class resource path.";
               } else {
                  is = new BufferedInputStream(new FileInputStream(filename));
                  this.propSrc = "the specified file : '" + filename + "'";
               }

               props.load((InputStream)is);
            } catch (IOException var12) {
               this.initException = new SchedulerException("Properties file: '" + filename + "' could not be read.", var12);
               throw this.initException;
            } finally {
               if (is != null) {
                  try {
                     ((InputStream)is).close();
                  } catch (IOException var11) {
                  }
               }

            }

            this.initialize(props);
         }
      }
   }

   public void initialize(InputStream propertiesStream) throws SchedulerException {
      if (this.cfg == null) {
         if (this.initException != null) {
            throw this.initException;
         } else {
            Properties props = new Properties();
            if (propertiesStream != null) {
               try {
                  props.load(propertiesStream);
                  this.propSrc = "an externally opened InputStream.";
               } catch (IOException var4) {
                  this.initException = new SchedulerException("Error loading property data from InputStream", var4);
                  throw this.initException;
               }

               this.initialize(props);
            } else {
               this.initException = new SchedulerException("Error loading property data from InputStream - InputStream is null.");
               throw this.initException;
            }
         }
      }
   }

   public void initialize(Properties props) throws SchedulerException {
      if (this.propSrc == null) {
         this.propSrc = "an externally provided properties instance.";
      }

      this.cfg = new PropertiesParser(props);
   }

   private Scheduler instantiate() throws SchedulerException {
      if (this.cfg == null) {
         this.initialize();
      }

      if (this.initException != null) {
         throw this.initException;
      } else {
         JobStore js = null;
         ThreadPool tp = null;
         QuartzScheduler qs = null;
         DBConnectionManager dbMgr = null;
         String instanceIdGeneratorClass = null;
         Properties tProps = null;
         String userTXLocation = null;
         boolean wrapJobInTx = false;
         boolean autoId = false;
         long idleWaitTime = -1L;
         long dbFailureRetry = 15000L;
         SchedulerRepository schedRep = SchedulerRepository.getInstance();
         String schedName = this.cfg.getStringProperty("org.quartz.scheduler.instanceName", "QuartzScheduler");
         String threadName = this.cfg.getStringProperty("org.quartz.scheduler.threadName", schedName + "_QuartzSchedulerThread");
         String schedInstId = this.cfg.getStringProperty("org.quartz.scheduler.instanceId", "NON_CLUSTERED");
         if (schedInstId.equals("AUTO")) {
            autoId = true;
            instanceIdGeneratorClass = this.cfg.getStringProperty("org.quartz.scheduler.instanceIdGenerator.class", "org.quartz.simpl.SimpleInstanceIdGenerator");
         } else if (schedInstId.equals("SYS_PROP")) {
            autoId = true;
            instanceIdGeneratorClass = "org.quartz.simpl.SystemPropertyInstanceIdGenerator";
         }

         userTXLocation = this.cfg.getStringProperty("org.quartz.scheduler.userTransactionURL", userTXLocation);
         if (userTXLocation != null && userTXLocation.trim().length() == 0) {
            userTXLocation = null;
         }

         String classLoadHelperClass = this.cfg.getStringProperty("org.quartz.scheduler.classLoadHelper.class", "org.quartz.simpl.CascadingClassLoadHelper");
         wrapJobInTx = this.cfg.getBooleanProperty("org.quartz.scheduler.wrapJobExecutionInUserTransaction", wrapJobInTx);
         String jobFactoryClass = this.cfg.getStringProperty("org.quartz.scheduler.jobFactory.class", (String)null);
         idleWaitTime = this.cfg.getLongProperty("org.quartz.scheduler.idleWaitTime", idleWaitTime);
         if (idleWaitTime > -1L && idleWaitTime < 1000L) {
            throw new SchedulerException("org.quartz.scheduler.idleWaitTime of less than 1000ms is not legal.");
         } else {
            dbFailureRetry = this.cfg.getLongProperty("org.quartz.scheduler.dbFailureRetryInterval", dbFailureRetry);
            if (dbFailureRetry < 0L) {
               throw new SchedulerException("org.quartz.scheduler.dbFailureRetryInterval of less than 0 ms is not legal.");
            } else {
               boolean makeSchedulerThreadDaemon = this.cfg.getBooleanProperty("org.quartz.scheduler.makeSchedulerThreadDaemon");
               boolean threadsInheritInitalizersClassLoader = this.cfg.getBooleanProperty("org.quartz.scheduler.threadsInheritContextClassLoaderOfInitializer");
               boolean skipUpdateCheck = this.cfg.getBooleanProperty("org.quartz.scheduler.skipUpdateCheck", false);
               long batchTimeWindow = this.cfg.getLongProperty("org.quartz.scheduler.batchTriggerAcquisitionFireAheadTimeWindow", 0L);
               int maxBatchSize = this.cfg.getIntProperty("org.quartz.scheduler.batchTriggerAcquisitionMaxCount", 1);
               boolean interruptJobsOnShutdown = this.cfg.getBooleanProperty("org.quartz.scheduler.interruptJobsOnShutdown", false);
               boolean interruptJobsOnShutdownWithWait = this.cfg.getBooleanProperty("org.quartz.scheduler.interruptJobsOnShutdownWithWait", false);
               boolean jmxExport = this.cfg.getBooleanProperty("org.quartz.scheduler.jmx.export");
               String jmxObjectName = this.cfg.getStringProperty("org.quartz.scheduler.jmx.objectName");
               boolean jmxProxy = this.cfg.getBooleanProperty("org.quartz.scheduler.jmx.proxy");
               String jmxProxyClass = this.cfg.getStringProperty("org.quartz.scheduler.jmx.proxy.class");
               boolean rmiExport = this.cfg.getBooleanProperty("org.quartz.scheduler.rmi.export", false);
               boolean rmiProxy = this.cfg.getBooleanProperty("org.quartz.scheduler.rmi.proxy", false);
               String rmiHost = this.cfg.getStringProperty("org.quartz.scheduler.rmi.registryHost", "localhost");
               int rmiPort = this.cfg.getIntProperty("org.quartz.scheduler.rmi.registryPort", 1099);
               int rmiServerPort = this.cfg.getIntProperty("org.quartz.scheduler.rmi.serverPort", -1);
               String rmiCreateRegistry = this.cfg.getStringProperty("org.quartz.scheduler.rmi.createRegistry", "never");
               String rmiBindName = this.cfg.getStringProperty("org.quartz.scheduler.rmi.bindName");
               if (jmxProxy && rmiProxy) {
                  throw new SchedulerConfigException("Cannot proxy both RMI and JMX.");
               } else {
                  boolean managementRESTServiceEnabled = this.cfg.getBooleanProperty("org.quartz.managementRESTService.enabled", false);
                  String managementRESTServiceHostAndPort = this.cfg.getStringProperty("org.quartz.managementRESTService.bind", "0.0.0.0:9889");
                  Properties schedCtxtProps = this.cfg.getPropertyGroup("org.quartz.context.key", true);
                  if (rmiProxy) {
                     if (autoId) {
                        schedInstId = "NON_CLUSTERED";
                     }

                     String uid = rmiBindName == null ? QuartzSchedulerResources.getUniqueIdentifier(schedName, schedInstId) : rmiBindName;
                     RemoteScheduler remoteScheduler = new RemoteScheduler(uid, rmiHost, rmiPort);
                     schedRep.bind(remoteScheduler);
                     return remoteScheduler;
                  } else {
                     ClassLoadHelper loadHelper = null;

                     try {
                        loadHelper = (ClassLoadHelper)this.loadClass(classLoadHelperClass).newInstance();
                     } catch (Exception var89) {
                        throw new SchedulerConfigException("Unable to instantiate class load helper class: " + var89.getMessage(), var89);
                     }

                     loadHelper.initialize();
                     JobFactory jobFactory;
                     if (jmxProxy) {
                        if (autoId) {
                           schedInstId = "NON_CLUSTERED";
                        }

                        if (jmxProxyClass == null) {
                           throw new SchedulerConfigException("No JMX Proxy Scheduler class provided");
                        } else {
                           jobFactory = null;

                           RemoteMBeanScheduler jmxScheduler;
                           try {
                              jmxScheduler = (RemoteMBeanScheduler)loadHelper.loadClass(jmxProxyClass).newInstance();
                           } catch (Exception var66) {
                              throw new SchedulerConfigException("Unable to instantiate RemoteMBeanScheduler class.", var66);
                           }

                           if (jmxObjectName == null) {
                              jmxObjectName = QuartzSchedulerResources.generateJMXObjectName(schedName, schedInstId);
                           }

                           jmxScheduler.setSchedulerObjectName(jmxObjectName);
                           tProps = this.cfg.getPropertyGroup("org.quartz.scheduler.jmx.proxy", true);

                           try {
                              this.setBeanProps(jmxScheduler, tProps);
                           } catch (Exception var65) {
                              this.initException = new SchedulerException("RemoteMBeanScheduler class '" + jmxProxyClass + "' props could not be configured.", var65);
                              throw this.initException;
                           }

                           jmxScheduler.initialize();
                           schedRep.bind(jmxScheduler);
                           return jmxScheduler;
                        }
                     } else {
                        jobFactory = null;
                        if (jobFactoryClass != null) {
                           try {
                              jobFactory = (JobFactory)loadHelper.loadClass(jobFactoryClass).newInstance();
                           } catch (Exception var88) {
                              throw new SchedulerConfigException("Unable to instantiate JobFactory class: " + var88.getMessage(), var88);
                           }

                           tProps = this.cfg.getPropertyGroup("org.quartz.scheduler.jobFactory", true);

                           try {
                              this.setBeanProps(jobFactory, tProps);
                           } catch (Exception var87) {
                              this.initException = new SchedulerException("JobFactory class '" + jobFactoryClass + "' props could not be configured.", var87);
                              throw this.initException;
                           }
                        }

                        InstanceIdGenerator instanceIdGenerator = null;
                        if (instanceIdGeneratorClass != null) {
                           try {
                              instanceIdGenerator = (InstanceIdGenerator)loadHelper.loadClass(instanceIdGeneratorClass).newInstance();
                           } catch (Exception var86) {
                              throw new SchedulerConfigException("Unable to instantiate InstanceIdGenerator class: " + var86.getMessage(), var86);
                           }

                           tProps = this.cfg.getPropertyGroup("org.quartz.scheduler.instanceIdGenerator", true);

                           try {
                              this.setBeanProps(instanceIdGenerator, tProps);
                           } catch (Exception var85) {
                              this.initException = new SchedulerException("InstanceIdGenerator class '" + instanceIdGeneratorClass + "' props could not be configured.", var85);
                              throw this.initException;
                           }
                        }

                        String tpClass = this.cfg.getStringProperty("org.quartz.threadPool.class", SimpleThreadPool.class.getName());
                        if (tpClass == null) {
                           this.initException = new SchedulerException("ThreadPool class not specified. ");
                           throw this.initException;
                        } else {
                           try {
                              tp = (ThreadPool)loadHelper.loadClass(tpClass).newInstance();
                           } catch (Exception var84) {
                              this.initException = new SchedulerException("ThreadPool class '" + tpClass + "' could not be instantiated.", var84);
                              throw this.initException;
                           }

                           tProps = this.cfg.getPropertyGroup("org.quartz.threadPool", true);

                           try {
                              this.setBeanProps(tp, tProps);
                           } catch (Exception var83) {
                              this.initException = new SchedulerException("ThreadPool class '" + tpClass + "' props could not be configured.", var83);
                              throw this.initException;
                           }

                           String jsClass = this.cfg.getStringProperty("org.quartz.jobStore.class", RAMJobStore.class.getName());
                           if (jsClass == null) {
                              this.initException = new SchedulerException("JobStore class not specified. ");
                              throw this.initException;
                           } else {
                              try {
                                 js = (JobStore)loadHelper.loadClass(jsClass).newInstance();
                              } catch (Exception var82) {
                                 this.initException = new SchedulerException("JobStore class '" + jsClass + "' could not be instantiated.", var82);
                                 throw this.initException;
                              }

                              SchedulerDetailsSetter.setDetails(js, schedName, schedInstId);
                              tProps = this.cfg.getPropertyGroup("org.quartz.jobStore", true, new String[]{"org.quartz.jobStore.lockHandler"});

                              try {
                                 this.setBeanProps(js, tProps);
                              } catch (Exception var81) {
                                 this.initException = new SchedulerException("JobStore class '" + jsClass + "' props could not be configured.", var81);
                                 throw this.initException;
                              }

                              if (js instanceof JobStoreSupport) {
                                 String lockHandlerClass = this.cfg.getStringProperty("org.quartz.jobStore.lockHandler.class");
                                 if (lockHandlerClass != null) {
                                    try {
                                       Semaphore lockHandler = (Semaphore)loadHelper.loadClass(lockHandlerClass).newInstance();
                                       tProps = this.cfg.getPropertyGroup("org.quartz.jobStore.lockHandler", true);
                                       if (lockHandler instanceof TablePrefixAware) {
                                          tProps.setProperty("tablePrefix", ((JobStoreSupport)js).getTablePrefix());
                                          tProps.setProperty("schedName", schedName);
                                       }

                                       try {
                                          this.setBeanProps(lockHandler, tProps);
                                       } catch (Exception var79) {
                                          this.initException = new SchedulerException("JobStore LockHandler class '" + lockHandlerClass + "' props could not be configured.", var79);
                                          throw this.initException;
                                       }

                                       ((JobStoreSupport)js).setLockHandler(lockHandler);
                                       this.getLog().info("Using custom data access locking (synchronization): " + lockHandlerClass);
                                    } catch (Exception var80) {
                                       this.initException = new SchedulerException("JobStore LockHandler class '" + lockHandlerClass + "' could not be instantiated.", var80);
                                       throw this.initException;
                                    }
                                 }
                              }

                              String[] dsNames = this.cfg.getPropertyGroups("org.quartz.dataSource");

                              String dsJndiInitial;
                              String listenerClass;
                              String dsJndiCredentials;
                              JNDIConnectionProvider jrsf;
                              String plugInClass;
                              for(int i = 0; i < dsNames.length; ++i) {
                                 PropertiesParser pp = new PropertiesParser(this.cfg.getPropertyGroup("org.quartz.dataSource." + dsNames[i], true));
                                 String cpClass = pp.getStringProperty("connectionProvider.class", (String)null);
                                 String dsJndi;
                                 if (cpClass != null) {
                                    dsJndi = null;

                                    ConnectionProvider cp;
                                    try {
                                       cp = (ConnectionProvider)loadHelper.loadClass(cpClass).newInstance();
                                    } catch (Exception var78) {
                                       this.initException = new SchedulerException("ConnectionProvider class '" + cpClass + "' could not be instantiated.", var78);
                                       throw this.initException;
                                    }

                                    try {
                                       pp.getUnderlyingProperties().remove("connectionProvider.class");
                                       this.setBeanProps(cp, pp.getUnderlyingProperties());
                                       cp.initialize();
                                    } catch (Exception var77) {
                                       this.initException = new SchedulerException("ConnectionProvider class '" + cpClass + "' props could not be configured.", var77);
                                       throw this.initException;
                                    }

                                    dbMgr = DBConnectionManager.getInstance();
                                    dbMgr.addConnectionProvider(dsNames[i], cp);
                                 } else {
                                    dsJndi = pp.getStringProperty("jndiURL", (String)null);
                                    if (dsJndi == null) {
                                       plugInClass = pp.getStringProperty("driver");
                                       dsJndiInitial = pp.getStringProperty("URL");
                                       if (plugInClass == null) {
                                          this.initException = new SchedulerException("Driver not specified for DataSource: " + dsNames[i]);
                                          throw this.initException;
                                       }

                                       if (dsJndiInitial == null) {
                                          this.initException = new SchedulerException("DB URL not specified for DataSource: " + dsNames[i]);
                                          throw this.initException;
                                       }

                                       try {
                                          PoolingConnectionProvider cp = new PoolingConnectionProvider(pp.getUnderlyingProperties());
                                          dbMgr = DBConnectionManager.getInstance();
                                          dbMgr.addConnectionProvider(dsNames[i], cp);
                                       } catch (SQLException var76) {
                                          this.initException = new SchedulerException("Could not initialize DataSource: " + dsNames[i], var76);
                                          throw this.initException;
                                       }
                                    } else {
                                       boolean dsAlwaysLookup = pp.getBooleanProperty("jndiAlwaysLookup");
                                       dsJndiInitial = pp.getStringProperty("java.naming.factory.initial");
                                       String dsJndiProvider = pp.getStringProperty("java.naming.provider.url");
                                       listenerClass = pp.getStringProperty("java.naming.security.principal");
                                       dsJndiCredentials = pp.getStringProperty("java.naming.security.credentials");
                                       Properties props = null;
                                       if (null != dsJndiInitial || null != dsJndiProvider || null != listenerClass || null != dsJndiCredentials) {
                                          props = new Properties();
                                          if (dsJndiInitial != null) {
                                             props.put("java.naming.factory.initial", dsJndiInitial);
                                          }

                                          if (dsJndiProvider != null) {
                                             props.put("java.naming.provider.url", dsJndiProvider);
                                          }

                                          if (listenerClass != null) {
                                             props.put("java.naming.security.principal", listenerClass);
                                          }

                                          if (dsJndiCredentials != null) {
                                             props.put("java.naming.security.credentials", dsJndiCredentials);
                                          }
                                       }

                                       jrsf = new JNDIConnectionProvider(dsJndi, props, dsAlwaysLookup);
                                       dbMgr = DBConnectionManager.getInstance();
                                       dbMgr.addConnectionProvider(dsNames[i], jrsf);
                                    }
                                 }
                              }

                              String[] pluginNames = this.cfg.getPropertyGroups("org.quartz.plugin");
                              SchedulerPlugin[] plugins = new SchedulerPlugin[pluginNames.length];

                              for(int i = 0; i < pluginNames.length; ++i) {
                                 Properties pp = this.cfg.getPropertyGroup("org.quartz.plugin." + pluginNames[i], true);
                                 plugInClass = pp.getProperty("class", (String)null);
                                 if (plugInClass == null) {
                                    this.initException = new SchedulerException("SchedulerPlugin class not specified for plugin '" + pluginNames[i] + "'");
                                    throw this.initException;
                                 }

                                 dsJndiInitial = null;

                                 SchedulerPlugin plugin;
                                 try {
                                    plugin = (SchedulerPlugin)loadHelper.loadClass(plugInClass).newInstance();
                                 } catch (Exception var75) {
                                    this.initException = new SchedulerException("SchedulerPlugin class '" + plugInClass + "' could not be instantiated.", var75);
                                    throw this.initException;
                                 }

                                 try {
                                    this.setBeanProps(plugin, pp);
                                 } catch (Exception var74) {
                                    this.initException = new SchedulerException("JobStore SchedulerPlugin '" + plugInClass + "' props could not be configured.", var74);
                                    throw this.initException;
                                 }

                                 plugins[i] = plugin;
                              }

                              Class<?>[] strArg = new Class[]{String.class};
                              String[] jobListenerNames = this.cfg.getPropertyGroups("org.quartz.jobListener");
                              JobListener[] jobListeners = new JobListener[jobListenerNames.length];

                              for(int i = 0; i < jobListenerNames.length; ++i) {
                                 Properties lp = this.cfg.getPropertyGroup("org.quartz.jobListener." + jobListenerNames[i], true);
                                 listenerClass = lp.getProperty("class", (String)null);
                                 if (listenerClass == null) {
                                    this.initException = new SchedulerException("JobListener class not specified for listener '" + jobListenerNames[i] + "'");
                                    throw this.initException;
                                 }

                                 dsJndiCredentials = null;

                                 JobListener listener;
                                 try {
                                    listener = (JobListener)loadHelper.loadClass(listenerClass).newInstance();
                                 } catch (Exception var73) {
                                    this.initException = new SchedulerException("JobListener class '" + listenerClass + "' could not be instantiated.", var73);
                                    throw this.initException;
                                 }

                                 try {
                                    Method nameSetter = null;

                                    try {
                                       nameSetter = listener.getClass().getMethod("setName", strArg);
                                    } catch (NoSuchMethodException var72) {
                                    }

                                    if (nameSetter != null) {
                                       nameSetter.invoke(listener, jobListenerNames[i]);
                                    }

                                    this.setBeanProps(listener, lp);
                                 } catch (Exception var94) {
                                    this.initException = new SchedulerException("JobListener '" + listenerClass + "' props could not be configured.", var94);
                                    throw this.initException;
                                 }

                                 jobListeners[i] = listener;
                              }

                              String[] triggerListenerNames = this.cfg.getPropertyGroups("org.quartz.triggerListener");
                              TriggerListener[] triggerListeners = new TriggerListener[triggerListenerNames.length];

                              String listenerClass;
                              for(int i = 0; i < triggerListenerNames.length; ++i) {
                                 Properties lp = this.cfg.getPropertyGroup("org.quartz.triggerListener." + triggerListenerNames[i], true);
                                 listenerClass = lp.getProperty("class", (String)null);
                                 if (listenerClass == null) {
                                    this.initException = new SchedulerException("TriggerListener class not specified for listener '" + triggerListenerNames[i] + "'");
                                    throw this.initException;
                                 }

                                 jrsf = null;

                                 TriggerListener listener;
                                 try {
                                    listener = (TriggerListener)loadHelper.loadClass(listenerClass).newInstance();
                                 } catch (Exception var71) {
                                    this.initException = new SchedulerException("TriggerListener class '" + listenerClass + "' could not be instantiated.", var71);
                                    throw this.initException;
                                 }

                                 try {
                                    Method nameSetter = null;

                                    try {
                                       nameSetter = listener.getClass().getMethod("setName", strArg);
                                    } catch (NoSuchMethodException var70) {
                                    }

                                    if (nameSetter != null) {
                                       nameSetter.invoke(listener, triggerListenerNames[i]);
                                    }

                                    this.setBeanProps(listener, lp);
                                 } catch (Exception var93) {
                                    this.initException = new SchedulerException("TriggerListener '" + listenerClass + "' props could not be configured.", var93);
                                    throw this.initException;
                                 }

                                 triggerListeners[i] = listener;
                              }

                              boolean tpInited = false;
                              boolean qsInited = false;
                              listenerClass = this.cfg.getStringProperty("org.quartz.threadExecutor.class");
                              Object threadExecutor;
                              if (listenerClass != null) {
                                 tProps = this.cfg.getPropertyGroup("org.quartz.threadExecutor", true);

                                 try {
                                    threadExecutor = (ThreadExecutor)loadHelper.loadClass(listenerClass).newInstance();
                                    this.log.info("Using custom implementation for ThreadExecutor: " + listenerClass);
                                    this.setBeanProps(threadExecutor, tProps);
                                 } catch (Exception var69) {
                                    this.initException = new SchedulerException("ThreadExecutor class '" + listenerClass + "' could not be instantiated.", var69);
                                    throw this.initException;
                                 }
                              } else {
                                 this.log.info("Using default implementation for ThreadExecutor");
                                 threadExecutor = new DefaultThreadExecutor();
                              }

                              try {
                                 jrsf = null;
                                 if (userTXLocation != null) {
                                    UserTransactionHelper.setUserTxLocation(userTXLocation);
                                 }

                                 Object jrsf;
                                 if (wrapJobInTx) {
                                    jrsf = new JTAJobRunShellFactory();
                                 } else {
                                    jrsf = new JTAAnnotationAwareJobRunShellFactory();
                                 }

                                 if (autoId) {
                                    try {
                                       schedInstId = "NON_CLUSTERED";
                                       if (js.isClustered()) {
                                          schedInstId = instanceIdGenerator.generateInstanceId();
                                       }
                                    } catch (Exception var68) {
                                       this.getLog().error("Couldn't generate instance Id!", var68);
                                       throw new IllegalStateException("Cannot run without an instance id.");
                                    }
                                 }

                                 if (js.getClass().getName().startsWith("org.terracotta.quartz")) {
                                    try {
                                       String uuid = (String)js.getClass().getMethod("getUUID").invoke(js);
                                       if (schedInstId.equals("NON_CLUSTERED")) {
                                          schedInstId = "TERRACOTTA_CLUSTERED,node=" + uuid;
                                          if (jmxObjectName == null) {
                                             jmxObjectName = QuartzSchedulerResources.generateJMXObjectName(schedName, schedInstId);
                                          }
                                       } else if (jmxObjectName == null) {
                                          jmxObjectName = QuartzSchedulerResources.generateJMXObjectName(schedName, schedInstId + ",node=" + uuid);
                                       }
                                    } catch (Exception var67) {
                                       throw new RuntimeException("Problem obtaining node id from TerracottaJobStore.", var67);
                                    }

                                    if (null == this.cfg.getStringProperty("org.quartz.scheduler.jmx.export")) {
                                       jmxExport = true;
                                    }
                                 }

                                 if (js instanceof JobStoreSupport) {
                                    JobStoreSupport jjs = (JobStoreSupport)js;
                                    jjs.setDbRetryInterval(dbFailureRetry);
                                    if (threadsInheritInitalizersClassLoader) {
                                       jjs.setThreadsInheritInitializersClassLoadContext(threadsInheritInitalizersClassLoader);
                                    }

                                    jjs.setThreadExecutor((ThreadExecutor)threadExecutor);
                                 }

                                 QuartzSchedulerResources rsrcs = new QuartzSchedulerResources();
                                 rsrcs.setName(schedName);
                                 rsrcs.setThreadName(threadName);
                                 rsrcs.setInstanceId(schedInstId);
                                 rsrcs.setJobRunShellFactory((JobRunShellFactory)jrsf);
                                 rsrcs.setMakeSchedulerThreadDaemon(makeSchedulerThreadDaemon);
                                 rsrcs.setThreadsInheritInitializersClassLoadContext(threadsInheritInitalizersClassLoader);
                                 rsrcs.setRunUpdateCheck(!skipUpdateCheck);
                                 rsrcs.setBatchTimeWindow(batchTimeWindow);
                                 rsrcs.setMaxBatchSize(maxBatchSize);
                                 rsrcs.setInterruptJobsOnShutdown(interruptJobsOnShutdown);
                                 rsrcs.setInterruptJobsOnShutdownWithWait(interruptJobsOnShutdownWithWait);
                                 rsrcs.setJMXExport(jmxExport);
                                 rsrcs.setJMXObjectName(jmxObjectName);
                                 if (managementRESTServiceEnabled) {
                                    ManagementRESTServiceConfiguration managementRESTServiceConfiguration = new ManagementRESTServiceConfiguration();
                                    managementRESTServiceConfiguration.setBind(managementRESTServiceHostAndPort);
                                    managementRESTServiceConfiguration.setEnabled(managementRESTServiceEnabled);
                                    rsrcs.setManagementRESTServiceConfiguration(managementRESTServiceConfiguration);
                                 }

                                 if (rmiExport) {
                                    rsrcs.setRMIRegistryHost(rmiHost);
                                    rsrcs.setRMIRegistryPort(rmiPort);
                                    rsrcs.setRMIServerPort(rmiServerPort);
                                    rsrcs.setRMICreateRegistryStrategy(rmiCreateRegistry);
                                    rsrcs.setRMIBindName(rmiBindName);
                                 }

                                 SchedulerDetailsSetter.setDetails(tp, schedName, schedInstId);
                                 rsrcs.setThreadExecutor((ThreadExecutor)threadExecutor);
                                 ((ThreadExecutor)threadExecutor).initialize();
                                 rsrcs.setThreadPool(tp);
                                 if (tp instanceof SimpleThreadPool && threadsInheritInitalizersClassLoader) {
                                    ((SimpleThreadPool)tp).setThreadsInheritContextClassLoaderOfInitializingThread(threadsInheritInitalizersClassLoader);
                                 }

                                 tp.initialize();
                                 tpInited = true;
                                 rsrcs.setJobStore(js);

                                 for(int i = 0; i < plugins.length; ++i) {
                                    rsrcs.addSchedulerPlugin(plugins[i]);
                                 }

                                 qs = new QuartzScheduler(rsrcs, idleWaitTime, dbFailureRetry);
                                 qsInited = true;
                                 Scheduler scheduler = this.instantiate(rsrcs, qs);
                                 if (jobFactory != null) {
                                    qs.setJobFactory(jobFactory);
                                 }

                                 int i;
                                 for(i = 0; i < plugins.length; ++i) {
                                    plugins[i].initialize(pluginNames[i], scheduler, loadHelper);
                                 }

                                 for(i = 0; i < jobListeners.length; ++i) {
                                    qs.getListenerManager().addJobListener(jobListeners[i], (Matcher)EverythingMatcher.allJobs());
                                 }

                                 for(i = 0; i < triggerListeners.length; ++i) {
                                    qs.getListenerManager().addTriggerListener(triggerListeners[i], (Matcher)EverythingMatcher.allTriggers());
                                 }

                                 Iterator i$ = schedCtxtProps.keySet().iterator();

                                 while(i$.hasNext()) {
                                    Object key = i$.next();
                                    String val = schedCtxtProps.getProperty((String)key);
                                    scheduler.getContext().put((String)key, val);
                                 }

                                 js.setInstanceId(schedInstId);
                                 js.setInstanceName(schedName);
                                 js.setThreadPoolSize(tp.getPoolSize());
                                 js.initialize(loadHelper, qs.getSchedulerSignaler());
                                 ((JobRunShellFactory)jrsf).initialize(scheduler);
                                 qs.initialize();
                                 this.getLog().info("Quartz scheduler '" + scheduler.getSchedulerName() + "' initialized from " + this.propSrc);
                                 this.getLog().info("Quartz scheduler version: " + qs.getVersion());
                                 qs.addNoGCObject(schedRep);
                                 if (dbMgr != null) {
                                    qs.addNoGCObject(dbMgr);
                                 }

                                 schedRep.bind(scheduler);
                                 return scheduler;
                              } catch (SchedulerException var90) {
                                 this.shutdownFromInstantiateException(tp, qs, tpInited, qsInited);
                                 throw var90;
                              } catch (RuntimeException var91) {
                                 this.shutdownFromInstantiateException(tp, qs, tpInited, qsInited);
                                 throw var91;
                              } catch (Error var92) {
                                 this.shutdownFromInstantiateException(tp, qs, tpInited, qsInited);
                                 throw var92;
                              }
                           }
                        }
                     }
                  }
               }
            }
         }
      }
   }

   private void shutdownFromInstantiateException(ThreadPool tp, QuartzScheduler qs, boolean tpInited, boolean qsInited) {
      try {
         if (qsInited) {
            qs.shutdown(false);
         } else if (tpInited) {
            tp.shutdown(false);
         }
      } catch (Exception var6) {
         this.getLog().error("Got another exception while shutting down after instantiation exception", var6);
      }

   }

   protected Scheduler instantiate(QuartzSchedulerResources rsrcs, QuartzScheduler qs) {
      Scheduler scheduler = new StdScheduler(qs);
      return scheduler;
   }

   private void setBeanProps(Object obj, Properties props) throws NoSuchMethodException, IllegalAccessException, InvocationTargetException, IntrospectionException, SchedulerConfigException {
      props.remove("class");
      BeanInfo bi = Introspector.getBeanInfo(obj.getClass());
      PropertyDescriptor[] propDescs = bi.getPropertyDescriptors();
      PropertiesParser pp = new PropertiesParser(props);
      Enumeration keys = props.keys();

      while(keys.hasMoreElements()) {
         String name = (String)keys.nextElement();
         String c = name.substring(0, 1).toUpperCase(Locale.US);
         String methName = "set" + c + name.substring(1);
         Method setMeth = this.getSetMethod(methName, propDescs);

         try {
            if (setMeth == null) {
               throw new NoSuchMethodException("No setter for property '" + name + "'");
            }

            Class<?>[] params = setMeth.getParameterTypes();
            if (params.length != 1) {
               throw new NoSuchMethodException("No 1-argument setter for property '" + name + "'");
            }

            PropertiesParser refProps = pp;
            String refName = pp.getStringProperty(name);
            if (refName != null && refName.startsWith("$@")) {
               refName = refName.substring(2);
               refProps = this.cfg;
            } else {
               refName = name;
            }

            if (params[0].equals(Integer.TYPE)) {
               setMeth.invoke(obj, refProps.getIntProperty(refName));
            } else if (params[0].equals(Long.TYPE)) {
               setMeth.invoke(obj, refProps.getLongProperty(refName));
            } else if (params[0].equals(Float.TYPE)) {
               setMeth.invoke(obj, refProps.getFloatProperty(refName));
            } else if (params[0].equals(Double.TYPE)) {
               setMeth.invoke(obj, refProps.getDoubleProperty(refName));
            } else if (params[0].equals(Boolean.TYPE)) {
               setMeth.invoke(obj, refProps.getBooleanProperty(refName));
            } else {
               if (!params[0].equals(String.class)) {
                  throw new NoSuchMethodException("No primitive-type setter for property '" + name + "'");
               }

               setMeth.invoke(obj, refProps.getStringProperty(refName));
            }
         } catch (NumberFormatException var14) {
            throw new SchedulerConfigException("Could not parse property '" + name + "' into correct data type: " + var14.toString());
         }
      }

   }

   private Method getSetMethod(String name, PropertyDescriptor[] props) {
      for(int i = 0; i < props.length; ++i) {
         Method wMeth = props[i].getWriteMethod();
         if (wMeth != null && wMeth.getName().equals(name)) {
            return wMeth;
         }
      }

      return null;
   }

   private Class<?> loadClass(String className) throws ClassNotFoundException, SchedulerConfigException {
      try {
         ClassLoader cl = this.findClassloader();
         if (cl != null) {
            return cl.loadClass(className);
         } else {
            throw new SchedulerConfigException("Unable to find a class loader on the current thread or class.");
         }
      } catch (ClassNotFoundException var3) {
         if (this.getClass().getClassLoader() != null) {
            return this.getClass().getClassLoader().loadClass(className);
         } else {
            throw var3;
         }
      }
   }

   private ClassLoader findClassloader() {
      if (Thread.currentThread().getContextClassLoader() == null && this.getClass().getClassLoader() != null) {
         Thread.currentThread().setContextClassLoader(this.getClass().getClassLoader());
      }

      return Thread.currentThread().getContextClassLoader();
   }

   private String getSchedulerName() {
      return this.cfg.getStringProperty("org.quartz.scheduler.instanceName", "QuartzScheduler");
   }

   public Scheduler getScheduler() throws SchedulerException {
      if (this.cfg == null) {
         this.initialize();
      }

      SchedulerRepository schedRep = SchedulerRepository.getInstance();
      Scheduler sched = schedRep.lookup(this.getSchedulerName());
      if (sched != null) {
         if (!sched.isShutdown()) {
            return sched;
         }

         schedRep.remove(this.getSchedulerName());
      }

      sched = this.instantiate();
      return sched;
   }

   public static Scheduler getDefaultScheduler() throws SchedulerException {
      StdSchedulerFactory fact = new StdSchedulerFactory();
      return fact.getScheduler();
   }

   public Scheduler getScheduler(String schedName) throws SchedulerException {
      return SchedulerRepository.getInstance().lookup(schedName);
   }

   public Collection<Scheduler> getAllSchedulers() throws SchedulerException {
      return SchedulerRepository.getInstance().lookupAll();
   }
}
