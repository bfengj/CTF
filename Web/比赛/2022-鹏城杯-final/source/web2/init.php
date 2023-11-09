<?php
include_once("config.php");
$js = $_SESSION['juese'];

$sql = "select value,bm,shanchu,xiugai from juese where id=$js";
$requ = mysqli_query($con,$sql);
$rs = mysqli_fetch_array($requ);
$js = $rs['value'];
//$bm = $rs['bm'];

//$_SESSION['bm'] = $bm;
$_SESSION['shanchu'] = $rs['shanchu'];
$_SESSION['xiugai'] = $rs['xiugai'];

$js = rtrim($js,',');
$js = explode(',',$js);


$s = getSystemInit();
echo($s);

// 获取初始化数据
function getSystemInit(){
	$homeInfo = [
		'title' => '首页',
		'href'  => 'page/welcome-3.html?t=1',
	];
	$logoInfo = [
		'title' => '资产管理',
		'image' => 'images/logo2.png',
		'href'  => ''
	];
	$menuInfo = getMenuList();
	$systemInit = [
		'homeInfo' => $homeInfo,
		'logoInfo' => $logoInfo,
		'menuInfo' => $menuInfo,
	];
	return json_encode($systemInit);
}

// 获取菜单列表
function getMenuList(){
	global $con;
	global $js;
	$sql = "select id,pid,title,icon,href,target from system_menu where status=1 order by sort desc";
	mysqli_query($con, 'SET NAMES UTF8');
	$requ = mysqli_query($con,$sql);
	$menuList = array();
	while($rs = mysqli_fetch_array($requ)){
		if(in_array($rs['id'],$js)){
			$a=array("id"=>$rs['id'],"pid"=>$rs['pid'],"title"=>$rs['title'],
					 "icon"=>$rs['icon'],"href"=>$rs['href'],
					 "target"=>$rs['target']);
			array_push($menuList,$a);
		}
	}
	$menuList = buildMenuChild(0, $menuList);
	return $menuList;
}

//递归获取子菜单
function buildMenuChild($pid, $menuList){
	$treeList = [];
	foreach ($menuList as $v) {
		if ($pid == $v['pid']) {
			$node = $v;
			$child = buildMenuChild($v['id'], $menuList);
			if (!empty($child)) {
				$node['child'] = $child;
			}
			$treeList[] = $node;
		}
	}
	return $treeList;
}
















