<?php
/**
	下载文件类插件
*/

class downChajian extends Chajian{
	
	private $upobj;
	private $messign;
	
	protected function initChajian()
	{
		$this->messign = '';
		$this->upobj = c('upfile');
	}
	
	/**
	*	获取随机文件名
	*/
	public function getallfilename($ext)
	{
		$mkdir 	= ''.UPDIR.'/'.date('Y-m').'';
		if(!is_dir($mkdir))mkdir($mkdir);
		$allfilename			= ''.$mkdir.'/'.date('d_His').''.rand(10,99).'.'.$ext.'';
		return $allfilename;
	}
	
	/**
	*	根据扩展名保存文件(一般邮件附件下载)
	*/
	public function savefilecont($ext, $cont)
	{
		$bo  = $this->upobj->issavefile($ext);
		if(isempt($cont))return;
		$file= '';
		if(!$bo){
			$file	= $this->getallfilename('uptemp');
			$bo 	= @file_put_contents($file, base64_encode($cont));
		}else{
			$file 	= $this->getallfilename($ext);
			$bo 	= @file_put_contents($file, $cont);
		}
		if(!$bo){
			$file = '';
		}else{
			if($this->upobj->isimg($ext)){
				$bo = $this->upobj->isimgsave($ext, $file);
				if(!$bo)$file = '';
			}
		}
		return $file;
	}
	
	private function reutnmsg($msg)
	{
		$this->messign = $msg;
		return false;
	}
	
	//获取提示内容
	public function gettishi($msg1='')
	{
		$msg = $this->messign;
		if(isempt($msg))$msg = $msg1;
		return $msg;
	}
	
	/**
	*	根据内容创建文件
	*/
	public function createimage($cont, $ext, $filename, $thumbnail='')
	{
		if(isempt($cont))return $this->reutnmsg('创建内容为空');
		$allfilename			= $this->getallfilename($ext);
		$upses['oldfilename'] 	= $filename.'.'.$ext;
		$upses['fileext'] 	  	= $ext;
		@file_put_contents($allfilename, $cont);
		if(!file_exists($allfilename))return $this->reutnmsg('无法写入:'.$allfilename.'');
		
		$fileobj				= getimagesize($allfilename);
		$mime					= strtolower($fileobj['mime']);
		$next 					= 'jpg';
		if(contain($mime,'bmp'))$next = 'bmp';
		if($mime=='image/gif')$next = 'gif';
		if($mime=='image/png')$next = 'png';
		if($ext != $next){
			@unlink($allfilename);
			$ext = $next;
			$allfilename			= $this->getallfilename($ext);
			$upses['oldfilename'] 	= $filename.'.'.$ext;
			$upses['fileext'] 	  	= $ext;
			@file_put_contents($allfilename, $cont);
			if(!file_exists($allfilename))return $this->reutnmsg('无法写入:'.$allfilename.'');
		}
		
		$filesize 			  	= filesize($allfilename);
		$filesizecn 		  	= $this->upobj->formatsize($filesize);
		$picw					= $fileobj[0];				
		$pich					= $fileobj[1];
		if($picw==0||$pich==0){
			@unlink($allfilename);
			return $this->reutnmsg('无效的图片');;
		}
		$upses['filesize']	 	= $filesize;
		$upses['filesizecn']	= $filesizecn;
		$upses['allfilename']	= $allfilename;
		$upses['picw']	 		= $picw;
		$upses['pich']	 		= $pich;
		$arr 					= $this->uploadback($upses, $thumbnail);
		return $arr;
	}
	
	public function uploadback($upses, $thumbnail='', $subo=true)
	{
		if($thumbnail=='')$thumbnail='150x150';
		$msg 		= '';
		$data 		= array();
		if(is_array($upses)){
			$noasyn = $this->rock->get('noasyn'); //=yes就不同步到文件平台
			$arrs	= array(
				'adddt'	=> $this->rock->now,
				'valid'	=> 1,
				'filename'	=> $this->replacefile($upses['oldfilename']),
				'web'		=> $this->rock->web,
				'ip'		=> $this->rock->ip,
				'mknum'		=> $this->rock->get('sysmodenum'),
				//'mid'		=> $this->rock->get('sysmid','0'),
				'fileext'	=> substr($upses['fileext'],0,10),
				'filesize'	=> (int)$this->rock->get('filesize', $upses['filesize']),
				'filesizecn'=> $upses['filesizecn'],
				'filepath'	=> str_replace('../','',$upses['allfilename']),
				'optid'		=> $this->adminid,
				'optname'	=> $this->adminname,
				'comid'		=> m('admin')->getcompanyid(),
			);
			$arrs['filetype'] = m('file')->getmime($arrs['fileext']);
			$thumbpath	= $arrs['filepath'];
			$sttua		= explode('x', $thumbnail);
			$lw 		= (int)$sttua[0];
			$lh 		= (int)$sttua[1];
			if($upses['picw']>$lw || $upses['pich']>$lh){
				$imgaa	= c('image', true);
				$imgaa->createimg($thumbpath);
				$thumbpath 	= $imgaa->thumbnail($lw, $lh, 1);
			}
			if($upses['picw'] == 0 && $upses['pich']==0)$thumbpath = '';
			$arrs['thumbpath'] = $thumbpath;
			
			
			$bo = $this->db->record('[Q]file',$arrs);
			if(!$bo)$this->reutnmsg($this->db->error());
			
			$id	= $this->db->insert_id();
			$arrs['id']   = $id;
			$arrs['picw'] = $upses['picw'];
			$arrs['pich'] = $upses['pich'];
			$data= $arrs;
			
			//上传到上传的文件管理2021-08-09
			if(getconfig('rockfile_autoup') && $noasyn != 'yes'){
				$stime = time()+rand(3,6);
				if($subo)$stime=0;
				c('rockqueue')->push('flow,uptofile', array('fileid'=>$id), $stime);
			}
			
			//自动上传到腾讯云存储
			if(getconfig('qcloudCos_autoup') && $noasyn != 'yes'){
				$stime = time()+rand(3,6);
				if($subo)$stime=0;
				c('rockqueue')->sendfile($id, $stime);
			}
			
		}else{
			$data['msg'] = $upses;
		}
		return $data;
	}
	
	//过滤特殊文件名
	private function replacefile($str)
	{
		$s 			= strtolower($str);
		$s2			= $s.'';
		$lvlaraa  	= explode(',','user(),found_rows,(),select*from,select*,%20');
		$lvlarab	= array();
		foreach($lvlaraa as $_i)$lvlarab[]='';
		$s = str_replace($lvlaraa, $lvlarab, $s);
		if($s!=$s2)$str = $s;
		return $str;
	}
	
	//获取扩展名
	public function getext($file)
	{
		return strtolower(substr($file,strrpos($file,'.')+1));
	}
}
