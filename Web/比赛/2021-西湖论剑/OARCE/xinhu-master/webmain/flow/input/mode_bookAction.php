<?php
class mode_bookClassAction extends inputAction{
	

	protected function savebefore($table, $arr, $id, $addbo){
		
	}
		
	protected function saveafter($table, $arr, $id, $addbo){
		$title = $arr['title'];
		m('bookborrow')->update("`bookname`='$title'", "`bookid`='$id'");
	}
}	
			