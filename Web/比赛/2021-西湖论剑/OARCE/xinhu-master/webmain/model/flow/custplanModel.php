<?php
class flow_custplanClassModel extends flowModel
{

	//替换
	public function flowrsreplace($rs, $slx=0){
		
		$zts 		= $rs['status'];
		$str 		= $this->getstatus($rs,'','',1);
	
		$rs['statusval']= $zts;
		$rs['status']	= $str;
		return $rs;
	}
	
	
	//计划跟进提醒（一条提醒一次）
	public function plantodo()
	{
		$date = $this->rock->date;
		$rows = $this->getall("`status`=0 and `plandt` like '".$date."%'");
		foreach($rows as $k=>$rs){
			$this->id = $rs['id'];
			$this->pushs($rs['uid'], '客户“'.$rs['custname'].'”需要在'.$rs['plandt'].'用“'.$rs['gentype'].'”跟进');
		}
	}
	
	protected function flowoptmenu($ors, $crs)
	{
		if($ors['num']=='bywc'){
			$findt = arrvalue($this->rs, 'findt', $this->rock->now);
			m('customer')->update("`lastdt`='{$findt}'", $this->rs['custid']);
		}
	}
	
}