# 前言

开始学习一下Go，虽然最近一直在看Java安全。本来打算是以Java作为主语言，想了一下还是以Go作为主语言，因此先把Go给学一下，以后的各种写代码的事情都拿Go来完成，以此来不断地学习Go。

Go的学习大概会持续1-2周叭，因为中间还有其他的事情要做。之后Go的学习也会带着，再去继续学习一下Java安全。之后大概会补一下XSS，然后可能会先开始由PHP往底层慢慢学习，以及学习各种安全方面的问题。（好吧从写这个的时候到现在已经过了2个月了，我的Go还停留在刚学不到一周的水平上，我真是菜！）

大概等10月之后再慢慢回归安全的学习了，有一些事情要去慢慢解决。

单纯的自己能看得懂的学习笔记，只记录自己觉得重要的或者第一次见的东西，自己看得懂就行。



（好吧上面这段话是9月初的时候写的，到现在已经过了2个月了，我的Go还停留在刚学不到一周的水平上，当时学了不到一周就因为某些事情，咕掉了。这个月末考操作系统，操作系统看累了也不太想去看Java了，也不太想去做做题了，因为最近的比赛实在太多，所以就来稍微看看Go。等这学期结束，寒假花2周时间把数据结构重学一遍然后敲敲代码，然后就开始拿Go语言刷力扣来提高代码能力。然后应该还会去看看编译原理，至于Java.....给我爬...。现在基本已经算半退役了现在，等到下学期基本就算完全退役了，大三要忙学业了。如果大三努力这一年能成功保研的话，大四的一年还有未来都会舒服很多。所以，冲啊！！！）

# 基本结构和基本数据类型



## 文件名、关键字与标识符

Go 的源文件以 .go 为后缀名存储在计算机中，这些文件名均由小写字母组成，如 scanner.go 。如果文件名由多个部分组成，则使用下划线 _ 对它们进行分隔，如 scanner_test.go 。文件名不包含空格或其他特殊字符。

Go 语言也是区分大小写的。

有效的标识符必须以字符（可以使用任何 UTF-8 编码的字符或 `_`）开头，然后紧跟着 0 个或多个字符或 Unicode 数字。

_ 本身就是一个特殊的标识符，被称为空白标识符。它可以像其他标识符那样用于变量的声明或赋值（任何类型都可以赋值给它），但任何赋给这个标识符的值都将被抛弃，因此这些值不能在后续的代码中使用，也不可以使用这个标识符作为变量对其它变量进行赋值或运算。

**程序的代码通过语句来实现结构化。每个语句不需要像 C 家族中的其它语言一样以分号 `;` 结尾，因为这些工作都将由 Go 编译器自动完成。**



## 包

每个 Go 文件都属于且仅属于一个包。一个包可以由许多以 `.go` 为扩展名的源文件组成，因此文件名和包名一般来说都是不相同的。

所有的包名都应该使用小写字母。

在 Go 的安装文件里包含了一些可以直接使用的包，即标准库。在 Windows 下，标准库的位置在 Go 根目录下的子目录 `pkg\windows_386` 中。

**如果对一个包进行更改或重新编译，所有引用了这个包的客户端程序都必须全部重新编译。**

一个 Go 程序是通过 `import` 关键字将一组包链接在一起。

导入多个包的更短且更优雅的方法：

```go
import (
	"fmt"
	"os"
)
```



如果包名不是以 . 或 / 开头，如 "fmt" 或者 "container/list"，则 Go 会在全局文件进行查找；如果包名以 ./ 开头，则 Go 会在相对目录中查找；如果包名以 / 开头（在 Windows 下也可以这样使用），则会在系统的绝对路径中查找。



## 可见性规则

