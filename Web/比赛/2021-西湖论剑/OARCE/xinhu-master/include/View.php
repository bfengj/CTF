<?php
if(!isset($ajaxbool))$ajaxbool = $rock->jm->gettoken('ajaxbool', 'false');
$ajaxbool	= $rock->get('ajaxbool', $ajaxbool);
$p			= PROJECT;
if(!isset($m))$m='index';
if(!isset($a))$a='default';
if(!isset($d))$d='';
$m			= $rock->get('m', $m);
$a			= $rock->get('a', $a);
$d			= $rock->get('d', $d);

define('M', $m);
define('A', $a);
define('D', $d);
define('P', $p);

$_m			= $m;
if($rock->contain($m, '|')){
	$_mas 	= explode('|', $m);
	$m 		= $_mas[0];
	$_m		= $_mas[1];
}
include_once($rock->strformat('?0/?1/?1Action.php',ROOT_PATH, $p));
$rand		= date('YmdHis').rand(1000,9999);
if(substr($d,-1)!='/' && $d!='')$d.='/';
$errormsg	= '';
$methodbool	= true;
$actpath	= $rock->strformat('?0/?1/?2?3',ROOT_PATH, $p, $d, $_m);
define('ACTPATH', $actpath);
$actfile	= $rock->strformat('?0/?1Action.php',$actpath, $m);
$actfile1	= $rock->strformat('?0/?1Action.php',$actpath, $_m);
$actbstr 	= null;
if(file_exists($actfile1))include_once($actfile1);
if(file_exists($actfile)){
	include_once($actfile);
	$clsname	= ''.$m.'ClassAction';
	$xhrock		= new $clsname();
	$actname	= ''.$a.'Action';
	if($ajaxbool == 'true')$actname	= ''.$a.'Ajax';
	if(method_exists($xhrock, $actname)){
		$xhrock->beforeAction();
		$actbstr = $xhrock->$actname();
		$xhrock->bodyMessage = $actbstr;
		if(is_string($actbstr)){echo $actbstr;$xhrock->display=false;}
		if(is_array($actbstr)){echo json_encode($actbstr);$xhrock->display=false;}
	}else{
		$methodbool = false;
		if($ajaxbool == 'false')echo ''.$actname.' not found;';
	}
	$xhrock->afterAction();
}else{
	echo 'actionfile not exists;';
	$xhrock		= new Action();
}

$_showbool = false;
if($xhrock->display && ($ajaxbool == 'html' || $ajaxbool == 'false')){
	$xhrock->smartydata['p']	= $p;
	$xhrock->smartydata['a']	= $a;
	$xhrock->smartydata['m']	= $m;
	$xhrock->smartydata['d']	= $d;
	$xhrock->smartydata['rand']	= $rand;
	$xhrock->smartydata['qom']	= QOM;
	$xhrock->smartydata['path']	= PATH;
	$xhrock->smartydata['sysurl']= SYSURL;
	$temppath					= ''.ROOT_PATH.'/'.$p.'/';
	$tplpaths					= ''.$temppath.''.$d.''.$m.'/';
	$tplname					= 'tpl_'.$m.'';
	if($a!='default')$tplname  .= '_'.$a.'';
	$tplname				   .= '.'.$xhrock->tpldom.'';
	$mpathname					= $tplpaths.$tplname;
	if($xhrock->displayfile!='' && file_exists($xhrock->displayfile))$mpathname = $xhrock->displayfile;
	if(!file_exists($mpathname) || !$methodbool){
		if(!$methodbool){
			$errormsg	= 'in ('.$m.') not found Method('.$a.');';
		}else{
			$errormsg	= ''.$tplname.' not exists;';
		}
		echo $errormsg;
	}else{
		$_showbool = true;
	}
}
if($xhrock->display && ($ajaxbool == 'html' || $xhrock->tpltype=='html' || $ajaxbool == 'false') && $_showbool){
	$xhrock->setHtmlData();
	$da = $xhrock->smartydata;
	foreach($xhrock->assigndata as $_k=>$_v)$$_k=$_v;
	include_once($mpathname);
	$_showbool = false;
}