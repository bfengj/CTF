<?php
//客户收付款单
class flow_custfinaClassModel extends flowModel
{
	
	public function initModel(){
		$this->statearrs		= c('array')->strtoarray('未收款|red,已收款|green');
		$this->statearrf		= c('array')->strtoarray('未付款|red,已付款|green');
	}
	
	public function flowrsreplace($rs)
	{
		$starrr			= array('收','付');
		$rs['paystatus']	= $rs['ispay'];
		$ispay 			= '<font color=red>未'.$starrr[$rs['type']].'款</font>';
		if($rs['ispay']==1)$ispay = '<font color=green>已'.$starrr[$rs['type']].'款</font>';
		$rs['ispay']	 = $ispay;
		$rs['type']	 	 = ''.$starrr[$rs['type']].'款单';
		
		return $rs;
	}
	
	//操作菜单操作
	protected function flowoptmenu($ors, $arr)
	{
		//标识已付款处理
		if($ors['num']=='pay'){
			$ispay = 0;
			$paydt = arrvalue($arr,'fields_paydt', $this->rock->now);
			if(!isempt($paydt))$ispay = 1;
			$this->update("`ispay`='$ispay',`paydt`='$paydt'", $this->id);
			m('crm')->ractmoney($this->rs['htid']);
		}
	}
	
	protected function flowbillwhere($uid, $lx)
	{
		$month	= $this->rock->post('month');
		$where 	= '';
		if($month!=''){
			$where.=" and `dt` like '$month%'";
		}

		return array(
			'where' => $where,
			'order' => '`optdt` desc'
		);
	}
}