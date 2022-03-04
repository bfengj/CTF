<?php
/**
*	此文件是流程模块【worc.文档分区】对应控制器接口文件。
*/ 
class mode_worcClassAction extends inputAction{
	
	//获取分区列表
	public function getworcAjax()
	{
		return m('worc')->getmywroc();
	}
	
	/**
	*	获取文件
	*/
	public function getfiledataAjax()
	{
		return m('word')->getdata();
	}
	
	
	/**
	*	保存上传的文件
	*/
	public function savefileAjax()
	{
		m('word')->savefile();
		echo 'ok';
	}
	
	/**
	*	创建文件夹
	*/
	public function createfolderAjax()
	{
		$cqid 	= (int)$this->post('cqid');
		$typeid = (int)$this->post('typeid','0');
		$name 	= $this->post('name');
		
		m('word')->createfolder($name, $cqid, $typeid);
	}
	
	/**
	*	删除
	*/
	public function delfileAjax()
	{
		$id = (int)$this->post('id','0');
		return m('word')->delword($id);
	}
	
	/**
	*	共享
	*/
	public function sharefileAjax()
	{
		m('word')->sharefile();
	}
	
	public function movegetAjax()
	{
		return m('word')->getworcfolder();
	}
	
	public function movefileAjax()
	{
		return m('word')->movefile();
	}
	
	
	public function getmyinfoAjax()
	{
		return array(
			'uid' => 'u'.$this->adminid.'',
			'uname' => $this->adminname,
		);
	}
}	
			