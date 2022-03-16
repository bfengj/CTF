package org.quartz.impl.matchers;

import org.quartz.Matcher;
import org.quartz.utils.Key;

public class AndMatcher<T extends Key<?>> implements Matcher<T> {
   private static final long serialVersionUID = 4697276220890670941L;
   protected Matcher<T> leftOperand;
   protected Matcher<T> rightOperand;

   protected AndMatcher(Matcher<T> leftOperand, Matcher<T> rightOperand) {
      if (leftOperand != null && rightOperand != null) {
         this.leftOperand = leftOperand;
         this.rightOperand = rightOperand;
      } else {
         throw new IllegalArgumentException("Two non-null operands required!");
      }
   }

   public static <U extends Key<?>> AndMatcher<U> and(Matcher<U> leftOperand, Matcher<U> rightOperand) {
      return new AndMatcher(leftOperand, rightOperand);
   }

   public boolean isMatch(T key) {
      return this.leftOperand.isMatch(key) && this.rightOperand.isMatch(key);
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
         AndMatcher<?> other = (AndMatcher)obj;
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
