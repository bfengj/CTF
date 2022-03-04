<?php
/**
	通讯录显示用户模块的
*/
class agent_userClassModel extends agentModel
{
	
	protected function agentrows($rows, $rowd, $uid){
		foreach($rowd as $k=>$rs){
			unset($rows[$k]['id']);
			if($this->agentnum=='vcard')unset($rows[$k]['uid']);//个人通讯录不要头像
		}
		return $rows;
	}
	
}