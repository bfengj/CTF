package org.quartz;

import java.io.IOException;
import java.io.ObjectInputStream;
import java.io.Serializable;
import java.text.ParseException;
import java.util.ArrayList;
import java.util.Date;
import java.util.GregorianCalendar;
import java.util.HashMap;
import java.util.Iterator;
import java.util.Locale;
import java.util.Map;
import java.util.Set;
import java.util.SortedSet;
import java.util.StringTokenizer;
import java.util.TimeZone;
import java.util.TreeSet;

public final class CronExpression implements Serializable, Cloneable {
   private static final long serialVersionUID = 12423409423L;
   protected static final int SECOND = 0;
   protected static final int MINUTE = 1;
   protected static final int HOUR = 2;
   protected static final int DAY_OF_MONTH = 3;
   protected static final int MONTH = 4;
   protected static final int DAY_OF_WEEK = 5;
   protected static final int YEAR = 6;
   protected static final int ALL_SPEC_INT = 99;
   protected static final int NO_SPEC_INT = 98;
   protected static final Integer ALL_SPEC = 99;
   protected static final Integer NO_SPEC = 98;
   protected static final Map<String, Integer> monthMap = new HashMap(20);
   protected static final Map<String, Integer> dayMap = new HashMap(60);
   private final String cronExpression;
   private TimeZone timeZone = null;
   protected transient TreeSet<Integer> seconds;
   protected transient TreeSet<Integer> minutes;
   protected transient TreeSet<Integer> hours;
   protected transient TreeSet<Integer> daysOfMonth;
   protected transient TreeSet<Integer> months;
   protected transient TreeSet<Integer> daysOfWeek;
   protected transient TreeSet<Integer> years;
   protected transient boolean lastdayOfWeek = false;
   protected transient int nthdayOfWeek = 0;
   protected transient boolean lastdayOfMonth = false;
   protected transient boolean nearestWeekday = false;
   protected transient int lastdayOffset = 0;
   protected transient boolean expressionParsed = false;
   public static final int MAX_YEAR;

   public CronExpression(String cronExpression) throws ParseException {
      if (cronExpression == null) {
         throw new IllegalArgumentException("cronExpression cannot be null");
      } else {
         this.cronExpression = cronExpression.toUpperCase(Locale.US);
         this.buildExpression(this.cronExpression);
      }
   }

   public CronExpression(CronExpression expression) {
      this.cronExpression = expression.getCronExpression();

      try {
         this.buildExpression(this.cronExpression);
      } catch (ParseException var3) {
         throw new AssertionError();
      }

      if (expression.getTimeZone() != null) {
         this.setTimeZone((TimeZone)expression.getTimeZone().clone());
      }

   }

   public boolean isSatisfiedBy(Date date) {
      java.util.Calendar testDateCal = java.util.Calendar.getInstance(this.getTimeZone());
      testDateCal.setTime(date);
      testDateCal.set(14, 0);
      Date originalDate = testDateCal.getTime();
      testDateCal.add(13, -1);
      Date timeAfter = this.getTimeAfter(testDateCal.getTime());
      return timeAfter != null && timeAfter.equals(originalDate);
   }

   public Date getNextValidTimeAfter(Date date) {
      return this.getTimeAfter(date);
   }

   public Date getNextInvalidTimeAfter(Date date) {
      long difference = 1000L;
      java.util.Calendar adjustCal = java.util.Calendar.getInstance(this.getTimeZone());
      adjustCal.setTime(date);
      adjustCal.set(14, 0);
      Date lastDate = adjustCal.getTime();

      while(difference == 1000L) {
         Date newDate = this.getTimeAfter(lastDate);
         if (newDate == null) {
            break;
         }

         difference = newDate.getTime() - lastDate.getTime();
         if (difference == 1000L) {
            lastDate = newDate;
         }
      }

      return new Date(lastDate.getTime() + 1000L);
   }

   public TimeZone getTimeZone() {
      if (this.timeZone == null) {
         this.timeZone = TimeZone.getDefault();
      }

      return this.timeZone;
   }

   public void setTimeZone(TimeZone timeZone) {
      this.timeZone = timeZone;
   }

   public String toString() {
      return this.cronExpression;
   }

   public static boolean isValidExpression(String cronExpression) {
      try {
         new CronExpression(cronExpression);
         return true;
      } catch (ParseException var2) {
         return false;
      }
   }

   public static void validateExpression(String cronExpression) throws ParseException {
      new CronExpression(cronExpression);
   }

