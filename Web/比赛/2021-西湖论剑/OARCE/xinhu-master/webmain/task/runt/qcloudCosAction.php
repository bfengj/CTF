<?php
/**
*	文件同步上传到腾讯云存储
*	
*/
class qcloudCosClassAction extends runtAction
{

	/**
	*	发送上传文件
	*	php task.php qcloudCos,run -fileid=1
	*	http://你地址/task.php?m=qcloudCos|runt&a=run&fileid=文件id
	*/
	public function runAction()
	{
		$fileid = (int)$this->getparams('fileid','0'); //文件ID
		if($fileid<=0)return 'error fileid';
		$frs 	= m('file')->getone($fileid);
		if(!$frs)return 'filers not found';
		
		$filepath 	= $frs['filepath'];
		if(substr($filepath, 0, 4)=='http')return 'filepath is httppath';
		
		if(substr($filepath,-6)=='uptemp'){
			$aupath = ROOT_PATH.'/'.$filepath;
			$nfilepath  = str_replace('.uptemp','.'.$frs['fileext'].'', $filepath);
			$content	= file_get_contents($aupath);
			$this->rock->createtxt($nfilepath, base64_decode($content));
			unlink($aupath);
			$filepath 	= $nfilepath;
		}
		
		$msg 	= $this->sendpath($filepath, $frs, 'filepathout');
		if($msg)return $msg;
		
		$thumbpath	= $frs['thumbpath'];
		if(!isempt($thumbpath)){
			$msg 	= $this->sendpath($thumbpath, $frs, 'thumbplat');
			if($msg)return $msg;
		}
		return 'success';
	}
	
	private function sendpath($filepath, $frs, $fields)
	{
		$path 		= ROOT_PATH.'/'.$filepath;
		if(!file_exists($path))return 'filepath['.$fields.'] not exists';
		$res = c('qcloudCos')->upload($path,'', $filepath);
		if($res['code']==0){
			$data = $res['data'];
			$bo = m('file')->update("`$fields`='".$res['url']."'", $frs['id']);
			if($bo)@unlink($path);//删除文件
			if(PHP_SAPI != 'cli')print_r($res);
		}else{
			return $res['code'].'.'.$res['message'];
		}
	}
	
	/**
	*	下载文件，预览用到
	*	php task.php qcloudCos,down -fileid=1
	*/
	public function downAction()
	{
		$fileid = (int)$this->getparams('fileid','0'); //文件ID
		if($fileid<=0)return 'error fileid';
		
		$fobj 	= m('file');
		$frs 	= $fobj->getone($fileid);
		if(!$frs)return 'filers not found';

		$filepathout = $frs['filepathout'];
		if(isempt($filepathout))return 'filepathout is empty';
		//$filepathout = str_replace('//');
		
		$filepath	 = $frs['filepath'];
		$fileext	 = $frs['fileext'];
		$dstPath	 = ROOT_PATH.'/'.$filepath;
		if(file_exists($dstPath)){
			return ''.$dstPath.' exists';
		}
		$filepath 	 = ''.UPDIR.'/logs/costmp/'.date('YmdHis').'a'.$fileid.'.'.$fileext.'';//用临时文件
		$dstPath	 = ROOT_PATH.'/'.$filepath;
		$this->rock->createdir($filepath);
		$fsarr 		 = explode('myqcloud.com', $filepathout);
		$srcPath 	 = substr($fsarr[1],1);
		$res 		 = c('qcloudCos')->download($srcPath, $dstPath);
		if($res['code']==0 && file_exists($dstPath)){
			if(!c('upfile')->issavefile($fileext)){
				$filebase64	= base64_encode(file_get_contents($dstPath));
				$filepath 	= str_replace('.'.$fileext.'','.uptemp', $filepath);
				$bo 		= $this->rock->createtxt($filepath, $filebase64);
				@unlink($dstPath);
			}
			$fobj->update("`filepath`='$filepath'", $fileid);
		}else{
			$msg = ''.$frs['filename'].',无法下载('.$res['code'].')：'.$res['message'].'';
			m('log')->addlogs('腾讯云存储下载',$msg,2);
		}
		
		return $res['code'].'.'.$res['message'].'@'.$filepath.'@'.$srcPath;
	}
}