<?php
//人事调动
class mode_hrtransferClassAction extends inputAction{
	

	protected function savebefore($table, $arr, $id, $addbo){
		$tranuid = $arr['tranuid'];
		if(m($table)->rows("`tranuid`='$tranuid' and `id`<>'$id' and `status` not in(1,5)")>0)return '该人员已申请过了';
	}
	
	protected function saveafter($table, $arr, $id, $addbo){
		
	}
	
	public function chenguserAjax()
	{
		$sid = $this->post('sid','0');
		$rs  = m('admin')->getone($sid, 'ranking,deptname,workdate');
		$this->returnjson($rs);
	}
}	
			