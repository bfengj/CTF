<?php
/**
*	客户.收付款单
*/
class mode_custfinaClassAction extends inputAction{
	

	protected function savebefore($table, $arr, $id, $addbo){
		
		$narr	= array();
		$htid 	= (int)$arr['htid'];
		$money 	= floatval($arr['money']);
		if($money<=0)return '金额必须大于0';
		
		//从合同读取
		if($htid>0){
			$htrs = m('custract')->getone($htid);
			$narr['htnum'] 		= $htrs['num'];
			$narr['custid'] 	= $htrs['custid'];
			$narr['type'] 		= $htrs['type'];
			$narr['custname'] 	= $htrs['custname'];
			$zmoney				= floatval($htrs['money']);
			$omoney	= m('crm')->getmoneys($htid, $id);
			$chaojg	= $omoney + $money - $zmoney;
			if($chaojg>0)return '金额已超过合同上金额'.$zmoney.'';
		}
		
		//从销售单读取
		if($htid<0){
			$htrs = m('goodm')->getone('`id`='.(0-$htid).'');
			$narr['htnum'] 		= $htrs['num'];
			$narr['custid'] 	= $htrs['custid'];
			$narr['type'] 		= '0';
			$narr['custname'] 	= $htrs['custname'];
			$zmoney				= floatval($htrs['money']);
			$omoney	= m('crm')->getmoneys($htid, $id);
			$chaojg	= $omoney + $money - $zmoney;
			if($chaojg>0)return '金额已超过销售单上金额'.$zmoney.'';
		}
		$narr['htid'] = $htid;
		return array('rows'=> $narr);
	}
	
		
	protected function saveafter($table, $arr, $id, $addbo){
		$htid 	= (int)$arr['htid'];
		if($htid>0)m('crm')->ractmoney($htid);
		if($htid<0){
			$htrs = m('goodm')->getone('`id`='.(0-$htid).'');
			m('crm')->xiaozhuantai($htrs,1);
		}
	}
	
	public function selectcust()
	{
		$rows = m('crm')->getmycust($this->adminid, $this->rock->arrvalue($this->rs, 'custid'));
		return $rows;
	}
	
	public function hetongdata()
	{
		$htid = 0;
		$mid  = (int)$this->get('mid','0');
		if($mid>0){
			$htid = (int)$this->flow->getmou('htid', $mid); //当前记录也要显示合同ID
		}
		$rows = m('crm')->getmyract($this->adminid, $htid);
		$arr  = array();
		foreach($rows as $k=>$rs){
			$arr[] = array(
				'value' => $rs['id'],
				'optgroup'=>'合同',
				'name' 	=> '['.$rs['num'].']'.$rs['custname'],
			);
		}
		
		//读取我的销售单
		$rows = m('goodm')->getall('`uid`='.$this->adminid.' and `type`=2 and `status`=1 and `custractid`=0 and (`ispay`=0 or `id`='.(0-$htid).')');
		foreach($rows as $k=>$rs){
			$arr[] = array(
				'value' => '-'.$rs['id'],
				'optgroup'=>'销售单',
				'name' 	=> '['.$rs['num'].']'.$rs['custname'],
			);
		}
		
		return $arr;
	}
	
	public function ractchangeAjax()
	{
		$htid 	= (int)$this->get('ractid');
		$cars['type'] = '0';
		//销售单
		if($htid<0){
			$xrs = m('goodm')->getone('`id`='.(0-$htid).'');
			$cars['custid'] = $xrs['custid'];
			$cars['custname'] = $xrs['custname'];
			$cars['num'] = $xrs['num'];
			$cars['signdt'] = $xrs['applydt'];
			$cars['money'] = m('crm')->xiaozhuantai($xrs,1);
		}else{
			$cars 	= m('custract')->getone($htid, 'id,custid,custname,money,type,num,signdt');
			$omoney	= m('crm')->getmoneys($htid);
			$cars['money'] = $cars['money']-$omoney;
		}
		$this->returnjson($cars);
	}
}	
			