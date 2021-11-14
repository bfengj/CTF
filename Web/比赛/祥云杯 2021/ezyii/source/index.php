<?php
include("closure/autoload.php");
function myloader($class){
    require_once './class/' . (str_replace('\\', '/', $class) . '.php');
}
spl_autoload_register("myloader"); 
error_reporting(0);
if($_POST['data']){
    unserialize(base64_decode($_POST['data']));
}else{
	echo "<h1>某ii最新的某条链子</h1>";
}