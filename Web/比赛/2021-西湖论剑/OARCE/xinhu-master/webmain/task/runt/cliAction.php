<?php
/**
*	这个固定     文件名,方法名 参数	
*	php task.php cli,run -key=a -key2=b
*/
class cliClassAction extends runtAction
{
	
	public function runAction()
	{
		echo 'Hello Xinhu Cli';
	}
	
	//测试队列用的
	public function testAction()
	{
		$rand = $this->getparams('rand');
		$this->option->setval('asyntest', $rand);
		return 'success';
	}
	
	//http://192.168.1.104/app/xinhu/task.php?m=cli&a=urltest
	public function urltestAction()
	{
		$id 	= $this->get('id');
		$id2 	= $this->get('id2');
		
		echo $id2;
		echo '------';
		echo $id;
	}
	
	/**
	*	(异步用)REIM即时通讯平台应用提醒
	*/
	public function reimplatsendAction()
	{
		$body = $this->getparams('body');
		if(!$body)return;
		$cans = json_decode($this->jm->base64decode($body),true);
		return m('reimplat:agent')->sendxiao($cans['touid'],$cans['agentname'],$cans['wxarr'],true);
	}
}