   protected void buildExpression(String expression) throws ParseException {
      this.expressionParsed = true;

      try {
         if (this.seconds == null) {
            this.seconds = new TreeSet();
         }

         if (this.minutes == null) {
            this.minutes = new TreeSet();
         }

         if (this.hours == null) {
            this.hours = new TreeSet();
         }

         if (this.daysOfMonth == null) {
            this.daysOfMonth = new TreeSet();
         }

         if (this.months == null) {
            this.months = new TreeSet();
         }

         if (this.daysOfWeek == null) {
            this.daysOfWeek = new TreeSet();
         }

         if (this.years == null) {
            this.years = new TreeSet();
         }

         int exprOn = 0;

         for(StringTokenizer exprsTok = new StringTokenizer(expression, " \t", false); exprsTok.hasMoreTokens() && exprOn <= 6; ++exprOn) {
            String expr = exprsTok.nextToken().trim();
            if (exprOn == 3 && expr.indexOf(76) != -1 && expr.length() > 1 && expr.contains(",")) {
               throw new ParseException("Support for specifying 'L' and 'LW' with other days of the month is not implemented", -1);
            }

            if (exprOn == 5 && expr.indexOf(76) != -1 && expr.length() > 1 && expr.contains(",")) {
               throw new ParseException("Support for specifying 'L' with other days of the week is not implemented", -1);
            }

            if (exprOn == 5 && expr.indexOf(35) != -1 && expr.indexOf(35, expr.indexOf(35) + 1) != -1) {
               throw new ParseException("Support for specifying multiple \"nth\" days is not implemented.", -1);
            }

            StringTokenizer vTok = new StringTokenizer(expr, ",");

            while(vTok.hasMoreTokens()) {
               String v = vTok.nextToken();
               this.storeExpressionVals(0, v, exprOn);
            }
         }

         if (exprOn <= 5) {
            throw new ParseException("Unexpected end of expression.", expression.length());
         } else {
            if (exprOn <= 6) {
               this.storeExpressionVals(0, "*", 6);
            }

            TreeSet<Integer> dow = this.getSet(5);
            TreeSet<Integer> dom = this.getSet(3);
            boolean dayOfMSpec = !dom.contains(NO_SPEC);
            boolean dayOfWSpec = !dow.contains(NO_SPEC);
            if ((!dayOfMSpec || dayOfWSpec) && (!dayOfWSpec || dayOfMSpec)) {
               throw new ParseException("Support for specifying both a day-of-week AND a day-of-month parameter is not implemented.", 0);
            }
         }
      } catch (ParseException var8) {
         throw var8;
      } catch (Exception var9) {
         throw new ParseException("Illegal cron expression format (" + var9.toString() + ")", 0);
      }
   }

