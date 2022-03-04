<?php 
class reimClassAction extends apiAction
{
	/**
	*	获取聊天记录
	*/
	public function getrecordAction()
	{
		$uid 		= $this->adminid;
		$type 		= $this->post('type');
		$gid 		= (int)$this->post('gid');
		$minid 		= (int)$this->post('minid');
		$lastdt 	= (int)$this->post('lastdt');
		$lastdts	= '';
		if($lastdt>0)$lastdts = date('Y-m-d H:i:s', $lastdt);
		$arr 		= m('reim')->getrecord($type, $uid, $gid, $minid, $lastdts);
		$this->showreturn($arr);
	}
	
	/**
	*	获取会话的信息
	*/
	public function getreceinforAction()
	{
		$uid 		= $this->adminid;
		$type 		= $this->post('type');
		$gid 		= (int)$this->post('gid');
		$reimdb		= m('reim');
		$arr['receinfor'] 		= $reimdb->getreceinfor($type, $gid);
		$reimdb->setallyd($type, $uid, $gid);
		$this->showreturn($arr);
	}
	
	/**
	*	发消息
	*/
	public function sendinforAction()
	{
		$uid 		= $this->adminid;
		$type 		= $this->post('type');
		$gid 		= (int)$this->post('gid');
		$lx 		= 0;
		if($this->cfrom=='reim')$lx=1;
		if($type=='group'){
			$tos = m('im_groupuser')->rows("`gid`='$gid' and `uid`='$uid'");
			if($tos==0)$this->showreturn('','您不在此会话中，不允许发送', 201);
		}
		
		$cont 		= $this->post('cont');
		$cont 		= $this->jm->base64decode($cont);
		$cont 		= str_replace('<br>','[BR]', $cont);
		$cont 		= str_replace(array('<','>'),array('&lt;','&gt;'), $cont);
		$cont 		= $this->jm->base64encode(str_replace('[BR]','<br>',$cont));
		
		$arr 		= m('reim')->sendinfor($type, $uid, $gid, array(
			'optdt' => $this->now,
			'cont'  => $cont,
			'fileid'=> (int)$this->post('fileid')
		), $lx);
		$arr['sendname'] = $this->adminname;
		$this->showreturn($arr);
	}
	
	/**
	*	标识已读
	*/
	public function yiduAction()
	{
		$id = $this->post('id');
		m('reim')->setyd($id, $this->adminid);
		$this->showreturn($id);
	}
	
	//将会话标识已读
	public function yiduallAction()
	{
		$type 		= $this->post('type');
		$gid 		= (int)$this->post('gid');
		m('reim')->setallyd($type, $this->adminid, $gid);
		$this->showreturn('');
	}
	
	/**
	*	创建讨论组
	*/
	public function createtaolunAction()
	{
		$name 	= $this->post('title');
		$explain= $this->post('content');
		$receid = $this->post('receid');
		if($name==''||$receid=='')$this->showreturn('','not data',201);
		$arr = m('reim')->creategroup($name, $receid.','.$this->adminid, 1, $explain);
		$this->showreturn($arr);
	}
	
	/**
	*	获取会话上人员
	*/
	public function getgroupuserAction()
	{
		$gid 	= (int)$this->post('gid');
		$type 	= $this->post('type');
		$arr 	= m('reim')->getgroupuser($gid, $type);
		$this->showreturn($arr);
	}
	
	/**
	*	下载聊天记录
	*/
	public function downrecordAction()
	{
		$minid = floatval($this->post('minid','999999999'));
		$maxid = floatval($this->post('maxid','0'));
		$arr 	= m('reim')->downrecord($this->adminid, $maxid, $minid);
		$this->showreturn($arr);
	}
	
	/**
	*	删除历史会话
	*/
	public function delhistoryAction()
	{
		$gid 	= (int)$this->post('gid');
		$type 	= $this->post('type');
		$arr 	= m('reim')->delhistory($type,$gid,$this->adminid);
		$this->showreturn('');
	}
	
	//邀请人员
	public function yaoqinguidAction()
	{
		$gid	= (int)$this->post('gid');
		$val	= $this->post('val');
		$ids 	= m('reim')->adduserchat($gid, $val, true);
		$msg	= 'success'.$ids.'';
		$this->showreturn($msg);
	}
	
	//修改会话名称
	public function editnameAction()
	{
		$gid	= (int)$this->post('gid');
		$val	= $this->post('val');
		if(isempt($val))return returnerror('不能为空');
		m('reim')->editname($gid, $val);
		$this->showreturn('');
	}
	
