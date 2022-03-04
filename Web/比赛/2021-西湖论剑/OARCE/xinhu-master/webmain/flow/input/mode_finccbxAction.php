<?php
/**
*	此文件是流程模块【finccbx.出差报销】对应接口文件。
*	可在页面上创建更多方法如：public funciton testactAjax()，用js.getajaxurl('testact','mode_finccbx|input','flow')调用到对应方法
*/ 
class mode_finccbxClassAction extends inputAction{
	
	
	protected function savebefore($table, $arr, $id, $addbo){
		$rows['type'] = '1';//一定要是1，不能去掉
		return array(
			'rows'=>$rows
		);
	}
	

	protected function saveafter($table, $arr, $id, $addbo){
		
	}
	
	public function getlastAjax()
	{
		$rs = m('fininfom')->getone("`uid`='$this->adminid' and `type`<3 order by `optdt` desc",'paytype,cardid,openbank,fullname');
		if(!$rs)$rs='';
		$this->returnjson($rs);
	}
}	
			