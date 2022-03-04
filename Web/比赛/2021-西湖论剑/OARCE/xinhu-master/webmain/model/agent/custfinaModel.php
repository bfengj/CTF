<?php
//客户.收款单的应用
class agent_custfinaClassModel extends agentModel
{
	
	
	//状态显示替换
	protected function agentrows($rows, $rowd, $uid)
	{
		$statea = $this->flow->statearrs;
		foreach($rowd as $k=>$rs){
			$state 	 = $rs['paystatus'];
			$ztarr	 = $statea[$state];
			$rows[$k]['statustext']		= $ztarr[0];
			$rows[$k]['statuscolor']	= $ztarr[1];
		}
		return $rows;
	}
	
}