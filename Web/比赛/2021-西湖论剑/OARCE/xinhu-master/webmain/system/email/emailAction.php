<?php
class emailClassAction extends Action
{
	public function setsaveAjax()
	{
		$this->option->setval('email_sendhost@-1', $this->post('sendhost'));
		$this->option->setval('email_sendport@-1', $this->post('sendport'));
		$this->option->setval('email_recehost@-1', $this->post('recehost'));
		$this->option->setval('email_sendsecure@-1', $this->post('sendsecure'));
		$this->option->setval('email_sysname@-1', $this->post('sysname'));
		$this->option->setval('email_sysuser@-1', $this->post('sysuser'));
		$this->option->setval('email_receyumi@-1', $this->post('receyumi'));
		$syspass	= $this->post('syspass');
		if(!isempt($syspass)){
			$this->option->setval('email_syspass@-1', $this->jm->encrypt($syspass));
		}
		$this->backmsg();
	}
	
	public function getsetAjax()
	{
		$arr= array();
		$arr['sendhost']	= $this->option->getval('email_sendhost');
		$arr['sendport']	= $this->option->getval('email_sendport');
		$arr['recehost']	= $this->option->getval('email_recehost');
		$arr['sendsecure']	= $this->option->getval('email_sendsecure');
		$arr['sysname']		= $this->option->getval('email_sysname');
		$arr['sysuser']		= $this->option->getval('email_sysuser');
		$arr['receyumi']	= $this->option->getval('email_receyumi');
		echo json_encode($arr);
	}
	
	public function savebeforecog($table, $cans)
	{
		$emailpass	= $this->post('emailpass');
		if(!isempt($emailpass)){
			$cans['emailpass'] = $this->jm->encrypt($emailpass);
		}
		return array(
			'rows' => $cans
		);
	}
	
	public function coguserbeforeshow($table)
	{
		$fields = '`id`,`name`,`user`,`deptallname`,`status`,`ranking`,`email`,`sort`,`face`,`emailpass`';
		$s 		= '';
		$key 	= $this->post('key');
		if($key!=''){
			$s = m('admin')->getkeywhere($key);
		}
		return array(
			'fields'=> $fields,
			'where'	=> $s,
			'order'	=> '`sort`'
		);
	}
	public function coguseraftershow($table, $rows)
	{
		foreach($rows as $k=>$rs){
			if(!isempt($rs['emailpass']))$rows[$k]['emailpass']='******';
		}
		return array(
			'rows' => $rows
		);
	}
	
	public function testsendAjax()
	{
		$msg 	= m('email')->sendmail_test();
		echo $msg;
	}
	
	public function emailtotals($table, $rows)
	{
		$emrs = m('admin')->getone($this->adminid, 'email');
		$istxemail = $this->option->getval('txemail_corpid') ? 1 : 0;
		return array(
			'rows' => $rows,
			'email'=> $emrs,
			'istxemail'=> $istxemail,
			'total'=> m('emailm')->zongtotal($this->adminid)
		);
	}
	
	
	//收信
	public function recemailAjax()
	{
		$barr 	= m('emailm')->receemail($this->adminid);
		if(is_array($barr)){
			$this->showreturn($barr['count']);
		}else{
			$this->showreturn('', $barr, 201);
		}
	}
	
	//标已读
	public function biaoydAjax()
	{
		$sid = c('check')->onlynumber($this->post('sid'));
		m('emailm')->biaoyd($this->adminid, $sid);
		echo '成功标识';
	}
		
	/**
	*	删除邮件
	*/
	public function delyjAjax()
	{
		$sid 	= c('check')->onlynumber($this->post('sid'));
		$atype 	= $this->post('atype');
		$uid 	= $this->adminid;
		//收件箱删除
		if($atype==''){
			m('emails')->update('isdel=1','`uid`='.$uid.' and `mid` in('.$sid.') and `type` in(0,1)');
		}
		//草稿箱删除
		if($atype=='cgx'){
			m('emailm')->delete('`id` in('.$sid.') and `sendid`='.$uid.' and `isturn`=0');
		}
		//已发送删除
		if($atype=='yfs'){
			m('emails')->update('isdel=1','`uid`='.$uid.' and `mid` in('.$sid.') and `type`=2');
		}
		//已删除删除
		if($atype=='ysc'){
			m('emails')->delete('`uid`='.$uid.' and `mid` in('.$sid.') and `isdel`=1 and `type` in(0,1)');
		}
		echo '删除成功';
	}
	
	//用户修改自己邮箱密码
	public function saveemaipassAjax()
	{
		$pass = $this->post('emailpass');
		if(getconfig('systype')!='demo')m('admin')->update("`emailpass`='$pass'", '`id`='.$this->adminid.'');
		$this->backmsg('','修改成功');
	}
	
	
	//设置自动接收邮件
	public function helpsetAction()
	{
		$this->display = false;
		$ljth = str_replace('/','\\',ROOT_PATH);
		echo '<title>自动接收邮件设置</title>';
		echo '<br>';
		
		echo '<font color="red">自动接收邮件必须使用服务器的计划任务你参考以下设置。</font><br><a target="_blank" style="color:blue" href="'.URLY.'view_email.html">查看官网上帮助</a><br>';
		
		echo '一、<b>Windows服务器</b>，可根据以下设置定时任务<br>';
		$str1 = '@echo off
cd '.$ljth.'	
'.getconfig('phppath','php').' '.$ljth.'\task.php email';
		$this->rock->createtxt(''.UPDIR.'/cli/xinhuemailrun.bat', $str1);
		
		echo '1、打开系统配置文件webmainConfig.php加上一个配置phppath设置php环境的目录地址如：F:\php\php-5.6.22\php.exe，设置好了，刷新本页面。<br>';
		echo '<div style="background:#caeccb;padding:5px;border:1px #888888 solid;border-radius:5px;">';
		echo "return array(<br>'title'	=>'信呼OA',<br>'phppath' => 'F:\php\php-5.6.22\php.exe' <font color=#aaaaaa>//加上这个你php.exe的路径</font><br>)";
		echo '</div>';
		echo '2、在您的win服务器上，开始菜单→运行 输入 cmd 回车(管理员身份运行)，输入以下命令(每30分钟运行一次)：<br>';
		echo '<div style="background:#caeccb;padding:5px;border:1px #888888 solid;border-radius:5px;">';
		echo 'schtasks /create /sc DAILY /mo 1 /du "24:00" /ri 30 /sd "2018/03/01" /st "00:00:05"  /tn "信呼自动接收邮件" /ru System /tr '.$ljth.'\\'.UPDIR.'\cli\xinhuemailrun.bat';
		echo '</div>';
		
		
		$str1 = 'cd '.ROOT_PATH.''.chr(10).'php '.ROOT_PATH.'/task.php email';
		$spath= ''.UPDIR.'/cli/xinhuemailrun.sh';
		$this->rock->createtxt($spath, $str1);	
		echo '<br>二、<b>Linux服务器</b>，可用根据以下设置定时任务<br>';
		echo '根据以下命令设置运行：<br>';
		echo '<div style="background:#caeccb;padding:5px;border:1px #888888 solid;border-radius:5px;"><font color=blue>chmod</font> 777 '.ROOT_PATH.'/'.$spath.'<br>';
		echo '<font color=blue>crontab</font> -e<br>';
		echo '#信呼自动接收邮件每30分钟运行一次<br>';
		echo '*/30 * * * * '.ROOT_PATH.'/'.$spath.'</div>';
	}
}