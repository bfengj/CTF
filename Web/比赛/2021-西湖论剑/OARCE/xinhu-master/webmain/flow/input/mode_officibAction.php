<?php
/**
*	此文件是流程模块【officib.收文单】对应接口文件。
*	可在页面上创建更多方法如：public funciton testactAjax()，用js.getajaxurl('testact','mode_officib|input','flow')调用到对应方法
*/ 
class mode_officibClassAction extends inputAction{
	
	
	protected function savebefore($table, $arr, $id, $addbo){
		
		$rows['type'] = '1';//一定要是1，不能去掉
		return array(
			'rows'=>$rows
		);
	}
	
	
	protected function saveafter($table, $arr, $id, $addbo){
		
	}
	
	public function getofficiaunit()
	{
		return $this->option->getdata('officiaunit');
	}
}	
			