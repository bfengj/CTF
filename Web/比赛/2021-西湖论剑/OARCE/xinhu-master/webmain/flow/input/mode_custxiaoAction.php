<?php
/**
*	此文件是流程模块【custxiao.销售】对应控制器接口文件。
*/ 
class mode_custxiaoClassAction extends inputAction{
	
	protected function savebefore($table, $arr, $id, $addbo){
		$data = $this->getsubtabledata(0);
		if(count($data)==0)return '至少要有一行记录';
		$this->sssaid = '0';
		foreach($data as $k=>$rs){
			$this->sssaid.=','.$rs['aid'].'';
			if(isset($rs['aid']))foreach($data as $k1=>$rs1){
				if($k!=$k1){
					if($rs['aid']==$rs1['aid'])
						return '行'.($k1+1).'的物品已在行'.($k+1).'上填写，不要重复填写';
				}
			}
		}
		
		//判断合同上金额是不是和销售金额一致
		$custractid = (int)$arr['custractid'];
		$money 		= floatval($arr['money']);
		if($custractid>0){
			$rars =  m('custract')->getone($custractid);
			if($rars){
				if(floatval($rars['money']) != $money)return '销售金额'.$money.'跟关联合同金额'.$rars['money'].'不一致';
				if($rars['custid']!=$arr['custid'])return '所选客户跟关联合同上的客户不一致应该选['.$rars['custname'].']';
			}else{
				$custractid = '0';
			}
		}
		
		$rows['type'] = '2';//一定要是2，不能去掉
		$rows['custractid'] = $custractid;
		return array(
			'rows'=>$rows
		);
	}
	
		
	protected function saveafter($table, $arr, $id, $addbo){
		$custractid = $arr['custractid'];
		if($custractid>0)m('custract')->update('`xiaoid`='.$id.'', $custractid);//更新客户对应销售id
	}
	
	//读取物品
	public function getgoodsdata()
	{
		return m('goods')->getgoodsdata(1);
	}
	
	//读取我的客户
	public function getmycust()
	{
		$rows = m('crm')->getmycust($this->adminid, $this->rock->arrvalue($this->rs, 'custid'));
		return $rows;
	}
	
	//读取我的合同
	public function getmycustract()
	{
		$arr[] = array('value'=>'0','name'=>'不关联');
		
		//读取我合同
		$custractid = 0;
		$mid  = (int)$this->get('mid','0');
		if($mid>0){
			$custractid = (int)$this->flow->getmou('custractid', $mid);
		}
		
		$where 	= '`uid`='.$this->adminid.' and `status`=1 and `type`=0 and (`xiaoid`=0 or `id`='.$custractid.')';
		$where .= " and `enddt`>='{$this->date}'";
		$rows 	= m('custract')->getrows($where, 'id,custid,custname,`num`','`id` desc');

		foreach($rows as $k=>$rs){
			$arr[] = array(
				'value' => $rs['id'],
				'name' 	=> ''.$rs['num'].'.'.$rs['custname'].''
			);
		}
		return $arr;
	}
	
	public function ractchangeAjax()
	{
		$htid 	= (int)$this->get('ractid');
		$cars 	= m('custract')->getone($htid, 'id,custid,custname,money,type,num,signdt');
		$this->returnjson($cars);
	}
}	
			