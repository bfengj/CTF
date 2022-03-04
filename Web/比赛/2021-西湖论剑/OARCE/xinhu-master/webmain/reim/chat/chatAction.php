<?php 
class chatClassAction extends ActionNot{
	
	public function initAction()
	{
		if($this->adminid==0){
			exit('登录已经失效了');
		}
	}
	
	public function defaultAction()
	{
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