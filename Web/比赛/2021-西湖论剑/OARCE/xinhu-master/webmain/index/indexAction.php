<?php 
class indexClassAction extends Action{
	
	public $homestylebool = false;
	
	public function defaultAction()
	{
		if(strlen(getconfig('randkey'))!=26)exit('配置文件randkey不正确，请重新设置为：'.$this->jm->getRandkey().'');
		
		$homestyle		= getconfig('homestyle');
		if($homestyle>=1 && !$this->homestylebool){
			$temshot = $this->getsession('homestyle');
			if(!$temshot){
				$urlt = '?a=home';
				if($homestyle==2)$urlt = '?a=new';
				$this->rock->location($urlt);
				return;
			}
		}
		
		$afrom 			= $this->get('afrom');
		$this->tpltype	= 'html';
		$my			= $this->db->getone('[Q]admin', "`id`='$this->adminid' and `status`=1",'`face`,`id`,`name`,`ranking`,`deptname`,`deptallname`,`type`,`style`,`user`');
		if(!$my)return '登录用户不存在了，<a href="?m=login&a=exit">重新登录</a>';
		$allmenuid	= m('sjoin')->getuserext($this->adminid, $my['type']);
		m('dept')->online(1);
		$mewhere	= '';
		$isadmin	= 1;
		$myext		= $allmenuid;
		if($myext != '-1'){
			$isadmin	= 0;	
			$mewhere	= ' and `id` in('.str_replace(array('[',']'), array('',''), $myext).')';
		}
		if($this->adminid!=1)$mewhere.=' and `type`=0';
		
		$this->rock->savesession(array(
			'adminallmenuid'	=> $allmenuid,
			'isadmin'			=> $isadmin,
			'homestyle'			=> 'rock',
			'adminuser'			=> $my['user']
		));
		$this->smartydata['adminuser']	= $my['user'];
		$this->smartydata['topmenu'] 	= m('menu')->getall("`pid`=0 and `status`=1 $mewhere order by `sort`");
		$homeurl 						= $this->jm->base64decode($this->get('homeurl'));
		$homename 						= $this->jm->base64decode($this->get('homename'));
		$menuid 						= (int)$this->jm->base64decode($this->get('menuid'));
		$showkey						= $this->jm->base64encode($this->jm->getkeyshow());
		if($menuid<1)$menuid = '';
		if($homeurl=='')$showkey = '';
		if(!isempt($homeurl) && isempt($menuid))return '无权限打开['.$homename.']的页面1';
		if(!isempt($menuid) && $isadmin==0){
			if(!contain($myext,'['.$menuid.']'))return '无权限打开['.$homename.']的页面2';
		}
		$this->smartydata['showkey']	= $showkey;
		$this->smartydata['homeurl']	= $homeurl;
		$this->smartydata['homename']	= $homename;
		$this->smartydata['admintype']	= $isadmin;
		$this->smartydata['my']			= $my;
		$this->smartydata['afrom']		= $afrom;
		$this->smartydata['face']		= $this->rock->repempt($my['face'], 'images/noface.png');
		if(!isempt($homename))$this->title = $homename;
		
		//样式主题处理
		$strs = explode(',', ',cerulean,cosmo,cyborg,darkly,flatly,journal,lumen,paper,readable,sandstone,simplex,slate,spacelab,superhero,united,xinhu,yeti');
		$zys  = count($strs)-1;
		$style= (int)$this->rock->repempt($my['style'], '0');//默认样式
		$styys= 'inverse';
		$this->smartydata['style']		= $style;
		if($style==0)$style = (int)getconfig('defstype','1');//默认的主题皮肤
		if($style>$zys){
			$styys= 'default';
			$style= $style-$zys;
		}
		$stylecs1	= 'mode/bootstrap3.3/css/bootstrap';
		$stylecss	= ''.$stylecs1.'_'.$strs[$style].'.css';
		if(!file_exists($stylecss))$stylecss= ''.$stylecs1.'_cerulean.css';
		$this->smartydata['stylecss']	= $stylecss;
		$this->smartydata['styledev']	= $styys;
		
		//读取单位
		$this->smartydata['logo'] = 'images/xh829.png';
		$this->smartydata['icon'] = 'favicon.ico';
		$companyinfo = false;
		if(COMPANYNUM)$companyinfo = m('company')->getone(1);
		if(ISMORECOM)$companyinfo  = m('admin')->getcompanyinfo($this->adminid, 1);
		if($companyinfo){
			$this->title = $companyinfo['name'];	
			if(!isempt($companyinfo['oaname']))$this->title = $companyinfo['oaname'];
			if(!isempt($companyinfo['logo'])){
				$this->smartydata['logo'] = $companyinfo['logo'];
				$this->smartydata['icon'] = $this->smartydata['logo'];
			}
		}
	}
	
