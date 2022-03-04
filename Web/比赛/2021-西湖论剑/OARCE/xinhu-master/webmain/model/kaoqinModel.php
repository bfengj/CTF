<?php
/**
*	考勤的model
*/
class kaoqinClassModel extends Model
{
	private $userarr 	= array();
	private $_pipeiarr 	= array();
	
	public function initModel()
	{
		$this->settable('kqdist');
		$this->admindb 	= m('admin');
		$this->sjoindb 	= m('sjoin');
		$this->dtobj 	= c('date');
	}
	
	public function adddkjl($uid,$type=0, $dkdt='',$ip='',$mac='')
	{
		$now = $this->rock->now;
		if($dkdt=='')$dkdt = $now;
		if($ip=='')$ip=$this->rock->ip;
		$xh  	= c('xinhu');
		$kqa 	= $this->admindb->getusinfo($uid,'dkip,dkmac');
		$mac	= strtolower($mac);
		if(isempt($kqa['dkip']) && isempt($kqa['dkmac']))return '未设置打卡IP或MAC地址'.$xh->helpstr('kaoqin').'';
		if(!isempt($kqa['dkip']) && $kqa['dkip']!='*'){
			$ass 	= explode(',', $kqa['dkip']);
			if(!in_array($ip, $ass))return '打卡电脑IP必须是：'.$kqa['dkip'].'，当前IP是：'.$ip.''.$xh->helpstr('kaoqin').'';
		}
		if(!isempt($kqa['dkmac'])){
			if($mac=='')return '无法获取MAC地址,请使用REIM客户端，'.$xh->helpstr('client','下载').'';
			$dkmac 	= str_replace('-',':',strtolower($kqa['dkmac']));
			$ass 	= explode(',', $dkmac);
			if(!in_array($mac, $ass))return '打卡电脑MAC地址必须是：'.$kqa['dkmac'].'，当前MAC地址是：'.$mac.'';
		}
		$this->db->record('[Q]kqdkjl',array(
			'dkdt' 	=> $dkdt,
			'uid'	=> $uid,
			'optdt'	=> $now,
			'ip'	=> $ip,
			'mac'	=> $mac,
			'comid'	=> $this->admindb->getcompanyid($uid),
			'type'	=> $type
		));
		$dt = substr($dkdt, 0, 10);
		$this->kqanay($uid, $dt);
		return '';
	}
	
	/**
	*	读取考勤时间
	*	$lx0 考勤时段,$lx1上班时间
	*/
	public function getkqsj($uid, $dt, $lx=0)
	{
		$key 	= $this->rock->getfunkey(func_get_args(),'kqdist');
		$rows 	= arrvalue($this->_pipeiarr, $key);
		if($rows)return $rows;
		$mid 	= $this->getdistid($uid, $dt, 0);
		$rows 	= $this->getkqsjs($mid);
		if($lx==1){
			$this->_pipeiarr[$key] = $rows;
			return $rows;
		}
		foreach($rows as $k=>$rs){
			$rows[$k]['children'] = $this->getkqsjs($rs['id']);
		}
		$this->_pipeiarr[$key] = $rows;
		return $rows;
	}
	private $getkqsjsarr = array();
	private function getkqsjs($id)
	{
		if(!isset($this->getkqsjsarr[$id])){
			$rows 	= $this->db->getrows('[Q]kqsjgz','pid='.$id.'','id,name,stime,etime,qtype,sort,iskt,iskq,isxx','`sort`');
			$this->getkqsjsarr[$id] = $rows;
		}
		return $this->getkqsjsarr[$id];
	}
	
	/**
	*	读取某人一天考勤类型状态
	*/
	public function getkqztarr($uid, $dt)
	{
		$kqzt 	= $this->getkqsj($uid, $dt);
		$kqsr 	= array(); $xu 	= 0;
		foreach($kqzt as $k=>$rs){
			foreach($rs['children'] as $rs1){
				if(!isset($kqsr[$rs1['name']])){
					$kqsr[$rs1['name']]='state'.$xu.'';
					$xu++;
				}
			}
		}
		return $kqsr;
	}
	
	/**
	*	获取匹配Id
	*	$type 0考勤时间,1休息,2定位
	*/
	private $getdistrows = array();
	public function getdistid($uid, $dt, $type=0)
	{
		//根据排班来
		if($type==0){
			$pbarr	= $this->getpbarr($dt);
			if($pbarr){
				$key0 	= 'a'.$dt.'_'.$uid.'_2_0';//休息key
				if(isset($pbarr[$key0]))return $pbarr[$key0];
				//根据组来判断考勤时间规则
				$gids 	= $this->sjoindb->getgroupid($uid); //组Id
				if($gids!='0'){
					$gidar = explode(',', $gids);
					for($i=1; $i<count($gidar); $i++){
						$gid 	= $gidar[$i];
						$key0 	= 'a'.$dt.'_'.$gid.'_1_0';
						if(isset($pbarr[$key0]))return $pbarr[$key0];
					}
				}
			}
		}
		
		$uarr[] 	= 'u'.$uid.'';
		$urs 		= $this->getusarr($uid);
		$deptpath 	= arrvalue($urs, 'deptpath');
		if(!isempt($deptpath)){
			$depa = explode(',', str_replace(array('[',']'), array('',''), $deptpath));
			foreach($depa as $depas){
				$uarr[] = 'd'.$depas.'';
			}
		}
		$groupname  = arrvalue($urs, 'groupname');
		if(!isempt($groupname)){
			$depa = explode(',', $groupname);
			foreach($depa as $depas){
				$uarr[] = 'g'.$depas.'';
			}
		}
		
		if(!isset($this->getdistrows[$type])){
			$barr = $this->getall("`status`=1 and `type`=".$type." ",'*', '`sort`');
			foreach($barr as $k=>$rs)if(isempt($rs['enddt']))$barr[$k]['enddt'] = $rs['startdt'];
			$this->getdistrows[$type] = $barr;
		}else{
			$barr = $this->getdistrows[$type];
		}
		$rows 		= array();
		if($barr)foreach($barr as $k=>$rs){
			$receid = $rs['receid'];
			if($dt<$rs['startdt'] || $dt>$rs['enddt'])continue;
			if(isempt($receid) || $receid=='all'){
				$rows[] = $rs;
				continue;
			}
			$receida = explode(',', $receid);
			$bo 	 = false;
			foreach($uarr as $uarrs){
				if(in_array($uarrs, $receida))$bo=true;
			}
			if($bo)$rows[] = $rs;
		}
		$mid 	= $this->getpipeimid($uid, $rows);
		if($type==0){
			
		}
		return $mid;
	}
	
