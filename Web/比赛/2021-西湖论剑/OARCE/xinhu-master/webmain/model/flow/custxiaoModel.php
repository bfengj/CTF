<?php
class flow_custxiaoClassModel extends flowModel
{
	public $minwidth	= 600;//子表最小宽
	
	public function initModel()
	{
		$this->goodsobj 	= m('goods');
		$this->custractobj 	= m('custract');
		$this->crmobj		= m('crm');
	}
	
	public function flowxiangfields(&$fields)
	{
		$fields['base_name'] 	= '销售人';
		$fields['base_deptname'] = '销售人部门';
		$fields['base_sericnum'] = '销售单号';
		return $fields;
	}
	
	public function flowsearchfields()
	{
		$arr[] = array('name'=>'销售人...','fields'=>'uid');
		return $arr;
	}
	
	protected function flowsubmit($na, $sm)
	{
		$num = $this->sericnum;
		$this->update(array('num'=>$num),$this->id);
		m('custfina')->update("`htnum`='$num'", "`htid`='-".$this->id."'");
	}
	
	//子表数据替换处理
	protected function flowsubdata($rows, $lx=0){
		$db = m('goods');
		foreach($rows as $k=>$rs){
			$one = $db->getone($rs['aid']);
			if($one){
				$name = $one['name'];
				if(!isempt($one['xinghao']))$name.='('.$one['xinghao'].')';
				if($lx==1)$rows[$k]['aid'] = $name; //1展示时
				$rows[$k]['temp_aid'] = $name;
			}
		}
		return $rows;
	}
	
	//作废或删除时
	protected function flowzuofeibill($sm)
	{
		$this->custractobj->update('`xiaoid`=0', "`xiaoid`='".$this->id."'");//合同取消关联销售单
		
		$this->update('`custractid`=0', $this->id);//取消关联合同
		
		//删除关联收付款单
		$this->deletebilljoin('custfina',"`htid`='-".$this->id."'", $sm);
		
		//删除出库详情的
		m('goodss')->delete("`mid`='$this->id'");
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
		
		$custractid = (int)$rs['custractid'];
		
		//读取收款状态
		$dsmoney		  = '';
		if($custractid>0){
			
			//从合同那读取
			$htrs 	= $this->custractobj->getone('`id`='.$custractid.' and `status`<>5');
			if($htrs){
				$custractid = $htrs['num'];//读取合同编号
				if($lx==1)$custractid = '<a href="'.$this->getxiangurl('custract',$rs['custractid'],'auto').'">'.$custractid.'</a>';
				
				$nrss	 			= $this->crmobj->ractmoney($htrs);
				$ispay 				= $nrss[0];
				$moneys 			= $nrss[1];
				
				if($ispay==1){
					$dsmoney		= '<font color=green>已全部收款</font>';
				}else{
					$dsmoney		= '待收<font color=#ff6600>'.$moneys.'</font>';
				}
				
			}else{
				$custractid = 0;//不存在
				$this->update('`custractid`='.$custractid.'', $rs['id']);
			}
		}
		
		
		if($custractid===0){
			$custractid = '<font color=#aaaaaa>无关联</a>';
			$dsmoney	= $this->crmobj->xiaozhuantai($rs);
		}
		$rs['custractid'] = $custractid;
		$rs['shoukuzt']   = $dsmoney;
		
		
		return $rs;
	}
}