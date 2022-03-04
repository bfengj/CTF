<?php
class agent_jiankongClassModel extends agentModel
{
	public function initModel()
	{
		$this->settable('flow_bill');
	}
	
	
	protected function agentdata($uid, $lx)
	{
		$rows 	= m('flowbill')->getrecord($uid, $lx, $this->page, $this->limit);
		$modearr= array();
		if($this->loadci==1){
			$modeids = '0';
			$rows1 = m('view')->getjilu($uid);
			foreach($rows1 as $k1=>$rs1){
				$modeids.=','.$rs1['modeid'].'';
			}
			$modearr = m('mode')->getmodemyarr(0,'and `id` in('.$modeids.')');
		}
		return array(
			'rows' =>$rows,
			'modearr' =>$modearr,
		);
	}
	
}