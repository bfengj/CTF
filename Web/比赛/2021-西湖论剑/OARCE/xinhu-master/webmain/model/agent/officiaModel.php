<?php
//发文单
class agent_officiaClassModel extends agentModel
{
	
	//状态替换
	protected function agentrows($rows, $rowd, $uid){
		return $this->agentrows_status($rows, $rowd);
	}
	
}