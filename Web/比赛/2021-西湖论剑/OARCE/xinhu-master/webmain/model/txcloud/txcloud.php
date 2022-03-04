<?php
class txcloudModel extends Model
{
	private $SecretId	= '';
	private $SecretKey	= '';
	
	//api地址[这个是人脸识别上的]
	public $apiurl		= 'iai.tencentcloudapi.com';
	
	public function inittxCloud(){}
	
	public function initModel()
	{
		$this->option		= m('option');
		$this->SecretId 	= $this->rock->jm->uncrypt($this->option->getval('txcloud_secretid'));
		$this->SecretKey 	= $this->rock->jm->uncrypt($this->option->getval('txcloud_secretkey'));
		$this->inittxCloud();
	}
	
	//创建签名和参数
	private function createAuth($can = array())
	{
		$barr['Action'] 	= '';
		$barr['Region'] 	= ''; //地区
		$barr['SecretId'] 	= $this->SecretId;
		$barr['Timestamp'] 	= time();
		$barr['Nonce'] 		= rand(10000,99999);
		$barr['Version'] 	= '2018-03-01';
		foreach($can as $k=>$v)$barr[$k] = $v;
		
		ksort($barr);
		$str	= '';
		foreach($barr as $k=>$v)$str.='&'.$k.'='.$v.'';
		$str	= substr($str, 1);
		$srcStr	= 'POST'.$this->apiurl.'/?'.$str.'';
		
		$signStr = base64_encode(hash_hmac('sha1', $srcStr, $this->SecretKey, true));
		
		$barr['Signature'] = urlencode($signStr);
		return $barr;
	}
	
	
	/**
	*	发送请求 $Action方法，其他参数
	*/
	public function send($Action, $can = array(), $njcan=array())
	{
		if(isempt($this->SecretId) || isempt($this->SecretKey))return returnerror('没有完整配置腾讯云api密钥');
		
		$can['Action'] = $Action;
		$params = $this->createAuth($can);
		$url 	= 'https://'.$this->apiurl.'';
		
		foreach($njcan as $k=>$v)$params[$k]= urlencode($v);
		
		$result = c('curl')->postjson($url, $params);
		
		if(isempt($result))return returnerror('无法访问接口');
		$barr	= json_decode($result, true);
		if(!isset($barr['Response']))return returnerror('返回出错'.$result.'');
		
		$Response = $barr['Response'];
		if(isset($Response['Error'])){
			$error = $Response['Error'];
			return returnerror('error:'.$error['Message'].','.$error['Code'].'');
		}
		
		return returnsuccess($Response);
	}
	
}