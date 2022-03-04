<?php
class customerClassAction extends Action
{
	public function custtotalbefore($table)
	{
		$where 	= '';
		$uid 	= $this->adminid;
		$lx		= $this->post('atype');
		$month	= $this->month = $this->post('month',date('Y-m'));
		$key	= $this->post('key');
		if($lx=='my'){
			$where=' and `id`='.$uid.'';
		}
		if($lx=='down'){
			$s 		= m('admin')->getdownwheres('id', $uid, 0);
			$where 	=' and ('.$s.' or `id`='.$uid.')';
		}
		if($lx!='my' && $lx!='down'){
			$where  = m('admin')->getcompanywhere(5);
		}
		if($key!=''){
			$where .= m('admin')->getkeywhere($key);
		}
		return array(
			'fields'=> 'id,name,deptname',
			'where'	=> $where,
		);
	}
	
	public function custtotalafter($table,$rows)
	{
		$crm = m('crm');
		foreach($rows as $k=>$rs){
			$rows[$k]['month'] = $this->month;
			$toarr 	= $crm->moneytotal($rs['id'], $this->month);
			
			foreach($toarr as $f=>$v){
				if($v==0)$v='';
				$rows[$k][$f] = $v;
			}
		}
		return array(
			'rows' => $rows
		);
	}
	
	public function custtotalgebefore($table)
	{
		return 'and 1=2';
	}
	
	public function custtotalgeafter($t, $rows)
	{
		$rows 		 = array();
		$crm 		 = m('crm');
		$dtobj		 = c('date');
		$uid		 = $this->post('uid', $this->adminid);
		$urs 		 = m('admin')->getone($uid, 'name,deptname');
		$start		 = $this->post('startdt', date('Y-01'));
		$end		 = $this->post('enddt', date('Y-m'));
		$jgm 		 = $dtobj->datediff('m', $start.'-01', $end.'-01');
		for($i=0; $i<=$jgm; $i++){
			$month 	= $dtobj->adddate($end.'-01', 'm', 0-$i, 'Y-m');
			$arr['month'] 	= $month;
			$arr['name'] 	= $urs['name'];
			$arr['deptname']= $urs['deptname'];
			
			$toarr 	= $crm->moneytotal($uid, $month);
			foreach($toarr as $f=>$v){
				if($v==0)$v='';
				$arr[$f] = $v;
			}
			$rows[]	= $arr;
		}
		
		$barr['rows'] 		= $rows;
		$barr['totalCount'] = count($rows);
		return $barr;
	}
	
	//客户转移
	public function movecustAjax()
	{
		$sid 	= c('check')->onlynumber($this->post('sid'));
		$toid 	= (int)$this->post('toid');
		if($sid==''||$sid=='')return;
		m('crm')->movetouser($this->adminid, $sid, $toid);
	}
	
	public function retotalAjax()
	{
		m('crm')->custtotal();
	}
	
	
	//批量添加客户
	public function addplcustAjax()
	{
		$rows  	= c('html')->importdata('type,name,unitname,laiyuan,linkname,tel,mobile,email,address','type,name');
		$oi 	= 0;
		$db 	= m('customer');
		foreach($rows as $k=>$rs){
			$rs['adddt']	= $this->now;
			$rs['optdt']	= $this->now;
			$rs['status']	= 1;
			$rs['uid']		= $this->adminid;
			$rs['createid']		= $this->adminid;
			$rs['optname']		= $this->adminname;
			$rs['createname']	= $this->adminname;
			$db->insert($rs);
			$oi++;
		}
		backmsg('','成功导入'.$oi.'条数据');
	}
	
	//分配客户
	public function distcustAjax()
	{
		$sid 	= c('check')->onlynumber($this->post('sid','0'));
		$sname 	= $this->post('sname');
		$snid 	= $this->post('snid');
		$lx 	= $this->post('lx');
		$uarr['uid'] 	 = 0;
		if($lx==1 && $snid!='' && $sname!=''){
			$uarr['uid'] 	 = $snid;
			$uarr['suoname'] = $sname;
			$uarr['isgh'] 	 = '0';
			m('crm')->update($uarr, "`id` in($sid)");
		}
		if($lx==0){
			m('crm')->update($uarr, "`id` in($sid)");
		}
		echo 'ok';
	}
}