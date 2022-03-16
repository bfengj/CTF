package org.quartz.impl.matchers;

import org.quartz.Matcher;
import org.quartz.utils.Key;

public class OrMatcher<T extends Key<?>> implements Matcher<T> {
   private static final long serialVersionUID = -2867392824539403712L;
   protected Matcher<T> leftOperand;
   protected Matcher<T> rightOperand;

   protected OrMatcher(Matcher<T> leftOperand, Matcher<T> rightOperand) {
      if (leftOperand != null && rightOperand != null) {
         this.leftOperand = leftOperand;
         this.rightOperand = rightOperand;
      } else {
         throw new IllegalArgumentException("Two non-null operands required!");
      }
   }

   public static <U extends Key<?>> OrMatcher<U> or(Matcher<U> leftOperand, Matcher<U> rightOperand) {
      return new OrMatcher(leftOperand, rightOperand);
   }

   public boolean isMatch(T key) {
      return this.leftOperand.isMatch(key) || this.rightOperand.isMatch(key);
   }

   public Matcher<T> getLeftOperand() {
      return this.leftOperand;
   }

   public Matcher<T> getRightOperand() {
      return this.rightOperand;
   }

   public int hashCode() {
      int prime = true;
      int result = 1;
      int result = 31 * result + (this.leftOperand == null ? 0 : this.leftOperand.hashCode());
      result = 31 * result + (this.rightOperand == null ? 0 : this.rightOperand.hashCode());
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
         OrMatcher<?> other = (OrMatcher)obj;
         if (this.leftOperand == null) {
            if (other.leftOperand != null) {
               return false;
            }
         } else if (!this.leftOperand.equals(other.leftOperand)) {
            return false;
         }

         if (this.rightOperand == null) {
            if (other.rightOperand != null) {
               return false;
            }
         } else if (!this.rightOperand.equals(other.rightOperand)) {
            return false;
         }

         return true;
      }
   }
}
