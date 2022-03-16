package org.terracotta.quartz;

import org.terracotta.toolkit.internal.ToolkitInternal;

public class TerracottaJobStore extends AbstractTerracottaJobStore {
   TerracottaJobStoreExtensions getRealStore(ToolkitInternal toolkit) {
      return new PlainTerracottaJobStore(toolkit);
   }
}
