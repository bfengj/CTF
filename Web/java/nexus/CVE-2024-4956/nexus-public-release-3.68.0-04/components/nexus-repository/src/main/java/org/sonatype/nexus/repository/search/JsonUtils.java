/*
 * Sonatype Nexus (TM) Open Source Version
 * Copyright (c) 2008-present Sonatype, Inc.
 * All rights reserved. Includes the third-party code listed at http://links.sonatype.com/products/nexus/oss/attributions.
 *
 * This program and the accompanying materials are made available under the terms of the Eclipse Public License Version 1.0,
 * which accompanies this distribution and is available at http://www.eclipse.org/legal/epl-v10.html.
 *
 * Sonatype Nexus (TM) Professional Version is available from Sonatype, Inc. "Sonatype" and "Sonatype Nexus" are trademarks
 * of Sonatype, Inc. Apache Maven is a trademark of the Apache Software Foundation. M2eclipse is a trademark of the
 * Eclipse Foundation. All other trademarks are the property of their respective owners.
 */
package org.sonatype.nexus.repository.search;

import java.io.IOException;

import com.fasterxml.jackson.databind.ObjectMapper;
import com.fasterxml.jackson.databind.ObjectWriter;

/**
 * JSON related utilities.
 *
 * @since 3.0
 */
class JsonUtils
{
  private JsonUtils() {
    // empty
  }

  private static final ObjectMapper MAPPER = new ObjectMapper();

  private static final ObjectWriter WRITER = MAPPER.writerWithDefaultPrettyPrinter();

  /**
   * Converts any object to JSON.
   */
  public static String from(final Object value) throws IOException {
    return WRITER.writeValueAsString(value);
  }
}
