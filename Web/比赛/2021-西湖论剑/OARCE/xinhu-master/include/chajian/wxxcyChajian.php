<?php
/**
*	微信小程序接口
*/
class wxxcyChajian extends Chajian{
	

	protected function initChajian()
	{
		$this->wxxcy_url	= getconfig('wxxcy_url'); //微信小程序地址
		if(getconfig('systype')=='dev')$this->wxxcy_url='http://localhost/app/xcy_hosh/';
	}
	
	/**
	*	发送订阅消息
	*	$xcytype 类型入hetong
	*	$mobile 接收人员手机号
	*	$params 参数，多个|分开
	*	$tplid 发给哪个模版用0,1,2,3等
	*/
	public function subscribe($xcytype, $mobile, $params,$tplid='0',$path='')
	{
		if(!$this->wxxcy_url)return returnerror('wxxcy_url empty');
		$onr = m('wxxcyus')->getone("`xcytype`='$xcytype' and `mobile`='$mobile'");
		if(!$onr)return returnerror('no subscribe');
		$url = ''.$this->wxxcy_url.'api.php?m=weixin&a=subscribe&xcytype='.$xcytype.'';
		$data['mobile'] = $mobile;
		$data['params'] = $params;
		$data['tplid']  = $tplid;
		$data['path']   = $path;
		
		$result = c('curl')->postcurl($url, $data);
		if(!$result)return returnerror('api not data');
		if(substr($result,0,1)!='{')return returnerror('err:'.$result.'');
		
		return json_decode($result, true);
	}
}