<?php
error_reporting(0);
session_start();
header('Content-type: text/html; charset=utf-8');
require("data/head.php");
$act = isset($_REQUEST['act']) ? trim($_REQUEST['act']) : 'agent';
 if($act == "agent"){
	 $result_str = unstrreplace($cf['agents']);
	 ////模板选择
	 if($_REQUEST['themes']!=""){
	   $_SESSION['ag_themes'] = $_REQUEST['themes'];	 
	 }else if($_SESSION['ag_themes'] == ""){
	   $_SESSION['ag_themes'] = $cf['agent_themes'];
	 }
	 require("themes/".$cf['agent_themes']."/index.php");
	 echo "<!--Power by http://www.weiyyong.com-->";
 }
 
?>