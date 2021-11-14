<?php

include 'head_sq.php';

$act = $_GET["act"];


////添加代理信息

if($act == "Addagent")

{   

    $agentid      = trim($_REQUEST["agentid"]);
	$idcard      = trim($_REQUEST["idcard"]);
	$dengji      = trim($_REQUEST["dengji"]);

	$product      = trim($_REQUEST["product"]);

	$quyu         = trim($_REQUEST["quyu"]);
	
	$applytime         = trim($_REQUEST["applytime"]);

	$shuyu        = trim($_REQUEST["shuyu"]);

	$url          = strreplace(trim($_REQUEST["url"]));

	$qudao        = trim($_REQUEST["qudao"]);

	$about        = strreplace(trim($_REQUEST["about"]));

	$addtime      = trim($_REQUEST["addtime"]);

	$jietime      = trim($_REQUEST["jietime"]);

	$name         = trim($_REQUEST["name"]);

	$tel          = trim($_REQUEST["tel"]);

	$fax          = trim($_REQUEST["fax"]);

	$phone        = trim($_REQUEST["phone"]);

	$danwei       = trim($_REQUEST["danwei"]);

	$email        = trim($_REQUEST["email"]);	

	$qq           = trim($_REQUEST["qq"]);

	$weixin       = trim($_REQUEST["weixin"]);

	$wangwang     = trim($_REQUEST["wangwang"]);

	$paipai       = trim($_REQUEST["paipai"]);
	
	$sjdl       = trim($_REQUEST["sjdl"]);
	
	$shzt       = 2;
	
	$hmd       = 2;
	$password       = trim($_REQUEST["password"]);

	$zip          = trim($_REQUEST["zip"]);

	$dizhi        = strreplace(trim($_REQUEST["dizhi"]));

	$beizhu       = strreplace(trim($_REQUEST["beizhu"]));	
	$sql="select * from tgs_agent where weixin='$sjdl' limit 1";

	//echo $sql;

	$result=mysql_query($sql);

	$arr=mysql_fetch_array($result);

	$sjdlid      = $arr["id"];
	if(empty($sjdl)){
}
else{

	if($sjdlid=="")

	{

	  echo "<script>alert('上级代理微信号不存在，请检查');history.go(-1);</script>";

	  exit;

	}
}


	if($agentid=="")

	{

	  echo "<script>alert('代理商编号不存在，请检查');history.go(-1);</script>";

	  exit;

	}
	if($name=="")

	{

	  echo "<script>alert('请输入姓名');history.go(-1);</script>";

	  exit;

	}
	if($weixin=="")

	{

	  echo "<script>alert('请输入微信号');history.go(-1);</script>";

	  exit;

	}
	if($phone=="")

	{

	  echo "<script>alert('请输入手机号码');history.go(-1);</script>";

	  exit;

	}

	$sql = "select id from tgs_agent where agentid='".$agentid."' limit 1";

	$res = mysql_query($sql);

	$arr = mysql_fetch_array($res);

	if(mysql_num_rows($res)>0){

	  echo "<script>alert('代理商编号已存在');history.go(-1);</script>";

	  exit;

	}
	
	$sql = "select id from tgs_agent where weixin='".$weixin."' limit 1";

	$res = mysql_query($sql);

	$arr = mysql_fetch_array($res);

	if(mysql_num_rows($res)>0){

	  echo "<script>alert('微信号已存在');history.go(-1);</script>";

	  exit;

	}
	
	$sql = "select id from tgs_agent where phone='".$phone."' limit 1";

	$res = mysql_query($sql);

	$arr = mysql_fetch_array($res);

	if(mysql_num_rows($res)>0){

	  echo "<script>alert('手机号已存在');history.go(-1);</script>";

	  exit;

	}
	
	
if(empty($idcard)){
}
else{
if(strlen($idcard)== 18){    
    //验证通过    
         
}else{    
  echo "<script>alert('身份证号格式不正确');history.go(-1);</script>";
  
   exit;
         
   }
}
	

if(empty($phone)){
}
else{
if(strlen($phone)== 11){    
    //验证通过    
         
}else{    
  echo "<script>alert('手机号格式不正确');history.go(-1);</script>";
  
   exit;
         
}}
	

	$sql="insert into tgs_agent (agentid,idcard,dengji,product,quyu,applytime,shuyu,qudao,about,addtime,jietime,name,tel,fax,phone,danwei,email,url,qq,weixin,wangwang,paipai,zip,dizhi,beizhu,sjdl,shzt,hmd,password)values('$agentid','$idcard','$dengji','$product','$quyu','$applytime','$shuyu','$qudao','$about','$addtime','$jietime','$name','$tel','$fax','$phone','$danwei','$email','$url','$qq','$weixin','$wangwang','$paipai','$zip','$dizhi','$beizhu','$sjdlid','$shzt','$hmd','$password')";

	//$sql="insert into tgs_agent set agentid = '	".$agentid."',product='".$product."',quyu='".$quyu."',shuyu='".$shuyu."',qudao='".$qudao."',about='".$about."',addtime='".$addtime."',jietime='".$jietime."',name='".$name."',tel='".$tel."',fax='".$fax."',phone='".$phone."',danwei='".$danwei."',email='".$email."',url='".$url."',qq='".$qq."',weixin='".$weixin."',wangwang='".$wangwang."',paipai='".$paipai."',zip='".$zip."',dizhi='".$dizhi."',beizhu='".$beizhu."'";



	mysql_query($sql);

	echo "<script>alert('申请成功！');location.href='../AddInfo.php'</script>";

	exit;

}



