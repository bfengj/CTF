<?php 
class indexreimClassAction extends apiAction
{
	/**
	*	PC客户端首页读取
	*/
	public function indexinitAction()
	{
		$viewobj 	= m('view');
		$dbs 		= m('reim');
		$dba 		= m('admin');
		$gtype		= $this->rock->get('gtype');
		$udarr 		= m('dept')->getdeptuserdata();
		$userarr 	= $udarr['uarr'];
		$deptarr 	= $udarr['darr'];
		
		$grouparr 	= $dbs->getgroup($this->adminid);
		$agentarr	= $dbs->getagent($this->adminid);
		$historyarr	= $dbs->gethistory($this->adminid);
		$modearr	= array();
		
		if(isempt($gtype)){
			$applyarr	= m('mode')->getmoderows($this->adminid,'and islu=1');
			foreach($applyarr as $k=>$rs){
				if(!$viewobj->isadd($rs, $this->adminid))continue;
				$modearr[]=array('type'=>$rs['type'],'num'=>$rs['num'],'name'=>$rs['name']);
			}
		}
		
		$arr['deptjson']	= json_encode($deptarr);
		$arr['userjson']	= json_encode($userarr);
		$arr['groupjson']	= json_encode($grouparr);
		$arr['agentjson']	= json_encode($agentarr);
		$arr['historyjson'] = json_encode($historyarr);
		$arr['modearr'] 	= $modearr;
		$arr['config'] 		= $dbs->getreims();
		$arr['loaddt'] 		= $this->now;
		$arr['ip'] 			= $this->ip;
		$arr['editpass']	= $dba->iseditpass($this->adminid);
		$arr['companyinfo']	= $dba->getcompanyinfo($this->adminid, 1);
		$arr['onlinearr']	= $this->onlinearr();
		$arr['outunum']   	= $this->option->getval('outunum'.$this->adminid.'');
		$arr['outgroupopen']= $this->option->getval('outgroupopen'.$this->adminid.'');
		if(getconfig('systype')=='demo'){
			$arr['outgroupopen'] = 'open';
			$arr['outunum']		 = 'y4rwln';
		}
		$this->rock->savesession(array('homestyle' => 'rock'));
		
		return returnsuccess($arr);
	}
	
	
	//在线情况读取
	private function onlinearr()
	{
		$time 	= date('Y-m-d H:i:s', time()-6*60);
		$rows 	= m('login')->getall("`online`=1 and ((`ispush`=1) or (`cfrom`='reim' and moddt>='$time'))",'uid,ispush,web,cfrom');
		$onlinearr = array();
		foreach($rows as $k=>$rs){
			$uid = $rs['uid'];
			if(!isset($onlinearr[$uid]))$onlinearr[$uid]=array('uid'=>$uid,'reim'=>0,'mobile'=>0,'web'=>'');
			if($rs['cfrom']=='reim')$onlinearr[$uid]['reim']  = 1;
			if($rs['ispush']=='1'){
				$onlinearr[$uid]['mobile']  = 1;
				$onlinearr[$uid]['web'] 	= $rs['web'];
			}
		}
		return $onlinearr;
	}
	
	/**
	*	REIM的初始化
	*/
	public function reiminitAction()
	{
		$dbs 		= m('reim');
		$dba 		= m('admin');
		$udarr 		= m('dept')->getdeptuserdata();
		$userarr 	= $udarr['uarr'];
		$deptarr 	= $udarr['darr'];
		
		$grouparr 	= $dbs->getgroup($this->adminid);
		$historyarr	= $dbs->gethistory($this->adminid);
		$agentarr	= $dbs->getagent($this->adminid);
		
		$arr['deptjson']	= json_encode($deptarr);
		$arr['userjson']	= json_encode($userarr);
		$arr['groupjson']	= json_encode($grouparr);
		$arr['historyjson'] = json_encode($historyarr);
		$arr['agentjson']	= json_encode($agentarr);
		$arr['config'] 		= $dbs->getreims();
		$arr['loaddt'] 		= $this->now;
		$arr['ip'] 			= $this->ip;
		$arr['editpass']	= $dba->iseditpass($this->adminid);
		$arr['companyinfo']	= $dba->getcompanyinfo($this->adminid, 1);
		
		
		$this->showreturn($arr);
	}
	
