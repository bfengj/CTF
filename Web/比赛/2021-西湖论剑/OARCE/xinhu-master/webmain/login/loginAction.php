<?php 
class loginClassAction extends ActionNot{
	
	public function defaultAction()
	{
		$this->tpltype	= 'html';
		$this->smartydata['ca_adminuser']	= $this->getcookie('ca_adminuser');
		$this->smartydata['ca_rempass']		= $this->getcookie('ca_rempass');
		$this->smartydata['ca_adminpass']	= $this->getcookie('ca_adminpass');
		$this->smartydata['loginyzm']		= (int)getconfig('loginyzm','0'); //登录类型
		$this->smartydata['platsign']		= $this->getsession('platsign');
	}
	
	public function checkAjax()
	{
		$user 	= $this->jm->base64decode($this->post('adminuser'));
		$user	= str_replace(' ','',$user);
		$pass	= $this->jm->base64decode($this->post('adminpass'));
		$rempass= $this->post('rempass');
		$jmpass	= $this->post('jmpass');
		$cfrom	= $this->post('cfrom','pc');
		if($jmpass == 'true')$pass=$this->jm->uncrypt($pass);
		$userp	= $user;
		$arr 	= m('login')->start($user, $pass, $cfrom);
		$barr 	= array();
		if(is_array($arr)){
			
			if(isset($arr['mobile'])){
				$barr = $arr;
				$barr['success'] = false;
				return $barr;
			}
			
			$uid 	= $arr['uid'];
			$name 	= $arr['name'];
			$user 	= $arr['user'];
			$token 	= $arr['token'];
			$face 	= $arr['face'];
			m('login')->setsession($uid, $name, $token, $user);
			$this->rock->savecookie('ca_adminuser', $userp);
			$this->rock->savecookie('ca_rempass', $rempass);
			$ca_adminpass	= $this->jm->encrypt($pass);
			if($rempass=='0')$ca_adminpass='';
			$this->rock->savecookie('ca_adminpass', $ca_adminpass);
			$barr['success'] = true;
			$barr['face'] 	 = $face;
		}else{
			$barr['success'] = false;
			$barr['msg'] 	 = $arr;
		}
		return $barr;
	}
	
	public function exitAction()
	{
		m('dept')->online(0);//离线
		m('login')->exitlogin('pc',$this->admintoken);
		$this->rock->location('?m=login');
	}
}