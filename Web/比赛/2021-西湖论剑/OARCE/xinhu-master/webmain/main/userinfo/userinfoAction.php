<?php
class userinfoClassAction extends Action
{
	public function userinfobefore($table)
	{
		$table = '`[Q]admin` a left join `[Q]userinfo` b on a.id=b.id';
		$s 		= '';
		$key 	= $this->post('key');
		if($key!=''){
			$s = " and (a.`name` like '%$key%' or a.`user` like '%$key%' or a.`ranking` like '%$key%' or a.`deptname` like '%$key%') ";
		}
		if(ISMORECOM)$s.= ' and a.`companyid`='.m('admin')->getcompanyid().'';
		return array(
			'table' => $table,
			'where'	=> $s,
			'fields'=> 'a.name,a.deptname,a.id,a.status,a.ranking,b.id as ids,b.dkip,b.dkmac,b.iskq,b.isdwdk,b.isdaily,b.finger'
		);
	}
	
	public function userinfoafter($table, $rows)
	{
		$db = m($table);
		foreach($rows as $k=>$rs){
			if(isempt($rs['ids'])){
				$db->insert(array(
					'id' 		=> $rs['id'],
					'name' 		=> $rs['name'],
					'deptname' 	=> $rs['deptname'],
					'ranking' 	=> $rs['ranking']
				));
			}
		}
		return array('rows'=>$rows);
	}
	
	public function fieldsafters($table, $fid, $val, $id)
	{
		$fields = 'sex,ranking,tel,mobile,workdate,email,quitdt';
		if(contain($fields, $fid)){
			if($fid=='quitdt'){
				$dbs = m($table);
				if(isempt($val)){
					$dbs->update('`state`=0', "`id`='$id' and `state`=5");
				}else{
					$dbs->update('`state`=5', "`id`='$id'");
				}
			}
			m('admin')->update(array($fid=>$val), $id);
		}
	}

	
	public function userinfobeforegeren()
	{
		return ' and id='.$this->adminid.'';
	}
	
	//人员分析
	public function useranaybefore()
	{
		return 'and 1=2';
	}
	public function useranayafter($table, $rows)
	{
		$type 	= $this->post('type','deptname');
		$dt 	= $this->post('dt');
		$db		= m('userinfo');
		$where	= 'and state<>5';
		if($dt !=''){
			$where = "and ((state<>5 and workdate<='$dt') or (state=5 and workdate<='$dt' and  quitdt>'$dt'))";
		}
		$where .= m('admin')->getcompanywhere();
		
		$rows	= $db->getall("id>0 $where",'deptname,sex,xueli,state,birthday,workdate,quitdt,ranking');
		
		$nianls	= array(
			array(0,'16-20岁',16,20),
			array(0,'21-25岁',21,25),
			array(0,'26-30岁',26,30),
			array(0,'31-40岁',31,40),
			array(0,'41岁以上',41,9999),
			array(0,'其他',-555,15),
		);
		
		$yearls	= array(
			array(0,'1年以下',0,1),
			array(0,'1-3年',1,3),
			array(0,'3-5年',3,5),
			array(0,'5-10年',5,10),
			array(0,'10年以上',10,9999)
		);
		
		$atatea = explode(',', '试用期,正式,实习生,兼职,临时工,离职');
		foreach($rows as $k=>$rs){
			$year = '';
			if(!$this->isempt($rs['workdate'])) $year = substr($rs['workdate'],0,4);
			$rows[$k]['year'] = $year;
			
			$lian	= $this->jsnianl($rs['birthday']);
			foreach($nianls as $n=>$nsa){
				if( $lian >= $nsa[2]  && $lian <= $nsa[3]){
					$rows[$k]['nian'] = $nsa[1];
					break;
				}
			}
			
			$state = (int)$rs['state'];
			$rows[$k]['state'] = $atatea[$state];
			
			//入职连
			$nan = $this->worknx($rs['workdate']);
			foreach($yearls as $n=>$nsa){
				if( $nan >= $nsa[2]  && $nan < $nsa[3]){
					$rows[$k]['nianxian'] = $nsa[1];
					break;
				}
			}
		}
		
		$arr 	= array();
		$total 	= $this->db->count;
		foreach($rows as $k=>$rs){
			$val = $rs[$type];
			if($this->isempt($val))$val = '其他';
			if(!isset($arr[$val]))$arr[$val]=0;
			$arr[$val]++;
		}	
		
		$a		= array();
		foreach($arr as $k=>$v){
			$a[] = array(
				'name'	=> $k,
				'value'	=> $v,
				'bili'	=> ($this->rock->number($v/$total*100)).'%'
			);
		}

		return array(
			'rows' => $a,
			'totalCound' => count($a)
		);
	}
	
	private function jsnianl($dt)
	{
		$nY	= date('Y')+1;
		$lx	= 0;
		if(!$this->isempt($dt) && !$this->contain($dt, '0000')){
			$ss		= explode('-', $dt);
			$saa	= (int)$ss[0];
			$lx		= $nY - $saa;
		}
		return $lx	;
	}
	
	//计算工作年限的
	private function worknx($dt)
	{
		$w = 0;
		if(!$this->isempt($dt) && !$this->contain($dt, '0000')){
			$startt		= strtotime($dt);
			$date 		= date('Y-m-d');
			$endtime	= strtotime($date);
			$w			= (int)(($endtime - $startt) / (24*3600) / 365);
		}
		return $w;
	}
}