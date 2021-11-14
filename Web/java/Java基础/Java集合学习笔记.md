# Java集合学习笔记



## 前言

开始学习Java的集合，简要的记录一下学习到的东西，仅供自己查阅和复习方便。学习自廖雪峰的Java教程。



## 集合简介

Java标准库自带的`java.util`包提供了集合类：`Collection`，它是除`Map`外所有其他集合类的根接口。Java的`java.util`包主要提供了以下三种类型的集合：

- `List`：一种有序列表的集合，例如，按索引排列的`Student`的`List`；
- `Set`：一种保证没有重复元素的集合，例如，所有无重复名称的`Student`的`Set`；
- `Map`：一种通过键值（key-value）查找的映射表集合，例如，根据`Student`的`name`查找对应`Student`的`Map`。

Java集合的设计有几个特点：一是实现了接口和实现类相分离，例如，有序表的接口是`List`，具体的实现类有`ArrayList`，`LinkedList`等，二是支持泛型，我们可以限制在一个集合中只能放入同一种数据类型的元素，例如：

```
List<String> list = new ArrayList<>(); // 只能放入String类型
```

最后，Java访问集合总是通过统一的方式——迭代器（Iterator）来实现，它最明显的好处在于无需知道集合内部元素是按什么方式存储的。



## List

我们考察`List<E>`接口，可以看到几个主要的接口方法：

- 在末尾添加一个元素：`boolean add(E e)`
- 在指定索引添加一个元素：`boolean add(int index, E e)`
- 删除指定索引的元素：`E remove(int index)`
- 删除某个元素：`boolean remove(Object e)`
- 获取指定索引的元素：`E get(int index)`
- 获取链表大小（包含元素的个数）：`int size()`
- 判断`List`是否包含某个指定元素。`boolean contains(Object o)`。
- 返回某个元素的索引，如果元素不存在，就返回`-1`。`int indexOf(Object o)`

通常情况下，我们总是优先使用`ArrayList`。



除了使用`ArrayList`和`LinkedList`，我们还可以通过`List`接口提供的`of()`方法，根据给定元素快速创建`List`：

```
List<Integer> list = List.of(1, 2, 5);
```

但是`List.of()`方法不接受`null`值，如果传入`null`，会抛出`NullPointerException`异常。



### 遍历List

```java
List<String> list = List.of("a","b","c","d");
```



有三种方式遍历`List`，第一种就是简单的使用`for`循环和`get`：

```java
        for(int i = 0; i < list.size(); i++){
            System.out.println(list.get(i));
        }
```

这种方法并不推荐。

我们应该坚持使用迭代器`Iterator`来访问`List`。`Iterator`本身也是一个对象，但它是由`List`的实例调用`iterator()`方法的时候创建的。`Iterator`对象知道如何遍历一个`List`，并且不同的`List`类型，返回的`Iterator`对象实现也是不同的，但总是具有最高的访问效率。

`Iterator`对象有两个方法：`boolean hasNext()`判断是否有下一个元素，`E next()`返回下一个元素。因此，使用`Iterator`遍历`List`代码如下：

```java
        for(Iterator<String> it = list.iterator();it.hasNext();){
            String s = it.next();
            System.out.println(s);
        }
```



由于`Iterator`遍历是如此常用，所以，Java的`for each`循环本身就可以帮我们使用`Iterator`遍历：

```java
        for (String s:list){
            System.out.println(s);
        }
```



实际上，只要实现了`Iterable`接口的集合类都可以直接用`for each`循环来遍历，Java编译器本身并不知道如何遍历集合对象，但它会自动把`for each`循环变成`Iterator`的调用，原因就在于`Iterable`接口定义了一个`Iterator<E> iterator()`方法，强迫集合类必须返回一个`Iterator`实例。

### List和Array的转换

将`List`转换成`Array`有三种方法：

