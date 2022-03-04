<?php
/**
*/ 
class mode_newsClassAction extends inputAction{
	
	
	protected function savebefore($table, $arr, $id, $addbo){
		
		$uarr = array();
		if(!isset($arr['issms']))$uarr['issms']=0;
		return array(
			'rows' => $uarr
		);
	}
	
	
	protected function saveafter($table, $arr, $id, $addbo){
		
	}
	
	//获取新闻
	public function getnewsAjax()
	{
		$typename 	= $this->post('typename');
		$rows 	 = m('flow')->initflow('news')->getflowrows($this->adminid,'my',5,"and `typename`='$typename'");
		return $rows;
	}
}	
			