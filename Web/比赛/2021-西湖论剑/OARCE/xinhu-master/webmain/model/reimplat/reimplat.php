<?php
class reimplatModel extends Model
{
	public function initReimplat(){}
	
	public $reimplat_purl;
	public $reimplat_cnum;
	public $reimplat_secret;
	public $reimplat_devnum;
	public $reimplat_huitoken;
	
	public function initModel()
	{
		$this->option		= m('option');
		$this->reimplat_purl		= $this->option->getval('reimplat_purl');
		$this->reimplat_cnum		= $this->option->getval('reimplat_cnum');
		$this->reimplat_secret		= $this->option->getval('reimplat_secret');
		$this->reimplat_devnum		= $this->option->getval('reimplat_devnum');
		$this->reimplat_huitoken	= $this->option->getval('reimplat_huitoken');
		if(getconfig('systype')=='dev')$this->reimplat_purl = 'http://localhost/app/rockreim/';
		$this->initReimplat();
	}
	
	public function gethkey()
	{
		$key = $this->reimplat_huitoken;
		if(isempt($key))$key = $this->reimplat_secret;
		if(isempt($key))$key = $this->reimplat_cnum;
		return md5($key);
	}
	
	//获取地址
	public function geturl($m, $a,$can=array())
	{
		$url = $this->reimplat_purl;
		if(!$url)return showreturn('','没设置REIM通信平台地址', 201);
		if(substr($url,0,4)!='http')$url = 'http://'.$url.'';
		if(substr($url,-1)!='/')$url.='/';
		$url.= 'api.php?m='.$m.'&a='.$a.'';
		$url.= '&cnum='.$this->reimplat_cnum.'';
		$url.= '&secret='.$this->reimplat_secret.'';
		foreach($can as $k=>$v)$url.='&'.$k.'='.$v.'';
		return $url;
	}
	
	public function recordchu($cont)
	{
		$data  	= array('code'=>201,'success'=>false,'msg'=>'出错,返回:'.htmlspecialchars($cont).'');
		if($cont!='' && substr($cont,0,1)=='{'){
			$data  	= json_decode($cont, true);
		}
		return $data;
	}
}