```java
        Object[] array1 = list.toArray();
        System.out.println(Arrays.toString(array1));
        System.out.println("----------------------------");

        //第二种方式是给toArray(T[])传入一个类型相同的Array，List内部自动把元素复制到传入的Array中
        //如果传入的数组不够大，那么List内部会创建一个新的刚好够大的数组，填充后返回；如果传入的数组比List元素还要多，那么填充完元素后，剩下的数组元素一律填充null。
        //实际上，最常用的是传入一个“恰好”大小的数组：
        String[] array2 = list.toArray(new String[list.size()]);
        System.out.println(Arrays.toString(array2));
        System.out.println("----------------------------");

	    //最后一种更简洁的写法是通过List接口定义的T[] toArray(IntFunction<T[]> generator)方法：
        String[] array3 = list.toArray(String[]::new);
        System.out.println(Arrays.toString(array3));
        System.out.println("----------------------------");
```



将`Array`转换成`List`：

```java
        Integer[] array4 = {1,2,3};

        List<Integer> list1 = List.of(array4);
        for (Integer i : list1){
            System.out.println(i);
        }
        List<Integer> list2 = Arrays.asList(array4);
        System.out.println("----------------------------");
        for(Integer i: list2){
            System.out.println(i);
        }
```

两种方法，一种是`List.of()`。

对于JDK 11之前的版本，可以使用`Arrays.asList(T...)`方法把数组转换成`List`。



> 要注意的是，返回的`List`不一定就是`ArrayList`或者`LinkedList`，因为`List`只是一个接口，如果我们调用`List.of()`，它返回的是一个只读`List`。
>
> 对只读`List`调用`add()`、`remove()`方法会抛出`UnsupportedOperationException`。





## Map

`Map`这种键值（key-value）映射表的数据结构，作用就是能高效通过`key`快速查找`value`（元素）。



`Map<K, V>`是一种键-值映射表，当我们调用`put(K key, V value)`方法时，就把`key`和`value`做了映射并放入`Map`。当我们调用`V get(K key)`时，就可以通过`key`获取到对应的`value`。如果`key`不存在，则返回`null`。和`List`类似，`Map`也是一个接口，最常用的实现类是`HashMap`。

如果只是想查询某个`key`是否存在，可以调用`boolean containsKey(K key)`方法。

```java
        Employee e = new Employee("feng",1234);
        Map<String,Employee> map = new HashMap<>();
        map.put("feng",e);
        Employee target = map.get("feng");
        System.out.println(target == e);
        System.out.println(target.getName());
        Employee other = map.get("world");
        System.out.println(other);
```



**Map中不存在重复的key，因为放入相同的key，只会把原有的key-value对应的value给替换掉。**



### 遍历Map

有两种情况，第一种是遍历Key，使用`for each`循环遍历`Map`实例的`keySet()`方法返回的`Set`集合，它包含不重复的`key`的集合：

```java
        Map<String,Integer> map = new HashMap<>();
        map.put("feng",999);
        map.put("hello",111);
        map.put("world",1314);
        for(String key : map.keySet()){
            Integer value = map.get(key);
            System.out.println(key + " = " + value);
        }
```

第二种情况是同时遍历Key和Value，使用`for each`循环遍历`Map`对象的`entrySet()`集合，它包含每一个`key-value`映射：

```java
        Map<String,Integer> map = new HashMap<>();
        map.put("feng",999);
        map.put("hello",111);
        map.put("world",1314);
        for(Map.Entry<String,Integer> entry : map.entrySet()){
            String key = entry.getKey();
            Integer value = entry.getValue();
            System.out.println(key + " = " + value);
        }
```



`Map`和`List`不同的是，`Map`存储的是`key-value`的映射关系，并且，它*不保证顺序*。在遍历的时候，遍历的顺序既不一定是`put()`时放入的`key`的顺序，也不一定是`key`的排序顺序。使用`Map`时，任何依赖顺序的逻辑都是不可靠的。以`HashMap`为例，假设我们放入`"A"`，`"B"`，`"C"`这3个`key`，遍历的时候，每个`key`会保证被遍历一次且仅遍历一次，但顺序完全没有保证，甚至对于不同的JDK版本，相同的代码遍历的输出顺序都是不同的！

