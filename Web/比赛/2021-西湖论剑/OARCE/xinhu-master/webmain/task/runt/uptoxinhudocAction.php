<?php
/**
*	文件同步上传到文件平台
*	php task.php uptoxinhudoc,run -fileid=1
*/
class uptoxinhudocClassAction extends runtAction
{

	/**
	*	发送上传文件
	*/
	public function runAction()
	{
		$fileid = (int)$this->getparams('fileid','0'); //文件ID
		if($fileid<=0)return 'error fileid';
		$frs 	= m('file')->getone($fileid);
		if(!$frs)return 'filers not found';
		
		$filepath 	= $frs['filepath'];
		if(substr($filepath, 0, 4)=='http')return 'filepath is httppath';
		
		$msg 	= $this->sendpath($filepath, $frs);
		if($msg)return $msg;

		return 'success';
	}
	
	private function sendpath($filepath, $frs)
	{
		$path 		= ROOT_PATH.'/'.$filepath;
		if(!file_exists($path))return 'filepath not exists';
		$lx		= 'file';
		if(getconfig('xinhudoc_diskops'))$lx='path';
		$url 	= $this->upfileurl($lx);
		
		//文件远程上传的
		if($lx=='file'){
			$data   = base64_encode(file_get_contents($path));
			$params = array(
				'filename' 	=> $frs['filename'],
				'optname' 	=> $frs['optname'],
				'adddt' 	=> $frs['adddt'],
				'fileext' 	=> $frs['fileext'],
				'filesize' 	=> $frs['filesize'],
				'filepath' 	=> $frs['filepath'],
				'table'		=> ''.DB_BASE.'.file',
				'mid'		=> $frs['id'],
				'optid'		=> $frs['optid'],
			);
			$canstr  = $this->jm->base64encode(json_encode($params));
			$url	.= '&sendtype=file&paramsstr='.$canstr.'';
		
			$result	 = c('curl')->postcurl($url, $data);
			
			$msg	 = $this->chuliresult($result, $frs, 1);
			if(!$msg){
				$this->sendother($frs['thumbpath'], 'thumb', $frs,2);
				$this->sendother($frs['pdfpath'], 'pdf', $frs,3);
			}
			return $msg;
		}
		
		//同服务器用复制的
		$result	= c('curl')->postcurl($url, array(
			'rootpath'  => ROOT_PATH,
			'filepath' 	=> $frs['filepath'],
			'fileext' 	=> $frs['fileext'],
			'filename' 	=> $frs['filename'],
			'filesize' 	=> $frs['filesize'],
			'pdfpath' 		=> $frs['pdfpath'],
			'thumbpath' 	=> $frs['thumbpath'],
			'optname' 		=> $frs['optname'],
			'adddt' 		=> $frs['adddt'],
			'table'			=> ''.DB_BASE.'.file',
			'mid'			=> $frs['id'],
			'optid'			=> $frs['optid'],
		));
		
		return $this->chuliresult($result, $frs, 0);
	}
	
	private function sendother($filepath, $type, $frs, $lx)
	{
		if(isempt($filepath))return;
		$path 	= ROOT_PATH.'/'.$filepath;
		if(!file_exists($path))return;
		$url 	= $this->upfileurl('file');
		$data   = base64_encode(file_get_contents($path));
		$params = array(
			'filepath' 	=> $filepath,
			'table'		=> ''.DB_BASE.'.file',
			'mid'		=> $frs['id'],
		);
		
		$canstr  = $this->jm->base64encode(json_encode($params));
		$url	.= '&sendtype='.$type.'&paramsstr='.$canstr.'';
		$result  = c('curl')->postcurl($url, $data);
		return $this->chuliresult($result, $frs, $lx);
	}
	
	private function chuliresult($result, $frs, $lx)
	{
		$barr = c('xinhudoc')->returnresult($result);
		if($barr['success']){
			$data = $barr['data'];
			$uarr = array();
			if($lx==0 || $lx==1){
				$filenum = $data['filenum'];
				$uarr['filenum'] = $filenum;
			}
			
			//缩略图必须保存对应路径
			if($lx==0 || $lx==2){
				$thumbpath = arrvalue($data, 'thumbpath');
				if(!isempt($thumbpath)){
					$uarr['thumbplat'] = '{PLATURL}'.$thumbpath;
				}
			}
			
			//自动删除文件
			if(getconfig('autoup_localdbool')){
				if($lx==0 || $lx==1)$this->delfilelocal($frs['filepath']);
				if($lx==0 || $lx==2)$this->delfilelocal($frs['thumbpath']);
				if($lx==0 || $lx==3)$this->delfilelocal($frs['pdfpath']);
			}
			
			if($uarr)m('file')->update($uarr, $frs['id']); //更新
		}else{
			return $barr['msg'];
		}
		return '';
	}
	
	private function delfilelocal($path)
	{
		if(isempt($path))return;
		$path = ROOT_PATH.'/'.$path;
		if(file_exists($path))@unlink($path);
	}
	
	private function upfileurl($lx)
	{
		$url = c('xinhudoc')->geturlstr('upfile', $lx, array(
			'updir' => getconfig('xinhudoc_upmkdir')
		));
		return $url;
	}
	
	/**
	*	从管理平台上删除文件
	*/
	public function delAction()
	{
		$filenum = $this->getparams('filenum'); //文件ID
		if(isempt($filenum))return 'filenum is empty';
	
		$doc = c('xinhudoc');
		$url = $doc->geturlstr('upfile', 'del', array(
			'filenum' => $filenum
		));
		$result	= c('curl')->getcurl($url);
		$barr 	= $doc->returnresult($result);
		
		if($barr['success']){
			return $barr['data'];
		}else{
			return $barr['msg'];
		}
	}
}