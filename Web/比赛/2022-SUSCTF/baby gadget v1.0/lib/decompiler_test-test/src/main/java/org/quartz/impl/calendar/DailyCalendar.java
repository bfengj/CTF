package org.quartz.impl.calendar;

import java.text.NumberFormat;
import java.util.ArrayList;
import java.util.StringTokenizer;
import java.util.TimeZone;
import org.quartz.Calendar;

public class DailyCalendar extends BaseCalendar {
   static final long serialVersionUID = -7561220099904944039L;
   private static final String invalidHourOfDay = "Invalid hour of day: ";
   private static final String invalidMinute = "Invalid minute: ";
   private static final String invalidSecond = "Invalid second: ";
   private static final String invalidMillis = "Invalid millis: ";
   private static final String invalidTimeRange = "Invalid time range: ";
   private static final String separator = " - ";
   private static final long oneMillis = 1L;
   private static final String colon = ":";
   private int rangeStartingHourOfDay;
   private int rangeStartingMinute;
   private int rangeStartingSecond;
   private int rangeStartingMillis;
   private int rangeEndingHourOfDay;
   private int rangeEndingMinute;
   private int rangeEndingSecond;
   private int rangeEndingMillis;
   private boolean invertTimeRange = false;

   public DailyCalendar(String rangeStartingTime, String rangeEndingTime) {
      this.setTimeRange(rangeStartingTime, rangeEndingTime);
   }

   public DailyCalendar(Calendar baseCalendar, String rangeStartingTime, String rangeEndingTime) {
      super(baseCalendar);
      this.setTimeRange(rangeStartingTime, rangeEndingTime);
   }

   public DailyCalendar(int rangeStartingHourOfDay, int rangeStartingMinute, int rangeStartingSecond, int rangeStartingMillis, int rangeEndingHourOfDay, int rangeEndingMinute, int rangeEndingSecond, int rangeEndingMillis) {
      this.setTimeRange(rangeStartingHourOfDay, rangeStartingMinute, rangeStartingSecond, rangeStartingMillis, rangeEndingHourOfDay, rangeEndingMinute, rangeEndingSecond, rangeEndingMillis);
   }

   public DailyCalendar(Calendar baseCalendar, int rangeStartingHourOfDay, int rangeStartingMinute, int rangeStartingSecond, int rangeStartingMillis, int rangeEndingHourOfDay, int rangeEndingMinute, int rangeEndingSecond, int rangeEndingMillis) {
      super(baseCalendar);
      this.setTimeRange(rangeStartingHourOfDay, rangeStartingMinute, rangeStartingSecond, rangeStartingMillis, rangeEndingHourOfDay, rangeEndingMinute, rangeEndingSecond, rangeEndingMillis);
   }

   public DailyCalendar(java.util.Calendar rangeStartingCalendar, java.util.Calendar rangeEndingCalendar) {
      this.setTimeRange(rangeStartingCalendar, rangeEndingCalendar);
   }

   public DailyCalendar(Calendar baseCalendar, java.util.Calendar rangeStartingCalendar, java.util.Calendar rangeEndingCalendar) {
      super(baseCalendar);
      this.setTimeRange(rangeStartingCalendar, rangeEndingCalendar);
   }

   public DailyCalendar(long rangeStartingTimeInMillis, long rangeEndingTimeInMillis) {
      this.setTimeRange(rangeStartingTimeInMillis, rangeEndingTimeInMillis);
   }

   public DailyCalendar(Calendar baseCalendar, long rangeStartingTimeInMillis, long rangeEndingTimeInMillis) {
      super(baseCalendar);
      this.setTimeRange(rangeStartingTimeInMillis, rangeEndingTimeInMillis);
   }

   public DailyCalendar(TimeZone timeZone, long rangeStartingTimeInMillis, long rangeEndingTimeInMillis) {
      super(timeZone);
      this.setTimeRange(rangeStartingTimeInMillis, rangeEndingTimeInMillis);
   }