	//从排班表kqdisv读取记录
	private $getpbarrs	= array();
	private function getpbarr($dt)
	{
		$month= substr($dt, 0, 7);
		if(!isset($this->getpbarrs[$month])){
			$gset = $this->db->getall("select * from `[Q]kqdisv` where `dt` like '".$month."%' order by `type`");
			$setar= array();
			foreach($gset as $k=>$rs){
				$key = 'a'.$rs['dt'].'_'.$rs['receid'].'_'.$rs['plx'].'_'.$rs['type'].'';
				$setar[$key] = $rs['mid'];
			}
			$this->getpbarrs[$month] = $setar;
			return $setar;
		}else{
			return $this->getpbarrs[$month];
		}
	}
	
	/**
	*	是不是工作日
	*/
	private $isworkdtarr = array();
	public function isworkdt($uid, $dt)
	{
		//先从排班那读取
		$pbarr	= $this->getpbarr($dt);
		if($pbarr){
			$key1 	= 'a'.$dt.'_'.$uid.'_2_1';//休息key
			$key2 	= 'a'.$dt.'_'.$uid.'_2_2';//工作日key
			
			if(isset($pbarr[$key2]))return 1;
			if(isset($pbarr[$key1]))return 0;
			
			//根据组来判断排班
			$gids 	= $this->sjoindb->getgroupid($uid); //组Id
			if($gids!='0'){
				$gidar = explode(',', $gids);
				for($i=1; $i<count($gidar); $i++){
					$gid 	= $gidar[$i];
					$key2 	= 'a'.$dt.'_'.$gid.'_1_2';
					if(isset($pbarr[$key2]))return 1;
				}
				for($i=1; $i<count($gidar); $i++){
					$gid 	= $gidar[$i];
					$key1 	= 'a'.$dt.'_'.$gid.'_1_1';
					if(isset($pbarr[$key1]))return 0;
				}
			}
		}
		
		
		$month 	= substr($dt, 0, 7);
		$key 	= 'a'.$month.'';
		$barr 	= array();
		if(!isset($this->isworkdtarr[$key])){
			$rows = $this->db->getrows('[Q]kqxxsj',"`dt` like '$month%'");
			foreach($rows as $k=>$rs){
				$barr['a'.$rs['pid'].''.$rs['dt'].'_'.$rs['uid'].''] = $rs['type'];
			}
			$this->isworkdtarr[$key] = $barr;
		}else{
			$barr = $this->isworkdtarr[$key];
		}
		$isw	= 1;
		$mid 	= $this->getdistid($uid, $dt, 1);
		$type 	= arrvalue($barr, 'a'.$mid.''.$dt.'_0');
		if($type=='0')$isw = 0;
		$types 	= arrvalue($barr, 'a'.$mid.''.$dt.'_'.$uid.'');
		if(!isempt($types))$isw = (int)$types;
		//$tos 	= $this->db->rows('[Q]kqxxsj',"`pid`=$mid and `dt`='$dt'");
		//$isw 	= ( $tos>0 ) ? 0 : 1;
		return $isw;
	}
	
	//返回人员
	public function getusarr($uid)
	{
		if(is_array($uid)){
			return $uid;
		}else{
			if(!isset($this->userarr[$uid])){
				$_urs = $this->db->getone('[Q]admin', "`id`='$uid'", '`id`,`deptid`,`deptpath`,`groupname`');
				$this->userarr[$uid] = $_urs;
				return $_urs;
			}else{
				return $this->userarr[$uid];
			}
		}
	}
	
	/**
	*	读取人员定位定位打卡位置
	*/
	public function dwdkrs($uid, $dt)
	{
		$mid 	= $this->getdistid($uid, $dt, 2);
		return m('kqdw')->getone($mid);
	}
	
	/**
	*	数组匹配人员对应哪个记录
	*/
	public function getpipeimid($uid=0, $garrs, $esfi='mid', $momid=1, $dt='')
	{
		$mid 	= $momid;
		if($uid==0)return $mid;
		$mid 	= 0;
		
		$uarr 		= $this->getusarr($uid);
		$deptpath 	= arrvalue($uarr, 'deptpath');
		$uid		= arrvalue($uarr, 'id','0');

		$utid  	= $dtid  =  array();$allars=false;$dttime = 0;
		if($dt!='')$dttime	= strtotime($dt);
		foreach($garrs as $k=>$rs){
			$artid = explode(',', $rs['receid']);
			if($dttime>0){
				if(!isset($rs['starttime'])){
					$rs['starttime'] 	= strtotime($rs['startdt']);
					$rs['endtime'] 		= strtotime($rs['enddt']);
				}
				if($rs['starttime'] > $dttime || $rs['endtime'] < $dttime)continue;
			}	
			foreach($artid as $ssid){
				if($ssid=='')continue;
				if($ssid=='all'){
					$allars = $rs;
					continue;
				}
				$fs  = substr($ssid, 0, 1);
				$sid = str_replace(array('u','d','g'), array('','',''), $ssid);
				if($fs=='g'){
					$guar = $this->admindb->getgrouptouid($sid);
					if($guar!=''){
						$guara = explode(',', $guar);
						foreach($guara as $guid){
							$utid[$guid][]= $rs;
						}
					}
					continue;
				}
				if($fs=='d'){
					$dtid[$sid][]= $rs;
				}else{
					$utid[$sid][]= $rs;
				}					
			}
		}
		if(isset($utid[$uid]))$mid = (int)$utid[$uid][0][$esfi];
		if($mid == 0 && !$this->isempt($deptpath)){
			$depa = explode(',', str_replace(array('[',']'), array('',''), $deptpath));
			foreach($depa as $depas){
				if(isset($dtid[$depas]))$mid = (int)$dtid[$depas][0][$esfi];
			}
		}
		if($mid == 0 && is_array($allars))$mid = (int)$allars[$esfi];
		if($mid==0)$mid=$momid;
		return $mid;
	}
	
