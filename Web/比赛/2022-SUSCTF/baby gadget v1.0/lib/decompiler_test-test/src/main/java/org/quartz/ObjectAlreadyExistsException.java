package org.quartz;

public class ObjectAlreadyExistsException extends JobPersistenceException {
   private static final long serialVersionUID = -558301282071659896L;

   public ObjectAlreadyExistsException(String msg) {
      super(msg);
   }

   public ObjectAlreadyExistsException(JobDetail offendingJob) {
      super("Unable to store Job : '" + offendingJob.getKey() + "', because one already exists with this identification.");
   }

   public ObjectAlreadyExistsException(Trigger offendingTrigger) {
      super("Unable to store Trigger with name: '" + offendingTrigger.getKey().getName() + "' and group: '" + offendingTrigger.getKey().getGroup() + "', because one already exists with this identification.");
   }
}
