###############################################################################
# Copyright (c) 2003, 2012 IBM Corporation and others.
# All rights reserved. This program and the accompanying materials
# are made available under the terms of the Eclipse Public License v1.0
# which accompanies this distribution, and is available at
# http://www.eclipse.org/legal/epl-v10.html
# 
# Contributors:
#     IBM Corporation - initial API and implementation
###############################################################################
org.osgi.framework.system.packages = \
 javax.accessibility,\
 javax.swing,\
 javax.swing.border,\
 javax.swing.colorchooser,\
 javax.swing.event,\
 javax.swing.filechooser,\
 javax.swing.plaf,\
 javax.swing.plaf.basic,\
 javax.swing.plaf.metal,\
 javax.swing.plaf.multi,\
 javax.swing.table,\
 javax.swing.text,\
 javax.swing.text.html,\
 javax.swing.text.html.parser,\
 javax.swing.text.rtf,\
 javax.swing.tree,\
 javax.swing.undo,\
 org.omg.CORBA,\
 org.omg.CORBA.DynAnyPackage,\
 org.omg.CORBA.ORBPackage,\
 org.omg.CORBA.portable,\
 org.omg.CORBA.TypeCodePackage,\
 org.omg.CosNaming,\
 org.omg.CosNaming.NamingContextPackage
org.osgi.framework.bootdelegation = \
 sun.*,\
 com.sun.*
org.osgi.framework.executionenvironment = \
 JRE-1.1,\
 J2SE-1.2
org.osgi.framework.system.capabilities = \
 osgi.ee; osgi.ee="JavaSE"; version:List<Version>="1.0, 1.1, 1.2",\
 osgi.ee; osgi.ee="JRE"; version:List<Version>="1.0, 1.1"
osgi.java.profile.name = J2SE-1.2
org.eclipse.jdt.core.compiler.compliance=1.3
org.eclipse.jdt.core.compiler.source=1.3
org.eclipse.jdt.core.compiler.codegen.targetPlatform=1.1
org.eclipse.jdt.core.compiler.problem.assertIdentifier=ignore
org.eclipse.jdt.core.compiler.problem.enumIdentifier=ignore
