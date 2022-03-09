# Jackson反序列化基础知识

## 前言

开始Jackson反序列化的学习，发现很多师傅接触Java之后很早就学过了Jackson，我一直都还没学过。正好前段时间学了fastjson，再学学jackson也能有个对比。

学习自mi1k7ea师傅的文章。

## 简单介绍

相比于Fastjson，Jackson不仅开源稳定易使用，而且拥有Spring生态加持，更受使用者的青睐。按照使用者的说法Jackson的速度是最快的。

环境：

```xml
        <dependency>
            <groupId>com.fasterxml.jackson.core</groupId>
            <artifactId>jackson-databind</artifactId>
            <version>2.9.3</version>
        </dependency>
        <dependency>
            <groupId>com.fasterxml.jackson.core</groupId>
            <artifactId>jackson-core</artifactId>
            <version>2.9.3</version>
        </dependency>
        <dependency>
            <groupId>com.fasterxml.jackson.core</groupId>
            <artifactId>jackson-annotations</artifactId>
            <version>2.9.3</version>
        </dependency>
```



jackson简单的写法：

```java
        User user = new User("feng","feng");
        ObjectMapper mapper = new ObjectMapper();
        String json = mapper.writeValueAsString(user);
        System.out.println(json);//{"username":"feng","password":"feng"}
        User other = mapper.readValue(json,User.class);
        System.out.println(other);com.summer.entity.User@754ba872
```

```java
package com.summer.entity;


public class User {
    private String username;
    private String password;
    public User() {
    }

    public User(String username, String password) {
        this.username = username;
        this.password = password;
    }

    public String getUsername() {
        return username;
    }

    public String getPassword() {
        return password;
    }

    public void setUsername(String username) {
        this.username = username;
    }

    public void setPassword(String password) {
        this.password = password;
    }
}

```



## JacksonPolymorphicDeserialization(多态类型绑定)

> 如果对多态类的某一个子类实例在序列化后再进行反序列化时，如何能够保证反序列化出来的实例即是我们想要的那个特定子类的实例而非多态类的其他子类实例呢？——Jackson实现了JacksonPolymorphicDeserialization机制来解决这个问题。
>
> JacksonPolymorphicDeserialization即Jackson多态类型的反序列化：在反序列化某个类对象的过程中，如果类的成员变量不是具体类型（non-concrete），比如Object、接口或抽象类，则可以在JSON字符串中指定其具体类型，Jackson将生成具体类型的实例。
>
> 简单地说，就是将具体的子类信息绑定在序列化的内容中以便于后续反序列化的时候直接得到目标子类对象

有两种，分别是`DefaultTyping`和`@JsonTypeInfo`注解。

### DefaultTyping

四个值：

- JAVA_LANG_OBJECT: only affects properties of type `Object.class`
- OBJECT_AND_NON_CONCRETE: affects `Object.class` and all non-concrete types (abstract classes, interfaces)
- NON_CONCRETE_AND_ARRAYS: same as above, and all array types of the same (direct elements are non-concrete types or `Object.class`)
- NON_FINAL: affects all types that are not declared 'final', and array types of non-final element types.





- JAVA_LANG_OBJECT：当被序列化或反序列化的类里的属性被声明为一个Object类型时，会对该Object类型的属性进行序列化和反序列化，并且明确规定类名。（当然，这个Object本身也得是一个可被序列化的类）
- OBJECT_AND_NON_CONCRETE：除了前面提到的特征，当类里有Interface、AbstractClass类时，对其进行序列化和反序列化（当然这些类本身需要时合法的、可被序列化的对象）。此外，**enableDefaultTyping()默认的无参数的设置就是此选项。**
- NON_CONCRETE_AND_ARRAYS：除了前面提到的特征外，还支持Array类型。
- NON_FINAL：除了前面的所有特征外，包含即将被序列化的类里的全部、非final的属性，也就是相当于整个类、除final外的属性信息都需要被序列化和反序列化。

使用例子：

```java
        ObjectMapper mapper = new ObjectMapper();
        mapper.enableDefaultTyping(ObjectMapper.DefaultTyping.JAVA_LANG_OBJECT);
```



### @JsonTypeInfo注解

使用注解的形式，包括5种类型的值：

```java
@JsonTypeInfo(use = JsonTypeInfo.Id.NONE)
@JsonTypeInfo(use = JsonTypeInfo.Id.CLASS)
@JsonTypeInfo(use = JsonTypeInfo.Id.MINIMAL_CLASS)
@JsonTypeInfo(use = JsonTypeInfo.Id.NAME)
@JsonTypeInfo(use = JsonTypeInfo.Id.CUSTOM)
```

使用例子：

```java
public class User {
    private String username;
    private String password;
    @JsonTypeInfo(use= JsonTypeInfo.Id.NONE)
    private Object obj;
```

加上注释。

