# 前言

上一篇简单学了一下控制结构，这一篇学习一下函数。



# 函数(function)

Go 是编译型语言，所以函数编写的顺序是无关紧要的。

函数参数、返回值以及它们的类型被统称为函数签名。



函数重载（function overloading）指的是可以编写多个同名函数，只要它们拥有不同的形参 / 或者不同的返回值，**在 Go 里面函数重载是不被允许的**。



如果需要声明一个在外部定义的函数，你只需要给出函数名与函数签名，不需要给出函数体：

```go
func flushICache(begin, end uintptr) // implemented externally
```



几乎在任何情况下，传递指针（一个 32 位或者 64 位的值）的消耗都比传递副本来得少。

**尽量使用命名返回值：会使代码更清晰、更简短，同时更加容易读懂。**



## 变长参数

如果函数的最后一个参数是采用 `...type` 的形式，那么这个函数就可以处理一个变长的参数，这个长度可以为 0，这样的函数称为变长函数。

```go
package main

import "fmt"

func main() {
	var result int
	result = add(1,2,3)
	fmt.Println(result)
}
func add(nums ... int) (res int){
	for _, n := range nums{
		res += n
	}
	return
}
```

如果参数被存储在一个 slice 类型的变量 `slice` 中，则可以通过 `slice...` 的形式来传递参数调用变参函数（关于什么是slice，下一篇会讲到。当然可以认为就是数组了）：

```go
package main

import "fmt"

func main() {
	var result int
	slice := []int{7,9,3,5,1}
	result = add(slice...)
	fmt.Println(result)
}
func add(nums ... int) (res int){
	for _, n := range nums{
		res += n
	}
	return
}
```

变长参数可以作为对应类型的 slice 进行二次传递。



如果变长参数的类型都不相同的话，可以使用**结构**或者**空接口**。

使用空接口的例子（空接口之后会学到）：

```go
package main

import "fmt"

func main() {
	test(1,1.2,"hello")
}
func test(values ...interface{}) {
	for _, value := range values {
		fmt.Println(value)
	}
}
```



## defer

`defer`允许在`return`之后执行语句。可能类似于`Java`中的`finally`，一般用于释放某些已分配的资源。

```go
package main

import "fmt"

func main() {
	test()
}
func test() {
	fmt.Println("ego1")
	defer fmt.Println("end")
	fmt.Println("ego2")
}
/*
ego1
ego2
end
*/
```

当有多个`defer`时，是逆序执行，类似栈的后进先出：

```go
func test() {
	for i := 0; i <= 5; i++ {
		defer fmt.Println(i)
	}
}
/*
5
4
3
2
1
0
*/
```



## 递归函数

一个简单的斐波那契数列的例子：

```go
package main

import "fmt"

func main() {
	fmt.Println(fibonacci(4))
}

func fibonacci(n int) (res int) {
	if n == 0 {
		res = 0
	}else if n == 1 {
		res = 1
	}else {
		res = fibonacci(n-1) + fibonacci(n-2)
	}
	return
}
```



## 将函数作为参数

即**回调**

```go
package main

import "fmt"

func main() {
	test(hello)
}
func hello() {
	fmt.Println("hello,world!")
}
func test(f func()) {
	f()
}
```



书上举了一个`strings.indexFunc()`的例子：

```go
func IndexFunc(s string, f func(rune) bool) int
//IndexFunc returns the index into s of the first Unicode code point satisfying f(c), or -1 if none do.
```

比如这样：

```go
func main() {
	f := func(c rune) bool {     return unicode.Is(unicode.Han, c) }
	fmt.Println(strings.IndexFunc("world,你",f))//6
}
```

看一下底层的源码：

```go
func indexFunc(s string, f func(rune) bool, truth bool) int {
	for i, r := range s {
		if f(r) == truth {
			return i
		}
	}
	return -1
}
```

也就是遍历字符串，对于找到的第一个可以让回调函数返回`true`的就返回索引。



## 闭包(匿名函数)

没有名字的函数，不能独立存在但可以被赋予某个变量：

```go
func main() {
	f := func(n int) {
		fmt.Println(n)
	}
	f(5)
}
```

当然匿名函数也可以直接调用，在最后加上一对括号即可：

```go
	func(n int) {
		a := 5
		b := 10
		c := a + b + n
		fmt.Println(c)
	}(15)
```

匿名函数同样被称之为闭包（函数式语言的术语）：它们被允许调用定义在其它环境下的变量。闭包可使得某个函数捕捉到一些外部状态，例如：函数被创建时的状态。另一种表示方式为：一个闭包继承了函数所声明时的作用域。这种状态（作用域内的变量）都被共享到闭包的环境中，因此这些变量可以在闭包中被操作，直到被销毁。



例1：

```go
package main

func main() {
	f := create(10)
	println(f(5))//15
}
func create(n int) func(b int) int {
	return func(b int) int {
		return n + b
	}
}
```



例2：

```go
package main

func main() {
	f := create(10)
	println(f(5))//15
	println(f(5))//20
	println(f(5))//25
}
func create(n int) func(b int) int {
	return func(b int) int {
		n += b
		return n
	}
}
```



一个返回值为另一个函数的函数可以被称之为工厂函数。



`time.Now()`：返回当前本地时间。



当在进行大量的计算时，提升性能最直接有效的一种方式就是避免重复计算。通过在内存中缓存和重复利用相同计算的结果，称之为内存缓存。
