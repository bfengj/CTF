<?php
/**
	新闻资讯
*/
class agent_newsClassModel extends agentModel
{
	
	
	protected function agentrows($rows, $rowd, $uid){
		$typearr = array();
		if($this->loadci==1)$typearr = $this->flow->flowwesearchdata(1);
		return array(
			'rows' =>$rows,
			'typearr' =>$typearr,
		);
	}
}