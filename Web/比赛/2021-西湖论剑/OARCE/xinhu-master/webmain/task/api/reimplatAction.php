<?php 
/**
*	作者：信呼开发团队(雨中磐石)
*	官网：http://www.rockoa.com
*	软件：信呼OA
*	REIM即使通信平台回调
*/
class reimplatClassAction extends apiAction{

	public function initAction()
	{
		$this->display= false;
	}
	
	//平台上通知过来的数据
	public function indexAction()
	{
		$body = $this->getpostdata();
		if(!$body)return;
		$db 	 = m('reimplat:dept');
		$key 	 = $db->gethkey();
		$bodystr = $this->jm->strunlook($body, $key);
		if(!$bodystr)return;
	
		$data 	 = json_decode($bodystr, true);
		$msgtype = arrvalue($data,'msgtype');
		$msgevent= arrvalue($data,'msgevent');
		
		//用户状态改变停用
		if($msgtype=='subscribe'){
			$user 	= arrvalue($data, 'user');
			$zt 	= '0';
			if($msgevent=='yes')$zt = '1';
			if($msgevent=='stop')$zt = '2';
			$db->update('`status`='.$zt.'',"`user`='$user'");
		}
		
		//修改手机号
		if($msgtype=='editmobile'){
			$user 	= arrvalue($data, 'user');
			$mobile = arrvalue($data, 'mobile');
			$where  = "`user`='$user'";
			$upstr  = "`mobile`='$mobile'";
			$db->update($upstr, $where);
			$dbs	= m('admin');
			$dbs->update($upstr,$where);
			$uid 	= $dbs->getmou('id',$where);
			m('userinfo')->update($upstr,"`id`='$uid'");
		}
		
		//修改密码
		if($msgtype=='editpass'){
			$user = arrvalue($data, 'user');
			$pass = arrvalue($data, 'pass');
			if($pass && $user){
				$where  = "`user`='$user'";
				$mima 	= md5($pass);
				m('admin')->update("`pass`='$mima',`editpass`=`editpass`+1", $where);
			}
		}
	}
}