	//返回条件
	private $limitfenqxi = 20;//一次分析20人
	private $qingjiaarr	 = array();//读取对应人请假的数字[$uid][$month] = [{}];
	public function kqanayallfirst($month,$glx=0)
	{
		$month	= substr($month, 0, 7);
		$start	= ''.$month.'-01';
		$enddt 	= $this->dtobj->getenddt($month);
		$s 		= "and (`quitdt` is null or `quitdt`>='$start') and (`workdate` is null or `workdate`<='$enddt')";
		
		if($glx==0)return $s;
		
		$count 	= $this->admindb->rows('1=1 '.$s.'');
		$limit	= $this->limitfenqxi;
		$maxpage= ceil($count/$limit);
		
		return array(
			'count' => $count,
			'limit' => $limit,
			'maxpage' => $maxpage,
		);
	}
	
	public function kqanayall($month, $where='', $page=0)
	{
		if(isempt($month))return;
		$month	= substr($month, 0, 7);
		$s 		= $this->kqanayallfirst($month).' '.$where.'';
		$max 	= $this->dtobj->getmaxdt($month);
		$sql 	= "select `id`,`workdate`,`quitdt` from `[Q]admin` where 1=1 ".$s."";
		$limit	= $this->limitfenqxi;
		if($page>0){
			$sql.=" limit ".($page-1)*$limit.",".$limit."";
		}
		$urows 	= $this->db->getall($sql);
		$oi 	= 0;
		foreach($urows as $k=>$urs){
			$this->kqanaymonth($urs['id'], $month, $urs, $max);
			$oi++;
		}
		return $oi;
	}
	
	public function kqanayalldt($dt)
	{
		$s 		= "and (`quitdt` is null or `quitdt`>='$dt') and (`workdate` is null or `workdate`<='$dt')";
		$urows 	= $this->admindb->getall('1=1 '.$s.'', '`id`,`workdate`,`quitdt`');
		foreach($urows as $k=>$urs){
			$this->kqanay($urs['id'], $dt);
		}
	}
	
	public function kqanaymonth($uid, $month, $urs=false, $max=0)
	{
		$month	= substr($month, 0, 7);
		if(!$urs)$urs 	= $this->admindb->getone($uid, '`workdate`,`quitdt`,`id`');
		if($max==0)$max = $this->dtobj->getmaxdt($month);
		$this->getqingjiaqj($uid, $month);
		for($i=1; $i<=$max; $i++){
			$oi = $i;if($oi<10)$oi='0'.$i.'';
			$dt = ''.$month.'-'.$oi.'';
			if(!isempt($urs['workdate']) && $urs['workdate']>$dt)continue;
			if(!isempt($urs['quitdt']) && $urs['quitdt']<$dt)continue;
			$this->kqanay($uid, $dt);
		}
		$this->delquitwork($urs, $month, $max);
	}
	
	//读取请假和外出记录
	private function getqingjiaqj($uid, $month)
	{
		$month	= substr($month, 0, 7);
		$rows 	= $this->db->getall("select `stime`,`etime`,`qjkind` from `[Q]kqinfo` where `uid`='$uid' and `status`=1 and `isturn`=1 and `kind`='请假' and `stime` like '$month%' order by `stime`");
		$this->qingjiaarr[$uid][$month]['qingjia'] = $rows;
		
		$rows 	= $this->db->getall("select `outtime` as `stime`,`intime` as `etime`,`atype` from `[Q]kqout` where `uid`='$uid' and `status`=1 and `isturn`=1 and `outtime` like '$month%' order by `outtime`");
		$this->qingjiaarr[$uid][$month]['waichu'] = $rows;
	}
	//读取当天请假和外出的
	private function getqingjiaqjs($uid, $dt, $lx)
	{
		$dt 	= substr($dt, 0, 10);
		$month	= substr($dt, 0, 7);
		if(!isset($this->qingjiaarr[$uid]) || !isset($this->qingjiaarr[$uid][$month])){
			$this->getqingjiaqj($uid, $dt);
		}
		$rows = $this->qingjiaarr[$uid][$month][$lx];
		$barr = array();
		if($rows)foreach($rows as $k=>$rs){
			$stime = substr($rs['stime'], 0, 10);
			$etime = substr($rs['etime'], 0, 10);
			if($dt>=$stime && $dt<=$etime)$barr[]=$rs;//在对应日期里
		}
		return $barr;
	}
	
	private function delquitwork($urs, $month, $max)
	{
		$ds = array();
		$dt1= ''.$month.'-01'; $dt2= ''.$month.'-'.$max.'';
		$uid= $urs['id'];
		if(!isempt($urs['workdate'])){
			if($urs['workdate']>$dt1)$ds[] = "dt<'".$urs['workdate']."'";
		}
		if(!isempt($urs['quitdt'])){
			if($urs['quitdt']<$dt2)$ds[] = "dt>'".$urs['quitdt']."'";
		}
		$str = join(' or ', $ds);
		if(!isempt($str))$this->db->delete('[Q]kqanay',"`uid`=$uid and ($str)");
	}
	
	private function geetdkarr($uid, $dt)
	{
		return $this->db->getrows('[Q]kqdkjl',"`uid`='$uid' and `dkdt` like '$dt%'",'`dkdt`','`dkdt` asc');
	}
	
