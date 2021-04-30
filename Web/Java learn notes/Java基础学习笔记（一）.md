# 前言
开始学习Java，接下来会记录下自己学习Java过程中的一些东西，单纯的笔记，我自己也不一定能看懂，想到哪里记到哪里。



# Java程序设计环境

 - JDK：编写Java程序的程序员使用的软件。
 - SE：用于桌面或简单服务器应用的Java平台。
 - OpenJDK：Java SE的一个免费开源实现。

编译运行Java程序：

```powershell
javac xxx.java
java xxx
```



用IntelliJ IDEA创建工程似乎会出现很奇怪的问题，就是工程的那个文件夹似乎和我文件系统里的文件夹他不是同一个文件夹，因此考虑就是创建工程的时候不要让IntelliJ IDEA创建，用已经创建好的文件夹即可。



# Java的基本程序设计结构

Java区分大小写。

类是构建所有Java应用程序和appllet的构建块，Java应用程序中的全部内容都必须放置在类中。

类名必须以字母开头。且命名规范是骆驼命名法。

源代码的文件名必须与公共类的名字相同，并用.java作为拓展名。

运行已编译的程序时，Java虚拟机总是从指定类的main方法开始执行。且main方法必须声明为public，且必须是静态的(static)。

点号(.)用于调用方法。`object.method(parameters)`

Java用双引号界定字符串。

Syetem.out.print输出不增加换行符。

Java的注释：

- `//`
- `/**/`
- /**    */  文档注释

```java
/**
 *
 *
 *
 */
```



Java是强类型语言，必须为变量声明类型。Java中一共八种基本类型，4种整形，2种浮点类型，1种字符串类型char和boolean类型。

**整形**：int,short,long,byte。

Java种整形的范围与运行Java代码的机器无关。

- long：`400000L`
- 十六进制：`0xCA`
- 八进制：`010`
- 二进制：`0b1001`



Java没有任何无符号形式的整形类型。



**浮点**：float double

没有后缀F的浮点数值总是默认为double类型。当然也可以在最后添加后缀D或d。

浮点数值不适合于无法接受舍入误差的金融计算。如果在数值计算中不允许有任何舍入误差，就应该使用BigDecimal类。



**char类型**

char类型原本用于表示单个字符，不过现在如今有些Unicode字符可以用一个char值描述，另外一些unicode字符则需要2个char值。

char类型的字面量要用单引号括起来，例如'A'是编码值为65的字符常量。

char类型的值可以表示为十六进制，从\u0000到\uffff。

转义序列\u还可以出现在加引号的字符常量或字符串之外，而其他的所有转义序列都不行，例如：

`public static void main(String\u005B\u005D args)`，是`[]`

Unicode转义序列会在解析代码之前得到处理，例如：

`"\u0022+\u0022"`是`""+""`



强烈建议不要在程序中使用char类型，最好将字符串作为抽象数据类型使用。



**boolean**类型

false和true。**整型值和布尔值之间不能进行相互转换**



**变量与常量**

Jaca中的数字和字母范围更大。

声明一个变量之后，必须用赋值语句对变量进行**显式**初始化，千万不要使用未初始化的变量的值。

Java中可以将声明放在代码中的任何地方。但是变量的声明尽可能地靠近变量第一次使用的地方，这是一种良好的程序编写风格。



**从Java10开始，对于局部变量，如果可以从变量的初始值推断出它的类型，就不再需要声明类型，只需要使用关键字var而无需指定类型**

```java
var feng = 12;
var fff = "feng";
```



用final指示常量。final表示这个常量只能被赋值一次。常量名使用全大写。



如果一个常量在类的多个方法中被使用，通常将其设置成类常量，用`static final`。且类常量的定义放在main方法的外部

```java
public class Main
{
    static final double PI = 3.14;
    public static void main(String[] args)
    {
        System.out.println(PI);
    }
}
```



如果一个常量被声明为`public`，那么其他类的方法也可以使用这个常量。



**枚举**

枚举类型包括有限个命名的值。



当参与`/`运算的两个操作数都是整数时，表示整数除法，否则表示浮点除法。

整数被0除会产生一个异常，而浮点数被0除将会得到无穷大或NAN结果。

Math类包含了各种各样的数学函数：

```java
double x = 4;
double y = Math.sqrt(x);
```

Java没有幂运算，需要结果Math.pow方法：`double y = Math.pow(x,a);`。pow方法有2个double类型参数，返回结果也是double类型。

