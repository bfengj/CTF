<?php
class flow_hrshebaoClassModel extends flowModel
{
	protected $flowcompanyidfieds	= 'none'; //不要多单位判断，是共享的
	
	public function initModel()
	{
		
	}
	
	public function flowrsreplace($rs, $lx=0)
	{
		$month = date('Y-m');
		if($rs['status']=='0' || $rs['enddt']<$month || $rs['startdt']>$month)$rs['ishui']=1;
		return $rs;
	}
}