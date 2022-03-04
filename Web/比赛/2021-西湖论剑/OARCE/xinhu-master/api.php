<?php
/**
*	api的入口地址请求访问，访问方法：http://我的域名/api.php?m=index&a=方法
*	主页：http://www.rockoa.com/
*	软件：信呼
*	作者：雨中磐石(rainrock)
*/
define('ENTRANCE', 'api');
include_once('config/config.php');
$_paths = '';
$d		= 'task';$m	= 'index';$a = 'index';
if(isset($_GET['m'])){
	$m  = $rock->get('m');
	$a  = $rock->get('a', $a);
}else{
	if(isset($_SERVER['PHP_SELF']))$_paths=$_SERVER['PHP_SELF'];
	if($_paths==''&&isset($_SERVER['ORIG_PATH_INFO']))$_paths=$_SERVER['ORIG_PATH_INFO'];
	$_patha = explode('api.php', $_paths);
	$_paths = '/index/index';
	if(isset($_patha[1])){
		$_paths = $_patha[1];
	}else{
		if(isset($_SERVER['PATH_INFO']))$_paths=$_SERVER['PATH_INFO'];
	}
}
unset($_GET['d']);
unset($_GET['m']);
unset($_GET['a']);
if($_paths){
	$_pa = explode('/', $_paths);
	if(isset($_pa[1])&&$_pa[1])$m=$_pa[1];
	if(isset($_pa[2])&&$_pa[2])$a=$_pa[2];
}
if(substr($m,0,4)=='open'){
	$m 	= ''.$m.'|openapi';
}else{
	$m 	= ''.$m.'|api';
}
include_once('include/View.php');