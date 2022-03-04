<?php
class taskClassModel extends Model
{
	/**
	*	读取计划任务运行列表
	*/
	public function getrunlist($dt='', $lx=0, $ntime=0)
	{
		if($dt=='')$dt = $this->rock->date;
		$fields = '`id`,`url`,`type`,`time`';
		if($lx==1)$fields.=',`name`';
		$rows 	= $this->getrows('`status`=1 order by `sort`', $fields);
		$runa	= array();
		$sdts	= strtotime($dt);
		$edts	= strtotime($dt.' 23:59:59');
		if($ntime==0)$ntime = time();
		$ntime	= $ntime-20;//稍微减一下防止出现跳过的
		$brows	= array();
		$dtobj 	= c('date');
		$w 		= (int)date('w', $sdts);if($w==0)$w=7;//星期7
		foreach($rows as $k=>$rs){
			$ate = explode(',', $rs['type']);
			$ati = explode(',', $rs['time']);
			if(count($ate)!=count($ati))continue;
			$len = count($ate);
			$rs['adminid'] 	= 1;
			$rs['atype'] 	= 'runurl';
			$rs['urllu'] 	= $rs['url'];
			$rs['url'] 		= $this->showgeurl($rs['url'],$rs['id'], $lx);
			for($i=0;$i<$len;$i++){
				$rs['type'] = $ate[$i];
				$rs['time'] = $ati[$i];
				$brows[] = $rs;
			}
		}
		
		foreach($brows as $k=>$rs){
			$type 	= $rs['type'];
			$atime  = $rs['time'];
			
			$jg		= (int)str_replace(array('d','i','h','m','w'),array('','','','',''), $type);
			if($jg==0)$jg=1;
			$type 	= str_replace($jg,'', $type);
			$jgs 	= $jg; if($jg<10)$jgs = '0'.$jg.'';
			$time 	= '';
			//每天
			if($type=='d'){
				$time = $dt.' '.$rs['time'];
				$rs['runtimes'] 	= $time;
				$rs['runtime'] = strtotime($time);
				$runa[] = $rs;
			}
			//分钟
			if($type=='i'){
				$ges = $jg*60;
				for($i=$sdts;$i<=$edts;$i=$i+$ges){
					$rs['runtimes'] = date('Y-m-d H:i:s', $i);
					$rs['runtime']  = $i;
					$runa[] = $rs;
				}
			}
			//小时
			if($type=='h'){
				for($i=0;$i<=23;$i=$i+$jg){
					$time 			= date('Y-m-d H:'.$atime.'', $sdts+$i*3600);
					$rs['runtimes'] = $time;
					$rs['runtime'] 	= strtotime($time);
					$runa[] = $rs;
				}
			}
			//每月
			if($type=='m'){
				$time 			= date('Y-m-'.$atime.'');
				$rs['runtimes'] = $time;
				$rs['runtime'] 	= strtotime($time);
				$runa[] = $rs;
			}
			//周
			if($type=='w' && $jg==$w){
				$time 			= date('Y-m-d '.$atime.'');
				$rs['runtimes'] = $time;
				$rs['runtime'] 	= strtotime($time);
				$runa[] = $rs;
			}
			//每年
			if($type=='y'){
				$time 			= date('Y-'.$atime.'');
				$rs['runtimes'] = $time;
				$rs['runtime'] 	= strtotime($time);
				$runa[] = $rs;
			}
		}
		$brun	= array();
		foreach($runa as $k=>$rs){
			$_runti = $rs['runtime'];
			if($_runti >= $ntime && $_runti<=$edts)$brun[]=$rs;
		}
		$brun 	= c('array')->order($brun, 'runtime','asc');
		return $brun;
	}
	
	//$lx=2必须使用本地地址
	private function gettaskurl($lx=0)
	{
		$turl	= getconfig('taskurl');
		if($turl=='' || $lx==2)$turl	= getconfig('localurl', URL);
		return $turl;
	}
	
