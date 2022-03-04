<?php 
/**
*	文件上传管理中心相关接口
*/

class rockfileChajian extends Chajian{
	

	protected function initChajian()
	{
		$url  = getconfig('rockfile_url');
		$urlb = getconfig('rockfile_localurl');
		if($urlb)$url = $urlb;
		$this->agentkey = getconfig('rockfile_key');
		if(substr($url,-1)!='/')$url.='/';
		$this->updatekel = $url;
		$this->updatekey = $url.'api.php';
	}
	
	
	public function geturlstr($mod, $act, $can=array())
	{
		$url 	= $this->updatekey;
		$url.= '?m='.$mod.'&a='.$act.'';
		$url.= '&agentkey='.$this->agentkey.'';
		$url.= '&websign='.md5($this->rock->HTTPweb).'';
		foreach($can as $k=>$v)$url.='&'.$k.'='.$v.'';
		return $url;
	}

	
	/**
	*	get获取数据
	*/
	public function getdata($mod, $act, $can=array())
	{
		$url 	= $this->geturlstr($mod, $act, $can);
		$cont 	= c('curl')->getcurl($url);
		if(!isempt($cont) && contain($cont, 'success')){
			$data  	= json_decode($cont, true);
		}else{
			$data 	= returnerror('无法访问,'.$cont.'');
		}
		return $data;
	}
	
	/**
	*	post发送数据
	*/
	public function postdata($mod, $act, $can=array(), $cans=array())
	{
		$url 	= $this->geturlstr($mod, $act, $cans);
		$cont 	= c('curl')->postcurl($url, $can);
		if(!isempt($cont) && contain($cont, 'success')){
			$data  	= json_decode($cont, true);
		}else{
			$data 	= returnerror('无法访问,'.$cont.'');
		}
		return $data;
	}
	
	/**
	*	同步保存到自己的库
	*/
	public function getsave($fnum)
	{
		if(!$fnum)return $fnum;
		$nums = '';
		$fanu = explode(',', $fnum);
		$ids  = '';
		foreach($fanu as $st1){
			if(is_numeric($st1)){
				$ids.=','.$st1.'';
			}else{
				$nums.=','.$st1.'';
			}
		}
		if($nums){
			$nums = substr($nums, 1);
			$barr = $this->getdata('upload','filesaveinfo',array('nums'=>$nums));
			return $barr;
		}else{
			return returnerror('error');
		}
	}
	
	/**
	*	删除文件
	*/
	public function filedel($nums)
	{
		$barr = $this->getdata('upload','filedel',array('nums'=>$nums));
		return $barr;
	}
	
	/**
	*	上传
	*/
	public function uploadfile($fileid)
	{
		$frs  = m('file')->getone($fileid);
		if(!$frs)return returnerror('1');
		$path = ROOT_PATH.'/'.$frs['filepath'];
		if(!file_exists($path))return returnerror('404');
		$barr = $this->upload($path, array(
			'optid' 	=> $frs['optid'],
			'noasyn' 	=> 'no', //no和yes
			'fileexs' 	=> $frs['fileext'],
			'optname' 	=> $this->rock->jm->base64encode($frs['optname']),
			'filename' 	=> $this->rock->jm->base64encode($frs['filename']),
		));
		if(!$barr['success'])return $barr;
		$data 	 = $barr['data'];
		$filenum 	= arrvalue($data, 'filenum');
		$thumbpath 	= arrvalue($data, 'thumbpath');
		if($filenum){
			$guar['filenum'] = $filenum;
			if($thumbpath)$guar['thumbplat'] = $thumbpath;
			m('file')->update($guar,$fileid);
			unlink($path);
			$path = ROOT_PATH.'/'.$frs['thumbpath'];
			if($path && file_exists($path))unlink($path);
		}
		return $barr;
	}
	
	/**
	*	上传文件(分割发送)
	*/
	public function upload($path,$upcs=array(), $fcs=0.5)
	{
		if(!file_exists($path))return returnerror('404');
		$oi 	 = 0;
		$fp 	 = fopen($path,'rb');
		$filesize= filesize($path);
		$fileext = c('upfile')->getext($path);
		$size 	 = $fcs*1024*1024;
		$zong	 = ceil($filesize/$size);
		if($zong<=0)$zong = 1;
		$barr 	 = false;
		$biaoshi = rand(100000,999999);
		while(!feof($fp)){
			$cont = fread($fp, $size);
			$conts= base64_encode($cont);
			$upcan= array('ci'=>$oi,'biaoshi'=>$biaoshi,'zong'=>$zong,'filesize' => $filesize,'fileext'=>$fileext);
			foreach($upcs as $k=>$v)$upcan[$k] = $v;
			$barr = $this->postdata('upfile','index', $conts, $upcan);
			if(!$barr['success'])break;
			$oi++;
		}
		fclose ($fp);
		if($barr)return $barr;
		return returnerror('无效文件');
	}
}