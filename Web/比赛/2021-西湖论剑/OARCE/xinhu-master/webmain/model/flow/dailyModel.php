<?php
class flow_dailyClassModel extends flowModel
{
	public function initModel()
	{
		$this->typearr = explode(',','日报,周报,月报,年报');
		$this->logobj = m('log');
	}
	
	protected function flowchangedata()
	{
		$this->rs['typess'] 	= $this->typearr[$this->rs['type']];
	}
	
	public function flowrsreplace($rs, $lx=0)
	{
		if($rs['mark']=='0')$rs['mark'] = '';
		$cfrom	= $this->rock->get('cfrom');
		if($lx==2){
			if($cfrom!='mweb'){
				if(isset($rs['optdt']))$rs['optdt']	= str_replace(' ','<br>', $rs['optdt']);
				if(isset($rs['adddt']))$rs['adddt']	= str_replace(' ','<br>', $rs['adddt']);
			}
			
			$zt = $this->logobj->isread('daily', $rs['id'], $this->adminid);
	
			if($zt>0)$rs['ishui'] = 1;
	
			
			$dt 	= $rs['dt'];
			if($rs['type']!=0 && !isempt($rs['enddt']) && $cfrom!='mweb'){
				$dt.='<br><font color="#aaaaaa">'.$rs['enddt'].'</font>';
			}
			$rs['dt'] = $dt;
		}
		$this->replacepbr($rs, 'content');
		$rs['type'] 		= $this->typearr[$rs['type']];
		return $rs;
	}
	
	//提交保存完日报通知上级
	protected function flowsubmit($na, $sm)
	{
		$uparr = m('admin')->getsuperman($this->uid);
		$recid = arrvalue($uparr, 0);
		if(!$recid || $recid==0)return;
		$typea = $this->typearr[$this->rs['type']];
		$title = ''.$this->rs['optname'].'的'.$typea.'';
		$cont  = c('html')->substrstr($this->rs['content'],0, 100);
		$this->push($recid, '', "".$typea."日期：{dt}\n".$cont, $title);
	}
	
	protected function flowaddlog($a)
	{
		if($a['name'] == '日报评分'){
			$fenshu	 = (int)$this->rock->post('fenshu','0');
			$this->push($this->rs['uid'], '', ''.$this->adminname.'评分你[{dt}]的{typess},分数('.$fenshu.')',''.$this->modename.'评分');
			$this->update(array(
				'mark' => $fenshu
			), $this->id);
		}
		if($a['name'] == '点评'){
			$this->nexttodo($this->uid, 'pinglun', $a['explain'], '点评');
		}
	}
	
	protected function flowdatalog($arr)
	{
		$ispingfen	= 0;
		$barr 		= m('admin')->getsuperman($this->uid); //获取我的上级主管
		if($barr){
			$hes 	= $barr[0];
			if(contain(','.$hes.',',','.$this->adminid.','))$ispingfen = 1; //是否可以评分
		}
		$arr['ispingfen'] 	= $ispingfen;
		$arr['mark'] 		= $this->rs['mark'];
		return $arr;
	}
	
	protected function flowgetoptmenu($opt)
	{
		//if($this->uid==$this->adminid)return false;
		$to = m('log')->isread($this->mtable, $this->id);
		return $to<=0;
	}
	
	protected function flowoptmenu($ors, $crs)
	{
		$table 	= $this->mtable;
		$mid	= $this->id;
		$uid	= $this->adminid;
		$lx 	= $ors['num'];
		$log 	= m('log');
		if($lx=='yd'){
			$log->addread($table, $mid, $uid);
		}
		if($lx=='allyd'){
			$ydid  = $log->getread($table, $uid);	
			$where = m('view')->viewwhere($this->modeid, $uid);
			$where = "((1=1 $where) or (`uid`='$uid') )";
			$where = "`id` not in($ydid) and $where";
			
			$rows 	= m($table)->getrows($where,'id');
			foreach($rows as $k=>$rs)$log->addread($table, $rs['id'], $uid);
		}
	}
	
	
	protected function flowprintrows($rows)
	{
		foreach($rows as $k=>$rs){
			$rows[$k]['plan_style']		= 'text-align:left';
			$rows[$k]['content']		= str_replace("\n",'<br>', $rs['content']);
			$rows[$k]['plan']			= str_replace("\n",'<br>', $rs['plan']);
			$rows[$k]['type']			= $this->typearr[$rs['type']];
		}
		return $rows;
	}
	
	//条件过滤已从流程模块条件下设置
	protected function flowbillwhere($uid, $lx)
	{
		$type 	= $this->rock->post('type');
		$key 	= $this->rock->post('key');
		$dt 	= $this->rock->post('dt');
		$where 		= '';
		$keywhere 	= '';
		

		if(!isempt($type))$where.=" and a.`type`='$type'";
		if(!isempt($dt))$where.=" and a.`dt` like '$dt%'";
		
		if(!isempt($key))$keywhere=m('admin')->getkeywhere($key, 'b.', "or a.`content` like '%$key%'");
		
		return array(
			'table' => '`[Q]daily` a left join `[Q]admin` b on a.`uid`=b.`id`',
			'fields'=> 'a.*,b.`name`,b.`deptname`',
			'where' => $where,
			'keywhere' => $keywhere,
			'asqom' => 'a.', //主表别名
			'order' => 'a.`optdt` desc'
		);
	}
	
