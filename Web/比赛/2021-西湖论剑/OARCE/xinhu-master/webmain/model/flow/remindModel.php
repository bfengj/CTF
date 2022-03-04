<?php
//单据提醒设置
class flow_remindClassModel extends flowModel
{
	
	public function flowrsreplace($rs, $lx=0)
	{
		if($rs['status']==0)$rs['ishui']=1;
		if($lx==1){
			$barrs = $this->getstatusarr();
			$zts   = $barrs[$rs['status']];
			$rs['status'] = '<font color="'.$zts[1].'">'.$zts[0].'</font>';
		}
		return $rs;
	}
	
	public function getstatusarr()
	{
		$barr[1] = array('启用','green');
		$barr[0] = array('停用','#888888');
		return $barr;
	}


	//多个连表查询
	public function flowbillwhere($uid, $lx)
	{
		return array(
			'table' 		=> '`[Q]'.$this->mtable.'` a left join `[Q]flow_set` b on a.modenum=b.`num`',
			'fields'		=> 'a.id,a.ratecont,a.startdt,a.enddt,a.status,a.explain,a.recename,a.optname,a.optdt,b.name as modename',
			'orlikefields'	=> 'b.name,a.ratecont',
			'asqom'			=> 'a.'
		);
	}
	

	//获取进入需要提醒内容
	public function getreminddt($dt='',$modenum='')
	{
		if($dt=='')$dt = $this->rock->date;
		$dt		= substr($dt, 0, 10);
		$now 	= $this->rock->now;
		$rows 	= $this->getall("`status`=1 and `startdt`<='$now' and (`enddt` is null or `enddt`>='$now')");
		$dtobj	= c('date');
		$w 		= date('w', strtotime($dt));
		if($w==0)$w = 7;
		$nw 	= 'w'.$w.'';
		$nrows  = array();
		$timestr= '';
		foreach($rows as $k=>$rs){
			$ratea = explode(',', $rs['rate']);
			$rateb = explode(',', $rs['rateval']);
			$len   = count($ratea);
			for($i=0; $i<$len; $i++){
				$timea = $this->getssdt($dt, $nw, $ratea[$i], arrvalue($rateb, $i), $rs['uid'], $rs['startdt']);
				if($timea)foreach($timea as $time){
					if(!contain($timestr, '['.$time.']')){
						$rs['runtime']  = $time;
						$rs['runtimes'] = date('Y-m-d H:i:s',$time);
						$rs['rates'] 	= $ratea[$i]; //频率类型
						$nrows[] = $rs;
						$timestr.='['.$time.']';
					}
				}
			}
		}
		return $nrows;
	}
	
	//判断时间是否可使用
	private function getssdt($dt, $nw, $rate, $valstr, $uid, $startdts)
	{
		$timea  = array();
		$vala	= explode('|', $valstr);
		$val 	= $vala[0];
		$val2 	= arrvalue($vala, 1);
		
		//仅一次
		if($rate=='o' && contain($val, $dt)){
			$timea[] = strtotime($val);
		}
		
		//星期和天
		if($nw==$rate || $rate=='d'){
			$time = ''.$dt.' '.$val.'';
			$timea[] = strtotime($time);
		}
		//每小时
		if($rate=='h'){
			$ksis	= substr($startdts, 11);
			if(isempt($val2))$val2 = '23:59:59';
			$stime  = strtotime(''.$dt.' '.$ksis.'');
			$etime  = strtotime(''.$dt.' '.$val2.'');
			for($i=0;$i<=23;$i++){
				$time = strtotime(''.$dt.' '.$i.':'.$val.'');
				if($stime<=$time && $etime>=$time)$timea[] = $time;
			}
		}
		//每月
		if($rate=='m'){
			$time = ''.substr($dt,0, 8).''.$val.'';
			if(contain($time, $dt))$timea[] = strtotime($time);
		}
		//每年
		if($rate=='y'){
			$time = ''.substr($dt,0, 5).''.$val.'';
			if(contain($time, $dt))$timea[] = strtotime($time);
		}
		
		//工作日,休息日
		if($rate=='g' || $rate=='x'){
			$time = ''.$dt.' '.$val.'';
			$timea[] = strtotime($time);
		}
		
		return $timea;
	}
	
