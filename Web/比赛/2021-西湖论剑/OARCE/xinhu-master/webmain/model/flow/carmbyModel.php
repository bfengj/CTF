<?php
//车辆保养
class flow_carmbyClassModel extends flowModel
{
	
	public function flowrsreplace($rs)
	{
		if(isset($rs['carnum'])){
			$ors 	= $rs;
		}else{
			$ors 	= m('carm')->getone($rs['carid']);
		}
		if($ors)$rs['carid'] = ''.$ors['carbrand'].','.$ors['carmode'].'('.$ors['carnum'].')';
		
		return $rs;
	}


	//多个连表查询
	public function flowbillwhere($uid, $lx)
	{
		return array(
			'table' 		=> '`[Q]'.$this->mtable.'` a left join `[Q]carm` b on a.carid=b.id left join `[Q]flow_bill` c on c.`table`=\''.$this->mtable.'\' and c.mid=a.id',
			'fields'		=> 'a.*,b.carnum,b.carbrand,b.carmode,cartype,c.uname as base_name,c.udeptname as base_deptname',
			'orlikefields'	=> 'b.carnum,b.carbrand,b.carmode,b.`cartype`,c.`udeptname`,c.`uname`,c.`sericnum`@1',
			'asqom'			=> 'a.'
		);
	}
	
	//自定义审核人读取
	protected function flowcheckname($num){
		$sid = '';
		$sna = '';
		//驾驶员审核读取
		if($num=='jia'){
			$sid = $this->rs['jiaid'];
			$sna = $this->rs['jianame'];
		}
		return array($sid, $sna);
	}
}