	public function newAction()
	{
		$this->homestylebool = true;
		$this->defaultAction();
		$stylecss = 'mode/bootstrap3.3/css/bootstrap4_default.css';
		$stylecss = 'mode/bootstrap3.3/css/bootstrap3_xinhuoa.css';
		if(!file_exists('webmain/index/tpl_index_new.html')){
			$this->displayfile = 'webmain/index/tpl_index.html';
		}else{
			$_ysts = $this->smartydata['stylecss'];
			if(contain($_ysts,'xinhu') || contain($_ysts,'cerulean'))$this->smartydata['stylecss']	= $stylecss;
		}
	}
	
	public function homeAction()
	{
		$this->homestylebool = true;
		$this->defaultAction();
		$homewidth	= getconfig('homewidth');
		if(!$homewidth)$homewidth='1300px';
		$this->smartydata['homewidth']= $homewidth;
		$this->smartydata['beijing']  = $this->option->getval('beijing_'.$this->adminid.'','images/beijing/bj0.jpg');
		if(!file_exists('webmain/index/tpl_index_home.html'))$this->displayfile = 'webmain/index/tpl_index.html';
	}
	
	public function testnetAction()
	{
		$curl  = c('curl');
		$url   = 'https://www.baidu.com/';
		$cont  = $curl->getcurl($url);
		if(contain($cont,'http')){
			echo '可以访问地址：'.$url.'<br>';
		}else{
			echo '无法访问地址：'.$url.'<br>';
		}
		$url   = 'http://www.rockoa.com/';
		$cont  = $curl->getcurl($url);
		if(contain($cont,'http')){
			echo '可以访问地址：'.$url.'<br>';
		}else{
			echo '无法访问地址：'.$url.'<br>';
		}
		return '测试完成';
	}
	
	public function phpinfoAction()
	{
		$this->display = false;
		phpinfo();
	}
	
	private function menuwheres()
	{
		$this->menuwhere = '';
		$myext	= $this->getsession('adminallmenuid');
		if(isempt($myext))$myext = '0';
		if($myext != '-1'){	
			$this->menuwhere	= ' and `id` in('.str_replace(array('[',']'), array('',''), $myext).')';
		}
		if($this->adminid!=1)$this->menuwhere.=' and `type`=0';//非admin只能看普通菜单
	}
	
	/**
	*	搜索菜单
	*/
	public function getmenusouAjax()
	{
		$key = $this->post('key');
		$this->menuwheres();
		$this->addmenu = m('menu')->getall("`status`=1 $this->menuwhere and `name` like '%$key%' and ifnull(`url`,'')<>'' order by `pid`,`sort` limit 10",'`id`,`num`,`url`,`icons`,`name`,`pid`');
		$arr	= $this->getmenu(0, 1);
		$this->returnjson($arr);
	}
	
