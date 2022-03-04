<?php
//客户申请使用
class flow_custappyClassModel extends flowModel
{
	//流程全部完成后调用
	protected function flowcheckfinsh($zt)
	{
		if($zt==1){
			m('customer')->update(array(
				'isgh' => 0,
				'uid'	=> $this->uid
			),$this->rs['custid']);
		}
	}
}