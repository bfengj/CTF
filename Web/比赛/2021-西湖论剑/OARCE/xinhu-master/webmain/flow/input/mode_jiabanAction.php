<?php
class mode_jiabanClassAction extends inputAction{
	
	public function totalAjax()
	{
		$start	= $this->post('stime');
		$end	= $this->post('etime');
		$jiatype= (int)$this->post('jiatype');
		$date	= c('date', true);
		$sj		= $date->datediff('H', $start, $end);
		$jiafee	= 0;
		if($jiatype==1)$jiafee	= m('kaoqin')->jiafee($this->adminid, $sj, $start);
		
		$this->returnjson(array($sj, '', $jiafee));
	}
}	
			