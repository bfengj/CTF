<?php
//薪资
class mode_hrsalaryClassAction extends inputAction{
	

	protected function savebefore($table, $arr, $id, $addbo){
		$month 	= $arr['month'];
		$lastdt = c('date')->getenddt($month);
		if($lastdt>$this->date)return ''.$month.'月份超前了，还没结束呢';
		$uname 	= $arr['uname'];
		$xuid 	= $arr['xuid'];
		if(m($table)->rows('id<>'.$id.' and `xuid`='.$xuid.' and `month`=\''.$month.'\'')>0)return ''.$month.'月份['.$uname.']的薪资已申请过了'.$lastdt.'';
	}
	
	
	protected function saveafter($table, $arr, $id, $addbo){
		
	}
	
	public function changeunameAjax()
	{
		$xuid = (int)$this->post('xuid');
		$a 	 = m('admin')->getone($xuid,'deptname,ranking');
		$this->returnjson($a);
	}
	
	public function changemonthAjax()
	{
		$xuid 	= (int)$this->post('xuid', $this->adminid);
		$month 	= $this->post('month');
		$one 	= m('hrsalary')->getone("`xuid`='$xuid' and `month`='$month'");
		$barr['mid'] 	= 0;
		$barr['xuid'] 	= $xuid;
		$barr['month'] 	= $month;
		if($one){
			$barr['mid'] = $one['id'];
		}else{
			
		}
		return $barr;
	}
	
	public function selectmonth()
	{
		$mfy = 2016;
		$mf1 = date('Y');
		for($y=$mf1;$y>=$mfy;$y--){
			$max = 12;
			if($y==$mf1)$max = (int)date('m');
			for($j=$max;$j>=1;$j--){
				$j1 = $j;
				if($j<10)$j1='0'.$j.'';
				$barr[] = array(
					'name'=>''.$y.'-'.$j1.'',
					'value'=>''.$y.'-'.$j1.''
				);
			}
		}
		return $barr;
	}
	
	
	/**
	*	薪资初始化，主要计算考勤
	*/
	public function initdatasAjax()
	{
		$xuid 	= (int)$this->post('xuid');
		$month 	= $this->post('month');
		$a		= m('kaoqin')->getkqtotal($xuid, $month);
		$lmonth	= c('date')->adddate($month.'-01','m',-1,'Y-m');
		$sfielss= 'base,postjt,skilljt,travelbt,telbt';
		
		$flow 	= m('flow')->initflow('hrsalary');
		
		$lrs 	= $flow->getone("`xuid`='$xuid' and `month`='$month'");
		if(!$lrs)$lrs 	= $flow->getone("`xuid`='$xuid' and `status`<>5", $sfielss,'`month` desc');
		
		$sm 	= '';
		if($lrs){
			$sfiels 	= explode(',',$sfielss);
			foreach($sfiels as $sfie)$a[$sfie] = $lrs[$sfie];
		}
		$urs 	= m('admin')->getone($xuid,'quitdt,workdate');
		if(contain($urs['workdate'],$month))$sm.=''.$urs['workdate'].'入职;';
		if(contain($urs['quitdt'],$month))$sm.=''.$urs['quitdt'].'离职;';
		$txrs 	= m('hrtrsalary')->getone("`uid`='$xuid' and `effectivedt` like '$month%' and `status`=1");
		if($txrs){
			$sm.=''.$txrs['effectivedt'].'起调薪'.$txrs['floats'].';';
			if($lrs){
				$a['postjt'] = floatval($a['postjt']) + floatval($txrs['floats']);//岗位工资加起来
			}
		}
		
		//奖励
		$a['reward'] = floatval(m('reward')->getmou('sum(money)', "`objectid`='$xuid' and `status`=1 and `type`=0 and `applydt` like '$month%'"));
		
		
		//处罚
		$a['punish'] = floatval(m('reward')->getmou('sum(money)', "`objectid`='$xuid' and `status`=1 and `type`=1 and `applydt` like '$month%'"));
		
		if($sm!='')$a['explain'] = $sm;
		
		//读取辅助固定值
		/*
		$farrr = $flow->getfiearrs($xuid, $month);
		foreach($farrr as $k1=>$rs1){
			foreach($rs1['fieldsarr'] as $k2=>$rs2){
				if($rs2['type']=='0')$a[$rs2['fields']] = $rs2['devzhi'];
			}
		}*/
		//print_r($farrr);
		
		
		
		//读取社保公积金
		$rows = m('hrshebao')->getall("`status`=1 and `startdt`<='$month' and `enddt`>='$month'");
		if($rows){
			foreach($rows as $k=>$rs)$rows[$k]['xuhao']=$k+1;
			$gxu = m('kaoqin')->getpipeimid($xuid, $rows, 'xuhao', -1);
			if($gxu>0)$gxu--;
			if(isset($rows[$gxu])){
				$qrs = $rows[$gxu];
				$a['socials'] 		= $qrs['shebaogeren'];
				$a['socialsunit'] 	= $qrs['shebaounit'];
				$a['gonggeren'] 	= $qrs['gonggeren'];
				$a['gongunit'] 		= $qrs['gongunit'];
			}
		}
		
		
		$this->returnjson($a);
	}
}	
			