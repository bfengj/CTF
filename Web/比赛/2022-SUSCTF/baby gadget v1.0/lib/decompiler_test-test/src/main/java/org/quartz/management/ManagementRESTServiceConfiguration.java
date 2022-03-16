package org.quartz.management;

public class ManagementRESTServiceConfiguration {
   public static final String DEFAULT_BIND = "0.0.0.0:9888";
   public static final int DEFAULT_SECURITY_SVC_TIMEOUT = 5000;
   private volatile boolean enabled = false;
   private volatile String securityServiceLocation;
   private volatile int securityServiceTimeout = 5000;
   private volatile String bind = "0.0.0.0:9888";

   public boolean isEnabled() {
      return this.enabled;
   }

   public void setEnabled(boolean enabled) {
      this.enabled = enabled;
   }

   public String getSecurityServiceLocation() {
      return this.securityServiceLocation;
   }

   public void setSecurityServiceLocation(String securityServiceURL) {
      this.securityServiceLocation = securityServiceURL;
   }

   public int getSecurityServiceTimeout() {
      return this.securityServiceTimeout;
   }

   public void setSecurityServiceTimeout(int securityServiceTimeout) {
      this.securityServiceTimeout = securityServiceTimeout;
   }

   public String getBind() {
      return this.bind;
   }

   public String getHost() {
      return this.bind == null ? null : this.bind.split("\\:")[0];
   }

   public int getPort() {
      if (this.bind == null) {
         return -1;
      } else {
         String[] split = this.bind.split("\\:");
         if (split.length != 2) {
            throw new IllegalArgumentException("invalid bind format (should be IP:port)");
         } else {
            return Integer.parseInt(split[1]);
         }
      }
   }

   public void setBind(String bind) {
      this.bind = bind;
   }
}
