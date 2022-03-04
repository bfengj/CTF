<?php
class fworkClassAction extends Action
{
	
	/**
	*	流程申请获取数组
	*/
	public function getmodearrAjax()
	{
		$rows = m('mode')->getmoderows($this->adminid,'and islu=1');
		$row  = array();
		$viewobj = m('view');
		foreach($rows as $k=>$rs){
			$lx = $rs['type'];
			if(!$viewobj->isadd($rs, $this->adminid))continue;
			if(!isset($row[$lx]))$row[$lx]=array();
			$row[$lx][] = $rs;
		}
		$this->returnjson(array('rows'=>$row));
	}
	
	/**
	*	单据查看获取数组
	*/
	public function getmodesearcharrAjax()
	{
		$rows = m('mode')->getmoderows($this->adminid);
		$row  = array();
		$mid  = '0';
		foreach($rows as $k=>$rs){
			$path = ''.P.'/flow/page/rock_page_'.$rs['num'].'.php';
			if(!file_exists($path) || $rs['isscl']==0)continue;
			$lx = $rs['type'];
			$mid.=','.$rs['id'].'';
			$row[$lx][] = $rs;
		}
		if($mid!='0'){
			$where 	= m('admin')->getjoinstr('syrid', $this->adminid, 1);
			$wrows 	= m('flow_where')->getrows('`setid` in('.$mid.') and `status`=1 and `islb` and `num` is not null and ('.$where.') and `pnum` is null group by `setid`','`setid`,min(sort),`num`');
			$atypea = array();
			foreach($wrows as $k1=>$rs1){
				$nus = $rs1['setid'];
				if(!isset($atypea[$nus]))$atypea[$nus] = $rs1['num'];
			}
			foreach($row as $lx=>$rowaa){
				foreach($rowaa as $k2=>$rs2){
					$row[$lx][$k2]['atype'] = $this->rock->arrvalue($atypea, $rs2['id']);
				}
			}
		}
		$this->returnjson(array('rows'=>$row));
	}
	
	
	
	
	
	public function flowbillbefore($table)
	{
		$lx 	= $this->post('atype');
		$this->atypess = $lx;
		$dt 	= $this->post('dt1');
		$dt2 	= $this->post('dt2');
		$key 	= $this->post('key');
		$zt 	= $this->post('zt');
		$modeid = (int)$this->post('modeid','0');
		$uid 	= $this->adminid;
		$where	= 'and (a.`uid`='.$uid.' or a.`optid`='.$uid.')';
		//待办
		if($lx=='daib'){
			$where	= 'and a.`isturn`=1 and  a.`status` not in(1,2) and '.$this->rock->dbinstr('a.nowcheckid', $uid);
		}
		
		//我下属申请
		if($lx=='xia'){
			$where	= 'and a.`isturn`=1 and  '.$this->rock->dbinstr('b.superid', $uid);
		}
		
		//我参与
		if($lx=='jmy'){
			$where	= 'and a.`isturn`=1 and  '.$this->rock->dbinstr('a.allcheckid', $uid);
		}
		
		//未通过
		if($lx=='mywtg'){
			$where.=" and a.status=2";
		}
		
		//待提交
		if($lx=='daiturn'){
			$where.=" and a.`status` not in(5) and a.`isturn`=0 "; //未提交
		}
		
		//异常
		if($lx=='error'){
			$whers = m('flowbill')->errorwhere('a.');
			$where = ' and '.$whers.'';
		}
		
		
		//授权单据查看
		if($lx=='grantview'){
			$where =' and 1=2';
			if($modeid>0){
				$moders	= m('flow_set')->getone($modeid);
				$where 	= m('view')->viewwhere($moders, $uid);
			}
		}
		
		$this->modeids	= false;
		
		//抄送的
		if($lx=='chaosview'){
			$where =' and 1=2';
			$crows = $this->db->getall("select * from `[Q]flow_chao` where ".$this->rock->dbinstr('csnameid', $uid)."");
			$this->modeids = '0';
			if($crows){
				$modeids = '';
				$mids 	 = '';
				foreach($crows as $k1=>$rs1){
					$modeids.=','.$rs1['modeid'].'';
					$mids.=','.$rs1['mid'].'';
				}
				$this->modeids = substr($modeids,1);
				$where = " and a.`isturn`=1 and a.`modeid` in(".$this->modeids.") and a.`mid` in(".substr($mids,1).")";
			}
		}
		
		//流程监控
		if($lx=='jiankong'){
			$where =' and 1=2';
			$this->modeids = '0';
			if($modeid==0){
				$rows = m('view')->getjilu($this->adminid);
				foreach($rows as $k1=>$rs1){
					$this->modeids.=','.$rs1['modeid'].'';
				}
			}else{
				$wwhere = m('view')->jiankongwhere($modeid, $this->adminid);//返回主表的条件
				$wwhere = str_replace('{asqom}','', $wwhere);
				$moders = $this->db->getone('[Q]flow_set', $modeid);
				$where =' and `mid` in(select `id` from `[Q]'.$moders['table'].'` where 1=1 '.$wwhere.')';
			}
		}
		
		if($zt!=''){
			if($zt!='6'){
				$where.=" and a.`status`='$zt'";
				if($zt!='5')$where.=' and a.`isturn`=1';
			}else{
				$where.=" and a.`status` not in(5) and a.`isturn`=0 "; //未提交
			}
		}
		if($dt!='')$where.=" and a.`applydt`>='$dt'";
		if($dt2!='')$where.=" and a.`applydt`<='$dt2'";
		
		if($modeid>0)$where.=' and a.modeid='.$modeid.'';
		if(!isempt($key))$where.=" and (b.`name` like '%$key%' or b.`deptname` like '%$key%' or a.`sericnum` like '$key%' or a.`nowcheckname`='$key' or a.`modename`='$key')";
		

		
		return array(
			'table' => '`[Q]flow_bill` a left join `[Q]admin` b on a.uid=b.id',
			'where' => " and a.isdel=0 $where",
			'fields'=> 'a.*,b.name,b.deptname',
			'order' => 'a.optdt desc'
		);
	}
	
