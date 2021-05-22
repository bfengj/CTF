# 继承



**继承**的基本思想是，可以基于已有的类创建新的类。继承已存在的类就是复用（继承）这些类的方法，而且可以增加一些新的方法和字段，使新类能够适应新的情况。



## 类、超类和子类

使用extends表示继承。

通过拓展超类定义子类的时候，只需要指出子类与超类的不同之处。



只有父类的方法能访问父类的私有字段，这意味如果子类的方法不能直接访问父类的私有字段，需要使用公共接口，类似`getSalary()`这样的：

```java
    public double getSalary(){
        return this.bonus+super.getSalary();
    }
```



这里使用的是`super.getSalary()`，因为我们希望调用的是超类的`getSalary`方法，而不是当前类的这个方法。



继承绝对不会删除任何字段或方法。



子类的构造器：

```java
    public Manager(String name, double salary){
        super(name,salary);
        bonus = 0;
    }
```

`super(name,salary);`是调用超类`Employee`中的这个构造器：

```java
    public Employee(String name, double salary){
        this.name = name;
        this.salary = salary;
    }
```



**使用super调用构造器的语句必须是子类构造器的第一条语句。**

如果子类的构造器没有显式地调用超类的构造器，讲自动调用超类的无参数构造器。如果超类没有无参数的构造器，并且在子类的构造器中又没有显式地调用超类的其他构造器，Java编译器就会报告一个错误。



this：

- 指示隐式参数的引用。
- 调用该类的其他构造器。

super：

- 调用超类的方法。
- 调用超类的构造器。



```java
    public static void main(String[] var0) {
        var staff = new Employee[3];
        Manager boss = new Manager("feng1",123);
        boss.setBonus(123);
        staff[0] = boss;
        staff[1] = new Employee("feng2",123);
        staff[2] = new Employee("feng3",123);
        for(Employee e:staff){
            System.out.println(e.getName());
            System.out.println(e.getSalary());
        }
    }
```

尽管将e声明为Employee类型，但实际上e既可以引用Employee类型的对象，也可以引用Manager类型的对象。

虚拟机知道e实际引用的对象类型，因此能够正确地调用相应的方法。

一个对象变量可以指示多种实际类型的现象称为**多态**。在运行时能够自动地选择适当的方法，称为**动态绑定**。



### 多态

is-a规则，它指出子类的每个对象也是超类的对象。

**is-a规则的另一种表述是替换原则。它指出程序中出现超类对象的任何地方都可以使用子类对象替换。**

在Java程序设计语言中，对象变量是多态的。一个Employee类型的变量既可以引用一个Employee类型的对象，也可以引用Employee类的任何一个子类的对象。

不能将超类的引用赋给子类变量。

> 在Java中，子类引用的数组可以转换成超类引用的数组，而不需要使用强制类型转换。







在覆盖一个方法的时候，子类方法不能低于超类方法的可见性。特别是，如果超类方法是public，子类方法必须也要声明为public。



### 阻止继承：final类和方法

不允许拓展的类被称为final类。在定义类的时候使用final修饰符就表明这个类是final类。

类中的某个特定方法也可以被声明为final。如果这样做，子类就不能覆盖这个方法（final类中的所有方法自动地成为final方法，但是不包括字段，也就是说字段不会自动成为final）。



### 强制类型转换

**将一个值存入变量时，编译器将检查你是否承诺过多。如果将一个子类的引用赋值给一个超类变量，编译器是允许的。但将一个超类的引用赋给一个子类变量时，就承诺过多了。必须进行强制类型转换，这样才能够通过运行时的检查。**

```java
        Manager test1 = new Manager("test1",111);
        Employee test2 = new Employee("test2",111);
        Employee test3 = test1;  //可以
        Manager test4 = (Manager) test2;  //必须强制类型转换。而且这样是有问题的。
```



> 我个人的理解就是，子类可以直接隐式转换成父类，但是父类需要强制类型转换成子类。



在进行强制类型转换之前，先查看是否能够成功地转换。为此只需要使用instanceof操作符就可以实现。

`instanceof`是Java中的二元运算符，左边是对象，右边是类；当对象是右边类或子类所创建对象时，返回true；否则，返回false。

- `instanceof`是Java中的二元运算符，左边是对象，右边是类；当对象是右边类或子类所创建对象时，返回true；否则，返回false。
- `instanceof`左边显式声明的类型与右边操作元必须是同种类或存在继承关系，也就是说需要位于同一个继承树，否则会编译错误。

