package org.terracotta.quartz;

import java.io.IOException;
import java.io.InputStream;
import java.io.UnsupportedEncodingException;
import java.net.InetAddress;
import java.net.MalformedURLException;
import java.net.URL;
import java.net.URLConnection;
import java.net.URLEncoder;
import java.util.Properties;
import java.util.TimerTask;
import org.quartz.core.QuartzScheduler;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

public class UpdateChecker extends TimerTask {
   private static final Logger LOG = LoggerFactory.getLogger(UpdateChecker.class);
   private static final long MILLIS_PER_SECOND = 1000L;
   private static final String UNKNOWN = "UNKNOWN";
   private static final String UPDATE_CHECK_URL = "http://www.terracotta.org/kit/reflector?kitID=quartz&pageID=update.properties";
   private static final long START_TIME = System.currentTimeMillis();
   private static final String PRODUCT_NAME = "Quartz Terracotta";

   public void run() {
      this.checkForUpdate();
   }

   public void checkForUpdate() {
      try {
         if (!Boolean.getBoolean("org.terracotta.quartz.skipUpdateCheck")) {
            this.doCheck();
         }
      } catch (Throwable var2) {
         LOG.debug("Update check failed: " + var2.toString());
      }

   }

   private void doCheck() throws IOException {
      LOG.debug("Checking for update...");
      URL updateUrl = this.buildUpdateCheckUrl();
      Properties updateProps = this.getUpdateProperties(updateUrl);
      String currentVersion = this.getQuartzVersion();
      String propVal = updateProps.getProperty("general.notice");
      if (this.notBlank(propVal)) {
         LOG.info(propVal);
      }

      propVal = updateProps.getProperty(currentVersion + ".notices");
      if (this.notBlank(propVal)) {
         LOG.info(propVal);
      }

      propVal = updateProps.getProperty(currentVersion + ".updates");
      if (this.notBlank(propVal)) {
         StringBuilder sb = new StringBuilder();
         String[] newVersions = propVal.split(",");

         for(int i = 0; i < newVersions.length; ++i) {
            String newVersion = newVersions[i].trim();
            if (i > 0) {
               sb.append(", ");
            }

            sb.append(newVersion);
            propVal = updateProps.getProperty(newVersion + ".release-notes");
            if (this.notBlank(propVal)) {
               sb.append(" [");
               sb.append(propVal);
               sb.append("]");
            }
         }

         if (sb.length() > 0) {
            LOG.info("New update(s) found: " + sb.toString());
         }
      }

   }

   private String getQuartzVersion() {
      return String.format("%s.%s.%s", QuartzScheduler.getVersionMajor(), QuartzScheduler.getVersionMinor(), QuartzScheduler.getVersionIteration());
   }

   private Properties getUpdateProperties(URL updateUrl) throws IOException {
      URLConnection connection = updateUrl.openConnection();
      InputStream in = connection.getInputStream();

      Properties var5;
      try {
         Properties props = new Properties();
         props.load(connection.getInputStream());
         var5 = props;
      } finally {
         if (in != null) {
            in.close();
         }

      }

      return var5;
   }

   private URL buildUpdateCheckUrl() throws MalformedURLException, UnsupportedEncodingException {
      String url = System.getProperty("quartz.update-check.url", "http://www.terracotta.org/kit/reflector?kitID=quartz&pageID=update.properties");
      String connector = url.indexOf(63) > 0 ? "&" : "?";
      return new URL(url + connector + this.buildParamsString());
   }

   private String buildParamsString() throws UnsupportedEncodingException {
      StringBuilder sb = new StringBuilder();
      sb.append("id=");
      sb.append(this.getClientId());
      sb.append("&os-name=");
      sb.append(this.urlEncode(this.getProperty("os.name")));
      sb.append("&jvm-name=");
      sb.append(this.urlEncode(this.getProperty("java.vm.name")));
      sb.append("&jvm-version=");
      sb.append(this.urlEncode(this.getProperty("java.version")));
      sb.append("&platform=");
      sb.append(this.urlEncode(this.getProperty("os.arch")));
      sb.append("&tc-version=");
      sb.append(this.urlEncode(this.getQuartzVersion()));
      sb.append("&tc-product=");
      sb.append(this.urlEncode("Quartz Terracotta"));
      sb.append("&source=");
      sb.append(this.urlEncode("Quartz Terracotta"));
      sb.append("&uptime-secs=");
      sb.append(this.getUptimeInSeconds());
      sb.append("&patch=");
      sb.append(this.urlEncode("UNKNOWN"));
      return sb.toString();
   }

   private long getUptimeInSeconds() {
      long uptime = System.currentTimeMillis() - START_TIME;
      return uptime > 0L ? uptime / 1000L : 0L;
   }

   private int getClientId() {
      try {
         return InetAddress.getLocalHost().hashCode();
      } catch (Throwable var2) {
         return 0;
      }
   }

   private String urlEncode(String param) throws UnsupportedEncodingException {
      return URLEncoder.encode(param, "UTF-8");
   }

   private String getProperty(String prop) {
      return System.getProperty(prop, "UNKNOWN");
   }

   private boolean notBlank(String s) {
      return s != null && s.trim().length() > 0;
   }

   public static void main(String[] args) {
      (new UpdateChecker()).run();
   }
}