当标识符（包括常量、变量、类型、函数名、结构字段等等）以一个大写字母开头，如：Group1，那么使用这种形式的标识符的对象就可以被外部包的代码所使用（客户端程序需要先导入这个包），这被称为导出（像面向对象语言中的 public）；标识符如果以小写字母开头，则对包外是不可见的，但是他们在整个包的内部是可见并且可用的（像面向对象语言中的 private ）。

（大写字母可以使用任何 Unicode 编码的字符，比如希腊文，不仅仅是 ASCII 码中的大写字母）。

因此，在导入一个外部包后，能够且只能够访问该包中导出的对象。



你可以通过使用包的别名来解决包名之间的名称冲突，或者说根据你的个人喜好对包名进行重新设置，如：`import fm "fmt"`。



```go
package main

import fm "fmt"
func main()  {
	fm.Println("hello,world")
}

```

**如果你导入了一个包却没有使用它，则会在构建程序时引发错误，如 `imported and not used: os`，这正是遵循了 Go 的格言：“没有不必要的代码！“。**



## 函数

在程序开始执行并完成初始化后，第一个调用（程序的入口点）的函数是 `main.main()`（如：C 语言），该函数一旦返回就表示程序已成功执行并立即退出。main 函数既没有参数，也没有返回类型。

**左大括号 `{` 必须与方法的声明放在同一行，这是编译器的强制规定，否则你在使用 gofmt 时就会出现错误提示**（注意注意，养成习惯）

因此符合规范的函数一般写成如下的形式：

```go
func functionName(parameter_list) (return_value_list) {
   …
}
```

其中：

`parameter_list` 的形式为` (param1 type1, param2 type2, …)`
`return_value_list` 的形式为 `(ret1 type1, ret2 type2, …)`
只有当某个函数需要被外部包调用的时候才使用大写字母开头，并遵循 Pascal 命名法；否则就遵循骆驼命名法，即第一个单词的首字母小写，其余单词的首字母大写。



程序正常退出的代码为 0 即 `Program exited with code 0`；如果程序因为异常而被终止，则会返回非零值，如：1。这个数值可以用来测试是否成功执行一个程序。



一个函数可以拥有多返回值，返回类型之间需要使用逗号分割，并使用小括号 `()` 将它们括起：

```go
package main

import "fmt"

func main() {
	var i1 int
	var i2 int
	i1, i2 = test()
	fmt.Println(i1)
	fmt.Println(i2)
}
func test() (i1 int, i2 int){
	return 1,2
}
```



## 注释

单行注释：`//`

多行注释：`/**/`



## 类型

类型可以是基本类型，如：int、float、bool、string；结构化的（复合的），如：struct、array、slice、map、channel；只描述类型的行为的，如：interface。



结构化的类型没有真正的值，它使用 nil 作为默认值（在 Objective-C 中是 nil，在 Java 中是 null，在 C 和 C++ 中是 NULL 或 0）。值得注意的是，Go 语言中不存在类型继承。







使用 type 关键字可以定义你自己的类型，你可能想要定义一个结构体 (第 10 章)，但是也可以定义一个已经存在的类型的别名，如：

```go
type IZ int
```

**这里并不是真正意义上的别名，因为使用这种方法定义之后的类型可以拥有更多的特性，且在类型转换时必须显式转换。**

然后：

```go
var a IZ = 5
```



（这里感觉有点像typedef？不知道，后面慢慢学到了再看了）





## Go程序的一般结构

- 在完成包的 import 之后，开始对常量、变量和类型的定义或声明。
- 如果存在 init 函数的话，则对该函数进行定义（这是一个特殊的函数，每个含有该函数的包都会首先执行这个函数）。
- 如果当前包是 main 包，则定义 main 函数。
- 然后定义其余的函数，首先是类型的方法，接着是按照 main 函数中先后调用的顺序来定义相关函数，如果有很多函数，则可以按照字母顺序来进行排序。



Go 程序的执行（程序启动）顺序如下：

