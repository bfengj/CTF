# 常用类

接下来的内容开始学习尚硅谷的相关视频，记录下笔记（大多数都是直接摘抄B站的尚硅谷视频中的笔记，以便于以后自己的复习）

## String

`String`字符串，使用一对`""`引起来表示。

1. `String`声明为final的，不可被继承。
2. `String`实现了`Serializable`接口：表示字符串是支持序列化的。
   `String`实现了`Comparable`接口：表示`String`可以比较大小。
3. `String`内部定义了`final char[] value`用于存储字符串数据。
4. `String`代表不可变的字符序列。简称：不可变性。
   体现：1.当对字符串重新赋值时，需要重写指定内存区域赋值，不能使用原有的`value`进行赋值。
              2.当对现有的字符串进行连接操作时，也需要重新指定内存区域赋值，不能使用原有的`value`进行赋值。
              3.当调用`String`的`replace`方法修改指定字符或字符串时，也需要重新指定内存区域赋值，不能使用原有的`value`进行赋值。
5. 通过字面量的方式（区别于new）给一个字符串赋值，此时的字符串值声明在字符串常量池中。
6. 字符串常量池中式不会存储相同内容的字符串的。



### String的不同实例化

String的实例化方式：
	方式一：通过字面量定义的方式
	方式二：通过new + 构造器的方式

 面试题：`String s = new String("abc");`方式创建对象，在内存中创建了几个对象？
        两个:一个是堆空间中new结构，另一个是char[]对应的常量池中的数据：`"abc"`

```java
        //通过字面量定义的方式：此时的s1和s2的数据javaEE声明在方法区中的字符串常量池中。
        String s1 = "javaEE";
        String s2 = "javaEE";
        //通过new + 构造器的方式:此时的s3和s4保存的地址值，是数据在堆空间中开辟空间以后对应的地址值。
        String s3 = new String("javaEE");
        String s4 = new String("javaEE");

        System.out.println(s1 == s2);//true
        System.out.println(s1 == s3);//false
        System.out.println(s1 == s4);//false
        System.out.println(s3 == s4);//false

        System.out.println("***********************");
        Person p1 = new Person("Tom",12);
        Person p2 = new Person("Tom",12);

        System.out.println(p1.name.equals(p2.name));//true
        System.out.println(p1.name == p2.name);//true

        p1.name = "Jerry";
        System.out.println(p2.name);//Tom
```



```java
1.常量与常量的拼接结果在常量池。且常量池中不会存在相同内容的常量。
2.只要其中有一个是变量，结果就在堆中。
3.如果拼接的结果调用intern()方法，返回值就在常量池中

        String s1 = "javaEE";
        String s2 = "hadoop";

        String s3 = "javaEEhadoop";
        String s4 = "javaEE" + "hadoop";
        String s5 = s1 + "hadoop";
        String s6 = "javaEE" + s2;
        String s7 = s1 + s2;

        System.out.println(s3 == s4);//true
        System.out.println(s3 == s5);//false
        System.out.println(s3 == s6);//false
        System.out.println(s3 == s7);//false
        System.out.println(s5 == s6);//false
        System.out.println(s5 == s7);//false
        System.out.println(s6 == s7);//false

        String s8 = s6.intern();//返回值得到的s8使用的常量值中已经存在的“javaEEhadoop”
        System.out.println(s3 == s8);//true
```

关于`intern`方法：

```
public String intern()
返回字符串对象的规范表示。
最初为空的字符串池由类String私有维护。

调用实习方法时，如果池已包含等于String方法确定的String对象的字符串，则返回池中的字符串。 否则，将此String对象添加到池中，并返回对此String对象的引用。

由此可见，对于任何两个字符串s和t ， s.intern() == t.intern()是true当且仅当s.equals(t)为true 。

所有文字字符串和字符串值常量表达式都是实体。 字符串文字在The Java™ Language Specification的 3.10.5节中定义。
```





### String类的常用方法

