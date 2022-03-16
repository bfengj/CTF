package org.quartz;

public interface SimpleTrigger extends Trigger {
   long serialVersionUID = -3735980074222850397L;
   int MISFIRE_INSTRUCTION_FIRE_NOW = 1;
   int MISFIRE_INSTRUCTION_RESCHEDULE_NOW_WITH_EXISTING_REPEAT_COUNT = 2;
   int MISFIRE_INSTRUCTION_RESCHEDULE_NOW_WITH_REMAINING_REPEAT_COUNT = 3;
   int MISFIRE_INSTRUCTION_RESCHEDULE_NEXT_WITH_REMAINING_COUNT = 4;
   int MISFIRE_INSTRUCTION_RESCHEDULE_NEXT_WITH_EXISTING_COUNT = 5;
   int REPEAT_INDEFINITELY = -1;

   int getRepeatCount();

   long getRepeatInterval();

   int getTimesTriggered();

   TriggerBuilder<SimpleTrigger> getTriggerBuilder();
}