1. 按顺序导入所有被 main 包引用的其它包，然后在每个包中执行如下流程：
2. 如果该包又导入了其它的包，则从第一步开始递归执行，但是每个包只会被导入一次。
3. 然后以相反的顺序在每个包中初始化常量和变量，如果该包含有 init 函数的话，则调用该函数。
4. 在完成这一切之后，main 也执行同样的过程，最后调用 main 函数开始执行程序。



## 类型转换

由于 Go 语言不存在隐式类型转换，因此所有的转换都必须显式说明，就像调用一个函数一样（类型在这里的作用可以看作是一种函数）。

```go
valueOfTypeB = typeB(valueOfTypeA)
```

注意到和`Java`的区别，`Java`是`valueOfTypeB = (typeB)valueOfTypeA`



## 常量

常量使用关键字 `const` 定义，用于存储不会改变的数据。

存储在常量中的数据类型只可以是布尔型、数字型（整数型、浮点型和复数）和字符串型。

常量的定义格式：`const identifier [type] = value`

在 Go 语言中，你可以省略类型说明符 `[type]`，因为编译器可以根据常量的值来推断其类型：

- 显式：`const PI float64 = 3.141502653`

- 隐式：`const P = 3.14`



常量的值必须是能够在编译时就能够确定的；你可以在其赋值表达式中涉及计算过程，但是所有用于计算的值必须在编译期间就能获得。例如下面的就是不对的：

```go
const c2 = getNumber()
```

**因为在编译期间自定义函数均属于未知，因此无法用于常量的赋值，但内置函数可以使用，如：len ()。**



反斜杠 `\` 可以在常量表达式中作为多行的连接符使用。



`iota`可以被用作枚举值：

```go
package main

import "fmt"

const (
	a = iota
	b = iota
	c = iota
)
func main(){
	fmt.Println(c)
}
```



第一个 `iota` 等于 0，每当 `iota` 在新的一行被使用时，它的值都会自动加 1；所以 `a=0, b=1, c=2` 可以简写为如下形式：

```go
const (
   a = iota
   b
   c
)
```

> iota是`golang`语言的常量计数器,只能在常量的表达式中使用。
>
> iota在`const`关键字出现时将被重置为0(`const`内部的第一行之前)，`const`中每新增一行常量声明将使iota计数一次(iota可理解为`const`语句块中的行索引)。





## 变量

声明变量的一般形式是使用 `var` 关键字：`var identifier type`。

```go
	var a int
	var b float64
	var c string
```

也可以：

```go
	var (
		a int
		b float64
		c string
	)
```

这种因式分解关键字的写法一般用于声明**全局变量**。



当一个变量被声明之后，系统自动赋予它该类型的零值：int 为 0，float 为 0.0，bool 为 false，string 为空字符串，指针为 nil。记住，所有的内存在 Go 中都是经过初始化的。



如果一个变量在函数体外声明，则被认为是全局变量，可以在整个包甚至外部包（被导出后）使用，不管你声明在哪个源文件里或在哪个源文件里调用该变量。

在函数体内声明的变量称之为局部变量，它们的作用域只在函数体内，参数和返回值变量也是局部变量。



**尽管变量的标识符必须是唯一的，但你可以在某个代码块的内层代码块中使用相同名称的变量，则此时外部的同名变量将会暂时隐藏（结束内部代码块的执行后隐藏的外部同名变量又会出现，而内部同名变量则被释放），你任何的操作都只会影响内部代码块的局部变量。**（联想一下C语言）



 Go 编译器的智商已经高到可以根据变量的值来自动推断其类型，而且 Go 是在编译时就已经完成推断过程。

```go
var a = 15
var b = false
var str = "Go says hello to the world!"
```



当你在函数体内声明局部变量时，应使用简短声明语法 `:=`。

`=` 是赋值， := 是声明变量并赋值。

比如：

```go
	var a int
	a = 1
	var b int =1
	c := 1
	fmt.Println(a,b,c)