	/**
	*	日报分析
	*/
	public function dailyanay($uid=0, $month='')
	{
		$dto	= c('date');
		if($month=='')$month = $this->rock->date;
		$mon	= substr($month,0, 7);
		$start 	= $mon.'-01';
		$enddt	= $dto->getenddt($mon);
		$jg		= $dto->getmaxdt($start);
		$dtarr  = $dailydt = $leavedt = $zhoubdt = array();
		for($i=1; $i<=$jg; $i++){
			$oi	= ''.$i.'';
			if($i<10)$oi= '0'.$i.'';
			$dt = $mon.'-'.$oi.'';
			if($dt>=$this->rock->date)break;
			$dtarr[] = array($dt, strtotime($dt));
		}
		$kql	= m('kaoqin');
		$dbfx	= m('dailyfx');
		$where  = m('admin')->monthuwheres($start, $enddt);
		if($uid!=0)$where="and `id`='$uid'";
		$urows	= m('userinfo')->getall("1=1 $where", 'id,name,workdate,quitdt,isdaily');
		
		//日报
		$dailya = $this->getall("`type`=0 and `dt` like '$mon%' group by `uid`,`dt`",'`uid`,`dt`');
		foreach($dailya as $k=>$rs){
			$dailydt['a'.$rs['uid'].'_'.$rs['dt'].''] = 1;
		}
		
		//周报
		$dailya = $this->getall("`type`=1 and `adddt` like '$mon%'",'`uid`,`adddt`');
		foreach($dailya as $k=>$rs){
			$zhoubdt['a'.$rs['uid'].'_'.substr($rs['adddt'],0,10).''] = 1;
		}
		
		
		//读取是不是全天请假(这种情况无法统计，全天请假写了2个上午和下午的请假条，下次改进)
		$qjarr = $this->db->getall("select `stime`,`etime`,`uid` from `[Q]kqinfo` where `status`=1 and `kind`='请假' and `etime`>='$start' and `stime`<='$enddt' ");
		if($qjarr){
			foreach($qjarr as $k=>$rs){
				$qjarr[$k]['stimes'] = strtotime($rs['stime']);
				$qjarr[$k]['etimes'] = strtotime($rs['etime']);
			}
			foreach($dtarr as $d1=>$dtss){
				$dt  = $dtss[0];
				foreach($qjarr as $k=>$rs){
					$uid  = $rs['uid'];
					$sbdt = $kql->getsbstr($uid, $dt);
					
					if($rs['stimes']<=$sbdt['stimes'] && $rs['etimes']>=$sbdt['etimes']){
						$leavedt['a'.$uid.'_'.$dt.''] = 1; //全天请假
					}
				}
			}
		}
		
		foreach($urows as $k=>$urs){
			$totaly = 0;//应写
			$totalx	= 0;//已写次数
			$totalw	= 0;//未写次数
			$dtjoin	= '';
			$uid	= $urs['id'];
			$dtarra	= array();
			$ruzd	= 0;$lzzt = 9999999999999;
			if(!isempt($urs['workdate']))$ruzd 	= strtotime($urs['workdate']);
			if(!isempt($urs['quitdt']))$lzzt 	= strtotime($urs['quitdt']);
			
			$uarr 	= array(
				'uid' 	=> $uid,
				'month' => $mon,
				'optdt' => $this->rock->now
			);
			foreach($dtarr as $d1=>$dtss){
				$dt  	= $dtss[0];
				$d 		= $d1+1;
				$zt  	= 0; //0未写,1已写,2请假,3休息日,4没入职或已离职,5不需要写日报,时间还没到,6写周报了
				
				//入职离职判断
				if($dtss[1]<$ruzd || $dtss[1]>$lzzt){
					$uarr['day'.$d.''] 	= 4;
					continue;
				}
				
				$keys 	= 'a'.$uid.'_'.$dt.'';
				
				$xbo 	= true;
				$iswork = $kql->isworkdt($uid, $dt);
				if($iswork==0){
					$zt = 3;
					if(isset($dailydt[$keys])){
						$zt = 1;
					}
				}else{
					
					if(isset($leavedt[$keys])){
						$zt = 2;
					}else{
						$totaly++;
					}
					if(isset($dailydt[$keys])){
						$zt = 1;
					}
					if($zt==0 && isset($zhoubdt[$keys])){
						$zt = 6;//写周报了
					}
					if($zt==0){
						if($urs['isdaily']==0){
							$zt = 5;
						}else{
							$totalw++;//没写没请假
						}
					}
				}
				$uarr['day'.$d.''] 	= $zt;
			}
			
			$totalx				= $totaly - $totalw;
			$uarr['totaly'] 	= $totaly;
			$uarr['totalx'] 	= $totalx;
			$uarr['totalw'] 	= $totalw;
			
			$where = "`uid`='$uid' and `month`='$mon'";
			if($dbfx->rows($where)==0)$where = '';
			
			$dbfx->record($uarr, $where);
		}
	}
	
	/**
	*	未写日报通知
	*	return 未写人员如：貂蝉(人事部),大乔(开发部)
	*/
	public function dailytodo($dt='')
	{
		if($dt=='')$dt = $this->rock->date;
		$dta = explode('-', $dt);
		$month = substr($dt, 0,7);
		$d 	 = (int)$dta[2];
		$rows= $this->db->getall("select a.`id`,a.`name`,a.`deptname`,b.`day".$d."` from `[Q]admin` a left join `[Q]dailyfx` b on a.`id`=b.`uid` and b.`month`='$month' where a.`status`=1 and b.`day".$d."`=0");
		$w	 = c('date')->cnweek($dt);
		$cont= '你昨天['.$dt.',周'.$w.']的'.$this->modename.'未写，请及时补充填写。';
		$receid = '';
		foreach($rows as $k=>$rs){
			$receid.=','.$rs['id'].'';
		}
		$this->flowweixinarr = array(
			'url' => $this->getwxurl()
		);
		if($receid!='')$this->push(substr($receid, 1),'', $cont, ''.$this->modename.'未写提醒');
	}
}