<?php 
/**
*	【文档】应用的接口
*	createname：雨中磐石
*	homeurl：http://www.rockoa.com/
*	Copyright (c) 2016 rainrock (www.rockoa.com)
*	Date:2017-08-08
*/
class worcClassAction extends apiAction
{
	
	public function getdataAction()
	{
		$barr = m('word')->getdata();
		
		$this->showreturn($barr);
	}
}