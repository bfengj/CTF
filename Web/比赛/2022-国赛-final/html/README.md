> 此分支不再维护了，请升级到最新6.0版本

ThinkCMF 5.1.7 让你更自由地飞
===============

### ThinkCMF 6.0已发布，推荐所有用户使用
仓库地址如下：
1. 码云:https://gitee.com/thinkcmf/ThinkCMF/tree/6.0 主要仓库
2. GitHub:https://github.com/thinkcmf/thinkcmf/tree/6.0 国际镜像

### 如何保证代码同时兼容5.1和6.0?
https://www.thinkcmf.com/topic/10455.html

### 系列讲座
https://www.thinkcmf.com/college.html

### ThinkCMF5.1主要特性
* 更改框架协议为`MIT`,让你更自由地飞
* 基于`ThinkPHP 5.1`重构，但核心代码兼容5.0版本，保证老用户最小升级成本
* 增加对`swoole`支持，同时支持`swoole`协程和全同步模式
* 重新规范目录结构，更贴心
* CMF核心库及应用使用`composer`加载
* 合并API到框架核心
* 更规范的代码,遵循`PSR-2`命名规范和`PSR-4`自动加载规范
* 支持 `composer` 管理第三方库
* 核心化：独立核心代码包
* 应用化：开发者以应用的形式增加项目模模块
* 插件化：更强的插件机制，开发者以插件形式扩展功能
* 模板化：前台可视化设计
* 支持URL美化功能，支持别名设置，更简单
* 独立的回收站功能，可以管理所有应用临时删除的数据
* 统一的资源管理，相同文件只保存一份
* 注解式的后台菜单管理功能，方便开发者代码管理后台菜单
* 插件同样支持注解式的后台菜单管理功能
* 文件存储插件化，默认支持七牛文件存储插件
* 模板制作标签化，内置多个cmf标签，方便小白用户
* 更人性化的导航标签，可以随意定制 html 结构
* 后台首页插件化，用户可以定制的网站后台首页

### 开发手册
http://www.kancloud.cn/thinkcmf/doc5_1

### Git仓库

1. 码云:https://gitee.com/thinkcmf/ThinkCMF 主要仓库
2. GitHub:https://github.com/thinkcmf/thinkcmf 国际镜像

### 演示仓库
此仓库会放官方的一些演示应用，插件，模板，API等 
1. https://github.com/thinkcmf/demos 主要仓库
2. https://gitee.com/thinkcmf/demos 中国镜像

### 环境推荐
> php7.3

> mysql 5.7+

> 打开rewrite


### 最低环境要求
> php5.6+

> mysql 5.5+ (mysql5.1安装时选择utf8编码，不支持表情符)

> 打开rewrite


### 运行环境配置教程
https://www.thinkcmf.com/topic/1502.html



代码已经加入自动安装程序,如果你在安装中有任何问题请提交 issue!

1. public目录做为网站根目录,入口文件在 public/index.php
2. 配置好网站，请访问http://你的域名

enjoy your cmf~!

### 系统更新
如果您是已经安装过ThinkCMF的用户,请查看 update 目录下的 sql 升级文件,根据自己的下载的程序版本进行更新

### 完整版目录结构
~~~
thinkcmf  根目录
├─api                     api目录
│  ├─demo                 演示应用api目录
│  │  ├─controller        控制器目录
│  │  ├─model             模型目录
│  │  └─ ...              更多类库目录
├─app                     应用目录
│  ├─demo                 演示应用目录
│  │  ├─controller        控制器目录
│  │  ├─model             模型目录
│  │  └─ ...              更多类库目录
│  ├─ ...                 更多应用
│  ├─app.php              应用(公共)配置文件[可选]
│  ├─command.php          命令行工具配置文件[可选]
│  ├─common.php           应用公共(函数)文件[可选]
│  ├─database.php         数据库配置文件[可选]
│  ├─tags.php             应用行为扩展定义文件[可选]
├─data                    数据目录（可写）
│  ├─config               动态配置目录（可写）
│  ├─route                动态路由目录（可写）
│  ├─runtime              应用的运行时目录（可写）
│  └─ ...                 更多
├─public                  WEB 部署目录（对外访问目录）
│  ├─plugins              插件目录
│  ├─static               官方静态资源存放目录(css,js,image)，勿放自己项目文件
│  ├─themes               前后台主题目录
│  │  ├─admin_simpleboot3 后台默认主题
│  │  └─default           前台默认主题
│  ├─upload               文件上传目录
│  ├─api.php              API入口
│  ├─index.php            入口文件
│  ├─robots.txt           爬虫协议文件
│  ├─router.php           快速测试文件
│  └─.htaccess            apache重写文件
├─extend                  扩展类库目录[可选]
├─vendor                  第三方类库目录（Composer）
│  ├─thinkphp             ThinkPHP目录
│  └─...             
├─composer.json           composer 定义文件
├─LICENSE                 授权说明文件
├─README.md               README 文件
├─think                   命令行入口文件
~~~

### QQ群:
`ThinkCMF 官方交流群`:316669417  
   
`ThinkCMF 高级交流群`:100828313 (付费)  
高级群专属权益:  
第一波:两个后台风格(ThinkCMF官网风格后台主题,蓝色风格后台主题)  
第二波:ThinkCMF5完全开发手册离线版(PDF,EPUB,MOBI格式)  
更多专属权益正在路上...

