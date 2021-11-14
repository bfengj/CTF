# 前言

开始学习第八章`Map`。类似`python`中的`dict`，`Java`中的`Map`了。

# Map

## 声明、初始化和make

`map`是引用类型。声明：

```go
var map1 map[keytype]valuetype
```

map 是可以动态增长的。



key 可以是任意可以用 == 或者！= 操作符比较的类型，比如 string、int、float。所以切片和结构体不能作为 key（含有数组切片的结构体不能作为 key，只包含内建类型的 `struct` 是可以作为 key 的），但是指针和接口类型可以。如果要用结构体作为 key 可以提供 `Key()` 和 `Hash()` 方法，这样可以通过结构体的域计算出唯一的数字或者字符串的 key。



初始化：

```go
	var map1 map[int]string
	map1 = map[int]string{1:"ego",2:"feng",5:"egofeng"}
```

因为`map`是引用类型，所以可以像创建切片那样用`make`：

```go
map1 := make(map[int]string)
```

当然也可以加上初始容量，达到上限的时候再增加会自动+1。

**不要使用 new，永远用 make 来构造 map**



## 判断键值是否存在及删除元素

因为如果`map1[key]`并没有这个`key`的话，会返回值类型的空值，这样就无法判断到底是不存在，还是本身就是空值，可以这样：

```go
	if s, ok := map1[1]; ok {
		fmt.Println(s)
	}
```

通过返回一个bool值来判断。



删除元素：

```go
delete(map1, key1)
```

如果 key1 不存在，该操作不会产生错误。



遍历：

```go
	map1 := map[string]string{"c":"ego","b":"feng","a":"eeeegggggooooooo"}
	for key,value := range map1 {
		fmt.Println(key,value)
	}
//不是按照key的顺序排列的，也不是按照value的顺序排列的。
```



