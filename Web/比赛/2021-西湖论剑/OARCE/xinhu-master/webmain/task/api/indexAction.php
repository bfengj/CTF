<?php 
class indexClassAction extends apiAction
{
	public function indexAction()
	{
		$this->showreturn('','error', 203);
	}
	
	/**
	*	手机app读取
	*/
	public function indexinitAction()
	{
		$dbs 			= m('reim');
		$ntime			= floatval($this->post('ntime'));
		$uid 			= $this->adminid;
		$arr['loaddt']  	= $this->now;
		$arr['splittime'] 	= (int)($ntime/1000-time());
		$arr['reimarr']		= $dbs->gethistory($uid);
		$this->showreturn($arr);
	}
	
	/**
	* app首页接口截止
	*/
	public function indexappAction()
	{
		$dbs 			= m('reim');
		$ntime			= floatval($this->post('ntime'));
		$uid 			= $this->adminid;
		$agent 			= $dbs->getappagent($uid);
		$arr['loaddt']  	= $this->now;
		$arr['splittime'] 	= (int)($ntime/1000-time());
		$arr['reimarr']		= $dbs->gethistory($uid);
		$arr['agentarr']	= $agent['rows'];
		$arr['agentstotal']	= $agent['stotal'];
		$arr['maxupsize']	= c('upfile')->getmaxzhao();//最大上传大小M
		$arr['appversion']	= $this->get('appversion');
		$arr['xinhuver']	= VERSION;
		$arr['wsconfig']	= $dbs->getreims();
		
		$this->showreturn($arr);
	}
	
	public function lunxunAction()
	{
		$uid 			= $this->adminid;
		$loaddt			= $this->post('loaddt');
		//$reimarr 		= m('reim')->getwdarr($uid, $loaddt);
		$reimarr 		= m('reim')->gethistory($uid, $loaddt);
		$arr['reimarr'] = $reimarr;
		$arr['loaddt']  = $this->now;
		m('login')->uplastdt();
		$this->showreturn($arr);
	}
	
	
	//应用获取数据
	public function getyydataAction()
	{
		$num 	= $this->post('num');
		$event 	= $this->post('event');
		$page 	= (int)$this->post('page');
		$rows 	= m('agent:'.$num.'')->getdata($this->adminid, $num, $event, $page);
		
		$this->showreturn($rows);
	}
	
	public function yyoptmenuAction()
	{
		$num 	= $this->post('modenum');
		$sm 	= $this->post('sm');
		$optid 	= (int)$this->post('optmenuid');
		$zt 	= (int)$this->post('statusvalue');
		$mid 	= (int)$this->post('mid');
		$msg 	= m('flow')->opt('optmenu', $num, $mid, $optid, $zt, $sm);
		if($msg != 'ok')$this->showreturn('', $msg, 201);
		$this->showreturn('');
	}
	
	
	public function changetxAction()
	{
		$apptx = (int)$this->post('apptx');
		m('admin')->update("`apptx`='$apptx'", $this->adminid);
		$this->showreturn('');
	}
	
	
	public function checkewmAction()
	{
		$randkey = $this->get('randkey');
		$lx 	 = (int)$this->get('lx');
		$val 	 = $this->adminid;
		$lxarr 	 = array('已取消','已确认');
		if($lx==0)$val='-1';
		c('cache')->set($randkey,$val,60);
		$this->showreturn($lxarr[$lx]);
	}
	
	/**
	*	切换公司
	*/
	public function changecompanyAction()
	{
		$id = (int)$this->get('id');
		$db = m('admin');
		$db->update('comid='.$id.'', '`id`='.$this->adminid.'');
		$db->getcompanyinfo();
		return returnsuccess();
	}
	public function getcompanyAction()
	{
		$carr = m('admin')->getcompanyinfo($this->adminid);
		$this->showreturn($carr);
	}
	
	/**
	*	华为设置客户端token
	*/
	public function updateTokenIpAction()
	{
		$hwtoken 	= $this->get('hwtoken');
		$pushtoken 	= $this->get('pushtoken');
		$ispush  	= (int)$this->get('ispush','0');
		$uarr['ispush'] 	= $ispush;
		$uarr['pushtoken'] 	= $pushtoken;
		$uarr['moddt'] 		= $this->now;
		if(!isempt($hwtoken))$uarr['ip'] = $hwtoken;
		m('login')->update($uarr, "`token`='$this->admintoken'");
		if($ispush==1)m('reim')->sendpush($this->adminid, 'all', array(
			'type' => 'onoffline',
			'online' => 2
		));
		return returnsuccess();
	}
	
	public function addlogAction()
	{
		$tit  = $this->post('title');
		$cont = $this->post('cont');
		$web  = $this->post('web');
		$level = (int)$this->post('level','0');
		m('log')->addlogs($tit, $cont,$level, array(
			'web'		=> $web,
		));
		return returnsuccess();
	}
	
	public function sgstrs()
	{
	}
}