```java
        Manager test1 = new Manager("test1",111);
        Employee test2 = new Employee("test2",111);
        Employee test3 = test1;
        //Manager test4 = (Manager) test2;
        if (test2 instanceof Manager){
            Manager test4 = (Manager) test2;
        }
```

所以很明显这是进入不了`if`的。



- 只能在继承层次内进行强制类型转换。
- 在将超类强制转换成子类之前，应该使用`instanceof`进行检查

**一般情况下，最好尽量少用强制类型转换和`instanceof`运算符**



### 抽象类

为了提高程序的清晰度，包含一个或多个抽象方法的类本身必须被声明为**抽象**的。

```java
public abstract class Person 
{
    public abstract String getDescription();
}
```



除了抽象方法之外，抽象类还可以包含字段和具体方法。

```java
public abstract class Person
{
    private String name;

    public Person(String name){
        this.name = name;
    }
    public abstract String getDescription();

    public String getName(){
        return this.name;
    }
}
```

> 有些程序员认为，在抽象类中不能包含具体方法，。建议尽量将通用的字段和方法（不管是否是抽象的）放在超类（不管是否是抽象类）中。



拓展抽象类可以有两种选择，一种是在子类中保留抽象类的部分或所有抽象方法仍未定义，这样就必须将子类也标记为抽象类；另一种做法是定义全部方法，这样一来，子类就不是抽象的了。



即使不含抽象方法，也可以将类声明为抽象类。

**抽象类不能实例化。**但是可以定义一个抽象类的成员变量，但是这样一个变量只能引用非抽象子类的对象。

```java
Person person = new Student("feng","aaa");
```



### 受保护访问

在Java中，保护字段只能由同一个包中的类访问。

1. 仅对本类可见：private
2. 对外部完全可见：public
3. 对本包和所有子类可见：protected
4. 对本包可见：默认（很遗憾），不需要修饰符





## Object：所有类的超类



可以使用`Object`类型的变量引用任何类型的对象：

```java
Object obj = new Manager("feng",123);
```

`Object`类型的变量只能用于作为各种值得一个泛型容器。要想对其中得内容进行具体的操作，还需要清楚对象的原始类型，并进行相应的强制类型转换；

```java
        Object obj = new Manager("feng",123);
        Manager feng = (Manager) obj;
        System.out.println(feng.getName());
```

在Java中，只有基本类型不是对象，例如数值、字符、布尔类型等都不是对象。



### equals方法

`Object`类中的`equals`方法用于检测一个对象是否等于另外一个对象。

例如Employee类实现一个`equals`方法：

```java
    public boolean equals(Object otherObject)
    {
        if(this == otherObject) return true;

        if(otherObject == null) return false;

        if(getClass() != otherObject.getClass()) return false;

        Employee other = (Employee) otherObject;

        String name = getName();
        return Objects.equals(getName(),other.getName())
                &&salary == other.getSalary()
                &&getId()==other.getId();
    }
```



`getClass`方法将返回一个对象所属的类。

> Returns the runtime class of this `Object`. The returned `Class` object is the object that is locked by `static synchronized` methods of the represented class.

为了防备name可能为null的情况，需要使用`Objects.equals`方法。如果两个参数都为null，则`Objects.equals(a,b)`调用将返回true。如果其中一个参数为null，则返回false；否则，如果两个参数都不为null，则调用`a.equals(b)`。



完美的`equals`方法的建议：

1. 显式参数命名为otherObject，稍后需要将它强制转换为另一个名为other的变量。
2. 检测this与`otherObject`是否相等：
   `if(this == otherObject) return true;`
3. 检测`otherObject`是否为null，如果为null，返回false。这项检测是很必要的。
   `if(otherObject == null) return false;`
4. 比较this与`otherObject`的类。如果`equals`的语义可以在子类中改变，那就使用`getClass`检测：
   `if(getClass() != otherObject.getClass()) return false;`
   如果所有的子类都有相同的相等性语义，可以使用`instanceof`检测：
   `if(!(otherObject instanceof ClassName)) return false;`
5. 将`otherObject`强制转换为相应类类型的变量：
   `ClassName other = (ClassName) otherObject;`
