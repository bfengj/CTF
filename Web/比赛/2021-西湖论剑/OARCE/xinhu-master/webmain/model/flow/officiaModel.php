<?php
class flow_officiaClassModel extends flowModel
{
	public $xiangbordercolor = 'red';//默认边框颜色

	protected function flowdatalog($arr)
	{
		$arr['title'] = $this->moders['name'];

		return $arr;
	}
	
	public function flowxiangfields(&$fields)
	{
		$fields['base_name'] 	= '拟办人';
		$fields['base_deptname'] = '拟办人部门';
		return $fields;
	}
	
	public function flowsearchfields()
	{
		$arr[] = array('name'=>'拟办人...','fields'=>'uid');
		return $arr;
	}
	
	public function inputtitle()
	{
		return '拟办发文稿纸';
	}
}