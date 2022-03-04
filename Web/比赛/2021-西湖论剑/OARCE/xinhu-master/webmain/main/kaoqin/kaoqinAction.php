<?php
class kaoqinClassAction extends Action
{
	

	public function kqdkjlaftershow($table, $rows)
	{
		$reimbo = m('reim');
		return array(
			'rows' => $rows,
			'qybo' => $reimbo->installwx(1),
			'ddbo' => $reimbo->installwx(2),
		);
	}
	
	//获取打卡记录
	public function getdkjlAjax()
	{
		$reimbo = m('reim');
		$uids	= $this->adminid;
		if($this->post('atype')=='all')$uids = '';//全部
		$dt1	= $this->post('dt1');
		$dt2	= $this->post('dt2');
		$msg 	= '获取成功';
		if($reimbo->installwx(1)){
			$barr 	= m('weixinqy:daka')->getrecord($uids, $dt1, $dt2, 1);
			//加入异步
			$send = 0;
			if($uids=='' && $barr['errcode']==0 && $barr['maxpage']>1){
				for($i=1;$i<=$barr['maxpage'];$i++){
					if($i>1)$reimbo->asynurl('asynrun','wxdkjl', array(
						'dt1' 		=> $dt1,
						'dt2' 		=> $dt2,
						'page' 		=> $i
					));
				}
				$send++;
			}
			if($barr['errcode']!=0){
				$msg .= ',企业微信('.$barr['msg'].')';
			}else{
				if(isset($barr['zongts']))$msg .= ',微信打卡(共'.$barr['zongts'].'条,新增'.$barr['okload'].'条)';
				if($send>0)$msg .= ',并发送异步请求'.$send.'条';
			}
		}
		
		//钉钉
		if($reimbo->installwx(2)){
			$barr = m('dingding:daka')->getrecord($uids, $dt1, $dt2);
			if($barr['errcode']!=0)$msg .= ',钉钉('.$barr['msg'].')';
		}
		return returnsuccess($msg);
	}
	
	
	public function kqdwbefore($table)
	{
		$key	= $this->post('key');
		$where 	= '';
		if(!isempt($key))$where=" and (`name` like '%$key%' or `address` like '%$key%')";
		return $where;
	}
	
	
	//考勤信息
	public function kqinfobeforeshow($table)
	{
		$dt1	= $this->post('dt1');
		$dt2	= $this->post('dt2');
		$atype	= $this->post('atype');
		$key	= $this->post('key');
		$keys	= $this->post('keys');
		$uid	= $this->adminid;
		$s 		= '';
		
		if($atype=='all'){
			$s = m('admin')->getcompanywhere(4);
		}
		if($atype=='my'){
			$s = 'and uid='.$uid.'';
		}
		if($atype=='down'){
			$s  = 'and '.m('admin')->getdownwheres('a.uid', $uid, 0);
		}
		
		if(!isempt($dt1))$s.=" and a.`stime` >= '$dt1'";
		if(!isempt($dt2))$s.=" and a.`stime` <= '$dt2 23:59:59'";
		if(!isempt($key))$s.=" and (b.`name` like '%$key%' or b.`deptname` like '%$key%')";
		if(!isempt($keys))$s.=" and (a.`kind`='$keys' or a.`qjkind`='$keys')";
		$fields = 'a.*,b.name,b.deptname';
		$table  = '[Q]'.$table.' a left join `[Q]admin` b on a.uid=b.id';
		return array('where'=>$s,'table'=>$table, 'fields'=>$fields,'order'=>'a.stime desc');
	}
	
