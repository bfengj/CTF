<?php 
class xinhuChajian extends Chajian{
	
	private $updatekey 	= '';
	private $updatekeys = 'aHR0cDovLzEyNy4wLjAuMS9hcHAvcm9ja3hpbmh1d2ViLw::';
	
	protected function initChajian()
	{
		if(getconfig('systype')=='dev'){
			$this->updatekey = $this->rock->jm->base64decode($this->updatekeys);
		}else{
			$this->updatekey = URLY;
		}
		if(isempt($this->updatekey))$this->updatekey=$this->rock->jm->base64decode('aHR0cDovL3d3dy5yb2Nrb2EuY29tLw::');
	}
	
	public function getwebsite()
	{
		return $this->updatekey;
	}
	
	public function geturlstr($act, $can=array())
	{
		$url = $this->updatekey;
		$url.= 'api.php?a='.$act.'';
		$url.= '&host='.$this->rock->jm->base64encode(HOST).'&version='.VERSION.'&time='.time().'&web='.$this->rock->web.'&ipstr='.$this->rock->ip.'&randkey='.getconfig('randkey').'&xinhukey='.getconfig('xinhukey').'';
		$url.= '&authorkey='.getconfig('authorkey').'&cfrom='.getconfig('xinhutype').'';
		if($act!='xinhuinstall')$url.= '&aukey='.m('option')->getval('auther_aukey').'';
		foreach($can as $k=>$v)$url.='&'.$k.'='.$v.'';
		return $url;
	}
	
	public function getdata($act, $can=array())
	{
		$url 	= $this->geturlstr($act, $can);
		$cont 	= c('curl')->getcurl($url);
		$data  	= array('code'=>199,'msg'=>'出错'.URLY.',返回:'.htmlspecialchars($cont).'');
		if($cont!='' && substr($cont,0,1)=='{'){
			$data  	= json_decode($cont, true);
		}
		return $data;
	}
	
	public function helpstr($num, $na='')
	{
		if($na=='')$na='帮助';
		return '<a style="color:blue" href="'.$this->updatekey.'view_'.$num.'.html" target="_blank">['.$na.']</a>';
	}
	
}