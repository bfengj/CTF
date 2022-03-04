<?php
//客户.跟进计划
class agent_custplanClassModel extends agentModel
{
	
	
	//状态显示替换
	protected function agentrows($rows, $rowd, $uid)
	{
		$ztarr = $this->flow->getstatusarr();
		foreach($rowd as $k=>$rs){
			$ztarra = $ztarr[$rs['statusval']];
			$rows[$k]['statustext']		= $ztarra[0];
			$rows[$k]['statuscolor']	= $ztarra[1];
		}
		return $rows;
	}
	
	public function gettotal()
	{
		$stotal	= $this->gettotalss($this->adminid);
		$titles	= '';
		return array('stotal'=>$stotal,'titles'=> $titles);
	}
	
	private function gettotalss($uid)
	{
		$to = m('custplan')->rows('`uid`='.$uid.' and `status`=0');
		return $to;
	}
	
	protected function agenttotals($uid)
	{
		return array(
			'mywwc' => $this->gettotalss($uid)
		);
	}
}