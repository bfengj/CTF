<?php
class mode_vcardClassAction extends inputAction{
	

	protected function savebefore($table, $arr, $id, $addbo){
		
	}
	

	protected function saveafter($table, $arr, $id, $addbo){
		
	}
	
	public function getgname()
	{
		$uid = $this->adminid;
		$num = 'gerenvcard_'.$uid.'';
		$rows= $this->option->getmnum($num);
		$row = array();
		foreach($rows as $k=>$rs){
			$row[] = array(
				'name' => $rs['name'],
				'value' => $rs['name'],
			);
		}
		return $row;
	}
}	
			