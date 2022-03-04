<?php
class sysfileClassAction extends Action
{
	public function initAction()
	{
		$this->isdelmu = array(''.UPDIR.'/logs', ''.UPDIR.'/sqllog',''.UPDIR.'/cache', ''.UPDIR.'/data/'.(date('Y')-1).'');
	}
	
	public function getdataAjax()
	{
		$notedit = ',exe,dll,zip,rar,gz,ocx,png,gif,jpg,ico,mp4,wmv,frx,psd,';
		$this->notedit = $notedit;
		$rows = array();
		$path = '';
		$isope= getconfig('sysfileview');
		if($this->adminid!=1 || getconfig('system')=='demo' || !$isope){
			if($this->adminid!=1)$rows[]=array('name'=>'非admin管理员不能查看');
			if(getconfig('system')=='demo')$rows[]=array('name'=>'演示不能查看');
			if(!$isope)$rows[]=array('name'=>'系统配置文件没有打开sysfileview选项，不能查看，可配置加上\'sysfileview\'=>true,即可');
		}else{
			$path = $this->jm->base64decode($this->get('path'));
			$rows = $this->getfilelist($path);
		}
		
		$total = count($rows);
		return array(
			'rows' => $rows,
			'totalCount'=> $total,
			'success' => true,
			'nowpath' => $path
		);
	}
	private function getfilelist($path)
	{
		$chk = c('check');
		$php5= version_compare(PHP_VERSION, '7.0.0','<');
		$dir_arr = @scandir(ROOT_PATH.'/'.$path);
		$darr1=	$rows = array();
		foreach($dir_arr as $key=>$val){
			if($val == '.' || $val == '..'){
			}else{
				if($php5 && $chk->isincn($val))$val = iconv('gb2312','utf-8', $val);
				$mulu = $path.'/'.$val.'';
				if(!$path)$mulu = $val;
				if(is_dir($mulu)){
					$isdel = 0;
					foreach($this->isdelmu as $sdif)if(strpos($mulu, $sdif)===0)$isdel=1;
					$rows[] = array(
						'name' => $val,
						'type'=>'folder-close-alt',
						'lei'=>0,
						'isdel'=>$isdel,
						'path'=> $this->jm->base64encode($mulu)
					);
				}else{
					$suhs = $this->getfilew($val, $mulu,'',0);
					if($suhs)$darr1[] = $suhs;
				}
			}
		}
		foreach($darr1 as $k=>$rs)$rows[] = $rs;
		
		return $rows;
	}
	
	private function getfilew($val,$mulu,$sm='',$isdel=0)
	{
		if(!file_exists($mulu))return false;
		$fileext = strtolower(substr($val,strripos($val,'.')+1));
		$isedit  = 1;
		if(contain($this->notedit,','.$fileext.','))$isedit = 0;
		foreach($this->isdelmu as $sdif)if(strpos($mulu, $sdif)===0)$isdel=1;
		return array(
			'name' => $val,
			'type' => 'file',
			'lei'=>1,
			'filesize' 	=> $this->rock->formatsize(filesize($mulu)),
			'createdt' 	=> date('Y-m-d H:i:s',filectime($mulu)),
			'lastdt' 	=> date('Y-m-d H:i:s',filemtime($mulu)),
			'path'		=> $this->jm->base64encode($mulu),
			'fileext' 	=> $fileext,
			'isedit' 	=> $isedit,
			'isdel' 	=> $isdel,
			'explain'	=> $sm
		);
	}
	private function iscaozuo()
	{
		if($this->adminid!=1 || getconfig('system')=='demo' || !getconfig('sysfileview'))return '禁止操作';
		return '';
	}
	
	public function editAction()
	{
		if($str=$this->iscaozuo())return $str;
		$path = $this->jm->base64decode($this->get('path'));
		if(isempt($path))return '无效路径';
		if(!file_exists($path))return '文件不存在';
		$pathinfo=pathinfo($path);
		
		$filename = $pathinfo['basename'];
		$filesize = filesize($path);
		$content  = file_get_contents($path);
		$encode   = mb_detect_encoding($content, array('ASCII','UTF-8','GB2312','GBK','BIG5'));
		
		if($encode && $encode != "UTF-8"){
			$content = iconv($encode,'utf-8',$content);
		}
		
		$this->smartydata['filename'] = $filename;
		$fileext = strtolower(substr($path,strripos($path,'.')+1));
		$this->smartydata['fileext'] = $fileext;
		$this->smartydata['content'] = $content;
		$this->smartydata['filepath']    = $this->jm->base64encode($path);
		$this->smartydata['filesize'] = $this->rock->formatsize($filesize);
	}
	
	private function delfolder($path)
	{
		$this->fileall 	 = array();
		$this->folderall = array();
		$this->getallfile($path);
		$total = count($this->fileall);
		if($this->fileall)foreach($this->fileall as $file)unlink($file);
		if($this->folderall)foreach($this->folderall as $file)rmdir($file);
		if(is_dir($path))rmdir($path);
		return '共删除'.$total.'个文件';
	}
	
	/**
	*	清理
	*/
	public function clearlogsAjax()
	{
		if($str=$this->iscaozuo())return $str;
		$path = ''.UPDIR.'/logs';
		return $this->delfolder($path);
	}
	private function getallfile($path)
	{
		$dir_arr = @scandir($path);
		$darr1=	$rows = array();
		if($dir_arr)foreach($dir_arr as $key=>$val){
			if($val == '.' || $val == '..'){
			}else{
				$mulu = $path.'/'.$val.'';
				if(is_dir($mulu)){
					$this->getallfile($mulu);
					$this->folderall[] = $mulu;
				}else{
					$this->fileall[] = $mulu;
				}
			}
		}
	}
	
	/**
	*	删除文件
	*/
	public function delfileAjax()
	{
		if($str=$this->iscaozuo())return $str;
		$path = $this->jm->base64decode($this->get('path'));
		if(isempt($path))return '无效文件';
		$isdel = 0;
		foreach($this->isdelmu as $sdif)if(strpos($path, $sdif)===0)$isdel=1;
		if($isdel==0)return '此文件禁止删除';
		if(is_dir($path))return $this->delfolder($path);
		unlink($path);
		return '删除成功';
	}
	
	public function svnupdateAjax()
	{
		$cmd = '"'.getconfig('svnpath').'" /command:update /closeonend:1 /path:"'.ROOT_PATH.'"';
		c('socket')->udpsend($cmd);
		return '已发送svn更新';
	}
}