遍历Map时，不可假设输出的key是有序的！





### EnumMap

如果作为key的对象是`enum`类型，那么，还可以使用Java集合库提供的一种`EnumMap`，它在内部以一个非常紧凑的数组存储value，并且根据`enum`类型的key直接定位到内部数组的索引，并不需要计算`hashCode()`，不但效率最高，而且没有额外的空间浪费。

例子：

```java
import java.time.DayOfWeek;
import java.util.*;
public class Main {
    public static void main(String[] args) {
        Map<DayOfWeek, String> map = new EnumMap<>(DayOfWeek.class);
        map.put(DayOfWeek.MONDAY, "星期一");
        map.put(DayOfWeek.TUESDAY, "星期二");
        map.put(DayOfWeek.WEDNESDAY, "星期三");
        map.put(DayOfWeek.THURSDAY, "星期四");
        map.put(DayOfWeek.FRIDAY, "星期五");
        map.put(DayOfWeek.SATURDAY, "星期六");
        map.put(DayOfWeek.SUNDAY, "星期日");
        System.out.println(map);
        System.out.println(map.get(DayOfWeek.MONDAY));
    }
}
```



### TreeMap

我们已经知道，`HashMap`是一种以空间换时间的映射表，它的实现原理决定了内部的Key是无序的，即遍历`HashMap`的Key时，其顺序是不可预测的（但每个Key都会遍历一次且仅遍历一次）。

还有一种`Map`，它在内部会对Key进行排序，这种`Map`就是`SortedMap`。注意到`SortedMap`是接口，它的实现类是`TreeMap`。

`SortedMap`保证遍历时以Key的顺序来进行排序。

```java
        Map<String, Integer> map = new TreeMap<>();
        map.put("orange", 1);
        map.put("apple", 2);
        map.put("pear", 3);
        for (String key : map.keySet()) {
            System.out.println(key);
        }
        // apple, orange, pear
```



**使用`TreeMap`时，放入的Key必须实现`Comparable`接口。**

如果作为Key的class没有实现`Comparable`接口，那么，必须在创建`TreeMap`时同时指定一个自定义排序算法：

```java
        Map<Person, Integer> map = new TreeMap<>(new Comparator<Person>() {
            public int compare(Person p1, Person p2) {
                return p1.name.compareTo(p2.name);
            }
        });
        map.put(new Person("Tom"), 1);
        map.put(new Person("Bob"), 2);
        map.put(new Person("Lily"), 3);
        for (Person key : map.keySet()) {
            System.out.println(key);
        }
        // {Person: Bob}, {Person: Lily}, {Person: Tom}
        System.out.println(map.get(new Person("Bob"))); // 2
```



> `SortedMap`在遍历时严格按照Key的顺序遍历，最常用的实现类是`TreeMap`；
>
> 作为`SortedMap`的Key必须实现`Comparable`接口，或者传入`Comparator`；
>
> 要严格按照`compare()`规范实现比较逻辑，否则，`TreeMap`将不能正常工作。



### Properties

配置文件的特点是，它的Key-Value一般都是`String`-`String`类型的，因此我们完全可以用`Map<String, String>`来表示它。

#### 读取配置文件

因为配置文件非常常用，所以Java集合库提供了一个`Properties`来表示一组“配置”。由于历史遗留原因，`Properties`内部本质上是一个`Hashtable`，但我们只需要用到`Properties`自身关于读写配置的接口。



```java
String f = "setting.properties";
Properties props = new Properties();
props.load(new java.io.FileInputStream(f));

String filepath = props.getProperty("last_open_file");
String interval = props.getProperty("auto_save_interval", "120");
```

用`Properties`读取配置文件，一共有三步：

1. 创建`Properties`实例；
2. 调用`load()`读取文件；
3. 调用`getProperty()`获取配置。



`getProperty()`的第二个参数是默认值。因为如果key不存在将返回null，所以可以提供一个默认值。



如果有多个`.properties`文件，可以反复调用`load()`读取，后读取的key-value会覆盖已读取的key-value。



