package com.ctf.badbean.bean;


import java.io.Serializable;
import java.lang.reflect.Method;
import java.util.Iterator;
import java.util.List;

public class MyBean implements Serializable {
    private Object url;
    private Object message;
    private Object obj;
    private Class<?> beanClass;
    public MyBean() {
    }
    public MyBean(Object url, Object message,Object obj,Class<?> beanClass) {
        this.url = url;
        this.message = message;
        this.obj = obj;
        this.beanClass = beanClass;
    }


    public String toString() {
        StringBuffer sb = new StringBuffer(128);
        try {
            List<PropertyDescriptor> propertyDescriptors = BeanIntrospector.getPropertyDescriptorsWithGetters(this.beanClass);
            Iterator flag = propertyDescriptors.iterator();

            while(flag.hasNext()) {
                PropertyDescriptor propertyDescriptor = (PropertyDescriptor)flag.next();
                String propertyName = propertyDescriptor.getName();
                Method getter = propertyDescriptor.getReadMethod();
                Object value = getter.invoke(this.obj, new Object[0]);
            }
        } catch (Exception e) {
            Class<? extends Object> clazz = this.obj.getClass();
            String errorMessage = e.getMessage();
            sb.append(String.format("\n\nEXCEPTION: Could not complete %s.toString(): %s\n", clazz, errorMessage));
        }

        return sb.toString();
    }

    public Object getMessage() {
        return message;
    }

    public void setMessage(Object message) {
        this.message = message;
    }

    public Object getUrl() {
        return url;
    }

    public void setUrl(Object url) {
        this.url = url;
    }



}
