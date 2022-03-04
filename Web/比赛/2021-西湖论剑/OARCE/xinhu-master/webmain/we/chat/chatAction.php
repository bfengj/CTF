<?php 
class chatClassAction extends ActionNot{
	
	public function initAction()
	{
		$this->mweblogin(0, true);
	}
	
	public function defaultAction()
	{
		//if(getconfig('platdwnum'))return '平台模式请使用REIM通信平台会话';
		$type 	= $this->get('type');
		$uid  	= (int)$this->get('uid');
		$db 	= m('reim');
		$arr 	= $db->getreceinfor($type, $uid);
		if(!isset($arr['name']))exit('error');
		$this->title = $arr['name'];
		if(isset($arr['utotal']))$this->title.='('.$arr['utotal'].')';
		$this->assign('arr', $arr);
	}
}