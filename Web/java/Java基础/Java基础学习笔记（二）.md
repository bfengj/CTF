# 对象和类

**面向对象程序设计（object-oriented programming,OOP）**



由类构造(construct)对象的过程称为创建类的实例。

对象中的数据称为实例字段，操作数据的过程称为方法。



实现封装的关键在于，绝对不能让类中的方法直接访问其他类的实例字段。程序只能通过对象的方法与对象进行交互。

Java中，所有其他类都拓展自Object类。



类之间，最常见的关系：

- 依赖（uses-a）
- 聚合（has-a）
- 继承（is-a）

如果一个类的方法使用或操纵另一个类的对象，我们就说一个类依赖于另一个类。



Java中使用构造器构造新实例。构造器的名字应该与类名相同。

```java
        Date nowTime = new Date();
        System.out.println(nowTime);
```

对象变量并没有实际包含一个对象，它只是引用一个对象。

在Java中，任何对象变量的值都是对存储在另一个地方的某个对象的引用。new操作符的返回值也是一个引用。

在Java中，必须使用clone方法获得对象的完整副本。





标准Java类库分别包含了两个类：一个是用来表示时间点的Date类，另一个是大家熟悉的日历表示法表示日期的LocalDate类。

不要使用构造器来构造LocalDate类的对象，应当使用静态工厂方法。

```java
        LocalDate date1 = LocalDate.now();
        LocalDate date2 = LocalDate.of(2001,01,02);
        System.out.println(date1); //2021-05-08
        System.out.println(date2); //2001-01-02
        int year = date2.getYear();
        int month = date2.getMonthValue();
        int day = date2.getDayOfMonth();
        System.out.println(year);//2001
        System.out.println(month);//1
        System.out.println(day);//2
        LocalDate date3 = date2.plusDays(1);
        System.out.println(date3);//2001-01-03
        LocalDate date4 = date2.minusDays(1);
        System.out.println(date4);//2001-01-01
```



**更改器方法：**调用这个方法后，对象的状态会改变。

**访问器方法：**只访问对象而不修改对象的方法。



文件名必须与public类的名字相匹配。在一个源文件中，只能有一个公共类，但可以有任意数目的非公共类。





```java
import java.time.LocalDate;
import java.util.Date;

public class Main
{
    public static void main(String[] args){
        Employee[] staff = new Employee[3];
        staff[0] = new Employee("feng1",123,2001,1,2);
        staff[1] = new Employee("feng2",123,2001,1,2);
        staff[2] = new Employee("feng3",123,2001,1,2);
        for(Employee e:staff){
            e.raiseSalary(5);
        }
        for(Employee e:staff){
            System.out.println(e.getName());
        }
    }
}
```

```java
import java.time.LocalDate;

public class Employee
{
    private String name;
    private double salary;
    private LocalDate hireDay;

    public Employee(String name, double salary, int year, int month, int day){
        this.name = name;
        this.salary = salary;
        this.hireDay = LocalDate.of(year, month, day);
    }
    public String getName(){
        return this.name;
    }

    public double getSalary() {
        return this.salary;
    }

    public LocalDate getHireDay() {
        return this.hireDay;
    }

    public void raiseSalary(double byPercent){
        double raise = salary*byPercent/100;
        this.salary += raise;
    }
}
```



**构造器**

构造器与类同名。在构造Employee类的对象时，构造器会运行，从而将实例字段初始化为所希望的初始状态。

构造器总是结合new运算符来调用。不能对一个已经存在的对象调用构造器来达到重新设置实例字段目的。



不要在构造器中定义与实例字段同名的局部变量。



**从Java10开始，对于局部变量，如果可以从变量的初始值推断出它的类型，就不再需要声明类型，只需要使用关键字var而无需指定类型**

注意var关键字只能用于方法中的局部变量。参数和字段的类型必须声明。



如果对null值应用一个方法，会产生一个`NullPointerException`异常。



**隐式参数和显式参数**

`raiseSalary`方法有2个参数，第一个参数称为隐式参数，是出现在方法名前的Employee类型的对象。第二个参数是位于方法名后面括号中的数值，这事一个显式参数。

在每一个方法中，关键字`this`指示隐式参数。



**注意不要编写返回可变对象引用的访问器方法**

如果需要返回一个可变对象的引用，首先应该对它进行克隆。对象克隆是指存放在另一个新位置上的对象副本。



**一个方法可以访问所属类的所有对象的私有数据**

```java
    public String getOtherName(Employee other){
        return other.name;
    }

        Employee other = new Employee("fengfeng",123,2001,1,2);
        System.out.println(staff[0].getOtherName(other));
```

staff[0]对象的`getOtherName`方法还可以访问other对象的私有字段name，是因为他们都是`Employee`类型对象，`Employee`类的方法可以访问任意`Employee`类型对象的私有字段。



可以将实例字段定义为final。这样的字段必须在构造对象时初始化，并且以后不能再修改这个字段。

如果类中的所有方法都不会改变其对象，这样的类就是不可变的类。



**静态字段**

如果将一个字段定义为static，每个类只有一个这样的字段。而对于非静态的实例字段，每个对象都有自己的一个副本。

静态字段属于类，而不属于任何单个的对象。



**静态常量**

静态常量很常用，例如：

```java
public class Employee
{
    public static final double PI = 3.141592653;
}
```

然后就可以用`Employee.PI`来访问这个静态常量。



**静态方法**

静态方法是不在对象上执行的方法。例如Math类的pow方法。

可以认为静态方法是没有`this`参数的方法（没有隐式参数）

静态方法不能访问实例字段，但是可以访问静态字段。

