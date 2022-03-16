package org.quartz.utils;

import java.io.Serializable;

public class StringKeyDirtyFlagMap extends DirtyFlagMap<String, Object> {
   static final long serialVersionUID = -9076749120524952280L;
   /** @deprecated */
   private boolean allowsTransientData = false;

   public StringKeyDirtyFlagMap() {
   }

   public StringKeyDirtyFlagMap(int initialCapacity) {
      super(initialCapacity);
   }

   public StringKeyDirtyFlagMap(int initialCapacity, float loadFactor) {
      super(initialCapacity, loadFactor);
   }

   public boolean equals(Object obj) {
      return super.equals(obj);
   }

   public int hashCode() {
      return this.getWrappedMap().hashCode();
   }

   public String[] getKeys() {
      return (String[])this.keySet().toArray(new String[this.size()]);
   }

   /** @deprecated */
   public void setAllowsTransientData(boolean allowsTransientData) {
      if (this.containsTransientData() && !allowsTransientData) {
         throw new IllegalStateException("Cannot set property 'allowsTransientData' to 'false' when data map contains non-serializable objects.");
      } else {
         this.allowsTransientData = allowsTransientData;
      }
   }

   /** @deprecated */
   public boolean getAllowsTransientData() {
      return this.allowsTransientData;
   }

   /** @deprecated */
   public boolean containsTransientData() {
      if (!this.getAllowsTransientData()) {
         return false;
      } else {
         String[] keys = this.getKeys();

         for(int i = 0; i < keys.length; ++i) {
            Object o = super.get(keys[i]);
            if (!(o instanceof Serializable)) {
               return true;
            }
         }

         return false;
      }
   }

   /** @deprecated */
   public void removeTransientData() {
      if (this.getAllowsTransientData()) {
         String[] keys = this.getKeys();

         for(int i = 0; i < keys.length; ++i) {
            Object o = super.get(keys[i]);
            if (!(o instanceof Serializable)) {
               this.remove(keys[i]);
            }
         }

      }
   }

   public void put(String key, int value) {
      super.put(key, value);
   }

   public void put(String key, long value) {
      super.put(key, value);
   }

   public void put(String key, float value) {
      super.put(key, value);
   }

   public void put(String key, double value) {
      super.put(key, value);
   }

   public void put(String key, boolean value) {
      super.put(key, value);
   }

   public void put(String key, char value) {
      super.put(key, value);
   }

   public void put(String key, String value) {
      super.put(key, value);
   }

   public Object put(String key, Object value) {
      return super.put(key, value);
   }

   public int getInt(String key) {
      Object obj = this.get(key);

      try {
         return obj instanceof Integer ? (Integer)obj : Integer.parseInt((String)obj);
      } catch (Exception var4) {
         throw new ClassCastException("Identified object is not an Integer.");
      }
   }

   public long getLong(String key) {
      Object obj = this.get(key);

      try {
         return obj instanceof Long ? (Long)obj : Long.parseLong((String)obj);
      } catch (Exception var4) {
         throw new ClassCastException("Identified object is not a Long.");
      }
   }

   public float getFloat(String key) {
      Object obj = this.get(key);

      try {
         return obj instanceof Float ? (Float)obj : Float.parseFloat((String)obj);
      } catch (Exception var4) {
         throw new ClassCastException("Identified object is not a Float.");
      }
   }

   public double getDouble(String key) {
      Object obj = this.get(key);

      try {
         return obj instanceof Double ? (Double)obj : Double.parseDouble((String)obj);
      } catch (Exception var4) {
         throw new ClassCastException("Identified object is not a Double.");
      }
   }

   public boolean getBoolean(String key) {
      Object obj = this.get(key);

      try {
         return obj instanceof Boolean ? (Boolean)obj : Boolean.parseBoolean((String)obj);
      } catch (Exception var4) {
         throw new ClassCastException("Identified object is not a Boolean.");
      }
   }

   public char getChar(String key) {
      Object obj = this.get(key);

      try {
         return obj instanceof Character ? (Character)obj : ((String)obj).charAt(0);
      } catch (Exception var4) {
         throw new ClassCastException("Identified object is not a Character.");
      }
   }

   public String getString(String key) {
      Object obj = this.get(key);

      try {
         return (String)obj;
      } catch (Exception var4) {
         throw new ClassCastException("Identified object is not a String.");
      }
   }
}
