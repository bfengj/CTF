<?php
/**
*	此文件是流程模块【officia.发文单】对应接口文件。
*	可在页面上创建更多方法如：public funciton testactAjax()，用js.getajaxurl('testact','mode_officia|input','flow')调用到对应方法
*/ 
class mode_officiaClassAction extends inputAction{
	
	
	protected function savebefore($table, $arr, $id, $addbo){
		
	}
	
		
	protected function saveafter($table, $arr, $id, $addbo){
		
	}
	
	public function getfilenumAjax()
	{
		$type 	= $this->post('type');
		$num 	= ''.$type.'〔Year〕';
		return $this->db->sericnum($num,'[Q]official','num', 1).'号';
	}
	
	//读取主送单位
	public function getofficiaunit()
	{
		return $this->option->getdata('officiaunit');
	}
}	
			