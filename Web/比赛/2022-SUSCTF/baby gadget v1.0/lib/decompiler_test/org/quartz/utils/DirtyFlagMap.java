package org.quartz.utils;

import java.io.Serializable;
import java.lang.reflect.Array;
import java.util.Collection;
import java.util.HashMap;
import java.util.Iterator;
import java.util.Map;
import java.util.Set;
import java.util.Map.Entry;

public class DirtyFlagMap<K, V> implements Map<K, V>, Cloneable, Serializable {
   private static final long serialVersionUID = 1433884852607126222L;
   private boolean dirty = false;
   private Map<K, V> map;

   public DirtyFlagMap() {
      this.map = new HashMap();
   }

   public DirtyFlagMap(int initialCapacity) {
      this.map = new HashMap(initialCapacity);
   }

   public DirtyFlagMap(int initialCapacity, float loadFactor) {
      this.map = new HashMap(initialCapacity, loadFactor);
   }

   public void clearDirtyFlag() {
      this.dirty = false;
   }

   public boolean isDirty() {
      return this.dirty;
   }

   public Map<K, V> getWrappedMap() {
      return this.map;
   }

   public void clear() {
      if (!this.map.isEmpty()) {
         this.dirty = true;
      }

      this.map.clear();
   }

   public boolean containsKey(Object key) {
      return this.map.containsKey(key);
   }

   public boolean containsValue(Object val) {
      return this.map.containsValue(val);
   }

   public Set<Entry<K, V>> entrySet() {
      return new DirtyFlagMap.DirtyFlagMapEntrySet(this.map.entrySet());
   }

   public boolean equals(Object obj) {
      return obj != null && obj instanceof DirtyFlagMap ? this.map.equals(((DirtyFlagMap)obj).getWrappedMap()) : false;
   }

   public int hashCode() {
      return this.map.hashCode();
   }

   public V get(Object key) {
      return this.map.get(key);
   }

   public boolean isEmpty() {
      return this.map.isEmpty();
   }

   public Set<K> keySet() {
      return new DirtyFlagMap.DirtyFlagSet(this.map.keySet());
   }

   public V put(K key, V val) {
      this.dirty = true;
      return this.map.put(key, val);
   }

   public void putAll(Map<? extends K, ? extends V> t) {
      if (!t.isEmpty()) {
         this.dirty = true;
      }

      this.map.putAll(t);
   }

   public V remove(Object key) {
      V obj = this.map.remove(key);
      if (obj != null) {
         this.dirty = true;
      }

      return obj;
   }

   public int size() {
      return this.map.size();
   }

   public Collection<V> values() {
      return new DirtyFlagMap.DirtyFlagCollection(this.map.values());
   }

   public Object clone() {
      try {
         DirtyFlagMap<K, V> copy = (DirtyFlagMap)super.clone();
         if (this.map instanceof HashMap) {
            copy.map = (Map)((HashMap)this.map).clone();
         }

         return copy;
      } catch (CloneNotSupportedException var3) {
         throw new IncompatibleClassChangeError("Not Cloneable.");
      }
   }

   private class DirtyFlagMapEntry implements Entry<K, V> {
      private Entry<K, V> entry;

      public DirtyFlagMapEntry(Entry<K, V> entry) {
         this.entry = entry;
      }

      public V setValue(V o) {
         DirtyFlagMap.this.dirty = true;
         return this.entry.setValue(o);
      }

      public K getKey() {
         return this.entry.getKey();
      }

      public V getValue() {
         return this.entry.getValue();
      }

      public boolean equals(Object o) {
         return this.entry.equals(o);
      }
   }

   private class DirtyFlagMapEntryIterator extends DirtyFlagMap<K, V>.DirtyFlagIterator<Entry<K, V>> {
      public DirtyFlagMapEntryIterator(Iterator<Entry<K, V>> iterator) {
         super(iterator);
      }

