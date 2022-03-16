package org.quartz;

import java.io.Serializable;
import java.util.Map;
import org.quartz.utils.StringKeyDirtyFlagMap;

public class JobDataMap extends StringKeyDirtyFlagMap implements Serializable {
   private static final long serialVersionUID = -6939901990106713909L;

   public JobDataMap() {
      super(15);
   }

   public JobDataMap(Map<?, ?> map) {
      this();
      this.putAll(map);
   }

   public void putAsString(String key, boolean value) {
      String strValue = Boolean.valueOf(value).toString();
      super.put(key, strValue);
   }

   public void putAsString(String key, Boolean value) {
      String strValue = value.toString();
      super.put(key, strValue);
   }

   public void putAsString(String key, char value) {
      String strValue = Character.valueOf(value).toString();
      super.put(key, strValue);
   }

   public void putAsString(String key, Character value) {
      String strValue = value.toString();
      super.put(key, strValue);
   }

   public void putAsString(String key, double value) {
      String strValue = Double.toString(value);
      super.put(key, strValue);
   }

   public void putAsString(String key, Double value) {
      String strValue = value.toString();
      super.put(key, strValue);
   }

   public void putAsString(String key, float value) {
      String strValue = Float.toString(value);
      super.put(key, strValue);
   }

   public void putAsString(String key, Float value) {
      String strValue = value.toString();
      super.put(key, strValue);
   }

   public void putAsString(String key, int value) {
      String strValue = Integer.valueOf(value).toString();
      super.put(key, strValue);
   }

   public void putAsString(String key, Integer value) {
      String strValue = value.toString();
      super.put(key, strValue);
   }

   public void putAsString(String key, long value) {
      String strValue = Long.valueOf(value).toString();
      super.put(key, strValue);
   }

   public void putAsString(String key, Long value) {
      String strValue = value.toString();
      super.put(key, strValue);
   }

   public int getIntFromString(String key) {
      Object obj = this.get(key);
      return new Integer((String)obj);
   }

   public int getIntValue(String key) {
      Object obj = this.get(key);
      return obj instanceof String ? this.getIntFromString(key) : this.getInt(key);
   }

   public Integer getIntegerFromString(String key) {
      Object obj = this.get(key);
      return new Integer((String)obj);
   }

   public boolean getBooleanValueFromString(String key) {
      Object obj = this.get(key);
      return Boolean.valueOf((String)obj);
   }

   public boolean getBooleanValue(String key) {
      Object obj = this.get(key);
      return obj instanceof String ? this.getBooleanValueFromString(key) : this.getBoolean(key);
   }

   public Boolean getBooleanFromString(String key) {
      Object obj = this.get(key);
      return Boolean.valueOf((String)obj);
   }

   public char getCharFromString(String key) {
      Object obj = this.get(key);
      return ((String)obj).charAt(0);
   }

   public Character getCharacterFromString(String key) {
      Object obj = this.get(key);
      return ((String)obj).charAt(0);
   }

   public double getDoubleValueFromString(String key) {
      Object obj = this.get(key);
      return Double.valueOf((String)obj);
   }

   public double getDoubleValue(String key) {
      Object obj = this.get(key);
      return obj instanceof String ? this.getDoubleValueFromString(key) : this.getDouble(key);
   }

   public Double getDoubleFromString(String key) {
      Object obj = this.get(key);
      return new Double((String)obj);
   }

   public float getFloatValueFromString(String key) {
      Object obj = this.get(key);
      return new Float((String)obj);
   }

   public float getFloatValue(String key) {
      Object obj = this.get(key);
      return obj instanceof String ? this.getFloatValueFromString(key) : this.getFloat(key);
   }

   public Float getFloatFromString(String key) {
      Object obj = this.get(key);
      return new Float((String)obj);
   }

   public long getLongValueFromString(String key) {
      Object obj = this.get(key);
      return new Long((String)obj);
   }

   public long getLongValue(String key) {
      Object obj = this.get(key);
      return obj instanceof String ? this.getLongValueFromString(key) : this.getLong(key);
   }

   public Long getLongFromString(String key) {
      Object obj = this.get(key);
      return new Long((String)obj);
   }
}
