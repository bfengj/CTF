# 前言

开始学习第十一章 接口与反射。

## 接口是什么

接口定义了一组方法（方法集），但是这些方法不包含（实现）代码：它们没有被实现（它们是抽象的）。接口里也不能包含变量。

```go
type Namer interface {
    Method1(param_list) return_type
    Method2(param_list) return_type
    ...
}
```



类型不需要显式声明它实现了某个接口：接口被隐式地实现。多个类型可以实现同一个接口。

实现某个接口的类型（除了实现接口方法外）可以有其他的方法。

一个类型可以实现多个接口。

接口类型可以包含一个实例的引用， 该实例的类型实现了此接口（接口是动态类型）。

```go
package main

import "fmt"

type Shaper interface {
	Area() float64
}

type Square struct {
	side float64
}

func (sq *Square) Area() float64{
	return sq.side*sq.side
}

func main(){
	sq1 := new(Square)
	sq1.side = 5
	var areaIntf Shaper
	areaIntf = sq1
	//areaIntf := Shaper(sq1)
	//areaIntf := sq1

	fmt.Println(areaIntf.Area())
	fmt.Println(sq1.Area())
}
```

```go
package main

import "fmt"

type Shaper interface {
	Area() float64
}

type Square struct {
	side float64
}

func (sq *Square) Area() float64{
	return sq.side*sq.side
}

type Rectangle struct{
	length float64
	width float64
}
func (r *Rectangle) Area() float64{
	return r.length*r.width
}

func main(){
	sq := &Square{5}
	r := &Rectangle{2,1}
	shapers := []Shaper{sq, r}
	for _,v := range shapers{
		fmt.Println(v)
		fmt.Println(v.Area())
	}

}
```

## 接口嵌套接口

```go
type ReadWrite interface {
    Read(b Buffer) bool
    Write(b Buffer) bool
}

type Lock interface {
    Lock()
    Unlock()
}

type File interface {
    ReadWrite
    Lock
    Close()
}
```

## 类型断言：如何检测和转换接口变量的类型

有一种方式来检测它的 **动态** 类型，即运行时在变量中存储的值的实际类型。

```go
if v, ok := varI.(T); ok {  // checked type assertion
    Process(v)
    return
}
// varI is not of type T
```

```go
package main

import "fmt"

type Simpler interface {
	Get() int
	Set(int)
}
type Simple struct {
	n int
}
func (s *Simple) Get() int{
	return s.n
}
func (s *Simple) Set(n int){
	s.n = n
}
func main() {
	var s Simpler = &Simple{555}
	if s,ok := s.(*Simple);ok{
		fmt.Println(s.Get())
	}
}

```

## 类型判断：type-switch

```go
func classifier(items ...interface{}) {
    for i, x := range items {
        switch x.(type) {
        case bool:
            fmt.Printf("Param #%d is a bool\n", i)
        case float64:
            fmt.Printf("Param #%d is a float64\n", i)
        case int, int64:
            fmt.Printf("Param #%d is a int\n", i)
        case nil:
            fmt.Printf("Param #%d is a nil\n", i)
        case string:
            fmt.Printf("Param #%d is a string\n", i)
        default:
            fmt.Printf("Param #%d is unknown\n", i)
        }
    }
}
```



练习：

```go
package main

import "fmt"

type Simpler interface {
	Get() int
	Put(int)
}

type Simple struct {
	i int
}
type RSimple struct {
	i int
}

func (p *Simple) Get() int {
	return p.i
}

func (p *Simple) Put(u int) {
	p.i = u
}

func (p *RSimple) Get() int {
	return p.i
}

func (p *RSimple) Put(u int) {
	p.i = u
}

func fI(it Simpler) {
	switch it.(type) {
	case *Simple:
		fmt.Println("Simple")
	case *RSimple:
		fmt.Println("RSimple")
	case nil:
		fmt.Println("nil")
	default:
		fmt.Println("unknown")
	}
}

func main() {
	var s *Simple
	fI(s)
}

```

## 测试一个值是否实现了某个接口

```go
type Stringer interface {
    String() string
}

if sv, ok := v.(Stringer); ok {
    fmt.Printf("v implements String(): %s\n", sv.String()) // note: sv, not v
}
```

## 使用方法集与接口

在接口上调用方法时，必须有和方法定义时相同的接收者类型或者是可以从具体类型 P 直接可以辨识的：

- 指针方法可以通过指针调用

- 值方法可以通过值调用
- 接收者是值的方法可以通过指针调用，因为指针会首先被解引用
- 接收者是指针的方法不可以通过值调用，因为存储在接口中的值没有地址



Go 语言规范定义了接口方法集的调用规则：

- 类型 *T 的可调用方法集包含接受者为 *T 或 T 的所有方法集
- 类型 T 的可调用方法集包含接受者为 T 的所有方法
- 类型 T 的可调用方法集不包含接受者为 *T 的方法