   protected int storeExpressionVals(int pos, String s, int type) throws ParseException {
      int incr = 0;
      int i = this.skipWhiteSpace(pos, s);
      if (i >= s.length()) {
         return i;
      } else {
         char c = s.charAt(i);
         if (c >= 'A' && c <= 'Z' && !s.equals("L") && !s.equals("LW") && !s.matches("^L-[0-9]*[W]?")) {
            String sub = s.substring(i, i + 3);
            int sval = true;
            int eval = -1;
            int sval;
            if (type == 4) {
               sval = this.getMonthNumber(sub) + 1;
               if (sval <= 0) {
                  throw new ParseException("Invalid Month value: '" + sub + "'", i);
               }

               if (s.length() > i + 3) {
                  c = s.charAt(i + 3);
                  if (c == '-') {
                     i += 4;
                     sub = s.substring(i, i + 3);
                     eval = this.getMonthNumber(sub) + 1;
                     if (eval <= 0) {
                        throw new ParseException("Invalid Month value: '" + sub + "'", i);
                     }
                  }
               }
            } else {
               if (type != 5) {
                  throw new ParseException("Illegal characters for this position: '" + sub + "'", i);
               }

               sval = this.getDayOfWeekNumber(sub);
               if (sval < 0) {
                  throw new ParseException("Invalid Day-of-Week value: '" + sub + "'", i);
               }

               if (s.length() > i + 3) {
                  c = s.charAt(i + 3);
                  if (c == '-') {
                     i += 4;
                     sub = s.substring(i, i + 3);
                     eval = this.getDayOfWeekNumber(sub);
                     if (eval < 0) {
                        throw new ParseException("Invalid Day-of-Week value: '" + sub + "'", i);
                     }
                  } else if (c == '#') {
                     try {
                        i += 4;
                        this.nthdayOfWeek = Integer.parseInt(s.substring(i));
                        if (this.nthdayOfWeek < 1 || this.nthdayOfWeek > 5) {
                           throw new Exception();
                        }
                     } catch (Exception var11) {
                        throw new ParseException("A numeric value between 1 and 5 must follow the '#' option", i);
                     }
                  } else if (c == 'L') {
                     this.lastdayOfWeek = true;
                     ++i;
                  }
               }
            }

            if (eval != -1) {
               incr = 1;
            }

            this.addToSet(sval, eval, incr, type);
            return i + 3;
         } else {
            int val;
            if (c == '?') {
               ++i;
               if (i + 1 < s.length() && s.charAt(i) != ' ' && s.charAt(i + 1) != '\t') {
                  throw new ParseException("Illegal character after '?': " + s.charAt(i), i);
               } else if (type != 5 && type != 3) {
                  throw new ParseException("'?' can only be specfied for Day-of-Month or Day-of-Week.", i);
               } else {
                  if (type == 5 && !this.lastdayOfMonth) {
                     val = (Integer)this.daysOfMonth.last();
                     if (val == 98) {
                        throw new ParseException("'?' can only be specfied for Day-of-Month -OR- Day-of-Week.", i);
                     }
                  }

                  this.addToSet(98, -1, 0, type);
                  return i;
               }
            } else if (c != '*' && c != '/') {
               if (c == 'L') {
                  ++i;
                  if (type == 3) {
                     this.lastdayOfMonth = true;
                  }

                  if (type == 5) {
                     this.addToSet(7, 7, 0, type);
                  }

                  if (type == 3 && s.length() > i) {
                     c = s.charAt(i);
                     if (c == '-') {
                        ValueSet vs = this.getValue(0, s, i + 1);
                        this.lastdayOffset = vs.value;
                        if (this.lastdayOffset > 30) {
                           throw new ParseException("Offset from last day must be <= 30", i + 1);
                        }

                        i = vs.pos;
                     }

                     if (s.length() > i) {
                        c = s.charAt(i);
                        if (c == 'W') {
                           this.nearestWeekday = true;
                           ++i;
                        }
                     }
                  }

                  return i;
               } else if (c >= '0' && c <= '9') {
                  val = Integer.parseInt(String.valueOf(c));
                  ++i;
                  if (i >= s.length()) {
                     this.addToSet(val, -1, -1, type);
                     return i;
                  } else {
                     c = s.charAt(i);
                     if (c >= '0' && c <= '9') {
                        ValueSet vs = this.getValue(val, s, i);
                        val = vs.value;
                        i = vs.pos;
                     }

                     i = this.checkNext(i, s, val, type);
                     return i;
                  }
               } else {
                  throw new ParseException("Unexpected character: " + c, i);
               }
            } else if (c == '*' && i + 1 >= s.length()) {
               this.addToSet(99, -1, incr, type);
               return i + 1;
            } else if (c == '/' && (i + 1 >= s.length() || s.charAt(i + 1) == ' ' || s.charAt(i + 1) == '\t')) {
               throw new ParseException("'/' must be followed by an integer.", i);
            } else {
               if (c == '*') {
                  ++i;
               }

               c = s.charAt(i);
               int incr;
               if (c != '/') {
                  incr = 1;
               } else {
                  ++i;
                  if (i >= s.length()) {
                     throw new ParseException("Unexpected end of string.", i);
                  }

                  incr = this.getNumericValue(s, i);
                  ++i;
                  if (incr > 10) {
                     ++i;
                  }

                  if (incr > 59 && (type == 0 || type == 1)) {
                     throw new ParseException("Increment > 60 : " + incr, i);
                  }

                  if (incr > 23 && type == 2) {
                     throw new ParseException("Increment > 24 : " + incr, i);
                  }

                  if (incr > 31 && type == 3) {
                     throw new ParseException("Increment > 31 : " + incr, i);
                  }

                  if (incr > 7 && type == 5) {
                     throw new ParseException("Increment > 7 : " + incr, i);
                  }

                  if (incr > 12 && type == 4) {
                     throw new ParseException("Increment > 12 : " + incr, i);
                  }
               }

               this.addToSet(99, -1, incr, type);
               return i;
            }
         }
      }
   }

