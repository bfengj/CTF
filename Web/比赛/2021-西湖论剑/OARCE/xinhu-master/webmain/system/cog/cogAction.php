<?php
class cogClassAction extends Action
{
	public function sysinfoAjax()
	{
		$fields = array(
			'title'	=> '系统名称',
			'url'	=> '系统URL地址',
			'localurl'	=> '系统本地地址',
			'outurl'	=> '外网地址',
			'db_drive'	=> '操作数据库驱动',
			'db_host'	=> '数据库地址',
			'db_name'	=> '数据库名称',
			'version'	=> '版本',
			'phpos'		=> '服务器',
			'phpver'	=> 'PHP版本',
			'mysqlver'	=> 'mysql版本',
			'SERVER_SOFTWARE'	=> 'web服务器',
			'upload_max_filesize'	=> '最大上传大小',
			'post_max_size'	=> 'POST最大',
			'memory_limit'	=> '使用最大内存',
			'curl'			=> '是否支持CURL',
			'max_execution_time'			=> 'PHP执行超时时间',
		);
	
		$data = array(
			'title'	=> getconfig('title'),
			'url'	=> getconfig('url'),
			'localurl'	=> getconfig('localurl'),
			'taskurl'	=> getconfig('taskurl'),
			'outurl'	=> getconfig('outurl'),
			'xinhukey'	=> getconfig('xinhukey'),
			'db_host'	=> DB_HOST,
			'db_name'	=> DB_BASE,
			'db_drive'	=> getconfig('db_drive'),
			'version'	=> '信呼V'.VERSION.'',
			'phpos'	=> PHP_OS,
			'phpver'	=> PHP_VERSION,
			'mysqlver'	=> $this->db->getsyscount('version'),
			'SERVER_SOFTWARE'	=> $_SERVER['SERVER_SOFTWARE'],
			'upload_max_filesize'	=> ini_get('upload_max_filesize'),
			'post_max_size'			=> ini_get('post_max_size'),
			'memory_limit'			=> ini_get('memory_limit'),
			'max_execution_time'			=> ini_get('max_execution_time').'秒',
			
		);
		if(!function_exists('curl_init')){
			$data['curl'] = '<font color=red>不支持</font>';
		}else{
			$data['curl'] = '<font color=green>支持</font>';
		}
		
		$this->returnjson(array(
			'fields' => $fields,
			'data' 	=> $data,
		));
	}
	
	public function getinfoAjax()
	{
		$arr['title'] 		= getconfig('title');
		$arr['outurl'] 		= getconfig('outurl');
		$arr['url'] 		= arrvalue($GLOBALS['_tempconf'],'url');
		$arr['localurl'] 	= getconfig('localurl');
		$arr['apptitle'] 	= getconfig('apptitle');
		$arr['platurl'] 	= getconfig('platurl');
		$arr['reimtitle'] 	= getconfig('reimtitle');
		$arr['asynkey'] 	= getconfig('asynkey');
		$arr['openkey'] 	= getconfig('openkey');
		$arr['db_drive'] 	= getconfig('db_drive');
		$arr['xinhukey'] 	= getconfig('xinhukey');
		$arr['bcolorxiang'] = getconfig('bcolorxiang');
		$arr['qqmapkey'] 	= getconfig('qqmapkey');
		$arr['asynsend'] 	= getconfig('asynsend');
		$arr['defstype'] 	= getconfig('defstype','1');
		$arr['officeyl'] 	= getconfig('officeyl'); //文档预览
		$arr['officebj'] 	= getconfig('officebj');
		$arr['apptheme'] 	= getconfig('apptheme');
		$arr['officebj_key'] = getconfig('officebj_key');
		$arr['useropt'] 	= getconfig('useropt');
		$arr['sqllog'] 		= getconfig('sqllog') ? '1' : '0';
		$arr['debug'] 		= getconfig('debug') ? '1' : '0';
		$arr['reim_show'] 	= getconfig('reim_show') ? '1' : '0';
		$arr['mobile_show'] = getconfig('mobile_show') ? '1' : '0';
		$arr['companymode'] = getconfig('companymode') ? '1' : '0';
		$arr['isshou'] 		= $this->isshouquan() ? '1' : '0';
		$arr['editpass'] 	= getconfig('editpass','0');
		
		$arr['asyntest'] 	= $this->option->getval('asyntest');
		
		$loginyzm			= getconfig('loginyzm');
		if(!$loginyzm)$loginyzm	= '0';
		$arr['loginyzm'] 	= $loginyzm;
		if(getconfig('systype')=='demo'){
			$arr['xinhukey']='';
			$arr['officebj_key']='';
		}
		if(!isempt($arr['xinhukey']))$arr['xinhukey'] = substr($arr['xinhukey'],0,5).'*****'.substr($arr['xinhukey'],-5);
		
		$this->returnjson($arr);
	}
	
