<?php
/**
*	来自：信呼开发团队
*	作者：磐石(rainrock)
*	网址：http://www.rockoa.com/
*	系统默认配置文件，请不要去修改
*	要修改配置文件在：webmain/webmainConfig.php
*/
@session_start();
if(function_exists('date_default_timezone_set'))date_default_timezone_set('Asia/Shanghai'); //设置默认时区
header('Content-Type:text/html;charset=utf-8');
define('ROOT_PATH',str_replace('\\','/',dirname(dirname(__FILE__))));	//系统跟目录路径

include_once(''.ROOT_PATH.'/include/rockFun.php');
include_once(''.ROOT_PATH.'/include/Chajian.php');
include_once(''.ROOT_PATH.'/include/class/rockClass.php');
$rock 		= new rockClass();

$db			= null;		
$smarty		= false;
define('HOST', $rock->host);
define('REWRITE', 'true');
if(!defined('PROJECT'))define('PROJECT', $rock->get('p', 'webmain'));
if(!defined('ENTRANCE'))define('ENTRANCE', 'index');

$config		= array(
	'title'		=> '信呼',
	'url'		=> '',
	'urly'		=> 'http://www.rockoa.com/',	//官网域名地址，修改后就无法提供在线升级了。
	'db_host'	=> '127.0.0.1',
	'db_user'	=> 'root',
	'db_pass'	=> '',
	'db_base'	=> '',
	'perfix'	=> '',
	'qom'		=> '',
	'highpass'	=> '',
	'install'	=> false,
	'version'	=> require('version.php'),
	'path'		=> 'index',
	'updir'		=> 'upload',
	'dbencrypt'	=> false,
	'sqllog'	=> false,
	'checksign'	=> false,			//列表请求是否验证
	'memory_limit'	=> '',			//运行内存大小
	'timeout'		=> -1,			//抄送时间(秒),-1默认的
	'db_drive'		=> 'mysqli',	//数据库操作驱动
	'db_engine'		=> 'MyISAM',	//数据库默认引擎
	'debug'			=> true,	//默认debug模式
	'reim_show' 	=> true,	//首页是否显示REIM
	'mobile_show' 	=> true,	//首页是否显示手机版
	'accesslogs' 	=> false,	//是否记录访问日志和限制IP
	'upurl'			=> '', 		//上传文件附件地址(还不能使用)
	'authorkey'		=> '', 		//系统授权的key，请联系官网获取
	'companymode'	=> false 	//是否开启多单位模式切换
);

//引入配置文件
$_confpath		= $rock->strformat('?0/?1/?1Config.php', ROOT_PATH, PROJECT);
if(file_exists($_confpath)){
	$_tempconf	= require($_confpath);
	foreach($_tempconf as $_tkey=>$_tvs)$config[$_tkey] = $_tvs;
	if(isempt($config['url']))$config['url'] = $rock->url();
	if(!isempt($config['memory_limit']) && function_exists('ini_set'))
		ini_set('memory_limit', $config['memory_limit']);
	if($config['timeout']>-1 && function_exists('set_time_limit'))set_time_limit($config['timeout']);	
}

$_confpath	= ''.ROOT_PATH.'/config/author.php';
if(file_exists($_confpath)){
	$config['authorkey'] = require($_confpath);
}

$_confpath	= ''.ROOT_PATH.'/config/xinhuplat.php';
if(file_exists($_confpath))include_once($_confpath);
if(!defined('COMPANYNUM'))define('COMPANYNUM', '');

define('DEBUG', $config['debug']);
error_reporting(DEBUG ? E_ALL : 0);

define('TITLE', $config['title']);
define('URL', $config['url']);
define('URLY', $config['urly']);
define('PATH', $config['path']);

define('DB_DRIVE', $config['db_drive']);
define('DB_HOST', $config['db_host']);
define('DB_USER', $config['db_user']);
define('DB_PASS', $config['db_pass']);
define('DB_BASE', $config['db_base']);

define('UPDIR', $config['updir']);
define('PREFIX', $config['perfix']);
define('QOM', $config['qom']);
define('VERSION', $config['version']);
define('HIGHPASS', $config['highpass']);
define('SYSURL', ''.URL.PATH.'.php');
define('UPURL', $config['upurl']);
define('ISMORECOM', $config['companymode']);

$_confpath	= ''.ROOT_PATH.'/config/iplogs.php'; //这个用来限制IP访问的
if(file_exists($_confpath) && PHP_SAPI != 'cli')include_once($_confpath);
$rock->initRock();