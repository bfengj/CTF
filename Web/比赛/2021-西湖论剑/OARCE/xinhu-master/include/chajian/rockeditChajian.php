<?php 
/**
*	连接官网在线编辑文档
*/

class rockeditChajian extends Chajian{
	

	protected function initChajian()
	{
		$urs = $this->rock->jm->base64decode('aHR0cDovL29mZmljZS5yb2Nrb2EuY29tLw::');
		$url = getconfig('officebj_url', $urs);
		$this->agentkey = getconfig('officebj_key');
		if(substr($url,-1)!='/')$url.='/';
		$this->updatekel = $url;
		$this->updatekey = $url.'api.php';
	}
	
	
	public function geturlstr($mod, $act, $can=array())
	{
		$url 	= $this->updatekey;
		$url.= '?m='.$mod.'&a='.$act.'';
		$url.= '&host='.$this->rock->jm->base64encode(HOST).'&ip='.$this->rock->ip.'&xinhukey='.getconfig('xinhukey').'';
		$url.= '&adminid='.$this->adminid.'';
		$url.= '&agentkey='.$this->agentkey.'';
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
	public function postdata($mod, $act, $can=array())
	{
		$url 	= $this->geturlstr($mod, $act);
		$cont 	= c('curl')->postcurl($url, $can);
		if(!isempt($cont) && contain($cont, 'success')){
			$data  	= json_decode($cont, true);
		}else{
			$data 	= returnerror('无法访问,'.$cont.'');
		}
		return $data;
	}
	
	public function sendedit($id, $admintoken='', $otype=0)
	{
		$frs 		= m('file')->getone($id);
		if(!$frs)return returnerror('文件不存在');
		
		$filepath 	= $frs['filepath'];
		$filepathout= arrvalue($frs, 'filepathout');
		$onlynum	= $frs['onlynum'];
		$recedata   = '';
		if(substr($filepath,0,4)!='http' && !file_exists($filepath)){
			if(isempt($filepathout))return returnerror('文件不存在2');
			$filepath = $filepathout;
			$recedata = $filepath;
		}
		
		if(isempt($onlynum)){
			$onlynum	= md5(''.$this->rock->jm->getRandkey().date('YmdHis').'file'.$id.'');
			m('file')->update("`onlynum`='$onlynum'", $id);
		}
		$urs 		= m('admin')->getone($this->adminid);
		$barr 		= $this->getdata('file','change', array(
			'filenum' 	=> $onlynum,
			'optid'		=> $this->adminid,
			'optname'	=> $this->rock->jm->base64encode($this->adminname),
			'face'		=> $this->rock->jm->base64encode(m('admin')->getface($urs['face'])),
		));
		if(!$barr['success'])return $barr;
		$data 		= $barr['data'];
		$type 		= $data['type'];
		$gokey		= $data['gokey'];
		$gourl 		= arrvalue($data,'gourl');
		if(isempt($gourl))$gourl = $this->updatekel;
		$bsar		= $data;
		if($type=='0'){
			if($recedata=='')$recedata = $this->rock->jm->base64encode(file_get_contents($filepath));
			$barr 	= $this->postdata('file','recedata', array(
				'data' 		=> $recedata,
				'fileid' 	=> $id,
				'filenum' 	=> $onlynum,
				'fileext'	=> $frs['fileext'],
				'filename'	=> $frs['filename'],
				'optid'		=> $frs['optid'],
				'optname'	=> $frs['optname'],
				'filesize'	=> $frs['filesize'],
				'filesizecn'=> $frs['filesizecn'],
			));
			if(!$barr['success'])return $barr;
			$bsar['type'] = '1';
		}
		if($bsar['type']=='1'){
			$url = $gourl.'api.php?m=file&a=goto&filenum='.$onlynum.'&sign='.md5($this->rock->HTTPweb).'';
			$url.= '&optid='.$this->adminid.'';
			$url.= '&gokey='.$gokey.'';
			$url.= '&otype='.$otype.'';
			if($otype==0){
				$callurl = $this->rock->getouturl().'api.php?m=upload&a=upfilevb&fileid='.$id.'&adminid='.$this->adminid.'&token='.$admintoken.'';
				$url.='&callurl='.$this->rock->jm->base64encode($callurl).'';
			}
			$bsar['url'] = $url;
		}
		return returnsuccess($bsar);
	}
}