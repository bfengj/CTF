<?php
/**
*	计划任务用的程序
*/
class runtAction extends ActionNot
{
	public $runid = 0;
	public $queuelogid = 0;
	public $runrs = array();
	public $splitlast = 0; //距离上次提醒秒数0上次没有运行
	
	public $todoarr		= array();
	
	public function initAction()
	{
		$this->display 	= false;
		ob_start(); //打开缓冲区
		$this->runid		= (int)$this->getparams('runid','0');
		$this->queuelogid	= (int)$this->getparams('queuelogid','0');
		$this->initTask($this->runid);
	}
	
	public function initTask($runid)
	{
		if($runid==0)return;
		$this->runid 	= $runid;
		$this->runrs	= m('task')->getone($this->runid);
		if($this->runrs && !isempt($this->runrs['lastdt'])){
			$this->splitlast = time() - strtotime($this->runrs['lastdt']);
		}
	}
	
	public function taskAfter()
	{
		//提醒的
		$todoid = arrvalue($this->runrs,'todoid');
		if(!isempt($todoid) && $this->todoarr){
			$modenum	= arrvalue($this->todoarr, 'modenum');
			$agentname	= arrvalue($this->todoarr, 'agentname');
			$title		= arrvalue($this->todoarr, 'title');
			$cont		= arrvalue($this->todoarr, 'cont');
			if(!isempt($modenum)){
				$flow 	= m('flow')->initflow($modenum);
				$flow->push($todoid, $agentname, $cont, $title);
			}else{
				m('todo')->add($todoid, $title, $cont);
			}
		}
	}
	
	
	/**
	*	运行完成后判断运行状态
	*/
	public function afterAction()
	{
		if($this->runid > 0){
			$state	= 2;
			$cont  	= ob_get_contents();	
			if($cont=='success')$state=1;
			m('task')->update(array(
				'lastdt'	=> $this->rock->now,
				'lastcont' 	=> $cont,
				'state' 	=> $state
			), $this->runid);
			$this->taskAfter();
		}
		if($this->queuelogid > 0){
			$cont  	= ob_get_contents();
			m('log')->update(array('result' => $cont), $this->queuelogid);
		}
	}
	
	/**
	*	获取cli上参数格式：-key=val
	*/
	public function getparams($key, $dev='')
	{
		if(PHP_SAPI != 'cli'){
			return $this->get($key, $dev);
		}
		$arr = arrvalue($GLOBALS, 'argv');
		$sss = '';
		if($arr)for($i=2;$i<count($arr);$i++){
			$str = $arr[$i];
			if(!isempt($str)){
				$stra = explode('=', $str);
				if($stra[0]=='-'.$key.''){
					$sss  = arrvalue($stra, 1);
					break;
				}
			}
		}
		if(isempt($sss))$sss = $dev;
		return $sss;
	}
}
class runtClassAction extends runtAction
{
	public function runAction()
	{
		$mid	= (int)$this->get('mid','0');
		m('task')->baserun($mid);
		echo 'success';
	}
	public function getlistAction()
	{
		$dt 	= $this->get('dt', $this->date);
		$barr 	= m('task')->getlistrun($dt);
		$this->option->setval('systaskrun', $this->now);
		$this->returnjson($barr);
	}
	
	/**
	*	运行定时任务用于cli模式的，建每5分钟运行一次
	*	Linux 使用crontab php task.php runt,task
	*	win 使用计划任务 php task.php runt,task
	*	也可以每5分钟访问地址：http://127.0.0.1/app/xinhu/task.php?m=runt&a=task
	*/
	public function taskAction()
	{
		$runtime = $this->getparams('runtime',time());
		$rtype	 = $this->getparams('rtype'); //运行类型
		$dbs 	 = m('task');
		if($rtype=='queue')$dbs->sendstarttask();
		$yunarr	 = $dbs->runjsonlist($runtime);
		$oi 	 = $cg = $sb = 0;
		foreach($yunarr as $k=>$rs){
			$urllu 	= $rs['urllu'];
			$taskid = (int)$rs['id'];
			$state	= 2;
			$cont  	= '';
			$oi++;
			if(substr($urllu,0,4)=='http'){
				$cont = c('curl')->getcurl($urllu);
			}else{
				$urla = explode(',', $urllu);
				$path = ''.ROOT_PATH.'/'.P.'/task/runt/'.$urla[0].'Action.php';
				if(file_exists($path)){
					$act  = arrvalue($urla, 1,'run').'Action';
					include_once($path);
					$class= ''.$urla[0].'ClassAction';
					$obj  = new $class();
					$obj->initTask($taskid);
					$cont = $obj->$act();
					$obj->taskAfter();
				}else{
					$cont = ''.$urla[0].'Action.php not found';
				}
			}
			if(contain($cont,'success')){
				$state = 1;
				$cg++;
			}else{
				$sb++;
			}
			$dbs->update(array(
				'lastdt'	=> $this->rock->now,
				'lastcont' 	=> $cont,
				'state' 	=> $state
			),  $taskid);
			
		}
		return 'runtask('.$oi.'),success('.$cg.'),fail('.$sb.')';
	}
	
	//新服务端加载计划任务
	public function taskgetAction()
	{
		m('task')->sendstarttask();
		return 'taskget.'.time().'';
	}
	
	/**
	*	初始化计划任务linux
	*	php task.php runt,taskinit
	*/
	public function taskinitAction()
	{
		$str1 = 'cd '.ROOT_PATH.''.chr(10).'php '.ROOT_PATH.'/task.php runt,task';
		$spath= ''.UPDIR.'/cli/xinhutaskrun.sh';
		file_put_contents($spath, $str1);
		if(function_exists('exec'))exec('chmod 777 '.$spath.'');
		echo 'xinhu taskinit success';
	}
}