<?php
/**
*	信呼中node队列的处理
*/

class rockqueueChajian extends Chajian
{
	//队列服务器主机
	private $rockqueue_host = '127.0.0.1';
	
	//队列服务端口，数字类型，为0从服务器设置上读取
	private $rockqueue_port = 0;	
	
	
	//初始化配置读取
	protected function initChajian()
	{
		$this->rockqueue_host = getconfig('rockqueue_host', $this->rockqueue_host);
		$this->rockqueue_port = getconfig('rockqueue_port', $this->rockqueue_port);
		if($this->rockqueue_port==0){
			$reim 	 = m('reim');
			$reimhot = $reim->getpushhostport($reim->serverpushurl);
			$this->rockqueue_host = $reimhot['host'];
			$this->rockqueue_port = $reimhot['port'];
		}
		
		
		$this->cmdshell = array(
			array('soffice.exe','php.exe'), //win下必须包含
			array('libreoffice'),  //Linux下包含
			array('pdf:writer_pdf_Export') //命令里至少要有一个
		);
	}
	
	/**
	*	发送队列信息
	*	$cont 内容可以是http地址，也可以如:cli,run
	*	$param 参数
	*	使用 c('rockqueue')->push('cli,run');
	*/
	public function push($cont, $param=array(), $runtime=0, $id=0)
	{
		$type 	= 'cmd';
		$url	= $cont;
		$queuelogid = 0;
		if(!isset($param['nolog'])){
			$queuelogid= m('log')->addlogs('异步队列','', 3);
			$param['queuelogid'] = $queuelogid;
		}
		if(substr($cont,0,4)=='http'){
			$type='url';
		}else{
			if(!contain($url, ','))$url='cli,'.$url.'';
			$phppath = getconfig('phppath');
			if(!contain($this->rockqueue_host, '127.0.0.1') || isempt($phppath)){
				$urla= explode(',', $url);
				$url = URL.'task.php?m='.$urla[0].'|runt&a='.$urla[1].'';
				$type= 'url';
			}else{
				$st1 = '';
				$check = c('check');
				foreach($param as $k=>$v)$st1.=' -'.$k.'='.$v.'';
				if(contain($phppath,' ') || $check->isincn($phppath))
					return returnerror('配置文件phppath不能有空格，请加入环境变量设置并为php');
				if(contain(ROOT_PATH,' ') || $check->isincn(ROOT_PATH))
					return returnerror('OA系统目录“'.ROOT_PATH.'”有空格，无法使用');
				$url = ''.$phppath.' '.ROOT_PATH.'/task.php '.$url.''.$st1.'';
			}
		}
		if($type=='url'){
			$jg  = contain($url,'?')?'&':'?';
			$st1 = '';
			foreach($param as $k=>$v)$st1.='&'.$k.'='.$v.'';
			if($st1!='')$url.=''.$jg.''.substr($st1,1).'';
		}
		
		if($id==0)$id = rand(1,99999);
		$rarr[] = array(
			'qtype'		=> $type,
			'runtime'	=> $runtime,
			'url'		=> $url,
			'id'		=> $id
		);
		$barr = $this->pushdata($rarr);
		$barr['cmdurl'] = ''.$type.':'.$url;
		if($runtime==0)$runtime = time();
		if($queuelogid>0){
			m('log')->update(array(
				'url' => $url,
				'remark'=> '['.$type.']'.date('Y-m-d H:i:s', $runtime).'',
			),$queuelogid);
		}
		return $barr;
	}
	
	/**
	*	执行shell命令
	*/
	public function pushcmd($cmd)
	{
		if(contain(PHP_OS,'WIN')){
			$cmdshell = $this->cmdshell[0];
		}else{
			$cmdshell = $this->cmdshell[1];
		}
		$qianz = explode(' ', $cmd);
		$qianz = $qianz[0];
		//$boa   = false;
		//foreach($cmdshell as $sell)if(contain($qianz, $sell))$boa = true;
		//if(!$boa)return returnerror('非法操作');
		
		$boa   = false;
		foreach($this->cmdshell[2] as $sell)if(contain($cmd, $sell))$boa = true;
		if(!$boa)return returnerror('无效参数');
		
		$id = rand(1,99999);
		$rarr[] = array(
			'qtype'		=> 'cmd',
			'runtime'	=> '0',
			'url'		=> escapeshellcmd($cmd),
			'id'		=> $id
		);
		return $this->pushdata($rarr);
	}
	
	/**
	*	推送数据过去
	*/
	public function pushdata($rarr)
	{
		if(is_array($rarr))$rarr = json_encode($rarr);
		$url = 'http://'.$this->rockqueue_host.':'.$this->rockqueue_port.'/?atype=send&data='.urlencode($rarr).'';
		$reqult = c('curl')->setTimeout(3)->getcurl($url);
		if($reqult){
			return returnsuccess($reqult);
		}else{
			return returnerror('服务端配置不能用');
		}
		//return c('socket')->udppush($rarr, $this->rockqueue_host, $this->rockqueue_port);
	}
	
	/**
	*	推送类型
	*/
	public function pushtype($type, $url, $can=array())
	{
		$can['qtype'] = $type;
		$can['url']   = $url;
		$rarr[] = $can;
		return $this->pushdata($rarr);
	}
	
	/**
	*	发送腾讯云存储
	*	调用：c('rockqueue')->sendfile(文件Id);
	*/
	public function sendfile($fileid, $runtime=0)
	{
		return $this->push('qcloudCos,run', array('fileid'=>$fileid), $runtime);
	}
	
	public function senddown($fileid)
	{
		return $this->push('qcloudCos,down', array('fileid'=>$fileid));
	}
	
	/**
	*	在信呼文件管理平台上删除对应文件
	*	调用：c('rockqueue')->delfile(文件编号);
	*/
	public function delfile($fileid)
	{
		return $this->push('qcloudCos,del', array('fileid'=>$fileid));
	}
}