	/**
	*	会话列表记录
	*/
	public function gethistoryAction()
	{
		$arr = m('reim')->gethistory($this->adminid);
		$this->showreturn($arr);
	}
	
	/**
	*	最新app读取通信地址
	*/
	public function reimconfigAction()
	{
		$arr['config'] 		= m('reim')->getreims();
		$type  = $this->option->getval('reimservertype','0');
		$appwx = $this->option->getval('reimappwxsystem','0');
		if($type=='0' || $appwx=='0'){//非nodejs版本就不要
			$arr['config']['wsurl'] = '';
			$arr['config']['recid'] = '';
		}
		$this->showreturn($arr);
	}
	
	/**
	*	手机网页版读取，最新webapp的
	*/
	public function mwebinitAction()
	{
		$dbs 		= m('reim');
		$dba 		= m('admin');
		$agentarr	= $dbs->getappagent($this->adminid);
		$historyarr	= $dbs->gethistory($this->adminid);
		
		$arr['agentjson']	= json_encode($agentarr['rows']);
		$arr['historyjson'] = json_encode($historyarr);
		$arr['loaddt'] 		= $this->now;
		$arr['loadtime'] 	= time();
		if($historyarr)$arr['loadtime'] = strtotime($historyarr[0]['optdt']);
		$arr['editpass']	= $dba->iseditpass($this->adminid);
		$arr['companyinfo']	= $dba->getcompanyinfo($this->adminid, 1);
		
		
		//读取app首页显示图片，从公告和新闻上读取
		$arr['myhomenum']	= '';
		if($this->isshowshouye('appsy_yyshow'))$arr['myhomenum']	= $this->option->getval('yinghomeshow_'.$this->adminid.'');//我常用的
		
		$silderarr	= array();
		if($this->isshowshouye('appsy_ggshow')){
			$sildergong = m('flow')->initflow('gong')->getflowrows($this->adminid, 'my', 5, " and `appxs`=1");
			foreach($sildergong as $k=>$rs){
				if(isempt($rs['fengmian']))continue;
				$silderarr[] = array(
					'src' => $this->rock->gethttppath($rs['fengmian']),
					'title' => $rs['title'],
					'url'	=> 'task.php?a=x&num=gong&mid='.$rs['id'].''
				);
			}
		}
		if($this->isshowshouye('appsy_xwshow','否')){
			$sildernews = m('flow')->initflow('news')->getflowrows($this->adminid, 'my', 5, " and `appxs`=1");
			foreach($sildernews as $k=>$rs){
				if(isempt($rs['fengmian']))continue;
				$silderarr[] = array(
					'src' => $this->rock->gethttppath($rs['fengmian']),
					'title' => $rs['title'],
					'url'	=> 'task.php?a=x&num=news&mid='.$rs['id'].''
				);
			}
		}
		
		$arr['silderarr'] = $silderarr;
		$arr['outunum']   = $this->option->getval('outunum'.$this->adminid.'');
		$arr['outgroupopen']   = $this->option->getval('outgroupopen'.$this->adminid.'');
		$arr['tonghuabo'] = getconfig('video_bool') ? '1' : '2';//是否开启音视频
		
		$this->showreturn($arr);
	}
	
	private function isshowshouye($lx, $mr='是')
	{
		$val = $this->option->getval($lx);
		if(isempt($val))$val=$mr;
		return $val=='是';
	}
	
	public function ldataAction()
	{
		$loaddt		= $this->rock->jm->base64decode($this->post('loaddt'));
		$type		= $this->post('type','history');
		$dbs 		= m('reim');
		$json		= array();
		if($type=='history')$json = $dbs->gethistory($this->adminid, $loaddt);
		if($type=='group')$json = $dbs->getgroup($this->adminid);
		if($type=='dept')$json 	= m('dept')->getdata();
		if($type=='user')$json 	= m('admin')->getuser();
		if($type=='agent')$json = $dbs->getagent($this->adminid);
		if($type=='config')$json = m('reim')->getreims();
		
		$arr['json'] 	= json_encode($json);	
		$arr['loaddt']  = $this->now;
		$arr['ip']  	= $this->ip;
		$arr['type']  	= $type;

		$this->showreturn($arr);
	}

	
	public function indexupgetAction()
	{
		$historyarr			= m('reim')->gethistory($this->adminid);
		$arr['historyjson'] = json_encode($historyarr);
		$this->showreturn($arr);
	}
	
