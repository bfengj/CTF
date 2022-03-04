<?php
/**
*	此文件是流程模块【carmby.车辆保养】对应接口文件。
*/ 
class mode_carmbyClassAction extends inputAction{
	
	
	public function getcardata()
	{
		$where= m('admin')->getcompanywhere(1);
		$rows = m('carm')->getall("1=1 ".$where."",'carnum as name,id as value');
		return $rows;
	}
	
	protected function savebefore($table, $arr, $id, $addbo){
		$carid 	= $arr['carid'];
		$to 	= m($table)->rows('id<>'.$id.' and `carid`='.$carid.' and `type`=1 and `status`=0');
		if($to>0)return '当前车辆已申请了保养在处理中了';
	}
}	
			