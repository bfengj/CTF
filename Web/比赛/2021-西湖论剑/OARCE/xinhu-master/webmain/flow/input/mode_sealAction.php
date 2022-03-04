<?php
class mode_sealClassAction extends inputAction{
	

	protected function savebefore($table, $arr, $id, $addbo){
		
	}
	

	protected function saveafter($table, $arr, $id, $addbo){
		$name = $arr['name'];
		m('sealapl')->update("`sealname`='$name'", "`sealid`='$id'");
	}
}	
			