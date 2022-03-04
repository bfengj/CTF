<?php
//计划任务列表控制器
class taskClassAction extends Action
{
	
	public function getrunlistAjax()
	{
		$barr = m('task')->getlistrun($this->date);
		$this->returnjson($barr);
	}
	public function starttaskAjax()
	{
		$lx		= (int)$this->get('lx','0');
		$tobj 	= m('task');
		$tobj->cleartask();
		if($lx==0){
			$carr 	= $tobj->pdlocal();
			if(!$carr['success'])return $carr;
			$barr 	= $tobj->starttask();
			if($barr['success']){
				return returnsuccess('启动成功');
			}else{
				return returnsuccess('无法启动可能未开启服务端:'.$barr['msg'].'');
			}
		}else{
			if($lx==1){
				$barr = c('xinhuapi')->starttask();
				if($barr['success'])$barr['data'] = '已通过官网服务开启计划任务';
			}
			if($lx==2){
				$barr = c('xinhuapi')->stoptask();
				if($barr['success'])$barr['data'] = '已停止使用官网计划任务';
			}
			return $barr;
		}
	}
	
	public function clearztAjax()
	{
		m('task')->update('state=0,lastdt=null,lastcont=null','1=1');
	}
	
	
	public function downbatAjax()
	{
		$ljth = str_replace('/','\\',ROOT_PATH);
		echo '<title>计划任务开启方法</title>';
		
		echo '<font color="red">如您有安装信呼服务端，就不用根据下面来开启计划任务了</font><br><a target="_blank" style="color:blue" href="'.URLY.'view_taskrun.html">查看官网上帮助</a><br>';
		echo '计划任务的运行时间需要设置为5的倍数才可以运行到。<br>';
		
		
		
		echo '一、<b>Windows服务器</b>，可根据以下设置定时任务<br>';
		$str1 = '@echo off
cd '.$ljth.'	
'.getconfig('phppath','php').' '.$ljth.'\task.php runt,task';
		$this->rock->createtxt(''.UPDIR.'/cli/xinhutaskrun.bat', $str1);
		echo '1、打开系统配置文件webmainConfig.php加上一个配置phppath设置php环境的目录地址如：F:\php\php-5.6.22\php.exe，设置好了，刷新本页面。<br>';
		echo '<div style="background:#caeccb;padding:5px;border:1px #888888 solid;border-radius:5px;">';
		echo "return array(<br>'title'	=>'信呼OA',<br>'phppath' => 'F:\php\php-5.6.22\php.exe' <font color=#aaaaaa>//加上这个，路径如果有空格请加入环境变量，这个设置为php即可</font><br>)";
		echo '</div>';
		echo '2、在您的win服务器上，开始菜单→运行 输入 cmd 回车(管理员身份运行)，输入以下命令(每5分钟运行一次)：<br>';
		echo '<div style="background:#caeccb;padding:5px;border:1px #888888 solid;border-radius:5px;">';
		echo 'schtasks /create /sc DAILY /mo 1 /du "24:00" /ri 5 /sd "2017/04/01" /st "00:00:05"  /tn "信呼计划任务" /ru System /tr '.$ljth.'\\'.UPDIR.'\cli\xinhutaskrun.bat';
		echo '</div>';
		

		$str1 = 'cd '.ROOT_PATH.''.chr(10).'php '.ROOT_PATH.'/task.php runt,task';
		$spath= ''.UPDIR.'/cli/xinhutaskrun.sh';
		$this->rock->createtxt($spath, $str1);	
		echo '<br>二、<b>Linux服务器</b>，可用根据以下设置定时任务<br>';
		echo '根据以下命令设置运行：<br>';
		echo '<div style="background:#caeccb;padding:5px;border:1px #888888 solid;border-radius:5px;"><font color=blue>chmod</font> 777 '.ROOT_PATH.'/'.$spath.'<br>';
		echo '<font color=blue>crontab</font> -e<br>';
		echo '#信呼计划任务每5分钟运行一次<br>';
		echo '*/5 * * * * '.ROOT_PATH.'/'.$spath.'</div>';
		
		echo '<br><br>三、<b>浏览器窗口运行</b>，用于你的是虚拟主机没办法管理服务器时<br>';
		echo '打开<a href="?m=task&a=queue&d=system" style="color:blue">[计划任务队列]</a> 来启用计划任务。<br>';
	}
	
	public function queueAction()
	{
		$this->title = '计划任务队列';
		$tasklist 	 = m('task')->getrunlist('',1);
		$this->smartydata['tasklist'] = $tasklist;
	}
}