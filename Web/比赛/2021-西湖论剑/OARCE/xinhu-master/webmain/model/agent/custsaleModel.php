<?php
class agent_custsaleClassModel extends agentModel
{
	
	public function gettotal()
	{
		$stotal	= $this->gettotalss($this->adminid);
		$titles	= '';
		return array('stotal'=>$stotal,'titles'=> $titles);
	}
	
	private function gettotalss($uid)
	{
		$to = m('custsale')->rows('`uid`='.$uid.' and `state`=0');
		return $to;
	}
	
	protected function agenttotals($uid)
	{
		return array(
			'gen' => $this->gettotalss($uid)
		);
	}
	
	protected function agentrows($rows, $rowd, $uid)
	{
		$statea = $this->flow->statearr;
		foreach($rowd as $k=>$rs){
			$state 	 = $rs['statess'];
			if($state==2){
				$rows[$k]['ishui']		=1;
			}
			$ztarr	 = $statea[$state];
			$rows[$k]['statustext']		= $ztarr[0];
			$rows[$k]['statuscolor']	= $ztarr[1];
		}
		return $rows;
	}
	
}