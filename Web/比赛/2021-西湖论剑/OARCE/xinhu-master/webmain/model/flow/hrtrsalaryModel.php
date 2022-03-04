<?php
/**
*	人事模块.调薪
*/
class flow_hrtrsalaryClassModel extends flowModel
{

	protected function flowbillwhere($uid, $lx)
	{
		$key  	= $this->rock->post('key');
		$where 	= '';
		if($key!='')$where.=" and (b.udeptname like '%$key%' or b.`uname` like '%$key%')";
		return array(
			'keywhere' => $where,
			'leftbill' => 1
		);
	}
}