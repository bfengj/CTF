<?php 
class tonghuaClassAction extends apiAction
{
	/**
	*	通话初始化
	*/
	public function thinitAction()
	{
		if(!getconfig('video_bool'))return returnerror('系统未开启音视频');
		$id 		= (int)$this->get('id');
		$type 		= (int)$this->get('type');
		if($id==$this->adminid)return returnerror('不能和自己通话');
		$nowtime 	= strtotime($this->now);
		
		//判断用户有没有在线
		$gbarr = m('reim')->pushserver('getonline', array(
			'onlineid' => $id
		));
		if(!$gbarr)return returnerror('没有服务端');
		if(!$gbarr['success'])return $gbarr;
		$ondats = json_decode(arrvalue($gbarr,'data'), true);
		$online = false;
		if($ondats){
			if($ondats['pc']==$id)$online = true;
			if($ondats['app']==$id)$online = true;
		}
		
		if(!$online){
			$to = m('login')->rows('`uid`='.$id.' and `online`=1 and `ispush`=1');
			if($to==0)return returnerror('对方不在线，无法通话');
		}
		
		$barr	= c('xinhuapi')->getdata('tonghua','thinit', array('faid'=>$this->adminid,'nowtime'=>$nowtime,'toid'=>$id,'type'=>$type));
		if(!$barr['success'])return $barr;
		$data 	= $barr['data'];
		$key 	= $data['channel'];
		c('cache')->set($key, $data, 60);
		
		//保存自己通话里面
		m('im_tonghua')->insert(array(
			'uid' 	=> $this->adminid,
			'faid' 	=> $this->adminid,
			'channel' =>$data['channel'],
			'type' 	  =>$data['type'],
			'joinids' =>$id,
			'adddt' 	=>$this->now,
		));
		
		//异步发送
		c('rockqueue')->push('tonghua,call', array('key' => $key,'cishu'=>1));

		return $barr;	
	}
	
	
	/**
	*	取消呼叫
	*/
	public function cancelAction()
	{
		$channel = $this->get('channel');
		$state 	 = (int)$this->get('state','3');
		m('im_tonghua')->update('`state`='.$state.'',"`channel`='$channel'");
		$barr = c('rockqueue')->push('tonghua,cancel', array('key' => $channel));
		if(!$barr['success'])return $barr;
		return returnsuccess();
	}
	
	/**
	*	接电话了(1同意，2拒绝,3取消，4接受者已打开页面，5呼叫超过30秒无人接听)
	*/
	public function jieAction()
	{
		$channel = $this->get('channel');
		$state 	 = (int)$this->get('state','2');
		$dbs 	 = m('im_tonghua');
		$onrs	 = $dbs->getone("`channel`='$channel'");
		$satype	 = '';
		if(!$onrs)$satype = '通话不存在';
		if($onrs && ($onrs['state']=='3' || $onrs['state']=='5'))$satype = '对方已取消';
		if(!$satype){
			$nowtime 	= strtotime($this->now);
			$upstsr		= '`state`='.$state.'';
			if($state==1)$upstsr.=",`jiedt`='$this->now'";
			$dbs->update($upstsr,"`channel`='$channel'");
			$barr = c('rockqueue')->push('tonghua,jie', array('key'=>$channel,'nowtime'=>$nowtime,'uid'=>$this->adminid,'state'=>$state));
			if(!$barr['success'])return $barr;
		}
		return returnsuccess(array(
			'satype' => $satype
		));
	}
	
	/**
	*	接通
	*/
	public function jietongAction()
	{
		$channel 	= $this->get('channel');
		$barr		= c('xinhuapi')->getdata('tonghua','jietong', array('uid'=>$this->adminid,'channel'=>$channel));
		if($barr['success']){
			$bars = $this->jieAction();
			if(!$bars['success'])return $bars;
			$datas= $bars['data'];
			foreach($datas as $k=>$v)$barr['data'][$k] = $v;
		}
		return $barr;
	}
	
	/**
	*	结束通话
	*/
	public function jiesuAction()
	{
		$nowtime 	= strtotime($this->now);
		$channel 	= $this->get('channel');
		$toid 		= (int)$this->get('toid');
		c('rockqueue')->push('tonghua,jiesu', array('uid'=>$this->adminid,'toid'=>$toid,'nowtime'=>$nowtime,'channel'=>$channel));
		m('im_tonghua')->update("`enddt`='$this->now',`jieid`='$this->adminid'","`channel`='$channel'");
		return returnsuccess();
	}
	
	/**
	*	接受者打开了界面
	*/
	public function receopenAction()
	{
		$channel 	= $this->get('channel');
		$where 		= "`channel`='$channel'";
		$dbs = m('im_tonghua');
		$dbs->update('`state`=4', $where);
		$thrs		= $dbs->getone($where);
		$sytime = time()-strtotime($thrs['adddt']);
		return returnsuccess(array(
			'sytime' => $sytime
		));
	}
	
	/**
	*	时时读取状态
	*/
	public function stateAction()
	{
		$channel 	= $this->get('channel');
		$onrs 		= m('im_tonghua')->getone("`channel`='$channel'");
		$tayar 		= array('','tongyi','jujue','cancel','wait','cancel');
		
		return returnsuccess(array(
			'state' => arrvalue($tayar, $onrs['state'])
		));
	}
	
	/**
	*	判断通话是不是结束
	*/
	public function statethAction()
	{
		$channel 	= $this->get('channel');
		$onrs 		= m('im_tonghua')->getone("`channel`='$channel'");
		$state		= 'wu';
		if($onrs && !isempt($onrs['enddt']))$state = 'jiesu';
		
		return returnsuccess(array(
			'state' => $state
		));
	}
}