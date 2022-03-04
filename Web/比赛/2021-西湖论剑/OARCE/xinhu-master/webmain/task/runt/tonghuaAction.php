<?php
/**
*	语音通话
*	
*/
class tonghuaClassAction extends runtAction
{
	
	public function sendcpush($arr)
	{
		$arr['msgtype'] = 'calltonghua';
		$arr['type']	= 'calltonghua';
		$reimobj 		= m('reim');
		$reimobj->pushserver('sendapp', $arr);
		$reimobj->pushserver('send', $arr);
	}

	/**
	*	呼叫发送
	*	http://192.168.1.2/app/xinhu/task.php?m=tonghua|runt&a=call&key=d9ydh2d8
	*/
	public function callAction()
	{
		$key 	= $this->getparams('key');
		$cishu 	= (int)$this->getparams('cishu','1');
		
		if($cishu>=15)return;
		if(!$key)return;
		$data = c('cache')->get($key);
		if(!$data)return;
		$channel = $data['channel'];
		$uid 	 = $data['uid'];
		$urs  = m('admin')->getone($uid);
		$thrs = m('im_tonghua')->getone("`channel`='$channel'");
		if(!$thrs)return;
		if($thrs['state']>0)return;
		$this->rock->adminid 	= $uid;
		$this->rock->adminname 	= $urs['name'];
		
		//每3秒呼叫一次
		$this->sendcpush(array(
			'adminid'	=> $uid,
			'adminname'	=> $urs['name'],
			'adminface' => m('admin')->getface($urs['face']),
			'th_type' 	=> $data['type'],
			'calltype'	=> 'call', 
			'th_channel' => $channel,
			'th_appid'  => $data['appid'],
			'th_time'	=> time()-strtotime($thrs['adddt']),
			'receid'	=> $data['toid']
		));
		
		if($cishu==1){
			$typea= array('语音','视频');
			$cont = $this->jm->base64encode('['.$typea[$data['type']].'通话]');
			$pushcont = $this->jm->base64encode('邀请与您'.$typea[$data['type']].'通话...');
			m('reim')->sendinfor('user', $uid, $data['toid'], array(
				'optdt' => $thrs['adddt'],
				'cont'  => $cont,
				'pushcont'  => $pushcont,
				'msgid'  => $channel,
			));
		}
		c('rockqueue')->push('tonghua,call', array('key' => $key,'cishu'=>$cishu+1),time()+2);
	
		return 'success';
	}
	
	/**
	*	取消呼叫
	*/
	public function cancelAction()
	{
		$key 	= $this->getparams('key');
		$data	= c('cache')->get($key);
		if(!$data)return;
		$channel = $data['channel'];
		
		$this->sendcpush(array(
			'adminid'	=> $data['uid'],
			'calltype'	=> 'cancel', 
			'receid'	=> $data['toid']
		));
		
		return 'success';
	}
	
	/**
	*	拒接/同意
	*/
	public function jieAction()
	{
		$key 		= $this->getparams('key');
		$state 		= (int)$this->getparams('state','2');
		$nowtime 	= $this->getparams('nowtime');
		$uid 		= (int)$this->getparams('uid','0');
		$data		= c('cache')->get($key);
		if(!$data)return;
		$channel = $data['channel'];
		
		$tayar = array('','tongyi','jujue');
		
		$this->sendcpush(array(
			'adminid'	=> $data['toid'],
			'calltype'	=> $tayar[$state], 
			'receid'	=> $data['uid'].','.$data['toid']
		));
		
		c('xinhuapi')->getdata('tonghua','jietongs', array('uid'=>$uid,'state'=>$state,'nowtime'=>$nowtime,'channel'=>$channel));
		
		return 'success';
	}
	
	/**
	*	结束通话
	*/
	public function jiesuAction()
	{
		$uid 		= (int)$this->getparams('uid');
		$toid 		= (int)$this->getparams('toid');
		$nowtime 	= $this->getparams('nowtime');
		$channel 	= $this->getparams('channel');
		$this->sendcpush(array(
			'adminid'	=> $uid,
			'calltype'	=> 'jiesu', 
			'receid'	=> $toid
		));
		return c('xinhuapi')->getdata('tonghua','jiesu', array('uid'=>$uid,'nowtime'=>$nowtime,'channel'=>$channel));
	}
}