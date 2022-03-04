<?php
class salaryClassAction extends Action
{
	public function biaoshiffAjax()
	{
		$sid = $this->post('sid');
		if($sid=='')return;
		m('flow')->initflow('hrsalary')->gongzifafang($sid);
	}
	
	public function createdataAjax()
	{
		$month = $this->post('month');
		if($month=='')return;
		$lastdt = c('date')->getenddt($month);
		if($lastdt>$this->date)exit(''.$month.'月份超前了');
		$barr 	= m('flow')->initflow('hrsalary')->createdata($month);
		echo $barr;
	}
	
	//弃用启用
	public function xinzlfafter222($table, $rows)
	{
		$uids = '';
		foreach($rows as $k=>$rs){
			$uids.=','.$rs['xuid'].'';
		}
		if($uids!=''){
			$uids = substr($uids,1);
			$barr = $this->db->getarr('[Q]userinfo','id in('.$uids.')','`bankname`,`banknum`');
			foreach($rows as $k=>$rs){
				$brs = $this->rock->arrvalue($barr, $rs['xuid']);
				if($brs){
					$rows[$k]['bankname'] = $brs['bankname'];
					$rows[$k]['banknum'] = $brs['banknum'];
				}
			}
		}
		return array(
			'rows' => $rows
		); 
	}
	
	public function bumenafter($table, $rows)
	{
		foreach($rows as $k=>$rs){
			if($rs['isturn']==0){
				$rows[$k]['trbgcolor']='#fbe5d5';
			}else{
				$rows[$k]['checkdisabled']=true;
			}
		}
		return array(
			'rows' => $rows,
			'isdaochu' => m('view')->isdaochu($this->flow->modeid, $this->adminid)
		); 
	}
	
	public function xinziafter($table, $rows)
	{
		return array(
			'rows' => $rows,
			'isdaochu' => m('view')->isdaochu($this->flow->modeid, $this->adminid)
		); 
	}
	
	public function xinziafafter($table, $rows)
	{
		foreach($rows as $k=>$rs){
			if($rs['ispay']==1){
				$rows[$k]['checkdisabled']=true;
				$rows[$k]['ishui']=1;
			}
		}
		return array(
			'rows' => $rows,
			'isdaochu' => m('view')->isdaochu($this->flow->modeid, $this->adminid)
		); 
	}
}