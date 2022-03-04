<?php
//模块 hrcheck.考核评分
class flow_hrcheckClassModel extends flowModel
{
	//审核完成处理
	protected function flowcheckfinsh($zt){
		//最后得分计算，
		if($zt==1){
			$fenzp = floatval($this->rs['fenzp']);
			$fensj = floatval($this->rs['fensj']);
			$fenrs = floatval($this->rs['fenrs']);
			
			//默认分数=自己评分*50% + 上级评分*25% + 人事评分*25%
			$fen   = $fenzp*0.5 + $fensj*0.25 + $fenrs*0.25; 
			
			//3个平均分
			//$fen 	= ($fenzp+$fensj+$fenrs)/3;

			
			$this->update(array('fen' => $fen), $this->id);		
		}
	}
	
	//需要评分就可以查看
	protected function flowisreadqx()
	{
		$boss = $this->getpfrows();
		if($boss)return true;
		return false;
	}
	
	private function getpfrows()
	{
		return m('hrcheckn')->getall('mid='.$this->id.' and `optid`='.$this->adminid.' and `optdt` is null','*', '`sort`');
	}
	
	public function flowrsreplace($rs, $lx=0)
	{
		if(isset($rs['abclex']))return $rs;
		$ztstr = '';
		if(isempt($rs['pfrens'])){
			$ztstr = '<font color=green>评分已完成</font>';
		}else{
			if($rs['uid']==$this->adminid){
				$ztstr = '待<font color=blue>'.$rs['pfrens'].'</font>评分';
			}else{
				$ztstr = '<font color=blue>待评分</font>';
			}
		}
		$rs['pfrenids'] = $ztstr;
		if($lx==1){
			$ckbo 		= $this->isreadqxs();//是否设置查看权限
			
			
			
			$ispf 		= $this->getpfrows();
			if(!$ispf){
				if($ckbo){ //设置可查看
					$rs['subdatastr'] = $this->getsubdatastr($this->id);
					$this->moders['isgbcy'] = 0;
					$this->moders['isgbjl'] = 0;
					
				}else{
					if($rs['uid']!=$this->adminid)$rs['fen']	= '-';
				}
			}else{
				$rs['fen']	= '-';
			}
		}
		if($rs['uid']!=$this->adminid && !contain($this->atype, 'all'))$rs['fen']='-';//自己只能看自己分
		return $rs;
	}
	
	public function getsubdatastr($id, $glx=0)
	{
		$srows 		= m('hrchecks')->getrows('`mid`='.$id.'','*','`sort`');
		$nrows 		= m('hrcheckn')->getrows('`mid`='.$id.'','*','`sort`');
		$pars 		= array();
		$fsarr		= array();
		foreach($nrows as $k2=>$rs2){
			$pars[$rs2['pfid']] = $rs2['pfname'].'<br>'.$rs2['optname'].'(权重'.$rs2['weight'].'%)';
			$fsarr['s'.$rs2['sid'].'_'.$rs2['pfid'].''] = $rs2['defen'];
		}
		$fenshu		= 0;
		$toarr 		= array();
		foreach($srows as $k1=>&$rs1){
			$rs1['xuhaos'] = $k1+1;
			
			foreach($pars as $k3=>$v3){
				$val = arrvalue($fsarr, 's'.$rs1['id'].'_'.$k3.'');
				if(!isset($toarr[$k3]))$toarr[$k3] = 0;
				$toarr[$k3]+=floatval($val);
				
				if($val==0)$val= '';
				$rs1['pfzd'.$k3.''] = $val; //得分
			}
			
			
			$fenshu+=floatval($rs1['fenshu']);
		}
		
		//统计的
		$tjarr = array();
		foreach($toarr as $k3=>$v1){
			if($v1==0)$v1='';
			$tjarr['pfzd'.$k3.''] = $v1;
		}
		$tjarr['itemname'] = '小计';
		$tjarr['fenshu'] = $fenshu;
		$srows[]= $tjarr;
		
		if($glx==1){
			return array(
				'data'=>$srows,
				'colums'=> $pars
			);
		}
		
		$herstr = 'xuhaos,,center@itemname,考核内容,left@fenshu,分值';
		foreach($pars as $k1=>$v1)$herstr.='@pfzd'.$k1.','.$v1.'';
		
		$pfrowsstr 	= c('html')->createrows($srows, $herstr,'#dddddd');
		
		return $pfrowsstr;
	}
	
	
	protected function flowinit()
	{
		$this->dtobj = c('date');
	}
	
