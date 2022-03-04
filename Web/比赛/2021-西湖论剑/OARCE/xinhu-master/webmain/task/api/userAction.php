<?php 
class userClassAction extends apiAction
{
	public function editpassAction()
	{
		if(getconfig('systype')=='demo')$this->showreturn('演示上不要修改');
		$id			= $this->adminid;
		$oldpass	= $this->post('passoldPost');
		$pasword	= $this->post('passwordPost');
		$msg		= '';
		if($this->isempt($pasword))$msg ='新密码不能为空';
		if($msg == ''){
			$oldpassa	= $this->db->getmou($this->T('admin'),"`pass`","`id`='$id'");
			if($oldpassa != md5($oldpass))$msg ='旧密码不正确';
			if($msg==''){
				if($oldpassa == md5($pasword))$msg ='新旧密码不能相同';
			}
		}
		if($msg == ''){
			if(!$this->db->record($this->T('admin'), "`pass`='".md5($pasword)."',`editpass`=`editpass`+1", "`id`='$id'"))$msg	= $this->db->error();
		}
		if($msg==''){
			$this->showreturn('success');
		}else{
			$this->showreturn('',$msg, 201);
		}
	}
	
	//修改头像
	public function editfaceAction()
	{
		$fid = (int)$this->post('fid');
		$dbs = m('admin');
		$face= $dbs->changeface($this->adminid, $fid);
		if($face)$face = $dbs->getface($face);
		
		$this->showreturn($face);
	}
	
	//设置极光推送的regid
	public function setjpushidAction()
	{
		$id = $this->get('id');
		m('login')->update("`ip`='$id'", "`token`='$this->token' and `uid`='$this->adminid'");
		$this->showreturn('ok');
	}
}