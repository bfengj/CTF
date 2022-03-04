<?php
/**
*	此文件是流程模块【custplan.跟进计划】对应控制器接口文件。
*/ 
class mode_custplanClassAction extends inputAction{
	
	public function getmycust()
	{
		$rows = m('crm')->getmycust($this->adminid, $this->rock->arrvalue($this->rs, 'custid'));
		return $rows;
	}
	
	protected function saveafter($table, $arr, $id, $addbo){
		$status = arrvalue($arr,'status');
		$custid = arrvalue($arr,'custid','0');
		$findt = arrvalue($arr,'findt');
		if($status==1 && $custid>0 && $findt){
			m('customer')->update("`lastdt`='{$findt}'", $custid);
		}
	}
}	
			