<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style>
.head {
	width: 560px;
	height: 385px;
	background-image: url(images/fangweima.jpg);
	margin: 20px;
}
.code {
	padding: 145px 0 0;
	text-align: left;
	margin: 0 auto;
	width: 400px;
	line-height: 22px;
}
.code p {
	line-height: 26px;
	font-size: 20px;
	font-family: Arial, Helvetica, sans-serif;
}
</style>
</HEAD>
<BODY style="background-color:#e0d7d4;">
<div class="head">
  <div class="code">    
<?php
error_reporting(0);
session_start();
header('Content-type: text/html; charset=utf-8');
require("head.php");

    $bianhao = $_GET["bianhao"];		
	$result  = 0;
	$msg0    = 1;	
	if($bianhao != ""){		   
	  if($msg0 == 1){
	   $sql="select * from tgs_code where bianhao='$bianhao' limit 1";
	   $res=mysql_query($sql);
	   if(mysql_num_rows($res)>0){
		   $arr = mysql_fetch_array($res);
		   $bianhao  =  $arr["bianhao"];
		   $riqi     =  $arr["riqi"];
		   $product  =  $arr["product"];
		   $zd1      =  $arr["zd1"];
		   $zd2      =  $arr["zd2"];
		   $query_time  = $arr["query_time"];
		   $hits        = $arr['hits'];		   
		   $results     = 1;
		   $msg1        = str_replace("{{product}}",$product,unstrreplace($cf['notice_1']));
		   if($_SESSION['s_query_time']==""){
			 $_SESSION['s_query_time'] = $query_time;
		   }		   
		   if($hits>0){		
			   $results = 2;
			   $msg1        = str_replace("{{product}}",$product,unstrreplace($cf['notice_2']));
		   }
		    $msg1        = str_replace("{{riqi}}",$riqi,$msg1);
			$msg1        = str_replace("{{zd1}}",$zd1,$msg1);
			$msg1        = str_replace("{{zd2}}",$zd2,$msg1);
			$msg1        = str_replace("{{hits}}",$hits+1,$msg1);
			$msg1        = str_replace("{{query_time}}",$_SESSION['s_query_time'],$msg1);	   
		  mysql_query("update tgs_code set hits=hits+1,query_time='".$GLOBALS['tgs']['cur_time']."' where bianhao='".$bianhao."' limit 1");		  
		  $msg0 = 1;
	   }
	   else
	   {
		 $results = 3;		 
		 $msg1   = str_replace("{{bianhao}}",$bianhao,unstrreplace($cf['notice_3']));
	   }
		$sql = "insert into tgs_history set keyword='".$bianhao."',results='".$results."',addtime='".$GLOBALS['tgs']['cur_time']."',addip='".$GLOBALS['tgs']['cur_ip']."'";
		mysql_query($sql);
	 }	
	}else{
	    $msg1 = "请输入防伪码";
	}
//echo $msg0."|".$msg1;  
 echo $msg1;
?>
</div>
</div>
</BODY>
</HTML>