<?php
class emailClassModel extends Model
{
	
	private $errorinfo = '';
	
	public function initModel()
	{
		$this->settable('email_cog');
	}
	/**
	*	系统邮件发送邮件
	*	$to_uid 发送给。。。
	*	$rows	内容
	*/
	public function sendmail($title, $body, $to_uid, $rows=array(), $zjsend=0, $oparm=array())
	{
		if(!function_exists('socket_get_status') || !function_exists('fsockopen'))return '没有开启socket扩展无法使用';
		if(!function_exists('openssl_sign'))return '没有开启openssl扩展无法使用';
		
		$setrs		= m('option')->getpidarr(-1);
		if(!$setrs)return '未设置发送邮件';
		
		$serversmtp 	= $this->rock->arrvalue($setrs, 'email_sendhost');
		$emailuser  	= $this->rock->arrvalue($setrs, 'email_sysuser');
		$emailname  	= $this->rock->arrvalue($setrs, 'email_sysname');
		$emailpass  	= $this->rock->arrvalue($setrs, 'email_syspass');
		$serverport  	= $this->rock->arrvalue($setrs, 'email_sendport');
		$emailsecure  	= $this->rock->arrvalue($setrs, 'email_sendsecure');
		
		if(isempt($serversmtp) || isempt($serverport) || isempt($emailuser)|| isempt($emailpass))return '未设置发送帐号';

		$to_em	= $to_mn = $to_id 	= '';
		
		if(is_array($to_uid)){
			$to_id = arrvalue($to_uid,'receid','0');
			$to_em = arrvalue($to_uid,'receemail');
			$to_mn = arrvalue($to_uid,'recename');
		}else{
			$urs	= $this->db->getall("select `email`,`name`,`id` from `[Q]admin` where `id` in($to_uid) and `email` is not null and `status`=1 order by `sort`");
			foreach($urs as $k=>$rs){
				$to_em.=','.$rs['email'];
				$to_mn.=','.$rs['name'];
				$to_id.=','.$rs['id'];
			}	
			if(isempt($to_em))return '用户('.$to_uid.')没有设置邮箱';
			$to_em	= substr($to_em, 1);
			$to_mn	= substr($to_mn, 1);
			$to_id	= substr($to_id, 1);
		}
		
		if(isempt($to_em))return '没有接收人1';

		$body	= $this->rock->reparr($body, $rows);
		$title	= $this->rock->reparr($title, $rows);
			
		$body	= str_replace("\n", '<br>', $body);
		
		$msg 	= 'ok';
		
		if(!getconfig('asynsend') || $zjsend==1){
			$sarrs	= array(
				'emailpass' 	=> $emailpass,
				'serversmtp' 	=> $serversmtp,
				'serverport' 	=> $serverport,
				'emailsecure' 	=> $emailsecure,
				'emailuser' 	=> $emailuser,
				'emailname' 	=> $emailname,
				'receemail' 	=> $to_em,
				'recename' 		=> $to_mn,
				'title' 		=> $title,
				'body' 			=> $body,
			);
			foreach($oparm as $k1=>$v1)$sarrs[$k1] = $v1;
			$bo 	= $this->sendddddd($sarrs, true);
			if(!$bo)$msg = $this->errorinfo;
		}else{
			//异步发送邮件
			$uarr['title'] 		= $title;
			$uarr['body'] 		= $body;
			$uarr['receid'] 	= $to_id;
			$uarr['recename'] 	= $to_mn;
			$uarr['receemail'] 	= $to_em;
			$uarr['optdt'] 		= $this->rock->now();
			$uarr['optid'] 		= $this->adminid;
			$uarr['optname'] 	= $this->adminname;
			$uarr['status'] 	= 0;
			foreach($oparm as $k1=>$v1)$uarr[$k1] = $v1;
			$sid 	= m('email_cont')->insert($uarr);
			m('reim')->asynurl('asynrun','sendemail', array(
				'id' 	=> $sid,
				'stype' => 0
			));//系统邮件提醒用的
		}
		return $msg;
	}
	
