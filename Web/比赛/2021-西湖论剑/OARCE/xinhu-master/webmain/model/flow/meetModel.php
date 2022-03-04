<?php

class flow_meetClassModel extends flowModel
{
	public function initModel()
	{
		$this->hyarra 	= array('正常','会议中','结束','取消');
		$this->hyarrb 	= array('green','blue','#ff6600','#888888');
		$this->dbobj	= c('date');
		
		$this->reatearr = array(
			'd' => '每天',
			'w1' => '每周一',
			'w2' => '每周二',
			'w3' => '每周三',
			'w4' => '每周四',
			'w5' => '每周五',
			'w6' => '每周六',
			'w7' => '每周日',
			'm' => '每月',
			'y' => '每年',
		);
	}
	
	public function getratestore()
	{
		$arr = array();
		foreach($this->reatearr as $k=>$v)$arr[] = array(
			'value' => $k,
			'name' => $v
		);
		return $arr;
	}
	
	public function flowrsreplace($rs, $lx=0)
	{
		if(arrvalue($rs, 'type')=='1'){
			$ztrs = '<font color=green>启用</font>';
			if($rs['status']=='0'){
				$ztrs = '<font color=#888888>停用</font>';
				$rs['ishui']=1;
			}
			if(!isempt($rs['rate']))$ztrs.=','.arrvalue($this->reatearr,$rs['rate']).''; //转未汉字
			$rs['state'] = $ztrs;
			return $rs;//说明是固定会议
		}
		$rs['week']  = $this->dbobj->cnweek($rs['startdt']);
		$zt 		 = $rs['state'];
		$nzt 		 = $zt;
		$time 		 = time();
		
		$stime 	= strtotime($rs['startdt']);
		$etime 	= strtotime($rs['enddt']);
		if($zt < 2){
			if($etime<$time){
				$nzt = 2;
			}else if($stime>$time){
				$nzt = 0;
			}else{
				$nzt = 1;
			}
		}
		
		if($zt != $nzt){
			$this->update('state='.$nzt.'', $rs['id']);
			$zt = $nzt;
		}
		
		$rs['ishui'] = ($zt>=2) ? 1 : 0;
		if($lx==1){
			$content 	 = '';
			$inpurl 	 = $this->getinputurl('meetjy',0,'def_mid='.$this->id.'');
			$rows 		 = $this->getrows('`mid`='.$this->id.' and `type`=2','id,content,optname,optdt,optid','id');
			//是否可以加会议纪要
			$dtss   = c('date')->adddate($this->rock->date,'d',-10).' 00:00:00';
			$addbo 	= $rs['startdt']>$dtss && $zt>0;
			$fobj   = m('file');
			foreach($rows as $k=>$rs1){
				$content.= '<div style="border-bottom:1px #cccccc solid;padding:5px">['.$rs1['optname'].']纪要';
				$inpurl1 = $this->getinputurl('meetjy',$rs1['id']);
				if($addbo && $rs1['optid']==$this->adminid)$content.= '&nbsp;<a href="'.$inpurl1.'" class="blue">[编辑]</a>';
				$content.= '：<br>'.$rs1['content'].'';
				$fstr 	 = $fobj->getstr('meet', $rs1['id'], 2);
				if($fstr!='')$content.= '<br>'.$fstr.'';
				$content.= '</div>';
			}
			
			if($addbo){
				 $content.='&nbsp;<a href="'.$inpurl.'" class="blue">＋新增纪要</a>';
			}
			$rs['content']= $content;
			$rs['content_style'] = 'padding:0px';
		}
		$rs['state'] = $this->getstatezt($zt);
		$rs['nzt']	 = $zt;
		if(isset($rs['issms'])){
			$issms 		 = '否';
			if($rs['issms']==1)$issms = '是';
			$rs['issms'] = $issms;
		}
		
		//打印时
		if($lx==3){
			if(substr($rs['startdt'],0,10)==$this->rock->date)$rs['startdt'] = substr($rs['startdt'],11);
			if(substr($rs['enddt'],0,10)==$this->rock->date)$rs['enddt'] = substr($rs['enddt'],11);
			$rs['ermurl'] = $this->rock->jm->base64encode(getconfig('outurl',URL).'task.php?a=x&num=meet&mid='.$rs['id'].'&showtype=qd');
		}
		
		return $rs;
	}
	
