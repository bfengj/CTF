# Java反射学习



## 前言

学习一下Java的反射，学习了先知上面的文章还有P神的《Java安全漫谈》，简单的做一下知识点的整理。



## 反射的概念

反射是Java的特征之一，是一种间接操作目标对象的机制，核心是JVM在运行状态的时候才动态加载类，对于任意一个类都能够知道这个类所有的属性和方法，并且对于任意一个对象，都能够调用它的方法/访问属性。这种动态获取信息以及动态调用对象方法的功能成为Java语言的反射机制。通过使用反射我们不仅可以获取到任何类的成员方法(Methods)、成员变量(Fields)、构造方法(Constructors)等信息，还可以动态创建Java类实例、调用任意的类方法、修改任意的类成员变量值等。





`java`反射机制组成需要重点注意以下的类：

`java.lang.Class`：类对象;

`java.lang.reflect.Constructor`：类的构造器对象;

`java.lang.reflect.Field`：类的属性对象;

`java.lang.reflect.Method`：类的方法对象;



## Class类

在程序运行期间，Java运行时系统始终为所有对象维护一个运行时类型标识。这个信息会跟踪每个对象所属的类。虚拟机利用运行时类型信息选择要执行的正确方法。

可以使用一个特殊的Java类访问这些信息。保存这些信息的类名为`Class`。



得到Class类有三种方法：

1. `.class`，在加载了某个类的情况下，直接拿它的属性即可，例如：`Runtime.class`。
2. `obj.getClass()`。如果存在某个类的实例obj，那么可以通过`getClass()`方法得到这个类对应的`Class`。
3. `Class.forName`，也是最常用的。知道了那个类的完整名字后（包括包名），作为参数即可：`Class clazz = Class.forName("java.lang.Runtime");`



> 我们一般使用第三种通过`Class.forName`方法去动态加载类。且使用`forName`就不需要import导入其他类，可以加载我们任意的类。
>
> 而使用类`.class`属性，需要导入类的包，依赖性太强，在大型项目中容易抛出编译错误；
>
> 而使用实例化对象的`getClass()`方法，需要本身创建一个对象，本身就没有了使用反射机制意义。
>
> 所以我们在获取class对象中，一般使用`Class.forName`方法去获取。



拿到了类之后，就可以利用反射来获取这个类的成员变量、方法，构造函数，实例化类，并且调用方法。因此依次列出。



## 获取成员变量Field

`Class`类有这些获取属性的方法：

```java
//返回 字段对象，该对象反映此 类对象表示的类或接口的指定声明字段。
getDeclaredField(String name)
//返回 字段对象的数组， 字段对象反映由此 类对象表示的类或接口声明的所有字段。
getDeclaredFields()
//返回 字段对象，该对象反映此 类对象表示的类或接口的指定公共成员字段。
getField(String name)
//返回一个包含 字段对象的数组， 字段对象反映此 类对象所表示的类或接口的所有可访问公共字段。
getFields()
```

大致区分就是，上面两个可以获取的是所有的成员变量，下面的两个得到的只有`public`的成员变量。第一第三个可以传入一个名字（字符串）来得到指定的成员变量，而第二第四个只能得到一个数组，没法得到指定的。

还有一个需要注意的点，就是前两个虽然不受到`public`的限制，但是它不能得到超类的成员变量。后两个虽然只能得到`public`成员变量，但是可以得到超类的`public`成员变量。



看个例子，获取成员变量名后就可以通过`getName()`方法得到成员变量名的字符串。

```java
        Class clazz = Class.forName("com.javalearn.summer.Employee");
        Field name = clazz.getField("name");
        System.out.println(name.getName());
```





## 获取方法Method

`Class`类有这些获取`Method`的方法：

