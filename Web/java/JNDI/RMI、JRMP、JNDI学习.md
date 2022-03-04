# 	RMI、JRMP、JNDI学习

## jdk8u121之前

- RMI服务端使用bind方法可以实现主动攻击RMI Registry
- RMI客户端使用lookup方法理论上可以主动攻击RMI Registry
- RMI Registry在RMI客户端使用lookup方法的时候，可以实现被动攻击RMI客户端
- RMI客户端在远程调用的过程中把参数的序列化数据替换成恶意序列化数据能攻击服务端。
- RMI服务端替换其返回的序列化数据为恶意序列化数据进而被动攻击客户端。



## jdk8u121

### 反序列化白名单

增加了反序列化的白名单：

1. String.clas
2. Number.class
3. Remote.class
4. Proxy.class
5. UnicastRef.class
6. RMIClientSocketFactory.class
7. RMIServerSocketFactory.class
8. ActivationID.class
9. UID.class



可以用`ysoserial.payloads.JRMPClient`的payload。

### com.sun.jndi.rmi.object.trustURLCodebase

8u121及其之后默认为false，导致没法通过rmi协议实现JNDI注入打客户端。

## jdk8u191之后

### com.sun.jndi.ldap.object.trustURLCodebase

默认为false。LDAP加载远程代码攻击的方式失效。