此外还有2个最接近的近似值：`Math.PI`和`Math.E`来表示π和e。

静态导入：`import static java.lang.Math.*`





强制类型转换是在圆括号中给出想要转换的目标类型，后面紧跟待转换的变量名：

```java
double x = 9.997;
int nx = (int) Math.round(x);
```





**字符串**

Java没有内置的字符串类型，而是在标准Java类库中提供了一个预定义类，叫做String。每个用双引号括起来的字符串都是String类的一个实例。

用substring方法提取字串

```java
        String feng = "hello,world";
        String s = feng.substring(2,3);
        System.out.println(s);
```



Java语言允许用`+`号拼接字符串。

当一个字符串与一个非字符串的值进行拼接时，后者会转换成字符串。

把多个字符串放一起，用一个界定符分割，用静态方法join：

```java
String s = String.join(".","a","b","c");
```



**String类没有提供修改字符串中某个字符的方法。**可以提取想要保留的字串，再与希望替换的字符拼接。



由于不能修改Java字符串中的单个字符，所以在Java文档中将String类对象称为是不可变的。



**可以使用equals方法检测两个字符串是否相等**。不区分大小写可以用`equalsIgnoreCase`。

不要使用`==`检测两个字符串是否相等。它只能确定两个字符串是否存放在同一位置上。

空字符串是长度为0的字符串，检查方式：

```java
if(str.length()==0)
if(str.equals(""))
```

检查一个字符串是否是null：

```java
if(str==null)
```



length方法将返回采用UTF-16编码表示给定字符串所需要的代码单元数量。





每次拼接字符串的时候，都会构建一个新的String对象，即耗时，又浪费空间。使用StringBuilder类。

```java
StringBuilder s = new StringBuilder();
s.append("a");
s.append("b");
String completedString = s.toString();
System.out.println(completedString);
```

还有一个StringBuffer，这两个类之后再具体的学习。





读取输入：

```java
        Scanner in = new Scanner(System.in);
        String s1 = in.nextLine();//读取一行输入
        String s2 = in.next(); //读取一个单词，以空白符作为分割。
        int age = in.nextInt(); //读取一个整数
        double feng = in.nextDouble();
        System.out.println(s1);
        System.out.println(s2);
        System.out.println(age);
        System.out.println(feng);
```



注意要加上`import java.util.*`。当使用的类不是定义在基本java.lang包中时，一定要使用import指令导入相应的包。



格式化输出：

`System.out.print(x)`将数值x输出到控制台。这条命令将以x的类型所允许的最大非0数位个数打印输出x。

```java
double x = 1000.0/3.0;
System.out.print(x);
```



Java也沿用了C语言的printf，具体使用不说了。



可以使用静态的String.format方法创建一个格式化的字符串，而不打印输出：

```java
String x =String.format("hello,%s");
```





文件输入与输出：

要想读取一个文件，需要构造一个Scanner对象：

```java
Scanner in = new Scanner(Path.of("Java.iml"), StandardCharsets.UTF_8);
while(in.hasNextLine()){
    System.out.println(in.nextLine());
}
```



要想写入文件，就需要构造一个PrintWriter对象：

```java
PrintWriter out = new PrintWriter("1.txt",StandardCharsets.UTF_8);
out.println("hello,world");
out.println("hhhhhh");
out.close();//必须有，不然写不进去
```

如果文件不存在，则创建该文件。



关于路径问题，可以使用下面的调用找到启动目录的位置：

```java
String dir = System.getProperty("user.dir");
System.out.println(dir);
```





IntelliJ IDEA的启动目录总是和.idea同级而不是当然执行的src里面的那个Main.java。





Java中，不能在嵌套的两个块中声明同名的变量。

if语句：

```java
int feng = 321;
if(feng > 111){
    System.out.println("yes");
}else{
    System.out.println("no");
}
```



while语句：

```java
int feng = 0;
while(feng<10){
    System.out.println(feng);
    feng++;
}
```



for：

```java
for(int i = 0;i <= 10;i++){
    System.out.println(i);
}
```



**在循环中，检测两个浮点数是否相等需要格外小心**：

```java
for(double x = 0;x != 10;x+=0.1)
```

永远不会结束，由于舍入的误差，永远达不到精确的最终值。



```java
for(int i = 1; i <= 10; i++)
{
   
}
//i no longer defined here
//此外，如果在for语句内部定义一个变量，这个变量就不能在循环体之外使用。
```