	private function isshouquan()
	{
		$key = getconfig('authorkey');
		if(!isempt($key) && $this->rock->isjm($key)){
			return true;
		}else{
			return false;
		}
	}
	
	public function savecongAjax()
	{
		if(getconfig('systype')=='demo')exit('演示上禁止设置');
		if($this->getsession('isadmin')!='1')exit('非管理员不能操作');
		
		$puurl 			= $this->option->getval('reimpushurlsystem',1);
		
		$_confpath		= $this->rock->strformat('?0/?1/?1Config.php', ROOT_PATH, PROJECT);
		$arr 			= require($_confpath);
		
		$title 			= $this->post('title');
		if(!isempt($title))$arr['title'] = $title;
		
		$arr['url'] 		= $this->post('url');
		$arr['outurl'] 		= $this->post('outurl');
		$arr['reimtitle'] 	= $this->post('reimtitle');
		$arr['qqmapkey'] 	= $this->post('qqmapkey');
		$arr['platurl'] 	= $this->post('platurl');
		
		$apptitle 			= $this->post('apptitle');
		if(!isempt($apptitle))$arr['apptitle'] = $apptitle;
		
		$asynkey 			= $this->post('asynkey');
		if(!isempt($asynkey))$arr['asynkey'] = $asynkey;
		
		$db_drive 			= $this->post('db_drive');
		if(!isempt($db_drive)){
			if($db_drive=='mysql' && !function_exists('mysql_connect'))exit('未开启mysql扩展模块');
			if($db_drive=='mysqli' && !class_exists('mysqli'))exit('未开启mysqli扩展模块');
			if($db_drive=='pdo' && !class_exists('PDO'))exit('未开启pdo扩展模块');
			$arr['db_drive'] = $db_drive;
		}
		
		$arr['localurl'] 	= $this->post('localurl');
		$arr['openkey']  	= $this->post('openkey');
		
		$arr['xinhukey'] 	= $this->post('xinhukey');
		if(contain($arr['xinhukey'],'**'))$arr['xinhukey'] = getconfig('xinhukey');
		
		$arr['bcolorxiang'] = $this->post('bcolorxiang');
		
		$arr['officeyl'] 	= $this->post('officeyl');
		$arr['useropt'] 	= $this->post('useropt');
		$arr['editpass'] 	= $this->post('editpass');
		$arr['defstype'] 	= $this->post('defstype','1');
		$arr['officebj'] 	= $this->post('officebj');
		$arr['officebj_key']= $this->post('officebj_key');
		
		$asynsend 		 	= $this->post('asynsend');
		$arr['asynsend'] 	= $asynsend;
		
		$arr['sqllog'] 	 	= $this->post('sqllog')=='1';
		$arr['debug'] 	 	= $this->post('debug')=='1';
		$arr['reim_show'] 	= $this->post('reim_show')=='1';
		$arr['mobile_show'] = $this->post('mobile_show')=='1';
		$arr['companymode'] = $this->post('companymode')=='1';
		$arr['loginyzm']  	= $this->post('loginyzm');
		$arr['apptheme']  	= $this->post('apptheme');
		
		if($asynsend == '1' && isempt($puurl))exit('未安装或开启服务端不能使用异步发送消息');
		
		$smarr['url']			= '系统URL';
		$smarr['localurl']		= '本地系统URL，用于服务器上浏览地址';
		$smarr['title']			= '系统默认标题';
		$smarr['neturl']		= '系统外网地址，用于公网';
		$smarr['apptitle']		= 'APP上和手机网页版上的标题';
		$smarr['reimtitle']		= 'REIM即时通信上标题';
		$smarr['weblogo']		= 'PC客户端上的logo图片';
		$smarr['db_host']		= '数据库地址';
		$smarr['db_user']		= '数据库用户名';
		$smarr['db_pass']		= '数据库密码';
		$smarr['db_base']		= '数据库名称';
		$smarr['perfix']		= '数据库表名前缀';
		$smarr['qom']			= 'session、cookie前缀';
		$smarr['highpass']		= '超级管理员密码，可用于登录任何帐号';
		$smarr['db_drive']		= '操作数据库驱动有mysql,mysqli,pdo三种';
		$smarr['randkey']		= '系统随机字符串密钥';
		$smarr['asynkey']		= '这是异步任务key';
		$smarr['openkey']		= '对外接口openkey';
		$smarr['sqllog']		= '是否记录sql日志保存'.UPDIR.'/sqllog下';
		$smarr['asynsend']		= '是否异步发送提醒消息，0同步，1自己服务端异步，2官网VIP用户异步';
		$smarr['install']		= '已安装，不要去掉啊';
		$smarr['xinhukey']		= '信呼官网key，用于在线升级使用';
		$smarr['bcolorxiang']	= '单据详情页面上默认展示线条的颜色';
		$smarr['debug']			= '为true调试开发模式,false上线模式';
		$smarr['reim_show']		= '首页是否显示REIM';
		$smarr['mobile_show']	= '首页是否显示手机版';
		$smarr['loginyzm']		= '登录方式:0仅使用帐号+密码,1帐号+密码/手机+验证码,2帐号+密码+验证码,3仅使用手机+验证码';
		$smarr['officeyl']		= '文档Excel.Doc预览类型,0自己部署插件，1使用官网支持任何平台';
		$smarr['officedk']		= '文件预览打开方式1新窗口打开';
		$smarr['useropt']		= '1记录用户操作保存到日志里,空不记录';
		$smarr['defstype']		= 'PC后台主题皮肤，可以设置1到34';
		$smarr['editpass']		= '用户登录修改密码：0不用修改，1强制用户必须修改';
		$smarr['companymode']	= '多单位模式，true就是开启';
		$smarr['outurl']		= '这个地址当你内网地址访问时向手机推送消息的地址';
		$smarr['officebj']		= '文档在线编辑，1官网提供或者自己部署';
		$smarr['officebj_key']	= '文档在线编辑agentkey';
		$smarr['apptheme']		= '系统或app的主题颜色';
		
		$str1 = '';
		foreach($arr as $k=>$v){
			$bz = '';
			if(isset($smarr[$k]))$bz='	//'.$smarr[$k].'';
			if(is_bool($v)){
				$v = $v ? 'true' : 'false';
			}else{
				$v = "'$v'";
			}
			$str1.= "	'$k'	=> $v,$bz\n";
		}
		
		$str = '<?php
if(!defined(\'HOST\'))die(\'not access\');
//['.$this->adminname.']在'.$this->now.'通过[系统→系统工具→系统设置]，保存修改了配置文件
return array(
'.$str1.'
);';
		@$bo = file_put_contents($_confpath, $str);
		if($bo){
			echo 'ok';
		}else{
			echo '保存失败无法写入：'.$_confpath.'';
		}
	}
	
	public function logbefore($table)
	{
		$key = $this->post('key');
		$s   = '';
		if($key != ''){
			$s = "and (`type`='$key' or `optname` like '$key%' or `remark` like '$key%' or `web`='$key' or `ip`='$key')";
		}
		return $s;
	}
	
	public function dellogAjax()
	{
		$id = $this->post('id');
		m('log')->delete('id in('.$id.')');
		backmsg();
	}
	
	public function clearlogAjax()
	{
		$lx = (int)$this->get('lx','0');
		$where = "`type`='异步队列'";
		if($lx==0)$where = '1=1';
		m('log')->delete($where);
		return returnsuccess();
	}
	
	public function saveautherAjax()
	{
		if(getconfig('systype')=='demo')exit('演示上不要操作');
		$autherkey 	= $this->post('key');
		$ym 		= $this->post('ym');
		$barr 		= c('xinhuapi')->authercheck($autherkey, $ym);
		if($barr['success']){
			echo 'ok';
		}else{
			echo $barr['msg'];
		}
	}
	public function savelixianAjax()
	{
		if(getconfig('systype')=='demo')exit('演示上不要操作');
		$aukey 	= $this->post('key');
		$ym 	= $this->post('ym');
		$path   = 'config/rockauther.php';
		if(!file_exists($path))exit('没有下载签授文件到系统上');
		$da 	= require($path);
		$barr 	= c('xinhuapi')->autherfile($da, $aukey, $ym);
		if($barr['success']){
			@unlink($path);
			echo 'ok';
		}else{
			echo $barr['msg'];
		}
	}
	public function autherAjax()
	{
		$aukey = $this->option->getval('auther_aukey');
		$use   = '1';
		$barr  = array();
		if(isempt($aukey)){
			$use = '0';
		}else{
			$barr['enddt'] = $this->option->getval('auther_enddt');
			$barr['yuming']= $this->option->getval('auther_yuming');
			$barr['aukey'] = substr($aukey,0,5).'****'.substr($aukey,-5);
		}
		$barr['use'] = $use;
		return returnsuccess($barr);
	}
	public function autherdelAjax()
	{
		if(getconfig('systype')=='demo')return returnerror('演示上不要操作');
		return c('xinhuapi')->autherdel();
	}
	public function tongbudwAjax()
	{
		$rows = m('company')->getall('iscreate=1');
		foreach($rows as $k=>$rs){
			$base = ''.DB_BASE.'_company_'.$rs['num'].'';
			$this->sevessee($base, 'auther_aukey');
			$this->sevessee($base, 'auther_enddt');
			$this->sevessee($base, 'auther_yuming');
			$this->sevessee($base, 'auther_authkey');
		}
		return '同步成功';
	}
	private function sevessee($base, $key)
	{
		$val = $this->option->getval($key);
		$sql = "update ".$base.".`[Q]option` set `value`='$val',`optdt`='{$this->now}' where `num`='$key'";
		$this->db->query($sql, false);
	}
}