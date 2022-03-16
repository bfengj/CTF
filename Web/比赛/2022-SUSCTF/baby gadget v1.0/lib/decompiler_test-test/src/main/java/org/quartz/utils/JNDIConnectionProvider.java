package org.quartz.utils;

import java.sql.Connection;
import java.sql.SQLException;
import java.util.Properties;
import javax.naming.InitialContext;
import javax.sql.DataSource;
import javax.sql.XADataSource;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

public class JNDIConnectionProvider implements ConnectionProvider {
   private String url;
   private Properties props;
   private Object datasource;
   private boolean alwaysLookup = false;
   private final Logger log = LoggerFactory.getLogger(this.getClass());

   public JNDIConnectionProvider(String jndiUrl, boolean alwaysLookup) {
      this.url = jndiUrl;
      this.alwaysLookup = alwaysLookup;
      this.init();
   }

   public JNDIConnectionProvider(String jndiUrl, Properties jndiProps, boolean alwaysLookup) {
      this.url = jndiUrl;
      this.props = jndiProps;
      this.alwaysLookup = alwaysLookup;
      this.init();
   }

   protected Logger getLog() {
      return this.log;
   }

   private void init() {
      if (!this.isAlwaysLookup()) {
         InitialContext ctx = null;

         try {
            ctx = this.props != null ? new InitialContext(this.props) : new InitialContext();
            this.datasource = (DataSource)ctx.lookup(this.url);
         } catch (Exception var11) {
            this.getLog().error("Error looking up datasource: " + var11.getMessage(), var11);
         } finally {
            if (ctx != null) {
               try {
                  ctx.close();
               } catch (Exception var10) {
               }
            }

         }
      }

   }

   public Connection getConnection() throws SQLException {
      InitialContext ctx = null;

      Connection var3;
      try {
         Object ds = this.datasource;
         if (ds == null || this.isAlwaysLookup()) {
            ctx = this.props != null ? new InitialContext(this.props) : new InitialContext();
            ds = ctx.lookup(this.url);
            if (!this.isAlwaysLookup()) {
               this.datasource = ds;
            }
         }

         if (ds == null) {
            throw new SQLException("There is no object at the JNDI URL '" + this.url + "'");
         }

         if (!(ds instanceof XADataSource)) {
            if (ds instanceof DataSource) {
               var3 = ((DataSource)ds).getConnection();
               return var3;
            }

            throw new SQLException("Object at JNDI URL '" + this.url + "' is not a DataSource.");
         }

         var3 = ((XADataSource)ds).getXAConnection().getConnection();
      } catch (Exception var13) {
         this.datasource = null;
         throw new SQLException("Could not retrieve datasource via JNDI url '" + this.url + "' " + var13.getClass().getName() + ": " + var13.getMessage());
      } finally {
         if (ctx != null) {
            try {
               ctx.close();
            } catch (Exception var12) {
            }
         }

      }

      return var3;
   }

   public boolean isAlwaysLookup() {
      return this.alwaysLookup;
   }

   public void setAlwaysLookup(boolean b) {
      this.alwaysLookup = b;
   }

   public void shutdown() throws SQLException {
   }

   public void initialize() throws SQLException {
   }
}