	protected function flowbillwhere($uid, $lx)
	{
		$month = $this->rock->post('month');
		$where = '';
		if(!isempt($month)){
			$where="and a.`startdt` like '".$month."%'";
		}
		return array(
			'table'		=> '`[Q]hrcheck` a left join `[Q]userinfo` b on a.uid=b.id',
			'fields'	=> 'a.*,b.deptname',
			'asqom'		=> 'a.',
			'where'		=> $where,
			'orlikefields' => 'b.deptname'
		);
	}
	
	
	//是否可评分
	protected function flowdatalog($arr)
	{
		$pfrows = $this->getpfrows();
		foreach($pfrows as $k=>&$rs){
			$rs['xuhaos'] = $k+1;
			$rs['defenstr'] = '<input type="number" min="0" max="'.$rs['fenshu'].'" class="inputs" onfocus="js.focusval=this.value" name="pingfen_'.$rs['id'].'" onblur="js.number(this)">';
			$rs['itemname'] = '<div style="max-width:200px">'.$rs['itemname'].'</div>';
		}
		$pfrowsstr 	= c('html')->createrows($pfrows, 'xuhaos,,center@itemname,考核内容,left@pfname,评分名称@fenshu,总分数@defenstr,评分分数','#dddddd');
		return array(
			'pfrows' => $pfrows,
			'pfrowsstr' => $pfrowsstr,
		);
	}

	
	public function hrkaohemrun()
	{
		$dt   = $this->rock->date;
		$dbs  = m('hrkaohes');
		$dbs1 = m('hrchecks');
		$dbn1 = m('hrcheckn');
		$dbn  = m('hrkaohen');
		$rows = m('hrkaohem')->getall("`status`=1 and `startdt`<='$dt' and `enddt`>='$dt'");
		$keox = 0;
		foreach($rows as $k=>$rs){
			
			$bo 	= $this->xuyuns($rs);
			if(!$bo)continue;
			
			$keox++;
			$rowxm = $dbs->getall("`mid`='".$rs['id']."'",'*','`sort`');
			$rowpf = $dbn->getall("`mid`='".$rs['id']."'",'*','`sort`');
			
			$inarr = array(
				'khid' 		=> $rs['id'],
				'title' 	=> $rs['title'],
				'startdt'	=> $this->rock->date,
				'applydt'	=> $this->rock->date,
				'optdt'		=> $this->rock->now,
				'createdt'	=> $this->rock->now,
				'optname'	=> $rs['optname'],
				'optid'		=> $rs['optid'],
				'isturn' 	=> 1,
				'month'		=> date('Y-m'),
				'status' 	=> 1,
				'enddt'		=> ''
			);
			if($rs['pfsj']>0)$inarr['enddt'] = $this->dtobj->adddate($inarr['startdt'],'d', $rs['pfsj']);
			$recwe		= $this->adminmodel->gjoin($rs['receid'],'', 'where');
			$uarr 		= $this->adminmodel->getall("`status`=1 and ($recwe)",'id,name');
			foreach($uarr as $uk=>$urs){
				$inarr['uid'] 		= $urs['id'];
				$inarr['applyname'] = $urs['name'];
				$pfren  = $pfrenid  = $pfrenids = $pfrens = '';
				$mid 	= 0;
				$where1 = "`uid`='".$urs['id']."' and `startdt`='".$inarr['startdt']."' and `khid`='".$rs['id']."'";
				if($mrs = $this->getone($where1)){
					$mid= (int)$mrs['id'];
				}else{
					$where1= '';
				}
				//保存的hrcheck表
				$bo = $this->record($inarr, $where1);
				if($mid==0)$mid = $this->db->insert_id();
				
				//在保存到hrchecks考核内容表
				$sids = '0';
				$sids1 = '0';
				foreach($rowxm as $k1=>$rs1){
					$where1 = "`mid`='$mid' and `itemname`='".$rs1['itemname']."'";
					$sid 	= (int)$dbs1->getmou('id',$where1);
					if($sid==0)$where1 = '';
					$fenshu = floatval($rs['maxfen']) * floatval($rs1['weight']) * 0.01;
					$dbs1->record(array(
						'mid' 	=> $mid,
						'sort'	=> $k1,
						'itemname'	=> $rs1['itemname'],
						'weight'	=> $rs1['weight'],
						'fenshu'	=> $fenshu
					), $where1);
					if($sid==0)$sid = $this->db->insert_id();
					$sids.=','.$sid.'';
	
					//添加到hrcheckn考核内容表
					
					foreach($rowpf as $k2=>$rs2){
						$pfarr = $this->getpftype($urs, $rs2);
						$where2= "`mid`='$mid' and `pfid`='".$rs2['id']."' and `sid`='$sid'";
						$sid1  = 0;
						$srs   = $dbn1->getone($where2);
						if($srs){
							$sid1  = (int)$srs['id'];
						}
						if($sid1==0)$where2 = '';
						
						$dbn1->record(array(
							'mid' => $mid,
							'sort' => $k2,
							'itemname'	=> $rs1['itemname'],
							'weight'	=> $rs2['pfweight'],
							'pfid'	=> $rs2['id'],
							'pfname'	=> $rs2['pfname'],
							'sid'	=> $sid,
							'fenshu'	=> $fenshu,
							'optid'	=> $pfarr[0],
							'optname'	=> $pfarr[1],
						),$where2);
						if($sid1==0)$sid1 = $this->db->insert_id();
						$sids1.=','.$sid1.'';
						if(!isempt($pfarr[0]) && !contain(','.$pfrenid.',',','.$pfarr[0].',')){
							$pfren   .= ','.$pfarr[1];
							$pfrenid .= ','.$pfarr[0];
						}
						//未评分人
						$ispf = false;
						if($srs && !isempt($srs['optdt']))$ispf=true;
						
						if(!$ispf && !isempt($pfarr[0]) && !contain(','.$pfrenids.',',','.$pfarr[0].',')){
							$pfrenids .= ','.$pfarr[0];
							$pfrens .= ','.$pfarr[1];
						}
					}
				}
				$dbs1->delete("`mid`='$mid' and `id` not in($sids)");
				$dbn1->delete("`mid`='$mid' and `id` not in($sids1)");
				if($pfrenid!=''){
					$pfrenid = substr($pfrenid,1);
					$pfren = substr($pfren,1);
				}
				if($pfrenids!=''){
					$pfrenids = substr($pfrenids, 1);
					$pfrens = substr($pfrens, 1);
				}
				
				
				$this->update(array(
					'pfrenid' => $pfrenid,
					'pfren' => $pfren,
					'pfrenids' => $pfrenids,
					'pfrens' => $pfrens,
				), $mid);
				
				//发给对应人通知
				$this->loaddata($mid, false);
				$this->numtodosend('pftodo','评分');
			}
		}
		return $keox;
	}
	private function xuyuns($rs){
		$pinlv  = $rs['pinlv'];
		$sctime = $rs['sctime'];
		if(isempt($sctime))return false;
		if($pinlv=='d')return true; //每天
		if($pinlv=='m'){
			if(date('d')==date('d', strtotime($sctime)))return true;
		}
		//每季度
		if($pinlv=='j'){
			$m   = (int)date('m');
			$jdr = array(1,4,7,10);
			if(in_array($m, $jdr)){
				if(date('d')==date('d', strtotime($sctime)))return true;
			}
		}
		//每年
		if($pinlv=='y'){
			if(date('m-d')==date('m-d', strtotime($sctime)))return true;
		}
		return false;
	}
	//获取评分人
	private function getpftype($urs, $rs2){
		$pftype = $rs2['pftype'];
		$sid 	= '';
		$sna    = '';
		if(!isempt($rs2['pfren'])){
			$sid = $rs2['pfrenid'];
			$sna = $rs2['pfren'];
		}
		if($pftype=='my'){
			$sid = $urs['id'];
			$sna = $urs['name'];
		}
		//上级
		if($pftype=='super' && $sid==''){
			$sua = $this->adminmodel->getsuperman($urs['id']);
			if($sua){
				$sid = $sua[0];
				$sna = $sua[1];
			}
		}
		return array($sid, $sna);
	}
	
