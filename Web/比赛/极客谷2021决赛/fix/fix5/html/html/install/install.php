

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>正在创建数据库</title>
</head>

<body>

<?php 
//检测安装文件是否存在
if (file_exists("install.lock"))
{
echo "对不起，您的程序已经安装过。请修改install.lock为install.php再浏览页面";
exit;
}
$files="../data/conn.php"; 
if(!is_writable($files)){ 
echo "<font color=red>不可写！！！</font>"; 
}else{ 
} 
if(isset($_POST[install])){ 
$config_str = "<?php"; 
$config_str .= "\n"; 
$config_str .= '$db_host = "' . $_POST[db_host] . '";'; 
$config_str .= "\n"; 
$config_str .= '$db_user = "' . $_POST[db_user] . '";'; 
$config_str .= "\n"; 
$config_str .= '$db_pwd = "' . $_POST[db_pass] . '";'; 
$config_str .= "\n"; 
$config_str .= '$db_name = "' . $_POST[db_dbname] . '";'; 
$config_str .= "\n"; 
$config_str .= '$db_port = "' . $_POST[db_port] . '";'; 
$config_str .= "\n"; 
$config_str .= '$mysql_tag = "' . $_POST[db_tag] . '";'; 
$config_str .= "\n"; 
$config_str .= '?>'; 
$ff = fopen($files, "w+"); 
fwrite($ff, $config_str); 
//===================== 
include_once ("../data/conn.php"); //嵌入配置文件 
if (!@$link = mysql_connect($db_host, $db_user, $db_pwd)) { //检查数据库连接情况 
echo "数据库连接失败! 请返回上一页检查连接参数 <a href=install.php>返回修改</a>"; 
} else { 

@mysql_query("SET NAMES 'utf8'",$con);
if(@mysql_query("CREATE DATABASE `$db_name`;",$con)){
echo "创建数据库<b><FONT color='RED'>$mydb</FONT></b>成功!<FONT color='RED'>OK</FONT><br> \n";}	
else{
echo " ";}


$con=mysql_connect($db_host,$db_user,$db_pwd);

$sql="set names 'utf8'";
mysql_query($sql);
$table1=tgs_admin;  //表单一名定义
@mysql_select_db($db_name,$con);
if(@mysql_query("CREATE TABLE `$table1` (

     `id` tinyint(3) unsigned NOT NULL auto_increment, 
`username` varchar(20) NOT NULL, 
`password` varchar(40) NOT NULL, 
`logins` mediumint(8) NULL, 
PRIMARY KEY (`id`) 
		) CHARACTER SET utf8",$con))
{

echo "</br>（一）创建表<b><FONT color='RED'>$table1</FONT></b>...成功!<FONT color='RED'>OK</FONT>\n";}	
else{
echo "</br>（一）创建表<b><FONT color='RED'>$table1</FONT></b>...失败,可能表已经存在<FONT color='RED'>X</FONT>";
}


$table2=tgs_agent;  //表单一名定义
@mysql_select_db($db_name,$con);
if(@mysql_query("CREATE TABLE `$table2` (
`id` int(11) unsigned NOT NULL auto_increment, 
`agentid` varchar(50) NULL, 
`name` varchar(100) NULL, 
`phone` varchar(100) NULL, 
`idcard` varchar(100) NULL, 
`weixin` varchar(100) NULL, 
`qq` varchar(100) NULL, 
`addtime` date NULL, 
`jietime` date NULL,  
`qudao` varchar(100) NULL,
`dengji` varchar(100) NULL,
`product` varchar(100) NULL, 
`quyu` varchar(100) NULL, 
`shuyu` varchar(100) NULL, 
`url` varchar(100) NULL,
`about` varchar(250) NULL,  
`tel` varchar(100) NULL,    
`fax` varchar(100) NULL,   
`danwei` varchar(100) NULL,   
`email` varchar(100) NULL,   
`wangwang` varchar(100) NULL, 
`paipai` varchar(100) NULL, 
`zip` varchar(100) NULL, 
`dizhi` varchar(100) NULL, 
`beizhu` varchar(100) NULL, 
`hits` int(8) NULL DEFAULT '0', 
`query_time` datetime NULL, 
`sjdl` varchar(100) NULL, 
`shzt` varchar(50) NULL, 
`hmd` varchar(50) NULL,  
`password` varchar(50) NULL,  
`sqdengji` varchar(50) NULL,  
`dkpic` varchar(50) NULL,  
`sqtime` varchar(50) NULL,  
`applytime` date NULL, 
PRIMARY KEY (`id`) 
		) CHARACTER SET utf8",$con))
{

echo "</br>（二）创建表<b><FONT color='RED'>$table2</FONT></b>...成功!<FONT color='RED'>OK</FONT>\n";}	
else{
echo "</br>（二）创建表<b><FONT color='RED'>$table2</FONT></b>...失败,可能表已经存在<FONT color='RED'>X</FONT>";
}


$table3=tgs_code;  //表单一名定义
@mysql_select_db($db_name,$con);
if(@mysql_query("CREATE TABLE `$table3` (

`id` int(11) unsigned NOT NULL auto_increment, 
`bianhao` varchar(50) NULL, 
`riqi` varchar(30) NULL, 
`product` varchar(100) NULL, 
`zd1` varchar(250) NULL, 
`zd2` varchar(250) NULL,
`hits` int(8) NULL DEFAULT '0',
`query_time` datetime NULL,
`query_ip` varchar(250) NULL,  
PRIMARY KEY (`id`) 
		) CHARACTER SET utf8",$con))
{

echo "</br>（三）创建表<b><FONT color='RED'>$table3</FONT></b>...成功!<FONT color='RED'>OK</FONT>\n";}	
else{
echo "</br>（三）创建表<b><FONT color='RED'>$table3</FONT></b>...失败,可能表已经存在<FONT color='RED'>X</FONT>";
}



$table4=tgs_config;  //表单一名定义
@mysql_select_db($db_name,$con);
if(@mysql_query("CREATE TABLE `$table4` (
`id` mediumint(6) unsigned NOT NULL auto_increment, 
`parentid` smallint(5) NOT NULL, 
`code` varchar(30) NOT NULL, 
`code_name` varchar(100) NOT NULL, 
`code_value` text NULL, 
`store_range` varchar(50) NULL,
`type` varchar(20) NULL,
PRIMARY KEY (`id`) 
		) CHARACTER SET utf8",$con))
{

echo "</br>（四）创建表<b><FONT color='RED'>$table4</FONT></b>...成功!<FONT color='RED'>OK</FONT>\n";}	
else{
echo "</br>（四）创建表<b><FONT color='RED'>$table4</FONT></b>...失败,可能表已经存在<FONT color='RED'>X</FONT>";
}



$table5=tgs_hisagent;  //表单一名定义
@mysql_select_db($db_name,$con);
if(@mysql_query("CREATE TABLE `$table5` (
`id` int(11) unsigned NOT NULL auto_increment, 
`keyword` varchar(50) NULL, 
`addtime` datetime NULL, 
`addip` varchar(40) NULL, 
`results` tinyint(2) NULL, 
PRIMARY KEY (`id`) 
		) CHARACTER SET utf8",$con))
{

echo "</br>（五）创建表<b><FONT color='RED'>$table5</FONT></b>...成功!<FONT color='RED'>OK</FONT>\n";}	
else{
echo "</br>（五）创建表<b><FONT color='RED'>$table5</FONT></b>...失败,可能表已经存在<FONT color='RED'>X</FONT>";
}

$table7=tgs_dengji;  //表单一名定义
@mysql_select_db($db_name,$con);
if(@mysql_query("CREATE TABLE `$table7` (

`id` int(11) unsigned NOT NULL auto_increment, 
`djname` varchar(50) NULL, 
`checkper` varchar(50) NULL, 
`editper` varchar(50) NULL, 
`jibie` varchar(50) NULL, 
`delper` varchar(50) NULL,
`sjcheckper` varchar(50) NULL,
PRIMARY KEY (`id`) 
		) CHARACTER SET utf8",$con))
{

echo "</br>（三）创建表<b><FONT color='RED'>$table7</FONT></b>...成功!<FONT color='RED'>OK</FONT>\n";}	
else{
echo "</br>（三）创建表<b><FONT color='RED'>$table7</FONT></b>...失败,可能表已经存在<FONT color='RED'>X</FONT>";
}

$table8=tgs_product;  //表单一名定义
@mysql_select_db($db_name,$con);
if(@mysql_query("CREATE TABLE `$table8` (

`id` int(11) unsigned NOT NULL auto_increment, 
`proname` varchar(50) NULL, 
`jibie` varchar(50) NULL, 
`proimg` varchar(100) NULL, 
`saytext` text(0) NULL, 
PRIMARY KEY (`id`) 
		) CHARACTER SET utf8",$con))
{

echo "</br>（三）创建表<b><FONT color='RED'>$table8</FONT></b>...成功!<FONT color='RED'>OK</FONT>\n";}	
else{
echo "</br>（三）创建表<b><FONT color='RED'>$table8</FONT></b>...失败,可能表已经存在<FONT color='RED'>X</FONT>";
}

$table6=tgs_history;  //表单一名定义
@mysql_select_db($db_name,$con);
if(@mysql_query("CREATE TABLE `$table6` (
`id` int(11) unsigned NOT NULL auto_increment, 
`keyword` varchar(50) NULL, 
`addtime` datetime NULL, 
`addip` varchar(40) NULL, 
`results` tinyint(2) NULL, 
PRIMARY KEY (`id`) 
		) CHARACTER SET utf8",$con))
{

echo "</br>（六）创建表<b><FONT color='RED'>$table6</FONT></b>...成功!<FONT color='RED'>OK</FONT>\n";}	
else{
echo "</br>（六）创建表<b><FONT color='RED'>$table6</FONT></b>...失败,可能表已经存在<FONT color='RED'>X</FONT>";
}

 //写入管理员信息
@mysql_select_db($db_name,$con);
if(@mysql_query("insert into tgs_admin(username,password,logins)values('admin','21232f297a57a5a743894a0e4a801fc3','0');"))
{

echo "</br>（七）写入<b><FONT color='RED'>管理员信息</FONT></b>...成功!<FONT color='RED'>OK</FONT>\n";}	
else{
echo "</br>（七）写入<b><FONT color='RED'>管理员信息</FONT></b>...失败,可能表已经存在<FONT color='RED'>X</FONT>";
}

 //写入等级信息
@mysql_select_db($db_name,$con);
if(@mysql_query("insert into tgs_dengji(djname,checkper,editper,jibie,delper)values('一级代理','1','1','9','1'),('二级代理','1','1','8','1'),('三级代理','1','1','7','1');"))
{

echo "</br>（七）写入<b><FONT color='RED'>代理等级信息</FONT></b>...成功!<FONT color='RED'>OK</FONT>\n";}	
else{
echo "</br>（七）写入<b><FONT color='RED'>代理等级信息</FONT></b>...失败,可能表已经存在<FONT color='RED'>X</FONT>";
}

 //写入系统配置信息
@mysql_select_db($db_name,$con);
$noteice="请刮开防伪标签上灰色涂层，在输入框内依次输入16位防伪码，然后点查询键即可。<br>查询显示结果如下：<br>1、如果错误，提示为“您输入的防伪码不存在，谨防假冒”。<br>2、如果查询过，提示为“该商品已经被查询过,本次是第*次查询,谨防假冒”。<br /><br />
 <p style=\"text-align:center\"><a href=\"index.php\">代理查询</a>     <a href=\"mblogin/\" target=\"_blank\">代理登录</a></p>";
if(@mysql_query("insert into tgs_config (parentid,code,code_name,code_value,type)values('1','site_url','网站地址','/','text'),('1','site_name','网站名称','微营销管理系统','text'),('1','timezone','timezone','8','text'),('1','time_format','时间格式','Y-m-d H:i:s','text'),('1','site_lang','语言','zh_cn','text'),('1','text_type','txttype','1','text'),('1','site_themes','防伪主题','2001','text'),('1','agent_themes','代理主题','1001','text'),('1','yzm_status','验证码状态','0','checkbox'),('1','page_title','网页标题','','text'),('1','page_keywords','微营销管理系统','','text'),('1','page_desc','网页描述','','text'),('1','site_close','关闭站点','','text'),('1','site_close_reason','关闭理由','','text'),('1','notices','提示','$noteice','text'),('1','notice_1','提示','尊敬的顾客您好，您购买的是本公司生产的产品{{product}},有效服务期限为{{riqi}}，{{zd1}},{{zd2}},属于正品，请放心使用。','text'),('1','notice_2','提示','该商品已经被查询过,本次是第<font color=\"#FF0000\" size=\"+1\">{{hits}}</font>次查询,上次一次查询时间：{{query_time}},查询IP：{{query_ip}} 谨防假冒','text'),('1','notice_3','提示','您输入的防伪码{{bianhao}}不存在，谨防假冒。','text'),('1','agents','提示','请输入代理商微信号或手机号，然后点查询键即可。<br><br />
 <p style=\"text-align:center\"><a href=\"fw.php\">防伪查询</a>     <a href=\"mblogin/\" target=\"_blank\">代理登录</a></p>','text'),('1','agent_1','提示','兹授权 {{name}}  手机号码{{phone}} 
微信号{{weixin}}为 我司产品{{dengji}}。
全权代理本公司产品销售及推广业务。
特此授权


授权期限：{{addtime}} 至 {{jietime}}
授权编号：{{agentid}}

                   签发日期：{{addtime}}','text'),('1','dldj','代理等级','一级代理,二级代理,三级代理','text'),('1','site_logo','网站LOGO','editor/attached/logo-a.png','text'),('1','copyrighta','底部版权','Copyright  2016 版权所有</br>
微营销查询管理系统','text'),('1','agent_2','提示','您查询的用户{{name}}因违返公司相关规定，已经加入黑名单！谨防假冒','text'),('1','agent_3','提示','您输入的代理商信息{{keyword}}不存在，谨防假冒。','text'),('1','list_num','list','100','text'),('1','agentqz','代理编号前缀','NF','text'),('1','agent_logo','代理页面LOGO','editor/attached/agent_logo.png','text'),('1','fw_logo','防伪页面LOGO','editor/attached/fw_logo.png','text'),('1','bgpic','自定义背景图','editor/attached/bg.jpg','text'),('1','mbgpic','手机端背景图','editor/attached/bg.jpg','text');"))
{

$anstatue=1;
echo "</br>（八）写入<b><FONT color='RED'>系统配置信息</FONT></b>...成功!<FONT color='RED'>OK</FONT>\n";}	
else{
	
echo "</br>（八）写入<b><FONT color='RED'>系统配置信息</FONT></b>...失败<FONT color='RED'>X</FONT>";

}
if ($anstatue==1) {
  echo "<script>alert('恭喜您安装成功!');location.href='success.php'</script>";
  rename("install.php","install.lock"); 
} else {
  echo "</br></br></br>安装失败，请检查系统设置！";
}
} 
} 
?> 

</body>
</html>
