<?php
/**
*	此文件是流程模块【wenjuan.调查问卷】对应控制器接口文件。
*/ 
class mode_wenjuanClassAction extends inputAction{
	
	
	protected function savebefore($table, $arr, $id, $addbo){
		
	}
	
	
	protected function saveafter($table, $arr, $id, $addbo){
		
	}
	
	public function submitwenjianAjax()
	{
		$mid = (int)$this->post('mid','0');
		return m('flow')->initflow('wenjuan', $mid)->submitwenjuan();
	}
}	
			