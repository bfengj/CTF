# 目录

[TOC]



## linux换源

`vim /etc/apt/sources.list`

```
deb http://mirrors.aliyun.com/ubuntu/ xenial main
deb-src http://mirrors.aliyun.com/ubuntu/ xenial main
deb http://mirrors.aliyun.com/ubuntu/ xenial-updates main
deb-src http://mirrors.aliyun.com/ubuntu/ xenial-updates main
deb http://mirrors.aliyun.com/ubuntu/ xenial universe
deb-src http://mirrors.aliyun.com/ubuntu/ xenial universe
deb http://mirrors.aliyun.com/ubuntu/ xenial-updates universe
deb-src http://mirrors.aliyun.com/ubuntu/ xenial-updates universe
deb http://mirrors.aliyun.com/ubuntu/ xenial-security main
deb-src http://mirrors.aliyun.com/ubuntu/ xenial-security main
deb http://mirrors.aliyun.com/ubuntu/ xenial-security universe
deb-src http://mirrors.aliyun.com/ubuntu/ xenial-security universe
```

## python

ubuntu自带了。不过还需要额外安装些别的

```shell
apt-get install python3-pip libssl-dev libffi-dev build-essential

```

安装python3的pip：

```shell
feng@ubuntu:~$ pip --version
pip 20.3.4 from /home/feng/.local/lib/python3.5/site-packages/pip (python 3.5)

```

然后pip也需要换源：

```shell
mkdir ~/.pip/
vim ~/.pip/pip.conf


[global]
index-url = https://pypi.tuna.tsinghua.edu.cn/simple
```



## pwntools

```shell
pip install pwntools
```



## checksec和ROPgadget

安装完pwntools后会自带，把当前的终端关掉后再打开应该就添加进了环境变量

```shell
feng@ubuntu:~$ echo $PATH
/home/feng/bin:/home/feng/.local/bin:/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin:/usr/games:/usr/local/games:/snap/bin
```

`就在`/home/feng/.local/bin`下面。

```shell
feng@ubuntu:~/.local/bin$ ls
asm        disablenx  main    pwnstrip           ROPgadget         unhex
checksec   disasm     phd     __pycache__        rpyc_classic.py   update
common     elfdiff    pip     pygmentize         rpyc_registry.py  version
constgrep  elfpatch   pip3    pyserial-miniterm  scramble
cyclic     errno      pip3.5  pyserial-ports     shellcraft
debug      hex        pwn     readelf.py         template

```



## gdb

```shell
sudo apt install gdb
```

## pwndbg

```shell
git clone https://github.com/pwndbg/pwndbg
cd pwndbg
./setup.sh
```

安装完之后运行gdb会显示pwndbg：

```shell
feng@ubuntu:~/Desktop/GDB-Plugins/pwndbg$ gdb
GNU gdb (Ubuntu 7.11.1-0ubuntu1~16.5) 7.11.1
Copyright (C) 2016 Free Software Foundation, Inc.
License GPLv3+: GNU GPL version 3 or later <http://gnu.org/licenses/gpl.html>
This is free software: you are free to change and redistribute it.
There is NO WARRANTY, to the extent permitted by law.  Type "show copying"
and "show warranty" for details.
This GDB was configured as "x86_64-linux-gnu".
Type "show configuration" for configuration details.
For bug reporting instructions, please see:
<http://www.gnu.org/software/gdb/bugs/>.
Find the GDB manual and other documentation resources online at:
<http://www.gnu.org/software/gdb/documentation/>.
For help, type "help".
Type "apropos word" to search for commands related to "word".
pwndbg: loaded 191 commands. Type pwndbg [filter] for a list.
pwndbg: created $rebase, $ida gdb functions (can be used with print/break)
pwndbg> quit
feng@ubuntu:~/Desktop/GDB-Plugins/pwndbg$
```



## pwngdb

https://blog.csdn.net/weixin_43092232/article/details/105648769

## LibcSearcher

```shell
pip install LibcSearcher
```

https://github.com/dev2ero/LibcSearcher

这是全新的`LibcSearcher`的实现。

## one_gadget

我直接按照菜鸟的源码安装高版本的ruby。https://www.runoob.com/ruby/ruby-installation-unix.html。

然后再：

```shell
sudo apt install gem
sudo gem install one_gadget
```

因为我是ubuntu16，里面的自动安装的ruby版本太低，更新ruby的话下载速度太慢了，也懒得给pwn虚拟机弄梯子，就直接源码安装了。



## main_arena_offset

```
git clone  https://github.com/bash-c/main_arena_offset
```





## IDA Pro

自己找地方下一个吧。