	/**
	*	
	*/
	public function sendtoemail($params=array())
	{
		$setrs		= m('option')->getpidarr(-1);
		if(!$setrs)return '未设置发送邮件';
		
		$serversmtp 	= $this->rock->arrvalue($setrs, 'email_sendhost');
		$emailuser  	= $this->rock->arrvalue($setrs, 'email_sysuser');
		$emailname  	= $this->rock->arrvalue($setrs, 'email_sysname');
		$emailpass  	= $this->rock->arrvalue($setrs, 'email_syspass');
		$serverport  	= $this->rock->arrvalue($setrs, 'email_sendport');
		$emailsecure  	= $this->rock->arrvalue($setrs, 'email_sendsecure');
		
		$barr 			= array(
			'emailpass' 	=> $emailpass,
			'serversmtp' 	=> $serversmtp,
			'serverport' 	=> $serverport,
			'emailsecure' 	=> $emailsecure,
			'emailuser' 	=> $emailuser,
			'emailname' 	=> $emailname,
			'receemail' 	=> '',
			'recename' 		=> '',
			'title' 		=> '',
			'body' 			=> '',
		);
		foreach($params as $k=>$v)$barr[$k]=$v;
		return $this->sendddddd($barr, true);
	}
	
	//$jbs 密码是否加密 保存日志$log
	private function sendddddd($arr, $jbs, $log=false)
	{
		extract($arr);
		$pass	= $emailpass;
		if($jbs)$pass	= $this->rock->jm->uncrypt($pass);
		$mail	= c('mailer');
		$mail->setHost($serversmtp, $serverport, $this->rock->repempt($emailsecure));
		$mail->setUser($emailuser, $pass);
		$mail->setFrom($emailuser, $emailname);
		$mail->addAddress($receemail, $recename);
		if(isset($ccemail) && !isempt($ccemail)){
			$mail->addCC($ccemail, $ccname);
		}
		if(isset($attachpath) && !isempt($attachpath)){
			$mail->addAttachment($attachpath, $attachname);
		}
		$mail->sendMail($title, $body);
		$bo		= $mail->isSuccess();
		if(!$bo){
			$this->errorinfo = 'error:'.$mail->getErrror().';to:'.$receemail.'';
		}
		return $bo;
	}
	
	/**
	*	测试发送邮件
	*/
	public function sendmail_test()
	{
		return $this->sendmail('测试邮件帐号','这只是一个测试邮件帐号，不要紧张！<br>来自：'.TITLE.'<br>发送人：'.$this->adminname.'<br>网址：'.URL.'<br>发送时间：'.$this->rock->now().'', $this->adminid, array(),1);
	}
	