	public function kqinfoaftershow($table, $rows)
	{
		$uid 	= $this->adminid;
		$types 	= explode(',','<font color=blue>待审核</font>,<font color=green>已审核</font>,<font color=red>未通过</font>,,,<font color=#888888>已作废</font>');
		foreach($rows as $k=>$rs){
			$rows[$k]['status'] = $this->rock->arrvalue($types, $rs['status']);
			$modenum  = 'leavehr';
			$modename = '考勤信息';
			if($rs['kind']=='请假'){
				$modenum  = 'leave';
				$modename = '请假条';
			}
			$jiatype 	 = '';
			if($rs['kind']=='加班'){
				$modenum  = 'jiaban';
				$modename = '加班单';
				$jiatype  = '调休';
				if($rs['jiatype']=='1')$jiatype='加班费'.$rs['jiafee'].'';
			}
			$rows[$k]['modenum'] 	= $modenum;
			$rows[$k]['modename'] 	= $modename;
			if($rs['status']==5)$rows[$k]['ishui'] 	= 1;
			
			$totday			= floatval(arrvalue($rs,'totday','0'));
			if($totday>0)$rows[$k]['totals'].='('.$totday.'天)';
			$rows[$k]['jiatype'] = $jiatype;
			if(!isempt($rs['enddt'])){
				$rows[$k]['etime'] = $rs['enddt']; //截止时间
				if($rs['enddt']<$this->rock->now)$rows[$k]['ishui'] = 1;
			}
		}
		$month	= $this->post('dt1', date('Y-m'));
		$str = '';
		if($this->post('atype')=='my'){
			$kqm 	= m('kaoqin');
			$jiafee = $kqm->getjiafee($uid, $month);
			$str	= ''.$kqm->getqjsytimestr($uid).'';
			if($jiafee>0)$str.='，'.substr($month,0,7).'加班费('.$jiafee.'元)';
		}
		return array('rows'=>$rows,'totalstr'=> $str);
	}
	
	
	
	
	
	
	
	
	
	
	public function kqsjgzdataAjax()
	{
		$this->rows = array();
		$this->getkqdat(0, 1);
		$this->returnjson(array(
			'rows' => $this->rows
		));
	}
	private function getkqdat($pid, $oi)
	{
		$db		= m('kqsjgz');
		$menu	= $db->getall("`pid`='$pid' order by `sort`",'*');
		foreach($menu as $k=>$rs){
			$sid			= $rs['id'];
			$rs['level']	= $oi;
			$rs['stotal']	= $db->rows("`pid`='$sid'");
			$this->rows[] = $rs;
			$this->getkqdat($sid, $oi+1);
		}
	}
	public function kqsjgzdatadelAjax()
	{
		$type	= (int)$this->post('type','0');
		$id 	= (int)$this->post('id');
		if($id==1 && $type!=3)showreturn('','此记录不能删除',201);
		if($type==0)m('kqsjgz')->delete("`id`='$id' or pid='$id'");
		if($type==1)m('kqdist')->delete("`id`='$id'"); //分配的
		if($type==2)m('kqxxsj')->delete("`id`='$id' or pid='$id'");
		if($type==3){
			$ida = c('check')->onlynumber($this->post('id'));
			m('kqxxsj')->delete("`id` in($ida)");
		}
		showreturn();
	}
	
	public function kqdwdkdatadelAjax()
	{
		$id 	= (int)$this->post('id');
		m('kqdw')->delete("`id`='$id'");
		showreturn();
	}
	
	
	
	
	
