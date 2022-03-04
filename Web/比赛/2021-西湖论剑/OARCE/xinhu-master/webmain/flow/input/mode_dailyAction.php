<?php
class mode_dailyClassAction extends inputAction{
	
	
	protected function savebefore($table, $arr, $id, $addbo){
		
		$type 	= arrvalue($arr, 'type');
		$uid 	= arrvalue($arr, 'uid');
		
		$dt 	= arrvalue($arr, 'dt');
		if(isempt($dt))return '';
		$enddt 	= arrvalue($arr, 'enddt');
		$where  = "id<>$id and `uid`=$uid and `type`='$type' and `dt`='$dt'";
		if(!isempt($enddt))$where.=' and `enddt`='.$enddt.'';
		if($type==0 && $dt>$this->date)return '日期['.$dt.']还是个未来呢';
		$to 	= $this->mdb->rows($where);
		if($to>0)return '该类型日期['.$dt.']段已申请了';
	}
	
		
	protected function saveafter($table, $arr, $id, $addbo){
		
	}
	
	public function getdtstrAjax()
	{
		$type 	= (int)$this->post('type');
		$dt 	= $this->post('dt');
		$dta 	= explode('-', $dt);
		$dtobj	= c('date');
		$startdt= $dt;
		$enddt  = '';
		
		if($type==1){
			$dtw = $dtobj->getweekarr($dt);
			$startdt= $dtw[0];
			$enddt  = $dtw[6];
		}
		if($type==2){
			$startdt= ''.$dta[0].'-'.$dta[1].'-01';
			$enddt= $dtobj->getenddt($dt);
		}
		if($type==3){
			$startdt= ''.$dta[0].'-01-01';
			$enddt	=  ''.$dta[0].'-12-31';
		}
		$a = array($startdt, $enddt);
		$this->returnjson($a);
	}
	
	
	public function anxyfxbeforershow($table)
	{
		$dt1			= $this->post('dt1', date('Y-m'));
		$this->months 	= $dt1;
		$key	= $this->post('key');
		$atype	= $this->post('atype');
		$isdaily= $this->post('isdaily','1');
		$dbs 	= m('admin');
		$s 		= $dbs->monthuwhere($dt1,'a.');
		if($atype=='my'){
			$s = 'and a.`id`='.$this->adminid.'';
		}else{
			if($isdaily=='1')$s.=" and a.`isdaily`=$isdaily";
			$s.= m('admin')->getcompanywhere(5,'a.');
		}
		if($atype=='down'){
			$dids = $dbs->getdown($this->adminid, 1);
			if(isempt($dids))$dids='0';
			$s.= ' and a.`id` in('.$dids.')';
		}
		
		if(!isempt($key))$s.=" and (a.`name` like '%$key%' or a.`ranking` like '%$key%' or a.`deptname` like '%$key%')";
		$table  = "`[Q]userinfo` a left join `[Q]dailyfx` b on a.id=b.uid and b.`month`='$dt1'";
		
		$fields = 'a.id,a.name,a.deptname,a.ranking,a.workdate,a.state,b.*';
		return array(
			'where' =>$s,
			'fields'=>$fields,
			'order'=>'a.`id`',
			'table'=> $table
		);
	}
	
	public function anxyfxaftershow($table, $rows)
	{
		$zta 	= m('flow:userinfo');
		$maxjg	= c('date')->getmaxdt($this->months);
		//0未写,1已写,2请假,3休息日,4没入职或已离职,5不需要写日报,时间还没到
		foreach($rows as $k=>$rs){
			if($rs['state']==5)$rows[$k]['ishui']=1;
			$rows[$k]['state'] = $zta->getuserstate($rs['state']);
			
			for($i=1;$i<=$maxjg;$i++){
				$zt  = arrvalue($rs,'day'.$i.'');
				$oi  = ($i<10)?'0'.$i.'':$i;
				$dt  = $this->months.'-'.$oi;
				$str = '';
				if($dt<$this->date){
					if($zt=='0')$str='×';
					if($zt=='1')$str='<font color=green>√</font>';
					if($zt=='2')$str='<font color=#888888>假</font>';
					if($zt=='6')$str='<font color=green>◇</font>';
				}
				$rows[$k]['day'.$i.''] = $str;
			}
		}
		
	
		return array(
			'rows' => $rows,
			'maxjg'=> $maxjg,
			'week' => date('w', strtotime($this->months.'-01'))
		);
	}
	
	//日报统计分析
	public function dailyfxAjax()
	{
		$dt 	= $this->post('dt');
		$atype 	= $this->post('atype');
		$uid	= ($atype=='my') ? $this->adminid : 0;
		m('flow:daily')->dailyanay($uid, $dt);
	}
	
	//昨天未写显示
	public function ztweixiedatabefore($table)
	{
		$dt 	= c('date')->adddate($this->rock->date, 'd', -1);
		$dt 	= $this->post('dt', $dt);
		$this->ztdt = $dt;
		$dta 	= explode('-', $dt);
		$month 	= substr($dt, 0,7);
		$d 	 	= (int)$dta[2];
		$where 	= m('admin')->getcompanywhere(5,'a.');
		return array(
			'table' => '`[Q]dailyfx` b left join `[Q]userinfo` a on a.`id`=b.`uid`',
			'where'	=> "and b.`month`='$month' and b.`day".$d."`=0 ".$where."",
			'fields'=> 'b.totalw,b.totalx,b.totaly,a.name,a.deptname'
		);
	}
	
	public function ztweixiedataafter($table, $rows)
	{
		return array(
			'ztdt' => $this->ztdt.',周'.c('date')->cnweek($this->ztdt).''
		);
	}
	
	//部门统计
	public function bmztweixiedatabefore($table)
	{
		$dt 	= c('date')->adddate($this->rock->date, 'd', -1);
		$dt 	= $this->post('dt', $dt);
		$this->ztdt = $dt;
		$dta 	= explode('-', $dt);
		$month 	= substr($dt, 0,7);
		$d 	 	= (int)$dta[2];
		$where 	= m('admin')->getcompanywhere(5,'a.');
		return array(
			'table' => '`[Q]dailyfx` b left join `[Q]userinfo` a on a.`id`=b.`uid`',
			'where'	=> "and b.`month`='$month' and b.`day".$d."`=0 $where",
			'fields'=> 'count(1) as value,a.deptname as name',
			'order' => '`value`',
			'group'	=> 'a.deptname'
		);
	}
	public function bmztweixiedataafter($table, $rows)
	{
		return array(
			'rows' => $rows
		);
	}
}	
			