   public DailyCalendar(Calendar baseCalendar, TimeZone timeZone, long rangeStartingTimeInMillis, long rangeEndingTimeInMillis) {
      super(baseCalendar, timeZone);
      this.setTimeRange(rangeStartingTimeInMillis, rangeEndingTimeInMillis);
   }

   public Object clone() {
      DailyCalendar clone = (DailyCalendar)super.clone();
      return clone;
   }

   public boolean isTimeIncluded(long timeInMillis) {
      if (this.getBaseCalendar() != null && !this.getBaseCalendar().isTimeIncluded(timeInMillis)) {
         return false;
      } else {
         long startOfDayInMillis = this.getStartOfDayJavaCalendar(timeInMillis).getTime().getTime();
         long endOfDayInMillis = this.getEndOfDayJavaCalendar(timeInMillis).getTime().getTime();
         long timeRangeStartingTimeInMillis = this.getTimeRangeStartingTimeInMillis(timeInMillis);
         long timeRangeEndingTimeInMillis = this.getTimeRangeEndingTimeInMillis(timeInMillis);
         if (!this.invertTimeRange) {
            return timeInMillis > startOfDayInMillis && timeInMillis < timeRangeStartingTimeInMillis || timeInMillis > timeRangeEndingTimeInMillis && timeInMillis < endOfDayInMillis;
         } else {
            return timeInMillis >= timeRangeStartingTimeInMillis && timeInMillis <= timeRangeEndingTimeInMillis;
         }
      }
   }

   public long getNextIncludedTime(long timeInMillis) {
      long nextIncludedTime = timeInMillis + 1L;

      while(true) {
         while(true) {
            while(!this.isTimeIncluded(nextIncludedTime)) {
               if (!this.invertTimeRange) {
                  if (nextIncludedTime >= this.getTimeRangeStartingTimeInMillis(nextIncludedTime) && nextIncludedTime <= this.getTimeRangeEndingTimeInMillis(nextIncludedTime)) {
                     nextIncludedTime = this.getTimeRangeEndingTimeInMillis(nextIncludedTime) + 1L;
                  } else if (this.getBaseCalendar() != null && !this.getBaseCalendar().isTimeIncluded(nextIncludedTime)) {
                     nextIncludedTime = this.getBaseCalendar().getNextIncludedTime(nextIncludedTime);
                  } else {
                     ++nextIncludedTime;
                  }
               } else if (nextIncludedTime < this.getTimeRangeStartingTimeInMillis(nextIncludedTime)) {
                  nextIncludedTime = this.getTimeRangeStartingTimeInMillis(nextIncludedTime);
               } else if (nextIncludedTime > this.getTimeRangeEndingTimeInMillis(nextIncludedTime)) {
                  nextIncludedTime = this.getEndOfDayJavaCalendar(nextIncludedTime).getTime().getTime();
                  ++nextIncludedTime;
               } else if (this.getBaseCalendar() != null && !this.getBaseCalendar().isTimeIncluded(nextIncludedTime)) {
                  nextIncludedTime = this.getBaseCalendar().getNextIncludedTime(nextIncludedTime);
               } else {
                  ++nextIncludedTime;
               }
            }

            return nextIncludedTime;
         }
      }
   }

   public long getTimeRangeStartingTimeInMillis(long timeInMillis) {
      java.util.Calendar rangeStartingTime = this.createJavaCalendar(timeInMillis);
      rangeStartingTime.set(11, this.rangeStartingHourOfDay);
      rangeStartingTime.set(12, this.rangeStartingMinute);
      rangeStartingTime.set(13, this.rangeStartingSecond);
      rangeStartingTime.set(14, this.rangeStartingMillis);
      return rangeStartingTime.getTime().getTime();
   }