	//考勤时间分配
	public function kqdistbefore($table)
	{
		$type	= (int)$this->post('type','0');
		$gzid	= (int)$this->post('gzid','0');
		$key	= $this->post('key');
		$where 	= 'and `type`='.$type.'';
		if($gzid!=0)$where.=" and `mid` ='$gzid'";
		if(!isempt($key))$where.=" and `recename` like '%$key%'";
		return array(
			'where' => $where,
			'order' => 'id desc'
		);
	}
	public function kqdistafter($table, $rows)
	{
		$type	= (int)$this->post('type','0');
		$db 	= m('kqsjgz');
		if($type==1)$db = m('kqxxsj');
		if($type==2)$db = m('kqdw');
		foreach($rows as $k=>$rs){
			$rows[$k]['mid'] 	= $db->getmou('name', $rs['mid']);
			$rows[$k]['mids'] 	= $rs['mid'];
		}
		$gzdata = array();
		if($type==0){
			$gzdata	= $db->getall('pid=0','id,name','`sort`');
		}else if($type==1){
			$gzdata	= $db->getall('pid=0','id,name','`id`');
		}else if($type==2){
			$gzdata	= $db->getall('1=1','id,name','`id`');
		}
		return array(
			'rows' 		=> $rows,
			'gzdata' 	=> $gzdata
		);
	}
	
	
	public function kqxxsjdtbefore($table)
	{
		$pid 	= (int)$this->post('pid','0');
		$month 	= $this->post('month');
		$s 		= 'and `pid`='.$pid.'';
		if(!isempt($month))$s.=" and `dt` like '$month%'";
		return array(
			'where' => $s,
			'order' => 'dt desc'
		);
	}
	public function kqxxsjdtafter($table, $rows)
	{
		$dtobj = c('date');
		foreach($rows as $k=>$rs){
			$w = $dtobj->cnweek($rs['dt']);
			$rows[$k]['week'] = $w;
			if($w=='六' || $w=='日')$rows[$k]['ishui'] = 1;
		}
		return array('rows'=>$rows);
	}
	public function setxiugdateAjax()
	{
		$month 	= $this->post('month');
		$pid 	= (int)$this->post('pid','0');
		if(isempt($month) || $pid==0)return;
		$dtobj 	= c('date');
		$max 	= $dtobj->getmaxdt($month);
		$db 	= m('kqxxsj');
		for($i=1; $i<=$max; $i++){
			$oi = $i;if($oi<10)$oi='0'.$i.'';
			$dt = ''.$month.'-'.$oi.'';
			$we = $dtobj->cnweek($dt);
			if($we=='六' || $we=='日'){
				$where = "pid='$pid' and `dt`='$dt'";
				if($db->rows($where)==0)$db->insert("pid='$pid',`dt`='$dt'");
			}
		}
	}
	
