# 前言

慢慢学习PWN的过程中逐渐见到了GDB调试的各种用法，所以慢慢的积累起来。



# 用法

```shell
b main //在main函数上下断电
r      //运行
ni     //单步调试
fini   //跳出当前函数
s      //单步跟入
vmmap  //查看栈、bss段是否可以执行

print $esp:打印esp的值

x/10x $esp：打印出10个从esp开始的值

x/10x $esp-4：打印出10个从偏移4开始的值

x/10gx $esp：以64位格式打印

parseheap
h
bins

telescope 0xxxx 查看对象布局二进制信息
```



可以在python脚本中加入一行`pause()`，运行后会知道内存PID，然后进去gdb，再`attach xxx`就可以进入调试了。
