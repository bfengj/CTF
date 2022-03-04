<?php
//演示模块的接口文件
class flow_demoClassModel extends flowModel
{
	protected function flowcoursejudge($num){
		
	}
	
	//默认返回就是前缀
	public function ABCYmd($num)
	{
		return 'QOM-';
		return array(
			'qom' => 'AExeeCC-',
			'wshu' => 5,
			'bom'	=> '-LLL'
		);
	}
}