	//判断设置本地地址是否可以使用
	public function pdlocal($urla='')
	{
		if($urla=='')$urla= $this->gettaskurl();
		$url = $urla.'task.php?m=day|runt&a=getitle';
		if($urla != URL){
			if(c('curl')->getcurl($url) != TITLE)return returnerror('设置的本地地址“'.$urla.'”不能使用，请到[系统→系统工具→系统设置]下重新设置');
		}
		return returnsuccess();
	}
	
	//获取运行url,$lx=2必须使用本地地址
	private function showgeurl($url, $id, $lx=0)
	{
		if(contain($url, 'http://') || contain($url, 'https://')){
			
		}else{
			$aurl 	= explode(',', $url);
			$turl	= $this->gettaskurl($lx);
			$yurl 	= 'task.php?m='.$aurl[0].'|runt&a='.arrvalue($aurl, 1, 'run').'&runid='.$id.'';
			$url 	= ''.$turl.''.$yurl.'';
		}
		return $url;
	}
	
	//基本运行 弃用
	public function baserun($mid=0,$gtimes='')
	{
		$where  = '';
		if($mid>0)$where='and id='.$mid.'';
		$rows 	= $this->getrows('`status`=1 '.$where.' order by `sort`','`id`,`url`,`type`,`time`,`lastdt`');
		$dt 	= $this->rock->date;
		if($gtimes=='')$gtimes = date('H:i:s');
		$time  	= strtotime($dt.' '.$gtimes);
		$jgs	= 5;
		foreach($rows as $k=>$rs){
			$ybbo 	= false;
			$stime	= $this->getruntime($dt, $rs['type'], $rs['time'], $rs['lastdt']);
			if($stime > 0){
				if($time>=$stime-$jgs && $time<=$stime+$jgs){
					$ybbo = true;
				}
			}
			if($ybbo || $mid>0){
				$state 	= '2';
				$url 	= $this->showgeurl($rs['url'], $rs['id']);
				$cont 	= c('curl')->getcurl($url);
				if($cont=='success')$state='1';
				$this->update(array(
					'state' => $state,
					'lastdt'=> $this->rock->now,
					'lastcont' => $cont
				), $rs['id']);
			}
		}
	}
	
	private function getruntimes($dt, $type, $time, $lastdt)
	{
		$stime	= 0;$sj = '';
		if($type=='d'){
			$sj = $dt.' '.$time;
		}
		if($type=='h'){
			
		}
		if($type=='i'){
			
		}
		if($sj!=''){
			$stime = strtotime($sj);
		}
		return $stime;
	}
	
	private function getruntime($dt, $type, $time, $lastdt)
	{
		$stime	= 0;
		if(!$this->isempt($type) && !$this->isempt($time)){
			$atype = explode(',', $type);
			$atime = explode(',', $time);
			$len1  = count($atype);
			$len2  = count($atime);
			if($len1 == $len2){
				for($i=0; $i<$len1; $i++){
					$stime = $this->getruntimes($dt, $atype[$i], $atime[$i], $lastdt);
					if($stime>0)break;
				}
			}
		}
		return $stime;
	}
	
	private function tasklistpath($lx=0)
	{
		$str = ''.UPDIR.'/logs/tasklist.json';
		if($lx==1)return $str;
		return ''.ROOT_PATH.'/'.$str.'';
	}
	
	/**
	*	清空
	*/
	public function cleartask()
	{
		@unlink($this->tasklistpath());
	}
	
	//读取下一个5分钟时间
	private function getnextfz()
	{
		$time 	= time();
		$ni 	= date('i', $time);
		$tar 	= array(0,5,10,15,20,25,30,35,40,45,50,55,60);
		$gi 	= 0;
		for($i=0;$i<count($tar)-1;$i++){
			$i1 = $tar[$i];
			$i2 = $tar[$i+1];
			if($ni>=$i1 && $ni<$i2){
				$gi = $i2;
				break;
			}
		}
		if($gi==60){
			$date = date('Y-m-d H:00:00', $time+600);
		}else{
			$date = date('Y-m-d H:'.$gi.':00', $time);
		}
		return strtotime($date);
	}
	
