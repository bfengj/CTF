package org.quartz.impl.matchers;

import org.quartz.Matcher;
import org.quartz.utils.Key;

public abstract class StringMatcher<T extends Key<?>> implements Matcher<T> {
   private static final long serialVersionUID = -2757924162611145836L;
   protected String compareTo;
   protected StringMatcher.StringOperatorName compareWith;

   protected StringMatcher(String compareTo, StringMatcher.StringOperatorName compareWith) {
      if (compareTo == null) {
         throw new IllegalArgumentException("CompareTo value cannot be null!");
      } else if (compareWith == null) {
         throw new IllegalArgumentException("CompareWith operator cannot be null!");
      } else {
         this.compareTo = compareTo;
         this.compareWith = compareWith;
      }
   }

   protected abstract String getValue(T var1);

   public boolean isMatch(T key) {
      return this.compareWith.evaluate(this.getValue(key), this.compareTo);
   }

   public int hashCode() {
      int prime = true;
      int result = 1;
      int result = 31 * result + (this.compareTo == null ? 0 : this.compareTo.hashCode());
      result = 31 * result + (this.compareWith == null ? 0 : this.compareWith.hashCode());
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
         StringMatcher<?> other = (StringMatcher)obj;
         if (this.compareTo == null) {
            if (other.compareTo != null) {
               return false;
            }
         } else if (!this.compareTo.equals(other.compareTo)) {
            return false;
         }

         if (this.compareWith == null) {
            if (other.compareWith != null) {
               return false;
            }
         } else if (!this.compareWith.equals(other.compareWith)) {
            return false;
         }

         return true;
      }
   }

   public String getCompareToValue() {
      return this.compareTo;
   }

   public StringMatcher.StringOperatorName getCompareWithOperator() {
      return this.compareWith;
   }

   public static enum StringOperatorName {
      EQUALS {
         public boolean evaluate(String value, String compareTo) {
            return value.equals(compareTo);
         }
      },
      STARTS_WITH {
         public boolean evaluate(String value, String compareTo) {
            return value.startsWith(compareTo);
         }
      },
      ENDS_WITH {
         public boolean evaluate(String value, String compareTo) {
            return value.endsWith(compareTo);
         }
      },
      CONTAINS {
         public boolean evaluate(String value, String compareTo) {
            return value.contains(compareTo);
         }
      },
      ANYTHING {
         public boolean evaluate(String value, String compareTo) {
            return true;
         }
      };

      private StringOperatorName() {
      }

      public abstract boolean evaluate(String var1, String var2);

      // $FF: synthetic method
      StringOperatorName(Object x2) {
         this();
      }
   }
}