	public function changewxtxAction()
	{
		$tx = (int)$this->post('tx','1');
		m('admin')->update('wxtx='.$tx.'', $this->adminid);
		$this->showreturn('');
	}
	
	public function showmyinfoAction()
	{
		$dbs = m('admin');
		$arr = $dbs->getone($this->adminid,'`id`,`deptallname`,`ranking`,`email`,`tel`,`apptx`,`face`,`name`,`user`,`mobile`');
		if(!$arr)$this->showreturn('','not user', 201);
		$arr['face']		= $dbs->getface($arr['face']);
		$arr['admintoken']  = $this->admintoken;
		$arr['companyinfo']  = $dbs->getcompanyinfo($this->adminid, 1);
		$arr['companymode']	 = ISMORECOM;
		if(m('reim')->installwx(3)){
			$bdwx	= m('wouser')->getone('`uid`='.$this->adminid.'','nickname,headimgurl');
			$arr['bdwx'] 		= $bdwx;
		}
		$this->showreturn($arr);
	}
	
	public function wxbdjcAction()
	{
		m('wouser')->update('`uid`=0','`uid`='.$this->adminid.'');
		$this->showreturn('');
	}
	
	//同步微信上头像
	public function tongbufaceAction()
	{
		$reim = m('reim');
		if($reim->installwx(1)){
			$barr 	= m('weixinqy:user')->anayface($this->userrs['user'], true);
			if($barr['errcode'] != 0)$this->showreturn('',$barr['msg'],202);
			$this->showreturn($barr);
		}else{
			$this->showreturn('','没部署企业微信',201);
		}
	}
	
	public function loadinfoAction()
	{
		$type 	= $this->get('type');
		$receid = $this->get('receid');
		$arr 	= array();
		if($type=='user'){
			$arr 	= m('admin')->getuser(0, $receid);
		}
		$this->showreturn($arr);
	}
	
	//判断是否有最新历史信息
	public function loadhitAction()
	{
		$time  = $this->get('time');
		$arr['loadtime'] 	= $time;
		$optdt = date('Y-m-d H:i:s', $time);
		$arr['total'] = 0;
		$historyarr	  = m('reim')->gethistory($this->adminid, $optdt);
		$arr['rows']  = $historyarr;
		if($historyarr)$arr['loadtime'] = strtotime($historyarr[0]['optdt']);
		
		$this->showreturn($arr);
	}
	
	//设置常应用
	public function shecyyAction()
	{
		$yynum  = $this->get('yynum');
		$myyyid= $this->option->getval('yinghomeshow_'.$this->adminid.'');
		$yarrs = array();
		$iscy  = 0;
		if(isempt($myyyid)){
			$yarrs[]= $yynum;
			$iscy = 1;
		}else{
			$yarrs = explode(',', $myyyid);
			if(in_array($yynum, $yarrs)){
				$iscy = 0;
				foreach($yarrs as $k1=>$v1)if($v1==$yynum)unset($yarrs[$k1]);
			}else{
				$iscy 	= 1;
				$yarrs[]= $yynum;
			}
		}
		$myyyid = join(',', $yarrs);
		$this->option->setval('yinghomeshow_'.$this->adminid.'', $myyyid);
		$msg 	= '已设置首页显示';
		if($iscy==0)$msg = '已取消首页显示';
		$this->showreturn(array(
			'iscy' => $iscy,
			'msg' => $msg,
		));
	}
	
	public function openoutqunAction()
	{
		$isop = $this->get('isop');
		$this->option->setval('outgroupopen'.$this->adminid.'', $isop);
		return returnsuccess();
	}
}