```

这是使用变量的首选形式，但是它只能被用在函数体内，而不可以用于全局变量的声明与赋值。使用操作符 `:=` 可以高效地创建一个新的变量，称之为初始化声明。

如果在相同的代码块中，我们不可以再次对于相同名称的变量使用初始化声明。但是赋予新值是可以的。

全局变量是允许声明但不使用，局部变量是声明后必须被使用。



同一类型的多个变量可以声明在同一行：

```go
var a, b, c int
```



并行赋值：

```go
	a, b, c := 1, 2.5, "abc"
```



交换两个变量的值：

```go
	a, b = b, a
```



### 值类型和引用类型

像 int、float、bool 和 string 这些基本类型都属于值类型，使用这些类型的变量直接指向存在内存中的值。像数组和结构这些复合类型也是值类型。

当使用等号 `=` 将一个变量的值赋值给另一个变量时，如：`j = i`，实际上是在内存中将 i 的值进行了拷贝。

可以通过 &i 来获取变量 i 的内存地址。值类型的变量的值存储在栈中。



一个引用类型的变量 r1 存储的是 r1 的值所在的内存地址（数字），或内存地址中第一个字所在的位置。这个内存地址被称之为指针。

在 Go 语言中，指针属于引用类型，其它的引用类型还包括 slices，maps和 channel。被引用的变量会存储在堆中，以便进行垃圾回收，且比栈拥有更大的内存空间。

（之后学习的时候可以联想一下C语言的指针）



## init函数

变量除了可以在全局声明中初始化，也可以在 init 函数中初始化。这是一类非常特殊的函数，它不能够被人为调用，而是在每个包完成初始化后自动执行，并且执行优先级比 main 函数高。

每个源文件都只能包含一个 init 函数。初始化总是以单线程执行，并且按照包的依赖关系顺序执行。

一个可能的用途是在开始执行程序之前对数据进行检验或修复，以保证程序状态的正确性。





## 数字类型

Go 语言中没有 float 类型。（Go 语言中只有 float32 和 float64）没有 double 类型。

你应该尽可能地使用 float64，因为 `math` 包中所有有关数学运算的函数都会要求接收这个类型。

位运算只能用于整数类型的变量，且需当它们拥有等长位模式时。



相对于一般规则而言，Go 在进行字符串拼接时允许使用对运算符 `+` 的重载，但 Go 本身不允许开发者进行自定义的运算符重载。



对于整数和浮点数，你可以使用一元运算符 `++`（递增）和 `--`（递减），但**只能用于后缀**：

```go
i++
i--
```

**同时，带有 ++ 和 -- 的只能作为语句，而非表达式，因此 n = i++ 这种写法是无效的，其它像 f(i++) 或者 a[i]=b[i++] 这些可以用于 C、C++ 和 Java 中的写法在 Go 中也是不允许的。**



`rand` 包实现了伪随机数的生成：

```go
package main

import (
	"fmt"
	"math/rand"
	"time"
)

func main() {
	timens := int64(time.Now().Nanosecond())
	rand.Seed(timens)
	for i := 0; i < 10; i++ {
		fmt.Printf("%2.2f / ", 100*rand.Float32())
	}
}

```



函数 rand.Float32 和 rand.Float64 返回介于 [0.0, 1.0) 之间的伪随机数，其中包括 0.0 但不包括 1.0。函数 rand.Intn 返回介于 [0, n) 之间的伪随机数。

你可以使用 Seed(value) 函数来提供伪随机数的生成种子，一般情况下都会使用当前时间的纳秒级数字。



## 字符类型

`byte` 类型是 `uint8` 的别名：

```go
	var ch1 byte = 'A'
	var ch2 byte = '\x41'
	println(ch1)//65
	println(ch2)//65
