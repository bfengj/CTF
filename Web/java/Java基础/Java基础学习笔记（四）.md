# 异常



## 处理错误

异常处理的任务就是将控制权从产生错误的地方转移到能够处理这种情况的错误处理器。



在Java中，如果某个方法不能够采用正常的途径完成它的任务，可以通过另外一个路径退出方法。在这种情况下，方法并不返回任何值，而是抛出了一个封装了错误信息的对象。需要注意的是，这个方法将会立刻退出，并不返回正常值。此外，也不会从调用这个方法的代码继续执行，取而代之的是，异常处理机制开始搜索能够处理这种异常状况的异常处理器。



异常对象都是派生于`Throwable`类的一个类实例。如果Java中内置的异常类不能满足需求，用户还可以创建自己的异常类。

所有的异常都是有`Throwable`继承而来，但在下一层立即分解为两个分支：`Error`和`Exception`。

`Erroe`类层次结构描述了Java运行时系统的内部错误和资源耗尽错误。

`Exception`类层次结构又分解为两个分支：一个分支派生于`RuntimeException`；另一个分支包含其他异常。一般的规则是由编程错误导致的异常属于`RuntimeException`；如果程序本身没有问题，但由于像`I/O`错误这类问题导致的异常属于其他异常（`IOException`）。



Java语言规范将派生于`Error`类或`RuntimeException`类的所有异常称为非检查型异常，所有其他的异常称为检查型异常。



要在方法的首部指出这个方法可能抛出一个异常，所以要修改方法首部，以反映这个方法可能抛出的检查型异常。如果一个方法有可能抛出多个检查型异常类型，那么就必须在方法的首部列出所有的异常类。

1. 不需要声明Java的内部错误，即从Error继承的异常。
2. 也不应该声明从`RuntimeException`继承的那些非检查型异常。





> 如果在子类中覆盖了超类的一个方法，子类方法中声明的检查型异常不能比超类方法中声明的异常更通用（子类方法可以抛出更特定的异常，或者根本不抛出任何异常。）如果超类方法没有抛出任何检查型异常，子类也不能抛出任何检查型异常。



如果类中的一个方法声明它会抛出一个异常，而这个异常时某个特定类的实例，那么这个方法抛出的异常可能属于这个类，也可能属于这个类的一个子类。



```java
throw new EOFException
```



抛出异常非常容易：

1. 找到一个合适的异常类。
2. 创建这个类的一个对象。
3. 将对象抛出。

一旦方法抛出了异常，这个方法就不会返回到调用者。也就是说，不必操心建立一个默认的返回值或错误码。





### 创建异常类

我们需要做的只是定义一个派生于`Exception`的类，或者派生于`Exception`的某个子类，如`IOException`。习惯做法是，自定义的这个类应该包含两个构造器，一个是默认的构造器，另一个是包含详细描述信息的构造器（超类`Throwable`的`toString`方法会返回一个字符串，其中包含这个详细信息，这在调试中非常有用）。



## 捕获异常

要想捕获一个异常，需要设置`try/catch`语句块：

```java
        try
        {
            var in =new FileInputStream("2.txt");
        }
        catch(IOException exception)
        {
            exception.printStackTrace();
        }
```

如果try语句块中的任何代码抛出了catch子句中指定的一个异常类，那么

1. 程序将跳过try语句块的其余代码。
2. 程序将执行catch子句中的处理器代码。

如果`try`语句块中的代码没有抛出任何异常，那么程序将跳过`catch`子句。

如果方法中的任何代码抛出了`catch`子句中没有声明的一个异常类型，那么这个方法就会立即退出（希望它的调用者为这种类型的异常提供了`catch`子句）。



一般的经验是，要捕获那些你知道如何处理的异常，而继续传播那些你不知道怎样处理的异常。

 

### 捕获多个异常

在一个`try`语句块中可以捕获多个异常类型，并对不同类型的异常做出不同的处理，要为每个异常类型使用一个单独的`catch`子句。

```java
try
{
    ....
}
catch(FileNotFoundException e)
{
    ...
}
catch(IOException e)
{
    ...
}
```

异常对象可能包含有关异常性质的信息。要想获得这个对象的更多信息，可以尝试使用`e.getMessage()`得到详细的错误信息。



### `finally`子句

不管是否有异常被捕获，`finally`子句中的代码都会被执行：

```java
    public static void main(String[] args) throws FileNotFoundException,IOException
    {
        FileInputStream in = null;
        try
        {
            in =new FileInputStream("2.txt");
        }
        catch(IOException exception)
        {
            System.out.println(exception.getMessage());
        }
        finally
        {
            in.close();
        }
    }
```

其实这里的`finally`子句也有可能抛出异常。



> `finally`子句的体要用于清理资源。不要把改变控制流的语句（`return,throw,break,continue`）放在`finally`子句中。





### `try-with-Resources`语句

`try-with-Resources`语句（带资源的`try`语句）的最简形式为：

```java
try(Resource res = ...)
{
    work with res
}
```

`try`块退出时，会自动调用`res.close()`。



## 使用异常的技巧

1. 异常处理不能代替简单的测试。
2. 不要过分地细化异常。
3. 充分利用异常层次结构。
4. 不要压制异常。
5. 在检测错误时，“苛刻’要比放任更好。
6. 不要羞于传递异常。







