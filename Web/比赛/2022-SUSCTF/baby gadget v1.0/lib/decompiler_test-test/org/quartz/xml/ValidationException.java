package org.quartz.xml;

import java.util.ArrayList;
import java.util.Collection;
import java.util.Collections;
import java.util.Iterator;

public class ValidationException extends Exception {
   private static final long serialVersionUID = -1697832087051681357L;
   private Collection<Exception> validationExceptions;

   public ValidationException() {
      this.validationExceptions = new ArrayList();
   }

   public ValidationException(String message) {
      super(message);
      this.validationExceptions = new ArrayList();
   }

   public ValidationException(Collection<Exception> errors) {
      this();
      this.validationExceptions = Collections.unmodifiableCollection(this.validationExceptions);
      this.initCause((Throwable)errors.iterator().next());
   }

   public ValidationException(String message, Collection<Exception> errors) {
      this(message);
      this.validationExceptions = Collections.unmodifiableCollection(this.validationExceptions);
      this.initCause((Throwable)errors.iterator().next());
   }

   public Collection<Exception> getValidationExceptions() {
      return this.validationExceptions;
   }

   public String getMessage() {
      if (this.getValidationExceptions().size() == 0) {
         return super.getMessage();
      } else {
         StringBuffer sb = new StringBuffer();
         boolean first = true;

         Exception e;
         for(Iterator iter = this.getValidationExceptions().iterator(); iter.hasNext(); sb.append(e.getMessage())) {
            e = (Exception)iter.next();
            if (!first) {
               sb.append('\n');
               first = false;
            }
         }

         return sb.toString();
      }
   }
}
