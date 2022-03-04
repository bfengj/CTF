<?php
/**
*	此文件是流程模块【subscribe.订阅】对应控制器接口文件。
*/ 
class mode_subscribeClassAction extends inputAction{
	
	
	
	protected function savebefore($table, $cans, $id, $addbo=true)
	{
		$suburlpost = $cans['suburlpost'];
		$optid		= $cans['optid'];
		$to  = m('subscribe')->rows("`id`<>'$id' and `optid`='$optid' and `suburlpost`='$suburlpost'");
		if($to>0)return '已订阅过，请到我的订阅管理下操作';
	}
	
	//运行订阅
	public function yunsubscribeAjax()
	{
		$id = (int)$this->get('id');
		$this->flow = m('flow')->initflow('subscribeinfo');
		return $this->flow->subscribe($id);
	}
}	
			