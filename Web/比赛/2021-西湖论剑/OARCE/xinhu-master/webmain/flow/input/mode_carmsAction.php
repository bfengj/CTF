<?php
/**
*	此文件是流程模块【carms.车辆信息登记】对应接口文件。
*	可在页面上创建更多方法如：public funciton testactAjax()，用js.getajaxurl('testact','mode_carms|input','flow')调用到对应方法
*/ 
class mode_carmsClassAction extends inputAction{
	
	
	//可预定的车辆
	public function getcardata()
	{
		$where= m('admin')->getcompanywhere(1);
		$rows = m('carm')->getall("1=1 ".$where."",'carnum as name,id as value');
		return $rows;
	}
}	
			