6. 根据相等性概念的要求来比较字段。使用`==`比较基本类型字段，使用`Objects.equals`比较对象字段。如果所有的字段都匹配，就返回true；否则返回false。
   如果在子类中重新定义`equals`，就要在其中包含一个`super.equals(other)`的调用。



> 对于数组类型的字段，可以使用静态的`Arrays.equals`方法检测相应的数组元素是否相等。



可以使用`@Override`标记要覆盖超类方法的那些子类方法：

```java
    @Override
    public boolean equals(Object otherObject)
```

如果出现了错误，并且正在定义一个新方法，编译器就会报告一个错误。

例如这样，并没有覆盖`Object`类的`equals`方法，因此会报告错误：

```java
    @Override
    public boolean equals(Employee otherObject)
```



### `toString`方法

返回表示对象值的一个字符串。

只要对象与一个字符串通过操作符 “+”连接起来，Java编译器就会自动地调用`toString`方法来获得这个对象的字符串描述。



> 可以不写为`x.toString()`，而写作`""+x`。与`toString`不同的是，即使x是基本类型，这条语句照样能够执行。



打印数组利用`Arrays.toString`：

```java
        double[] x = {1,2,3,4,5};
        System.out.println(Arrays.toString(x));
```



## 泛型数组列表

`ArrayList`是一个有类型参数的泛型类。为了指定数组列表保存的元素对象的类型，需要用一对尖括号将类名括起来追加到`ArrayList`后面，例如`ArrayList<Integer>`。

```java
        ArrayList<Employee> staff1 = new ArrayList<Employee>();
        ArrayList<Employee> staff2 = new ArrayList<>();
        var staff3 = new ArrayList<Employee>();
```

最好使用var关键字以避免重复写类名；如果没有使用var关键字，可以省去右边的类型参数。（菱形语法）

当然还可以把初始容量传递给`ArrayList构造器`：

```java
        ArrayList<Employee> staff1 = new ArrayList<Employee>(100);
```

> 数组列表的容量与数组的大小有一个非常重要的区别。如果分配一个有100个元素的数组，数组就有100个空位置可以使用。而容量为100个元素的数组列表只是**可能**保存100个元素。但是在最初，甚至完成初始化构造之后，数组列表不包含任何元素。



使用**add**方法可以将元素添加到数组列表中。如果需要在数组列表的中间插入元素，可以使用add方法并提供一个索引参数。

要设置第i个元素，可以使用`set`。

要得到数组列表的元素，可以使用`get`。

```java
        ArrayList<Employee> staff = new ArrayList<>();
        staff.add(new Employee("feng",123));
        System.out.println(staff.get(0).getName()); //feng
        staff.set(0,new Employee("hhh",3456));
        System.out.println(staff.get(0).getName()); //hhh
```



> 只有当数组列表的大小大于i时，才能够调用`list.set(i,x)`。set方法只是用来替换数组中已经加入的元素。



size方法将返回数组列表中包含的**实际元素个数**。

```java
        ArrayList<Employee> staff = new ArrayList<>(100);
        System.out.println(staff.size()); //0
        staff.add(new Employee("feng",123));
        System.out.println(staff.size()); //1
```



`remove`方法可以从数组列表中间删除一个元素：

```java
        ArrayList<Employee> staff = new ArrayList<>(100);
        System.out.println(staff.size()); //0
        staff.add(new Employee("feng",123));
        System.out.println(staff.size()); //1
        Employee e = staff.remove(0);
        System.out.println(staff.size()); //0
        System.out.println(e.getName());  //feng
```



可以使用`for each`循环遍历数组列表的内容：

```java
        ArrayList<Employee> staff = new ArrayList<>();
        staff.add(new Employee("feng1",123));
        staff.add(new Employee("feng2",123));
        staff.add(new Employee("feng3",123));
        staff.add(new Employee("feng4",123));
        for(Employee e:staff){
            System.out.println(e.getName());
        }
```







## 对象包装器与自动装箱

所有的基本类型都有一个与之对应的类。这些类称为包装器。

```java
Integer,Long,Float,Double,Short,Byte,Character,Boolean (前六个类派生于公共的超类Number)
```

包装器类是不可变的，即一旦构造了包装器，就不允许更改包装在其中的值。同时，包装器类型还是`final`，因此不能派生它们的子类。

对于`ArrayList`，尖括号中的类型不允许是基本类型，因此要用到包装器类，例如`Integer`等。