   protected int checkNext(int pos, String s, int val, int type) throws ParseException {
      int end = -1;
      if (pos >= s.length()) {
         this.addToSet(val, end, -1, type);
         return pos;
      } else {
         char c = s.charAt(pos);
         int i;
         TreeSet set;
         if (c == 'L') {
            if (type == 5) {
               if (val >= 1 && val <= 7) {
                  this.lastdayOfWeek = true;
                  set = this.getSet(type);
                  set.add(val);
                  i = pos + 1;
                  return i;
               } else {
                  throw new ParseException("Day-of-Week values must be between 1 and 7", -1);
               }
            } else {
               throw new ParseException("'L' option is not valid here. (pos=" + pos + ")", pos);
            }
         } else if (c == 'W') {
            if (type == 3) {
               this.nearestWeekday = true;
               if (val > 31) {
                  throw new ParseException("The 'W' option does not make sense with values larger than 31 (max number of days in a month)", pos);
               } else {
                  set = this.getSet(type);
                  set.add(val);
                  i = pos + 1;
                  return i;
               }
            } else {
               throw new ParseException("'W' option is not valid here. (pos=" + pos + ")", pos);
            }
         } else if (c != '#') {
            ValueSet vs;
            int v2;
            if (c == '-') {
               i = pos + 1;
               c = s.charAt(i);
               v2 = Integer.parseInt(String.valueOf(c));
               int end = v2;
               ++i;
               if (i >= s.length()) {
                  this.addToSet(val, v2, 1, type);
                  return i;
               } else {
                  c = s.charAt(i);
                  if (c >= '0' && c <= '9') {
                     vs = this.getValue(v2, s, i);
                     end = vs.value;
                     i = vs.pos;
                  }

                  if (i < s.length() && s.charAt(i) == '/') {
                     ++i;
                     c = s.charAt(i);
                     int v2 = Integer.parseInt(String.valueOf(c));
                     ++i;
                     if (i >= s.length()) {
                        this.addToSet(val, end, v2, type);
                        return i;
                     } else {
                        c = s.charAt(i);
                        if (c >= '0' && c <= '9') {
                           ValueSet vs = this.getValue(v2, s, i);
                           int v3 = vs.value;
                           this.addToSet(val, end, v3, type);
                           i = vs.pos;
                           return i;
                        } else {
                           this.addToSet(val, end, v2, type);
                           return i;
                        }
                     }
                  } else {
                     this.addToSet(val, end, 1, type);
                     return i;
                  }
               }
            } else if (c == '/') {
               i = pos + 1;
               c = s.charAt(i);
               v2 = Integer.parseInt(String.valueOf(c));
               ++i;
               if (i >= s.length()) {
                  this.addToSet(val, end, v2, type);
                  return i;
               } else {
                  c = s.charAt(i);
                  if (c >= '0' && c <= '9') {
                     vs = this.getValue(v2, s, i);
                     int v3 = vs.value;
                     this.addToSet(val, end, v3, type);
                     i = vs.pos;
                     return i;
                  } else {
                     throw new ParseException("Unexpected character '" + c + "' after '/'", i);
                  }
               }
            } else {
               this.addToSet(val, end, 0, type);
               i = pos + 1;
               return i;
            }
         } else if (type != 5) {
            throw new ParseException("'#' option is not valid here. (pos=" + pos + ")", pos);
         } else {
            i = pos + 1;

            try {
               this.nthdayOfWeek = Integer.parseInt(s.substring(i));
               if (this.nthdayOfWeek < 1 || this.nthdayOfWeek > 5) {
                  throw new Exception();
               }
            } catch (Exception var12) {
               throw new ParseException("A numeric value between 1 and 5 must follow the '#' option", i);
            }

            set = this.getSet(type);
            set.add(val);
            ++i;
            return i;
         }
      }
   }

   public String getCronExpression() {
      return this.cronExpression;
   }

   public String getExpressionSummary() {
      StringBuilder buf = new StringBuilder();
      buf.append("seconds: ");
      buf.append(this.getExpressionSetSummary((Set)this.seconds));
      buf.append("\n");
      buf.append("minutes: ");
      buf.append(this.getExpressionSetSummary((Set)this.minutes));
      buf.append("\n");
      buf.append("hours: ");
      buf.append(this.getExpressionSetSummary((Set)this.hours));
      buf.append("\n");
      buf.append("daysOfMonth: ");
      buf.append(this.getExpressionSetSummary((Set)this.daysOfMonth));
      buf.append("\n");
      buf.append("months: ");
      buf.append(this.getExpressionSetSummary((Set)this.months));
      buf.append("\n");
      buf.append("daysOfWeek: ");
      buf.append(this.getExpressionSetSummary((Set)this.daysOfWeek));
      buf.append("\n");
      buf.append("lastdayOfWeek: ");
      buf.append(this.lastdayOfWeek);
      buf.append("\n");
      buf.append("nearestWeekday: ");
      buf.append(this.nearestWeekday);
      buf.append("\n");
      buf.append("NthDayOfWeek: ");
      buf.append(this.nthdayOfWeek);
      buf.append("\n");
      buf.append("lastdayOfMonth: ");
      buf.append(this.lastdayOfMonth);
      buf.append("\n");
      buf.append("years: ");
      buf.append(this.getExpressionSetSummary((Set)this.years));
      buf.append("\n");
      return buf.toString();
   }

