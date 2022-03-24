<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Smarty calculator</title>
</head>
<body background="img/1.jpg">
<div align="center">
    <h1>Smarty calculator</h1>
</div>
<div style="width:100%;text-align:center">
    <form action="" method="POST">
        <input type="text" style="width:150px;height:30px" name="data" placeholder="      输入值进行计算" value="">
        <br>
        <input type="submit" value="Submit">
    </form>
</div>
</body>
</html>
<?php
error_reporting(0);
include_once('./Smarty/Smarty.class.php');
$smarty = new Smarty();
$my_security_policy = new Smarty_Security($smarty);
$my_security_policy->php_functions = null;
$my_security_policy->php_handling = Smarty::PHP_REMOVE;
$my_security_policy->php_modifiers = null;
$my_security_policy->static_classes = null;
$my_security_policy->allow_super_globals = false;
$my_security_policy->allow_constants = false;
$my_security_policy->allow_php_tag = false;
$my_security_policy->streams = null;
$my_security_policy->php_modifiers = null;
$smarty->enableSecurity($my_security_policy);

function waf($data){
  $pattern = "php|\<|flag|\?";
  $vpattern = explode("|", $pattern);
  foreach ($vpattern as $value) {
        if (preg_match("/$value/", $data)) {
		  echo("<div style='width:100%;text-align:center'><h5>Calculator don  not like U<h5><br>");
          die();
        }
    }
    return $data;
}

if(isset($_POST['data'])){
  if(isset($_COOKIE['login'])) {
      $data = waf($_POST['data']);
      echo "<div style='width:100%;text-align:center'><h5>Only smarty people can use calculators:<h5><br>";
      $smarty->display("string:" . $data);
  }else{
      echo "<script>alert(\"你还没有登录\")</script>";
  }
}