```java
//返回 方法对象，该对象反映此 类对象表示的类或接口的指定声明方法。
getDeclaredMethod(String name, 类<?>... parameterTypes);
//返回一个包含 方法对象的数组， 方法对象反映此 类对象表示的类或接口的所有已声明方法，包括public，protected，default（package）访问和私有方法，但不包括继承的方法。
getDeclaredMethods();
//返回 方法对象，该对象反映此 类对象表示的类或接口的指定公共成员方法。
getMethod(String name, 类<?>... parameterTypes);
//返回一个包含 方法对象的数组， 方法对象反映此 类对象所表示的类或接口的所有公共方法，包括由类或接口声明的那些以及从超类和超接口继承的那些。
getMethods();
```

用法同获取`Field`的那四个方法。需要注意后面的`类<?>... parameterTypes`。举个例子：

```java
        Class clazz = Class.forName("java.lang.Runtime");
        clazz.getMethod("exec", String.class);
```

因为`Runtime`类的`exec`方法存在**方法重载**：

![pic34](D:\this_is_feng\github\CTF\Web\picture\pic34.png)

因此存在方法名相同，但是参数列表不同的多种方法的时候，使用`forMethod`就需要带上第二个参数，才能准确的找到指定的方法对象。

获取了`Method`对象后有什么用呢？肯定就是想办法调用这个方法了，但是在此之前还需要在做一步准备。



## 获取构造函数

Class类获取构造函数有这些方法：

```java
//返回一个 构造器对象，该对象反映此 类对象所表示的类或接口的指定构造函数。
getDeclaredConstructor(类<?>... parameterTypes)
//返回 构造器对象的数组， 构造器对象反映由此 类对象表示的类声明的所有构造函数。
getDeclaredConstructors()
//返回一个 构造器对象，该对象反映此 类对象所表示的类的指定公共构造函数。
getConstructor(类<?>... parameterTypes)
//返回一个包含 构造器对象的数组， 构造器对象反映了此 类对象所表示的类的所有公共构造函数。
getConstructors()
```



一个小例子，用法基本和之前一样。

```java
        Class clazz = Class.forName("com.javalearn.summer.Employee");
        Constructor cons = clazz.getDeclaredConstructor(String.class,double.class);
```



## 实例化类

最简单的方法，就是在得到了类的`Class`后，直接使用`newInstance`：

```java
        Class clazz = Class.forName("com.javalearn.summer.Employee");
        Object o = clazz.newInstance();
        System.out.println(o);
```

但是这样实例化类，相当于调用类的无参构造器，当需要调用非无参构造器来实例化类的时候，就需要用到之前得到的`Constructor`了：

```java
        Class clazz = Class.forName("com.javalearn.summer.Employee");
        Constructor cons = clazz.getDeclaredConstructor(String.class,double.class);
        Object feng = cons.newInstance("feng", 123);
```





## 调用方法

调用方法用到的是`Method`类的invoke方法：

```java
//在具有指定参数的指定对象上调用此 方法对象表示的基础方法。
invoke(Object obj, Object... args)	
```

举个例子叭。比如要调用`Employee`类的`getName`方法，因此我们首先需要得到这个`getName`方法的`Method`对象，其次还需要得到这个`Employee`类的对象，作为`invoke`方法的第一个参数：

```java
        Class clazz = Class.forName("com.javalearn.summer.Employee");
        Constructor cons = clazz.getDeclaredConstructor(String.class,double.class);
        Object feng = cons.newInstance("feng", 123);
        Method m = clazz.getMethod("getName");
        String name = (String) m.invoke(feng);
        System.out.println(name);
```



还有一个点需要注意：

**如果调用这个方法是普通方法，第一个参数就是类对象；**

**如果调用这个方法是静态方法，第一个参数就是类，或者可以忽略，设置为null；**

因此如果要调用的这个方法是静态方法的话，就不需要用到这个类的对象了，直接用这个类：

```java
Class clazz = Class.forName("java.lang.Runtime");
Method execMethod = clazz.getMethod("exec", String.class);
Method getRuntimeMethod = clazz.getMethod("getRuntime");
Object runtime = getRuntimeMethod.invoke(clazz);
execMethod.invoke(runtime, "calc.exe");
```