`Properties`设计的目的是存储`String`类型的key－value，但`Properties`实际上是从`Hashtable`派生的，它的设计实际上是有问题的，但是为了保持兼容性，现在已经没法修改了。除了`getProperty()`和`setProperty()`方法外，还有从`Hashtable`继承下来的`get()`和`put()`方法，这些方法的参数签名是`Object`，我们在使用`Properties`的时候，不要去调用这些从`Hashtable`继承下来的方法。

#### 写入配置文件

```java
        String f = "test.properties";
        Properties props = new Properties();
        props.setProperty("name","feng");
        props.store(new FileOutputStream("test.properties"),"这是写入的properties注释");
```

#### 

#### 编码

早期版本的Java规定`.properties`文件编码是ASCII编码（ISO8859-1），如果涉及到中文就必须用`name=\u4e2d\u6587`来表示，非常别扭。从JDK9开始，Java的`.properties`文件可以使用UTF-8编码了。

不过，需要注意的是，由于`load(InputStream)`默认总是以ASCII编码读取字节流，所以会导致读到乱码。我们需要用另一个重载方法`load(Reader)`读取：

```java
Properties props = new Properties();
props.load(new FileReader("settings.properties", StandardCharsets.UTF_8));
```

就可以正常读取中文。`InputStream`和`Reader`的区别是一个是字节流，一个是字符流。字符流在内存中已经以`char`类型表示了，不涉及编码问题。

#### 小结

Java集合库提供的`Properties`用于读写配置文件`.properties`。`.properties`文件可以使用UTF-8编码。

可以从文件系统、classpath或其他任何地方读取`.properties`文件。

读写`Properties`时，注意仅使用`getProperty()`和`setProperty()`方法，不要调用继承而来的`get()`和`put()`等方法。





## Set

`Set`用于存储不重复的元素集合，它主要提供以下几个方法：

- 将元素添加进`Set<E>`：`boolean add(E e)`
- 将元素从`Set<E>`删除：`boolean remove(Object e)`
- 判断是否包含元素：`boolean contains(Object e)`

例子：

```java
        Set<String> set = new HashSet<>();
        set.add("a");
        set.add("b");
        System.out.println(set.add("a"));
        System.out.println(set);
        System.out.println(set.contains("a"));
        System.out.println(set.size());
```



`Set`实际上相当于只存储key、不存储value的`Map`。我们经常用`Set`用于去除重复元素。

因为放入`Set`的元素和`Map`的key类似，都要正确实现`equals()`和`hashCode()`方法，否则该元素无法正确地放入`Set`。

最常用的`Set`实现类是`HashSet`，实际上，`HashSet`仅仅是对`HashMap`的一个简单封装。



`Set`接口并不保证有序，而`SortedSet`接口则保证元素是有序的：

- `HashSet`是无序的，因为它实现了`Set`接口，并没有实现`SortedSet`接口；
- `TreeSet`是有序的，因为它实现了`SortedSet`接口。



可以利用`Set`可以去除重复元素；



## Queue

队列（`Queue`）是一种经常使用的集合。`Queue`实际上是实现了一个先进先出（FIFO：First In First Out）的有序表。它和`List`的区别在于，`List`可以在任意位置添加和删除元素，而`Queue`只有两个操作：

- 把元素添加到队列末尾；
- 从队列头部取出元素。



在Java的标准库中，队列接口`Queue`定义了以下几个方法：

- `int size()`：获取队列长度；
- `boolean add(E)`/`boolean offer(E)`：添加元素到队尾；
- `E remove()`/`E poll()`：获取队首元素并从队列中删除；
- `E element()`/`E peek()`：获取队首元素但并不从队列中删除。



对于具体的实现类，有的Queue有最大队列长度限制，有的Queue没有。注意到添加、删除和获取队列元素总是有两个方法，这是因为在添加或获取元素失败时，这两个方法的行为是不同的。



|                    | throw Exception | 返回false或null    |
| :----------------- | :-------------- | ------------------ |
| 添加元素到队尾     | add(E e)        | boolean offer(E e) |
| 取队首元素并删除   | E remove()      | E poll()           |
| 取队首元素但不删除 | E element()     | E peek()           |



