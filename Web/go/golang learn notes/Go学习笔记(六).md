# 前言

开始学习第十章 结构(struct) 与 方法(method)



# 结构体定义

```go
type identifier struct {
    field1 type1
    field2 type2
    ...
}
```



```go
package main

import "fmt"

type point struct {
	x float64
	y float64
}

func main() {
	var p1 point
	p1.x = 5
	p1.y = 5
	fmt.Println(p1)
	fmt.Println(p1.x)
	fmt.Println(p1.y)
}
```





使用 **new** 函数给一个新的结构体变量分配内存，它返回指向已分配内存的指针：

```go
package main

import "fmt"

type point struct {
	x float64
	y float64
}

func main() {
	var p1 *point
	p1 = new(point)
	p1.x = 1
	p1.y = 1
	fmt.Println(p1)//&{1 1}
	p2 := new(point)
	p2.x = 2
	p2.y = 2
	fmt.Println(p2)//&{2 2}
}
```



声明 `var t T` 也会给 `t` 分配内存，并零值化内存，但是这个时候 `t` 是类型 T。在这两种方式中，`t` 通常被称做类型 T 的一个实例（instance）或对象（object）。



初始化结构体实例：

```go
package main

import "fmt"

type point struct {
	x float64
	y float64
}

func main() {
	p1 := &point{1,2} //必须按照字段顺序来写
	fmt.Println(p1)
    p2 := &point{y:1,x:2}//带字段名
	fmt.Println(p2)
}
```



表达式`new(Type)` 和 `&Type{}`是等价的。



一个导出的结构体类型中有些字段是导出的，另一些不是，这是可能的。



# 结构体工厂

Go 语言不支持面向对象编程语言中那样的构造子方法，但是可以很容易的在 Go 中实现 “构造子工厂” 方法，通常以`new`或者`New`开头：

```go
package main

import "fmt"

type Point struct {
	x float64
	y float64
}

func NewPoint(x float64, y float64) *Point {
	return &Point{x,y}
}

func main() {
	p1 := NewPoint(1,2)
	fmt.Println(p1)
}
```



有点类似与面向对象中的`new Point(...)`，但是这更简便。



而且如果结构体的首字母是小写（对包外不可见），这样包外想要使用这个结构体，就只能用工厂方法了。



# 结构体的标签

结构体中的字段除了有名字和类型外，还可以有一个可选的标签（tag）：它是一个附属于字段的字符串，可以是文档或其他的重要标记：

```go
type Point struct {
	x float64 "this is x"
	y float64 "this is y"
}
```

需要通过反射获取，下一章会学到：

```go
package main

import (
	"fmt"
	"reflect"
)

type Point struct {
	x float64 "this is x"
	y float64 "this is y"
}

func NewPoint(x float64, y float64) *Point {
	return &Point{x,y}
}

func main() {
	tt := NewPoint(1,5)
	for i := 0; i < 2; i++ {
		refTag(tt, i)
	}
}

func refTag(tt *Point, ix int) {
	ttType := reflect.TypeOf(tt)
	if ttType.Kind() == reflect.Ptr {
		ttType = ttType.Elem()
	}
	ixField := ttType.Field(ix)
	fmt.Printf("%v\n", ixField.Tag)
}
//this is x
//this is y
```





# 匿名字段

就是结构体中的字段也可以没有显式的名字，这样它的类型就是它的名字：

```go
package main

import "fmt"

type Person struct {
	int
	string
}

func main() {
	person := new(Person)
	person.int = 5
	person.string = "ego"
	fmt.Println(person)
}
```

在一个结构体中对于每一种数据类型只能有一个匿名字段。



# 内嵌结构体

匿名字段本身也可以是一个结构体，因此叫做内嵌结构体，比如这样：

```go
package main

import "fmt"

type Thing struct {
	thing1 string
	thing2 string
}
type Person struct {
	age int
	name string
	Thing
}

func main() {
	person1 := new(Person)
	person1.age = 5
	person1.name = "ego"
	person1.thing1 = "aaa"
	person1.thing2 = "bbb"
	fmt.Println(person1)//&{5 ego {aaa bbb}}
	person2 := Person{1,"ego",Thing{"abc","def"}}
	fmt.Println(person2)//{1 ego {abc def}}
}

```

可以直接通过`person1.thing1`来访问内嵌结构体，也可以通过`person1.Thing.thing1`来访问。



这种内嵌结构体可以用来模拟类似继承的行为。Go 语言中的继承是通过内嵌或组合来实现的，所以可以说，在 Go 语言中，相比较于继承，组合更受青睐。



## 命名冲突

1. 外层名字会覆盖内层名字（但是两者的内存空间都保留），这提供了一种重载字段或方法的方式；
2. 如果相同的名字在同一级别出现了两次，如果这个名字被程序使用了，将会引发一个错误（不使用没关系）。没有办法来解决这种问题引起的二义性，必须由程序员自己修正。



说一下规则一：

```go
package main

import "fmt"

type A struct {
   a int
   B
}
type B struct {
   a string
}

func main() {
   ego := A{1,B{"a"}}
   fmt.Println(ego.a)
   fmt.Println(ego.B.a)
}
```