   protected String getExpressionSetSummary(Set<Integer> set) {
      if (set.contains(NO_SPEC)) {
         return "?";
      } else if (set.contains(ALL_SPEC)) {
         return "*";
      } else {
         StringBuilder buf = new StringBuilder();
         Iterator<Integer> itr = set.iterator();

         for(boolean first = true; itr.hasNext(); first = false) {
            Integer iVal = (Integer)itr.next();
            String val = iVal.toString();
            if (!first) {
               buf.append(",");
            }

            buf.append(val);
         }

         return buf.toString();
      }
   }

   protected String getExpressionSetSummary(ArrayList<Integer> list) {
      if (list.contains(NO_SPEC)) {
         return "?";
      } else if (list.contains(ALL_SPEC)) {
         return "*";
      } else {
         StringBuilder buf = new StringBuilder();
         Iterator<Integer> itr = list.iterator();

         for(boolean first = true; itr.hasNext(); first = false) {
            Integer iVal = (Integer)itr.next();
            String val = iVal.toString();
            if (!first) {
               buf.append(",");
            }

            buf.append(val);
         }

         return buf.toString();
      }
   }

   protected int skipWhiteSpace(int i, String s) {
      while(i < s.length() && (s.charAt(i) == ' ' || s.charAt(i) == '\t')) {
         ++i;
      }

      return i;
   }

   protected int findNextWhiteSpace(int i, String s) {
      while(i < s.length() && (s.charAt(i) != ' ' || s.charAt(i) != '\t')) {
         ++i;
      }

      return i;
   }

   protected void addToSet(int val, int end, int incr, int type) throws ParseException {
      TreeSet<Integer> set = this.getSet(type);
      if (type != 0 && type != 1) {
         if (type == 2) {
            if ((val < 0 || val > 23 || end > 23) && val != 99) {
               throw new ParseException("Hour values must be between 0 and 23", -1);
            }
         } else if (type == 3) {
            if ((val < 1 || val > 31 || end > 31) && val != 99 && val != 98) {
               throw new ParseException("Day of month values must be between 1 and 31", -1);
            }
         } else if (type == 4) {
            if ((val < 1 || val > 12 || end > 12) && val != 99) {
               throw new ParseException("Month values must be between 1 and 12", -1);
            }
         } else if (type == 5 && (val == 0 || val > 7 || end > 7) && val != 99 && val != 98) {
            throw new ParseException("Day-of-Week values must be between 1 and 7", -1);
         }
      } else if ((val < 0 || val > 59 || end > 59) && val != 99) {
         throw new ParseException("Minute and Second values must be between 0 and 59", -1);
      }

      if ((incr == 0 || incr == -1) && val != 99) {
         if (val != -1) {
            set.add(val);
         } else {
            set.add(NO_SPEC);
         }

      } else {
         int startAt = val;
         int stopAt = end;
         if (val == 99 && incr <= 0) {
            incr = 1;
            set.add(ALL_SPEC);
         }

         if (type != 0 && type != 1) {
            if (type == 2) {
               if (end == -1) {
                  stopAt = 23;
               }

               if (val == -1 || val == 99) {
                  startAt = 0;
               }
            } else if (type == 3) {
               if (end == -1) {
                  stopAt = 31;
               }

               if (val == -1 || val == 99) {
                  startAt = 1;
               }
            } else if (type == 4) {
               if (end == -1) {
                  stopAt = 12;
               }

               if (val == -1 || val == 99) {
                  startAt = 1;
               }
            } else if (type == 5) {
               if (end == -1) {
                  stopAt = 7;
               }

               if (val == -1 || val == 99) {
                  startAt = 1;
               }
            } else if (type == 6) {
               if (end == -1) {
                  stopAt = MAX_YEAR;
               }

               if (val == -1 || val == 99) {
                  startAt = 1970;
               }
            }
         } else {
            if (end == -1) {
               stopAt = 59;
            }

            if (val == -1 || val == 99) {
               startAt = 0;
            }
         }

         int max = -1;
         if (stopAt < startAt) {
            switch(type) {
            case 0:
               max = 60;
               break;
            case 1:
               max = 60;
               break;
            case 2:
               max = 24;
               break;
            case 3:
               max = 31;
               break;
            case 4:
               max = 12;
               break;
            case 5:
               max = 7;
               break;
            case 6:
               throw new IllegalArgumentException("Start year must be less than stop year");
            default:
               throw new IllegalArgumentException("Unexpected type encountered");
            }

            stopAt += max;
         }

         for(int i = startAt; i <= stopAt; i += incr) {
            if (max == -1) {
               set.add(i);
            } else {
               int i2 = i % max;
               if (i2 == 0 && (type == 4 || type == 5 || type == 3)) {
                  i2 = max;
               }

               set.add(i2);
            }
         }

      }
   }