	/**
	*	获取菜单
	*/
	public function getmenuAjax()
	{
		$pid 	= $this->get('pid');
		$loadci = (int)$this->get('loadci');
		$this->menuwheres();
		$this->addmenu = m('menu')->getall("`status`=1 $this->menuwhere order by `sort`,`id`",'`id`,`num`,`url`,`icons`,`name`,`pid`');
		$barr	= array(
			'menuarr' => $this->getmenu($pid,0)
		);
		if($loadci==1)$barr['menutopid'] = $this->menutopid();
		return $barr;
	}
	private function getmenu($pid, $lx=0)
	{
		$menu	= $this->addmenu;
		$rows	= array();
		foreach($menu as $k=>$rs){
			if($lx == 0 && $pid != $rs['pid'])continue;
			$num			= $rs['num'];
			$rs['bh']		= $num;
			$sid			= $rs['id'];
			$icons			= $rs['icons'];
			if(isempt($num))$num 		= 'num'.$sid;
			if(isempt($icons))$icons 	= 'bookmark-empty';
			$rs['icons']	= $icons;
			$rs['num']		= $num;
			if($lx == 0){
				$children		= $this->getmenu($sid);
				$rs['children']	= $children;
				$rs['stotal']	= count($children);
			}else{
				$rs['stotal']	= 0;
			}
			$rows[] = $rs;
		}
		return $rows;
	}
	private function menutopid()
	{
		$pnuma  = array();
		$gpar	= array();
		foreach($this->addmenu as $k=>$rs)if($rs['pid']>0)$pnuma[$rs['id']]=$rs['pid'];
		foreach($this->addmenu as $k=>$rs){
			if($rs['pid']>0 && !isempt($rs['num'])){
				$pid = $rs['pid'];
				if(isset($pnuma[$pid])){
					$pid=$pnuma[$pid];
					if(isset($pnuma[$pid]))$pid=$pnuma[$pid];
				}
				$gpar[$rs['num']]=$pid;
			}
		}
		return $gpar;
	}
	
	public function downAction()
	{
		$this->display = false;
		$id  = (int)$this->jm->gettoken('id');
		m('file')->show($id);
	}
	
	/**
	*	单页显示
	*/
	public function showAction()
	{
		$url 	= $this->get('url');
		if($url=='')exit('无效请求');
		$this->defaultAction();
	}
	
	/**
	*	获取模版文件
	*/
	public function getshtmlAction()
	{
		$surl = $this->jm->base64decode($this->get('surl'));
		$num  = $this->get('num');
		$menuname  = $this->jm->base64decode($this->get('menuname'));
		if(isempt($surl))exit('not found');
		$file = ''.P.'/'.$surl.'.php';
		if(!file_exists($file))$file = ''.P.'/'.$surl.'.shtml';
		if(!file_exists($file))exit('404 not found '.$surl.'');
		if(contain($surl,'home/index/rock_index'))$this->showhomeitems();//首页的显示
		$this->displayfile = $file;
		//记录打开菜单日志
		if($num!='home' && getconfig('useropt')=='1')
			m('log')->addlog('打开菜单', '菜单['.$num.'.'.$menuname.']');
	}
	//显示桌面项目
	private function showhomeitems()
	{
		$rows = m('homeitems')->getmyshow();
		if(!$rows)$rows = json_decode('[{"num":"kjrk","row":"0","name":"快捷入口","sort":"0"},{"num":"gong","row":"0","name":"通知公告","sort":"1"},{"num":"kqdk","row":"0","name":"考勤打卡","sort":"2"},{"num":"gwwx","row":"0","receid":"u1","recename":"管理员","name":"微信办公","sort":"10"},{"num":"apply","row":"1","name":"我的申请","sort":"0"},{"num":"meet","row":"1","name":"今日会议","sort":"2"},{"num":"syslog","receid":"u1","recename":"管理员","row":"1","name":"系统日志","sort":"3"},{"num":"about","row":"1","receid":"u1","recename":"管理员","name":"关于信呼","sort":"10"}]', true);
		$homeitems  = $homearrs = array(); 
		foreach($rows as $k=>$rs)$homearrs[] = $rs['num'];
		
		foreach($rows as $k=>$rs){
			$bh = $rs['num'];
			if($bh != 'kjrko'){
				if(in_array('kjrko',$homearrs) && $bh == 'kjrk'){
				}else{
					$homeitems[$rs['row']][] = array(
						'num' => $bh,
						'name'=> $rs['name']
					);
				}
			}
			
		}
		$this->assign('homeitems', $homeitems);
		$this->assign('homearrs', $homearrs);
	}
	
	//开发时快速打开文件
	public function openfileAjax()
	{
		$file = $this->rock->jm->base64decode($this->get('file'));
		$str  = ''.ROOT_PATH.'/'.$file.'';
		$bo   = c('socket')->udpsend($str,'cmd');
		if(is_string($bo))return $bo;
		return 'ok';
	}
	
	public function testAjax()
	{
		//header("HTTP/1.1 500 Not Found");
		echo $this->get('abc');
	}
}