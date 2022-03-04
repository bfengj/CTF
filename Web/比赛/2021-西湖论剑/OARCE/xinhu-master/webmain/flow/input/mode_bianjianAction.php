<?php
/**
*	此文件是流程模块【bianjian.便笺】对应控制器接口文件。
*/ 
class mode_bianjianClassAction extends inputAction{
	
	
	public function statedata()
	{
		$arr = $this->flow->statedatashow();
		return $arr;
	}
	
	public function homedataAjax()
	{
		$arr   = array();
		$startdt = $this->get('st1');
		$endddt  = $this->get('st2');
		$st1   = $startdt.' 00:00:00';
		$st2   = $endddt.' 23:59:59';
		$ztobj = m('flow:bianjian');
	
		$rowa  = m('bianjian')->getall("`uid`='$this->adminid' and `suodt`>='$st1' and `suodt`<='$st2'",'*','`suodt` asc');
		$rows  = array();
		foreach($rowa as $k=>$rs){
			$dta = explode(' ', $rs['suodt']);
			$dt  = $dta[0];
			$statestr = '';
			if($rs['state']>0){
				$ztrs = $ztobj->getststrsssa($rs['state']);
				if($ztrs)$statestr = '<font color="'.$ztrs['color'].'">('.$ztrs['name'].')</font>';
			}
			$rows[$dt][] = array(
				'content' => $rs['content'],
				'time' => substr($dta[1],0,5),
				'state'=> $statestr
			);
		}
		
		$to = m('mode')->rows("`num`='schedule' and `status`=1");
		if($to==1){
			$rcarr = m('schedule')->getlistdata($this->adminid, $startdt, $endddt);
			foreach($rcarr as $dt=>$dtrs){
				foreach($dtrs as $k=>$rs){
					$rows[$dt][] = array(
						'content' => $rs['title'],
						'time' => $rs['timea'],
						'state'=> '(日程)'
					);
				}
			}
		}
		
		
		$arr['rows']   = $rows;
		//$arr['rcarr']   = $rcarr;
		return $arr;
	}
}	
			