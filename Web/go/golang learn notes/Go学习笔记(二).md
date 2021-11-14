# 前言

第一篇学习了基本结构和基本数据类型，这一篇学习一下控制结构。



# 控制结构



## if-else

```go
if condition1 {
    // do something 
} else if condition2 {
    // do something else    
} else {
    // catch-all or default
}
```

即使当代码块之间只有一条语句时，大括号也不可被省略。

**关键字 if 和 else 之后的左大括号 { 必须和关键字在同一行，如果你使用了 else-if 结构，则前段代码块的右大括号 } 必须和 else-if 关键字在同一行。这两条规则都是被编译器强制规定的**

当条件比较复杂时，则可以使用括号让代码更易读。（但都是可以省略的）



在函数中的一种比较规范的写法：

```go
func ifTest(num1 int) bool{
	if num1 > 10 {
		return true
	} 
	return false
}
```

而不是这样：

```go
func ifTest(num1 int) bool{
	if num1 > 10 {
		return true
	} else{
		return false
	}
}
```



> 不要同时在 if-else 结构的两个分支里都使用 return 语句，这将导致编译报错 。
>
> 但是，**该问题已经在 Go 1.1 中被修复或者说改进**



`if`还有这么一种写法，可以包含一个初始化语句：

```go
if initialization; condition {
    // do something
}
```

```go
	if n := 10; n < 20 {
		fmt.Println("eg0")
	}
```

使用简短方式 := 声明的变量的作用域只存在于 if 结构中（在 if 结构的大括号之间，如果使用 if-else 结构则在 else 代码块中变量也会存在）。如果变量在 if 结构之前就已经存在，那么在 if 结构中，该变量原来的值会被隐藏。最简单的解决方案就是不要在初始化语句中声明变量。





## 多返回值函数的错误

```go
value, err := pack1.Function1(param1)
if err != nil {
    fmt.Printf("An error occured in pack1.Function1 with parameter %v", param1)
    return err
}
// 未发生错误，继续执行：
```

还可以将错误的获取放在if语句的初始化中。



## switch

### 第一种形式

```go
switch var1 {
    case val1:
        ...
    case val2:
        ...
    default:
        ...
}
```

变量 var1 可以是任何类型，而 val1 和 val2 则可以是同类型的任意值。类型不被局限于常量或整数，但必须是相同的类型；或者最终结果为相同类型的表达式。前花括号 { 必须和 switch 关键字在同一行。

**一旦成功地匹配到某个分支，在执行完相应代码后就会退出整个 switch 代码块，也就是说您不需要特别使用 `break` 语句来表示结束。**



如果一个分支执行结束后还希望继续执行后续分支的代码，可以用`fallthrough`：

```go
	switch "1" {
	case "1":
		fmt.Println(1)
		fallthrough
	case "2":
		fmt.Println(2)
	case "3":
		fmt.Println(3)
	default:
		fmt.Println("no")
	}
//会打印出
//1
//2

```



### 第二种形式

```go
switch {
    case condition1:
        ...
    case condition2:
        ...
    default:
        ...
}
```

### 第三种形式

```go
switch initialization {
    case val1:
        ...
    case val2:
        ...
    default:
        ...
}
```



## for

### 第一种形式

```go
for 初始化语句; 条件语句; 修饰语句 {}
```

### 第二种形式

```go
for 条件语句 {}
```

**类似其他语言的`while`**。`go`没有`while`。

```go
	var count int = 0
	for count <= 10 {
		fmt.Println(count)
		count++
	}
```



如果 for 循环的头部没有条件语句，那么就会认为条件永远为 true，因此循环体内必须有相关的条件判断以确保会在某个时刻退出循环。

比如`for true{}`可以被简写为`for {}`

想要直接退出循环体，可以使用 break 语句。

### for-range结构

类似于其他语言的`foreach`：`for ix, val := range coll { }`，但仍然可以获得每次迭代所对应的索引。

**`val` 始终为集合中对应索引的值拷贝，因此它一般只具有只读性质，对它所做的任何修改都不会影响到集合中原有的值（如果 `val` 为指针，则会产生指针的拷贝，依旧可以修改集合中的原值）。**

```go
	var str string = "hello,ego"
	for pos, char := range str {
		fmt.Printf("%d : %c \n",pos,char)
	}
```

如果只想获得索引，可以忽略第二个变量：

```go
	arr := [4]int{1,2,3,4}
	for i := range arr {
		fmt.Println(i)
	}
```





一个字符串是 Unicode 编码的字符（或称之为 `rune`）集合，因此您也可以用它迭代字符串。每个 rune 字符和索引在 for-range 循环中是一一对应的。它能够自动根据 UTF-8 规则识别 Unicode 编码的字符：

```go
	var str string = "hello,怡果"
	for pos, char := range str {
		fmt.Printf("%d : %c \n",pos,char)
	}

/*
0 : h 
1 : e 
2 : l 
3 : l 
4 : o 
5 : , 
6 : 怡 
9 : 果 
*/
```