	//一键添加节假日
	public function setjiedateAjax()
	{
		$month 	= $this->post('month');
		$pid 	= (int)$this->post('pid','0');
		if(isempt($month) || $pid==0)return;
		$dtobj 	= c('date');
		$year 	= substr($month,0,4);
		$dt		= ''.$year.'-01-01';
		$db 	= m('kqxxsj');
		//从官网读取节假日日期
		$barr 	= c('xinhuapi')->getjiari();
		if(!$barr['success'])return $barr;
		$jierixiuxi 	= $barr['data']['jierixiuxi']; //休息日
		$jierishangban 	= $barr['data']['jierishangban']; //上班日
		
		for($i=0;$i<366;$i++){
			if($i>0)$dt = $dtobj->adddate($dt,'d', 1);
			$we = $dtobj->cnweek($dt);
			$isxiu = 0;
			if($we=='六' || $we=='日'){
				$isxiu = 1;
			}
			if(contain($jierixiuxi, $dt))$isxiu = 1;
			if(contain($jierishangban, $dt))$isxiu = 0;//上班
			
			$where = "pid='$pid' and `dt`='$dt'";
			if($isxiu==1){
				if($db->rows($where)==0)$db->insert("pid='$pid',`dt`='$dt'");
			}else{
				$db->delete($where);
			}
			if($dt==''.$year.'-12-31')break;
		}
		return returnsuccess();
	}
	
	
	//考勤分析
	public function kqanaybeforeshow($table)
	{
		$dt1	= $this->post('dt1');
		$dt2	= $this->post('dt2');
		$key	= $this->post('key');
		$iswork	= $this->post('iswork','1');
		$iskq	= $this->post('iskq','1');
		$s 		= '';
		if($iswork=='1')$s.=" and a.`iswork`=$iswork";
		if($iskq=='1')$s.=" and b.`iskq`=$iskq";
		if(!isempt($dt1))$s.=" and a.`dt` >= '$dt1'";
		if(!isempt($dt2))$s.=" and a.`dt` <= '$dt2'";
		if(!isempt($key))$s.=" and (b.`name` like '%$key%' or b.`deptname` like '%$key%')";
		$fields = 'a.*,b.name,b.deptname';
		if(ISMORECOM && $this->adminid>1)$s.=' and b.`companyid`='.m('admin')->getcompanyid().'';
		$table  = '[Q]'.$table.' a left join `[Q]userinfo` b on a.uid=b.id';
		return array('where'=>$s,'table'=>$table, 'fields'=>$fields,'order'=>'a.`dt` desc,a.`uid`,`sort`');
	}
	public function kqanayaftershow($table, $rows)
	{
		$dtobj = c('date');
		$ustie = '';
		$iswordk = array('否','是');
		$kq 	= m('kaoqin');
		foreach($rows as $k=>$rs){
			$rows[$k]['status'] 	= $rs['iswork'];
			$rows[$k]['week']	 	= $dtobj->cnweek($rs['dt']);
			$keys= ''.$rs['dt'].''.$rs['uid'].'';
			$rows[$k]['iswork'] = arrvalue($iswordk, $rs['iswork']);
			
			$rows[$k]['state']	= $kq->getkqstate($rs);

			if($ustie!='' && $ustie==$keys){
				$rows[$k]['deptname'] 	= '';
				$rows[$k]['name'] 		= '';
				$rows[$k]['dt'] 		= '';
				$rows[$k]['iswork'] 	= '';
				$rows[$k]['week']		 = '';
			}
			$ustie= $keys;
		}
		return array('rows'=>$rows);
	}
	public function kqanayallAjax()
	{
		$dt 	= $this->post('dt');
		$atype 	= $this->post('atype');
		$whe 	= '';
		if($atype=='my')$whe=' and id='.$this->adminid.'';
		m('kaoqin')->kqanayall($dt, $whe);
	}
	public function kqanayallinitAjax()
	{
		$dt 	= $this->post('dt');
		$atype 	= $this->post('atype');
		if($atype=='my'){
			$this->kqanayallAjax();
			return '{"zong":"ok"}';
		}
		return m('kaoqin')->kqanayallfirst($dt, 1);
	}
	public function kqanayallpageAjax()
	{
		$dt 	= $this->post('dt');
		$page 	= (int)$this->post('page');
		m('kaoqin')->kqanayall($dt, '', $page);
		echo 'ok';
	}
	
	
	//考勤分析总表
	public function kqanayallbeforeshow($table)
	{
		$this->month	= substr($this->post('dt1',date('Y-m')),0,7);
		$key	= $this->post('key');
		$iskq	= $this->post('iskq','1');
		$s 		= m('admin')->monthuwhere($this->month, 'b.');
		if($iskq=='1')$s.=" and b.`iskq`=$iskq";
		if(!isempt($key))$s.=" and (b.`name` like '%$key%' or b.`deptname` like '%$key%')";
		$fields = 'b.name,b.deptname,b.ranking';
		$table  = '`[Q]userinfo` b';
		return array('where'=>$s,'table'=>$table, 'fields'=>$fields);
	}
	public function kqanayallaftershow($table, $rows)
	{
		$barr 	= array();
		$kq 	= m('kaoqin');
		$dtobj 	= c('date');
		
		$barr[] = array(
			'dt1_0' => '上班',
			'dt1_1' => '下班',
			'dt2_0' => '上班',
			'dt2_1' => '下班',
		);
		
		foreach($rows as $k=>&$rs){
			$rs['dt1_0'] = '正常';
			$rs['dt1_1'] = '正常';
			
			$rs['dt2_0'] = '未打卡';
			$rs['dt2_1'] = '未打卡';
			
			$barr[] = $rs;
		}
		return array('rows'=>$barr);
	}
	
	
	//个人考勤数据库
	public function getmyanaykqAjax()
	{
		$uid 	= (int)$this->post('uid', $this->adminid);
		$month 	= $this->post('month');
		$kq 	= m('kaoqin');
		$barr 	= $kq->getanay($uid, $month);
		$barrs	=  $toarr	= array();
		foreach($barr as $dt=>$dtrows){
			$str = '';
			foreach($dtrows as $k=>$rs){
				$iswork = $rs['iswork'];
				$state	= $rs['state'];
				
				if($iswork==1 && isempt($rs['states'])){
					if(!isset($toarr[$state]))$toarr[$state]=0;
					$toarr[$state]++;
				}
				$s   = $kq->getkqstate($rs);
				$str.= ''.$rs['ztname'].'：'.$s.'';
				$str.= '<br>';
				if($iswork==0)$str='<font color="#aaaaaa">'.$str.'</font>';
			}
			$barrs[$dt] = $str;
		}
		$barrs['total']	= $toarr;
		$this->returnjson($barrs);
	}
	public function reladanaymyAjax()
	{
		$uid 	= (int)$this->post('uid', $this->adminid);
		$month 	= $this->post('month');
		m('kaoqin')->kqanaymonth($uid, $month);
	}
	
	
	
	
	
	
	
	
	
	
	//考勤统计
	public function kqtotalbeforeshow($table)
	{
		$dt1			= $this->post('month', date('Y-m'));
		$iskq			= $this->post('iskq','1');
		$iskq			= $this->post('iskq','1');
		$this->months 	= $dt1;
		$key	= $this->post('key');
		$atype	= $this->post('atype');
		$receid	= $this->post('receid');
		$s 		= m('admin')->monthuwhere($dt1);
		
		//下属,userinfo下的
		if($atype=='down'){
			$s  .= 'and '.m('admin')->getdownwheres('id', $this->adminid, 0);
		}
		
		if($atype=='my'){
			$s = 'and id='.$this->adminid.'';
		}else{
			if($iskq=='1')$s.=" and `iskq`=$iskq";
			if(ISMORECOM)$s.=" and `companyid`=".m('admin')->getcompanyid()."";
		}
		if(isempt($receid)){
			if(!isempt($key))$s.=" and (`name` like '%$key%' or `ranking` like '%$key%' or `deptallname` like '%$key%')";
		}else{
			$ofval = m('admin')->gjoin($receid,'', 'all');
			if(!$ofval)$ofval='0';
			$s	.= ' and `id` in ('.$ofval.')';
		}
		
		$fields = 'id,name,deptname,ranking,workdate,state';
		return array('where'=>$s,'fields'=>$fields,'order'=>'`id`');
	}
	
