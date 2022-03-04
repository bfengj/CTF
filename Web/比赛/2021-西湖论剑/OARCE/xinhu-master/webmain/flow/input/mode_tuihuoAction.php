<?php
/**
*	此文件是流程模块【tuihuo.退货单】对应控制器接口文件。
*/ 
class mode_tuihuoClassAction extends inputAction{
	
	
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
		
		$rows['type'] = '5';//一定要是5，不能去掉
		return array(
			'rows'=>$rows
		);
	}
	
	protected function saveafter($table, $arr, $id, $addbo){

		m('goods')->setstock($this->sssaid);
	}
	
	public function getgoodsdata()
	{
		return m('goods')->getgoodsdata(2);
	}
	
	//读取我的客户
	public function getmycust()
	{
		$rows = m('crm')->getmycust($this->adminid, $this->rock->arrvalue($this->rs, 'custid'));
		return $rows;
	}
	
	//读取我的销售单
	public function getxiaoshou()
	{
		$arr[] = array('value'=>'0','name'=>'不关联');
		
		//读取我合同
		$custractid = 0;
		$mid  = (int)$this->get('mid','0');
		if($mid>0){
			$custractid = (int)$this->flow->getmou('custractid', $mid);
		}
		//已申请过的
		$where = ' and `id` not in(select `custractid` from `[Q]goodm` where `type`=5 and `status`<>5)';
		if($custractid>0)$where=' and `id`='.$custractid.'';
		
		$rows = m('goodm')->getall('`uid`='.$this->adminid.' and `type`=2 and `status`=1'.$where.'');

		foreach($rows as $k=>$rs){
			$arr[] = array(
				'value' => $rs['id'],
				'name' 	=> ''.$rs['num'].'.'.$rs['custname'].''
			);
		}
		return $arr;
		
	}
	
	public function tuileixing()
	{
		$this->option->addoption('退货类型','tuileixing',62, '普通退货,无理由退货');
		$data = $this->option->getmnum('tuileixing');
		$arr  = array();
		foreach($data as $k=>$rs){
			$arr[] = array(
				'name' => $rs['name'],
				'value' => $rs['name'],
			);
		}
		return $arr;
	}
	
	public function ractchangeAjax()
	{
		$htid 	= (int)$this->get('ractid');
		$cars 	= m('goodm')->getone($htid, 'id,custid,custname,money,discount');
		
		$cars['zbarr'] = $this->db->getall("select a.`aid`,b.name as temp_aid,a.`count`,a.`unit`,a.`price` from `[Q]goodn` a left join `[Q]goods` b on a.`aid`=b.`id` where a.`mid`='$htid'");
		
		$this->returnjson($cars);
	}
}	
			