	public function flowbillafter($table, $rows)
	{
		$rows = m('flowbill')->getbilldata($rows);
		$flowarr = array();
		if($this->atypess!='error'){
			if($this->modeids===false){
				$flowarr = m('mode')->getmodemyarr($this->adminid);
			}else{
				$flowarr = m('mode')->getmodemyarr(0,'and `id` in('.$this->modeids.')');
			}
		}else if($rows){
			foreach($rows as $k=>$rs){
				$errorsm	= '';
				$chuli 		= '到[流程模块→流程审核步骤]下对应的步骤设置审核人';
				$errtype	= 0;//有步骤没审核人
				if(isempt($rs['nowcheckid'])){
					if($rs['nowcourseid']=='0'){
						$errorsm = '<font color=blue>当前没有审核步骤</font>';
						$chuli 		= '到[流程模块→流程单据查看]删除最后一条处理记录，然后[重新匹配流程]';
						$errtype	= 1; //没有步骤
					}else{
						$errorsm = '<font color=red>当前没有审核人</font>';
					}
				}else{
					$errorsm = '<font color=#800000>审核人帐号已停用</font>';
					$errtype	= 2; //人员停用
				}
				$rows[$k]['errorsm'] = $errorsm;
				$rows[$k]['chuli'] 	 = $chuli;
				$rows[$k]['errtype'] = $errtype;
			}
		}
		return array(
			'rows'		=> $rows,
			'flowarr' 	=> $flowarr
		);
	}
	
	
	public function flowtodosbefore($table)
	{
		$dt 	= $this->post('dt1');
		$key 	= $this->post('key');
		$zt 	= $this->post('zt');
		$modenum= $this->post('modeid');
		$uid 	= $this->adminid;
		$where	= 'and `uid`='.$uid.'';
		if(!isempt($modenum))$where.=" and `modenum`='$modenum'";
		if(!isempt($dt))$where.=" and `adddt` like '$dt%'";
		
		return array(
			'where' => $where,
			'order' => '`adddt` desc'
		);
	}
	public function flowtodosafter($table, $rows)
	{
		$nums = "''";
		$mors = $this->db->getall('select `modenum` from `[Q]flow_todos` where `uid`='.$this->adminid.' group by `modenum`');
		foreach($mors as $k=>$rs)$nums.=",'".$rs['modenum']."'";
		$flowarr = m('mode')->getrows("`status`=1 and `num` in($nums)",'`num`,`name`,`summary`,`type`','sort');
		$modearr = array();
		foreach($flowarr as $k=>$rs){
			$modearr[$rs['num']] = $rs['summary'];
		}
		if($rows){
			foreach($rows as $k=>$rs){
				//$rows[$k]['id'] = $rs['mid'];
				$rers 			= $this->db->getone('[Q]'.$rs['table'].'', $rs['mid']);
				$summary		= '';
				if($rers){
					$summary	= $this->rock->reparr(arrvalue($modearr, $rs['modenum']), $rers);
					$rows[$k]['optdt']   = arrvalue($rers,'optdt');
					$rows[$k]['optname'] = arrvalue($rers,'optname');
				}
				$rows[$k]['summary'] = $summary;
				if($rs['isread']=='1'){
					$rows[$k]['ishui']  = 1;
					$rows[$k]['isread'] = '<font color="#888888">已读</font>';
				}else{
					$rows[$k]['isread'] = '<font color="red">未读</font>';
				}
			}
		}
		return array(
			'rows'		=> $rows,
			'flowarr' 	=> $flowarr
		);
	}
	
