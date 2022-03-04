<?php
//收文单
class agent_officibClassModel extends agentModel
{
	
	//状态替换
	protected function agentrows($rows, $rowd, $uid){
		return $this->agentrows_status($rows, $rowd);
	}
	
}