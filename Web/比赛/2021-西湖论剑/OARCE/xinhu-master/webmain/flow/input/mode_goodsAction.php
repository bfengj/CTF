<?php

class mode_goodsClassAction extends inputAction{
	
	public function getgoodstype()
	{
		$rows = m('goods')->getgoodstype();
		return $rows;
	}
	
	protected function savebefore($table, $arr, $id, $addbo){
		$bo = m('goods')->existsgoods($arr, $id);
		if($bo)return '改物品已存在了';
	}
	
	
}	
			