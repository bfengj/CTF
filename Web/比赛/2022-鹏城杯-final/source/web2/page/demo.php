<?php 
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
