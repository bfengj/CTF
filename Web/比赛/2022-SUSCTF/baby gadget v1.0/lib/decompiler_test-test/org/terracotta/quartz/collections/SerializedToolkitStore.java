package org.terracotta.quartz.collections;

import java.io.IOException;
import java.io.Serializable;
import java.util.Collection;
import java.util.Collections;
import java.util.HashMap;
import java.util.HashSet;
import java.util.Iterator;
import java.util.Map;
import java.util.Set;
import java.util.Map.Entry;
import org.terracotta.toolkit.concurrent.locks.ToolkitReadWriteLock;
import org.terracotta.toolkit.config.Configuration;
import org.terracotta.toolkit.search.QueryBuilder;
import org.terracotta.toolkit.search.attribute.ToolkitAttributeExtractor;
import org.terracotta.toolkit.store.ToolkitStore;

public class SerializedToolkitStore<K, V extends Serializable> implements ToolkitStore<K, V> {
   private final ToolkitStore<String, V> toolkitStore;

   public SerializedToolkitStore(ToolkitStore toolkitMap) {
      this.toolkitStore = toolkitMap;
   }

   public int size() {
      return this.toolkitStore.size();
   }

   public boolean isEmpty() {
      return this.toolkitStore.isEmpty();
   }

   private static String serializeToString(Object key) {
      try {
         return SerializationHelper.serializeToString(key);
      } catch (IOException var2) {
         throw new RuntimeException(var2);
      }
   }

   private static Object deserializeFromString(String key) {
      try {
         return SerializationHelper.deserializeFromString(key);
      } catch (IOException var2) {
         throw new RuntimeException(var2);
      } catch (ClassNotFoundException var3) {
         throw new RuntimeException(var3);
      }
   }

   public boolean containsKey(Object key) {
      return this.toolkitStore.containsKey(serializeToString(key));
   }

   public V get(Object key) {
      return (Serializable)this.toolkitStore.get(serializeToString(key));
   }

   public V put(K key, V value) {
      return (Serializable)this.toolkitStore.put(serializeToString(key), value);
   }

   public V remove(Object key) {
      return (Serializable)this.toolkitStore.remove(serializeToString(key));
   }

   public void putAll(Map<? extends K, ? extends V> m) {
      Map<String, V> tempMap = new HashMap();
      Iterator i$ = m.entrySet().iterator();

      while(i$.hasNext()) {
         Entry<? extends K, ? extends V> entry = (Entry)i$.next();
         tempMap.put(serializeToString(entry.getKey()), entry.getValue());
      }

      this.toolkitStore.putAll(tempMap);
   }

   public void clear() {
      this.toolkitStore.clear();
   }

   public Set<K> keySet() {
      return new SerializedToolkitStore.ToolkitKeySet(this.toolkitStore.keySet());
   }

   public boolean isDestroyed() {
      return this.toolkitStore.isDestroyed();
   }

   public void destroy() {
      this.toolkitStore.destroy();
   }

   public String getName() {
      return this.toolkitStore.getName();
   }

   public ToolkitReadWriteLock createLockForKey(K key) {
      return this.toolkitStore.createLockForKey(serializeToString(key));
   }

   public void removeNoReturn(Object key) {
      this.toolkitStore.removeNoReturn(serializeToString(key));
   }

   public void putNoReturn(K key, V value) {
      this.toolkitStore.putNoReturn(serializeToString(key), value);
   }

   public Map<K, V> getAll(Collection<? extends K> keys) {
      HashSet<String> tempSet = new HashSet();
      Iterator i$ = keys.iterator();

      Object tempMap;
      while(i$.hasNext()) {
         tempMap = i$.next();
         tempSet.add(serializeToString(tempMap));
      }

      Map<String, V> m = this.toolkitStore.getAll(tempSet);
      tempMap = m.isEmpty() ? Collections.EMPTY_MAP : new HashMap();
      Iterator i$ = m.entrySet().iterator();

      while(i$.hasNext()) {
         Entry<String, V> entry = (Entry)i$.next();
         ((Map)tempMap).put(deserializeFromString((String)entry.getKey()), entry.getValue());
      }

      return (Map)tempMap;
   }

   public Configuration getConfiguration() {
      return this.toolkitStore.getConfiguration();
   }

   public void setConfigField(String name, Serializable value) {
      this.toolkitStore.setConfigField(name, value);
   }

   public boolean containsValue(Object value) {
      return this.toolkitStore.containsValue(value);
   }

   public V putIfAbsent(K key, V value) {
      return (Serializable)this.toolkitStore.putIfAbsent(serializeToString(key), value);
   }

   public Set<Entry<K, V>> entrySet() {
      return new SerializedToolkitStore.ToolkitEntrySet(this.toolkitStore.entrySet());
   }

   public Collection<V> values() {
      return this.toolkitStore.values();
   }

   public boolean remove(Object key, Object value) {
      return this.toolkitStore.remove(serializeToString(key), value);
   }

   public boolean replace(K key, V oldValue, V newValue) {
      return this.toolkitStore.replace(serializeToString(key), oldValue, newValue);
   }

