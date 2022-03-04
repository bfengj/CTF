<?php
class flow_waichuClassModel extends flowModel
{
	protected function flowgetfields($lx)
	{
		$arr['location'] 		= '此时间定位打卡';
		return $arr;
	}
	
	public function flowrsreplace($rs, $lx=0)
	{
		if($lx==1){
			$s = $this->getdklocation($rs['outtime'], $rs['intime']);
			$rs['location'] = $s;
		}
		
		return $rs;
	}
	
	private function getdklocation($sdt,$edt)
	{
		$rows 	= $this->db->getrows('[Q]location',"`uid`='$this->uid' and `type`=0 and `optdt`>='$sdt' and `optdt`<='$edt'",'location_x,location_y,scale,label,`precision`,`optdt`,`id`');
		$s 		= '';
		foreach($rows as $k=>$rs){
			$s.=''.($k+1).'.'.$rs['label'].'('.$rs['optdt'].') <a href="javascript:;" onclick="js.locationshow('.$rs['id'].')" class="zhu">查看</a><br>';
		}
		return $s;
	}
	
	protected function flowbillwhere($uid, $lx)
	{
		$month	= $this->rock->post('month');
		$where 	= '';
		if($month!=''){
			$where.=" and `outtime` like '$month%'";
		}

		return array(
			'where' => $where
		);
	}
	
	
}