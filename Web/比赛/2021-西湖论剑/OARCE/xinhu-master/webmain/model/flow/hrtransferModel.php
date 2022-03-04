<?php
/**
*	人事模块.调动的
*/
class flow_hrtransferClassModel extends flowModel
{
	//审核完成处理
	protected function flowcheckfinsh($zt){
		m('hr')->hrrun();
	}

	protected function flowbillwhere($uid, $lx)
	{
		$key  	= $this->rock->post('key');
		$where 	= '';
		if($key!='')$where.=" and (tranname like '%$key%' or olddeptname like '%$key%' or newdeptname like '%$key%' or oldranking like '%$key%' or newranking like '%$key%' or `trantype`='$key')";
		return array(
			'where' => $where,
			'order' => '`optdt` desc'
		);
	}
}