<?php
set_time_limit(90);
class installClassAction extends ActionNot{
	
	public function initAction()
	{
		if(getconfig('systype')=='demo')exit('');
	}
	
	public function defaultAction()
	{
		$this->tpltype	= 'html';
		$this->title	= TITLE.'_安装';
		$dbdiz = '127.0.0.1';
		$paths = '../../mysql5.6.10/my.ini';
		if(@file_exists($paths)){
			$_conts = @file_get_contents($paths);
			if($_conts && contain($_conts,'3307'))$dbdiz.=':3307';
		}
		$this->assign('dbdiz', $dbdiz);
	}
	
	private function rmdirs($dir){
        $dir_arr = scandir($dir);
        foreach($dir_arr as $key=>$val){
            if($val == '.' || $val == '..'){
			}else{               
                @unlink($dir.'/'.$val);
            }
        }
		@rmdir($dir);
    }   
	
	public function delinstallAjax()
	{
		$this->delinstall();
		echo 'success';
	}
	
	private function delinstall()
	{
		$dir = ROOT_PATH.'/'.PROJECT.'/install';
		$this->rmdirs($dir);
	}
	
	public function saveAjax()
	{
		$dbtype 	= $this->post('dbtype');
		$host 		= $this->post('host');
		$user 		= $this->post('user');
		$pass 		= $this->post('pass');
		$base 		= $this->post('base');
		$xinhukey 	= $this->post('xinhukey');
		$perfix 	= strtolower($this->post('perfix','xinhu_'));
		$engine 	= $this->post('engine','MyISAM');
		$title 		= '信呼协同办公系统';
		$qom 		= 'xinhu_';
		$url 		= $this->post('url');
		$paths 		= ''.P.'/'.P.'Config.php';
		$paths1 	= ''.P.'/'.P.'Config.php1';
		$inpaths	= ROOT_PATH.'/'.$paths.'';
		
		
		$msg  		= '';
		
		if($dbtype=='mysql' && !function_exists('mysql_connect'))exit('未开启mysql扩展模块');
		if($dbtype=='mysqli' && !class_exists('mysqli'))exit('未开启mysqli扩展模块');
		@unlink($inpaths);
		$this->rock->createtxt($paths, '<?php return array();');
		if(!file_exists($inpaths))exit('无法写入文件夹'.P.'');
		
		//1
		$db1 		= import($dbtype);
		$db1->changeattr($host, $user, $pass, 'information_schema');
		$db1->connectdb();
		$msg = $db1->errormsg;
		if(!$this->isempt($msg))exit('数据库用户名/密码有误:'.$msg.'');
		
		
		//2
		$db 		= import($dbtype);
		$db->changeattr($host, $user, $pass, $base);
		$db->connectdb();
		$msg = $db->errormsg;
		if(!$this->isempt($msg)){
			$db1->query("CREATE DATABASE `$base` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci");
		}
		
		$db->connectdb();
		$msg = $db->errormsg;
		if(!$this->isempt($msg))exit('无法创建数据库:'.$msg.'');
		
		
		$dburl 	= ROOT_PATH.'/'.PROJECT.'/install/rockxinhu.sql';
		if(!file_exists($dburl))exit('数据库sql文件不存在');
		
		$sqlss 	= file_get_contents($dburl);
		$a 		= explode(";
", $sqlss);
		for($i=0; $i<count($a)-1; $i++){
			$sql 	= $a[$i];
			$sql	= str_replace('`xinhu_', '`'.$perfix.'', $sql);
			$sql	= str_replace('ENGINE=MyISAM', 'ENGINE='.$engine.'', $sql); //使用引擎
			$bo		= $db->query($sql);
			if(!$bo){
				exit('导入失败:'.$db->error().'');
			}
		}
		$db->query("delete from `".$perfix."option` where `name` is null");
		$urs	= $db->getone("".$perfix."admin", "`id`=1");
		if(!is_array($urs))exit('安装失败:'.$db->error().',可选择手动安装<a href="'.URLY.'view_anzz.html" style="color:blue" target="_blank">[查看]</a>');
		
		$rand	= $this->rock->jm->getRandkey();
		$asynkey= md5($this->rock->jm->getRandkey());
		$openkey= md5($this->rock->jm->getRandkey());
		$txt 	= "<?php
if(!defined('HOST'))die('not access');
//系统配置文件		
return array(
	'url'		=> '',		//系统URL
	'localurl'	=> '',			//本地系统URL，用于服务器上浏览地址
	'title'		=> '$title',	//系统默认标题
	'apptitle'	=> '信呼OA',			//APP上或PC客户端上的标题
	'db_host'	=> '$host',		//数据库地址
	'db_user'	=> '$user',		//数据库用户名
	'db_pass'	=> '$pass',		//数据库密码
	'db_base'	=> '$base',		//数据库名称
	'db_engine'	=> '$engine',	//数据库使用引擎
	'perfix'	=> '$perfix',	//数据库表名前缀
	'qom'		=> '$qom',		//session、cookie前缀
	'highpass'	=> '',			//超级管理员密码，可用于登录任何帐号
	'db_drive'	=> '$dbtype',	//操作数据库驱动有mysql,mysqli,pdo三种
	'randkey'	=> '$rand',		//系统随机字符串密钥
	'asynkey'	=> '$asynkey',	//这是异步任务key
	'openkey'	=> '$openkey',	//对外接口openkey
	'updir'		=> 'upload',	//默认上传目录
	'sqllog'	=> false,		//是否记录sql日志保存upload/sqllog下
	'asynsend'	=> false,		//是否异步发送提醒消息，为true需开启服务端
	'editpass'	=> '1',			//用户登录修改密码：0不用修改，1强制用户必须修改
	'install'	=> true,			//已安装，不要去掉啊
	'xinhukey'	=> '$xinhukey',		//信呼官网key，在线升级使用
);";
		$this->rock->createtxt($paths, $txt);
		$this->delinstall();
		if(file_exists($paths1))@unlink($paths1);
		c('xinhu')->getdata('xinhuinstall');//这个只是用于统计安装数而已
		echo 'success';
	}
}