```java
var list = new ArrayList<Integet>();
```



对于这种调用

```java
        var list =new ArrayList<Integer>();
        list.add(2);
        System.out.println(list.get(0));
```



将自动变成：

```java
        var list =new ArrayList<Integer>();
        list.add(Integer.valueOf(2));
        System.out.println(list.get(0));
```



这种变换称为自动装箱。

相反的，把`Integet`赋给一个`int`时，会自动拆箱：

```java
int n = list.get(0);
System.out.println(n);
```



装箱和拆箱是编译器要做的工作，而不是虚拟机。**编译器**在生成类的字节码时会插入必要的方法调用。**虚拟机**只是执行这些字节码。





## 参数数量可变的方法

可以提供参数数量可变的方法（有时这些方法被称为"变参"(varargs)方法）。

这里的省略号`...`是Java代码的一部分，它表明这个方法可以接收任意数量的对象。例如：

```java
    public static void main(String[] args) {
        printNumber(123,456);
    }

    public static void printNumber(double... numbers){
        for(double e:numbers){
            System.out.println(e);
        }
    }
```

> 允许将数组作为最后一个参数传递给可变参数的方法。
>
> 因此，如果一个已有方法的最后一个参数是数组，可以把它重新定义为有可变参数的方法，而不会破坏任何已有的代码。





## 枚举类

定义枚举类型：

```java
    public enum Size {SMALL,MEDIUM,LARGE,EXTRA_LARGE};
```

实际上，这个声明定义的类型是一个类，它刚好有四个实例，不可能构造新的对象。

因此，在比较两个枚举类型的值时，并不需要调用`equals`，直接使用`==`就可以了。

同样可以为枚举类型增加构造器、方法和字段。

```java
enum Size
{
    SMALL("S"),MEDIUM("M"),LARGE("L"),EXTRA_LARGE("XL");

    private String abbreviation;
    private Size(String abbreviation)
    {
        this.abbreviation = abbreviation;
    }
    public String getAbbreviation()
    {
        return this.abbreviation;
    }
}
```



枚举的构造器总是私有的。

所有的枚举类型都是`Enum`类的子类。

```java
System.out.println(Size.SMALL.getClass().getName());//com.javalearn.Size
```



它们继承了这个类的许多方法，其中最有用的一个是`toString`，这个方法返回枚举常量名：

```java
System.out.println(Size.SMALL.toString());  //SMALL
System.out.println(Size.SMALL.toString().getClass().getName());  //java.lang.String
```



`toString`的逆方法是静态`valueOf`：

```java
System.out.println(Enum.valueOf(Size.class,"SMALL"));//SMALL
System.out.println(Enum.valueOf(Size.class,"SMALL").getClass().getName());//com.javalearn.Size
```



每个枚举类型都有一个静态的`values`方法，它将返回一个包含全部枚举值的数组。

```java
        Size[] values = Size.values();
        for(Size e:values){
            System.out.println(e);
        }
/*
SMALL
MEDIUM
LARGE
EXTRA_LARGE
*/
```

`ordinal`方法返回enum声明中枚举常量的位置，位置从0开始计数。

```java
System.out.println(Size.SMALL.ordinal()); //0
```







## 反射

**（这部分只是跟着书上的反射知识过了一遍，之后会再专门针对Java安全中反射的利用进行一下学习）**

反射库提供了一个丰富且精巧的工具集，可以用来编写能够动态操纵Java代码的程序。

能够分析类能力的程序称为**反射**。

反射机制可以用来：

- 在运行时分析类的能力。
- 在运行时检查对象。
- 实现泛型数组操作代码。
- 利用Method对象，这个对象很像C++中的函数指针。



### Class类

在程序运行期间，Java运行时系统始终为所有对象维护一个运行时类型标识。这个信息会跟踪每个对象所属的类。虚拟机利用运行时类型信息选择要执行的正确方法。

可以使用一个特殊的Java类访问这些信息。保存这些信息的类名为`Class`。

`Object`类中的`getClass()`方法将会返回一个`Class`类型的实例。

```java
        var e = new Employee("feng",123);
        Class cl = e.getClass();
        System.out.println(cl); //class com.javalearn.Employee
```



`Class`对象会描述一个特定类的属性。最常用的Class方法就是`getName`。这个方法将返回类的名字。