```java
 int length()：返回字符串的长度： return value.length
 char charAt(int index)： 返回某索引处的字符return value[index]
 boolean isEmpty()：判断是否是空字符串：return value.length == 0
 String toLowerCase()：使用默认语言环境，将 String 中的所有字符转换为小写
 String toUpperCase()：使用默认语言环境，将 String 中的所有字符转换为大写
 String trim()：返回字符串的副本，忽略前导空白和尾部空白
 boolean equals(Object obj)：比较字符串的内容是否相同
 boolean equalsIgnoreCase(String anotherString)：与equals方法类似，忽略大
小写
 String concat(String str)：将指定字符串连接到此字符串的结尾。 等价于用“+”
 int compareTo(String anotherString)：比较两个字符串的大小
 String substring(int beginIndex)：返回一个新的字符串，它是此字符串的从
beginIndex开始截取到最后的一个子字符串。
 String substring(int beginIndex, int endIndex) ：返回一个新字符串，它是此字
符串从beginIndex开始截取到endIndex(不包含)的一个子字符串。
 boolean endsWith(String suffix)：测试此字符串是否以指定的后缀结束
 boolean startsWith(String prefix)：测试此字符串是否以指定的前缀开始
 boolean startsWith(String prefix, int toffset)：测试此字符串从指定索引开始的
子字符串是否以指定前缀开始
 boolean contains(CharSequence s)：当且仅当此字符串包含指定的 char 值序列
时，返回 true
 int indexOf(String str)：返回指定子字符串在此字符串中第一次出现处的索引
 int indexOf(String str, int fromIndex)：返回指定子字符串在此字符串中第一次出
现处的索引，从指定的索引开始
 int lastIndexOf(String str)：返回指定子字符串在此字符串中最右边出现处的索引
 int lastIndexOf(String str, int fromIndex)：返回指定子字符串在此字符串中最后
一次出现处的索引，从指定的索引开始反向搜索
注：indexOf和lastIndexOf方法如果未找到都是返回-1
 String replace(char oldChar, char newChar)：返回一个新的字符串，它是
通过用 newChar 替换此字符串中出现的所有 oldChar 得到的。
 String replace(CharSequence target, CharSequence replacement)：使
用指定的字面值替换序列替换此字符串所有匹配字面值目标序列的子字符串。
 String replaceAll(String regex, String replacement) ： 使 用 给 定 的
replacement 替换此字符串所有匹配给定的正则表达式的子字符串。
 String replaceFirst(String regex, String replacement) ： 使 用 给 定 的
replacement 替换此字符串匹配给定的正则表达式的第一个子字符串。
 boolean matches(String regex)：告知此字符串是否匹配给定的正则表达式。
 String[] split(String regex)：根据给定正则表达式的匹配拆分此字符串。
 String[] split(String regex, int limit)：根据匹配给定的正则表达式来拆分此
字符串，最多不超过limit个，如果超过了，剩下的全部都放到最后一个元素中。
```



### String与基本数据类型、包装类的转换

```java
    String --> 基本数据类型、包装类：调用包装类的静态方法：parseXxx(str)
    基本数据类型、包装类 --> String:调用String重载的valueOf(xxx)
```

```java
        String str1 = "123";
//        int num = (int)str1;//错误的
        int num = Integer.parseInt(str1);

        String str2 = String.valueOf(num);//"123"
        String str3 = num + "";

        System.out.println(str1 == str3);  //false
```



`String`与`char[]`之间的转换：

```java
    String --> char[]:调用String的toCharArray()
    char[] --> String:调用String的构造器
        
       
        String str1 = "abc123";  //题目： a21cb3

        char[] charArray = str1.toCharArray();
        for (int i = 0; i < charArray.length; i++) {
            System.out.println(charArray[i]);
        }

        char[] arr = new char[]{'h','e','l','l','o'};
        String str2 = new String(arr);
        System.out.println(str2);
```



String 与 byte[]之间的转换
编码：String --> byte[]:调用String的getBytes()
解码：byte[] --> String:调用String的构造器

编码：字符串 -->字节  (看得懂 --->看不懂的二进制数据)
解码：编码的逆过程，字节 --> 字符串 （看不懂的二进制数据 ---> 看得懂）

说明：解码时，要求解码使用的字符集必须与编码时使用的字符集一致，否则会出现乱码。





## `StringBuffer`与`StringBuilder`

    String、StringBuffer、StringBuilder三者的异同？
    String:不可变的字符序列；底层使用char[]存储
    StringBuffer:可变的字符序列；线程安全的，效率低；底层使用char[]存储
    StringBuilder:可变的字符序列；jdk5.0新增的，线程不安全的，效率高；底层使用char[]存储
    
    源码分析：
    String str = new String();//char[] value = new char[0];
    String str1 = new String("abc");//char[] value = new char[]{'a','b','c'};
    
    StringBuffer sb1 = new StringBuffer();//char[] value = new char[16];底层创建了一个长度是16的数组。
    System.out.println(sb1.length());//
    sb1.append('a');//value[0] = 'a';
    sb1.append('b');//value[1] = 'b';
    
    StringBuffer sb2 = new StringBuffer("abc");//char[] value = new char["abc".length() + 16];
    
    //问题1. System.out.println(sb2.length());//3
    //问题2. 扩容问题:如果要添加的数据底层数组盛不下了，那就需要扩容底层的数组。
    默认情况下，扩容为原来容量的2倍 + 2，同时将原有数组中的元素复制到新的数组中。
    
    指导意义：开发中建议大家使用：StringBuffer(int capacity) 或 StringBuilder(int capacity)





常用方法：

```java
StringBuffer append(xxx)：提供了很多的append()方法，用于进行字符串拼接
StringBuffer delete(int start,int end)：删除指定位置的内容
StringBuffer replace(int start, int end, String str)：把[start,end)位置替换为str
StringBuffer insert(int offset, xxx)：在指定位置插入xxx
StringBuffer reverse() ：把当前字符序列逆转
public int indexOf(String str)
public String substring(int start,int end):返回一个从start开始到end索引结束的左闭右开区间的子字符串
public int length()
public char charAt(int n )
public void setCharAt(int n ,char ch)
                                                       
                                                       
总结：
增：append(xxx)
删：delete(int start,int end)
改：setCharAt(int n ,char ch) / replace(int start, int end, String str)
查：charAt(int n )
插：insert(int offset, xxx)
长度：length();
遍历：for() + charAt() / toString()
```