	//签到菜单判断
	protected function flowgetoptmenu($num)
	{
		if($num=='qdnoup'){
			if($this->rock->get('showtype')!='qd')return false;
		}
	}
	
	public function getstatezt($zt)
	{
		return '<font color="'.$this->hyarrb[$zt].'">'.$this->hyarra[$zt].'</font>';
	}
	
	protected function flowsubmit($na, $sm)
	{
		if($this->rs['status']==1){
			$this->tisongtodo();
		}
		//固定会议
		if($this->rs['type']=='1'){
			$this->createmeet($this->id);
		}
	}
	
	//审核完成后发通知
	protected function flowcheckfinsh($zt)
	{
		if($zt==1)$this->tisongtodo();
	}
	
	private function tisongtodo()
	{
		if($this->rs['type']!='0')return;//这个是普通会议才需要通知。
		
		if($this->rs['startdt'] < $this->rock->now)return;//已过期了
		
		//发给参会人
		//$cont  = '{optname}发起会议“{title}”在{hyname}，时间{startdt}至{enddt}';
		//$this->push($this->rs['joinid'], '会议', $cont);
		$cont = "您好，您有一个新的会议计划。\n\n会议主题：{title}\n会议时间：{startdt}至{enddt}\n会议地点：{hyname}";
		$zcren= arrvalue($this->rs,'zcren');
		if(!isempt($zcren)){
			$cont.="\n会议主持人：{zcren}";
		}else{
			$cont.="\n发起人：{optname}";
		}
		$this->pushs($this->rs['joinid'], $cont, '会议通知');
		
		$tplnum	= m('option')->getval('meetsmstpl', 'meetapply');
		$this->sendsms($this->rs, $tplnum, array(
			'optname' 	=> $this->adminname,
			'title' 	=> $this->rs['title'],
			'hyname' 	=> $this->rs['hyname'],
			'startdt' 	=> $this->rs['startdt'],
			'enddt' 	=> $this->rs['enddt'],
		));
	}
	
	protected function flowaddlog($a)
	{
		$actname = $a['name'];
		if($actname == '取消会议'){
			$this->push($this->rs['joinid'], '会议', ''.$this->adminname.'取消会议“{title}”，时间{startdt}至{enddt}，请悉知。');
			$this->update('`state`=3', $this->id);
			
			$tplnum	= m('option')->getval('meetsmstplqx', 'meetcancel');
			$this->sendsms($this->rs, $tplnum, array(
				'optname' 	=> $this->adminname,
				'title' 	=> $this->rs['title'],
				'hyname' 	=> $this->rs['hyname'],
				'startdt' 	=> $this->rs['startdt'],
				'enddt' 	=> $this->rs['enddt'],
			));
		}
		if($actname == '结束会议'){
			$this->update('`state`=2', $this->id);
		}
	}
	
	//发短信提醒
	public function sendsms($rs, $tplnum, $params)
	{
		$receid = $rs['joinid'];
		$issms  = arrvalue($rs,'issms');
		
		if(isempt($receid) || $issms!='1')return;
		$jyid	= $rs['jyid'];
		if(!isempt($jyid))$receid.=','.$jyid.''; //发个纪要人
		
		$zcrenid= arrvalue($rs, 'zcrenid');
		if(!isempt($zcrenid))$receid.=','.$zcrenid.''; //发个主持人
		
		$qiannum= ''; //签名编号，可以为空
		$barr = c('xinhuapi')->sendsms($receid, $qiannum, $tplnum, $params);
		return $barr;
	}
	