```java
        var e = new Employee("feng",123);
        Class cl = e.getClass();
        System.out.println(cl.getName());
```

如果类在一个包里，包的名字也作为类名的一部分。所以我这里会返回`com.javalearn.Employee`



还可以使用静态方法`forName`获得类名对应的`Class`对象。

```java
        String className = "com.javalearn.Employee";
        Class cl = Class.forName(className);
```



获得`Class`类对象的第三种方法是一个很方便的快捷方式。如果T是任意的Java类型（或void关键字），`T.class`将代表匹配的类对象。

> `Class`类实际上是一个泛型类。



虚拟机为每个类型管理一个唯一的`Class`对象。因此，可以利用`==`运算符实现两个类对象的比较。

```java
        var e = new Employee("feng",123);
        if(e.getClass() == Employee.class){
            System.out.println("ok");
        }
```



如果有一个`Class`类型的对象，可以用它构造类的实例。调用`getConstructor`方法将得到一个`Constructor`类型的对象，然后使用`newInstance`方法来构造一个实例。

```java
        String className = "com.javalearn.Employee";
        Class cl = Class.forName(className);
        Object obj = cl.getConstructor().newInstance();
        System.out.println(obj.getClass().getName());
```

但是这个类如果没有无参数的构造器，`getConstructor`方法会抛出异常。



### 声明异常入门

当运行时发生错误时，程序就会“抛出一个异常”。我们可以提供一个处理器“捕获”这个异常并进行处理。

异常有两种类型，非检查型异常和检查型异常。

如果一个方法包含一条可能抛出检查型异常的语句，则在方法名上增加一个`throws`子句：

```java
public static void main(String[] args) throws ReflectiveOperationException {
```

调用这个方法的任何方法也都需要一个`throws`声明。



### 利用反射分析类的能力



在`java.lang.reflect`包中有三个类`Field、Method和Constructor`分别用于描述类的字段、方法和构造器。这三个类都有一个叫做`getName`的方法，用来返回字段、方法或构造器的名称。

`Field`类中有`getType`方法用来返回描述字段类型的一个对象。`Method`和`Constructor`也有报告参数类型的方法：`getParameterTypes`。`Method`类还有一个报告返回类型的方法`getReturnType`。

这三个类都有一个名为`getModifiers`的方法，它将返回一个整数，用不同的0/1位描述所使用的修饰符，如public和static。还可以利用`Modifier.toString`方法将修饰符打印出来。



`Class`类中的`getFields`、`getMethods`和`getConstructors`方法将分别返回这个类支持的**公共**字段、方法和构造器的数组，其中包括超类的**公共**成员。

`Class`类的`getDeclareFields`、`getDeclareMethods`和`getDeclaredConstructors`方法将分别返回类中声明的全部字段、方法和构造器的数组，其中包括私有成员、包成员和受保护成员，**但不包括超类的成员** 。



下面这个程序显示如何打印一个类的全部信息：

```java
package com.javalearn;

import java.lang.reflect.Constructor;
import java.lang.reflect.Field;
import java.lang.reflect.Method;
import java.lang.reflect.Modifier;

public class ReflectionTest
{
    public static void main(String[] args) throws ReflectiveOperationException
    {
        String className = "com.javalearn.Employee";
        Class cl = Class.forName(className);
        Class supercl = cl.getSuperclass();
        String modifiers = Modifier.toString(cl.getModifiers());
        if (modifiers.length()>0) System.out.print(modifiers+" ");
        System.out.print("class " + className);
        if(supercl !=null && supercl !=Object.class) System.out.print(" extends " + supercl.getName());
        System.out.print("\n{\n");
        printConstructors(cl);
        System.out.println();
        printMethods(cl);
        System.out.println();
        printFields(cl);
        System.out.print("}");
    }

    public static void printConstructors(Class cl)
    {
        Constructor[] constructors = cl.getDeclaredConstructors();
        for(Constructor c:constructors)
        {
            String name = c.getName();
            System.out.print("   ");
            String modifiers = Modifier.toString(c.getModifiers());
            if(modifiers.length()>0) System.out.print(modifiers + " ");
            System.out.print(name + "(");

            Class[] paramTypes = c.getParameterTypes();
            for(int j = 0;j<paramTypes.length;j++)
            {
                if(j>0) System.out.print(", ");
                System.out.print(paramTypes[j].getName());
            }
            System.out.println(");");

        }
    }

    public static void printMethods(Class cl)
    {
        Method[] methods = cl.getDeclaredMethods();
        for(Method m :methods)
        {
            Class retType = m.getReturnType();
            String name = m.getName();

            System.out.print("   ");
            String modifiers = Modifier.toString(m.getModifiers());
            if(modifiers.length()>0) System.out.print(modifiers+" ");
            System.out.print(retType.getName() + " " + name + "(");

            Class[] paramTypes = m.getParameterTypes();
            for(int j =0; j<paramTypes.length;j++)
            {
                if(j>0) System.out.print(", ");
                System.out.print(paramTypes[j].getName());
            }
            System.out.println(");");

        }
    }

    public static void printFields(Class cl)
    {
        Field[] fields = cl.getDeclaredFields();

        for(Field e:fields)
        {
            Class type = e.getType();
            String name = e.getName();
            System.out.print("   ");
            String modifiers = Modifier.toString(e.getModifiers());
            if(modifiers.length()>0) System.out.print(modifiers+" ");
            System.out.println(type.getName()+" " + name+ ";");
        }
    }
}

```





