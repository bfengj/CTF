<?php
/**
*	客户.合同管理
*/
class mode_custractClassAction extends inputAction{
	
	
	public function selectcust()
	{
		$rows = m('crm')->getmycust($this->adminid, $this->rock->arrvalue($this->rs, 'custid'));
		return $rows;
	}
	
	
	
	
	public function selectsale()
	{
		$rows = m('crm')->getmysale($this->adminid, (int)$this->get('mid'));
		$arr  = array();
		foreach($rows as $k=>$rs){
			$arr[] = array(
				'value' => $rs['id'],
				'name' 	=> '['.$rs['laiyuan'].']'.$rs['custname'],
			);
		}
		return $arr;
	}
	
	public function salechangeAjax()
	{
		$saleid = (int)$this->get('saleid');
		$cars 	= m('custsale')->getone($saleid, 'id,custid,custname,money,laiyuan');
		$this->returnjson($cars);
	}
	
	protected function savebefore($table, $arr, $id, $addbo){
		
		//判断是不是关联了销售单，金额就不能随便改了
		$rows = array();
		if($id>0){
			$xiaoid = (int)arrvalue($this->rs, 'xiaoid','0');
			if($xiaoid>0){
				$onrs = m('goodm')->getone('`id`='.$xiaoid.' and `status`<>5');
				if(!$onrs){
					$xiaoid = '0';
				}else{
					if($arr['type']!='0')return '此合同关联了销售单合同类型必须是“收款合同”';
					if(floatval($arr['money']) != floatval($onrs['money']))return '此合同关联了销售单，金额必须和销售单一致,合同金额('.$arr['money'].')，销售单金额('.$onrs['money'].')';
				}
			}
			$rows['xiaoid'] = $xiaoid;
		}
		
		return array(
			'rows' => $rows
		);
	}
	
	
	protected function saveafter($table, $arr, $id, $addbo){
		m('crm')->ractmoney($id); //计算未收/付款
		$saleid = (int)arrvalue($arr, 'saleid', '0');
		$dbs 	= m('custsale');
		$dbs->update('htid=0', "`htid`='$id'");
		if($saleid > 0){
			$dbs->update('`htid`='.$id.',`state`=1', "`id`='$saleid'");
			$jhrs = $dbs->getone($saleid);
			m($table)->update(array(
				'custid' => $jhrs['custid'],
				'custname' => $jhrs['custname'],
			), $id);
		}
		//同步更新收款单合同编号
		$htnum = arrvalue($arr,'num');
		m('custfina')->update("`htnum`='$htnum'", "`htid`='$id'");
		
		//替换word里的变量
		$htfileid 	= (int)arrvalue($arr,'htfileid','0');
		m('word')->replaceWord($htfileid, $arr);
	}
	
	public function remoneyAjax()
	{
		m('crm')->custractupzt();
	}
}	
			