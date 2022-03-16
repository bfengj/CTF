package org.quartz.impl.matchers;

import org.quartz.Matcher;
import org.quartz.utils.Key;

public class NotMatcher<T extends Key<?>> implements Matcher<T> {
   private static final long serialVersionUID = -2856769076151741391L;
   protected Matcher<T> operand;

   protected NotMatcher(Matcher<T> operand) {
      if (operand == null) {
         throw new IllegalArgumentException("Non-null operand required!");
      } else {
         this.operand = operand;
      }
   }

   public static <U extends Key<?>> NotMatcher<U> not(Matcher<U> operand) {
      return new NotMatcher(operand);
   }

   public boolean isMatch(T key) {
      return !this.operand.isMatch(key);
   }

   public Matcher<T> getOperand() {
      return this.operand;
   }

   public int hashCode() {
      int prime = true;
      int result = 1;
      int result = 31 * result + (this.operand == null ? 0 : this.operand.hashCode());
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
         NotMatcher<?> other = (NotMatcher)obj;
         if (this.operand == null) {
            if (other.operand != null) {
               return false;
            }
         } else if (!this.operand.equals(other.operand)) {
            return false;
         }

         return true;
      }
   }
}
