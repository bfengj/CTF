package org.quartz.impl.matchers;

import org.quartz.JobKey;
import org.quartz.TriggerKey;
import org.quartz.utils.Key;

public class GroupMatcher<T extends Key<?>> extends StringMatcher<T> {
   private static final long serialVersionUID = -3275767650469343849L;

   protected GroupMatcher(String compareTo, StringMatcher.StringOperatorName compareWith) {
      super(compareTo, compareWith);
   }

   public static <T extends Key<T>> GroupMatcher<T> groupEquals(String compareTo) {
      return new GroupMatcher(compareTo, StringMatcher.StringOperatorName.EQUALS);
   }

   public static GroupMatcher<JobKey> jobGroupEquals(String compareTo) {
      return groupEquals(compareTo);
   }

   public static GroupMatcher<TriggerKey> triggerGroupEquals(String compareTo) {
      return groupEquals(compareTo);
   }

   public static <T extends Key<T>> GroupMatcher<T> groupStartsWith(String compareTo) {
      return new GroupMatcher(compareTo, StringMatcher.StringOperatorName.STARTS_WITH);
   }

   public static GroupMatcher<JobKey> jobGroupStartsWith(String compareTo) {
      return groupStartsWith(compareTo);
   }

   public static GroupMatcher<TriggerKey> triggerGroupStartsWith(String compareTo) {
      return groupStartsWith(compareTo);
   }

   public static <T extends Key<T>> GroupMatcher<T> groupEndsWith(String compareTo) {
      return new GroupMatcher(compareTo, StringMatcher.StringOperatorName.ENDS_WITH);
   }

   public static GroupMatcher<JobKey> jobGroupEndsWith(String compareTo) {
      return groupEndsWith(compareTo);
   }

   public static GroupMatcher<TriggerKey> triggerGroupEndsWith(String compareTo) {
      return groupEndsWith(compareTo);
   }

   public static <T extends Key<T>> GroupMatcher<T> groupContains(String compareTo) {
      return new GroupMatcher(compareTo, StringMatcher.StringOperatorName.CONTAINS);
   }

   public static GroupMatcher<JobKey> jobGroupContains(String compareTo) {
      return groupContains(compareTo);
   }

   public static GroupMatcher<TriggerKey> triggerGroupContains(String compareTo) {
      return groupContains(compareTo);
   }

   public static <T extends Key<T>> GroupMatcher<T> anyGroup() {
      return new GroupMatcher("", StringMatcher.StringOperatorName.ANYTHING);
   }

   public static GroupMatcher<JobKey> anyJobGroup() {
      return anyGroup();
   }

   public static GroupMatcher<TriggerKey> anyTriggerGroup() {
      return anyGroup();
   }

   protected String getValue(T key) {
      return key.getGroup();
   }
}
