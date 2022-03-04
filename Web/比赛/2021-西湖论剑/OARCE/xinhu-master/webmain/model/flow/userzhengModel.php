<?php
//证书
class flow_userzhengClassModel extends flowModel
{
	
	
	public function flowrsreplace($rs,$lx=0)
	{
		if($rs['edt'] && $rs['edt']<$this->rock->date){
			$rs['ishui'] = 1;
			$rs['explain'].='已过期';
		}
		
		if($lx==2){
			if(!isempt($rs['fengmian']))$rs['fengmian']='<img height="60" src='.$rs['fengmian'].'>';
		}
		
		return $rs;
	}
	
}