   TreeSet<Integer> getSet(int type) {
      switch(type) {
      case 0:
         return this.seconds;
      case 1:
         return this.minutes;
      case 2:
         return this.hours;
      case 3:
         return this.daysOfMonth;
      case 4:
         return this.months;
      case 5:
         return this.daysOfWeek;
      case 6:
         return this.years;
      default:
         return null;
      }
   }

   protected ValueSet getValue(int v, String s, int i) {
      char c = s.charAt(i);

      StringBuilder s1;
      for(s1 = new StringBuilder(String.valueOf(v)); c >= '0' && c <= '9'; c = s.charAt(i)) {
         s1.append(c);
         ++i;
         if (i >= s.length()) {
            break;
         }
      }

      ValueSet val = new ValueSet();
      val.pos = i < s.length() ? i : i + 1;
      val.value = Integer.parseInt(s1.toString());
      return val;
   }

   protected int getNumericValue(String s, int i) {
      int endOfVal = this.findNextWhiteSpace(i, s);
      String val = s.substring(i, endOfVal);
      return Integer.parseInt(val);
   }

   protected int getMonthNumber(String s) {
      Integer integer = (Integer)monthMap.get(s);
      return integer == null ? -1 : integer;
   }

   protected int getDayOfWeekNumber(String s) {
      Integer integer = (Integer)dayMap.get(s);
      return integer == null ? -1 : integer;
   }

