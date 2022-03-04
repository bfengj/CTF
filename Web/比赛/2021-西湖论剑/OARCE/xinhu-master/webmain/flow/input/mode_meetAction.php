<?php
class mode_meetClassAction extends inputAction{
	

	protected function savebefore($table, $arr, $id, $addbo){
		if(arrvalue($arr,'type')=='1')return ''; //固定会议不需要判断
		return m('meet')->isapplymsg($arr['startdt'], $arr['enddt'], $arr['hyname'], $id);
	}
	

	protected function saveafter($table, $arr, $id, $addbo){
		
	}
	
	//打印二维码判断
	public function getpewmAjax()
	{
		$mid = (int)$this->get('mid','0');
		$rs  = m('meet')->getone($mid);
		if($rs['type']!='0')return '不需要打印';
		if($rs['optid']!=$this->adminid)return '你不是发起人无法显示二维码';

		return 'ok';
	}
}		