建议使用类名而不是对象来调用静态方法。



下面两种情况下可以使用静态方法：

- 方法不需要访问对象状态，因为它需要的所有参数都通过显式参数提供。
- 方法只需要访问类的静态字段。





**Java程序设计语言总是采用按值调用。也就是说，方法得到的是所有参数值的一个副本。**具体来讲，方法不能修改传递给它的任何参数变量的内容。



方法得到的是对象引用的副本，原来的对象引用和这个副本都引用同一个对象。

实际上，对象引用是按值传递的。



- 方法不能修改基本数据类型的参数。
- 方法可以改变对象参数的状态。
- 方法不能让一个对象参数引用一个新的对象。





**重载**

如果多个方法有相同的名字、不同的参数，便出现了重载。编译器必须挑选出具体调用哪个方法。



> Java允许重载任何方法，而不只是构造器方法。因此，要完整地描述一个方法，需要指定方法名以及参数类型。这叫做方法的签名。
>
> **返回类型不是方法签名的一部分。也就是说，不能有两个名字相同、类型参数也相同却有不同返回类型的方法。**





如果在构造器中没有显式地为字段设置值，那么就会被自动地赋为默认值：数值为0、布尔值为false、对象引用为null。

> 方法中的局部变量必须明确地初始化，但是在类中，如果没有初始化类中的字段，将会自动初始化为默认值。





无参构造器创建对象时，对象的状态会设置为适当的默认值。如果写一个类时没有编写构造器，就会为你提供一个无参数构造器。这个构造器将所有的实例字段设置为默认值。

如果类中提供了至少一个构造器，但是没有提供无参数的构造器，那么构造对象时如果不提供参数就是不合法的。



可以在类定义中直接为任何字段赋值：

```java
public class Employee
{
    private String name="feng";
```



初始值不一定是常量值，可以利用方法调用初始化一个字段：

```java
    private static int nextId ;
    private int id= assignId();
    private static int assignId(){
        int r = nextId;
        nextId++;
        return r;
    }
```



**调用另一个构造器**

关键字`this`指示一个方法的隐式参数。此外，如果构造器的**第一个语句**形如`this(...)`，这个构造器将调用同一个类的另一个构造器。



**初始化块(不常见)**

在一个类的声明中，可以包含任意多个代码块。只要构造这个类的对象，这些块就会被执行。

```java
    {
        id = nextId;
        nextId ++;
    }
    public Employee(String name, double salary){
        this.name = name;
        this.salary = salary;
    }
```

**首先**运行初始化块，然后才运行构造器的主体部分。通常直接把初始化代码放在构造器中。



如果类的静态字段需要很复杂的初始化代码，那么可以使用静态的初始化块：

```java
static
{
    var generator = new Random();
    nextId - generator.nextInt(10000);
}
```





**由于Java会完成自动的垃圾回收，不需要人工回收内存，所以Java不支持析构器。**





**包**

> Java允许使用包将类组织在一个集合中。借助包可以方便地组织自己的代码，并将自己的代码与别人提供的代码库分开管理。



使用包的主要原因是确保类名的唯一性。为了保证包名的绝对唯一性，要用一个因特网域名以逆序的形式作为包名，然后对于不同的工程使用不同的子包。



**一个类可以使用所属包中的所有类，以及其他包中的公共类**

可以使用import语句导入一个特定的类或者整个包。import语句应该位于源文件的顶部（但位于package语句的后面）。

例如导入`java.util`包中的所有类：

```java
import java.time.*;
```



只能使用星号导入一个包，而不能使用`import java.*`或者`import java.*.*`导入所有以java为前缀的包。



有一种import 语句允许导入静态方法和静态字段，而不只是类。例如：

```java
import static java.lang.System.*;
```



就可以使用`System`类的静态方法和静态字段，而不必加类前缀。



要想将类放入包中，就必须将包的名字放在源文件的开头，即放在定义这个包中各个类的代码之前。

如果没有在源文件中放置package语句，这个源文件中的类就属于无名包。

将源文件放在与完整包名匹配的子目录中。



Java和Php不同的是，Java其实算有4种访问修饰符。如果没有指定public或private，这个部分（类、方法或变量）可以被同一个包中的所有方法访问。

> 类的成员不写访问修饰时默认为default。默认对于同一个包中的其他类相当于公开（public），对于不是同一个包中的其他类相当于私有（private）



![img](https://img-blog.csdn.net/20170105101714209?watermark/2/text/aHR0cDovL2Jsb2cuY3Nkbi5uZXQvcXFfMzMzNDIyNDg=/font/5a6L5L2T/fontsize/400/fill/I0JBQkFCMA==/dissolve/70/gravity/Center)



> Jar文件使用ZIP格式组织文件和子目录。可以使用任何ZIP工具查看JAR文件。



为了使类能够被多个程序共享，需要做到下面几点：

1. ​	把类文件放在一个目录中。需要注意，这目录是包树状结构的基目录。
2. 将JAR文件放在一个目录中。
3. 设置类路径。类路径是所有包含类文件的路径的集合。



最好使用-classpath选项指定类路径。



**JAR文件**

JAR文件是压缩的，它使用了我们熟悉的ZIP压缩格式。

通过下面的命令启动程序：

```
java -jar xxxxxx.jar
```



**类设计技巧**

1. 一定要保证数据私有。
2. 一定要对数据进行初始化。
3. 不要在类中使用过多的基本类型。
4. 不是所有的字段都需要单独的字段访问器和字段更改器。
5. 分解有过多职责的类。
6. 类名和方法名要能够体现它们的职责。
7. 优先使用不可变的类。