字段`a`同名了，但不在同一层，所以外层的`a int`会覆盖内层的`a string`，所以访问`ego.a`得到的是外层的。但是访问`ego.B.a`就可以得到内层的`a`了。





# 方法

### 什么是方法

考虑到go中并没有类的概念，所以还需要进行一定的联想。

结构体可以算是类的一种简化，而方法就是在`receiver`上的函数。`receiver`可以是几乎任何类型，但不能是接口类型。**它不能是一个指针类型，但它可以是任何其他允许类型的指针。**

在 Go 中，类型的代码和绑定在它上面的方法的代码可以不放置在一起，它们可以存在在不同的源文件，唯一的要求是：**它们必须是同一个包的。**

别名类型不能有它原始类型上已经定义过的方法。

方法的格式如下：

```go
func (recv receiver_type) methodName(parameter_list) (return_value_list) { ... }
```

和函数比起来，就是在`func`和`methodName`中间加上了`(recv receiver_type)`，指明了接收者和接收者的类型。

一个例子：

```go
package main

import "fmt"

type Person struct {
	name string
	age  int
}
type Employee struct {
	salary float64
	Person
}

func (e *Employee) giveRaise(percent float64) {
	e.salary += e.salary * percent
}
func main() {
	e := new(Employee)
	e.name = "ego"
	e.age = 18
	e.salary = 10000
	e.giveRaise(0.1)
	fmt.Println(e)
}
```



关于接收者的类型是不是指针这里，会发现，如果是`e Employee`，`e.giveRaise()`同样可以调用，是因为编译器为我们进行了隐式转换。反之同理。但是对于接口类型会出问题，之后再说了。

涉及到的文章：https://zhuanlan.zhihu.com/p/76384820

**指针方法和值方法都可以在指针或非指针上被调用**



**方法没有和数据定义（结构体）混在一起：它们是正交的类型；表示（数据）和行为（方法）是独立的。**



## 方法和未导出字段

类似于`Java`中的`private`字段，想要访问和修改需要使用`getter` 和 `setter` 方法。Go语言中`getter`方法只是用成员名，`setter`方法使用`Set`前缀：

```go
package main

import "fmt"

type Person struct {
	name string
	age  int
}
type Employee struct {
	salary float64
	Person
}

func (person *Person) Name() string {
	return person.name
}

func (person *Person) SetName(name string) {
	person.name = name
}
func (person *Person) Age()  int{
	return person.age
}
func (person *Person) SetAge(age int)  {
	person.age = age
}
func (e *Employee) Salary() float64{
	return e.salary
}
func (e *Employee) SetSalary(salary float64) {
	e.salary = salary
}

func (e *Employee) giveRaise(percent float64) {
	e.salary += e.salary * percent
}
func main() {
	e := new(Employee)
	e.name = "ego"
	e.age = 18
	e.salary = 10000
	e.SetSalary(999)
	fmt.Println(e)
}

```





**对象的字段（属性）不应该由 2 个或 2 个以上的不同线程在同一时间去改变**



## 内嵌类型的方法和继承

当一个匿名类型被内嵌在结构体中时，匿名类型的可见方法也同样被内嵌，这在效果上等同于外层类型 **继承** 了这些方法：**将父类型放在子类型中来实现亚型**。

```go
package main

import "fmt"

type Person struct {
	name string
	age  int
}
type Employee struct {
	salary float64
	Person
}
func (person *Person) SayHello() {
	fmt.Println("hello,"+person.name)
}

func main() {
	e := new(Employee)
	e.name = "ego"
	e.age = 18
	e.salary = 10000
	e.SayHello()
}
```



和内嵌类型方法具有同样名字的外层类型的方法会覆写内嵌类型对应的方法：

```go
package main

import "fmt"

type Person struct {
	name string
	age  int
}
type Employee struct {
	salary float64
	Person
}
func (person *Person) SayHello() {
	fmt.Println("hello,"+person.name)
}
func (e *Employee) SayHello() {
	fmt.Println("hello,hello,"+e.name)
}

func main() {
	e := new(Employee)
	e.name = "ego"
	e.age = 18
	e.salary = 10000
	e.SayHello()
}
```

结构体内嵌和自己在同一个包中的结构体时，可以彼此访问对方所有的字段和方法。



在 Go 中，类型就是类（数据和关联的方法）。Go 拥有类似面向对象语言的类继承的概念。继承有两个好处：代码复用和多态。

在 Go 中，代码复用通过组合和委托实现，多态通过接口的使用来实现：有时这也叫 组件编程（Component Programming）。





类型的`String()`方法其实就类似于`Java`中类的``toString()`方法了。



# 垃圾回收和SetFinalizer

Go 开发者不需要写代码来释放程序中不再使用的变量和结构占用的内存，在 Go 运行时中有一个独立的进程，即垃圾收集器（GC），会处理这些事情，它搜索不再使用的变量然后释放它们的内存。可以通过 runtime 包访问 GC 进程。

通过调用 `runtime.GC()` 函数可以显式的触发 GC。



如果需要在一个对象 obj 被从内存移除前执行一些特殊操作，比如写到日志文件中，可以通过如下方式调用函数来实现：

```go
runtime.SetFinalizer(obj, func(obj *typeObj))
```

