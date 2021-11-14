<?php
error_reporting(0);
require("../data/session_admin.php");
require("../data/head.php");
require('../data/reader.php');

$adminuser=$_SESSION["Adminnamess"];
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="renderer" content="webkit|ie-comp|ie-stand">

<meta http-equiv="Cache-Control" content="no-siteapp" />
<LINK rel="Bookmark" href="/favicon.ico" >
<LINK rel="Shortcut Icon" href="/favicon.ico" />
<!--[if lt IE 9]>
<script type="text/javascript" src="lib/html5.js"></script>
<script type="text/javascript" src="lib/respond.min.js"></script>
<script type="text/javascript" src="lib/PIE_IE678.js"></script>
<![endif]-->
<link rel="stylesheet" type="text/css" href="static/h-ui/css/H-ui.min.css" />
<link rel="stylesheet" type="text/css" href="static/h-ui.admin/css/H-ui.admin.css" />
<link rel="stylesheet" type="text/css" href="lib/Hui-iconfont/1.0.7/iconfont.css" />
<link rel="stylesheet" type="text/css" href="lib/icheck/icheck.css" />
<link rel="stylesheet" type="text/css" href="static/h-ui.admin/skin/default/skin.css" id="skin" />
<link rel="stylesheet" type="text/css" href="static/h-ui.admin/css/style.css" />
<!--[if IE 6]>
<script type="text/javascript" src="http://lib.h-ui.net/DD_belatedPNG_0.0.8a-min.js" ></script>
<script>DD_belatedPNG.fix('*');</script>
<![endif]-->
<TITLE>微营销后台管理系统</TITLE>
</head>
<body>
<header class="navbar-wrapper">
	<div class="navbar navbar-fixed-top">
		<div class="container-fluid cl"> <a class="logo navbar-logo f-l mr-10 hidden-xs" href="main.php">微应用商用版后台管理系统 V6.1</a>  <span class="logo navbar-slogan f-l mr-10 hidden-xs"></span> <a aria-hidden="false" class="nav-toggle Hui-iconfont visible-xs" href="javascript:;">&#xe667;</a>
			
			<nav id="Hui-userbar" class="nav navbar-nav navbar-userbar hidden-xs">
				<ul class="cl">
                <li><a href="../index.php" target="_blank">代理前台</a>&nbsp;&nbsp;</li>
                <li><a href="../fw.php" target="_blank">防伪前台</a>&nbsp;&nbsp;</li>
                <li><a href="../mblogin" target="_blank">代理商登录</a>&nbsp;&nbsp;</li>
					<li>管理员</li>
					<li class="dropDown dropDown_hover"> <a href="#" class="dropDown_A"><?php echo $adminuser ?> <i class="Hui-iconfont">&#xe6d5;</i></a>
						<ul class="dropDown-menu menu radius box-shadow">
							
							
						  <li><a href="index.php?act=logout">安全退出</a></li>
						</ul>
					</li>
					
				  <li id="Hui-skin" class="dropDown right dropDown_hover"> <a href="javascript:;" class="dropDown_A" title="换肤"><i class="Hui-iconfont" style="font-size:18px">&#xe62a;</i></a>
						<ul class="dropDown-menu menu radius box-shadow">
							<li><a href="javascript:;" data-val="default" title="默认（黑色）">默认（黑色）</a></li>
							<li><a href="javascript:;" data-val="blue" title="蓝色">蓝色</a></li>
							<li><a href="javascript:;" data-val="green" title="绿色">绿色</a></li>
							<li><a href="javascript:;" data-val="red" title="红色">红色</a></li>
							<li><a href="javascript:;" data-val="yellow" title="黄色">黄色</a></li>
							<li><a href="javascript:;" data-val="orange" title="绿色">橙色</a></li>
						</ul>
					</li>
				</ul>
			</nav>
		</div>
	</div>