	/**
	*	异步发送邮件
	*/
	public function sendemailcont($id, $stype=-1)
	{
		$rs 	= m('email_cont')->getone($id);
		if(!$rs)return '记录不存在';
		if($stype==-1)$stype	= (int)$this->rock->get('stype');
		if($stype == 0){
			$msg 	= $this->sendmail($rs['title'],$rs['body'], array(
				'receid' 	=> $rs['receid'],
				'receemail' => $rs['receemail'],
				'recename' 	=> $rs['recename'],
			), array(), 1, array(
				'ccname' 	=> $rs['ccname'],
				'ccemail' 	=> $rs['ccemail'],
				'attachpath'=> $rs['attachpath'],
				'attachname'=> $rs['attachname'],
			));
		}else{
			$msg 	= $this->sendemailout($rs['optid'],array(
				'title' 	=> $rs['title'],
				'body' 		=> $rs['body'],
				'receemail' => $rs['receemail'],
				'recename' 	=> $rs['recename'],
				'ccname' 	=> $rs['ccname'],
				'ccemail' 	=> $rs['ccemail'],
				'attachpath'=> $rs['attachpath'],
				'attachname'=> $rs['attachname'],
				'mid'		=> $rs['mid'],
			), 1);
		}
		$status = '2';
		if($msg=='ok')$status = '1';
		$uarr['status'] = $status;
		$uarr['senddt'] = $this->rock->now();
		m('email_cont')->update($uarr, $id);
		return $msg;
	}
	
	
	/**
	*	用户自己外发发送
	*/
	public function sendemailout($sendid, $canarr = array(), $zjsend=0)
	{
		$sendarr 		= array(
			'title'			=> '',
			'body'			=> '',
			'receemail'		=> '',
			'recename'		=> '',
			'ccname'		=> '',
			'ccemail'		=> '',
			'attachpath'	=> '',
			'attachname'	=> '',
		);
		foreach($canarr as $k=>$v)$sendarr[$k] = $v;
		extract($sendarr);
		$setrs			= m('option')->getpidarr(-1);
		if(!$setrs)return '未设置发送邮件';
		$serversmtp 	= $this->rock->arrvalue($setrs, 'email_sendhost');
		$serverport  	= $this->rock->arrvalue($setrs, 'email_sendport');
		$emailsecure  	= $this->rock->arrvalue($setrs, 'email_sendsecure');
		$myuser 		= m('admin')->getone($sendid,'name,email,emailpass');
		if(!$myuser)return '发送人不存在';

		$emailuser  	= $this->rock->arrvalue($myuser, 'email');
		$emailname  	= $this->rock->arrvalue($myuser, 'name');
		$emailpass  	= $this->rock->arrvalue($myuser, 'emailpass');
		
		if(isempt($serversmtp) || isempt($serverport) || isempt($emailuser)|| isempt($emailpass))return '用户未设置邮件帐号密码';
		
		$msg 	= 'ok';
		$outzt	= 2;
		if(!getconfig('asynsend') || $zjsend==1){
			$bo 	= $this->sendddddd(array(
				'emailpass' 	=> $emailpass,
				'serversmtp' 	=> $serversmtp,
				'serverport' 	=> $serverport,
				'emailsecure' 	=> $emailsecure,
				'emailuser' 	=> $emailuser,
				'emailname' 	=> $emailname,
				'receemail' 	=> $receemail,
				'recename' 		=> $recename,
				'ccname' 		=> $ccname,
				'ccemail' 		=> $ccemail,
				'attachpath' 	=> $attachpath,
				'attachname' 	=> $attachname,
				'title' 		=> $title,
				'body' 			=> $body,
			), false);
			if(!$bo)$msg = $this->errorinfo;
			if(isset($mid)){
				if($msg=='ok')$outzt=1;
				m('emailm')->update('`outzt`='.$outzt.'', $mid);
			}
		}else{
			//异步发送邮件
			$uarr['title'] 		= $title;
			$uarr['body'] 		= $body;
			$uarr['receid'] 	= '';
			$uarr['recename'] 	= $recename;
			$uarr['receemail'] 	= $receemail;
			$uarr['ccname'] 	= $ccname;
			$uarr['ccemail'] 	= $ccemail;
			$uarr['attachpath'] = $attachpath;
			$uarr['attachname'] = $attachname;
			$uarr['optdt'] 		= $this->rock->now();
			$uarr['optid'] 		= $this->adminid;
			$uarr['optname'] 	= $this->adminname;
			$uarr['status'] 	= 0;
			if(isset($mid))$uarr['mid'] = $mid;
			$sid 	= m('email_cont')->insert($uarr);
			c('rockqueue')->push('email,anaysend', array(
				'id' 	=> $sid,
				'stype' => 1
			));
			/*
			m('reim')->asynurl('asynrun','sendemail', array(
				'id' 	=> $sid,
				'stype' => 1
			));*/
		}
		return $msg;
	}
}