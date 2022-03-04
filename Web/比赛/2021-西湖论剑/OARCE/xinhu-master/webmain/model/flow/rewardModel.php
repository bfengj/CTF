<?php
class flow_rewardClassModel extends flowModel
{
	
	public function initModel(){
		$this->typearr		= array('<font color=green>奖励</font>','<font color=red>处罚</font>');
	}
	
	protected function flowcheckname($num)
	{
		if($num=='objectque'){
			return array($this->rs['objectid'], $this->rs['object']);
		}
	}
	
	public function flowsearchfields()
	{
		$arr[] = array('name'=>'申请人...','fields'=>'uid');
		$arr[] = array('name'=>'奖惩对象...','fields'=>'objectid');
		return $arr;
	}
	
	
	public function flowrsreplace($rs){
		$type 		= $rs['type'];
		$rs['type'] = $this->typearr[$type];
		return $rs;
	}
	

	protected function flowbillwhere($uid, $lx)
	{
		$key  	= $this->rock->post('key');
		$where 	= '';
		if($key!='')$where.=" and (b.udeptname like '%$key%' or b.`uname` like '%$key%' or a.`object` like '%$key%')";
		return array(
			'keywhere' => $where,
			'leftbill' => 1
		);
	}
}