## 第一个例子：使用 Sorter 接口排序

```go
package main

import (
	"fmt"
	"learn1/src/sort"
)

func main() {
	p := []int{1,35,2,111,67,32,6546,99}
	a := sort.IntArray(p)
	sort.Sort(a)
	fmt.Println(a)
}
```

```go
package sort

type Sorter interface {
	Len() int
	Less(i,j int) bool
	Swap(i,j int)
}

func Sort(data Sorter){
	for j:=1;j<data.Len();j++{
		for i:=0;i<data.Len()-j;i++{
			if data.Less(i+1,i) {
				data.Swap(i,i+1)
			}
		}
	}
}
type IntArray []int

func (p IntArray) Len() int {
	return len(p)
}
func (p IntArray) Less(i,j int) bool {
	return p[i] < p[j]
}
func (p IntArray) Swap(i,j int) {
	p[i],p[j] = p[j],p[i]
}
```



## 空接口

**空接口或者最小接口** 不包含任何方法，它对实现不做任何要求

```go
type Any interface {}
```

任何其他类型都实现了空接口。

可以给一个空接口类型的变量 `var val interface {}` 赋任何类型的值。

每个 `interface {}` 变量在内存中占据两个字长：一个用来存储它包含的类型，另一个用来存储它包含的数据或者指向数据的指针。



既然空接口可以赋任何类型的值，就可以构造出包含不同类型的数组：

```go
package main

import "fmt"

type Element interface{}

type Vector struct{
	data []Element
}
func (v * Vector) Get(i int) Element{
	return v.data[i]
}
func (v *Vector) Set(i int, e Element) {
	v.data[i] = e
}

func main() {
	v := &Vector{[]Element{1,"hello",true}}
	fmt.Println(v)
}
```





把数据切片复制给空接口切片必须一个一个显示复制：

```go
var dataSlice []myType = FuncReturnSlice()
var interfaceSlice []interface{} = make([]interface{}, len(dataSlice))
for i, d := range dataSlice {
    interfaceSlice[i] = d
}
```



## 反射包

`reflect.TypeOf` 和 `reflect.ValueOf`，返回被检查对象的类型和值。



`reflect.Type` 和 `reflect.Value `都有一个`Kind()`方法返回一个常量来表示类型，而且它总是返回底层类型。



Value 有叫做 Int 和 Float 的方法可以获取存储在内部的值。

```go
	a := 12
	v :=reflect.ValueOf(a).Int()
	fmt.Println(reflect.TypeOf(v))//int64
	fmt.Println(v)//12
```





`reflect.Value`对象的`Interface()`方法得到还原（接口）值：

```go
package main

import (
	"fmt"
	"reflect"
)

func main() {
	a := 12
	v :=reflect.ValueOf(a)
	fmt.Println(v.Interface())//12
}
```





### 通过反射修改（设置）值



```go
package main

import (
   "fmt"
   "reflect"
)

func main() {
   a := 12
   v :=reflect.ValueOf(a)
   fmt.Println(v.CanSet())//false
   //v = v.Elem()
   //fmt.Println(v.CanSet())


   v = reflect.ValueOf(&a)
   fmt.Println(v.CanSet())//false
   v = v.Elem()
   fmt.Println(v.CanSet())//true
   v.SetInt(999)
   fmt.Println(a)//999
}
```



### 反射结构体

`NumField()` 方法返回结构体内的字段数量；通过一个 for 循环用索引取得每个字段的值 `Field(i)`（返回的也是Value对象）。

同样能够调用签名在结构体上的方法，例如，使用索引 n 来调用：`Method(n).Call(nil)`。



```go
package main

import (
	"fmt"
	"reflect"
)

type Person struct {
	Age int
	Name string
}
func (person *Person) SayHello(){
	fmt.Println("hello,"+person.Name)
}

func main() {
	person := Person{18,"feng"}
	value := reflect.ValueOf(person)
	typ := reflect.TypeOf(person)
	fmt.Println(value)//{18 feng}
	fmt.Println(typ)//main.Person
	fmt.Println(value.Kind())//struct

	for i:=0;i<value.NumField();i++{
		fmt.Println(value.Field(i))
		//18
		//feng
	}
	value = reflect.ValueOf(&person)
	fmt.Println(value.NumMethod())//1
	value.Method(0).Call(nil)//hello,feng
	fmt.Println(reflect.ValueOf(&person).Elem().CanSet())//true
	reflect.ValueOf(&person).Elem().Field(1).SetString("ego")
	value.Method(0).Call(nil)//hello,ego
}
```



结构体中只有被导出字段（首字母大写）才是可设置的。且Golang没有提供类似`Java`的`setAccessible()`。