switch：

```java
int x = 1;
switch(x)
{
    case 1:
        System.out.println("1");
        break;
    case 2:
        System.out.println("2");
        break;
    default:
        System.out.println("no");
        break;
}
```





**有可能触发多个case分支。如果在case分支语句的末尾没有break语句，那么就会接着执行下一个case分支语句。这种情况非常危险，因此最好不要使用switch语句。**



带标签的break语句：

```java
        int n = 123;
        int count = 0;
        feng:
        while(count < 10){
            for(int i = 0; i <= 5; i++){
                if(i > count){
                    break feng;
                }
            }
        }
        System.out.println(count);
```

虽然代码毫无逻辑，但是至少了解了一下带标签的break。

标签必须放在希望跳出的最外层循环之外，并且必须紧跟一个冒号。执行带标签的break会跳转到带标签的语句块末尾。这里就是结束这个while循环，所以打印出来的是0。



大数：

`java.math`包中两个很有用的类：`BigInteger`和`BigDecimal`。这两个类可以处理包含任意长度数字序列的数值。

```java
        BigInteger a = BigInteger.valueOf(10000000);
        System.out.println(a);
		//对于更大的数，就是用带字符串的构造器
        BigInteger b = new BigInteger("1000000000000000000000000000000000000");
        System.out.println(b);
```



此外Java没有提供运算符重载的功能，因此必须用add,multiply,divide,subtract等函数。





**数组**

声明数组：在声明数组变量的时候，需要指出数组类型（数组元素类型紧跟[]）和数组变量的名字。

```java
int[] a;
```

使用**new**操作符创建数组：

```java
int[] a = new int[100];
```



一旦创建了数组，就不能再改变它的长度。

还有一种创建数组对象并同时提供初始值的简写形式：

```java
int[] a = {1,2,3,4,5} //最后一个值后面允许有逗号
```

还可以声明匿名数组。这种语法可以重新初始化一个数组而无须创建新变量：

```java
        int[] a = {1,2,3,5,};
        a = new int[] {1,2,3};
        System.out.println(Arrays.toString(a));
```



Java中允许有长度为0的数组，创建长度为0的数组：

```java
new elementType[0];
new elementType[] {};
```



创建一个数字数组时，所有元素都初始化为0。boolean数组的元素会初始化为false。对象数组的元素则初始化为一个特殊值null，表示这些元素还未存放任何对象。

使用`array.length`获得数组中元素的个数：

```java
        int[] a = {1,2,3,5,};
        int[] b= new int[100];
        System.out.println(a.length);
        System.out.println(b.length);
```



Java中的for each循环：

`for(variable : collection) statement`

collection这一集合表达式必须是一个数组或者是一个实现了Iterable接口的类对象。

```java
        int[] a = {1,2,3,4,5,6,};
        for(int i : a){
            System.out.println(i);
        }
```

for each循环语句的循环变量将会遍历数组中的每个元素，而不是下标值。



`Arrays`类的`toString`方法可以简单的打印一个数组中的所有值：

```java
        int[] a = {1,2,3,4,5,6,};
        System.out.println(Arrays.toString(a));
```



数组拷贝：

将一个数组变量拷贝到另一个数组变量，这时两个变量将引用同一数组：

```java
        int[] a = {1,2,3,4,5,6,};
        int[] b = a;
        b[1] = 999;
        System.out.println(a[1]);//999
        System.out.println(b[1]);//999
```



如果希望将一个数组中的所有值拷贝到一个新的数组中去，常用Arrays类的copyOf方法：

```java
        int[] a = {1,2,3,4,5,6,};
        int[] b = Arrays.copyOf(a,a.length);
        b[1] = 999;
        System.out.println(a[1]);//2
        System.out.println(b[1]);//999
```



copyOf的第二个参数是新数组的大小，正常可以比原数组的长度要大，以增长数组。如果短的话，就只会拷贝前面的值。



`Arrays.sort()`，使用优化的快速排序算法。



**多维数组**

声明：

```java
double[][] feng;
```

初始化：

```java
feng = new double[2][2];
int[][] a = {
    {1,2,3},
    {4,5,6},
    {7,8,9},
};
```



for each不能自动处理二维数组中的每一个元素，它是循环行的，因此要使用2层for each。

快速打印一个二维数组：

```java
        int[][] a = {
                {1,2,3},
                {4,5,6},
                {7,8,9},
        };
        System.out.println(Arrays.deepToString(a));
```



