	/**
	*	考勤分析
	*	@param $uid 用户Id
	*	@param $dt 分析日期
	*/
	public function kqanay($uid, $dt)
	{
		if($dt > $this->rock->date)return;
		$dkarr 	= $this->geetdkarr($uid, $dt);
		
		$iswork	= $this->isworkdt($uid, $dt);
		$sjarr	= $this->getkqsj($uid, $dt);
		$db 	= m('kqanay');
		$ids 	= '0';
		
		//是否有跨天的
		$isjy_1	=  $isjy_2	= 0;
		foreach($sjarr as $k=>$rs){
			foreach($rs['children'] as $k1=>$rs1){
				if($rs1['iskt']==2)$isjy_2=1; //-1天
				if($rs1['iskt']==1)$isjy_1=1; //+1天
			}
		}
		if($isjy_2==1){
			$dt2 	= $this->dtobj->adddate($dt,'d', -1);
			$dtarr2 = $this->geetdkarr($uid, $dt2);
			if($dtarr2)$dkarr = array_merge($dtarr2, $dkarr);
		}
		if($isjy_1==1){
			$dt1 	= $this->dtobj->adddate($dt,'d', 1);
			$dtarr1 = $this->geetdkarr($uid, $dt1);
			if($dtarr1)$dkarr = array_merge($dkarr, $dtarr1);
		}
		
		$this->_dkarr = $dkarr;
		$zshu		  = count($sjarr);
		$this->tempsbstatus = '';
		if($zshu==4)$this->tempsbstatus = $this->getstatessqj($sjarr[0], $dt, $uid); //[特殊判断]上午请假状态
		
		foreach($sjarr as $k=>$rs){
			if($rs['iskq']=='0')continue;//不用考勤就不分析了
			$ztname = $rs['name'];
			$arrs 	= $this->kqanaysss($uid, $dt, $rs, $this->_dkarr, $k, $zshu);
			$state	= $arrs['state'];
			$states	= $arrs['states'];
			$timesb	= $timeys = 0;
			
			//判断是否有请假和外出。。
			if($iswork==1 && $state !='正常'){
				$zcarr	= $arrs['zcarr'];
				if($zcarr && $states=='')$states = $this->getstates($zcarr, $dt, $uid);	
			}
	
			$emiao	= $arrs['emiao']; //迟到早退秒数
			$time	= $arrs['time'];
			
			//是工作时间段
			if($rs['isxx']=='0'){
				$mshu	= strtotime(''.$dt.' '.$rs['stime'].'') - strtotime(''.$dt.' '.$rs['etime'].'');
				$timesb	= abs(round($mshu / 60 / 60,1));
				if($state=='正常' || !isempt($states)){
					$timeys = $timesb;
				}else{
					if($emiao>0){
						$timeys = round((abs($mshu) - $emiao)/60/60, 1);
					}
				}
			}
			$arr	= array(
				'ztname' 	=> $ztname,
				'state' 	=> $state,
				'states' 	=> $states,
				'remark' 	=> $arrs['remark'],
				'time' 		=> $time,
				'uid' 		=> $uid,
				'dt' 		=> $dt,
				'sort' 		=> $k,
				'iswork' 	=> $iswork,
				'optdt' 	=> $this->rock->now,
				'emiao' 	=> $emiao,
				'timesb' 	=> $timesb,
				'timeys' 	=> $timeys
			);
			$where 	= "`uid`='$uid' and `dt`='$dt' and `ztname`='$ztname'";
			$id 	= (int)$db->getmou('id', $where);
			if($id==0)$where='';
			$db->record($arr, $where);
			if($id==0)$id = $this->db->insert_id();
			$ids.=','.$id.'';
		}
		$db->delete("id not in ($ids) and `uid`='$uid' and `dt`='$dt'");
	}
	private function kqanaysss($uid, $dt, $kqrs, $dkarr, $noi, $zshu)
	{
		$kqarr	= $kqrs['children'];
		$state	= '未打卡';$states = $remark = ''; $emiao	= 0; $tpk=-1; $time	= ''; $pdtime	= 0;
		$zcarr	= array();
		foreach($kqarr as $k2=>$cog2){
			if($cog2['name']=='正常')$zcarr = $cog2;//正常状态的
		}
		
		if($dkarr && $kqarr)foreach($kqarr as $k=>$rs){
			$stime 	= strtotime(''.$dt.' '.$rs['stime'].'');
			$etime 	= strtotime(''.$dt.' '.$rs['etime'].'');
			$qtype	= $rs['qtype'];
			$iskt	= $rs['iskt'];
			
			//-1跨天
			if($iskt==2){
				$dt2	= $this->dtobj->adddate($dt,'d', -1);
				$stime 	= strtotime(''.$dt2.' '.$rs['stime'].'');
				if($rs['stime']<$rs['etime']){
					$etime 	= strtotime(''.$dt2.' '.$rs['etime'].'');
				}
			}
			
			//+1跨天
			if($iskt==1){
				$dt1	= $this->dtobj->adddate($dt,'d', 1);
				$etime 	= strtotime(''.$dt1.' '.$rs['etime'].'');
				if($rs['stime']<$rs['etime']){
					$stime 	= strtotime(''.$dt1.' '.$rs['stime'].'');
				}
			}
			
			foreach($dkarr as $k1=>$rs1){
				$dkdt = strtotime($rs1['dkdt']);
				if($stime>$dkdt || $etime<$dkdt)continue;
				$time	= $dkdt;
				$state	= $rs['name'];
				$tpk	= $k1;
				if($qtype==0)break;
			}
			$pdtime	= $stime;
			if($qtype==1)$pdtime = $etime;
			if($time!='')break;
		}
		
		//4个状态，第2个状态需要额外处理，判断是不是请假的，上午全请假才需要判断
		$lxs = '';
		if($zshu==4 && $noi==1 && $zcarr){
			$lxs = $this->tempsbstatus;
			if($lxs!='')$states = $this->getstates($zcarr, $dt, $uid);
		}
		
		if($time!=''){
			if($state!='正常'){
				$emiao = $pdtime-$time;
			}
			if($lxs=='')unset($this->_dkarr[$tpk]);//一次打卡记录只能使用一次
		}
		$barr['state'] 		= $state;
		$barr['emiao'] 		= abs($emiao);
		if($time!='')$time	= date('Y-m-d H:i:s', $time);
		$barr['time'] 		= $time;
		$barr['states'] 	= $states;
		$barr['remark'] 	= $remark;
		$barr['zcarr'] 		= $zcarr;
		if($pdtime!=0)$barr['pdtime'] = date('Y-m-d H:i:s', $pdtime);
		return $barr;
	}
	
