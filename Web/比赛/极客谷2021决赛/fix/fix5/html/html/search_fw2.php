<?php
error_reporting(0);
session_start();
header('Content-type: text/html; charset=utf-8');
require("data/head.php");
$act = "query";

 ////ajax 查询返回
 if($act == "query"){ 
	$bianhao = trim($_GET["bianhao"]);	
	$search  = $_GET['search'];
	$yzm     = trim($_GET['yzm']);
	$result  = 0;//搜索结查默认值 1=正确搜索到结果,2=搜索到结果但非第一次,3=没搜索到结果,4=
	$msg0    = 1;	
	/*
	for($i=1;$i<=$cf['text_type'];$i++)
	{
	  $bianhao .= trim($_GET["bianhao".$i])
	}
	*/

	if($bianhao != ""){
		if($cf['yzm_status'] == 1 && $yzm == "")
		{
		 $msg1 = "请输入验证码";
		 $msg0 = 0;		
		}
		if($cf['yzm_status'] == 1 && $yzm != $_SESSION['authnum_session'])
		{
		 $msg1 = "验证码输入错误";
		 $msg0 = 0;
		}      
	  if($msg0 == 1){
		///防伪码信息
	   $sql="select * from tgs_code where bianhao='$bianhao' limit 1";
	   ///echo $sql;
	   $res=mysql_query($sql);
	   if(mysql_num_rows($res)>0){
		   $arr = mysql_fetch_array($res);
		   
		   $bianhao  =  $arr["bianhao"];
		   $riqi     =  $arr["riqi"];
		   $product  =  $arr["product"];
		   $zd1      =  $arr["zd1"];
		   $zd2      =  $arr["zd2"];
		   $query_time  = $arr["query_time"];
		    $query_ip  = $arr["query_ip"];
		   $hits        = $arr['hits'];		   
		   $results     = 1;
		    date_default_timezone_set('Asia/Chongqing'); 
		   $cxtime  = date('Y-m-d H:i:s',time());
		   $msg1        = str_replace("{{product}}",$product,unstrreplace($cf['notice_1']));
		   if($_SESSION['s_query_time']==""){
			 $_SESSION['s_query_time'] = $query_time;
		   }		   
		   if($hits>0){////非第一次查询			
			   $results = 2;
			   $msg1        = str_replace("{{product}}",$product,unstrreplace($cf['notice_2']));
		   }

		    $msg1        = str_replace("{{riqi}}",$riqi,$msg1);
			$msg1        = str_replace("{{zd1}}",$zd1,$msg1);
			$msg1        = str_replace("{{zd2}}",$zd2,$msg1);
			$msg1        = str_replace("{{hits}}",$hits+1,$msg1);
			$msg1        = str_replace("{{query_time}}",$query_time,$msg1);	   
		   mysql_query("update tgs_code set hits=hits+1,query_ip='".$GLOBALS['tgs']['cur_ip']."',query_time='".$cxtime."' where bianhao='".$bianhao."' limit 1");	  
		  $msg0 = 1;
	   }
	   else
	   {
		 $results = 3;		 
		 $msg1   = str_replace("{{bianhao}}",$bianhao,unstrreplace($cf['notice_3']));
	   }
	      ///保存查询记录
		$sql = "insert into tgs_history set keyword='".$bianhao."',results='".$results."',addtime='".$GLOBALS['tgs']['cur_time']."',addip='".$GLOBALS['tgs']['cur_ip']."'";
		mysql_query($sql);
	    //$msg1 = eregi_replace("[\]",'',$msg1);
	 }	
	}else{
	    $msg1 = "请输入防伪码";
	}
 }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>查询结果</title>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0">
    
      <link rel="stylesheet" type="text/css" href="themes/<?=$cf['site_themes']?>/style/css/show.css">
      <style>
      @charset "utf-8";

@media only screen and (min-width:801px) {
    .body {
        max-width: 320px;
        margin: 0px auto;
    }
}

html,body {
    color: #000;
    font-family: "微软雅黑","宋体","SimSun","Arial Narrow";
    -webkit-tap-highlight-color: rgba(0,0,0,0);
    -webkit-font-smoothing: antialiased;
    background-color: #EEEEEE;
}

body,div,dl,dt,dd,ul,ol,li,h1,h2,h3,h4,h5,h6,pre,code,form,fieldset,legend,input,button,textarea,p,blockquote,th,td,img {
    margin: 0;
    padding: 0;
    border: 0;
    line-height: 100%;
}

a {
    text-decoration: none;
}

a:focus {
    outline: none;
}

fieldset,img {
    border: 0;
}

table {
    border-collapse: collapse;
    border-spacing: 0;
}

address,caption,cite,code,dfn,em,strong,th,var,optgroup {
    font-style: inherit;
    font-weight: inherit;
}

del,ins {
    text-decoration: none;
}

ol,ul,li {
    list-style: none;
}

caption,th {
    text-align: left;
}

h1,h2,h3,h4,h5,h6 {
    font-size: 100%;
    font-weight: normal;
}

q:before,q:after {
    content: '';
}

abbr,acronym {
    border: 0;
    font-variant: normal;
}

sup {
    vertical-align: baseline;
}

sub {
    vertical-align: baseline;
}

input,button,textarea,select,optgroup,option {
    font-family: inherit;
    font-size: inherit;
    font-style: inherit;
    font-weight: inherit;
}

input,button,textarea,select {
    *font-size: 100%;
}

.clear {
    clear: both;
}

input::-moz-focus-inner {
    border: 0;
    padding: 0;
}

input[type=button],input[type=submit] {
    -webkit-appearance: button;
}

.clearfix:after {
    content: ".";
    display: block;
    height: 0;
    clear: both;
    visibility: hidden;
}

.clearfix {
    zoom: 1;
}

input:focus {
    outline: none;
}

.show {
    width: 100%;
}

.data_warp {
    padding: 30px 18px;
    font-size: 14px;
}

.data_warp p {
    line-height: 1.5
}

.data_warp img {
    display: block;
    margin: 0px auto;
    padding: 22px 0px 50px 0px
}

.user_input {
    width: 100%;
    height: 42px;
    line-height: 40px;
    padding: 6px 12px;
    box-sizing: border-box;
    border: 1px solid #000;
    font-size: 14px;
    background-color: transparent;
    margin-top: 18px;
    margin-bottom: 27px;
}

.user_click {
    display: block;
    width: 50%;
    height: 50px;
    text-align: center;
    line-height: 50px;
    background-color: #1D1C1B;
    color: #FFF;
    font-size: 14px;
    margin: 0px auto
}

.result {
    font-size: 18px;
    font-weight: bold;
    padding: 42px 25px 21px 25px;
}

.tesult_data {
    padding: 0px 25px 21px 25px;
    font-size: 14px;
}

.tesult_data p {
    line-height: 1.2
}

.hcolor {
    color: #FE0101
}
      </style>
</head>
<body>
<div class="body" id="failure">

 <p class="result hcolor">查询结果</p>
    <div class="tesult_data">
        <p><?php echo $msg1; ?></p>
    </div>
   
</div>
</body>
<script src="themes/<?=$cf['site_themes']?>/style/js/jquery.min.js?v=1.8.3"></script>
</html>