<?php
/**
*	sso登录
*	访问地址如：http://demo.rockoa.com/api.php?m=opensso
*/
class openssoClassAction extends openapiAction
{
	
	public function initAction()
	{
		$this->display= false;
	}
	
	public function indexAction()
	{
		$ssotoken	= $this->get('ssotoken');
		$lurl		= urldecode($this->get('backurl')); //登录成功跳转地址urlencode
		if(isempt($ssotoken))return '没有参数ssotoken';
		
		$checkurl 	= getconfig('sso_checkurl'); //验证地址
		$ssokey 	= getconfig('sso_key'); //验证key
		
		if(isempt($checkurl))return '没有配置验证地址';
		$jg 	= contain($checkurl,'?')?'&':'?';
		
		$url 	= $checkurl.$jg.'ssotoken='.$ssotoken.'&ssokey='.$ssokey.'';
		$user 	= c('curl')->getcurl($url); //要返回用户帐号/手机号
		if(isempt($user))return '验证失败没有返回值';
		
		//调用登录方法验证
		$lobj	= m('login');
		$rand 	= md5(''.$this->rock->now.''.$user.'');
		$lobj->setloginrand($rand);
		$arr 	= $lobj->start($user, $rand, 'pc','SSO');
		if(!is_array($arr)){
			return $arr;
		}else{
			$uid 	= $arr['uid'];
			$name 	= $arr['name'];
			$user 	= $arr['user'];
			$token 	= $arr['token'];
			$lobj->setsession($uid, $name, $token, $user);
			if(isempt($lurl)){
				$lurl = 'index.php?m=index';
				if($this->rock->ismobile())$lurl='index.php?d=we';
			}
			$this->rock->location($lurl);//跳转
			return 'success';
		}
	}
}	