	//【2019-05-12】判断是不是半天请假和外出了，只用于有每天4个考勤状态的
	private function getstatessqj($sbarr, $dts, $uid)
	{
		$rows 	= $this->getqingjiaqjs($uid, $dts,'qingjia');
		$st1	= strtotime($dts.' '.$sbarr['stime']);
		$et1	= strtotime($dts.' '.$sbarr['etime']);
		$lxs	= '';
		foreach($rows as $k=>$rs){
			$qst = strtotime($rs['stime']);
			$qet = strtotime($rs['etime']);
			if($qst<=$st1 && $qet>=$et1){
				$lxs = $rs['qjkind'];
			}
		}
		if($lxs==''){
			$rows 	= $this->getqingjiaqjs($uid, $dts,'waichu');
			foreach($rows as $k=>$rs){
				$qst = strtotime($rs['stime']);
				$qet = strtotime($rs['etime']);
				if($qst<=$st1 && $qet>=$et1){
					$lxs = $rs['atype'];
				}
			}
		}
		return $lxs;
	}

	/**
	*	上班: (当前qtype==0)请假开始时间小于等于 设置正常的截止时间（取最小值）
	*	下班: (当前qtype==1)请假截止时间大于等于 设置正常的开始时间（取最大值）
	*	最新取消
	*/
	private function getstates($ztarr, $dts, $uid)
	{
		$st1	= strtotime($dts.' '.$ztarr['stime']);
		$et1	= strtotime($dts.' '.$ztarr['etime']);
		$s 		= '';
		
		$rows 	= $this->getqingjiaqjs($uid, $dts,'qingjia');
		foreach($rows as $k=>$rs){
			$qst = strtotime($rs['stime']);
			$qet = strtotime($rs['etime']);
			if(($st1>=$qst && $st1<=$qet) || ($et1>=$qst && $et1<=$qet)){
				$s = $rs['qjkind'];
				break;
			}
		}
		if($s==''){
			$rows 	= $this->getqingjiaqjs($uid, $dts,'waichu');
			foreach($rows as $k=>$rs){
				$qst = strtotime($rs['stime']);
				$qet = strtotime($rs['etime']);
				if(($st1>=$qst && $st1<=$qet) || ($et1>=$qst && $et1<=$qet)){
					$s = $rs['atype'];
					break;
				}
			}
		}
		return $s;
	}
	
	/**
	*	获取默人对月份所有考勤状态{'2017-01-17':[{name:'上班','state':'正常'}]}
	*/
	public function getanay($uid, $month, $dt='')
	{
		$month	= substr($month, 0, 7);
		$dtobj  = c('date');
		$max 	= $dtobj->getmaxdt($month);
		$startdt= ''.$month.'-01';
		$enddt  = ''.$month.'-'.$max.'';
		if($dt!=''){
			$startdt = $dt;
			$enddt   = $dt;
		}
		$rows  	= $this->db->getrows('[Q]kqanay',"`uid`='$uid' and `dt` between '$startdt' and '$enddt'",'*','dt,sort');
		$barr 	= array();
		foreach($rows as $k=>$rs){
			if(!isset($barr[$rs['dt']]))$barr[$rs['dt']]=array();
			
			$barr[$rs['dt']][] 	= $rs;
		}
		return $barr;
	}
	
	
	/**
	*	计算剩余假期时间,如果审核未通过，申请人不删除照样也会扣除时间
	*/
	public function getqjsytime($uid, $type, $dt='', $id=0, $jzdt='')
	{
		$types 	= '增加'.$type.'';
		$wehe	= "`kind`='$types'";
		if($type=='调休'){
			$types='加班';
			$wehe = "((`kind`='$types' and `jiatype`=0) or (`kind`='增加调休'))"; //只有可调休才能用
		}
		if($dt=='')$dt = $this->rock->now;
		if($jzdt=='')$jzdt = $dt; //截止时间
		$zto	= $to1 = 0;
		$enddt 	= '';//截止
		
		
		
		
		//总
		$zrows	= $this->db->getall("select * from `[Q]kqinfo` where `uid`='$uid' and `status`=1 and $wehe order by `stime` asc");
		if(!$zrows)return 0;
		foreach($zrows as $k1=>$rs1){
			$jsdt = $rs1['enddt'];
			if(isempt($jsdt))$jsdt 	= '2099-12-31 23:59:59';
			$zrows[$k1]['enddt']	= $jsdt;
			$zrows[$k1]['totals']	= floatval($rs1['totals']);
		}
		
		//用过的
		$yrows  = $this->db->getall("select * from `[Q]kqinfo` where `uid`='$uid' and `kind`='请假' and `qjkind`='$type' and `status` not in(5) and `id`<>$id order by `stime` asc");
		
		
		return $this->getqjsytimess($zrows, $yrows, $dt, $jzdt);
	}
	
	private function getqjsytimess($zrows, $yrows, $dt, $jzdt)
	{
		$zross  = array();
		$yross  = array();
		$zsssj  = 0;
		if($yrows && $zrows){
			
			foreach($zrows as $k1=>$rs1){
				$totals = floatval($rs1['totals']); //总可用时间
				foreach($yrows as $k2=>$rs2){
					$totaly = floatval($rs2['totals']);
					if($totaly>0 && $rs2['stime']>=$rs1['stime'] && $rs2['etime']<=$rs1['enddt']){
						$sys = $totaly - $totals;
						if($sys==0){
							$totals = 0;
							$yrows[$k2]['totals']= 0;
						}else if($sys>0){
							$yrows[$k2]['totals']= $sys;
							$totals = 0;
						}else{
							$totals = 0-$sys;
							$yrows[$k2]['totals'] = 0;
						}
					}
					if($totals ==0)break;//扣完了
				}
				if($totals>0){
					$rs1['totals'] = $totals;
					$zross[] = $rs1;
				}
			}
			
			//递归处理
			$zsssj = $this->getqjsytimess($zross, $yross, $dt, $jzdt);
		}else{
			if($zrows)foreach($zrows as $k1=>$rs1){
				if($rs1['stime']<=$dt && $rs1['enddt']>=$jzdt){
					$zsssj+=$rs1['totals'];
				}
			}
		}
		
		return $zsssj;
	}
	
	
	//总统计显示
	public function getqjsytimestr($uid=0, $dt='', $id=0)
	{
		if($uid==0)$uid = $this->adminid;
		$rows = $this->db->getall("select `kind` from `[Q]kqinfo` where `uid`='$uid' and `status`=1 and `kind` like '增加%' group by `kind`");
		$tx   = $this->getqjsytime($uid, '调休', $dt, $id);
		$str  = '';
		if($tx>0)$str  = '可调休('.$tx.'小时)';
		foreach($rows as $k=>$rs){
			if($rs['kind']=='增加调休')continue;
			$type = str_replace('增加', '', $rs['kind']);
			$sj   = $this->getqjsytime($uid, $type, $dt, $id);
			if($str!='')$str  .= '，';
			$str  .= ''.$type.'('.$sj.'小时)';
		}
		if($str=='')$str='无剩余假期';
		return $str;
	}
	