</header>
<aside class="Hui-aside">
	<input runat="server" id="divScrollValue" type="hidden" value="" />
	<div class="menu_dropdown bk_2">
		<dl id="menu-article">
			<dt><i class="Hui-iconfont">&#xe60d;</i> <strong>代理商管理</strong><i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i></dt>
			<dd style="display: block;">
				<ul>
                    <li><a _href="agent.php?" data-title="管理代理商" href="javascript:void(0)">管理代理商</a></li>
					<li><a _href="agent.php?act=add" data-title="添加代理商" href="javascript:void(0)">添加代理商</a></li>
                    <li><a _href="agent_tree.php" data-title="查看代理树" href="javascript:void(0)">查看代理树</a></li>
                    <li><a _href="agent.php?act=import" data-title="导入代理商" href="javascript:void(0)">导入代理商</a></li>
                    <li><a _href="agent.php?act=query_record" data-title="代理商查询记录" href="javascript:void(0)">代理商查询记录</a></li>
                
				</ul>
			</dd>
		</dl>
		<dl id="menu-picture">
			<dt><i class="Hui-iconfont">&#xe613;</i> <strong>防伪码管理</strong><i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i></dt>
			<dd style="display: block;">
				<ul>
					<li><a _href="admin.php?" data-title="管理防伪码" href="javascript:void(0)">管理防伪码</a></li>
					<li><a _href="admin.php?act=add" data-title="添加/批量生成" href="javascript:void(0)">添加/批量生成</a></li>
                    <li><a _href="admin.php?act=import" data-title="导入防伪码" href="javascript:void(0)">导入防伪码</a></li>
                    <li><a _href="admin.php?act=query_record" data-title="防伪码查询记录" href="javascript:void(0)">防伪码查询记录</a></li>
				</ul>
			</dd>
		</dl>
		
		
		
		<dl id="menu-admin">
			<dt><i class="Hui-iconfont">&#xe62d;</i> <strong>管理员管理</strong><i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i></dt>
			<dd>
				<ul>
                <li><a _href="admin.php?act=superadmin" data-title="管理员列表" href="javascript:void(0)">管理员列表</a></li>
					
					<li><a _href="admin.php?act=superadmin" data-title="添加管理员" href="javascript:void(0)">添加管理员</a></li>
			  </ul>
			</dd>
		</dl>
		
	  <dl id="menu-system">
			<dt><i class="Hui-iconfont">&#xe62e;</i> <strong>系统设置</strong><i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i></dt>
			<dd>
				<ul>
					<li><a _href="admin.php?act=config" data-title="基本信息设置" href="javascript:void(0)">基本信息配置</a></li>
                    <li><a _href="admin.php?act=config#agent" data-title="基本信息设置" href="javascript:void(0)">代理系统配置</a></li>
					<li><a _href="admin.php?act=config#fw" data-title="代理权限设置" href="javascript:void(0)">防伪系统配置</a></li>
                    <li><a _href="admin.php?act=dengji" data-title="代理等级权限设置" href="javascript:void(0)">代理等级/权限设置</a></li>
                    <li><a _href="admin.php?act=product" data-title="产品名称设置" href="javascript:void(0)">产品名称设置</a></li>
                      
					
			  </ul>
			</dd>
		</dl>
        <dl id="menu-system">
			<dt><i class="Hui-iconfont">&#xe62e;</i> <strong>模板界面风格</strong><i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i></dt>
			<dd>
				<ul>
					<li><a _href="skin.php" data-title="模板设置/选择" href="javascript:void(0)">模板设置/选择</a></li>
                    <li><a _href="picmanage.php?act=config" data-title="模板图片管理" href="javascript:void(0)">模板图片管理</a></li>
					
					
			  </ul>
			</dd>
		</dl>
	</div>
</aside>
<div class="dislpayArrow hidden-xs"><a class="pngfix" href="javascript:void(0);" onClick="displaynavbar(this)"></a></div>
<section class="Hui-article-box">
	<div id="Hui-tabNav" class="Hui-tabNav hidden-xs">
		<div class="Hui-tabNav-wp">
			<ul id="min_title_list" class="acrossTab cl">
				<li class="active"><span title="我的桌面" data-href="welcome.html">我的桌面</span><em></em></li>
			</ul>
		</div>
		<div class="Hui-tabNav-more btn-group"><a id="js-tabNav-prev" class="btn radius btn-default size-S" href="javascript:;"><i class="Hui-iconfont">&#xe6d4;</i></a><a id="js-tabNav-next" class="btn radius btn-default size-S" href="javascript:;"><i class="Hui-iconfont">&#xe6d7;</i></a></div>
	</div>
	<div id="iframe_box" class="Hui-article">
		<div class="show_iframe">
			<div style="display:none" class="loading"></div>
			<iframe scrolling="yes" frameborder="0" src="welcome.php"></iframe>
		</div>
	</div>
</section>
<script type="text/javascript" src="lib/jquery/1.9.1/jquery.min.js"></script> 
<script type="text/javascript" src="lib/layer/2.1/layer.js"></script> 
<script type="text/javascript" src="static/h-ui/js/H-ui.js"></script> 
<script type="text/javascript" src="static/h-ui.admin/js/H-ui.admin.js"></script> 
<script type="text/javascript">
/*资讯-添加*/
function article_add(title,url){
	var index = layer.open({
		type: 2,
		title: title,
		content: url
	});
	layer.full(index);
}
/*图片-添加*/
function picture_add(title,url){
	var index = layer.open({
		type: 2,
		title: title,
		content: url
	});
	layer.full(index);
}
/*产品-添加*/
function product_add(title,url){
	var index = layer.open({
		type: 2,
		title: title,
		content: url
	});
	layer.full(index);
}
/*用户-添加*/
function member_add(title,url,w,h){
	layer_show(title,url,w,h);
}
</script> 
<script type="text/javascript">
var _hmt = _hmt || [];
(function() {
  var hm = document.createElement("script");
  hm.src = "//hm.baidu.com/hm.js?080836300300be57b7f34f4b3e97d911";
  var s = document.getElementsByTagName("script")[0]; 
  s.parentNode.insertBefore(hm, s)})();
var _bdhmProtocol = (("https:" == document.location.protocol) ? " https://" : " http://");
document.write(unescape("%3Cscript src='" + _bdhmProtocol + "hm.baidu.com/h.js%3F080836300300be57b7f34f4b3e97d911' type='text/javascript'%3E%3C/script%3E"));
</script>
</body>
</html>