<?php

class flow_goodsClassModel extends flowModel
{
	
	protected function flowchangedata(){
		$this->rs['typeid']	 = m('goods')->gettypename($this->rs['typeid']);
	}

	//导入数据的测试显示
	public function flowdaorutestdata()
	{
		return array(
			'typeid' 		=> '办公耗材/笔',
			'name' 			=> '红色粉笔',
			'num' 			=> 'WP-829',
			'guige' 		=> '红色',
			'xinghao' 		=> '5厘米',
			'price' 		=> '0.1',
			'unit' 			=> '盒',
			'stockcs' 		=> '20',
		);
	}
	
	public function flowxiangfields(&$fields)
	{
		$fields['stock'] 	 = '总库存';
		$where = '';
		if(ISMORECOM){
			$comid = arrvalue($this->rs,'comid','0');
			$where = ' and `comid`='.$comid.'';
		}
		$kcrow = m('godepot')->getall('1=1'.$where.'','*','`sort`');
		foreach($kcrow as $k1=>$rs1){
			$fields['stock_'.$rs1['id'].''] 	 = $rs1['depotname'];
		}
		return $fields;
	}
	
	//
	public function flowrsreplace($rs, $lx=0)
	{
		//详情页下显示对应仓库库存
		if($lx==1){
			$drows = $this->db->getall("SELECT `depotid`,sum(count)count FROM `[Q]goodss` where aid=".$rs['id']." and `status`=1 GROUP BY `depotid`");
			foreach($drows as $k1=>$rs1)$rs['stock_'.$rs1['depotid'].''] = $rs1['count'];
		}
		return $rs;
	}
	
	//导入之前
	public function flowdaorubefore($rows)
	{
		$inarr = array();
		$db 	= m('goods');
		$num 	= 'goodstype';
		if(ISMORECOM && $cnum=$this->adminmodel->getcompanynum())$num.='_'.$cnum.'';
		foreach($rows as $k=>$rs){
			$rs['typeid'] 	= $this->option->gettypeid($num,$rs['typeid']);
			
			//判断是否存在
			$odi 			= $db->existsgoods($rs);
			if($odi)continue;
			
			$rs['price']	= floatval($this->rock->repempt($rs['price'],'0')); //金额
			//$rs['stockcs']	= (int)$this->rock->repempt(arrvalue($rs,'stockcs','0')); //无用

			$inarr[] = $rs;
		}
		
		return $inarr;
	}
	
	//导入后处理（刷新库存）
	public function flowdaoruafter($ddoa=array())
	{
		//初始库存
		m('goods')->setstock();
	}
	
	//删除时
	protected function flowdeletebill($sm)
	{
		m('goodss')->delete('`aid`='.$this->id.'');
		m('goods')->setstock();
	}
}