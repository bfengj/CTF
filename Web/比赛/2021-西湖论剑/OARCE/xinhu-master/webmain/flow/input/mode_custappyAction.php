<?php
/**
*	此文件是流程模块【custappy.客户申请使用】对应控制器接口文件。
*/ 
class mode_custappyClassAction extends inputAction{
	
	
	protected function savebefore($table, $arr, $id, $addbo){
		//判断有没有人申请
		$custid = (int)arrvalue($arr, 'custid', '0');
		if($custid==0)return '请选择客户';
		//判断是不是公海库的
		$coot	= m('customer')->rows('id='.$custid.' and `uid`=0 and `isgh`=1');
		if($coot==0)return '此客户不在公海库里，不能申请';
		
		$coot 	= m($table)->rows("`custid`='$custid' and `status` =0 and `id`<>'$id'");
		if($coot>0)return '此客户已经有人去申请使用了';
	}
	
	protected function saveafter($table, $arr, $id, $addbo){
		
	}
	
	//公海数据
	public function custghaidata()
	{
		$ids  = '0';
		$mid  = (int)$this->get('mid','0');
		$arow = m('custappy')->getall('`status`=0');
		foreach($arow as $k1=>$rs1)$ids.=','.$rs1['custid'].'';
		
		$rows = m('customer')->getall('`id` not in('.$ids.') and `uid`=0 and `isgh`=1','`id` as `value`,`name`,`unitname`');
		if(!$rows)$rows[] = array('value'=>0,'name'=>'没有可选择的客户');
		//if($rows)foreach($rows as $k=>&$rs){
			//if(!isempt($rs['unitname']))$rs['name'] .= '('.$rs['unitname'].')';
		//}
		return $rows;
	}
}	
			