```java
        Queue<String> queue = new LinkedList<>();
        queue.add("a");
        queue.add("b");
        queue.add("c");
        System.out.println(queue.element());
```



`LinkedList`即实现了`List`接口，又实现了`Queue`接口，但是，在使用的时候，如果我们把它当作List，就获取List的引用，如果我们把它当作Queue，就获取Queue的引用：

```java
// 这是一个List:
List<String> list = new LinkedList<>();
// 这是一个Queue:
Queue<String> queue = new LinkedList<>();
```



要避免把`null`添加到队列。

#### PriorityQueue

优先队列。`PriorityQueue`和`Queue`的区别在于，它的出队顺序与元素的优先级有关，对`PriorityQueue`调用`remove()`或`poll()`方法，返回的总是优先级最高的元素。

要使用`PriorityQueue`，我们就必须给每个元素定义“优先级”。放入`PriorityQueue`的元素，必须实现`Comparable`接口，`PriorityQueue`会根据元素的排序顺序决定出队的优先级。

如果我们要放入的元素并没有实现`Comparable`接口怎么办？`PriorityQueue`允许我们提供一个`Comparator`对象来判断两个元素的顺序。

`PriorityQueue`实现了一个优先队列：从队首获取元素时，总是获取优先级最高的元素。

`PriorityQueue`默认按元素比较的顺序排序（必须实现`Comparable`接口），也可以通过`Comparator`自定义排序算法（元素就不必实现`Comparable`接口）。



### Deque

我们知道，`Queue`是队列，只能一头进，另一头出。

如果把条件放松一下，允许两头都进，两头都出，这种队列叫双端队列（Double Ended Queue），学名`Deque`。

Java集合提供了接口`Deque`来实现一个双端队列，它的功能是：

- 既可以添加到队尾，也可以添加到队首；
- 既可以从队首获取，又可以从队尾获取。



|                    | Queue                  | Deque                           |
| :----------------- | :--------------------- | ------------------------------- |
| 添加元素到队尾     | add(E e) / offer(E e)  | addLast(E e) / offerLast(E e)   |
| 取队首元素并删除   | E remove() / E poll()  | E removeFirst() / E pollFirst() |
| 取队首元素但不删除 | E element() / E peek() | E getFirst() / E peekFirst()    |
| 添加元素到队首     | 无                     | addFirst(E e) / offerFirst(E e) |
| 取队尾元素并删除   | 无                     | E removeLast() / E pollLast()   |
| 取队尾元素但不删除 | 无                     | E getLast() / E peekLast()      |



注意到`Deque`接口实际上扩展自`Queue`：

```java
public interface Deque<E> extends Queue<E> {
    ...
}
```

因此，`Queue`提供的`add()`/`offer()`方法在`Deque`中也可以使用，但是，使用`Deque`，最好不要调用`offer()`，而是调用`offerLast()`



`Deque`是一个接口，它的实现类有`ArrayDeque`和`LinkedList`。

我们发现`LinkedList`真是一个全能选手，它即是`List`，又是`Queue`，还是`Deque`。但是我们在使用的时候，总是用特定的接口来引用它，这是因为持有接口说明代码的抽象层次更高，而且接口本身定义的方法代表了特定的用途。

```
// 不推荐的写法:
LinkedList<String> d1 = new LinkedList<>();
d1.offerLast("z");
// 推荐的写法：
Deque<String> d2 = new LinkedList<>();
d2.offerLast("z");
```

**可见面向抽象编程的一个原则就是：尽量持有接口，而不是具体的实现类**。





`Deque`实现了一个双端队列（Double Ended Queue），它可以：

- 将元素添加到队尾或队首：`addLast()`/`offerLast()`/`addFirst()`/`offerFirst()`；
- 从队首／队尾获取元素并删除：`removeFirst()`/`pollFirst()`/`removeLast()`/`pollLast()`；
- 从队首／队尾获取元素但不删除：`getFirst()`/`peekFirst()`/`getLast()`/`peekLast()`；
- 总是调用`xxxFirst()`/`xxxLast()`以便与`Queue`的方法区分开；
- 避免把`null`添加到队列。



