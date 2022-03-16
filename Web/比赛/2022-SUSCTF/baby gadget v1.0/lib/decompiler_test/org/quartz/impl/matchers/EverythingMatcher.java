package org.quartz.impl.matchers;

import org.quartz.JobKey;
import org.quartz.Matcher;
import org.quartz.TriggerKey;
import org.quartz.utils.Key;

public class EverythingMatcher<T extends Key<?>> implements Matcher<T> {
   private static final long serialVersionUID = 202300056681974058L;

   protected EverythingMatcher() {
   }

   public static EverythingMatcher<JobKey> allJobs() {
      return new EverythingMatcher();
   }

   public static EverythingMatcher<TriggerKey> allTriggers() {
      return new EverythingMatcher();
   }

   public boolean isMatch(T key) {
      return true;
   }

   public boolean equals(Object obj) {
      return obj == null ? false : obj.getClass().equals(this.getClass());
   }

   public int hashCode() {
      return this.getClass().getName().hashCode();
   }
}
