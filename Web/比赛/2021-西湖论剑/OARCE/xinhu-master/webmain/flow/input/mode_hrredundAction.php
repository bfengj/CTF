<?php
//离职申请
class mode_hrredundClassAction extends inputAction{
	
	protected function savebefore($table, $arr, $id, $addbo)
	{
		$uid = $arr['uid'];
		if(m($table)->rows('id<>'.$id.' and `uid`='.$uid.' and `status`<>5')>0)return '您已申请过了';
	}
	
		
	protected function saveafter($table, $arr, $id, $addbo){
		
	}
	
	public function getuserinfoAjax()
	{
		$urs = m('admin')->getone($this->adminid,'ranking,workdate');
		$this->returnjson($urs);
	}
}	
			