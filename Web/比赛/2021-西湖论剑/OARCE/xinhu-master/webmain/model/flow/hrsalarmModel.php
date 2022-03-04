<?php
//薪资模版
class flow_hrsalarmClassModel extends flowModel
{
	protected $flowcompanyidfieds	= 'none'; //不要多单位判断，是共享的
	
	public function initModel()
	{
		
	}
	
	protected function flowbillwhere($uid, $lx)
	{
		
		return array(
			'order' => '`sort`,`id`'
		);
	}
	
	//读取字段
	public function xinzifields()
	{
		$mid 	= m('flow_set')->getmou('id',"`num`='hrsalary'");
		$rows  	= m('flow_element')->getall("`mid`='$mid'",'*','iszb,sort');
		$barr 	= array();
		$nofar 	= explode(',','uname,udeptname,postjt,mones,money,ranking,month,ispay,explain,isturn,status');
		foreach($rows as $k=>$rs){
			if(in_array($rs['fields'], $nofar))continue;
			$barr[] = array(
				'name'=>$rs['name'],
				'value'=>$rs['fields'],
				'subname'=>$rs['fields']
			);
		}
		
		return $barr;
	}
		
		
	public function flowrsreplace($rs, $lx=0)
	{
		$month = date('Y-m');
		if($rs['status']=='0' || $rs['enddt']<$month || $rs['startdt']>$month)$rs['ishui']=1;
		return $rs;
	}	
}