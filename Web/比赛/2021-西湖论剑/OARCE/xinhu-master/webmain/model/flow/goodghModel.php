<?php
//物品归还
class flow_goodghClassModel extends flowModel
{
	public function initModel()
	{
		$this->goodsobj = m('goods');
	}
	
	//标识已完成了审核归还
	protected function flowcheckfinsh($zt)
	{
		if($zt!=1)return;
		$mid = $this->rs['custid'];
		m('goodn')->update('`lygh`=2','`mid`='.$mid.' and `lygh`=1');
	}

	//作废或删除时
	protected function flowzuofeibill($sm)
	{
		//删除出库详情的
		m('goodss')->delete("`mid`='$this->id'");
	}
	
	
	//子表数据替换处理
	protected function flowsubdata($rows, $lx=0){
		foreach($rows as $k=>$rs){
			$one = $this->goodsobj->getone($rs['aid']);
			if($one){
				$name 	= $one['name'];
				if(!isempt($one['xinghao']))$name.='('.$one['xinghao'].')';
				if($lx==1){
					$rows[$k]['aid'] 	= $name;
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
		$rs['state'] = $this->goodsobj->crkstate($rs['state'],0);
		
		//读取物品
		if($lx==2){
			$rs['wupinlist'] = $this->goodsobj->getgoodninfo($rs['id'], 1);
		}
		
		if($lx==1){
			$url  = $this->getxiangurl('goodly', $rs['custid'], 'auto');
			$rs['custname'] = '<a href="'.$url.'"><u>'.$rs['custname'].'</u></a>';
		}
		return $rs;
	}
}