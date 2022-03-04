<?php
class mode_caigouClassAction extends inputAction{
	

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
		
		$rows['type'] = '1';//一定要是1，不能去掉
		return array(
			'rows'=>$rows
		);
	}
	
		
	protected function saveafter($table, $arr, $id, $addbo){
		$uarr['status'] = 0;
		$uarr['type'] 	= 0;
		m('goodss')->update($uarr,"`mid`='$id'");
		m('goodss')->update("`count`=abs(`count`)","`mid`='$id'");
		m('goods')->setstock($this->sssaid);
	}
	
	public function getgoodsdata()
	{
		return m('goods')->getgoodsdata(2);
	}
	
	public function getcustgong()
	{
		$arows 	= m('customer')->getall('`status`=1 and `isgys`=1','id,name');
		$rows	= array();
		foreach($arows as $k=>$rs)$rows[]=array(
			'name' 	=> $rs['name'],
			'value' => $rs['id']
		);
		return $rows;
	}
	
	public function getgoodsAjax()
	{
		$aid = (int)$this->post('aid');
		$rs  = m('goods')->getone($aid,'unit,price');
		$this->returnjson($rs);
	}
}	
			