### 使用反射在运行时分析对象

要想查看字段的具体内容，关键方法是`Field`类中的`get`方法。如果`f`是一个`Field`类型的对象（例如，通过`getDeclaredFields`得到的对象），`obj`是某个包含`f`字段的类的对象，`f.get(obj)`将返回一个对象，其值为`obj`的当前字段值。

此外，`f.set(obj,value)`将把对象`obj`的`f`表示的字段设置为新值`value`。

但是这有一个问题，如果那个字段是私有字段，`get`和`set`会抛出一个`IllegalAccessException`。只能对可以访问的字段使用`get`和`set`方法。

反射机制的默认行为受限于`Java`的访问控制。不过，可以调用`Field`、`Method`或`Constructor`对象的`setAccessible`方法覆盖`Java`的访问控制：

```java
f.setAccessible(true);
```

`setAccessible`方法是`AccessibleObejct`类的一个方法，它是`Field`、`Method`和`Constructor`类的公共超类。这个特性是为调式、持久存储和类似机制提供的。

所以具体获得一个字段的代码可以是这样：

```java
        var feng = new Employee("feng",123);
        Class cl = feng.getClass();
        Field f = cl.getDeclaredField("salary");
        f.setAccessible(true);
        Object v = f.get(feng);
        System.out.println(v);
```



### 调用任意方法和构造器

可以用`Field`类的`get`方法查看一个对象的字段。与之类似，`Method`类有一个`invoke`方法，允许你调用包装在当前`Method`对象中的方法。`invoke`方法的签名：

```java
Object invoke (Object obj, Object... args)
```

第一个参数是隐式参数，其余的对象提供显示参数。对于静态方法，第一个参数可以忽略，即可以把它设置为null。

如果返回类型是基本类型，`invoke`方法会返回其包装器类型。



想要得到`Method`对象的话，可以调用`getDeclareMethods`方法，也可以调用`Class`类的`getMethod`方法或`getDeclaredMethod`方法。不过，有可能存在若干个同名的方法，因此要准确地得到想要的那个方法，**必须提供想要的方法的参数类型**。`getMethod`的签名：

```java
Method getMethod(String name, Class... parameterTypes)
```



所以得到那个方法可能还需要提供方法签名。之后调用`invoke`方法即可调用：

```java
public void test(String name)
{
    System.out.println(name);
}


var method = cl.getMethod("test", String.class);
method.invoke(feng,"123");
```



可以使用类似的方法调用任意的构造器。将构造器的参数类型提供给`Class.getConstructor`方法，并把参数值提供给`Constructor.newInstance`方法。

```java
        Class cl  = Employee.class;
        Constructor cons = cl.getConstructor(String.class,double.class);
        Object obj = cons.newInstance("feng",123);
        Employee feng = (Employee) obj;
        System.out.println(feng.getName());//feng
```



## 继承的设计技巧

1. 将公共操作和字段放在超类中。
2. 不要使用受保护的字段。
3. 使用继承实现"is-a"关系。
4. 除非所有继承的方法都有意义，否则不要使用继承。
5. 在覆盖方法时，不要改变预期的行为。
6. 使用多态，而不要使用类型信息。
7. 不要滥用反射。























































