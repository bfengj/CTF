# 前言

学习一下Java的动态代理，简要记录一下学习的东西，内容大部分都直接摘抄自参考链接中的文章，将重点摘录了出来，以便遗忘的时候查阅。



# 概念

代理模式Java当中最常用的设计模式之一。其特征是代理类与委托类有同样的接口，代理类主要负责为委托类预处理消息、过滤消息、把消息转发给委托类，以及事后处理消息等。而Java的代理机制分为静态代理和动态代理，而这里我们主要重点学习java自带的jdk动态代理机制。

![pic38](D:\this_is_feng\github\CTF\Web\picture\pic38.png)



# 静态代理

一个例子：

```java
package com.javalearn.summer.proxy;

public interface Rental {
    public void sale();
}
```



```java
package com.javalearn.summer.proxy;

//委托类
public class Entrust implements Rental{

    @Override
    public void sale() {
        System.out.println("出租房子");
    }
}

```



```java
package com.javalearn.summer.proxy;


//代理类
public class AgentRental implements Rental{
    private Rental target;
    public AgentRental(Rental target){
        this.target = target;
    }
    @Override
    public void sale() {
        System.out.println("房子出租价位有1k-3k");
        this.target.sale();
    }
}

```

```java
package com.javalearn.summer.proxy;

public class Test {
    public static void main(String[] args){
        Rental test = new Entrust();
        System.out.println("---使用代理之前---");
        consumer(test);
        System.out.println("---使用代理之后---");
        consumer(new AgentRental(test));
    }
    public static void consumer(Rental subject){
        subject.sale();
    }
}

```



## 静态代理的优点

我们可以在不改变Entrust委托类源代码的情况下 ,通过AgentRental代理类来修改Entrust委托类的功能，从而实现“代理”操作。	

## 静态代理的缺点

当我们的接口类需要增加和删除方式的时候，委托类和代理类都需要更改，不容易维护。

同时如果需要代理多个类的时候，每个委托类都要编写一个代理类，会导致代理类繁多，不好管理。



# 动态代理

创建动态代理类会使用到`java.lang.reflect.Proxy`类和`java.lang.reflect.InvocationHandler`接口。`java.lang.reflect.Proxy`主要用于生成动态代理类`Class`、创建代理类实例，该类实现了`java.io.Serializable`接口。



简单来说，就是一个处理器，和一个代理对象。

`InvocationHandler`接口：负责提供调用代理操作。是由代理对象调用处理器实现的接口，定义了一个invoke()方法，每个代理对象都有一个关联的接口。当代理对象上调用方法时，该方法会被自动转发到`InvocationHandler.invoke()`方法来进行调用。



**`java.lang.reflect.Proxy`类主要方法如下：**

```java
package java.lang.reflect;

import java.lang.reflect.InvocationHandler;

/**
 * Creator: yz
 * Date: 2020/1/15
 */
public class Proxy implements java.io.Serializable {

  // 省去成员变量和部分类方法...

    /**
     * 获取动态代理处理类对象
     *
     * @param proxy 返回调用处理程序的代理实例
     * @return 代理实例的调用处理程序
     * @throws IllegalArgumentException 如果参数不是一个代理实例
     */
    public static InvocationHandler getInvocationHandler(Object proxy)
            throws IllegalArgumentException {
        ...
    }

    /**
     * 创建动态代理类实例
     *
     * @param loader     指定动态代理类的类加载器
     * @param interfaces 指定动态代理类的类需要实现的接口数组
     * @param h          动态代理处理类
     * @return 返回动态代理生成的代理类实例
     * @throws IllegalArgumentException 不正确的参数异常
     */
    public static Object newProxyInstance(ClassLoader loader, Class<?>[] interfaces, InvocationHandler h)
            throws IllegalArgumentException {
        ...
    }

    /**
     * 创建动态代理类
     *
     * @param loader     定义代理类的类加载器
     * @param interfaces 代理类要实现的接口列表
     * @return 用指定的类加载器定义的代理类，它可以实现指定的接口
     */
    public static Class<?> getProxyClass(ClassLoader loader, Class<?>... interfaces) {
        ...
    }

    /**
     * 检测某个类是否是动态代理类
     *
     * @param cl 要测试的类
     * @return 如该类为代理类，则为 true，否则为 false
     */
    public static boolean isProxyClass(Class<?> cl) {
        return java.lang.reflect.Proxy.class.isAssignableFrom(cl) && proxyClassCache.containsValue(cl);
    }

    /**
     * 向指定的类加载器中定义一个类对象
     *
     * @param loader 类加载器
     * @param name   类名
     * @param b      类字节码
     * @param off    截取开始位置
     * @param len    截取长度
     * @return JVM创建的类Class对象
     */
    private static native Class defineClass0(ClassLoader loader, String name, byte[] b, int off, int len);

}
```



## 实现

直接看代码了。首先写一个实现了`InvocationHandler`接口的类，重写`invoke`方法，该方法就是代理方法：

```java
package com.javalearn.summer.proxy;

import java.lang.reflect.InvocationHandler;
import java.lang.reflect.Method;

public class TestAgent implements InvocationHandler {
    private Object target;
    public TestAgent(Object target){
        this.target = target;
    }

    @Override
    public Object invoke(Object proxy, Method method, Object[] args) throws Throwable {
        System.out.println("房子出租价位有1k-3k");
        Object result = method.invoke(target, args);
        return result;
    }
}

```