      public DirtyFlagMap<K, V>.DirtyFlagMapEntry next() {
         return DirtyFlagMap.this.new DirtyFlagMapEntry((Entry)super.next());
      }
   }

   private class DirtyFlagMapEntrySet extends DirtyFlagMap<K, V>.DirtyFlagSet<Entry<K, V>> {
      public DirtyFlagMapEntrySet(Set<Entry<K, V>> set) {
         super(set);
      }

      public Iterator<Entry<K, V>> iterator() {
         return DirtyFlagMap.this.new DirtyFlagMapEntryIterator(this.getWrappedSet().iterator());
      }

      public Object[] toArray() {
         return this.toArray(new Object[super.size()]);
      }

      public <U> U[] toArray(U[] array) {
         if (!array.getClass().getComponentType().isAssignableFrom(Entry.class)) {
            throw new IllegalArgumentException("Array must be of type assignable from Map.Entry");
         } else {
            int size = super.size();
            U[] result = array.length < size ? (Object[])((Object[])Array.newInstance(array.getClass().getComponentType(), size)) : array;
            Iterator<Entry<K, V>> entryIter = this.iterator();

            for(int i = 0; i < size; ++i) {
               result[i] = entryIter.next();
            }

            if (result.length > size) {
               result[size] = null;
            }

            return result;
         }
      }
   }

   private class DirtyFlagIterator<T> implements Iterator<T> {
      private Iterator<T> iterator;

      public DirtyFlagIterator(Iterator<T> iterator) {
         this.iterator = iterator;
      }

      public void remove() {
         DirtyFlagMap.this.dirty = true;
         this.iterator.remove();
      }

      public boolean hasNext() {
         return this.iterator.hasNext();
      }

      public T next() {
         return this.iterator.next();
      }
   }

   private class DirtyFlagSet<T> extends DirtyFlagMap<K, V>.DirtyFlagCollection<T> implements Set<T> {
      public DirtyFlagSet(Set<T> set) {
         super(set);
      }

      protected Set<T> getWrappedSet() {
         return (Set)this.getWrappedCollection();
      }
   }

   private class DirtyFlagCollection<T> implements Collection<T> {
      private Collection<T> collection;

      public DirtyFlagCollection(Collection<T> c) {
         this.collection = c;
      }

      protected Collection<T> getWrappedCollection() {
         return this.collection;
      }

      public Iterator<T> iterator() {
         return DirtyFlagMap.this.new DirtyFlagIterator(this.collection.iterator());
      }

      public boolean remove(Object o) {
         boolean removed = this.collection.remove(o);
         if (removed) {
            DirtyFlagMap.this.dirty = true;
         }

         return removed;
      }

      public boolean removeAll(Collection<?> c) {
         boolean changed = this.collection.removeAll(c);
         if (changed) {
            DirtyFlagMap.this.dirty = true;
         }

         return changed;
      }

      public boolean retainAll(Collection<?> c) {
         boolean changed = this.collection.retainAll(c);
         if (changed) {
            DirtyFlagMap.this.dirty = true;
         }

         return changed;
      }

      public void clear() {
         if (!this.collection.isEmpty()) {
            DirtyFlagMap.this.dirty = true;
         }

         this.collection.clear();
      }

      public int size() {
         return this.collection.size();
      }

      public boolean isEmpty() {
         return this.collection.isEmpty();
      }

      public boolean contains(Object o) {
         return this.collection.contains(o);
      }

      public boolean add(T o) {
         return this.collection.add(o);
      }

      public boolean addAll(Collection<? extends T> c) {
         return this.collection.addAll(c);
      }

      public boolean containsAll(Collection<?> c) {
         return this.collection.containsAll(c);
      }

      public Object[] toArray() {
         return this.collection.toArray();
      }

      public <U> U[] toArray(U[] array) {
         return this.collection.toArray(array);
      }
   }
}
