<?php
require("conn.php");
require("function.php");
require('lang_zh_cn.php');

////数据库连接
$conn = mysql_connect($db_host, $db_user,$db_pwd);
if(!$conn)
{
   die("错误提示: 无法连接到数据库，请检查数据库。");
}
mysql_select_db($db_name,$conn);

$sql="set names 'utf8'";
mysql_query($sql);

///系统初如化
date_default_timezone_set('Asia/Chongqing'); 
$cf = get_site_config(1);
$GLOBALS['tgs']['cur_ip']    = $_SERVER["REMOTE_ADDR"];////当前IP
$GLOBALS['tgs']['cur_time']  = date($cf['time_format'],(time()+$cf['timezone']*0));///用户当前时区的当前时间

require("lang_".$cf['site_lang'].".php");



$source = $cf['dldj'];//按逗号分离字符串 
$dengjiArr = explode(',',$source); 




$wxid=$_GET[wx];
$sql="select * from tgs_agent where weixin='$wxid' limit 1";
$result=mysql_query($sql);
$arr=mysql_fetch_array($result);
$dengji = $arr['dengji'];
$sjname = $arr['name'];
$key = array_search($dengji, $dengjiArr);
$dengjiOpt = array_slice($dengjiArr, $key);
//print_r($dengjiOpt);
//print_r($key);


?>