	public function kqtotalaftershow($table, $rows)
	{
		$zta 	= m('flow:userinfo');
		$pnum	= $this->post('pnum');
		$colalls= array();
		foreach($rows as $k=>$rs){
			if($rs['state']==5)$rows[$k]['ishui']=1;
			$rows[$k]['state'] = $zta->getuserstate($rs['state']);
		}
		$kqobj 	= m('kaoqin');
		$barr 	= $kqobj->alltotalrows($this->months, $rows);
		$rows 	= $barr['rows'];
		$darr 	= array();
		//读取表头
		if($pnum=='all'){
			$dt 	= $this->months.'-01';
			//获取每天考勤几个状态
			$nuuid	= $this->adminid;
			if($rows)$nuuid = $rows[0]['id'];
			$sbarr	= $kqobj->getsbarr($nuuid, $dt);
			$lenz 	= count($sbarr); //每天考勤几个状态
			$touar 	= array();
			
			$max 	= $kqobj->dtobj->getmaxdt($this->months);
			for($i=1;$i<=$max;$i++){
				$xq = $kqobj->dtobj->cnweek($this->months.'-'.$i.'');
				for($j=0;$j<$lenz;$j++){
					$dataIndex = 'dt'.$i.'_'.$j.'';
					$colalls[] = array(
						'text' => ''.$i.'('.$xq.')',
						'dataIndex' => $dataIndex, //字段名
						'colspan' => $lenz
					);
					$touar[$dataIndex] = $sbarr[$j]['name'];
				}
			}
			
			$darr[] = $touar;
			
			//读取人员考勤状态
			foreach($rows as $k=>$rs){
				$uid 	= $rs['id'];
				$kqarr 	= $kqobj->getanay($uid, $this->months);
				for($i=1;$i<=$max;$i++){
					$oi = $i<10?'0'.$i.'':$i;
					$dt = $this->months.'-'.$oi.'';
					if(isset($kqarr[$dt]))foreach($kqarr[$dt] as $j=>$rs1){
						$dataIndex = 'dt'.$i.'_'.$j.'';
						$rs[$dataIndex] = $kqobj->getkqstate($rs1); //考勤状态
					}
				}
				$darr[] = $rs;
			}
		}else{
			$darr = $rows;
		}
		
		
		$barr['colalls'] = $colalls;
		$barr['rows'] 	 = $darr;
		
		
		
		return $barr;
	}
	
	
	