## 访问控制

如果得到的`Field`，`Method`或者`Constructor`是私有的等，受到了访问控制的影响，不能直接调用或者得到内容的话，需要利用`setAccessible(true);`

`setAccessible`方法是`AccessibleObejct`类的一个方法，它是`Field`、`Method`和`Constructor`类的公共超类。这个特性是为调式、持久存储和类似机制提供的。



例如,`Runtime`类的构造函数是私有的，因此可以这样来获取对象：

```java
Class clazz = Class.forName("java.lang.Runtime");
Constructor m = clazz.getDeclaredConstructor();
m.setAccessible(true);
clazz.getMethod("exec", String.class).invoke(m.newInstance(), "calc.exe");
```



## 设置属性的值

用`set`方法即可：

```java
        String url = "http://urld86.dnslog.cn/";
        URLStreamHandler handler = new TestURLStreamHandler();
        URL u = new URL(null,url,handler);
        //Reflection
        Class clazz = Class.forName("java.net.URL");
        Field field = clazz.getDeclaredField("hashCode");
        field.setAccessible(true);
        field.set(u,-1);
```



## 打印一个类的全部信息

打印一个类全部信息的代码：

```java
package com.javalearn;

import java.lang.reflect.Constructor;
import java.lang.reflect.Field;
import java.lang.reflect.Method;
import java.lang.reflect.Modifier;

public class ReflectionTest
{
    public static void main(String[] args) throws ReflectiveOperationException
    {
        String className = "com.javalearn.Employee";
        Class cl = Class.forName(className);
        Class supercl = cl.getSuperclass();
        String modifiers = Modifier.toString(cl.getModifiers());
        if (modifiers.length()>0) System.out.print(modifiers+" ");
        System.out.print("class " + className);
        if(supercl !=null && supercl !=Object.class) System.out.print(" extends " + supercl.getName());
        System.out.print("\n{\n");
        printConstructors(cl);
        System.out.println();
        printMethods(cl);
        System.out.println();
        printFields(cl);
        System.out.print("}");
    }

    public static void printConstructors(Class cl)
    {
        Constructor[] constructors = cl.getDeclaredConstructors();
        for(Constructor c:constructors)
        {
            String name = c.getName();
            System.out.print("   ");
            String modifiers = Modifier.toString(c.getModifiers());
            if(modifiers.length()>0) System.out.print(modifiers + " ");
            System.out.print(name + "(");

            Class[] paramTypes = c.getParameterTypes();
            for(int j = 0;j<paramTypes.length;j++)
            {
                if(j>0) System.out.print(", ");
                System.out.print(paramTypes[j].getName());
            }
            System.out.println(");");

        }
    }

    public static void printMethods(Class cl)
    {
        Method[] methods = cl.getDeclaredMethods();
        for(Method m :methods)
        {
            Class retType = m.getReturnType();
            String name = m.getName();

            System.out.print("   ");
            String modifiers = Modifier.toString(m.getModifiers());
            if(modifiers.length()>0) System.out.print(modifiers+" ");
            System.out.print(retType.getName() + " " + name + "(");

            Class[] paramTypes = m.getParameterTypes();
            for(int j =0; j<paramTypes.length;j++)
            {
                if(j>0) System.out.print(", ");
                System.out.print(paramTypes[j].getName());
            }
            System.out.println(");");

        }
    }

    public static void printFields(Class cl)
    {
        Field[] fields = cl.getDeclaredFields();

        for(Field e:fields)
        {
            Class type = e.getType();
            String name = e.getName();
            System.out.print("   ");
            String modifiers = Modifier.toString(e.getModifiers());
            if(modifiers.length()>0) System.out.print(modifiers+" ");
            System.out.println(type.getName()+" " + name+ ";");
        }
    }
}
```



## 参考文章

https://xz.aliyun.com/t/9117

《Java安全漫谈》

