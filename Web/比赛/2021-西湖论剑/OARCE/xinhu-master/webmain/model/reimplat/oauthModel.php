<?php
class reimplat_oauthClassModel extends reimplatModel
{
	public function login()
	{
		$platsign = $this->rock->session('platsign');
		if(isempt($platsign))return returnerror('无效登录');
		$url 	= $this->geturl('openoauth','oauthinfo', array(
			'platsign' => $platsign
		));
		
		$result = c('curl')->getcurl($url);
		$barr 	= $this->recordchu($result);
		if(!$barr['success'])return $barr;
		
		$info 	= $barr['data']['userinfo'];
		$user 	= $info['user'];
		$usr 	= m('admin')->getone("`status`=1 and `user`='$user'");
		if(!$usr)return returnerror('oa上用户不存在');
		c('cache')->set('login'.$usr['user'].'', $usr['id'], 60);
		
		return returnsuccess(array(
			'user' => $user,
			'pass' => md5($usr['pass'])
		));
	}
}