<?php
/**
*	此文件是流程模块【kqerr.打卡异常】对应接口文件。
*	可在页面上创建更多方法如：public funciton testactAjax()，用js.getajaxurl('testact','mode_kqerr|input','flow')调用到对应方法
*/ 
class mode_kqerrClassAction extends inputAction{
	

	protected function savebefore($table, $arr, $id, $addbo){
		
		$cshu= (int)m('option')->getval('kqerrapply',0); //每个月可申请次数，读取数据选项
		if($cshu>0){
			$uid = $arr['uid'];
			$dt  = substr($arr['dt'],0,7);
			$to  = m($table)->rows("`uid`='$uid' and `id`<>'$id' and `dt` like '".$dt."%'")+1;
			if($to>$cshu)return ''.$dt.'月份已申请超过'.$cshu.'次，不能在申请了';
		}
	}
	
	
	protected function saveafter($table, $arr, $id, $addbo){
		
	}
}	
			