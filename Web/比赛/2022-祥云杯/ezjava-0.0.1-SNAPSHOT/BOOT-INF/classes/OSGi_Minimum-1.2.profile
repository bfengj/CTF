###############################################################################
# Copyright (c) 2003, 2008 IBM Corporation and others.
# All rights reserved. This program and the accompanying materials
# are made available under the terms of the Eclipse Public License v1.0
# which accompanies this distribution, and is available at
# http://www.eclipse.org/legal/epl-v10.html
# 
# Contributors:
#     IBM Corporation - initial API and implementation
###############################################################################
org.osgi.framework.system.packages = \
 javax.security.auth.x500
org.osgi.framework.bootdelegation = \
 javax.security.auth.x500
org.osgi.framework.executionenvironment = \
 OSGi/Minimum-1.0,\
 OSGi/Minimum-1.1,\
 OSGi/Minimum-1.2
org.osgi.framework.system.capabilities = \
 osgi.ee; osgi.ee="OSGi/Minimum"; version:List<Version>="1.0, 1.1, 1.2"
osgi.java.profile.name = OSGi/Minimum-1.2
org.eclipse.jdt.core.compiler.compliance=1.4
org.eclipse.jdt.core.compiler.source=1.3
org.eclipse.jdt.core.compiler.codegen.targetPlatform=1.2
org.eclipse.jdt.core.compiler.problem.assertIdentifier=warning
org.eclipse.jdt.core.compiler.problem.enumIdentifier=warning
