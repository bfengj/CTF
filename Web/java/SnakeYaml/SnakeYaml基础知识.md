# SnakeYaml基础知识

## 基本概念

YAML是”YAML Ain’t a Markup Language”（YAML不是一种标记语言）的递归缩写，是一个可读性高、用来表达数据序列化的格式，类似于XML但比XML更简洁。

在Java中，有一个用于解析YAML格式的库，即SnakeYaml。

SnakeYaml是一个完整的YAML1.1规范Processor，支持UTF-8/UTF-16，支持Java对象的序列化/反序列化，支持所有YAML定义的类型。



## YAML

之前学SpringBoot的时候配置文件写yaml的时候了解过yaml的基本格式，具体参考https://www.yiibai.com/yaml



## SnakeYaml学习

SnakeYaml有2个方法：

- Yaml.load()，入参是一个字符串或者一个文件，返回一个Java对象；
- Yaml.dump()将一个对象转化为yaml文件形式

看着突然想到了python的pickle反序列化。。。



```java
package com.summer.entity;

public class User {
    private String name;

    public String getName() {
        return name;
    }

    public void setName(String name) {
        this.name = name;
    }
}


```

```java
        User user1 = new User();
        user1.setName("feng");
        Yaml yaml = new Yaml();
        System.out.println(yaml.dump(user1));//!!com.summer.entity.User {name: feng}

        String s = "!!com.summer.entity.User {name: feng}";
        User user2 = yaml.load(s);
        System.out.println(user2);//com.summer.entity.User@4f47d241
```

`!!`用于强制类型转换，`!!com.summer.entity.User`的意思是转换为User类。

再测试`setter`和`getter`：

```java
package com.summer.entity;

public class User {
    private String name;
    public User(){
        System.out.println("无参构造器");
    }

    public String getName() {
        System.out.println("getName");
        return name;
    }

    public void setName(String name) {
        System.out.println("setName");
        this.name = name;
    }
}


```

```java
        String s = "!!com.summer.entity.User {name: feng}";
        Yaml yaml = new Yaml();
        User user = yaml.load(s);
        //无参构造器
        //setName
```

说明反序列化的时候会调用无参构造器和`setter`，和jackson一样。

又测试了一下，发现对于4种属性修饰，`private,protected,public,default`，如果是`public`则不会调用`setter`。带着这样的疑惑调试分析看看。



具体可以自己调试，总的来说，SnakeYaml的反序列化是通过反射来实现的。

先把String转换为`Node`，然后对`Node`进行`constructObject()`处理，最后是获取node的type(也就是class)，然后反射调用无参构造器：

![image-20220310152246218](SnakeYaml基础知识.assets/image-20220310152246218.png)

之后调用`constructJavaBean2ndStep`处理属性，分别取得`keynode`和`valuenode`然后取其值（key必须是String，value还会调用`constructObject()`来递归获取）。

然后根据`property`来`set`属性：

```java
                    if (memberDescription == null
                            || !memberDescription.setProperty(object, key, value)) {
                        property.set(object, value);
```

发现在`public`的时候，并不是反射调用`setName`，而是反射对`Field`进行`set`了。（也对，因为是public）：

![image-20220310152522074](SnakeYaml基础知识.assets/image-20220310152522074.png)

而对于private等情况，则是反射调用方法来`invoke`：

![image-20220310152614739](SnakeYaml基础知识.assets/image-20220310152614739.png)