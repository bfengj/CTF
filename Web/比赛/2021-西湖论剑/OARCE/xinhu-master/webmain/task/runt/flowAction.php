<?php
class flowClassAction extends runtAction
{
	public function pipeiAction()
	{
		m('flow')->repipei();
		return 'success';
	}
	
	//文件同步到上传中心
	public function uptofileAction()
	{
		$fileid = (int)$this->getparams('fileid','0');
		return c('rockfile')->uploadfile($fileid);
	}
	
	//上传中心同步删除
	public function uptodeleteAction()
	{
		$filenum = $this->getparams('filenum');
		return c('rockfile')->filedel($filenum);
	}
}