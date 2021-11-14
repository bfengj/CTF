<?php
error_reporting(0);
session_start();
header('Content-type: text/html; charset=utf-8');
require("data/head.php");
$act = isset($_REQUEST['act']) ? trim($_REQUEST['act']) : 'agent';
 if($act == "agent"){
	 $result_str = unstrreplace($cf['agents']);
	 ////模板选择
 }
 ////ajax 查询返回
 if($act == "query"){ 
	$keyword = trim($_GET["keyword"]);	
	$show = trim($_GET["show"]);		
	$search  = $_GET['search'];
	$yzm     = trim($_GET['yzm']);
	$result  = 0;
	$msg0    = 1;	
    //输入不为空
	if($keyword != ""){
	  if($cf['yzm_status'] == 1 && $yzm == ""){
		 $msg1 = "请输入验证码";
		 $msg0 = 0;		
	  }
	  if($cf['yzm_status'] == 1 && $yzm != $_SESSION['authnum_session']){
		 $msg1 = "验证码输入错误";
		 $msg0 = 0;
	  }      
	  if($msg0 == 1){
	  $sql="select * from tgs_agent where $show='$keyword' and shzt='1' limit 1";
	   $res=mysql_query($sql);
	   if(mysql_num_rows($res)>0){
		  $arr = mysql_fetch_array($res);
		  $agentid  =  $arr["agentid"];
		  $idcard  =  $arr["idcard"];
		  $product  =  $arr["product"];
		  $dengji  =  $arr["dengji"];
		  $quyu     =  $arr["quyu"];
		  $shuyu    =  $arr["shuyu"];   
		  $qudao    =  $arr["qudao"];
		  $url      =  $arr["url"];
		  $about    =  $arr["about"];
		  $addtime  =  $arr["addtime"];
		  $jietime  =  $arr["jietime"];		   
		  $name     =  $arr["name"];
		  $tel      =  $arr["tel"];
		  $fax      =  $arr["fax"];
		  $phone    =  $arr["phone"];
		  $danwei   =  $arr["danwei"];		   
		  $email    =  $arr["email"];
		  $qq       =  $arr["qq"];
		  $weixin   =  $arr["weixin"];
		  $wangwang =  $arr["wangwang"];
		  $paipai   =  $arr["paipai"];
		  $zip      =  $arr["zip"];
		  $dizhi    =  $arr["dizhi"];
		  $beizhu   =  $arr["beizhu"];		   
		  $query_time  = $arr["query_time"];
		  $hits        = $arr['hits'];	
		   $hmd        = $arr['hmd'];		   
		  $results     = 1;
		  $msg1        = str_replace("{{weixin}}",$weixin,unstrreplace($cf['agent_1']));
		   
		  if($_SESSION['s_query_time']==""){
			 $_SESSION['s_query_time'] = $query_time;
		   }	
		   ////非第一次查询	   
		   if($hmd==1){			
			   $results = 2;
			   $msg1    = str_replace("{{weixin}}",$weixin,unstrreplace($cf['agent_2']));
			 
		   }
		    $msg1        = str_replace("{{agentid}}",$agentid,$msg1);
			$msg1        = str_replace("{{idcard}}",$idcard,$msg1);
			$msg1        = str_replace("{{product}}",$product,$msg1);
			$msg1        = str_replace("{{dengji}}",$dengji,$msg1);
			$msg1        = str_replace("{{quyu}}",$quyu,$msg1);
			$msg1        = str_replace("{{shuyu}}",$shuyu,$msg1);
			$msg1        = str_replace("{{qudao}}",$qudao,$msg1);
			$msg1        = str_replace("{{url}}",$url,$msg1);
			$msg1        = str_replace("{{about}}",$about,$msg1);
			$msg1        = str_replace("{{addtime}}",$addtime,$msg1);
			$msg1        = str_replace("{{jietime}}",$jietime,$msg1);
			$msg1        = str_replace("{{name}}",$name,$msg1);
			$msg1        = str_replace("{{tel}}",$tel,$msg1);
			$msg1        = str_replace("{{fax}}",$fax,$msg1);
			$msg1        = str_replace("{{phone}}",$phone,$msg1);
			$msg1        = str_replace("{{danwei}}",$danwei,$msg1);
			$msg1        = str_replace("{{email}}",$email,$msg1);
			$msg1        = str_replace("{{qq}}",$qq,$msg1);
			$msg1        = str_replace("{{weixin}}",$weixin,$msg1);
			$msg1        = str_replace("{{wangwang}}",$wangwang,$msg1);
			$msg1        = str_replace("{{paipai}}",$paipai,$msg1);
			$msg1        = str_replace("{{zip}}",$zip,$msg1);
			$msg1        = str_replace("{{dizhi}}",$dizhi,$msg1);
			$msg1        = str_replace("{{beizhu}}",$beizhu,$msg1);
			$msg1        = str_replace("{{hits}}",$hits+1,$msg1);			
			$msg1        = str_replace("{{query_time}}",$_SESSION['s_query_time'],$msg1);			    
		  mysql_query("update tgs_agent set hits=hits+1,query_time='".$GLOBALS['tgs']['cur_time']."' where weixin='".$keyword."' limit 1");		  
		  $msg0 = 1;
	   }else{
		 $results = 3;		 
		 $msg1   = str_replace("{{keyword}}",$weixin,unstrreplace($cf['agent_3']));
		
	   }
	   ///保存查询记录
	   $sql = "insert into tgs_hisagent set keyword='".$keyword."',results='".$results."',addtime='".$GLOBALS['tgs']['cur_time']."',addip='".$GLOBALS['tgs']['cur_ip']."'";
	   mysql_query($sql);
	  }	
	}else{
	    $msg1 = "请输入微信号！";
	}
	
 }

if ( $results == 1)
{
ob_clean();         

$myImage = ImageCreate(700,990); //参数为宽度和高度    
$myImage = imagecreatefrompng('zs001.png');           // 证书模版图片文件的路径 
$white=ImageColorAllocate($myImage, 255, 255, 255); 

 $black=ImageColorAllocate($myImage, 0, 0, 0); 

 $red=ImageColorAllocate($myImage, 255, 0, 0); 

 $green=ImageColorAllocate($myImage, 0, 255, 0); 

 $blue=ImageColorAllocate($myImage, 0, 0, 255);

 imagettftext($myImage, 16, 0, 140, 400, $black, "simhei.ttf",  "$msg1");
header("Content-type:image/png"); 

ImagePng($myImage);
ImageDestroy($myImage);
}

?>