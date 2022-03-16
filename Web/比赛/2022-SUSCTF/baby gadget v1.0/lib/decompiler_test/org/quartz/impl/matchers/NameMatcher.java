package org.quartz.impl.matchers;

import org.quartz.JobKey;
import org.quartz.TriggerKey;
import org.quartz.utils.Key;

public class NameMatcher<T extends Key<?>> extends StringMatcher<T> {
   private static final long serialVersionUID = -33104959459613480L;

   protected NameMatcher(String compareTo, StringMatcher.StringOperatorName compareWith) {
      super(compareTo, compareWith);
   }

   public static <T extends Key<?>> NameMatcher<T> nameEquals(String compareTo) {
      return new NameMatcher(compareTo, StringMatcher.StringOperatorName.EQUALS);
   }

   public static NameMatcher<JobKey> jobNameEquals(String compareTo) {
      return nameEquals(compareTo);
   }

   public static NameMatcher<TriggerKey> triggerNameEquals(String compareTo) {
      return nameEquals(compareTo);
   }

   public static <U extends Key<?>> NameMatcher<U> nameStartsWith(String compareTo) {
      return new NameMatcher(compareTo, StringMatcher.StringOperatorName.STARTS_WITH);
   }

   public static NameMatcher<JobKey> jobNameStartsWith(String compareTo) {
      return nameStartsWith(compareTo);
   }

   public static NameMatcher<TriggerKey> triggerNameStartsWith(String compareTo) {
      return nameStartsWith(compareTo);
   }

   public static <U extends Key<?>> NameMatcher<U> nameEndsWith(String compareTo) {
      return new NameMatcher(compareTo, StringMatcher.StringOperatorName.ENDS_WITH);
   }

   public static NameMatcher<JobKey> jobNameEndsWith(String compareTo) {
      return nameEndsWith(compareTo);
   }

   public static NameMatcher<TriggerKey> triggerNameEndsWith(String compareTo) {
      return nameEndsWith(compareTo);
   }

   public static <U extends Key<?>> NameMatcher<U> nameContains(String compareTo) {
      return new NameMatcher(compareTo, StringMatcher.StringOperatorName.CONTAINS);
   }

   public static NameMatcher<JobKey> jobNameContains(String compareTo) {
      return nameContains(compareTo);
   }

   public static NameMatcher<TriggerKey> triggerNameContains(String compareTo) {
      return nameContains(compareTo);
   }

   protected String getValue(T key) {
      return key.getName();
   }
}
