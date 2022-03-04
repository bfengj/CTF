<?php
/**
*	pThread 多线程插件，php.ini需要开启php_pthreads.dll扩展
*	来自：信呼开发团队
*	使用方法 c('thread')->startrun('http://127.0.0.1/');
*/
class threadChajian extends Thread{
	
	private $urlstr 	= '';
	private $result 	= '';
	
	/**
	*	执行的地址
	*	isback 是否等待返回内容
	*/
	public function startrun($url, $isback=false){
		$this->urlstr 	= $url;
		$this->start();
		if($isback)while($this->isRunning())usleep(10);
		return $this->result;
	}
	
	public function run(){
		@$this->result = c('curl')->getcurl($this->urlstr);
	}
	
	public function getresult()
	{
		return $this->result;
	}
}
if(!class_exists('Thread')){
	abstract class Thread {
		public function run(){}
		public function start(){
			$this->run();
		}
		public function join(){return true;}
		public function isRunning(){return false;}
	}
}