<?php
class mode_emailmClassAction extends inputAction{
	

	protected function savebefore($table, $arr, $id, $addbo){
		$barr['sendid'] 	= $this->adminid;
		$barr['sendname'] 	= $this->adminname;
		$barr['senddt'] 	= $this->now;
		$isfile				= 0;
		if($this->post('fileid') != '')$isfile = '1';
		$barr['isfile']		= $isfile;
		$type 				= (int)$arr['type'];
		//外发
		if($type==1 && $arr['isturn']==1){
			$vsl = $this->option->getval('email_recexin_'.$this->adminid.'');
			if(isempt($vsl))return '未成功收信过，不能外发邮件';
		}
		if($type == 1){
			$urs = m('admin')->getone($this->adminid, 'id,name,email');
			$barr['sendname'] = ''.$urs['name'].'('.$urs['email'].')';
		}
		
		//回复的ID
		$huiid = $this->post('huiid');
		if(!isempt($huiid)){
			$barr['hid'] = $huiid;
			m('emails')->update('ishui=1','`mid`='.$huiid.' and `uid`='.$this->adminid.' and `type`=0');//更新已回复
		}
		
		return array('rows'=>$barr);
	}
	
	
	protected function saveafter($table, $arr, $id, $addbo)
	{
		$isturn = (int)$arr['isturn'];
		$type 	= (int)$arr['type'];
		if($isturn==1){
			if($type==0){
				$this->flow->savesubmid($arr['receid'], $id, 0,0);
				$this->flow->savesubmid($arr['ccid'], $id, 1,0);
			}
			$this->flow->savesubmid($arr['sendid'], $id, 2,1);
			//外发发邮件的
			if($type == 1){
				$emsa = $this->getrecename($arr['receid']);
				m($table)->update('`outzt`=0', $id);
				if($emsa != ''){
					$ccsa 	= $this->getrecename($arr['ccid']);
					$fjar 	= m('file')->getfilepath('emailm', $id);
					m('email')->sendemailout($this->adminid, array(
						'title' 	=> $arr['title'],
						'body' 		=> $arr['content'],
						'receemail' => $emsa[0],
						'recename' 	=> $emsa[1],
						'ccemail' 	=> $ccsa[0],
						'ccname' 	=> $ccsa[1],
						'attachpath'=> $fjar[0],
						'attachname'=> $fjar[1],
						'mid'		=> $id,
					), 0);
				}
			}
		}
	}
	
	private function faemail($id, $arr)
	{
		$emsa = $this->getrecename($arr['receid']);
		m('emailm')->update('`outzt`=0', $id);
		if($emsa[0] != ''){
			$ccsa 	= $this->getrecename($arr['ccid']);
			$fjar 	= m('file')->getfilepath('emailm', $id);
			return m('email')->sendemailout($this->adminid, array(
				'title' 	=> $arr['title'],
				'body' 		=> $arr['content'],
				'receemail' => $emsa[0],
				'recename' 	=> $emsa[1],
				'ccemail' 	=> $ccsa[0],
				'ccname' 	=> $ccsa[1],
				'attachpath'=> $fjar[0],
				'attachname'=> $fjar[1],
				'mid'		=> $id,
			), 0);
		}else{
			return '接收空的';
		}
	}
	
	/**
	*	重新请求外发
	*/
	public function reoutfaAjax()
	{
		$id = (int)$this->get('sid','0');
		$arr = m('emailm')->getone($id);
		if($arr['type']!='1')return;
		$msg = $this->faemail($id, $arr);
		return $msg;
	}
	
	private function getrecename($sid)
	{
		$sem 	= $scn = '';
		if(!isempt($sid)){
			$rows 	= m('vcard')->getall("`id` in($sid)");
			foreach($rows as $k=>$rs){
				$sem.=','.$rs['email'].'';
				$scn.=','.$rs['name'].'';
			}
		}
		if($sem!=''){
			$sem = substr($sem, 1);
			$scn = substr($scn, 1);
		}
		return array($sem, $scn);
	}
	
	
	//邮件回复的
	public function emailhuifuAjax()
	{
		$mid 	= (int)$this->post('mid');
		$cont 	= $this->post('cont');
		$flow 	= m('flow')->initflow('emailm', $mid);
		echo $flow->huifu($cont);
	}
	
	//获取个人通讯录上联系人，外发发邮件的
	public function getvcardAjax()
	{
		$row = $this->getvcard();
		$this->returnjson($row);
	}
	
	public function getvcard()
	{
		$rows = m('vcard')->getall("uid='$this->adminid' and `email` is not null",'id,name,email','sort,optdt desc');
		$row  = array();
		foreach($rows as $k=>$rs){
			$row[] = array(
				'value' => $rs['id'],
				'name' => ''.$rs['name'].'('.$rs['email'].')',
			);
		}
		return $row;
	}
	
	public function getzfcontAjax()
	{
		$zfid 	= (int)$this->get('zfid');
		$zflx 	= (int)$this->get('zflx');
		$rs 	= m('emailm')->getone($zfid,'title,content,type,sendid,sendname,fromemail,id');
		if($zflx==0){
			$zffes	= m('file')->copyfile('emailm', $zfid); //转发附件
			$rs['filers'] = $zffes;
		}else{
			//外发时读取
			if($rs['type']==1){
				$rs['sendid'] 	= '';
				$rs['sendname'] = '';
				$rsem = m('emails')->getone("`mid`='$zfid' and `type`=2");
				if($rsem){
					$toemail 	= $rsem['email'];
					$toname 	= $rsem['personal'];
					//加入到个人通讯录上
					$rse = m('vcard')->getone("`uid`='$this->adminid' and `email`='$toemail'");
					if(!$rse){
						$toid = m('vcard')->insert(array(
							'name' => $toname,
							'uid' => $this->adminid,
							'email' => $toemail,
							'gname' => '未分组',
							'optdt' => $this->now,
						));
					}else{
						$toid = $rse['id'];
					}
					$rs['sendid'] = $toid;
					$rs['sendname'] = $toname.'('.$toemail.')';
				}
				
				$flow = m('flow')->initflow('emailm');
				$rs['content']  = '<br><br><br>'.$flow->getoldcont($zfid, false);
			}
		}
		$rs['zflx'] = $zflx;
		$this->returnjson($rs);
	}
}	
			