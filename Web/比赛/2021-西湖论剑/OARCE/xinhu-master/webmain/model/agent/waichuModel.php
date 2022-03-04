<?php
//外出应用
class agent_waichuClassModel extends agentModel
{
	//状态替换
	protected function agentrows($rows, $rowd, $uid){
		return $this->agentrows_status($rows, $rowd);
	}
}