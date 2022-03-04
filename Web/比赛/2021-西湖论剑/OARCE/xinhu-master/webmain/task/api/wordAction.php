<?php 
/**
*	【文档】应用的接口
*	createname：雨中磐石
*	homeurl：http://www.rockoa.com/
*	Copyright (c) 2016 rainrock (www.rockoa.com)
*	Date:2016-08-08
*/
class wordClassAction extends apiAction
{
	
	
	
	//获取数据
	public function getdataAction()
	{
		$barr = m('word')->getdata();
		$this->showreturn($barr);
	}
	
	
	//保存文件
	public function savefileAction()
	{
		m('word')->savefile();
		$frs = m('file')->getone($this->post('sid'));
		$this->showreturn($frs);
	}
	
	//创建文件夹
	public function createfolderAction()
	{
		
		$cqid 	= $this->post('cqid');
		$typeid = (int)$this->post('typeid','0');
		$name 	= $this->post('name');
		
		$arr 	= m('word')->createfolder($name, $cqid, $typeid);
		$this->showreturn($arr);
	}
	
	//从命名
	public function renameAction()
	{
		$id 	= (int)$this->post('id');
		$name 	= $this->getvals('name');
		$type 	= $this->post('type');
		m('word')->update("`name`='$name'", $id);
		$this->showreturn('');
	}
	
	//删除
	public function delfileAction()
	{
		$id 	= (int)$this->post('id');
		$barr 	= m('word')->delword($id);
		if(!$barr['success']){
			$this->showreturn('',$barr['msg'],201);
		}else{
			$this->showreturn('');
		}
	}
	
	//共享
	public function shatefileAction()
	{
		m('word')->sharefile();
		$this->showreturn('');
	}
	
	public function movegetAction()
	{
		return m('word')->getworcfolder();
	}
	
	public function movefileAction()
	{
		$barr = m('word')->movefile();
		if(!$barr['success']){
			$this->showreturn('',$barr['msg'],201);
		}else{
			$this->showreturn('');
		}
	}

	
}