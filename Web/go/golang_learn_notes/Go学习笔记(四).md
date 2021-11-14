# 前言

上一篇学了第六章函数，这篇开始学习第七章**数组与切片**



# 数组与切片



## 声明和初始化

数组是具有相同 **唯一类型** 的一组以编号且长度固定的数据项序列（这是一种同构的数据结构）。

如果我们想让数组元素类型为任意类型的话可以使用空接口作为类型，当使用值时我们必须先做一个类型判断。

声明的格式：

```go
var identifier [len]type
```





使用`for-range`遍历数组：

```go
func main() {
	var arr [5]int
	for i := 0; i < 5; i++ {
		arr[i] = i
	}
	for _, i := range arr {
		fmt.Println(i)
	}
}
```



Go 语言中的数组是一种 **值类型**（不像 C/C++ 中是指向首元素的指针）。



> - `new` 的作用是根据传入的类型分配一片内存空间并返回指向这片内存空间的指针。

所以如果利用`new`：

```go
	arr1 := new([5]int) //*[5]int
	fmt.Println(arr1)//&[0 0 0 0 0]
```

当把它赋值给另一个的时候，需要再做一次数组内存的拷贝操作：

```go
	arr1 := new([5]int)
	arr2 := *arr1
	arr2[0] = 100
	fmt.Println(arr1)//&[0 0 0 0 0]
	fmt.Println(arr2)//[100 0 0 0 0]
```



### 数组常量

如果数组的值提前知道，就可以通过数组常量的方法初始化数组。

```go
	var arr1 = [5]int{1,2,3,4,5}
	var arr2 = [...]int{1,2,3,4,5}//...同样可以忽略，技术上说它们变化为了切片。
	var arr3 = [5]int{1: 1, 2: 2, 4: 4}//[0 1 2 0 4]
```





## 切片

切片（slice）是对数组一个连续片段的引用（该数组我们称之为相关数组，通常是匿名的），所以切片是一个引用类型（因此更类似于 C/C++ 中的数组类型，或者 Python 中的 list 类型）。这个片段可以是整个数组，或者是由起始和终止索引标识的一些项的子集。需要注意的是，终止索引标识的项不包括在切片内。切片提供了一个相关数组的动态窗口。



切片的长度可以在运行时修改，最小为 0 最大为相关数组的长度：切片是一个 **长度可变的数组**。



`cap()`可以计算切片可达的最大容量。它等于切片从第一个元素开始，到相关数组末尾的元素个数。

```go
0 <= len(s) <= cap(s)
```



声明切片的格式：`var identifier []type`（不需要说明长度）

初始化格式：`var slice1 []type = arr1[start:end]`



一个由1，2，3组成的切片：

```go
s := [3]int{1,2,3}[:]
s := []int{1,2,3}
```



切片在内存中的组织方式实际上是一个有 3 个域的结构体：指向相关数组的指针，切片长度以及切片容量。

```go
	var arr = [10]int{1,2,3,4,5,6,7,8,9,10}
	var s []int = arr[4:7]
	fmt.Println(s)//[5,6,7]
	fmt.Println(len(s))//3
	fmt.Println(cap(s))//6
```



切片只能向后移动。

**绝对不要用指针指向 slice。切片本身已经是一个引用类型，所以它本身就是一个指针！**



### 把切片传递给函数

```go
package main

import "fmt"

func main() {
	var arr = [5]int{1,2,3,4,5}
	test(arr[:])
}

func test(a []int) {
	fmt.Println(a)
}
```



### 用make()创建切片

```go
var slice []int = make([]int,10)
slice := make([]int,10)
slice := make([]type, len, cap)

func make(Type, size IntegerType) Type

内建函数make分配并初始化一个类型为切片、映射、或通道的对象。其第一个实参为类型，而非值。make的返回类型与其参数相同，而非指向它的指针。其具体结果取决于具体的类型：

切片：size指定了其长度。该切片的容量等于其长度。切片支持第二个整数实参可用来指定不同的容量；
     它必须不小于其长度，因此 make([]int, 0, 10) 会分配一个长度为0，容量为10的切片。
映射：初始分配的创建取决于size，但产生的映射长度为0。size可以省略，这种情况下就会分配一个
     小的起始大小。
通道：通道的缓存根据指定的缓存容量初始化。若 size为零或被省略，该信道即为无缓存的。
```







### new和make的区别

- `new (T) `为每个新的类型 T 分配一片内存，初始化为 0 并且返回类型为 * T 的内存地址：这种方法 返回一个指向类型为 T，值为 0 的地址的指针，它适用于值类型如数组和结构体；它相当于 &T{}。
- make(T) 返回一个类型为 T 的初始值，它只适用于 3 种内建的引用类型：切片、map 和 channel。

`new`函数分配内存，`make`函数初始化。



## 切片的复制和追加

内置函数`copy`和`append`：

```go
func copy(dst, src []Type) int
内建函数copy将元素从来源切片复制到目标切片中，也能将字节从字符串复制到字节切片中。copy返回被复制的元素数量，它会是 len(src) 和 len(dst) 中较小的那个。来源和目标的底层内存可以重叠。

func append(slice []Type, elems ...Type) []Type
内建函数append将元素追加到切片的末尾。若它有足够的容量，其目标就会重新切片以容纳新的元素。否则，就会分配一个新的基本数组。append返回更新后的切片，因此必须存储追加后的结果。
```





```go
	slice1 := []int{1,2,3}
	slice2 := make([]int,10)
	slice3 := append(slice1,1,2,3)
	slice4 := append(slice1,slice2...)
	fmt.Println(slice3)//[1 2 3 1 2 3]
	fmt.Println(slice4)//[1 2 3 0 0 0 0 0 0 0 0 0 0]
	copy(slice2,slice1)
	fmt.Println(slice2)//[1 2 3 0 0 0 0 0 0 0]
```

其中`slice4 := append(slice1,slice2...)`就是用到了传递变长参数的方式，上一章提到了：

> 如果参数被存储在一个 slice 类型的变量 `slice` 中，则可以通过 `slice...` 的形式来传递参数调用变参函数。







## 字符串、数组和切片

Go 语言中的字符串是不可变的。

想修改，必须先转换成字节数组，然后再修改：

```go
package main

import "fmt"

func main() {
	s := "eg0"
	c := []byte(s)
	c[2] = 'o'
	s = string(c)
	fmt.Println(s)
}
```



### 搜索及排序切片

标准库提供了 `sort` 包来实现常见的搜索和排序操作。

```go
func Ints(a []int) //对int类型的切片排序
func IntsAreSorted(a []int) bool //检查是否已经被排序
```





想要在数组或切片中搜索一个元素，该数组或切片必须先被排序。

搜索函数：

```go
func SearchInts(a []int, n int) int//在a中搜索n，并返回对应结果的索引值
func SearchFloat64s(a []float64, x float64) int
func SearchStrings(a []string, x string) int
```





### 切片和垃圾回收

切片的底层指向一个数组，该数组的实际容量可能要大于切片所定义的容量。只有在没有任何切片指向的时候，底层的数组内存才会被释放，这种特性有时会导致程序占用多余的内存。

想要避免这个问题，可以通过拷贝我们需要的部分到一个新的切片中。