   public Date getTimeAfter(Date afterTime) {
      java.util.Calendar cl = new GregorianCalendar(this.getTimeZone());
      afterTime = new Date(afterTime.getTime() + 1000L);
      cl.setTime(afterTime);
      cl.set(14, 0);
      boolean gotOne = false;

      while(true) {
         while(true) {
            while(!gotOne) {
               if (cl.get(1) > 2999) {
                  return null;
               }

               SortedSet<Integer> st = null;
               int t = false;
               int sec = cl.get(13);
               int min = cl.get(12);
               st = this.seconds.tailSet(sec);
               if (st != null && st.size() != 0) {
                  sec = (Integer)st.first();
               } else {
                  sec = (Integer)this.seconds.first();
                  ++min;
                  cl.set(12, min);
               }

               cl.set(13, sec);
               min = cl.get(12);
               int hr = cl.get(11);
               int t = -1;
               st = this.minutes.tailSet(min);
               if (st != null && st.size() != 0) {
                  t = min;
                  min = (Integer)st.first();
               } else {
                  min = (Integer)this.minutes.first();
                  ++hr;
               }

               if (min == t) {
                  cl.set(12, min);
                  hr = cl.get(11);
                  int day = cl.get(5);
                  t = -1;
                  st = this.hours.tailSet(hr);
                  if (st != null && st.size() != 0) {
                     t = hr;
                     hr = (Integer)st.first();
                  } else {
                     hr = (Integer)this.hours.first();
                     ++day;
                  }

                  if (hr == t) {
                     cl.set(11, hr);
                     day = cl.get(5);
                     int mon = cl.get(2) + 1;
                     t = -1;
                     int tmon = mon;
                     boolean dayOfMSpec = !this.daysOfMonth.contains(NO_SPEC);
                     boolean dayOfWSpec = !this.daysOfWeek.contains(NO_SPEC);
                     int year;
                     int dow;
                     int daysToAdd;
                     if (dayOfMSpec && !dayOfWSpec) {
                        st = this.daysOfMonth.tailSet(day);
                        java.util.Calendar tcal;
                        Date nTime;
                        if (this.lastdayOfMonth) {
                           if (!this.nearestWeekday) {
                              t = day;
                              day = this.getLastDayOfMonth(mon, cl.get(1));
                              day -= this.lastdayOffset;
                              if (t > day) {
                                 ++mon;
                                 if (mon > 12) {
                                    mon = 1;
                                    tmon = 3333;
                                    cl.add(1, 1);
                                 }

                                 day = 1;
                              }
                           } else {
                              t = day;
                              day = this.getLastDayOfMonth(mon, cl.get(1));
                              day -= this.lastdayOffset;
                              tcal = java.util.Calendar.getInstance(this.getTimeZone());
                              tcal.set(13, 0);
                              tcal.set(12, 0);
                              tcal.set(11, 0);
                              tcal.set(5, day);
                              tcal.set(2, mon - 1);
                              tcal.set(1, cl.get(1));
                              dow = this.getLastDayOfMonth(mon, cl.get(1));
                              daysToAdd = tcal.get(7);
                              if (daysToAdd == 7 && day == 1) {
                                 day += 2;
                              } else if (daysToAdd == 7) {
                                 --day;
                              } else if (daysToAdd == 1 && day == dow) {
                                 day -= 2;
                              } else if (daysToAdd == 1) {
                                 ++day;
                              }

                              tcal.set(13, sec);
                              tcal.set(12, min);
                              tcal.set(11, hr);
                              tcal.set(5, day);
                              tcal.set(2, mon - 1);
                              nTime = tcal.getTime();
                              if (nTime.before(afterTime)) {
                                 day = 1;
                                 ++mon;
                              }
                           }
                        } else if (this.nearestWeekday) {
                           t = day;
                           day = (Integer)this.daysOfMonth.first();
                           tcal = java.util.Calendar.getInstance(this.getTimeZone());
                           tcal.set(13, 0);
                           tcal.set(12, 0);
                           tcal.set(11, 0);
                           tcal.set(5, day);
                           tcal.set(2, mon - 1);
                           tcal.set(1, cl.get(1));
                           dow = this.getLastDayOfMonth(mon, cl.get(1));
                           daysToAdd = tcal.get(7);
                           if (daysToAdd == 7 && day == 1) {
                              day += 2;
                           } else if (daysToAdd == 7) {
                              --day;
                           } else if (daysToAdd == 1 && day == dow) {
                              day -= 2;
                           } else if (daysToAdd == 1) {
                              ++day;
                           }

                           tcal.set(13, sec);
                           tcal.set(12, min);
                           tcal.set(11, hr);
                           tcal.set(5, day);
                           tcal.set(2, mon - 1);
                           nTime = tcal.getTime();
                           if (nTime.before(afterTime)) {
                              day = (Integer)this.daysOfMonth.first();
                              ++mon;
                           }
                        } else if (st != null && st.size() != 0) {
                           t = day;
                           day = (Integer)st.first();
                           year = this.getLastDayOfMonth(mon, cl.get(1));
                           if (day > year) {
                              day = (Integer)this.daysOfMonth.first();
                              ++mon;
                           }
                        } else {
                           day = (Integer)this.daysOfMonth.first();
                           ++mon;
                        }

                        if (day != t || mon != tmon) {
                           cl.set(13, 0);
                           cl.set(12, 0);
                           cl.set(11, 0);
                           cl.set(5, day);
                           cl.set(2, mon - 1);
                           continue;
                        }
                     } else {
                        if (!dayOfWSpec || dayOfMSpec) {
                           throw new UnsupportedOperationException("Support for specifying both a day-of-week AND a day-of-month parameter is not implemented.");
                        }

                        int lDay;
                        if (this.lastdayOfWeek) {
                           year = (Integer)this.daysOfWeek.first();
                           dow = cl.get(7);
                           daysToAdd = 0;
                           if (dow < year) {
                              daysToAdd = year - dow;
                           }

                           if (dow > year) {
                              daysToAdd = year + (7 - dow);
                           }

                           lDay = this.getLastDayOfMonth(mon, cl.get(1));
                           if (day + daysToAdd > lDay) {
                              cl.set(13, 0);
                              cl.set(12, 0);
                              cl.set(11, 0);
                              cl.set(5, 1);
                              cl.set(2, mon);
                              continue;
                           }

                           while(day + daysToAdd + 7 <= lDay) {
                              daysToAdd += 7;
                           }

                           day += daysToAdd;
                           if (daysToAdd > 0) {
                              cl.set(13, 0);
                              cl.set(12, 0);
                              cl.set(11, 0);
                              cl.set(5, day);
                              cl.set(2, mon - 1);
                              continue;
                           }
                        } else if (this.nthdayOfWeek != 0) {
                           year = (Integer)this.daysOfWeek.first();
                           dow = cl.get(7);
                           daysToAdd = 0;
                           if (dow < year) {
                              daysToAdd = year - dow;
                           } else if (dow > year) {
                              daysToAdd = year + (7 - dow);
                           }

                           boolean dayShifted = false;
                           if (daysToAdd > 0) {
                              dayShifted = true;
                           }

                           day += daysToAdd;
                           int weekOfMonth = day / 7;
                           if (day % 7 > 0) {
                              ++weekOfMonth;
                           }

                           daysToAdd = (this.nthdayOfWeek - weekOfMonth) * 7;
                           day += daysToAdd;
                           if (daysToAdd < 0 || day > this.getLastDayOfMonth(mon, cl.get(1))) {
                              cl.set(13, 0);
                              cl.set(12, 0);
                              cl.set(11, 0);
                              cl.set(5, 1);
                              cl.set(2, mon);
                              continue;
                           }

                           if (daysToAdd > 0 || dayShifted) {
                              cl.set(13, 0);
                              cl.set(12, 0);
                              cl.set(11, 0);
                              cl.set(5, day);
                              cl.set(2, mon - 1);
                              continue;
                           }
                        } else {
                           year = cl.get(7);
                           dow = (Integer)this.daysOfWeek.first();
                           st = this.daysOfWeek.tailSet(year);
                           if (st != null && st.size() > 0) {
                              dow = (Integer)st.first();
                           }

                           daysToAdd = 0;
                           if (year < dow) {
                              daysToAdd = dow - year;
                           }

                           if (year > dow) {
                              daysToAdd = dow + (7 - year);
                           }

                           lDay = this.getLastDayOfMonth(mon, cl.get(1));
                           if (day + daysToAdd > lDay) {
                              cl.set(13, 0);
                              cl.set(12, 0);
                              cl.set(11, 0);
                              cl.set(5, 1);
                              cl.set(2, mon);
                              continue;
                           }

                           if (daysToAdd > 0) {
                              cl.set(13, 0);
                              cl.set(12, 0);
                              cl.set(11, 0);
                              cl.set(5, day + daysToAdd);
                              cl.set(2, mon - 1);
                              continue;
                           }
                        }
                     }

                     cl.set(5, day);
                     mon = cl.get(2) + 1;
                     year = cl.get(1);
                     t = -1;
                     if (year > MAX_YEAR) {
                        return null;
                     }

                     st = this.months.tailSet(mon);
                     if (st != null && st.size() != 0) {
                        t = mon;
                        mon = (Integer)st.first();
                     } else {
                        mon = (Integer)this.months.first();
                        ++year;
                     }

                     if (mon != t) {
                        cl.set(13, 0);
                        cl.set(12, 0);
                        cl.set(11, 0);
                        cl.set(5, 1);
                        cl.set(2, mon - 1);
                        cl.set(1, year);
                     } else {
                        cl.set(2, mon - 1);
                        year = cl.get(1);
                        t = true;
                        st = this.years.tailSet(year);
                        if (st == null || st.size() == 0) {
                           return null;
                        }

                        t = year;
                        year = (Integer)st.first();
                        if (year != t) {
                           cl.set(13, 0);
                           cl.set(12, 0);
                           cl.set(11, 0);
                           cl.set(5, 1);
                           cl.set(2, 0);
                           cl.set(1, year);
                        } else {
                           cl.set(1, year);
                           gotOne = true;
                        }
                     }
                  } else {
                     cl.set(13, 0);
                     cl.set(12, 0);
                     cl.set(5, day);
                     this.setCalendarHour(cl, hr);
                  }
               } else {
                  cl.set(13, 0);
                  cl.set(12, min);
                  this.setCalendarHour(cl, hr);
               }
            }

            return cl.getTime();
         }
      }
   }