	public function defen($mid)
	{
		$rows = m('hrcheckn')->getall('`mid`='.$mid.'');
		$fshu 	= 0;
		$pfren  = $pfrenid  = $pfrenids = $pfrens = '';
		foreach($rows as $k=>$rs){
			$fshu += floatval($rs['defen']) * floatval($rs['weight']) * 0.01;
			
			if(!isempt($rs['optid']) && !contain(','.$pfrenid.',',','.$rs['optid'].',')){
				$pfren   .= ','.$rs['optname'];
				$pfrenid .= ','.$rs['optid'];
			}
			
			if(isempt($rs['optdt']) && !isempt($rs['optid']) && !contain(','.$pfrenids.',',','.$rs['optid'].',')){
				$pfrenids .= ','.$rs['optid'];
				$pfrens .= ','.$rs['optname'];
			}
		}
		
		if($pfrenid!=''){
			$pfrenid = substr($pfrenid,1);
			$pfren = substr($pfren,1);
		}
		if($pfrenids!=''){
			$pfrenids = substr($pfrenids, 1);
			$pfrens = substr($pfrens, 1);
		}
		
		$this->update(array(
			'pfrenid' 	=> $pfrenid,
			'pfren' 	=> $pfren,
			'pfrenids' 	=> $pfrenids,
			'pfrens' 	=> $pfrens,
			'fen' 		=> $fshu,
		), $mid);
	}
	
	/**
	*	提交评分
	*/
	public function pingfen()
	{
		$str = $this->rock->post('str');
		$star= c('array')->strtoarray($str);
		$dbn = m('hrcheckn');
		foreach($star as $kv2){
			$sid   = $kv2[0];
			$defen = $kv2[1];
			
			$uarr = array(
				'defen' => $defen,
				'optdt' => $this->rock->now
			);
			
			$dbn->update($uarr, $sid);
		}
		$this->defen($this->id);
		$sm 	= $this->rock->post('sm');
		
		$this->addlog(array(
			'name' => '评分',
			'explain' => $sm
		));
		//$this->numtodosend('pftz','评分', $sm);
		
		return 'ok';
	}
}