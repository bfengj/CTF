<?php
//便笺
class flow_bianjianClassModel extends flowModel
{
	
	
	public function flowrsreplace($rs,$isv=0)
	{
		$statestr = '';
		$zt 	= $rs['state'];
		$rs['stateval'] = $zt;
		if($zt>0){
			if($zt=='2')$rs['trstyle']='font-weight:bold';
			if($zt=='1')$rs['ishui']='1';
			$ztrs = $this->getststrsssa($zt);
			if($ztrs)$statestr = '<font color="'.$ztrs['color'].'">'.$ztrs['name'].'</font>';
		}
		$rs['state'] = $statestr;
		return $rs;
	}
	
	public function getststrsssa($zt)
	{
		$rs = false;
		$arrs = $this->statedatashow();
		foreach($arrs as $k1=>$rv1){
			if($rv1['value']==$zt){
				$rs = $rv1;
			}
		}
		return $rs;
	}

	public function statedatashow()
	{
		$arr[] = array('name'=>'无状态','value'=>'0','color'=>'');
		$arr[] = array('name'=>'等待完成','value'=>'2','color'=>'red');
		$arr[] = array('name'=>'已完成','value'=>'1','color'=>'green');
		return $arr;
	}
	
	public function flowbillwhere($uid, $lx)
	{
		//排序
		return array(
			//'order' => '`state` desc, `id` desc'
			'order' => '`suodt` desc'
		);
	}
}