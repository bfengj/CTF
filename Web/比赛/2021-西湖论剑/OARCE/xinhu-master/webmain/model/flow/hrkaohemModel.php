<?php
//考核项目
class flow_hrkaohemClassModel extends flowModel
{
	public function initModel()
	{
		$this->pinlva['d'] = '每天';
		$this->pinlva['w'] = '每周一';
		$this->pinlva['m'] = '每月';
		$this->pinlva['j'] = '每季度';
		$this->pinlva['y'] = '每年';
	}
	

	
	public function flowrsreplace($rs, $lx=0)
	{
		$pinlv 		 = $rs['pinlv'];
		
		$rs['pinlv'] = arrvalue($this->pinlva, $pinlv);
		$sctime		 = $rs['sctime'];
		if($pinlv=='m'){
			$rs['sctime']  = $rs['pinlv'].date('d号H:i', strtotime($sctime));
		}
		if($pinlv=='j'){
			$rs['sctime']  = $rs['pinlv'].'首月的'.date('d号H:i', strtotime($sctime));
		}
		if($pinlv=='y'){
			$rs['sctime']  = $rs['pinlv'].date('m月d号H:i', strtotime($sctime));;
		}
		if($lx==2){
			$zbdata = $this->db->getall("select * from `[Q]hrkaohes` where `mid`='".$rs['id']."' order by `sort`");
			$str 	= '';
			foreach($zbdata as $k1=>$rs1)$str.=''.$rs1['itemname'].'('.$rs1['weight'].'%);';
			$rs['temp_zbcont'] = $str;
			
			$zbdata = $this->db->getall("select * from `[Q]hrkaohen` where `mid`='".$rs['id']."' order by `sort`");
			$str 	= '';
			foreach($zbdata as $k1=>$rs1)$str.=''.$rs1['pfname'].'('.$rs1['pfweight'].'%);';
			$rs['temp_pfren'] = $str;
		}
		return $rs;
	}

}