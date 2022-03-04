<?php

class mode_carmreseClassAction extends inputAction{
	
	
	protected function savebefore($table, $arr, $id, $addbo){
		$msg 	 	= '';
		$startdt 	= $arr['startdt'];
		$enddt 		= $arr['enddt'];
		$carid		= $arr['carid'];
		if($startdt>=$enddt)$msg='截止时间小于开始时间，不科学啊';
		if($msg==''){
			$tj1   = "`returndt` is null and ((`startdt`<='$startdt' and `enddt`>='$startdt') or (`startdt`<='$enddt' and `enddt`>='$enddt') or (`startdt`>='$startdt' and `enddt`<='$enddt'))"; //未归还
			
			$tj2   = "`returndt` is not null and ((`startdt`<='$startdt' and `returndt`>='$startdt') or (`startdt`<='$enddt' and `returndt`>='$enddt') or (`startdt`>='$startdt' and `returndt`<='$enddt'))"; //已归还
			
			$where = "id <>'$id' and `carid` = '$carid' and `status` in(0,1) and (($tj1) or ($tj2))";
			if(m($table)->rows($where)>0)$msg='车辆该时间段已被预定了';
		}
		return array('msg'=>$msg);
	}
	
	
	protected function saveafter($table, $arr, $id, $addbo){
		
	}
	
	
}	
			