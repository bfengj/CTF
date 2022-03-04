<?php
/**
*	此文件是流程模块【godepot.仓库管理】对应控制器接口文件。
*/ 
class mode_godepotClassAction extends inputAction{
	
	
	protected function savebefore($table, $arr, $id, $addbo){
		
	}
	
	
	protected function saveafter($table, $arr, $id, $addbo){
		$name = $arr['depotname'];
		m('goodm')->update("`custname`='$name'","`type`=3 and `custid`='$id'");//更新调拨单选择的仓库名称
	}
	
	//从新统计仓库下的物品
	public function retotalAjax()
	{
		$db1 = m('godepot');
		$db2 = m('goodss');
		$rows = $db1->getall('1=1');
		foreach($rows as $k=>$rs){
			$wpshu = 0;
			$sql = 'SELECT `aid`,sum(`count`) as stttso FROM `[Q]goodss` where `depotid`='.$rs['id'].' GROUP BY aid';
			$ros1 = $this->db->getall($sql);
			foreach($ros1 as $k1=>$rs1)if($rs1['stttso']>0)$wpshu++;
			$db1->update('`wpshu`='.$wpshu.'', $rs['id']);
		}
	}
}	
			