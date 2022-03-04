<?php
/**
*	获取验证码接口
*/ 
class yanzmClassAction extends apiAction
{
	
	public function initAction()
	{
		$this->display= false;
	}
	
	/**
	*	获取验证码的
	*/
	public function indexAction()
	{
		$mobile = $this->rock->jm->uncrypt($this->post('mobile'));
		if(!c('check')->ismobile($mobile))return returnerror('手机号格式有误');
		
		$device	= $this->post('device');
		if(isempt($device))return returnerror('无效访问');
		
		return c('xinhuapi')->getvercode($mobile, $device);
	}
	
	/**
	*	登录时候
	*/
	public function gloginAction()
	{
		$mobile = $this->post('mobile');
		if(isempt($mobile))return returnerror('手机号不能为空');
		
		if($this->rock->isjm($mobile))$mobile = $this->jm->uncrypt($mobile);
		
		if(!c('check')->ismobile($mobile))return returnerror('手机号格式有误');
		
		$device	= $this->post('device');
		if(isempt($device))return returnerror('无效访问');
		
		//判断是否有注册
		if(m('admin')->rows("`mobile`='$mobile' and `status`=1")==0)return returnerror('此手机号不存在/已停用');
		
		return c('xinhuapi')->getvercode($mobile, $device);
	}
}