   public V replace(K key, V value) {
      return (Serializable)this.toolkitStore.replace(serializeToString(key), value);
   }

   public void setAttributeExtractor(ToolkitAttributeExtractor attrExtractor) {
      this.toolkitStore.setAttributeExtractor(attrExtractor);
   }

   public QueryBuilder createQueryBuilder() {
      throw new UnsupportedOperationException();
   }

   private static class ToolkitKeyIterator<K> implements Iterator<K> {
      private final Iterator<String> iter;

      public ToolkitKeyIterator(Iterator<String> iter) {
         this.iter = iter;
      }

      public boolean hasNext() {
         return this.iter.hasNext();
      }

      public K next() {
         String k = (String)this.iter.next();
         return k == null ? null : SerializedToolkitStore.deserializeFromString(k);
      }

      public void remove() {
         this.iter.remove();
      }
   }

   private static class ToolkitKeySet<K> implements Set<K> {
      private final Set<String> set;

      public ToolkitKeySet(Set<String> set) {
         this.set = set;
      }

      public int size() {
         return this.set.size();
      }

      public boolean isEmpty() {
         return this.set.isEmpty();
      }

      public boolean contains(Object o) {
         return this.set.contains(SerializedToolkitStore.serializeToString(o));
      }

      public Iterator<K> iterator() {
         return new SerializedToolkitStore.ToolkitKeyIterator(this.set.iterator());
      }

      public Object[] toArray() {
         throw new UnsupportedOperationException();
      }

      public <T> T[] toArray(T[] a) {
         throw new UnsupportedOperationException();
      }

      public boolean add(K e) {
         throw new UnsupportedOperationException();
      }

      public boolean remove(Object o) {
         throw new UnsupportedOperationException();
      }

      public boolean containsAll(Collection<?> c) {
         throw new UnsupportedOperationException();
      }

      public boolean addAll(Collection<? extends K> c) {
         throw new UnsupportedOperationException();
      }

      public boolean retainAll(Collection<?> c) {
         throw new UnsupportedOperationException();
      }

      public boolean removeAll(Collection<?> c) {
         throw new UnsupportedOperationException();
      }

      public void clear() {
         throw new UnsupportedOperationException();
      }
   }

   private static class ToolkitMapEntry<K, V> implements Entry<K, V> {
      private final K k;
      private final V v;

      public ToolkitMapEntry(K k, V v) {
         this.k = k;
         this.v = v;
      }

      public K getKey() {
         return this.k;
      }

      public V getValue() {
         return this.v;
      }

      public V setValue(V value) {
         throw new UnsupportedOperationException();
      }
   }

   private static class ToolkitEntryIterator<K, V> implements Iterator<Entry<K, V>> {
      private final Iterator<Entry<String, V>> iter;

      public ToolkitEntryIterator(Iterator<Entry<String, V>> iter) {
         this.iter = iter;
      }

      public boolean hasNext() {
         return this.iter.hasNext();
      }

      public Entry<K, V> next() {
         Entry<String, V> entry = (Entry)this.iter.next();
         return entry == null ? null : new SerializedToolkitStore.ToolkitMapEntry(SerializedToolkitStore.deserializeFromString((String)entry.getKey()), entry.getValue());
      }

      public void remove() {
         this.iter.remove();
      }
   }

   private static class ToolkitEntrySet<K, V> implements Set<Entry<K, V>> {
      private final Set<Entry<String, V>> set;

      public ToolkitEntrySet(Set<Entry<String, V>> set) {
         this.set = set;
      }

      public int size() {
         return this.set.size();
      }

      public boolean isEmpty() {
         return this.set.isEmpty();
      }

      public boolean contains(Object o) {
         if (!(o instanceof Entry)) {
            return false;
         } else {
            Entry<K, V> entry = (Entry)o;
            SerializedToolkitStore.ToolkitMapEntry<String, V> toolkitEntry = null;
            toolkitEntry = new SerializedToolkitStore.ToolkitMapEntry(SerializedToolkitStore.serializeToString(entry.getKey()), entry.getValue());
            return this.set.contains(toolkitEntry);
         }
      }

      public Iterator<Entry<K, V>> iterator() {
         return new SerializedToolkitStore.ToolkitEntryIterator(this.set.iterator());
      }

      public Object[] toArray() {
         throw new UnsupportedOperationException();
      }

      public <T> T[] toArray(T[] a) {
         throw new UnsupportedOperationException();
      }

      public boolean add(Entry<K, V> e) {
         throw new UnsupportedOperationException();
      }

      public boolean remove(Object o) {
         throw new UnsupportedOperationException();
      }

      public boolean containsAll(Collection<?> c) {
         throw new UnsupportedOperationException();
      }

      public boolean addAll(Collection<? extends Entry<K, V>> c) {
         throw new UnsupportedOperationException();
      }

      public boolean retainAll(Collection<?> c) {
         throw new UnsupportedOperationException();
      }

      public boolean removeAll(Collection<?> c) {
         throw new UnsupportedOperationException();
      }

      public void clear() {
         throw new UnsupportedOperationException();
      }
   }
}