	/**
	*	判断这段时间是否可以申请请假
	*/
	public function leavepan($uid, $qjkind, $start, $end, $totals, $id=0)
	{
		$msg 	= '';
		$sdf 	= $this->db->rows('[Q]kqinfo',"`uid`='$uid' and `status`<>5 and ((`stime`<='$start' and `etime`>='$start') or (`stime`<='$end' and `etime`>='$end') or (`stime`>='$start' and `etime`<='$end')) and `kind`='请假' and `id`<>'$id' ");
		if($sdf > 0){
			$msg = '该时间段已申请过了';
		}
		$tsjia	= '事假,病假';
		$tsjia	= m('option')->getval('kqsqtype', $tsjia); //读取选项
		if($msg == '' && !$this->contain(','.$tsjia.',', ','.$qjkind.',')){
			$sy1	= $this->getqjsytime($uid, $qjkind, $start, $id, $end);
			if($sy1<0)$sy1=0;
			$totals	= floatval($totals);
			if($totals>$sy1)$msg = '剩余['.$qjkind.']'.$sy1.'小时,不能申请,'.c('xinhu')->helpstr('kaoqin').'';
		}
		return $msg;
	}
	
	/**
	*	计算当月请假时间合计
	*/
	public function getqjtotal($uid, $month)
	{
		$to 	= $this->getkqtotalsss($uid, $month,'请假');
		return  $to;
	}
	
	/**
	*	计算当月加班时间合计
	*/
	public function getjbtotal($uid, $month)
	{
		$to 	= $this->getkqtotalsss($uid, $month,'加班');
		return  $to;
	}
	private function getkqtotalsss($uid, $month, $kind)
	{
		$ors  	= $this->db->getmou('[Q]kqinfo','sum(totals)as totals',"`status`=1 and `uid`=$uid and `stime` like '$month%' and `kind`='$kind'");
		$to 	= 0;
		if($ors)$to = $ors;
		return  $to;
	}
	
	/**
	*	统计当前我考勤异常的状态
	*/
	public function getkqtotal($uid, $month)
	{
		$sql 	= "SELECT state,count(1)stotal FROM `[Q]kqanay` where `dt` like '$month%' and `uid`=$uid and `iswork`=1 and `states` is null GROUP BY `state`";
		$rows 	= $this->db->getall($sql);
		$chidao	= $zaotui = $weidk = 0;
		foreach($rows as $k=>$rs){
			if($rs['state']=='迟到')$chidao = $rs['stotal'];
			if($rs['state']=='早退')$zaotui = $rs['stotal'];
			if($rs['state']=='未打卡')$weidk= $rs['stotal'];
		}
		$dbts = $this->getsbdt($uid, $month);//上班天数
		
		return array(
			'ysbtime' 	=> $dbts[0],
			'zsbtime' 	=> $dbts[1],
			'cidao' 	=> $chidao,
			'zaotui' 	=> $zaotui,
			'weidk' 	=> $weidk,
			'jiaban' 	=> $this->getjbtotal($uid, $month),
			'jiabans' 	=> $this->getjiafee($uid, $month),
			'leave' 	=> $this->getqjtotal($uid, $month),
		);
	}
	
	/**
	*	获取默认某天应该上班时间段
	*	返回array(array('每天上班时间断'))
	*/
	public function getsbarr($uid, $dt)
	{
		$arr 	= $this->getkqsj($uid, $dt, 1);
		$barr	= array();
		foreach($arr as $k=>$rs){
			if(!isempt($rs['stime']) && !isempt($rs['etime']))$barr[] = $rs;
		}
		return $barr;
	}
	
	/**
	*	获取某个人某一天考勤状态[{stat}]
	*/
	public function getsbanay($uid, $dt)
	{
		$ansy = $this->getanay($uid, $dt, $dt);
		$nates= array();
		if(isset($ansy[$dt])){
			$barr = $ansy[$dt];
			foreach($barr as $k=>$rs){
				$nates[$rs['ztname']] = $rs;
			}
		}
		$sbarr = $this->getsbarr($uid, $dt);
		foreach($sbarr as $k=>$rs){
			$state = '<font color=red>未打卡</font>';
			if(isset($nates[$rs['name']])){
				$ors 	= $nates[$rs['name']];
				$state 	= $this->getkqstate($ors);
			}
			$sbarr[$k]['state'] = $state;
		}
		
		return $sbarr;
	}
	
	/**
	*	获取某一天的打卡记录
	*/
	public function getdkjl($uid, $dt)
	{
		$rows = $this->db->getall("select `dkdt` from `[Q]kqdkjl` where uid='$uid' and `dkdt` like '".$dt."%' order by `dkdt`");
		foreach($rows as $k=>$rs){
			$rows[$k]['dktime'] = substr($rs['dkdt'], 11);
		}
		return $rows;
	}
	