	/**
	*	批量导入打卡记录(2017-08-22)弃用
	*/
	public function addpldkjlAjax()
	{
		$val = $this->post('val');
		if(isempt($val))backmsg('error');
		$arrs 	= explode("\n", $val);
		$oi 	= 0;$uarr = array();
		$dtobj 	= c('date');$adb 	= m('admin');$db = m('kqdkjl');
		foreach($arrs as $valss){
			$name = '';
			$dkdt = '';
			$uid  = 0;
			if(!isempt($valss)){
				$a 		= $this->adtewe(explode('	', $valss),2);
				$name 	= $a[0];
				$dkdt 	= $a[1];
			}
			if(!isempt($name) && !isempt($dkdt)){
				$dkdt	 = str_replace('/','-', $dkdt);
				if(!$dtobj->isdate($dkdt))continue;
				if(isset($uarr[$name])){
					$uid = $uarr[$name];
				}else{
					$usar 	= $adb->getrows("`name`='$name'",'id');
					if($this->db->count!=1)continue;
					$uid	= $usar[0]['id'];
					$uarr[$name] = $uid;
				}
				if($db->rows("`uid`='$uid' and `dkdt`='$dkdt'")>0)continue;
				$oi++;
				$db->insert(array(
					'uid'	=> $uid,
					'dkdt'	=> $dkdt,
					'optdt'	=> $this->now,
					'type'	=> 5
				));
			}
		}
		backmsg('','成功导入'.$oi.'条数据');
	}
	private function adtewe($a, $len){
		for($i=0;$i<$len;$i++){
			if(!isset($a[$i]))$a[$i] = '';
		}
		return $a;
	}
	
	public function savaweizzAjax()
	{
		$id = (int)$this->post('id');
		$uarr['location_x'] = $this->post('x');
		$uarr['location_y'] = $this->post('y');
		$uarr['scale'] 		= $this->post('zoom');
		m('kqdw')->update($uarr, $id);
	}
	
	
	
	
	
	
	
	
	public function locationAction()
	{
		$id = (int)$this->get('id');
		
		if($id>0){
			$rs = m('location')->getone($id);
			if(!$rs)exit('not found record');
			if($rs['scale']<=0)$rs['scale']=12;
			$rs['content'] = '地址：'.$rs['label'].'<br>定位时间：'.$rs['optdt'].'';
			$rs['type'] = 0;
		}else{
			$info = $this->get('info');
			if(!$info)exit('not found info');
			$arr = explode(',', $this->jm->base64decode($info));
			$rs['precision'] = 0;
			$rs['location_x'] = $arr[0];
			$rs['location_y'] = $arr[1];
			$rs['scale'] = $arr[2];
			$rs['type'] = 1;
			$rs['content'] = arrvalue($arr,3);
		}
		if($this->rock->ismobile())$rs['type'] = 1;
		$this->smartydata['rs'] = $rs;
		$this->smartydata['qqmapkey']	= getconfig('qqmapkey','55QBZ-JGYLO-BALWX-SZE4H-5SV5K-JCFV7');
	}
	
	public function locationchangeAction()
	{
		$callback 	= $this->get('callback');
		$location_x = $this->get('location_x','24.528153');
		$location_y = $this->get('location_y','118.167806');
		$scale 		= $this->get('scale',12);
		$this->assign('callback', $callback);
		$this->assign('location_x', $location_x);
		$this->assign('location_y', $location_y);
		$this->assign('scale', $scale);
		$this->smartydata['qqmapkey']	= getconfig('qqmapkey','55QBZ-JGYLO-BALWX-SZE4H-5SV5K-JCFV7');
	}
	
