FROM tomcat:alpine
RUN rm -rf /usr/local/tomcat/webapps/* && rm -rf /usr/local/tomcat/work && sed -i '1a CATALINA_OPTS="$CATALINA_OPTS -javaagent:/usr/local/tomcat/webapps/javaAgentLearn-1.0-SNAPSHOT-jar-with-dependencies.jar"; export CATALINA_OPTS' /usr/local/tomcat/bin/catalina.sh
COPY javaAgentLearn-1.0-SNAPSHOT-jar-with-dependencies.jar ROOT.war /usr/local/tomcat/webapps/
ENV ffl4444gg=e2zzzz5h333ll
USER nobody
WORKDIR /usr/local/tomcat/bin
EXPOSE 8080

