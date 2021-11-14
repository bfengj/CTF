<?php
error_reporting(0);
require("../data/session_admin.php");
require("../data/head.php");
require('../data/reader.php');

?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="renderer" content="webkit|ie-comp|ie-stand">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
<meta http-equiv="Cache-Control" content="no-siteapp" />
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
<script type="text/javascript" src="lib/DD_belatedPNG_0.0.8a-min.js" ></script>
<script>DD_belatedPNG.fix('*');</script>
<![endif]-->
<title>我的桌面</title>
</head>
<body>
<div class="page-container">
	<p class="f-20 text-success">欢迎使用微营销管理系统 <span class="f-14"></span></p>
	
	<table class="table table-border table-bordered table-bg">
		<thead>
			<tr>
				<th colspan="2" scope="col">系统提示:</th>
			</tr>
			<tr class="">
            <?php
$sql="select * from tgs_agent";
$query=mysql_query($sql);
$total=mysql_num_rows($query);
?>

 <?php
$sql1="select * from tgs_agent where shzt=2";
$query1=mysql_query($sql1);
$total1=mysql_num_rows($query1);
?>

 <?php
$sql2="select * from tgs_agent where sqdengji<>''";
$query2=mysql_query($sql2);
$total2=mysql_num_rows($query2);
?>

 <?php
$sql3="select * from tgs_agent where to_days(applytime) = to_days(now());";
$query3=mysql_query($sql3);
$total3=mysql_num_rows($query3);
?>

 <?php
$sql4="select * from tgs_agent where DATE_SUB(CURDATE(), INTERVAL 7 DAY) <= date(applytime)";
$query4=mysql_query($sql4);
$total4=mysql_num_rows($query4);
?>

 <?php
$sql5="select * from tgs_agent where DATE_SUB(CURDATE(), INTERVAL 30 DAY) <= date(applytime)";
$query5=mysql_query($sql5);
$total5=mysql_num_rows($query5);
?>
	
				<th align="left">代理总数：<?php echo $total;?> &nbsp;&nbsp;&nbsp;&nbsp;未审核代理：<?php echo $total1;?> &nbsp;&nbsp;&nbsp;&nbsp;代理升级请求：<?php echo $total2;?>&nbsp;&nbsp;&nbsp;&nbsp;今日新申请代理：<?php echo $total3;?>&nbsp;&nbsp;&nbsp;&nbsp;本周新申请代理：<?php echo $total4;?>&nbsp;&nbsp;&nbsp;&nbsp;当月新申请代理：<?php echo $total5;?></th>
			</tr>
		</thead>
		
	</table>
    
    <table class="table table-border table-bordered table-bg  mt-20">
		<thead>
			<tr>
				<th colspan="2" scope="col">常用操作说明:</th>
			</tr>
			<tr class="newslistff">
				<th align="left">
                <ul>
				<li><a href="/bz/zdydl.htm">如何自定义代理等级和权限？</a></li>
				<li><a href="/bz/zmsy.htm">防伪系统怎么使用？</a></li>
				<li><a href="/bz/ghsq.htm">如何更换授权证书？</a></li>
				<li><a href="/bz/bd.htm">如何配置微信公众平台？</a></li>
				<li><a href="/bz/xg.htm">如何修改网站LOGO？</a></li>
				<li><a href="/bz/pz.htm">第一步：配置网站基本信息</a></li>
				</ul>
                
                </th>
			</tr>
		</thead>
		
	</table>
	<table class="table table-border table-bordered table-bg mt-20">
		<thead>
			<tr>
				<th colspan="2" scope="col">程序信息</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<th width="30%">软件版本</th>
				<td><span id="lbServerName">微应用 V6.1</span></td>
			</tr>
            
            	<tr>
				<td>开发者</td>
				<td>微应用</td>
			</tr>
			
		</tbody>
	</table>
</div>
<footer class="footer mt-20">
	<div class="container">
		<p>感谢jQuery、layer、laypage、Validform、UEditor、My97DatePicker、iconfont、Datatables、WebUploaded、icheck、highcharts、bootstrap-Switch<br>
			Copyright &copy;2017 微应用V6.1商用版后台管理系统 All Rights Reserved.<br>
		
	</div>
</footer>
<script type="text/javascript" src="lib/jquery/1.9.1/jquery.min.js"></script> 
<script type="text/javascript" src="static/h-ui/js/H-ui.js"></script> 

</body>
</html>