	//开启发送运行任务
	public function sendstarttask()
	{
		$turl	= $this->gettaskurl();
		$option = m('option');
		$this->reimtype	= $option->getval('reimservertype');
		//node版本
		if($this->reimtype=='1'){
			$url 	= ''.$turl.'task.php?m=runt&a=task';
			$runtime= $this->getnextfz();
			$reim   = m('reim');
			if(!isempt(getconfig('phppath')) && contain($reim->serverpushurl, '127.0.0.1')){
				$url= 'runt,task';
			}
			$recID  = $option->getval('reimrecidsystem','rockxinhu');
			$keynum = 'service_'.$recID.'';
			$len 	= (int)$option->getval($keynum,'0');
			if($len<=0){
				$len 	= strlen($url)+rand(1000,9999);
				$option->setval($keynum, $len);
			}
			$barr	= c('rockqueue')->push($url, array('rtype'=>'queue','nolog'=>'1','runtime'=>$runtime), $runtime, $len);
			if(!COMPANYNUM && getconfig('platdwnum')){
				$creaar = m('company')->getall('`iscreate`=1');
				foreach($creaar as $k=>$rs)$barr = c('rockqueue')->push($url, array('dwnum'=>$rs['num'],'nolog'=>'1','runtime'=>$runtime), $runtime, $len+1+$k);
			}
		}else{
			$url 	= ''.$turl.'task.php?m=runt&a=getlist';
			$barr 	= m('reim')->pushserver('starttask', array(
				'url' => $url
			));
		}
		return $barr;
	}
	
	/**
	*	开启计划任务(自己服务端)
	*/
	public function starttask()
	{
		$barr = $this->sendstarttask();
		if($this->reimtype=='1'){
			$recID = m('option')->getval('reimrecidsystem','rockxinhu');
			c('rockqueue')->pushtype('starturl',''.$this->gettaskurl().'task.php?m=runt&a=taskget', array(
				'recid' => $recID
			));
		}
		$this->cleartask();
		return $barr;
	}
	
	//创建json数组
	public function createjson($time)
	{
		$barr 	= $this->getrunlist($this->rock->date, 2, $time);
		$this->rock->createtxt($this->tasklistpath(1), json_encode($barr));
		return $barr;
	}
	
	/**
	*	cli 运行每5分钟运行的，运行curl的
	*/
	public function runjsonlist($time)
	{
		$barr	= array();
		$fstr	= '';
		$fpath	= $this->tasklistpath();
		$dt 	= date('Y-m-d', $time);
		if(file_exists($fpath)){
			$lastdt = date('Y-m-d H:i:s',filemtime($fpath));//最后修改的时间
			$editdt = date('Y-m-d H:i:s',filectime($fpath));//上次修改时间
			if(contain($lastdt, $dt) && contain($editdt, $dt))$fstr	= @file_get_contents($fpath);
		}
		if(isempt($fstr)){
			$barr = $this->createjson($time);
			m('option')->setval('systaskrun', $this->rock->now);//记录运行时间
		}else{
			$barr = json_decode($fstr, true);
		}
		$ntime 	= strtotime(date('Y-m-d H:i:00', $time));
		$yunarr = array();
		foreach($barr as $k=>$rs){
			if($rs['runtime']==$ntime){
				$yunarr[] = $rs;
			}
		}
		return $yunarr;
	}
	
	//获取运行列表
	public function getlistrun($dt)
	{
		$brun = $this->getrunlist($dt);
		$this->sbarr = array();
		foreach($brun as $k=>$rs){
			$key = $this->bsdeitmes($rs['runtime'],0);
			unset($rs['id']);
			unset($rs['time']);
			unset($rs['type']);
			unset($rs['runtimes']);
			unset($rs['runtime']);
			$rs['from'] = '[recid]';
			$this->sbarr[$key] = $rs;
		}
		$barr = array();
		foreach($this->sbarr as $key=>$rs){
			$barr[$key] = json_encode($rs);
		}
		return $barr;
	}
	private function bsdeitmes($time, $oi)
	{
		$xu = $time+$oi;
		$key= '[recid]'.$xu.'';
		if(isset($this->sbarr[$key])){
			$key = $this->bsdeitmes($time, $oi+1);
		}
		return $key;
	}
}