接下来有2种方式创建代理类实例。一种是通过`newProxyInstance`，另一种是通过`getProxyClass`配合反射。

### 方式一

```java
        Rental rental = new Entrust();
        Rental proxy = (Rental) Proxy.newProxyInstance(
                Rental.class.getClassLoader(),
                new Class[]{Rental.class},
                new TestAgent(rental)
        );
        proxy.sale();
```



### 方式二

```java
        Rental rental = new Entrust();
        InvocationHandler handler = new TestAgent(rental);
        Class proxyClass = Proxy.getProxyClass(
                Rental.class.getClassLoader(),
                new Class[]{Rental.class}
        );
        Rental proxy = (Rental) proxyClass.getConstructor(
                new Class[]{InvocationHandler.class}
        ).newInstance(new Object[]{handler});
        proxy.sale();
```



感觉还是方式一更方便一些。



## `$ProxyXXX`类

产生的代理类代码是这样：

```java
package com.sun.proxy.$Proxy0;

import java.io.File;
import java.lang.reflect.InvocationHandler;
import java.lang.reflect.Method;
import java.lang.reflect.Proxy;
import java.lang.reflect.UndeclaredThrowableException;

public final class $Proxy0 extends Proxy implements FileSystem {

    private static Method m1;

  // 实现的FileSystem接口方法，如果FileSystem里面有多个方法那么在这个类中将从m3开始n个成员变量
    private static Method m3;

    private static Method m0;

    private static Method m2;

    public $Proxy0(InvocationHandler var1) {
        super(var1);
    }

    public final boolean equals(Object var1) {
        try {
            return (Boolean) super.h.invoke(this, m1, new Object[]{var1});
        } catch (RuntimeException | Error var3) {
            throw var3;
        } catch (Throwable var4) {
            throw new UndeclaredThrowableException(var4);
        }
    }

    public final String[] list(File var1) {
        try {
            return (String[]) super.h.invoke(this, m3, new Object[]{var1});
        } catch (RuntimeException | Error var3) {
            throw var3;
        } catch (Throwable var4) {
            throw new UndeclaredThrowableException(var4);
        }
    }

    public final int hashCode() {
        try {
            return (Integer) super.h.invoke(this, m0, (Object[]) null);
        } catch (RuntimeException | Error var2) {
            throw var2;
        } catch (Throwable var3) {
            throw new UndeclaredThrowableException(var3);
        }
    }

    public final String toString() {
        try {
            return (String) super.h.invoke(this, m2, (Object[]) null);
        } catch (RuntimeException | Error var2) {
            throw var2;
        } catch (Throwable var3) {
            throw new UndeclaredThrowableException(var3);
        }
    }

    static {
        try {
            m1 = Class.forName("java.lang.Object").getMethod("equals", Class.forName("java.lang.Object"));
            m3 = Class.forName("com.anbai.sec.proxy.FileSystem").getMethod("list", Class.forName("java.io.File"));
            m0 = Class.forName("java.lang.Object").getMethod("hashCode");
            m2 = Class.forName("java.lang.Object").getMethod("toString");
        } catch (NoSuchMethodException var2) {
            throw new NoSuchMethodError(var2.getMessage());
        } catch (ClassNotFoundException var3) {
            throw new NoClassDefFoundError(var3.getMessage());
        }
    }
}
```



有如下技术细节和特性：

1. 动态代理的必须是接口类，通过`动态生成一个接口实现类`来代理接口的方法调用(`反射机制`)。
2. 动态代理类会由`java.lang.reflect.Proxy.ProxyClassFactory`创建。
3. `ProxyClassFactory`会调用`sun.misc.ProxyGenerator`类生成该类的字节码，并调用`java.lang.reflect.Proxy.defineClass0()`方法将该类注册到`JVM`。
4. 该类继承于`java.lang.reflect.Proxy`并实现了需要被代理的接口类，因为`java.lang.reflect.Proxy`类实现了`java.io.Serializable`接口，所以被代理的类支持`序列化/反序列化`。
5. 该类实现了代理接口类(示例中的接口类是`com.anbai.sec.proxy.FileSystem`)，会通过`ProxyGenerator`动态生成接口类(`FileSystem`)的所有方法，
6. 该类因为实现了代理的接口类，所以当前类是代理的接口类的实例(`proxyInstance instanceof FileSystem`为`true`)，但不是代理接口类的实现类的实例(`proxyInstance instanceof UnixFileSystem`为`false`)。
7. 该类方法中包含了被代理的接口类的所有方法，通过调用动态代理处理类(`InvocationHandler`)的`invoke`方法获取方法执行结果。
8. 该类代理的方式重写了`java.lang.Object`类的`toString`、`hashCode`、`equals`方法。
9. 如果动过动态代理生成了多个动态代理类，新生成的类名中的`0`会自增，如`com.sun.proxy.$Proxy0/$Proxy1/$Proxy2`。





# 参考连接

https://xz.aliyun.com/t/9197

https://zhishihezi.net/b/5d644b6f81cbc9e40460fe7eea3c7925

