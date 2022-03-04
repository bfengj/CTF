<?php
//基础模块应用
class agent_baseClassModel extends agentModel
{
	
	
	protected function agentrows($rows, $rowd, $uid)
	{
		$typearr = array();
		if($this->loadci==1 && $this->flow)$typearr = $this->flow->flowwesearchdata(1); //读取搜索的下拉框数据
		foreach($rowd as $k=>$rs){
			$rows[$k]['modenum'] = $this->agentnum;
		}
		
		if($this->flow && $this->flow->isflow>0)$rows = $this->agentrows_status($rows, $rowd);//显示流程状态
		
		return array(
			'rows' 	   => $rows,
			'typearr'  => $typearr,
		);
	}
}