`ThinkCMF 铲屎官交流群`:415136742 (生活娱乐，为有喵的猿人准备)

### 话题专区
http://www.thinkcmf.com/topic/index/index/cat/11.html

### 反馈问题
https://github.com/thinkcmf/thinkcmf/issues

### 更新日志
#### 5.1.7
* 重构回收站代码，添加全部删除、一键清空和全部还原功能
* 增加插件url美化
* 增加默认过滤器
* 增加插件未安装、未启用时禁止访问
* 增加`think\facade\Db`类
* 优化语言包加载顺序
* 优化前端组件
* 优化cmf版本获取
* 优化`cmf_clear_cache()`函数
* 修复用户行为产生积分或金币为空还有日志问题
* 修复管理员编辑报错
* 规范所有数据库操作用法


#### 5.1.6
* 修复插件后台权限认证问题
* 升级到tp5.1.40
* 优化后台管理添加和编辑
* 删除phpquery类jqueryServer目录
* 优化后台管理员新增和编辑
* 优化语言包加载顺序

#### 5.1.5
* 升级到tp5.1.39
* 增加模板设计数组列表图片显示
* 优化前台基类
* 取消路由排序限制

#### 5.1.4
* 优化上传逻辑，已传文件更新文件名
* 优化系统钩子初始化
* 修复编辑器锚点处理错误
* 修复部分系统函数判断问题
* 修复tp5.1.38前台控制器报错
* 修复tp5.1.38下邮件验证码发不出

#### 5.1.3
* 增加`CMF_DATA`常量（注意升级）
* 增加插件路由功能
* 增加插件URL美化功能
* 修复app_init钩子引起的命令行报错
* 修复API中文件url转化错误

#### 5.1.2
[核心]
* 升级tp到`5.1.37`
* 优化`slides,noslides`标签
* 修复头像地址获取函数
* 优化上传类支持API文件上传
* 封装后台菜单，应用钩子，用户行为导入
* 增加应用自动安装
* 优化后台百度地图链接支持https

[API]
* 优化文件上传，支持云存储
* 修复积分日志接口数据返回错误
* 修复钩子不加载问题
* 修复API跨域报错问题


#### 5.1.1
[核心]
* `composer.json` extra 增加`think-config`配置
* 修复API UserLikeModel继承错误类
* 优化后台菜单 url 生成
* 增加Linux下全新安装时data目录不可写提示
* 修复插件模板常量`__ROOT__`不替换
* 增加`swoole`扩展钩子检测
* 修复插件API基类报错#577
* 优化应用初始化流程
* 优化行为加载流程

[swoole]
* 增加`swoole_server_start`,`swoole_worker_start`,`swoole_websocket_on_open`,`swoole_websocket_on_close`钩子
* 增加`WebSocket`独立运行命令
* 增加`WebSocket onOpen`回调
* 修复`WebSocket`事件引起的数据库执行报错
* 修复`WebSocket`发送消息未判断是否为`WebSocket`连接
* 增加`worker`进程启动时自动初始化所有模块



#### 5.1.0
[核心]
* 更改框架协议为`MIT`,让你更自由地飞
* 升级`TP`到`5.1.35`
* 独立安装应用为`composer`包
* 移除portal应用，请到`https://github.com/thinkcmf/demos`下载
* 移除`simpleboot3`模板，请到`https://github.com/thinkcmf/demos`下载
* 移除`phpoffice/phpspreadsheet`,`phpoffice/phpexcel`,`dompdf/dompdf`第三方库,请自行安装
* 移动`qiniu/php-sdk`库到七牛插件
* `extend`目录改为可选，开发者自行添加，核心不再包含此目录
* 增加`demo`应用，方便开发者学习
* 增加插件`@adminMenuRoot`注解支持
* 增加`app,api和插件`composer第三方库支持
* 增加后台模板动态设置功能
* 使用`composer classmap`做相关类的映射
* 更改所有`thinkcmf`包版本号依赖
* 优化清除缓存,清除opcache缓存
* 优化`cmf_set_dynamic_config`兼容5.0和5.1
* 升级`PHPMailer`使用`PHPMailer 6.0`（注意类的引入变化）
* 修复路由是否存在检测问题
* 修复url美化由于后台权限设置可能引起的漏洞(漏洞编号CVE-2019-6713 感谢topsec(zhan_ran)的及时反馈)
* 修复子导航标签报错
* 修复数据库对象实例化不当导致的问题
* 修复`BaseController`排序批量更新
* 修复新建管理员登录时报错
* 取消`THINKCMF_VERSION`常量，请使用`cmf_version()`
* 取消`PLUGINS_PATH`常量，请使用`WEB_ROOT.'plugins/`

[swoole]
* 增加`websocket`演示
* 优化`swoole`配置初始化
* 优化`swoole`下内容输出
* 更改默认缓存大小为128M
* 修复`swoole`如果控制器返回内容为空报错问题
* 修复`swoole`下核心包路由注册位置
* 修复`swoole`下后台风格无法设置

#### 5.1.0-beta
[核心]
* 升级`ThinkCMF 5.0`到`ThinkPHP 5.1`





