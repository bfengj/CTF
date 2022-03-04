<?php 
/**
	*****************************************************************
	* 联系QQ： 290802026/1073744729									*
	* 版  本： V2.0													*
	* 开发者：雨中磐石工作室										*
	* 邮  箱： qqqq2900@126.com										*
	* 网  址： http://www.rockoa.com/								*
	* 说  明: 插件主类												*
	* 备  注: 未经允许不得商业出售，代码欢迎参考纠正			*
	* 创建时间: 2014-08-30											*
	*****************************************************************
*/
abstract class Chajian{
	
	public	$rock;
	public 	$db;
	public 	$adminname;
	public 	$adminid;
	
	public function __construct()
	{
		$this->rock			= $GLOBALS['rock'];
		$this->db			= $GLOBALS['db'];
		$this->adminid		= $this->rock->adminid;
		$this->adminname	= $this->rock->adminname;
		$this->initChajian();
	}
	
	public function __destruct()
	{
		$this->destChajian();
	}
	
	public function isempt($str)
	{
		return $this->rock->isempt($str);
	}
	
	public function contain($str, $s1)
	{
		return $this->rock->contain($str, $s1);
	}
		
	protected function initChajian(){}
	protected function destChajian(){}
}