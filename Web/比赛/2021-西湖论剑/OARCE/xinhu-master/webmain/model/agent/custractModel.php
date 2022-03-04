<?php
//客户合同的应用
class agent_custractClassModel extends agentModel
{
	
	
	//状态显示替换
	protected function agentrows($rows, $rowd, $uid)
	{
		$statea = $this->flow->statearr;
		foreach($rowd as $k=>$rs){
			$state 	 = $rs['htstatus'];
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