	//删除打卡记录
	public function deldkjlAjax()
	{
		$sid = $this->post('id');
		//m('kqdkjl')->delete('id in('.$sid.')');
		$this->showreturn('');
	}
	
	
	
	
	
	
	//排班读取人员
	public function pbkqdistbefore($table)
	{
		$pblx	= (int)$this->post('pblx',0);//0查看,1组,2人员
		
		$dt1			= $this->post('dt1', date('Y-m'));
		$this->months 	= $dt1;
		
		//根据组
		if($pblx==1){
			$where1 = '';
			if(ISMORECOM)$where1='and `companyid` in(0,'.m('admin')->getcompanyid().')';
			return array(
				'table' => '`[Q]group`',
				'where' => $where1
			);
		}
		
		
		$key	= $this->post('key');
		
		$atype	= $this->post('atype');
		$s 		= m('admin')->monthuwhere($dt1,'a.');
		if($atype=='my'){
			$s = 'and a.`id`='.$this->adminid.'';
		}else{
			if(ISMORECOM)$s.='and a.`companyid`='.m('admin')->getcompanyid().'';
		}
		
		if(!isempt($key))$s.=" and (a.`name` like '%$key%' or a.`ranking` like '%$key%' or a.`deptname` like '%$key%')";
		$table  = "[Q]userinfo a left join `[Q]admin` b on a.id=b.id";
		
		$fields = 'a.id,a.name,a.deptname,a.ranking,a.workdate,a.state';
		return array(
			'where' =>$s,
			'fields'=>$fields,
			'order'=>'b.`sort`,a.`id`',
			'table'=> $table
		);
	}
	
	public function pbkqdistafter($table, $rows)
	{
		$zta 	= m('flow:userinfo');
		$maxjg	= c('date')->getmaxdt($this->months);
		$kqobj  = m('kaoqin');
		$pblx	= $this->post('pblx','0');
		
		//人员的
		if($pblx=='0'){
			foreach($rows as $k=>$rs){
				if($rs['state']==5)$rows[$k]['ishui']=1;
				$rows[$k]['state'] = $zta->getuserstate($rs['state']);
				$uid = $rs['id'];
				
				for($i=1;$i<=$maxjg;$i++){
					$oi  	= ($i<10) ? '0'.$i.'' : $i;
					$dt 	= $this->months.'-'.$oi;
					$zt 	= '';
					$iswork = $kqobj->isworkdt($uid, $dt);
					if($iswork==1){
						$zt = $kqobj->getdistid($uid, $dt);
					}
					$rows[$k]['day'.$i.''] = $zt;
				}
			}
		}
		
		//组的
		if($pblx=='1' || $pblx=='2'){
			$gset = $this->db->getall("select * from `[Q]kqdisv` where `dt` like '".$this->months."%' and `plx`=".$pblx." order by `type`");
			$setar= array();
			foreach($gset as $k=>$rs){
				$key = 'a'.$rs['dt'].'_'.$rs['receid'].'_'.$rs['type'].'';
				$setar[$key] = $rs['mid'];
			}
			
			foreach($rows as $k=>$rs){
				if($pblx=='1')$rows[$k]['deptname']='组';
				for($i=1;$i<=$maxjg;$i++){
					$oi  	= ($i<10) ? '0'.$i.'' : $i;
					$dt 	= $this->months.'-'.$oi;
					$key1 = 'a'.$dt.'_'.$rs['id'].'_1';//休息
					$key2 = 'a'.$dt.'_'.$rs['id'].'_2';//工作日
					$key0 = 'a'.$dt.'_'.$rs['id'].'_0'; //考勤
					$iswork = 1;
					$zt 	= '';
					if(isset($setar[$key1]))$iswork=0;
					if(isset($setar[$key2]))$iswork=1;//有设置工作日就是工作日
					if($iswork==1){
						$zt = arrvalue($setar, $key0,'0');
					}
					
					$rows[$k]['day'.$i.''] = $zt;
				}
			}
		}
		
		
		//读取考勤时间规则
		$gzrows = m('kqsjgz')->getall('pid=0','`id`,`name`','`sort`');
		
		return array(
			'rows' => $rows,
			'maxjg'=> $maxjg,
			'week' => date('w', strtotime($this->months.'-01')),
			'gzrows'=> $gzrows
		);
	}
	