////前台申请代理专用

if($act == "apply")

{   

    $agentid      = trim($_REQUEST["agentid"]);
	$idcard      = trim($_REQUEST["idcard"]);
	$dengji      = trim($_REQUEST["dengji"]);

	$product      = trim($_REQUEST["product"]);

	$quyu         = trim($_REQUEST["quyu"]);
	$applytime         = trim($_REQUEST["applytime"]);

	$shuyu        = trim($_REQUEST["shuyu"]);

	$url          = strreplace(trim($_REQUEST["url"]));

	$qudao        = trim($_REQUEST["qudao"]);

	$about        = strreplace(trim($_REQUEST["about"]));

	$addtime      = trim($_REQUEST["addtime"]);

	$jietime      = trim($_REQUEST["jietime"]);

	$name         = trim($_REQUEST["name"]);

	$tel          = trim($_REQUEST["tel"]);

	$fax          = trim($_REQUEST["fax"]);

	$phone        = trim($_REQUEST["phone"]);

	$danwei       = trim($_REQUEST["danwei"]);

	$email        = trim($_REQUEST["email"]);	

	$qq           = trim($_REQUEST["qq"]);

	$weixin       = trim($_REQUEST["weixin"]);

	$wangwang     = trim($_REQUEST["wangwang"]);

	$paipai       = trim($_REQUEST["paipai"]);
	
	$sjdl       = trim($_REQUEST["sjdl"]);
	
	$shzt       = 2;
	
	$hmd       = 2;
	$password       = trim($_REQUEST["password"]);

	$zip          = trim($_REQUEST["zip"]);

	$dizhi        = strreplace(trim($_REQUEST["dizhi"]));

	$beizhu       = strreplace(trim($_REQUEST["beizhu"]));	
	$sql="select * from tgs_agent where weixin='$sjdl' limit 1";

	//echo $sql;

	$result=mysql_query($sql);

	$arr=mysql_fetch_array($result);

	$sjdlid      = $arr["id"];
	if(empty($sjdl)){
}
else{

	if($sjdlid=="")

	{

	  echo "<script>alert('上级代理微信号不存在，请检查');history.go(-1);</script>";

	  exit;

	}
}


	if($agentid=="")

	{

	  echo "<script>alert('代理商编号不存在，请检查');history.go(-1);</script>";

	  exit;

	}
	if($name=="")

	{

	  echo "<script>alert('请输入姓名');history.go(-1);</script>";

	  exit;

	}
	if($weixin=="")

	{

	  echo "<script>alert('请输入微信号');history.go(-1);</script>";

	  exit;

	}
	if($phone=="")

	{

	  echo "<script>alert('请输入手机号码');history.go(-1);</script>";

	  exit;

	}

	$sql = "select id from tgs_agent where agentid='".$agentid."' limit 1";

	$res = mysql_query($sql);

	$arr = mysql_fetch_array($res);

	if(mysql_num_rows($res)>0){

	  echo "<script>alert('代理商编号已存在');history.go(-1);</script>";

	  exit;

	}
	
	$sql = "select id from tgs_agent where weixin='".$weixin."' limit 1";

	$res = mysql_query($sql);

	$arr = mysql_fetch_array($res);

	if(mysql_num_rows($res)>0){

	  echo "<script>alert('微信号已存在');history.go(-1);</script>";

	  exit;

	}
	
	$sql = "select id from tgs_agent where phone='".$phone."' limit 1";

	$res = mysql_query($sql);

	$arr = mysql_fetch_array($res);

	if(mysql_num_rows($res)>0){

	  echo "<script>alert('手机号已存在');history.go(-1);</script>";

	  exit;

	}
	
	
if(empty($idcard)){
}
else{
if(strlen($idcard)== 18){    
    //验证通过    
         
}else{    
  echo "<script>alert('身份证号格式不正确');history.go(-1);</script>";
  
   exit;
         
   }
}
	

if(empty($phone)){
}
else{
if(strlen($phone)== 11){    
    //验证通过    
         
}else{    
  echo "<script>alert('手机号格式不正确');history.go(-1);</script>";
  
   exit;
         
}}
	

	$sql="insert into tgs_agent (agentid,idcard,dengji,product,quyu,applytime,shuyu,qudao,about,addtime,jietime,name,tel,fax,phone,danwei,email,url,qq,weixin,wangwang,paipai,zip,dizhi,beizhu,sjdl,shzt,hmd,password)values('$agentid','$idcard','$dengji','$product','$quyu','$applytime','$shuyu','$qudao','$about','$addtime','$jietime','$name','$tel','$fax','$phone','$danwei','$email','$url','$qq','$weixin','$wangwang','$paipai','$zip','$dizhi','$beizhu','$sjdlid','$shzt','$hmd','$password')";

	//$sql="insert into tgs_agent set agentid = '	".$agentid."',product='".$product."',quyu='".$quyu."',shuyu='".$shuyu."',qudao='".$qudao."',about='".$about."',addtime='".$addtime."',jietime='".$jietime."',name='".$name."',tel='".$tel."',fax='".$fax."',phone='".$phone."',danwei='".$danwei."',email='".$email."',url='".$url."',qq='".$qq."',weixin='".$weixin."',wangwang='".$wangwang."',paipai='".$paipai."',zip='".$zip."',dizhi='".$dizhi."',beizhu='".$beizhu."'";



	mysql_query($sql);

	echo "<script>alert('申请成功！');location.href='../apply.php?wx=$sjdl'</script>";

	exit;

}


?>

</body>

</html>