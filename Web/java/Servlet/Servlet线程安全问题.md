# Servlet线程安全问题

## 前言

之前遇到过很多次Servlet的安全问题了，学习Y4师傅的文章。

## 例子

```java
package com.feng.servlet;

import javax.servlet.ServletException;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import java.io.IOException;

@WebServlet("/thread")
public class ThreadServlet extends HttpServlet {
    public boolean isAdmin;
    @Override
    protected void doGet(HttpServletRequest req, HttpServletResponse resp) throws ServletException, IOException {
        isAdmin = true;
        if (req.getParameter("cmd").contains("calc")){
            isAdmin = false;
        }
        try {
            Thread.sleep(1000);
        } catch (InterruptedException e) {
            e.printStackTrace();
        }
        if (isAdmin&&req.getParameter("cmd").contains("calc")){
            resp.getWriter().println("ok");
            Runtime.getRuntime().exec("calc");
        }
    }

    @Override
    protected void doPost(HttpServletRequest req, HttpServletResponse resp) throws ServletException, IOException {
        super.doPost(req, resp);
    }
}


```

看似不可能，其实确实可以的（加了`Thread.sleep(1000);`是为了产生延迟，更容易造成`calc`）。



## Servlet的线程

> Servlet实际上是一个单件，当我们第一次请求某个Servlet时，Servlet容器将会根据web.xml配置文件或者是注解实例化这个Servlet类，之后如果又有新的客户端请求该Servlet时，则一般不会再实例化该Servlet类

所以对于Servlet来说，多人访问的时候，其实访问的是一个Servlet的实例，对于Servlet的成员变量的修改存在线程问题，导致了`calc`的执行。



## 修复

### implements SingleThreadModel

实现这个接口之后`Servlet`的`service`将只能被一个线程执行。



但是问题是会话属性和静态变量仍然可以被多线程的多请求同时访问，所以对于会话属性和静态变量的访问无法保证安全



### 使用synchronized

Java多线程中的东西了，不提了。



## 总结

最好还是避免对`Servlet`中成员变量的访问，使用`synchronized`在高并发的时候也会影响性能。



## 参考文章

https://y4tacker.github.io/2022/02/03/year/2022/2/Servlet%E7%9A%84%E7%BA%BF%E7%A8%8B%E5%AE%89%E5%85%A8%E9%97%AE%E9%A2%98/















