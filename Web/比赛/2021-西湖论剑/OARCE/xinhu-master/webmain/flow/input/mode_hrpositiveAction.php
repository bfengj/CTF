<?php
//转正
class mode_hrpositiveClassAction extends inputAction{
	

	protected function savebefore($table, $arr, $id, $addbo)
	{
		$uid = $arr['uid'];
		if(m($table)->rows('id<>'.$id.' and `uid`='.$uid.' and `status` not in(5)')>0)return '您已申请过了';
	}
	
		
	protected function saveafter($table, $arr, $id, $addbo){
		
	}
	
	public function getuserinfoAjax()
	{
		$urs = m('admin')->getone($this->adminid,'ranking,workdate');
		$urs['syenddt'] = '';
		if(!isempt($urs['workdate']))$urs['syenddt'] = c('date')->adddate($urs['workdate'],'m',3);
		$this->returnjson($urs);
	}
}	
			