```



Go 同样支持 Unicode（UTF-8），因此字符同样称为 Unicode 代码点或者 runes，并在内存中使用 int 来表示。在文档中，一般使用格式 U+hhhh 来表示，其中 h 表示一个 16 进制数。

**其实 rune 也是 Go 当中的一个类型，并且是 `int32` 的别名。代表一个 UTF-8 字符**

在书写 Unicode 字符时，需要在 16 进制数之前加上前缀 \u 或者 \U。

因为 Unicode 至少占用 2 个字节，所以我们使用 int16 或者 int 类型来表示。如果需要使用到 4 字节，则会加上 \U 前缀；前缀 \u 则总是紧跟着长度为 4 的 16 进制数，前缀 \U 紧跟着长度为 8 的 16 进制数。

包 unicode 包含了一些针对测试字符的非常有用的函数（其中 ch 代表字符）：

判断是否为字母：unicode.IsLetter(ch)
判断是否为数字：unicode.IsDigit(ch)
判断是否为空白符号：unicode.IsSpace(ch)



## 字符串

字符串是一种值类型，且值不可变，即创建某个文本后你无法再次修改这个文本的内容；更深入地讲，字符串是字节的定长数组。

- 解释字符串：`var s1 string = "hello,world\n"`
- 非解释字符串：`var s2 string = `hello,world\n``



和 C/C++ 不一样，Go 中的字符串是根据长度限定，而非特殊字符 `\0`。



一般的比较运算符（`==`、`!=`、`<`、`<=`、`>=`、`>`）通过在内存中按字节比较来实现字符串的对比。（和Java不同，go语言中`==`就可以实现字符串的比较）。

`len()` 来获取字符串所占的字节长度。

字符串的内容（纯字节）可以通过标准索引法来获取，在中括号 `[]` 内写入索引，索引从 0 开始计数





**注意事项** ：**获取字符串中某个字节的地址的行为是非法的，例如：`&str[i]`。**





## strings和strconv包

```go
strings.HasPrefix(s string, prefix string) bool
//判断s是否以prefix开头

strings.HasSuffix(s string, suffix string) bool
//判断s是否以suffix结尾

strings.Contains(s string, substr string) bool
//判断s中是否包含substr

strings.Index(s string, str string) int
//返回str在s中的第一个索引，-1表示s不包含str

strings.LastIndex(s string, str string) int
//返回str在s中的最后一个索引，-1表示s不包含str

strings.IndexRune(s string, r rune) int
//同上，只不过如果r是非ASCII编码的字符的话。

strings.Replace(str, old, new, n) string
//用于将字符串 str 中的前 n 个字符串 old 替换为字符串 new，并返回一个新的字符串，如果 n = -1 则替换所有字符串 old 为字符串 new

strings.Count(s, str string) int
//计算字符串 str 在字符串 s 中出现的非重叠次数。非重叠的意思是，比如s是egoegoegoego，str是egoego，则Count得到的返回值是2

strings.Repeat(s, count int) string
//重复 count 次字符串 s 并返回一个新的字符串

strings.ToLower(s) string
//将字符串中的 Unicode 字符全部转换为相应的小写字符

strings.ToUpper(s) string
//将字符串中的 Unicode 字符全部转换为相应的大写字符

strings.TrimSpace(s string) string
//去除字符串开头和结尾的空白符号

strings.Trim(s string, cutset string) string
//去除字符串开头和结尾的cutset字符串。

strings.TrimLeft(s string, cutset string) string
//去除字符串开头的cutset字符串

strings.TrimRight(s string, cutset string) string
//去除字符串结尾的cutset字符串

strings.Fields(s string) []string
//利用空白作为分隔符将字符串分割为若干块，并返回一个 slice 。如果字符串只包含空白符号，返回一个长度为 0 的 slice 

strings.Split(s string, sep string) []string
//利用sep作为分隔符将字符串分割为若按块，并返回一个slice