	public function meetqingkbefore($table)
	{
		$pid = $this->option->getval('hyname','-1', 2);
		return array(
			'where' => "and `pid`='$pid'",
			'order' => 'sort',
			'field' => 'id,name',
		);
	}
	
	public function meetqingkafter($table, $rows)
	{
		$dtobj 		= c('date');
		$startdt	= $this->post('startdt', $this->date);
		$enddt		= $this->post('enddt');
		if($enddt=='')$enddt = $dtobj->adddate($startdt,'d',7);
		$jg 		= $dtobj->datediff('d',$startdt, $enddt);
		if($jg>30)$jg = 30;
		$flow 		= m('flow:meet');
		$data 		= m('meet')->getall("`status`=1 and `type`=0 and `startdt`<='$enddt 23:59:59' and `enddt`>='$startdt' order by `startdt` asc",'hyname,title,startdt,enddt,state,joinname,optname,id');
		$datss 		= array();
		foreach($data as $k=>$rs){
			$rs 	= $flow->flowrsreplace($rs);
			$key 	= substr($rs['startdt'],0,10).$rs['hyname'];
			if(!isset($datss[$key]))$datss[$key] = array();
			$str 	= '['.substr($rs['startdt'],11,5).'→'.substr($rs['enddt'],11,5).']'.$rs['title'].'('.$rs['joinname'].') '.$rs['state'].'';
			$datss[$key][] = $str;
		}
		
		$columns	= $rows;
		$barr 		= array();
		$dt 		= $startdt;
		for($i=0; $i<=$jg; $i++){
			if($i>0)$dt = $dtobj->adddate($dt,'d',1);
			$w 		= $dtobj->cnweek($dt);
			$status	= 1;
			if($w=='六'||$w=='日')$status	= 0;
			$sbarr	= array(
				'dt' 		=> '星期'.$w.'<br>'.$dt.'',
				'status' 	=> $status
			);
			foreach($rows as $k=>$rs){
				$key 	= $dt.$rs['name'];
				$str 	= '';
				if(isset($datss[$key])){
					foreach($datss[$key] as $k1=>$strs){
						$str.= ''.($k1+1).'.'.$strs.'<br>';
					}
				}
				$sbarr['meet_'.$rs['id'].''] = $str;
			}
			$barr[] = $sbarr;
		}
		$arr['columns'] = $columns;
		$arr['startdt'] = $startdt;
		$arr['enddt'] 	= $enddt;
		$arr['rows'] 	= $barr;
		$arr['totalCount'] 	= $jg+1;
		
		return $arr;
	}
	
	public function deltodoAjax()
	{
		$id = $this->post('id','0');
		m('flow_todos')->delete('id in('.$id.') and `uid`='.$this->adminid.'');
		$this->backmsg();
	}
		
}