   public long getTimeRangeEndingTimeInMillis(long timeInMillis) {
      java.util.Calendar rangeEndingTime = this.createJavaCalendar(timeInMillis);
      rangeEndingTime.set(11, this.rangeEndingHourOfDay);
      rangeEndingTime.set(12, this.rangeEndingMinute);
      rangeEndingTime.set(13, this.rangeEndingSecond);
      rangeEndingTime.set(14, this.rangeEndingMillis);
      return rangeEndingTime.getTime().getTime();
   }

   public boolean getInvertTimeRange() {
      return this.invertTimeRange;
   }

   public void setInvertTimeRange(boolean flag) {
      this.invertTimeRange = flag;
   }

   public String toString() {
      NumberFormat numberFormatter = NumberFormat.getNumberInstance();
      numberFormatter.setMaximumFractionDigits(0);
      numberFormatter.setMinimumIntegerDigits(2);
      StringBuffer buffer = new StringBuffer();
      buffer.append("base calendar: [");
      if (this.getBaseCalendar() != null) {
         buffer.append(this.getBaseCalendar().toString());
      } else {
         buffer.append("null");
      }

      buffer.append("], time range: '");
      buffer.append(numberFormatter.format((long)this.rangeStartingHourOfDay));
      buffer.append(":");
      buffer.append(numberFormatter.format((long)this.rangeStartingMinute));
      buffer.append(":");
      buffer.append(numberFormatter.format((long)this.rangeStartingSecond));
      buffer.append(":");
      numberFormatter.setMinimumIntegerDigits(3);
      buffer.append(numberFormatter.format((long)this.rangeStartingMillis));
      numberFormatter.setMinimumIntegerDigits(2);
      buffer.append(" - ");
      buffer.append(numberFormatter.format((long)this.rangeEndingHourOfDay));
      buffer.append(":");
      buffer.append(numberFormatter.format((long)this.rangeEndingMinute));
      buffer.append(":");
      buffer.append(numberFormatter.format((long)this.rangeEndingSecond));
      buffer.append(":");
      numberFormatter.setMinimumIntegerDigits(3);
      buffer.append(numberFormatter.format((long)this.rangeEndingMillis));
      buffer.append("', inverted: " + this.invertTimeRange + "]");
      return buffer.toString();
   }

   private String[] split(String string, String delim) {
      ArrayList<String> result = new ArrayList();
      StringTokenizer stringTokenizer = new StringTokenizer(string, delim);

      while(stringTokenizer.hasMoreTokens()) {
         result.add(stringTokenizer.nextToken());
      }

      return (String[])((String[])result.toArray(new String[result.size()]));
   }

   public void setTimeRange(String rangeStartingTimeString, String rangeEndingTimeString) {
      String[] rangeStartingTime = this.split(rangeStartingTimeString, ":");
      if (rangeStartingTime.length >= 2 && rangeStartingTime.length <= 4) {
         int rStartingHourOfDay = Integer.parseInt(rangeStartingTime[0]);
         int rStartingMinute = Integer.parseInt(rangeStartingTime[1]);
         int rStartingSecond;
         if (rangeStartingTime.length > 2) {
            rStartingSecond = Integer.parseInt(rangeStartingTime[2]);
         } else {
            rStartingSecond = 0;
         }

         int rStartingMillis;
         if (rangeStartingTime.length == 4) {
            rStartingMillis = Integer.parseInt(rangeStartingTime[3]);
         } else {
            rStartingMillis = 0;
         }

         String[] rEndingTime = this.split(rangeEndingTimeString, ":");
         if (rEndingTime.length >= 2 && rEndingTime.length <= 4) {
            int rEndingHourOfDay = Integer.parseInt(rEndingTime[0]);
            int rEndingMinute = Integer.parseInt(rEndingTime[1]);
            int rEndingSecond;
            if (rEndingTime.length > 2) {
               rEndingSecond = Integer.parseInt(rEndingTime[2]);
            } else {
               rEndingSecond = 0;
            }

            int rEndingMillis;
            if (rEndingTime.length == 4) {
               rEndingMillis = Integer.parseInt(rEndingTime[3]);
            } else {
               rEndingMillis = 0;
            }

            this.setTimeRange(rStartingHourOfDay, rStartingMinute, rStartingSecond, rStartingMillis, rEndingHourOfDay, rEndingMinute, rEndingSecond, rEndingMillis);
         } else {
            throw new IllegalArgumentException("Invalid time string '" + rangeEndingTimeString + "'");
         }
      } else {
         throw new IllegalArgumentException("Invalid time string '" + rangeStartingTimeString + "'");
      }
   }

