<?php
/**
*	此文件是流程模块【officic.公文查阅】对应控制器接口文件。
*/ 
class mode_officicClassAction extends inputAction{
	
	public function storeafter($table, $rows)
	{
		return array(
			'isadd'    => false
		);
	}
	
	//统计
	public function tongjiDataAjax()
	{
		$columns = $rows = array();
		$dtobj 		= c('date');
		$startdt	= $this->post('startdt', $this->date);
		$enddt		= $this->post('enddt');
		if($enddt=='')$enddt = $dtobj->adddate($startdt,'d',7);
		$jg 		= $dtobj->datediff('d',$startdt, $enddt);
		if($jg>30)$jg = 30;
		$atype		= $this->post('atype');
		$where 		= ' and `uid`='.$this->adminid.'';
		if($atype=='all')$where='';
		$darr 		= $this->db->getall("SELECT type,applydt,count(1)as stotal FROM `[Q]official` where `status`=1 and `applydt` between '$startdt' and '$enddt' $where group by type,applydt;");
		$carr		= array();
		foreach($darr as $k=>$rs){
			$carr[$rs['applydt']][$rs['type']] = floatval($rs['stotal']);
		}
		
		$dt 		= $startdt;
		$fwshuz = $swshuz = 0;
		for($i=0; $i<=$jg; $i++){
			$fwshu = $swshu = 0;
			if($i>0)$dt = $dtobj->adddate($dt,'d',1);
			$w 		= $dtobj->cnweek($dt);
			$sbarr	= array(
				'dt' 		=> $dt,
				'week' 		=> $w,
			);
			if(isset($carr[$dt]) && isset($carr[$dt][0]))$fwshu = $carr[$dt][0];
			if(isset($carr[$dt]) && isset($carr[$dt][1]))$swshu = $carr[$dt][1];
			
			if($fwshu>0)$sbarr['fwshu'] = $fwshu;
			if($swshu>0)$sbarr['swshu'] = $swshu;
			$fwshuz+=$fwshu;
			$swshuz+=$swshu;
			$rows[] = $sbarr;
		}
		$sbarr	= array(
			'dt' 		=> '合计',
			'fwshu' 		=> $fwshuz,
			'swshu' 		=> $swshuz,
		);
		$rows[] = $sbarr;
		return array(
			'rows' => $rows,
			'columns' => $columns,
			'startdt' => $startdt,
			'enddt' => $enddt,
			'totalCount' => count($rows),
			'downCount' => count($rows),
		);
	}
}	
			