	//排班标识保存
	public function setpaibanAjax()
	{
		$len 	= (int)$this->post('len','0');
		$db 	= m('kqdisv');
		for($i=0;$i<$len;$i++){
			$dt  = date('Y-m-d',strtotime($this->post('dt_'.$i.'')));
			$mid = $this->post('mid_'.$i.'');
			$plx = $this->post('plx_'.$i.'');//1组,2人员
			$receid = $this->post('receid_'.$i.'');
			$lx = (int)$this->post('type_'.$i.'','0');
			
			$type = 0;//考勤规则
			
			//设置休息日 取消休息日
			if($lx==0 || $lx==1){
				$type = 1;
			}
			//设置工作日 取消工作日
			if($lx==2 || $lx==3){
				$type = 2;
			}
			$where = "`plx`='$plx' and `receid`='$receid' and `dt`='$dt'";
			if($type==0)$where.=" and `type`='$type'";
			if($lx==1 || $lx==3 || $lx==5){
				$db->delete($where);
			}else{
				if($db->rows($where)==0){
					$db->insert(array(
						'plx' => $plx,
						'receid' => $receid,
						'dt' => $dt,
						'type' => $type,
						'mid' => $mid,
					));
				}else{
					$db->update('`mid`='.$mid.',`type`='.$type.'', $where);
				}
			}
		}
	}
	
	//自动添加年假
	public function addnianjiaAjax()
	{
		$dt 	= $this->get('dt');
		$barr	= m('flow:leave')->autoaddleave($dt);
		return '共添加'.count($barr).'人';
	}
	
	
	//剩余假期统计
	public function kqtotalafterjiashow($table, $rows)
	{
		$zta 	= m('flow:userinfo');
		$dt 	= $this->post('month');
		$kqkind	= $this->option->getdata('kqkind', "and `name`<>'增加调休'");
		$kq 	= m('kaoqin');
		
		foreach($rows as $k=>$rs){
			if($rs['state']==5)$rows[$k]['ishui']=1;
			$rows[$k]['state'] = $zta->getuserstate($rs['state']);
	
			foreach($kqkind as $k1=>$rs1){
				$tosss = $kq->getqjsytime($rs['id'], str_replace('增加','', $rs1['name']), $dt);
				if($tosss==0)$tosss='';
				$rows[$k]['total'.$k1.''] = $tosss;
			}
			$tosss = $kq->getqjsytime($rs['id'], '调休', $dt);
			if($tosss==0)$tosss='';
			$rows[$k]['tiaoxiu'] = $tosss;
		}
		return array(
			'rows'=> $rows,
			'kqkind'=> $kqkind,
		);
	}
	
	public function updateenddtAjax()
	{
		$to	= m('flow:leave')->updateenddt();
		return '更新成功';
	}
	
	public function kqtotalmxbefore($table)
	{
		$uid 	= (int)$this->post('uid');
		$qjkind = $this->post('qjkind');
		$this->optuid 		= $uid;
		$this->optqjkind 	= $qjkind;
		$where 	= 'and `uid`='.$uid.'';
		$this->optkind		= '';
		$this->optkinds		= '增加'.$qjkind.'';
		if($qjkind=='调休'){
			$this->optkind = '加班';
			$whera  = "((`kind`='$this->optkind' and `jiatype`=0) or (`kind`='$this->optkinds'))";
			$where .= " and ((`qjkind`='$qjkind' and `status` in(0,1)) or (`status`=1 and $whera))";
		}else{
			$this->optkind = $this->optkinds;
			$where .= " and ((`qjkind`='$qjkind' and `status` in(0,1)) or (`kind`='$this->optkind' and `status`=1))";
		}
		return array(
			'where' => $where,
			'order' => '`stime`'
		);
	}
	
	public function kqtotalmxafter($table, $rows)
	{
		$urs = m('userinfo')->getone($this->optuid);
		foreach($rows as $k=>$rs){
			if($urs){
				$rows[$k]['uname'] = $urs['name'];
				$rows[$k]['deptname'] = $urs['deptname'];
			}
			
			if(!isempt($rs['enddt'])){
				$rows[$k]['etime'] = $rs['enddt']; //截止时间
				if($rs['enddt']<$this->rock->now)$rows[$k]['ishui'] = 1;
			}else{
				if($rs['kind']==$this->optkind || $rs['kind']==$this->optkinds)$rows[$k]['etime'] = '';
			}
		}
		$kqkind	= $this->option->getdata('kqkind',"and `name`<>'增加调休'");
		if($rows){
			$rows[] = array(
				'deptname' => '合计',
				'totals1'	=> m('kaoqin')->getqjsytime($this->optuid, $this->optqjkind)
			);
		}
		return array(
			'rows' 		=> $rows,
			'kqkind' 	=> $kqkind,
		);
	}
}