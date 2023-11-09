###############################################################################
# Copyright (c) 2005 IBM Corporation and others.
# All rights reserved. This program and the accompanying materials
# are made available under the terms of the Eclipse Public License v1.0
# which accompanies this distribution, and is available at
# http://www.eclipse.org/legal/epl-v10.html
# 
# Contributors:
#     IBM Corporation - initial API and implementation
###############################################################################
org.osgi.framework.system.packages = \
 javax.microedition.io
org.osgi.framework.bootdelegation = \
 javax.microedition.io
org.osgi.framework.executionenvironment = \
 OSGi/Minimum-1.0,\
 OSGi/Minimum-1.1,\
 CDC-1.0/Foundation-1.0
org.osgi.framework.system.capabilities = \
 osgi.ee; osgi.ee="OSGi/Minimum"; version:List<Version>="1.0, 1.1",\
 osgi.ee; osgi.ee="CDC/Foundation"; version:Version="1.0"
osgi.java.profile.name = CDC-1.0/Foundation-1.0
org.eclipse.jdt.core.compiler.compliance=1.3
org.eclipse.jdt.core.compiler.source=1.3
org.eclipse.jdt.core.compiler.codegen.targetPlatform=1.1
org.eclipse.jdt.core.compiler.problem.assertIdentifier=ignore
org.eclipse.jdt.core.compiler.problem.enumIdentifier=ignore
