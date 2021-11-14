<?php
error_reporting(0);
session_start();
require("../data/head.php");
?>
<html><head>

	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

	<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0, maximum-scale=3.0, user-scalable=yes">

	<meta content="yes" name="apple-mobile-web-app-capable">

	<meta content="black" name="apple-mobile-web-app-status-bar-style">

	<meta content="telephone=no" name="format-detection">

<title>经销商登录</title>

    <link rel="stylesheet" href="style_mb/css/Jingle.css?r=20150505">

    <link rel="stylesheet" href="style_mb/css/app.css"> 

   

    <link href="style_mb/css/alertify.core.css" rel="stylesheet" type="text/css">

    <link href="style_mb/css/alertify.default.css" rel="stylesheet" type="text/css">

 

<style>

	#loginHtml {

	  width:90%;

	  margin:10px auto;

	}

</style>

	
<script language=javascript>
<!--
function CheckForm()
{
	if(document.Login.Username.value == "")
	{
		alert("请输入用户名！");
		document.Login.Username.focus();
		return false;
	}
	if(document.Login.Password.value == "")
	{
		alert("请输入密码！");
		document.Login.Password.focus();
		return false;
	}	
}
//-->
</script>
</head>
<body>
<?php
$act = $_REQUEST["act"];
if ($act == "adminlogin")
{
  $username = trim($_POST["Username"]);
  $password = trim($_POST["Password"]);
   $sql="select * from tgs_agent where weixin='".$username."' and password='".$password."' and shzt=1";
   $res=mysql_query($sql);
   $b=mysql_fetch_array($res);
   //echo $sql;

	$result=mysql_query($sql);

	$arr=mysql_fetch_array($result);

	$hmd      = $arr["hmd"];
	$jietime      = $arr["jietime"];
	 $zero1=date("y-m-d h:i:s");   
$zero2=$jietime;  
   if(!$b[0])
	{
	     echo "<script>alert('代理商帐号或密码错误,请重新输入!');location.href='index.php';</script>";
	     exit();
    }
	if($hmd==1)
	{
	     echo "<script>alert('对不起，你已被加入黑名单，无法登录，请联系管理员或上级代理');location.href='index.php';</script>";
	     exit();
    }
	if(strtotime($zero2)<strtotime($zero1))
	{
	     echo "<script>alert('对不起，您的帐号已过期，无法登录，请联系管理员或上级代理');location.href='index.php';</script>";
	     exit();
    }
    else
    {
		 $_SESSION["Adminname"] = $username;
		 mysql_query( "update tgs_admin set logins=logins+1 where username='$username' limit 1");
		  
		 echo "<script>location.href='mbadmin.php';</script>"; 
		 exit;
	 }
} 

//退出后台************************************************************

if ($act=="logout")
{
session_unset("Adminname");
echo "<script>location.href='index.php';</script>"; 
} 
?>

<div id="aside_container">

</div>

<div id="section_container">

    <section id="index_section" class="active"> 

         <header>

            <nav class="left">

                  

            </nav>

            <h1 class="title">

               代理商登录

            </h1>

            <nav class="right">

                <a data-target="section" data-icon="info" href="#">

                    

                </a>

            </nav>



        </header>  

        


 <form name="Login" action="?act=adminlogin" method="post" onSubmit="return CheckForm();">

 <div class="contents">

 			<div class="info_head_img" id="info_head_img">

			     <i class="icon user"></i>

			</div>  

        <div id="loginHtml">

		<input type="text" name="Username" id="item1" class="int" autocomplete="off" placeholder="代理账号（微信号）">
        
<input type="password" name="Password" id="item2"  value="" class="int" autocomplete="off" placeholder="密    码">
		

		 <p style="margin: 10px auto;">

		 <input type="submit" name="B1" value="登录"class="button block" />
        

		 </p>

		 </div>

 </div>

 </form>



    </section>



 <script language="javascript">

function Search()

{

if(FrmSearch.UserName.value==""){

alert('请输入用户名！');

FrmSearch.UserName.focus();

return false;

}



if(FrmSearch.UserPwd.value==""){

alert('请输入密码！');

FrmSearch.UserPwd.focus();

return false;

}

return true;

}

</script>



    

</div>

</body>
</html>