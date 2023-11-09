###############################################################################
# Copyright (c) 2014 IBM Corporation and others.
# All rights reserved. This program and the accompanying materials
# are made available under the terms of the Eclipse Public License v1.0
# which accompanies this distribution, and is available at
# http://www.eclipse.org/legal/epl-v10.html
# 
# Contributors:
#     IBM Corporation - initial API and implementation
###############################################################################
org.osgi.framework.system.packages = \
 javax.crypto.interfaces,\
 javax.crypto.spec,\
 javax.net,\
 javax.net.ssl,\
 javax.rmi.ssl,\
 javax.script,\
 javax.security.auth,\
 javax.security.auth.callback,\
 javax.security.auth.login,\
 javax.security.auth.spi,\
 javax.security.auth.x500,\
 javax.security.cert,\
 javax.sql,\
 javax.transaction,\
 javax.transaction.xa,\
 javax.xml,\
 javax.xml.datatype,\
 javax.xml.namespace,\
 javax.xml.parsers,\
 javax.xml.stream,\
 javax.xml.stream.events,\
 javax.xml.stream.util,\
 javax.xml.transform,\
 javax.xml.transform.dom,\
 javax.xml.transform.sax,\
 javax.xml.transform.stax,\
 javax.xml.transform.stream,\
 javax.xml.validation,\
 javax.xml.xpath,\
 org.w3c.dom,\
 org.w3c.dom.bootstrap,\
 org.w3c.dom.events,\
 org.w3c.dom.ls,\
 org.xml.sax,\
 org.xml.sax.ext,\
 org.xml.sax.helpers
org.osgi.framework.executionenvironment = \
 OSGi/Minimum-1.0,\
 OSGi/Minimum-1.1,\
 OSGi/Minimum-1.2,\
 JavaSE/compact1-1.8,\
 JavaSE/compact2-1.8
org.osgi.framework.system.capabilities = \
 osgi.ee; osgi.ee="OSGi/Minimum"; version:List<Version>="1.0, 1.1, 1.2",\
 osgi.ee; osgi.ee="JavaSE/compact1"; version:List<Version>="1.8",\
 osgi.ee; osgi.ee="JavaSE/compact2"; version:List<Version>="1.8"
osgi.java.profile.name = JavaSE/compact2-1.8
org.eclipse.jdt.core.compiler.compliance=1.8
org.eclipse.jdt.core.compiler.source=1.8
org.eclipse.jdt.core.compiler.codegen.inlineJsrBytecode=enabled
org.eclipse.jdt.core.compiler.codegen.targetPlatform=1.8
org.eclipse.jdt.core.compiler.problem.assertIdentifier=error
org.eclipse.jdt.core.compiler.problem.enumIdentifier=error
