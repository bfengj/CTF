<?php
class mode_projectClassAction extends inputAction{
	
	public function progressdata()
	{
		$arr = array();
		for($i=0;$i<=100;$i++)$arr[]=array('value'=>$i,'name'=>$i.'%');
		return $arr;
	}
}	
			