	protected function flowbillwhere($uid, $lx)
	{
		$dt 	= $this->rock->post('dt');
		$where 	= '';
		//固定会议
		if($lx=='allgd'){
			$where 	= 'and `type`=1';
		}else{
			$where 	= 'and `type`=0';
		}
		if($dt!='')$where.=" and startdt like '$dt%'";
		//$fields	= 'id,startdt,enddt,optname,state,title,hyname,joinname,`explain`,jyname';
		return array(
			//'fields' => $fields,
			'where'	 => $where,
			'order' => 'startdt desc'
		);
	}
	
	
	//每天运行计划任务将固定会议生成普通会议通知对应人
	public function createmeet($id=0, $nowdt='', $gbrr=false)
	{
		$owhe 	= '';
		if($id>0)$owhe='`id`='.$id.' and ';
		$narr 	= $this->getall(''.$owhe.'`type`=1 and `status`=1');
		$dtobj	= c('date');
		$jlarr 	= array();
		foreach($narr as $k=>$rs){
			$gdt = $dtobj->daterate($rs['rate'], $rs['startdt'], $nowdt);
			if(!$gdt)continue;
			$startdt = ''.$gdt.' '.substr($rs['startdt'],11).'';
			$enddt 	 = ''.$gdt.' '.substr($rs['enddt'],11).'';
			
			$ars 	 = $rs;
			$ars['mid'] = $rs['id'];
			$ars['type'] = '0';
			$ars['startdt'] = $startdt;
			$ars['enddt'] = $enddt;
			$ars['state'] = 0;
			$ars['rate'] = '';
			unset($ars['id']);
			$this->adminmodel->setcompanyid($rs['comid']);
			$GLOBALS['adminid'] = $ars['optid'];
			$where  = "`mid`=".$rs['id']." and `startdt` like '".$gdt."%'";
			$ors 	= $this->getone($where);
			$uwerew = '';
			$iid 	= 0;
			if($ors){
				$iid	= $ors['id'];
				$uwerew = "`id`='$iid'";
			}
			$jlarr[] = $ars;
			
			if($gbrr)continue; //不写入数据库里
			
			$this->record($ars, $uwerew);
			if($iid==0){
				$iid = $this->db->insert_id();
				$this->loaddata($iid, false);
				$this->tisongtodo();//通知
			}
		}
		
		return $jlarr;
	}
	
	
	public function meettodo()
	{
		$rows 	= $this->getall("`state` in(0,1) and `type`=0 and `startdt` like '".$this->rock->date."%' and `status`=1");
		$time	= time();
		foreach($rows as $k=>$rs){
			$this->adminmodel->setcompanyid($rs['comid']); //设置对应单位id
			$zt 	= $rs['state'];
			$dts	= explode(' ', $rs['startdt']);
			$sttime = strtotime($rs['startdt']);
			$ettime = strtotime($rs['enddt']);
			$GLOBALS['adminid'] = $rs['optid'];
			$nzt	= -1;
			if($ettime <= $time){
				$nzt = 2;
			}else{
				if($time >= $sttime && $time< $ettime){
					if($zt==0)$nzt = 1;
				}else{
					$jg = $sttime - $time;
					if($jg <= 600 && $zt==0){ //提前10分钟就提醒
						$ssj 	= floor($jg/60);
						$tzuid 	= $this->adminmodel->gjoin($rs['joinid']);
						
						//$cont  	= '会议“'.$rs['title'].'”将在'.$ssj.'分钟后的'.$dts[1].'开始请做好准备，在会议室“'.$rs['hyname'].'”';
						$this->loaddata($rs['id'], false);
						$this->meettodos($rs, '您好，会议即将在'.$ssj.'分钟后的'.$dts[1].'开始，请准时参加。');//快到时间通知
					
						//短信通知
						if($ssj<6)$this->sendsms($rs, 'meettodo', array(
							'fenz' 		=> ''.$ssj.'',
							'title' 	=> $rs['title'],
							'time' 		=> $dts[1],
							'hyname' 	=> $rs['hyname']
						));
					}
				}
			}
			if($nzt != -1)$this->update("`state`='$nzt'", $rs['id']);
		}
	}
	
	private function meettodos($rs, $kss)
	{
		$receid = $rs['joinid'];
		if(isempt($receid))return;
		
		$cont 	= "".$kss."\n\n会议主题：{title}\n会议时间：{startdt}至{enddt}\n会议地点：{hyname}";
		$zcren= arrvalue($rs,'zcren');
		if(!isempt($zcren)){
			$cont.="\n会议主持人：{zcren}";
		}else{
			$cont.="\n发起人：{optname}";
		}
		
		$jyid	= $rs['jyid'];
		if(!isempt($jyid))$receid.=','.$jyid.''; //发个纪要人
		
		$zcrenid= arrvalue($rs, 'zcrenid');
		if(!isempt($zcrenid))$receid.=','.$zcrenid.''; //发个主持人
		
		$this->pushs($receid, $cont, '会议提醒');
	}
}