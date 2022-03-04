<?php
class agent_customerClassModel extends agentModel
{
	
	public function gettotal()
	{
		$stotal	= $this->gettotalss($this->adminid);
		$titles	= '';
		return array('stotal'=>$stotal,'titles'=> $titles);
	}
	
	private function gettotalss($uid)
	{
		$to = 0;
		return $to;
	}
	
	protected function agentrows($rows, $rowd, $uid)
	{
		foreach($rowd as $k=>$rs){
			if($rs['statuss']==0){
				$rows[$k]['statustext']='已停用';
				$rows[$k]['statuscolor']='#888888';
				$rows[$k]['ishui']		=1;
			}
		}
		return $rows;
	}
}