<?php
class agent_meetClassModel extends agentModel
{
	
	private $joinwhere='';
	
	public function initModel()
	{
		$this->dtobj 		= c('date');
	}
	
	//今日会议
	public function getstotal($uid, $dt)
	{
		if($this->joinwhere=='')$this->joinwhere	= m('admin')->getjoinstr('joinid', $uid);
		$where 	= "`status`=1 and `type`=0 and `startdt` like '$dt%' and joinid is not null $this->joinwhere and `state`<2";
		$sto 	= m('meet')->rows($where);
		return $sto;
	}
	
	public function gettotal()
	{
		$stotal = $this->getstotal($this->adminid, $this->rock->date);
		return array('stotal'=>$stotal,'titles'=>'');
	}
	
	protected function agentrows($rows, $rowd, $uid)
	{
		$hyarra 	= $this->flow->hyarra;
		$hyarrb 	= $this->flow->hyarrb;
		if(!in_array($this->event, array('week','today'))){
			if($rowd)foreach($rowd as $k=>$rs){
				$nzt = $rs['nzt'];
				$rows[$k]['statustext'] 	= $hyarra[$nzt];
				$rows[$k]['statuscolor'] 	= $hyarrb[$nzt];
			}
		}
		return $rows;
	}
	
	protected function agentdata($uid, $lx)
	{
		$row = array();
		$dt  = $this->rock->date;
		if($this->joinwhere=='')$this->joinwhere	= m('admin')->getjoinstr('joinid', $uid);
		//本周
		if($lx=='week'){
			$warr = $this->dtobj->getweekarr($dt);
			foreach($warr as $dts){
				$rows = $this->getweek($dts, $uid);
				$row  = array_merge($row, $rows);
			}
		//今日	
		}else if($lx=='today'){
			$row 		= $this->getweek($dt, $uid);
		}else{
			return false;
		}
		$arr['rows'] 	= $row;
		return $arr;
	}
	
	protected function agenttotals($uid)
	{
		return array(
			'today' => $this->getstotal($uid, $this->rock->date)
		);
	}
	
	//数据处理
	private function gethuiwdata($rows, $dbs)
	{
		$row		= array();	
		$hyarra 	= $this->flow->hyarra;
		$hyarrb 	= $this->flow->hyarrb;
		$time 		= time();
		foreach($rows as $k=>$rs){
			$zt 	= $rs['state'];
			$nzt 	= $zt;
			$stime 	= strtotime($rs['startdt']);
			$etime 	= strtotime($rs['enddt']);
			if($zt < 2){
				if($etime<$time){
					$nzt = 2;
				}else if($stime>$time){
					$nzt = 0;
				}else{
					$nzt = 1;
				}
			}
			$rows[$k]['statustext'] 	= $hyarra[$nzt];
			$rows[$k]['statuscolor'] 	= $hyarrb[$nzt];
			$cont 	= '会议室：'.$rs['hyname'].'';
			$cont 	.= '<br>发起人：'.$rs['optname'].'';
			if(!$this->isempt($rs['joinname']))$cont.= '<br>参会人：'.$rs['joinname'].'';
			$rows[$k]['cont'] 			= $cont;
			if($nzt>1){
				$rows[$k]['ishui']		= 1;
			}
			$rows[$k]['optdt'] = substr($rs['startdt'],11).'至'.substr($rs['enddt'],11);
			unset($rows[$k]['startdt']);
			unset($rows[$k]['enddt']);
			unset($rows[$k]['joinname']);
			unset($rows[$k]['optname']);
			unset($rows[$k]['hyname']);
			
			$row[] = $rows[$k];
			if($zt != $nzt)$dbs->update('state='.$nzt.'', $rs['id']);
		}
		return $row;
	}
	
	//获取今日和本周会议的个人
	private function getweek($dt, $uid)
	{
		$week 		= $this->dtobj->cnweek($dt);
		$dbs 		= m('meet');
		$rows 		= $dbs->getrows("`status`=1 and `type`=0 and `startdt` like '$dt%' and joinid is not null $this->joinwhere",'hyname,title,optid,startdt,enddt,state,joinname,optname,id','`startdt` asc');
		if($rows)$row[] = array(
			'showtype' 	=> 'line',
			'title'		=> ''.$dt.'(周'.$week.')的会议'
		);
		return $this->gethuiwdata($rows, $dbs);
	}
}