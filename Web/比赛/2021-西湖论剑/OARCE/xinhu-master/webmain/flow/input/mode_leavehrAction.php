<?php
/**
*	此文件是流程模块【leavehr.考勤信息】对应接口文件。
*/ 
class mode_leavehrClassAction extends inputAction{
	
	public function totalAjax()
	{
		$uid	= (int)$this->post('uid','0');
		$start	= $this->post('stime');
		$end	= $this->post('etime');
		$kq 	= m('kaoqin');
		$sj 	= 0;
		$sbtime = $kq->getworktime($uid, $start); //一天上班小时
		return array($sj, '', $sbtime);
	}
	
	
	protected function saveafter($table, $cans, $id, $addbo){
		m('flow:leave')->updateenddt();
	}
}	
			