	//邀请人员
	public function yaoqingnameAction()
	{
		$gid	= (int)$this->post('gid');
		$val	= $this->post('val');
		if(isempt($val))return returnerror('不能为空');
		$urs 	= m('admin')->geturs($val);
		if(!$urs)return returnerror('“'.$val.'”不存在');
		$uids	= ''.$urs['id'].'';
		$ids 	= m('reim')->adduserchat($gid, $uids, true);
		$msg	= 'success'.$ids.'';
		$this->showreturn('ok');
	}
	
	//退出讨论组
	public function exitgroupAction()
	{
		$aid	= (int)$this->post('aid');
		if($aid==0)$aid = $this->adminid;
		$gid	= (int)$this->post('gid');
		m('reim')->exitchat($gid, $aid);
		$this->showreturn('success');
	}
	
	public function createlunAction()
	{
		$val	= $this->getvals('val');
		$isadd = m('view')->isadd('huihua', $this->adminid);
		if(!$isadd)return returnerror('无权限创建会话');
		m('reim')->createchat($val, $this->adminid,$this->adminid, $this->adminname,'', true);
		$this->showreturn('success');
	}
	
	//清除历史记录
	public function clearrecordAction()
	{
		$gid 	= (int)$this->post('gid');
		$type 	= $this->post('type');
		$ids 	= c('check')->onlynumber($this->post('ids'));
		$day 	= (int)$this->post('day');
		$arr 	= m('reim')->clearrecord($type,$gid,$this->adminid, $ids, $day);
		$this->showreturn('');
	}
	
	//上传头像
	public function changefaceAction()
	{
		$fid 	= (int)$this->post('id');
		$uid 	= $this->adminid;
		$face 	= m('admin')->changeface($uid, $fid); 	
		if(!$face)$this->showreturn('','fail changeface',201);
		$this->showreturn($face);
	}
	
	//下载文件
	public function downfileAction()
	{
		$id 	= (int)$this->post('id');
		m('file')->download($id);
	}
	
	//修改会话头像
	public function editfaceAction()
	{
		$gid 	= (int)$this->get('gid');
		$fileid = (int)$this->get('fileid');
		if($gid<=0)return returnerror('error');
		m('reim')->editface($gid, $fileid);
		$this->showreturn('');
	}
	
	/**
	*	文件转发发送给对应人员
	*/
	public function forwardAction()
	{
		$fid = (int)$this->post('fileid');
		$tuid= $this->post('tuid');
		$msg = m('reim')->forward($tuid, 'user', $this->post('cont'), $fid);
		if($msg!='ok')$this->showreturn('', $msg, 201);
		$this->showreturn('');
	}
	
	
	/**
	*	消息撤回
	*/
	public function chehuimessAction()
	{
		$gid 	= (int)$this->post('gid');
		$type 	= $this->post('type');
		$ids 	= (int)$this->post('ids');
		$barr 	= m('reim')->chehuimess($type, $gid, $ids);
		if(is_array($barr))$this->showreturn($barr);
		$this->showreturn('', $barr, 201);
	}
	
	public function saveoutunumAction()
	{
		$unum 	= $this->get('unum');
		$num 	= 'outunum'.$this->adminid.'';
		$this->option->setval($num, $unum);
		return returnsuccess();
	}
	
	
	/**
	*	收藏使用
	*/
	public function savestarAction()
	{
		$content = $this->post('content');
		$kev 	 = $this->post('kev');
		$id 	 = (int)$this->post('id','0');
		$num 	 = 'reimstar_'.$this->adminid.'';
		$this->option->setval($num,'收藏消息');
		$pid 	 = $this->option->getpids($num);
		$snum	 = ''.$num.'_'.$kev.'';
		$sid 	 = $this->option->getpids($snum);
		if($sid>0)$id = $sid;
		$uarr	 = array(
			'value' => $content,
			'pid'	=> $pid,
			'num'	=> $snum,
			'optdt' => $this->now,
			'optid' => $this->adminid,
		);
		if($id==0){
			$id	 = $this->option->insert($uarr);
		}else{
			$this->option->update($uarr, $id);
		}
		
		return returnsuccess(array(
			'id' => $id
		));
	}
	
	public function getstarAction()
	{
		$num 	 = 'reimstar_'.$this->adminid.'';
		$pid 	 = $this->option->getpids($num);
		$data 	 = $this->option->getall('`pid`='.$pid.'','id,value','optdt desc');
		return returnsuccess($data);
	}
	
	public function delstarAction()
	{
		$id = (int)$this->get('id');
		$this->option->delete('`id`='.$id.' and `optid`='.$this->adminid.'');
		return returnsuccess();
	}
}