	/**
	*	考勤状态
	*/
	public function getkqstate($rs)
	{
		$s 	 	= $rs['state'];
		$state 	= $rs['state'];
		$iswork = $rs['iswork'];
		
		$miaocn	= '';
		if($rs['emiao']>0){
			$stssa = explode(':', $this->dtobj->sjdate($rs['emiao'],'H:i:s'));
			if($stssa[0]>0)$miaocn=''.$stssa[0].'时';
			$miaocn.=''.$stssa[1].'分'.$stssa[2].'秒';
		}
		$rs['miaocn'] = $miaocn;
		
		if($iswork==0 && $state == '未打卡')$s='休息日';
		if(!isempt($rs['miaocn'])){
			$s.='['.$rs['miaocn'].']';
		}
		if(!isempt($rs['time']))$s.='('.substr($rs['time'],11).')';
		
		if(!isempt($rs['states'])){
			$state = '正常';
			$s	= $rs['states'];
		}
		
		if($state != '正常' && $iswork==1){
			if($s=='未打卡'){
				$s='<font color=red>'.$s.'</font>';
			}else{
				$s='<font color=blue>'.$s.'</font>';
			}
		}
		if($s=='休息日')$s='<font color=#888888>'.$s.'</font>';
		
		return $s;
	}
	
	/**
	*	获取默认某天应该上班时间段
	*	返回09:00-18:00
	*/
	public function getsbstr($uid, $dt)
	{
		$barr	= $this->getsbarr($uid, $dt);
		$stime 	= $etime = '';
		foreach($barr as $k=>$rs){
			if($rs['iskq']==1 && $rs['isxx']=='0'){
				if($stime=='')$stime = $rs['stime'];
				$etime = $rs['etime'];
			}
		}
		if($stime=='')$stime = '09:00:00';
		if($etime=='')$etime = '18:00:00';
		$stimes 	= $dt.' '.$stime;
		$etimes 	= $dt.' '.$etime;
		return array(
			'stime' 	=> $stimes,
			'stimes' 	=> strtotime($stimes),
			'etime' 	=> $etimes,
			'etimes' 	=> strtotime($etimes),
		);
	}
	
	/**
	*	每天上班时间断，返回8小时
	*/
	public function getworktime($uid, $dt='')
	{
		if($dt=='')$dt = $this->rock->date;
		$dt 	= substr($dt, 0, 10);
		$to 	= floatval(m('option')->getval('kqsbtime', 0));
		if($to==0)$to = $this->getsbtime($uid, $dt.' 00:00:00', $dt.' 23:59:59', 1);
		if($to<=0)$to = 8;//默认1天上班时间8小时
		return $to;
	}
	
	/**
	*	根据时间间隔获取上班时间小时
	*	$lx=0 计算请假时间 1算当前应上班时间
	*/
	public function getsbtime($uid,$sdt, $edt, $lx=0)
	{
		$tot	= 0;
		$sdt1	= strtotime($sdt);
		$edt1	= strtotime($edt);
		$dtsa	= explode(' ', $sdt);
		$dts	= $dtsa[0];
		$jg		= $this->dtobj->datediff('d', $sdt, $edt);
		for($i=0; $i<$jg+1; $i++){
			if($i>0)$dts = $this->dtobj->adddate($dts, 'd', 1);
			if($lx==0)if($this->isworkdt($uid, $dts)==0)continue;//休息日就不用算了
			$arr 	= $this->getsbarr($uid, $dts);
			foreach($arr as $k=>$rs){
				$iskt	= $rs['iskt'];
				if($rs['iskq']==1 && $rs['isxx']=='0'){
					$_sts = strtotime($dts.' '.$rs['stime']);
					$_ets = strtotime($dts.' '.$rs['etime']);
					
					//开始时间-1
					if($iskt=='2'){
						
					}
					
					//结束时间+1
					if($iskt=='1'){
						$dts2 = $this->dtobj->adddate($dts, 'd', 1);
						$_ets = strtotime($dts2.' '.$rs['etime']);
					}
					
					if($_sts<$sdt1)$_sts=$sdt1;
					if($_ets>$edt1)$_ets=$edt1;
					$_tisg = $_ets - $_sts;
					if($_tisg>0)$tot+=$_tisg;
				}
			}
		}
		return $tot / 3600;
	}
	
	/**
	*	统计月份应上班天数
	*	return array(应上班天数, 有上班天数);
	*/
	public function getsbdt($uid, $month)
	{
		$month	= substr($month, 0, 7);
		$dtobj  = c('date');
		$max 	= $dtobj->getmaxdt($month);
		$startdt= ''.$month.'-01';
		$enddt  = ''.$month.'-'.$max.'';
		$tian	= 0; //应上班
		$ysb	= 0; //已上班
		//$anayarr= $this->db->getrows('[Q]kqanay', "`uid`=$uid and `dt` like '$month%'");
		
		$sbxs	= $this->getworktime($uid); //每天上班时间
		
		$starr 	= $this->db->getall("SELECT `dt`,sum(timesb)as timesb,sum(timeys)as timeys FROM `[Q]kqanay` where `uid`=$uid and `dt` like '$month%' and `iswork`=1 GROUP BY dt");
		foreach($starr as $k=>$rs){
			$timesb = floatval($rs['timesb']);
			$timeys = floatval($rs['timeys']);
			
	
			$tian  += round($timesb/$sbxs,1);
			$ysb   += round($timeys/$sbxs, 1);
		}
		/*
		for($i=0; $i<$max; $i++){
			$oi = ($i<10) ? '0'.$i.'' : $i;
			$dt = ''.$month.'-'.$oi.'';
			if($this->isworkdt($uid, $dt)==1){
				$tian++;
				foreach($anayarr as $k=>$rs){
					if($rs['dt']==$dt && $rs['state']!='未打卡'){
						$ysb++;
						break;
					}
				}
			}
		}*/
		//$this->rock->number
		return array(round($tian,1), round($ysb,1), round($tian-$ysb,1));
	}
	
