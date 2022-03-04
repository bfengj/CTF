<?php
//项目的应用
class agent_projectClassModel extends agentModel
{
	
	public function gettotal()
	{
		$stotal	= $this->getwdtotal($this->adminid);
		$titles	= '';
		return array('stotal'=>$stotal,'titles'=> $titles);
	}
	
	private function getwdtotal($uid)
	{
		$to	= m('flow')->initflow('project')->getflowrows($uid,'wwc', 0);
		return $to;
	}
	
	//显示页面未完成数量
	protected function agenttotals($uid){
		return array(
			'wwc' => $this->getwdtotal($uid)
		);
	}
	
	protected function agentrows($rows, $rowd, $uid){
		return $this->agentrows_status($rows, $rowd);
	}
	
	
}