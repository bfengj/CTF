<?php
class mode_carmClassAction extends inputAction{
	

	protected function savebefore($table, $arr, $id, $addbo){
		
	}
	
		
	protected function saveafter($table, $arr, $id, $addbo){
		$carnum = $arr['carnum'];
		m('carmrese')->update("`carnum`='$carnum'", "`carid`='$id'");
	}
}	
			