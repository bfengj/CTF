<?php
/**
*	此文件是流程模块【hrshebao.社保公积金】对应控制器接口文件。
*/ 
class mode_hrshebaoClassAction extends inputAction{
	
	
	
	//复制
	public function copyfuzAjax()
	{
		$sid = (int)$this->get('sid');
		$db  = m('hrshebao');
		$arow= $db->getone($sid);
		if(!$arow)return;
		unset($arow['id']);
		$arow['optdt'] = $this->rock->now;
		$mid = $db->insert($arow);
	}
}	
			