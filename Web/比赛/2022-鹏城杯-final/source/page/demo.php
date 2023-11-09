<?php 
//检查登录状态
    session_start();
    if(!isset($_SESSION['user'])){die('请登录！！！');}
include_once("../config.php");
   $juese = $_SESSION['juese'];
   $sql="select value from juese where id='$juese'";
   $requ=mysqli_query($con,$sql);
   $rs=mysqli_fetch_array($requ);
   $value=explode(",",$rs['value']);
   if (in_array('17',$value)== false) 
   {
    die('您没有权限访问该页面！！！');
   }
require('PHPTree.class.php');
include_once("../config.php");
$sql = "select id,pid,title from system_menu where status=1 order by sort desc";
$requ = mysqli_query($con,$sql);
$menuList = array();
while($rs = mysqli_fetch_array($requ)){
	$a=array("id"=>$rs['id'],"pId"=>$rs['pid'],"name"=>$rs['title']);
	array_push($menuList,$a);
}



$r = PHPTree::makeTreeForHtml($menuList,array(
	'expanded' => true
));
echo json_encode($r);
?>
