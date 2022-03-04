<?php
class flow_officibClassModel extends flowModel
{
	public $xiangbordercolor = 'red';//默认边框颜色
	
	public function initModel()
	{
		
	}

	protected function flowdatalog($arr)
	{
		$arr['title'] = $this->moders['name'];

		return $arr;
	}
	
	public function flowxiangfields(&$fields)
	{
		$fields['base_name'] 	= '登记人';
		$fields['base_deptname'] = '登记人部门';
		return $fields;
	}
	
	public function flowsearchfields()
	{
		$arr[] = array('name'=>'登记人...','fields'=>'uid');
		return $arr;
	}
	
	//录入页面标题
	public function inputtitle()
	{
		return '收文登记';
	}
	
}