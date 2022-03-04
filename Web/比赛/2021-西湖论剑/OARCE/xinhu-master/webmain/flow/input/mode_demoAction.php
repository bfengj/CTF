<?php
/**
*	此文件是流程模块【demo.演示测试】对应控制器接口文件。
*/ 
class mode_demoClassAction extends inputAction{
	
	/**
	*	重写函数：保存前处理，主要用于判断是否可以保存
	*	$table String 对应表名
	*	$arr Array 表单参数
	*	$id Int 对应表上记录Id 0添加时，大于0修改时
	*	$addbo Boolean 是否添加时
	*	return array('msg'=>'错误提示内容','rows'=> array()) 可返回空字符串，或者数组 rows 是可同时保存到数据库上数组
	*/
	protected function savebefore($table, $arr, $id, $addbo){
		
	}
	
	/**
	*	重写函数：保存后处理，主要保存其他表数据
	*	$table String 对应表名
	*	$arr Array 表单参数
	*	$id Int 对应表上记录Id
	*	$addbo Boolean 是否添加时
	*/	
	protected function saveafter($table, $arr, $id, $addbo){
		
	}
	
	//读取客户的数据源
	public function getmycust()
	{
		//webmain\model\crmModel.php
		$rows = m('crm')->getmycust($this->adminid); //这个是写的否方法了
		return $rows;//返回，在去试试
	}
	
	public function getcustinfoAjax()
	{
		$custid = (int)$this->get('custid');//客户Id
		$rs  	= m('customer')->getone('`id`='.$custid.'');//读取客户，这个是操作数据库方法，官网有帮助
		return $rs;//返回
	}
	
	
	//弹出下拉选择单选
	public function tanxuan()
	{
		for($i=1;$i<=1520;$i++)
			$rows[] = array(
				'name' => '第'.$i.'个数据',
				'value'=> ''.$i.''
			);
		return $rows;
	}
	
	//弹出下拉选择多选
	public function tanxuancheck()
	{
		$rows = array();
		$tanxuanid = $this->get('tanxuanid'); //根据id过滤数据
		
		//咱们就根据这个id来读取数据源吧
		if($tanxuanid==0){
			$rows[] = array(
				'name' => '数据0'
			);
			$rows[] = array(
				'name' => '数据1'
			);
		}
		
		if($tanxuanid==1){
			$rows[] = array(
				'name' => '选择数据0'
			);
			$rows[] = array(
				'name' => '选择数据1'
			);
		}
		
		//这个数据源只是简单处理，更复杂就需要自己的业务逻辑了，如读取操作数据库等。
		
		
		return $rows;
		$rows[] = array(
			'name' => '数据1:'.$tanxuan.''
		);
		$rows[] = array(
			'name' => '数据2'
		);
		for($i=3;$i<=500;$i++)$rows[] = array(
			'name' => '数据'.$i.''
		);
		return $rows;
	}
	
	//联动获取城市数据数据库表city,根据pid读取
	public function getcityAjax()
	{
		$sheng 	= $this->post('sheng');//省名称
		if(isempt($sheng))return array();//为空
		$dbs 	= m('city');
		//获取省ＩＤ
		$pid 	= $dbs->getmou('id',"`type`=1 and `name`='$sheng'");//type=1
		
		$rows 	 = $dbs->getall("`pid`='$pid'",'name','`sort`'); //查找数据
		
		return $rows;//返回数据
	}
	
	//联动获取城市数据数据库表city,根据pid读取
	public function getxianAjax()
	{
		$city 	= $this->post('city');//省名称
		if(isempt($city))return array();//为空
		$dbs 	= m('city');
		//获取城市ＩＤ
		$pid 	= $dbs->getmou('id',"`type`=2 and `name`='$city'");//type=2
		
		$rows 	 = $dbs->getall("`pid`='$pid'",'name','`sort`'); //查找数据
		
		return $rows;//返回数据
	}
	
	
	//下拉框市的数据源
	public function citydata()
	{
		return $this->getshegnxiandat(arrvalue($this->rs,'sheng'), 1);
	}
	
	//下拉框县的数据源
	public function xiandata()
	{
		return $this->getshegnxiandat(arrvalue($this->rs,'shi'), 2);
	}
	
	//获取下级
	private function getshegnxiandat($name, $type)
	{
		if(isempt($name))return array();
		$dbs 	= m('city');
		
		//获取城市ＩＤ
		$pid 	= $dbs->getmou('id',"`type`='$type' and `name`='$name'");
		
		$rows 	 = $dbs->getall("`pid`='$pid'",'name','`sort`'); //查找数据
		
		return $rows;//返回数据
	}
}	
			