## Stack

栈（Stack）是一种后进先出（LIFO：Last In First Out）的数据结构。

`Stack`只有入栈和出栈的操作：

- 把元素压栈：`push(E)`；
- 把栈顶的元素“弹出”：`pop()`；
- 取栈顶元素但不弹出：`peek()`。



在Java中，我们用`Deque`可以实现`Stack`的功能：

- 把元素压栈：`push(E)`/`addFirst(E)`；
- 把栈顶的元素“弹出”：`pop()`/`removeFirst()`；
- 取栈顶元素但不弹出：`peek()`/`peekFirst()`。

为什么Java的集合类没有单独的`Stack`接口呢？因为有个遗留类名字就叫`Stack`，出于兼容性考虑，所以没办法创建`Stack`接口，只能用`Deque`接口来“模拟”一个`Stack`了。



## Collections

`Collections`是JDK提供的工具类，同样位于`java.util`包中。它提供了一系列静态方法，能更方便地操作各种集合。

> 注意Collections结尾多了一个s，不是Collection！

### 

### 创建空集合

`Collections`提供了一系列方法来创建空集合：

- 创建空List：`List<T> emptyList()`
- 创建空Map：`Map<K, V> emptyMap()`
- 创建空Set：`Set<T> emptySet()`

要注意到返回的空集合是不可变集合，无法向其中添加或删除元素。

此外，也可以用各个集合接口提供的`of(T...)`方法创建空集合。例如，以下创建空`List`的两个方法是等价的：

```java
List<String> list1 = List.of();
List<String> list2 = Collections.emptyList();
```



### 创建单元素集合

`Collections`提供了一系列方法来创建一个单元素集合：

- 创建一个元素的List：`List<T> singletonList(T o)`
- 创建一个元素的Map：`Map<K, V> singletonMap(K key, V value)`
- 创建一个元素的Set：`Set<T> singleton(T o)`

要注意到返回的单元素集合也是不可变集合，无法向其中添加或删除元素。



此外，也可以用各个集合接口提供的`of(T...)`方法创建单元素集合。例如，以下创建单元素`List`的两个方法是等价的：

```java
List<String> list1 = List.of("apple");
List<String> list2 = Collections.singletonList("apple");
```

实际上，使用`List.of(T...)`更方便，因为它既可以创建空集合，也可以创建单元素集合，还可以创建任意个元素的集合：

```java
List<String> list1 = List.of(); // empty list
List<String> list2 = List.of("apple"); // 1 element
List<String> list3 = List.of("apple", "pear"); // 2 elements
List<String> list4 = List.of("apple", "pear", "orange"); // 3 elements
```



### 排序

`Collections`可以对`List`进行排序。因为排序会直接修改`List`元素的位置，因此必须传入可变`List`。

`Collections.sort()`

### 洗牌

`Collections`提供了洗牌算法，即传入一个有序的`List`，可以随机打乱`List`内部元素的顺序，效果相当于让计算机洗牌

`Collections.shuffle()`

### 不可变集合

`Collections`还提供了一组方法把可变集合封装成不可变集合：

- 封装成不可变List：`List<T> unmodifiableList(List<? extends T> list)`
- 封装成不可变Set：`Set<T> unmodifiableSet(Set<? extends T> set)`
- 封装成不可变Map：`Map<K, V> unmodifiableMap(Map<? extends K, ? extends V> m)`

这种封装实际上是通过创建一个代理对象，拦截掉所有修改方法实现的。



然而，继续对原始的可变`List`进行增删是可以的，并且，会直接影响到封装后的“不可变”`List`。因此，如果我们希望把一个可变`List`封装成不可变`List`，那么，返回不可变`List`后，最好立刻扔掉可变`List`的引用，这样可以保证后续操作不会意外改变原始对象，从而造成“不可变”`List`变化了。