   public void setTimeRange(int rangeStartingHourOfDay, int rangeStartingMinute, int rangeStartingSecond, int rangeStartingMillis, int rangeEndingHourOfDay, int rangeEndingMinute, int rangeEndingSecond, int rangeEndingMillis) {
      this.validate(rangeStartingHourOfDay, rangeStartingMinute, rangeStartingSecond, rangeStartingMillis);
      this.validate(rangeEndingHourOfDay, rangeEndingMinute, rangeEndingSecond, rangeEndingMillis);
      java.util.Calendar startCal = this.createJavaCalendar();
      startCal.set(11, rangeStartingHourOfDay);
      startCal.set(12, rangeStartingMinute);
      startCal.set(13, rangeStartingSecond);
      startCal.set(14, rangeStartingMillis);
      java.util.Calendar endCal = this.createJavaCalendar();
      endCal.set(11, rangeEndingHourOfDay);
      endCal.set(12, rangeEndingMinute);
      endCal.set(13, rangeEndingSecond);
      endCal.set(14, rangeEndingMillis);
      if (!startCal.before(endCal)) {
         throw new IllegalArgumentException("Invalid time range: " + rangeStartingHourOfDay + ":" + rangeStartingMinute + ":" + rangeStartingSecond + ":" + rangeStartingMillis + " - " + rangeEndingHourOfDay + ":" + rangeEndingMinute + ":" + rangeEndingSecond + ":" + rangeEndingMillis);
      } else {
         this.rangeStartingHourOfDay = rangeStartingHourOfDay;
         this.rangeStartingMinute = rangeStartingMinute;
         this.rangeStartingSecond = rangeStartingSecond;
         this.rangeStartingMillis = rangeStartingMillis;
         this.rangeEndingHourOfDay = rangeEndingHourOfDay;
         this.rangeEndingMinute = rangeEndingMinute;
         this.rangeEndingSecond = rangeEndingSecond;
         this.rangeEndingMillis = rangeEndingMillis;
      }
   }

   public void setTimeRange(java.util.Calendar rangeStartingCalendar, java.util.Calendar rangeEndingCalendar) {
      this.setTimeRange(rangeStartingCalendar.get(11), rangeStartingCalendar.get(12), rangeStartingCalendar.get(13), rangeStartingCalendar.get(14), rangeEndingCalendar.get(11), rangeEndingCalendar.get(12), rangeEndingCalendar.get(13), rangeEndingCalendar.get(14));
   }

   public void setTimeRange(long rangeStartingTime, long rangeEndingTime) {
      this.setTimeRange(this.createJavaCalendar(rangeStartingTime), this.createJavaCalendar(rangeEndingTime));
   }

   private void validate(int hourOfDay, int minute, int second, int millis) {
      if (hourOfDay >= 0 && hourOfDay <= 23) {
         if (minute >= 0 && minute <= 59) {
            if (second >= 0 && second <= 59) {
               if (millis < 0 || millis > 999) {
                  throw new IllegalArgumentException("Invalid millis: " + millis);
               }
            } else {
               throw new IllegalArgumentException("Invalid second: " + second);
            }
         } else {
            throw new IllegalArgumentException("Invalid minute: " + minute);
         }
      } else {
         throw new IllegalArgumentException("Invalid hour of day: " + hourOfDay);
      }
   }
}
