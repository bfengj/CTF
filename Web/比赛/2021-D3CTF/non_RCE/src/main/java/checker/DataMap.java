/**
 * Alipay.com Inc. Copyright (c) 2004-2021 All Rights Reserved.
 */
package checker;

/**
 *
 * @author fantasyC4t
 * @version : DataMap.java, v 0.1 2021年03月03日 11:19 上午 th1s Exp $
 */

import java.io.Serializable;
import java.util.*;

public class DataMap implements Map, Serializable {

    private Map wrapperMap;
    private Map values;


    public DataMap() {
        this.wrapperMap = new HashMap();
        this.values = null;
    }

    public DataMap(Map wrapperMap, Map values) {
        this.wrapperMap = wrapperMap;
        this.values = values;
    }

    public DataMap(Map wrapperMap) {
        this.wrapperMap = wrapperMap;
        this.values = null;
    }

    static int hash(Object one) {
        return one == null ? 0 : one.hashCode();
    }

    static boolean equality(Object one, Object two) {
        return one == null ? two == null : one.equals(two);
    }

    protected Map getMap() {
        return this.wrapperMap;
    }

    public void clear() {
        this.wrapperMap.clear();
    }

    public boolean containsKey(Object key) {
        return this.wrapperMap.containsKey(key);
    }

    public boolean containsValue(Object value) {
        return this.wrapperMap.containsValue(value);
    }

    public Set entrySet() {
        return new DataMap.EntrySet();
    }

    public Object get(Object key) {
        Object v = null;
        if (this.values != null) {
            v = this.values.get(key);
        }

        if(v == null){
            v = this.wrapperMap.get(key);
            if (this.values == null) {
                this.values = new HashMap(this.wrapperMap.size());
            }

            this.values.put(key, v);
        }

        return  v;
    }

    public boolean isEmpty() {
        return this.wrapperMap.isEmpty();
    }

    public Set keySet() {
        return this.wrapperMap.keySet();
    }

    public Object put(Object key, Object value) {
        return this.wrapperMap.put(key, value);
    }

    public void putAll(Map mapToCopy) {
        this.wrapperMap.putAll(mapToCopy);
    }

    public Object remove(Object key) {
        return this.wrapperMap.remove(key);
    }

    public int size() {
        return this.wrapperMap.size();
    }

    public Collection values() {
        return this.wrapperMap.values();
    }

    public boolean equals(Object object) {
        return object == this ? true : this.wrapperMap.equals(object);
    }

    private final class Entry implements java.util.Map.Entry, Serializable {
        private final Object key;
        private Object value;

        Entry(Object k) {
            this.key = k;
        }

        public Object getKey() {
            return this.key;
        }

        public Object getValue() {
            if (this.value == null) {
                this.value = DataMap.this.get(this.key);
            }

            return this.value;
        }

        public Object setValue(Object value) {
            throw new UnsupportedOperationException();
        }

        public String toString() {
            return this.getKey() + "=" + this.getValue();
        }

        public int hashCode() {
            return DataMap.hash(this.getKey()) ^ DataMap.hash(this.getValue());
        }

        public boolean equals(Object obj) {
            if (obj == this) {
                return true;
            } else if (!(obj instanceof java.util.Map.Entry)) {
                return false;
            } else {
                java.util.Map.Entry<?, ?> other = (java.util.Map.Entry)obj;
                return DataMap.equality(this.getKey(), other.getKey()) && DataMap.equality(this.getValue(), other.getValue());
            }
        }
    }

    public int hashCode() {
        return this.wrapperMap.hashCode();
    }

    public String toString() {
        return this.wrapperMap.toString();
    }

    private final class EntryIterator implements Iterator<Map.Entry> {
        private final Iterator iter;
        private Object last;

        EntryIterator() {
            this.iter = DataMap.this.wrapperMap.keySet().iterator();
        }

        public boolean hasNext() {
            return this.iter.hasNext();
        }

        public java.util.Map.Entry next() {
            this.last = this.iter.next();
            return DataMap.this.new Entry(this.last);
        }

        public void remove() {
            this.iter.remove();
            if (DataMap.this.values != null) {
                DataMap.this.values.remove(this.last);
            }

        }
    }

    private final class EntrySet extends AbstractSet<Map.Entry> {
        EntrySet() {
        }

        public Iterator<java.util.Map.Entry> iterator() {
            return DataMap.this.new EntryIterator();
        }

        public int size() {
            return DataMap.this.size();
        }
    }
}
