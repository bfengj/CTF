<?php

class flow_custsaleClassModel extends flowModel
{
	public function initModel(){
		$this->statearr		 = c('array')->strtoarray('跟进中|blue,已成交|green,已丢失|#888888,暂缓|#ff6600');
	}

	
	public function flowrsreplace($rs)
	{
		$zt = $this->statearr[$rs['state']];
		$rs['statess']	 = $rs['state'];
		$rs['state']	 = '<font color="'.$zt[1].'">'.$zt[0].'</font>';
		if($rs['htid']>0)$rs['state'].=',<font color=#888888>并建立合同</font>';
		return $rs;
	}
	
	protected function flowsubmit($na, $sm)
	{
		m('crm')->update('`lastdt`=now()', $this->rs['custid']);
	}
	
	protected function flowoptmenu($ors, $crs)
	{
		$zt  = $ors['statusvalue'];
		$num = $ors['num'];
		if($num=='ztqh'){
			$sarr['state'] = $zt;
			if($zt==1)$sarr['dealdt'] = $this->rock->now;	
			$this->update($sarr, $this->id);
		}
		
		if($num=='zhuan'){
			$cname 	 = $crs['cname'];
			$cnameid = $crs['cnameid'];
			$this->update(array(
				'uid' 		=> $cnameid,
				'optname' 	=> $cname
			), $this->id);
			$this->push($cnameid, '客户销售', ''.$this->adminname.'将一个客户【{custname}】的一个销售单转移给你');
		}
		
		if($num=='genjin' || $num=='ztqh'){
			m('crm')->update('`lastdt`=now()', $this->rs['custid']);
		}
	}
	
	protected function flowbillwhere($uid, $lx)
	{
		return array(
			'order' => '`state`,`optdt` desc'
		);
	}
}