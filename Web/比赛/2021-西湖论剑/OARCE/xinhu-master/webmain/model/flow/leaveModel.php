<?php
//请假条的
class flow_leaveClassModel extends flowModel
{
	
	public function flowrsreplace($rs)
	{
		$rs['modenum']  = $this->modenum;
		$totday			= floatval(arrvalue($rs,'totday','0'));
		if($totday>0)$rs['totals'].='('.$totday.'天)';
		return $rs;
	}

	protected function flowbillwhere($uid, $lx)
	{
		$month	= $this->rock->post('month');
		$where 	= '';
		if($month!=''){
			$where.=" and `stime` like '$month%'";
		}

		return array(
			'where' => $where
		);
	}
	
	/**
	*	年假添加设置(自动添加)，可计划任务没有运行一次，兑换为小时的，默认一天8小时
	*/
	public function autoaddleave($ndate='')
	{
		$type = 0; //根据哪个类型计算年：0根据入职日期，1根据转正日期
		$hour = (int)m('option')->getval('kqsbtime', 8); //默认一天8小时(请自己设定)
		if($hour<=0)$hour = 8;
		if($ndate=='')$ndate = $this->rock->date;
		if($ndate > $this->rock->date)return array();
		$Y  	= substr($ndate,0,4);
		$niana	= array(
			//	 开始   截止    年假天数
			array(0, 	0, 		0), //0-0年，0天 
			array(1, 	10, 	5), //1年(含)-10年(含)，5天 
			array(11, 	20, 	10), //11年(含)-20年(含)，10天 
			array(21, 	9999, 	15), //21年(含)以上，15天 
		);
		//配置可根据自己情况修改
		$adlx = array('workdate','positivedt');
		$adln = array('入职','转正');
		$dtobj= c('date');
		
		$usea = $this->db->getall("select `uid` from `[Q]kqinfo` where `kind`='增加年假' and `status`=1 and `optname`='系统' and `stime` like '".$Y."-%'"); //系统已经自动添加过
		$uids = '0';
		foreach($usea as $k=>$rs)$uids.=','.$rs['uid'].'';
		$rows = $this->db->getall("select a.`id`,a.`name`,a.`workdate`,b.`positivedt` from `[Q]admin` a left join `[Q]userinfo` b on a.id=b.id where a.`status`=1 and a.id not in($uids) and b.`state`<>5");
		$barr 	= array();
		foreach($rows as $k=>$rs){
			$dt = $rs[$adlx[$type]];
			if(isempt($dt))continue;
			$dttime = strtotime($dt);
			$rs['dt'] = $dt;
			$jg = $dtobj->datediff('d', $dt, $ndate);
			$yea= (int)($jg/365); //年限 
			if($yea==0)continue;//未满1年
			$nianday	= 0;	//年假条数
			foreach($niana as $k1=>$ns){
				if($yea>=$ns[0] && $yea<=$ns[1]){
					$nianday = $ns[2];
					break;
				}
			}
			$dt 	= date(''.$Y.'-m-d', $dttime);
			if(strtotime($dt) > strtotime($ndate) )continue;//还没到对应日期
			
			$rs['nianday']  = $nianday;
			$rs['nyear'] 	= $yea; //入职年限
			$rs['stime']  	= $dt.' 00:00:00';
			$rs['etime']  	= $dt.' 23:59:59';
			$rs['nianhour'] = $nianday * $hour; //小时
			$barr[] = $rs;
		}
		
		//添加到kqinfo表上
		$dbs = m('kqinfo');
		foreach($barr as $k=>$rs){
			$uarr['uid'] = $rs['id'];
			$uarr['uname'] = $rs['name'];
			$uarr['stime'] = $rs['stime'];
			$uarr['etime'] = $rs['etime'];
			$uarr['kind'] = '增加年假';
			$uarr['status'] = '1';
			$uarr['totals'] = $rs['nianhour'];
			$uarr['optdt'] = $this->rock->now;
			$uarr['isturn'] = '1';
			$uarr['optname'] = '系统';
			$uarr['optid'] = '0';
			$uarr['applydt'] = $this->rock->date;
			$uarr['totday'] = $rs['nianday'];
			$uarr['explain'] = ''.$rs['dt'].''.$adln[$type].'年限满'.$rs['nyear'].'年添加年假'.$rs['nianday'].'天';
			$dbs->insert($uarr);
		}
		$this->updateenddt();
		return $barr;
	}
	
	/**
	*	更新年假/加班单的截止时间
	*/
	public function updateenddt()
	{
		$dbs 	= m('option');
		$jbuse 	= (int)$dbs->getval('kqjiabanuse', 0); //加班
		$njuse 	= (int)$dbs->getval('kqnianjiause', 0);
		$db 	= m('kqinfo');
		if($jbuse>0){
			$key  = "CONCAT(date_format(date_add(stime,interval ".$jbuse." month),'%Y-%m-%d'),' ','23:59:59')";
			$db->update('enddt='.$key.'',"`kind`='加班' and `enddt` is null"); //兑换调休的
		}
		
		if($njuse>0){
			$key  = "CONCAT(date_format(date_add(stime,interval ".$njuse." month),'%Y-%m-%d'),' ','23:59:59')";
			$db->update('enddt='.$key.'',"`kind`='增加年假' and `enddt` is null");
		}
	}
}