	/**
	*	计算人员加班工资，一天默认8小时
	*	$uid 人员，$hour 小时，$stime 加班开始时间
	*	加班费计算（正常工作日1.5），周末2.0，节假日3.0
	*	上月的(基本工资+职位津贴+技能津贴)/本月应上班天数 * 1.5，周末*2.0
	*/
	public function jiafee($uid, $hour, $stime)
	{
		$fee = 0;
		$hour= floatval($hour);
		if($hour<=0)return $fee;
		$nrs = $this->db->getone('[Q]hrsalary','`xuid`='.$uid.' and `status`<>5','`base`,`postjt`,`skilljt`','`month` desc');
		if($nrs){
			$dt 	= substr($stime, 0, 10);
			$hs  	= $this->getworktime($uid, $dt); //一天上班小时
			$sbarr	= $this->getsbdt($uid, $dt);
			$bsh	= 1.5;
			if($this->isworkdt($uid, $dt)==0)$bsh = 2; //休息日倍数2
			$base 	= floatval($nrs['base']) + floatval($nrs['postjt']) + floatval($nrs['skilljt']);
			
			$gongzi	= $base/$sbarr[0];//一天的工资
			$fee	= ($gongzi / $hs) * $bsh * $hour;
			$fee 	= $this->rock->number($fee);
		}
		return $fee;
	}
	
	//获取对应月份加班费
	public function getjiafee($uid, $month)
	{
		$mond = $this->db->getmou('[Q]kqinfo','sum(jiafee)', "`uid`=$uid and `kind`='加班' and `status`=1  and `jiatype`=1 and `stime` like '$month%'");
		if(isempt($mond))$mond = 0;
		return $mond;
	}
	
	
	/**
	*	all统计
	*/
	public function alltotal($month, $uids)
	{
		$barr = $this->alltotalrows($month, $uids);
		$data = $barr['rows'][0];
		$farrs 				= $barr['columns'];
		$farrs['未打卡'] 	= 'weidk';
		$farrs['请假'] 		= 'qingjia';
		$farrs['加班'] 		= 'jiaban';
		$farrs['外出出差'] 	= 'outci';
		$farrs['打卡异常'] 	= 'errci';
		$farrs['应上班'] 	= 'sbday';
		$farrs['已上班'] 	= 'ysbday';
		return array('data'=>$data, 'fields'=>$farrs, 'unita'=> array(
			'qingjia' => '小时',
			'jiaban'  => '小时',
			'sbday'  	=> '天',
			'ysbday'  	=> '天',
		));
	}
	
	/**
	*	考勤统计使用
	*/
	private function alltotalrowssst($rows,$dt, $oi=0)
	{
		$columns= array();
		if(!isset($rows[$oi]))return $columns;
		$fuid 	= $rows[$oi]['id'];
		$farrs 	= $this->getkqztarr($fuid, $dt);
		$columns= $farrs;
		if($columns)return $columns;
		return $this->alltotalrowssst($rows, $dt, $oi+1);
	}
	public function alltotalrows($month, $uids)
	{
		$rows	= array();
		if(is_array($uids)){
			$rows = $uids;
		}else{
			$uidsa = explode(',', $uids);
			foreach($uidsa as $_uids)$rows[] = array('id'=>$_uids);
		}
		$uids 	= '0';
		foreach($rows as $k=>$rs)$uids.=','.$rs['id'].'';
		$farrs	= $columns = array();

		//获取考勤状态数组{'正常':'state0'}
		if($rows)$columns= $this->alltotalrowssst($rows, $month.'-01'); //读取表头
		
		$darr	= $this->db->getall("SELECT uid,state,states FROM `[Q]kqanay` where `uid` in($uids) and dt like '$month%' and iswork=1");
		$sarr 	= array();
		foreach($darr as $k=>$rs){
			$state 	= $rs['state'];
			$uid 	= $rs['uid'];
			if(!isempt($rs['states']))$state='正常';
			if(!isset($sarr[$uid]))$sarr[$uid]=array();
			if(!isset($sarr[$uid][$state]))$sarr[$uid][$state]=0;
			$sarr[$uid][$state]++; //统计次数用的
		}
		
		$farrs				= $columns;
		$farrs['未打卡'] 	= 'weidk';
		$farrs['请假'] 		= 'qingjia';
		$farrs['事假'] 		= 'shijia';
		$farrs['加班'] 		= 'jiaban';
		$lxarr 				= m('option')->getmnum('kqqjkind');
		foreach($lxarr as $k=>$rs){
			$tev 				= 'qingjia'.$rs['id'].'';
			$farrs[$rs['name']] = $tev;
			//$columns[$rs['name']]		= $tev;
		}
		
		$kqarr	= $this->db->getall("select sum(totals)as totals,kind,qjkind,uid from `[Q]kqinfo` where `status`=1 and `uid` in($uids) and `stime` like '$month%' and `kind` in('请假','加班') group by `uid`,`qjkind`");
		foreach($kqarr as $k=>$rs){
			$uid 	= $rs['uid'];
			$kind	= $rs['qjkind'];
			if(isempt($kind))$kind = '加班';
			if(!isset($sarr[$uid]))$sarr[$uid]=array();
			$sarr[$uid][$kind] = $rs['totals'];
			$kinds = $kind.'(时)';
			if($rs['kind']=='请假' && !isset($columns[$kinds])){
				$columns[$kinds] = $farrs[$kind];
			}
		}
		
		foreach($rows as $k=>$rs){
			$uid 	= $rs['id'];
			if(isset($sarr[$uid])){
				foreach($sarr[$uid] as $zt=>$v){
					if(isset($farrs[$zt])){
						$rows[$k][$farrs[$zt]] = $v;
					}
				}
			}
			$outci	= $this->db->rows('[Q]kqout',"`status`=1 and `uid`=$uid and `outtime` like '$month%'");
			if($outci==0)$outci='';
			$rows[$k]['outci'] = $outci;
			
			$errci	= $this->db->rows('[Q]kqerr',"`status`=1 and `uid`=$uid and `dt` like '$month%'");
			if($errci==0)$errci='';
			$rows[$k]['errci'] = $errci;
			
			$sbdt 			   	= $this->getsbdt($uid, $month);
			$rows[$k]['sbday'] 	= $sbdt[0];
			$rows[$k]['ysbday'] = $sbdt[1]>0 ?  $sbdt[1] : '';
		}
		
		return array('rows'=>$rows,'columns'=>$columns);
	}
	

}