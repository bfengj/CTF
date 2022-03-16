package org.quartz.impl.matchers;

import org.quartz.Matcher;
import org.quartz.utils.Key;

public class KeyMatcher<T extends Key<?>> implements Matcher<T> {
   private static final long serialVersionUID = 1230009869074992437L;
   protected T compareTo;

   protected KeyMatcher(T compareTo) {
      this.compareTo = compareTo;
   }

   public static <U extends Key<?>> KeyMatcher<U> keyEquals(U compareTo) {
      return new KeyMatcher(compareTo);
   }

   public boolean isMatch(T key) {
      return this.compareTo.equals(key);
   }

   public T getCompareToValue() {
      return this.compareTo;
   }

   public int hashCode() {
      int prime = true;
      int result = 1;
      int result = 31 * result + (this.compareTo == null ? 0 : this.compareTo.hashCode());
      return result;
   }

   public boolean equals(Object obj) {
      if (this == obj) {
         return true;
      } else if (obj == null) {
         return false;
      } else if (this.getClass() != obj.getClass()) {
         return false;
      } else {
         KeyMatcher<?> other = (KeyMatcher)obj;
         if (this.compareTo == null) {
            if (other.compareTo != null) {
               return false;
            }
         } else if (!this.compareTo.equals(other.compareTo)) {
            return false;
         }

         return true;
      }
   }
}
