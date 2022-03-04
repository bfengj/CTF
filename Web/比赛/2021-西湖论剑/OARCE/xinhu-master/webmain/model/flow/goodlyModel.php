<?php
class flow_goodlyClassModel extends flowModel
{
	public function initModel()
	{
		$this->goodsobj = m('goods');
	}
	
	//审核完成处理
	protected function flowcheckfinsh($zt){
		/*
		m('goodss')->update('status='.$zt.'',"`mid`='$this->id'");
		$aid  = '0';
		$rows = m('goodss')->getall("`mid`='$this->id'",'aid');
		foreach($rows as $k=>$rs)$aid.=','.$rs['aid'].'';
		m('goods')->setstock($aid);*/
	}

	//作废或删除时
	protected function flowzuofeibill($sm)
	{
		//删除出库详情的
		m('goodss')->delete("`mid`='$this->id'");
	}
	
	
	//子表数据替换处理
	protected function flowsubdata($rows, $lx=0){
		$db = m('goods');
		$lygya = array('','需要','已归还');
		foreach($rows as $k=>$rs){
			$one = $db->getone($rs['aid']);
			if($one){
				$name 	= $one['name'];
				if(!isempt($one['xinghao']))$name.='('.$one['xinghao'].')';
				if($lx==1){
					$rows[$k]['aid'] 	= $name;
					$rows[$k]['count'] 	= 0-$rs['count']; //负数显示为正数
					if(isset($rs['lygh'])){
						$rows[$k]['lygh']=arrvalue($lygya, $rs['lygh']);
						
					}
				}
				$rows[$k]['unit'] 	= $one['unit'];
				$rows[$k]['temp_aid'] = $name;
			}
		}
		return $rows;
	}
	
	//$lx,0默认,1详情展示，2列表显示
	public function flowrsreplace($rs, $lx=0)
	{
		$rs['states']= $rs['state'];
		$rs['state'] = $this->goodsobj->crkstate($rs['state'],1);
		
		//读取物品
		if($lx==2){
			$rs['wupinlist'] = $this->goodsobj->getgoodninfo($rs['id'], 1);
		}
		return $rs;
	}
}