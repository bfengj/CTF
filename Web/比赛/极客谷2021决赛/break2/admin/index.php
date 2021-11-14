<?php
error_reporting(0);
session_start();
require("../data/head.php");
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
<link href="static/h-ui/css/H-ui.min.css" rel="stylesheet" type="text/css" />
<link href="static/h-ui.admin/css/H-ui.login.css" rel="stylesheet" type="text/css" />
<link href="static/h-ui.admin/css/style.css" rel="stylesheet" type="text/css" />
<link href="lib/Hui-iconfont/1.0.7/iconfont.css" rel="stylesheet" type="text/css" />
<!--[if IE 6]>
<script type="text/javascript" src="http://lib.h-ui.net/DD_belatedPNG_0.0.8a-min.js" ></script>
<script>DD_belatedPNG.fix('*');</script>
<![endif]-->
<title>微营销后台系统登陆</title>

</head>
<body>
<?php
$act = $_REQUEST["act"];
if ($act == "adminlogin")
{
  $username = trim($_POST["Username"]);
  $password = trim($_POST["Password"]);
   $sql="select * from tgs_admin where username='".$username."' and password='".md5($password)."'";
   $res=mysql_query($sql);
   $b=mysql_fetch_array($res);
   if(!$b[0])
	{
	     echo "<script>alert('管理员帐户或密码错误,请重新输入!');location.href='index.php';</script>";
	     exit();
    }
    else
    {
		 $_SESSION["Adminnamess"] = $username;
		 mysql_query( "update tgs_admin set logins=logins+1 where username='$username' limit 1");
		  
		 echo "<script>location.href='main.php';</script>"; 
		 exit;
	 }
} 

//退出后台************************************************************

if ($act=="logout")
{
session_unset("Adminnamess");
echo "<script>location.href='index.php';</script>"; 
} 
?>
<div class="header"></div>
<div class="loginWraper">
  <div id="loginform" class="loginBox">
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
    <form name="Login" class="form form-horizontal" action="?act=adminlogin" method="post" onSubmit="return CheckForm();">
      <div class="row cl">
        <label class="form-label col-xs-3"><i class="Hui-iconfont">&#xe60d;</i></label>
        <div class="formControls col-xs-8">
         
          
          <input type="text" name="Username" id="item1" placeholder="账户" class="input-text size-L" autocomplete="off" >
        </div>
      </div>
      <div class="row cl">
        <label class="form-label col-xs-3"><i class="Hui-iconfont">&#xe60e;</i></label>
        <div class="formControls col-xs-8">
          
          <input type="password" name="Password" id="item2" placeholder="密码"  value="" class="input-text size-L" autocomplete="off">
        </div>
      </div>
      <div class="row cl">
        <div class="formControls col-xs-8 col-xs-offset-3">
      </div>
      </div>
      <div class="row cl">
        <div class="formControls col-xs-8 col-xs-offset-3">
         
        </div>
      </div>
      <div class="row cl">
        <div class="formControls col-xs-8 col-xs-offset-3">
          <input name="" type="submit" class="btn btn-success radius size-L" value="&nbsp;登&nbsp;&nbsp;&nbsp;&nbsp;录&nbsp;">
          <input name="" type="reset" class="btn btn-default radius size-L" value="&nbsp;取&nbsp;&nbsp;&nbsp;&nbsp;消&nbsp;">
        </div>
      </div>
    </form>
  </div>
</div>
<div class="footer">Copyright <a href="https://web.1024yf.cn" target="_blank">渔枫网络</a> 证书授权查询管理系统 v6.1</div>
<script type="text/javascript" src="lib/jquery/1.9.1/jquery.min.js"></script> 
<script type="text/javascript" src="static/h-ui/js/H-ui.js"></script> 

</body>
</html>