strings.Join(sl []string, sep string) string
//将元素类型为 string 的 slice 使用分割符号来拼接组成一个字符串
```



与字符串相关的类型转换都是通过 `strconv` 包实现的。

可以尝试使用`string()`，发现它并不是想想象的那样的转换，比如`string(97)`，它返回的并不是`"97"`，而是`"A"`。



`strconv.IntSize`用于获取程序运行的操作系统平台下 int 类型所占的位数。



任何类型 **T** 转换为字符串总是成功的。

将字符串转换为其它类型 **tp** 并不总是可能的。



针对从数字类型转换到字符串：

- `strconv.Itoa(i int) string` 返回数字 i 所表示的字符串类型的十进制数。

- `strconv.FormatFloat(f float64, fmt byte, prec int, bitSize int) string` 将 64 位浮点型的数字转换为字符串，其中` fmt` 表示格式（其值可以是 'b'、'e'、'f' 或 'g'），`prec` 表示精度，`bitSize` 则使用 32 表示 `float32`，用 64 表示 `float64`。

  

针对从字符串类型转换为数字类型：

- `strconv.Atoi(s string) (i int, err error)` 将字符串转换为 int 型。
- `strconv.ParseFloat(s string, bitSize int) (f float64, err error)` 将字符串转换为 float64 型。



## 时间和日期

`time`包提供了`time.Time`这么个数据类型：

```go
type Time struct {
	// wall and ext encode the wall time seconds, wall time nanoseconds,
	// and optional monotonic clock reading in nanoseconds.
	//
	// From high to low bit position, wall encodes a 1-bit flag (hasMonotonic),
	// a 33-bit seconds field, and a 30-bit wall time nanoseconds field.
	// The nanoseconds field is in the range [0, 999999999].
	// If the hasMonotonic bit is 0, then the 33-bit field must be zero
	// and the full signed 64-bit wall seconds since Jan 1 year 1 is stored in ext.
	// If the hasMonotonic bit is 1, then the 33-bit field holds a 33-bit
	// unsigned wall seconds since Jan 1 year 1885, and ext holds a
	// signed 64-bit monotonic clock reading, nanoseconds since process start.
	wall uint64
	ext  int64

	// loc specifies the Location that should be used to
	// determine the minute, hour, month, day, and year
	// that correspond to this Time.
	// The nil location means UTC.
	// All UTC times are represented with loc==nil, never loc==&utcLoc.
	loc *Location
}
```

当前时间可以用`time.Now()`获取。此外，`time`类型的变量还具有`Day()`、`Minute()`等方法。

Duration 类型表示两个连续时刻所相差的纳秒数，类型为 int64。Location 类型映射某个时区的时间，UTC 表示通用协调世界时间。

func (t Time) Format(layout string) string 可以根据一个格式化字符串来将一个时间 t 转换为相应格式的字符串，你可以使用一些预定义的格式，如：time.ANSIC 或 time.RFC822。



## 指针

Go 语言的取地址符是 `&`，放到一个变量前使用就会返回相应变量的内存地址。

在指针类型前面加上 `*` 号（前缀）来获取指针所指向的内容，这里的 `*` 号是一个类型更改器。使用一个指针引用一个值被称为间接引用：

```go
	var i int = 9
	var p *int = &i
	fmt.Println(*p)
```



通过对 *p 赋另一个值来更改 “对象”，这样 s 也会随之更改：

```go
	var s string = "feng"
	var p *string = &s
	*p = "ego,ego"
	fmt.Println(s)//ego,ego
	fmt.Println(p)//0xc000042230
	fmt.Println(*p)//ego,ego
```



**你不能得到一个文字或常量的地址**

对一个空指针的反向引用是不合法的，并且会使程序崩溃。



Go 语言为程序员提供了控制数据结构的指针的能力；但是，**你不能进行指针运算**

对于经常导致 C 语言内存泄漏继而程序崩溃的指针运算是不被允许的。Go 语言中的指针保证了内存安全，更像是 Java、C# 和 VB.NET 中的引用。