	//时间段读取
	public function getremindtodo($startdt='', $enddt='')
	{
		if($startdt=='')$startdt = $this->rock->now;
		$stime= strtotime($startdt)-10;
		if($enddt=='')$enddt	= date('Y-m-d H:i:s', $stime + 310); //默认是5分钟内提醒
		$dt	  = substr($startdt, 0, 10);
		$rows = $this->getreminddt($startdt);
		
		$etime= strtotime($enddt);
		$sarr = $modearr = array();
		$modenums	= '';
		$kqd		= m('kaoqin');
		foreach($rows as $k=>$rs){
			$rate 	= $rs['rates']; //频率类型
			$bo 	= true;
			if($rs['runtime']>=$stime && $rs['runtime']<=$etime){
				//工作日休息日判断
				if($rate=='g' || $rate=='x'){
					$isw = $kqd->isworkdt($rs['uid'], $dt);
					if($isw==1 && $rate=='x')$bo = false;
					if($isw==0 && $rate=='g')$bo = false;
				}
				if($bo){
					$modenums.=",'".$rs['modenum']."'";
					$sarr[] = $rs;
				}
			}
		}
		//$this->flowtodosettx(8);
		if($modenums=='')return false;
		$modenums	= substr($modenums, 1);
		$modrs 		= m('flow_set')->getall("`num` in ($modenums) and `status`=1");
		foreach($modrs as $k=>$rs)$modearr[$rs['num']] = $rs;
		
		
		$flowtodoid = ''; //单据通知设置ID
		$subscribid = array(); //订阅的
		
		foreach($sarr as $k=>$rs){
			$mid 	= $rs['mid'];
			
			if($rs['modenum']=='flowtodo'){
				$flowtodoid.=','.$mid.'';
				continue;
			}
			$mrs	= arrvalue($modearr, $rs['modenum']);
			if(!$mrs)continue;
			$cont 	= $rs['explain'];
			$GLOBALS['adminid'] = $rs['uid'];
			$receid 	= $rs['uid'];
			$recename 	= $rs['optname'];
			if(!isempt($rs['receid'])){
				$receid  	= 'u'.$receid.','.$rs['receid'].'';
				$recename  .= ','.$rs['recename'].'';
			}
			
			//订阅的
			if($rs['modenum']=='subscribe'){
				$subscribid[] = array(
					'id'	=> $mid,
					'uid'	=> $rs['uid'],
					'receid'=> $receid,
					'recename'=> $recename,
				);
				continue;
			}
			
			$this->pushs($receid, $cont, $mrs['name'], array(
				'id' 		=> $mid,
				'modenum' 	=> $rs['modenum'],
				'modename' 	=> $mrs['name'],
				'moders'	=> $mrs
			));
		}
		
		//单据通知提醒需要另外提醒
		if($flowtodoid !='')$this->flowtodosettx(substr($flowtodoid, 1));
		
		//订阅的处理(建议用异步的)
		if($subscribid){
			if(getconfig('asynsend')){
				
				$reim	= m('reim');
				foreach($subscribid as $subo){
					$GLOBALS['adminid'] = $subo['uid'];
					$reim->asynurl('asynrun','subscribe', array(
						'recename' 	=> $this->rock->jm->base64encode($subo['recename']),
						'receid' 	=> $subo['receid'],
						'id' 		=> $subo['id'],
						'uid'		=> $subo['uid']
					));
				}
			}else{
				//没有异步直接调用
				$subflow = m('flow')->initflow('subscribeinfo');
				foreach($subscribid as $subo){
					$GLOBALS['adminid'] = $subo['uid'];
					$subflow->subscribe($subo['id'],$subo['uid'],$subo['receid'],$subo['recename']);
				}
			}
		}

		return $sarr;
	}
	
	//单据通知设置的，必须有触发条件和选择计划任务
	private function flowtodosettx($tids)
	{
		$rows = $this->db->getall('select a.*,b.num as modenum from `[Q]flow_todo` a left join `[Q]flow_set` b on a.`setid`=b.`id` where a.`id` in('.$tids.') and b.`status`=1 and a.`status`=1 and a.`botask`=1 and a.whereid>0');
		//print_r($rows);
		//有设置了提醒
		foreach($rows as $rk=>$rs){
			
			$modenum 	= $rs['modenum'];
			$flow		= m('flow')->initflow($modenum);
			$flowrows 	= $flow->gettodorows($rs['whereid']);
			$zongcount	= count($flowrows);
		
			$tostr 		= '';//提醒的内容
			$todofields	= array();
			if(!isempt($rs['todofields']))$todofields = explode(',', $rs['todofields']);
			$title 		= $rs['name'];
			$sanda		= array();
			
			foreach($flowrows as $k1=>$rs1){
				if($k1>0)$tostr	.= "\n";
				$tostrs  = $this->rock->reparr($rs['summary'], $rs1);
				$tostr	.= $tostrs;
				
				$receid  = $rs['receid']; //接收人
				//提交人
				if($rs['toturn']=='1' && isset($rs1['optid']))$receid = $this->strappend($receid,$rs1['optid']);
				//参与人
				if($rs['tocourse']=='1'){
					$cyrenId	= $this->billmodel->getmou('allcheckid', "`table`='".$flow->mtable."' and `mid`='".$rs1['id']."'");
					$receid = $this->strappend($receid, $cyrenId);
				}
				foreach($todofields as $tfid){
					$tfss = arrvalue($rs1, $tfid);
					if(!isempt($tfss))$receid = $this->strappend($receid, $tfss);
				}
				
				if(!isempt($receid)){
					$receid = $this->adminmodel->gjoins($receid);
					if($zongcount<20){
						$flow->id = $rs1['id'];
						$flow->push($receid, '', $tostrs, $title);
					}else{
						$receida= explode(',', $receid);
						foreach($receida as $ruid){
							if(!isset($sanda[$ruid]))$sanda[$ruid] = array();
							if(!in_array($tostrs,$sanda[$ruid]))$sanda[$ruid][] = $tostrs;
						}
					}
				}
			}
			
			//相同内容转化
			if($sanda){
				$sendarr	= array();
				foreach($sanda as $uid=>$narr){
					$enstr = '';
					foreach($narr as $k=>$v){
						if($k>0)$enstr.='<br>';
						$enstr.=''.$v.'';
					}
					if($enstr!=''){
						$sendarr[md5($enstr)][] = array(
							'uid' => $uid,
							'cont'=> $enstr
						);
					}
				}
				
				//发送
				foreach($sendarr as $key=>$rowss){
					$uids = '';
					$cont = $rowss[0]['cont'];
					foreach($rowss as $k=>$rsc){
						$uids.=','.$rsc['uid'].'';
					}
					//发送
					if($uids!=''){
						$uids = substr($uids, 1);
						$flow->flowweixinarr = array(
							'url' => $flow->getweurl()
						);
						$flow->push($uids, '', $cont, $title);
					}
				}
			}

			
		}
	}
}