   protected void setCalendarHour(java.util.Calendar cal, int hour) {
      cal.set(11, hour);
      if (cal.get(11) != hour && hour != 24) {
         cal.set(11, hour + 1);
      }

   }

   public Date getTimeBefore(Date endTime) {
      return null;
   }

   public Date getFinalFireTime() {
      return null;
   }

   protected boolean isLeapYear(int year) {
      return year % 4 == 0 && year % 100 != 0 || year % 400 == 0;
   }

   protected int getLastDayOfMonth(int monthNum, int year) {
      switch(monthNum) {
      case 1:
         return 31;
      case 2:
         return this.isLeapYear(year) ? 29 : 28;
      case 3:
         return 31;
      case 4:
         return 30;
      case 5:
         return 31;
      case 6:
         return 30;
      case 7:
         return 31;
      case 8:
         return 31;
      case 9:
         return 30;
      case 10:
         return 31;
      case 11:
         return 30;
      case 12:
         return 31;
      default:
         throw new IllegalArgumentException("Illegal month number: " + monthNum);
      }
   }

   private void readObject(ObjectInputStream stream) throws IOException, ClassNotFoundException {
      stream.defaultReadObject();

      try {
         this.buildExpression(this.cronExpression);
      } catch (Exception var3) {
      }

   }

   /** @deprecated */
   @Deprecated
   public Object clone() {
      return new CronExpression(this);
   }

   static {
      monthMap.put("JAN", 0);
      monthMap.put("FEB", 1);
      monthMap.put("MAR", 2);
      monthMap.put("APR", 3);
      monthMap.put("MAY", 4);
      monthMap.put("JUN", 5);
      monthMap.put("JUL", 6);
      monthMap.put("AUG", 7);
      monthMap.put("SEP", 8);
      monthMap.put("OCT", 9);
      monthMap.put("NOV", 10);
      monthMap.put("DEC", 11);
      dayMap.put("SUN", 1);
      dayMap.put("MON", 2);
      dayMap.put("TUE", 3);
      dayMap.put("WED", 4);
      dayMap.put("THU", 5);
      dayMap.put("FRI", 6);
      dayMap.put("SAT", 7);
      MAX_YEAR = java.util.Calendar.getInstance().get(1) + 100;
   }
}
