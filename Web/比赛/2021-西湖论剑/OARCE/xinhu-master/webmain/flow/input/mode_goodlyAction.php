<?php

class mode_goodlyClassAction extends inputAction{
	
	
	protected function savebefore($table, $arr, $id, $addbo){
		$data = $this->getsubtabledata(0);
		if(count($data)==0)return '至少要有一行记录';
		$cbarr= array();
		$this->sssaid = '0';
		foreach($data as $k=>$rs){
			if($rs['count']==0)return '行['.($k+1).']的数量不能为0';
			if(!isset($cbarr[$rs['aid']])){
				$cbarr[$rs['aid']] = 1;
			}else{
				return '行['.($k+1).']的物品已申请了';
			}
			$this->sssaid.=','.$rs['aid'].'';
		}
	}
	
	
	protected function saveafter($table, $arr, $id, $addbo){
		
		$uarr['status'] = 0;
		$uarr['type'] 	= 1;//是出库
		m('goodss')->update($uarr,"`mid`='$id'");
		m('goodss')->update("`count`=0-abs(`count`)","`mid`='$id'");
		m('goods')->setstock($this->sssaid);
	}
	
	public function getgoodsdata()
	{
		return m('goods')->getgoodsdata(1);
	}
}	
			