- JsonTypeInfo.Id.NONE：有无这个没区别
- JsonTypeInfo.Id.CLASS：反序列化的时候通过`@class`指定相关类。
- JsonTypeInfo.Id.MINIMAL_CLASS：和`JsonTypeInfo.Id.CLASS`差不多，只不过`@class`变成了`@c`。
- JsonTypeInfo.Id.NAME：多了`@type`，但是没法被反序列化利用。
- JsonTypeInfo.Id.CUSTOM：需要自定义，手写解析器。



所以`JsonTypeInfo.Id.CLASS`和`JsonTypeInfo.Id.MINIMAL_CLASS`可以触发反序列化漏洞。

## 反序列化种类属性方法的调用。

这就能联想到`fastjson`了。

看例子：

```java
        HashMap hashMap = new HashMap();
        hashMap.put(1,2);
        User user = new User("feng","feng",hashMap);
        ObjectMapper mapper = new ObjectMapper();
        mapper.enableDefaultTyping(ObjectMapper.DefaultTyping.JAVA_LANG_OBJECT);
        String json = mapper.writeValueAsString(user);
        System.out.println(json);
        User other = mapper.readValue(json,User.class);
        System.out.println(other);
```

```java
package com.summer.entity;

public class User {
    private String username;
    private String password;
    private Sex sex;
    public User() {
        System.out.println("User构造函数");
    }

    public String getPassword() {
        return password;
    }

    public Sex getSex() {
        return sex;
    }

    public String getUsername() {
        return username;
    }

    @Override
    public String toString() {
        return "User{" +
                "username='" + username + '\'' +
                ", password='" + password + '\'' +
                ", sex=" + sex +
                '}';
    }
}


```

```java
package com.summer.entity;

public interface Sex {
    public void setSex(int sex);
    public int getSex();
}

```



```java
package com.summer.entity;

public class MySex implements Sex{
    int sex;
    public MySex() {
        System.out.println("MySex构造函数");
    }

    @Override
    public int getSex() {
        System.out.println("MySex.getSex");
        return sex;
    }

    @Override
    public void setSex(int sex) {
        System.out.println("MySex.setSex");
        this.sex = sex;
    }
}

```

```java
        ObjectMapper mapper = new ObjectMapper();
        mapper.enableDefaultTyping();
        String json = "{\"username\":\"feng\",\"password\":\"feng\",\"sex\":[\"com.summer.entity.MySex\",{\"sex\":1}]}";
        User other = mapper.readValue(json,User.class);
        System.out.println(other);
```

发现在调用无参的`enableDefaultTyping()`或者范围更广的`DefaultTyping`的时候，会触发反序列化操作，调用无参构造器和`setter`：

```
User构造函数
MySex构造函数
MySex.setSex
User{username='feng', password='feng', sex=com.summer.entity.MySex@5f2108b5}
```

（但是用NON_FINAL这里会报一个奇奇怪怪的错误。。。）

使用`@JsonTypeInfo`注解中的`JsonTypeInfo.Id.CLASS`和`JsonTypeInfo.Id.MINIMAL_CLASS`同理。



具体流程就不写了，本地调了一下大概也懂了，就是2步，一步是调用无参构造器，然后依次遍历，然后调用`setter`。如果是能找到类型的，就会进入`deserializeWithType`，根据`typeId`找到反序列化器，用此反序列化器解析数组内的具体类型实例，然后又会进入`BeanDeserializer.deserialize()->BeanDeserializer.vanillaDeserialize()`，又是上面步骤的循环了。



所以：

**在Jackson反序列化中，若调用了enableDefaultTyping()函数或使用@JsonTypeInfo注解指定反序列化得到的类的属性为JsonTypeInfo.Id.CLASS或JsonTypeInfo.Id.MINIMAL_CLASS，则会调用该属性的类的构造函数和setter方法。**



## Jackson反序列化漏洞

满足下面三个条件之一即存在Jackson反序列化漏洞：

- 调用了ObjectMapper.enableDefaultTyping()函数；
- 对要进行反序列化的类的属性使用了值为JsonTypeInfo.Id.CLASS的@JsonTypeInfo注解；
- 对要进行反序列化的类的属性使用了值为JsonTypeInfo.Id.MINIMAL_CLASS的@JsonTypeInfo注解；



如果反序列化的类的属性是`Object`的时候，**因为Object类型是任意类型的父类，因此扩大了我们的攻击面，我们只需要寻找出在目标服务端环境中存在的且构造函数或setter方法存在漏洞代码的类即可进行攻击利用。**



## 参考文章

https://www.mi1k7ea.com/2019/11/13/Jackson%E7%B3%BB%E5%88%97%E4%B8%80%E2%80%94%E2%80%94%E5%8F%8D%E5%BA%8F%E5%88%97%E5%8C%96%E6%BC%8F%E6%B4%9E%E5%9F%BA%E6%9C%AC%E5%8E%9F%E7%90%86/

http://www.lmxspace.com/2019/07/30/Jackson-%E5%8F%8D%E5%BA%8F%E5%88%97%E5%8C%96%E6%B1%87%E6%80%BB/