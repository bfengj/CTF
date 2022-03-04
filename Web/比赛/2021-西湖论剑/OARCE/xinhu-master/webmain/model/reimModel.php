<?php
class reimClassModel extends Model
{
	private $groupname = '';
	public $serverrecid 	= 'rockreim';
	public $serverpushurl 	= '';
	public $serverhosturl 	= '';
	public $servertitle		= '';
	
	
	
	public function initModel()
	{
		$this->settable('im_mess');
		$this->hisobj = m('im_history');
		$this->option = m('option');
		$this->inithost();
	}
	
	private function inithost()
	{
		if($this->serverpushurl!='')return;
		$dbs = $this->option;
		$this->optiondb 		= $dbs;
		$this->serverrecid		= $dbs->getval('reimrecidsystem','rockxinhu');
		$this->serverpushurl	= $dbs->getval('reimpushurlsystem');
		$this->serverhosturl	= $dbs->getval('reimhostsystem');
		$this->servertitle		= $dbs->getval('reimtitlesystem');
		$this->serverpushurl	= getconfig('reim_push', $this->serverpushurl); 
		$this->serverhosturl	= getconfig('reim_host', $this->serverhosturl);
		if($this->isempt($this->servertitle))$this->servertitle='信呼';
	}
	
	public function isanwx()
	{
		$bo = false;
		return $bo;
	}
	
	/**
	*	返回判断是否有安装微信企业号/企业微信 $lx=0企业号,1企业微信,2钉钉,3微信公众号号,4是否微信模版消息
	*/
	public function installwx($lx=0)
	{
		if($lx==0)return $this->isanwx();
		$bo = false;
		if($lx==1){
			if(!isempt($this->optiondb->getval('weixinqy_corpid')))$bo=true;
			return $bo;
		}
		if($lx==2){
			if(!isempt($this->optiondb->getval('dingding_token0')))$bo=true;
			return $bo;
		}
		if($lx==3){
			if(!isempt($this->optiondb->getval('wxgzh_appid')))$bo=true;
			return $bo;
		}
		if($lx==4){
			if($this->optiondb->getval('wxgzh_tplmess')=='1')$bo=true;
			return $bo;
		}
		if($lx==5){
			if(!isempt($this->optiondb->getval('reimplat_cnum')))$bo=true;
			return $bo;
		}
		return false;
	}
	
	public function getreims()
	{
		$this->inithost();
		$chehui = (int)$this->optiondb->getval('reimchehuisystem',0);
		if($chehui<0)$chehui = 0;
		return array(
			'recid' => $this->serverrecid,			
			'title' => $this->servertitle,			
			'chehui' => $chehui * 60,			
			'wsurl' => $this->rock->jm->base64encode($this->serverhosturl)			
		);
	}

	private function getgroupid($gname)
	{
		$agesta = explode(',', $gname);
		$name 	= $agesta[0];
		$sid 	= (int)$this->db->getmou('[Q]im_group','id', "`name`='$name' and `type`=2");
		if($sid==0 && count($agesta)>1)$sid = $this->getgroupid($agesta[1]);
		$this->groupname = $name;
		return $sid;
	}
		
	/**
	*	REIM推送的
	*/
	public function sendsystem($sendid, $receid, $gname, $cont, $table='',$mid='', $url='')
	{
		$gid	= $this->getgroupid($gname);
		$gname	= $this->groupname;
		if($gid==0)return false;
		
		if($this->isempt($receid))return 'not receuid';
		$receids = $receid;
		
		$wheres	 = " and `id` in($receid)";
		if($receid=='all')$wheres='';
		$allsid 	= '';
		$recrarr 	= $this->db->getall("select id from [Q]admin where `status`=1  $wheres");
		foreach($recrarr as $k=>$rs){
			$allsid.=','.$rs['id'].'';
		}
		$messid		= 0;
		if($allsid != ''){
			$allsid = substr($allsid, 1);
			$this->insert(array(
				'type'	=> 'agent',
				'optdt'	=> $this->rock->now,
				'zt'	=> 0,
				'cont'	=> $this->rock->jm->base64encode($cont),
				'sendid'=> $sendid,
				'receid'=> $gid,
				'optid'	=> $sendid,
				'receuid' => $allsid,
				'table'	=> $table,
				'mid'	=> $mid,
				'url'	=> $url
			));
			$messid	= $this->db->insert_id();
			$this->db->insert('[Q]im_messzt','`mid`,`uid`,`gid`','select '.$messid.',id,'.$gid.' from `[Q]admin` where id in('.$allsid.') and `status`=1 ', true);
		}
		$resid = $receids;
		if($resid!='all')$resid = m('admin')->getonline($resid);
		if($resid!='' && $messid>0)$this->sendpush($sendid, $resid, array(
			'agent'		=> $gname,
			'optdt'		=> $this->rock->now,
			'type'		=> 'agent',
			'messid'	=> $messid,
			'agentid'	=> $gid,
			'cont'		=> $this->rock->jm->base64encode($cont),
			'table'		=> $table,
			'mid'		=> $mid,
			'url'		=> $url
		));
		//if($messid>0)$this->addhistory('agent', $gid, $allsid);
		return true;
	}
	
	/**
	*	应用信息推送
	*	$slx 0,1发送给pc，0,2发送给移动端,3不发送
	*	$xgurl 相关地址，一般是单据详情：模块编号|id
	*/
	public function pushagent($receid, $gname, $cont, $title='', $url='', $wxurl='', $slx=0, $xgurl='')
	{
		if($slx==3 || isempt($receid))return false;
		$cont	= str_replace(array("\n",'\n','<br>'),' ', $cont);
		$gid	= $this->getgroupid($gname);
		$grs 	= $this->getgroupxinxi($gid);
		$gname	= $this->groupname;
		$admdb  = m('admin');
		$sarr	= array(
			'gname'		=> $gname,
			'optdt'		=> $this->rock->now,
			'type'		=> 'agent',
			'pushtype'	=> 'agent',
			'title'		=> $title,
			'gface'		=> arrvalue($grs,'face'),
			'gid'		=> $gid,
			'cont'		=> $this->rock->jm->base64encode($cont),
			'url'		=> $url
		);
		
		if($title=='')$title = $gname;
		
		//保存到推送会话列表上历史记录上
		if($gid>0){
			$receids = $admdb->gjoins($receid);
			if($receids!='all' &&
				!isempt($receids)
			)$this->addhistory($sarr['type'], $gid, $receids, $sarr['optdt'], $sarr['cont'], $this->adminid, $title, $xgurl);
		}
		
		$resid  = $receid;
		if($slx == 0 || $slx==1){
			if($resid != 'all')$resid = $admdb->getonline($resid);
			if($resid != '')$this->sendpush($this->adminid, $resid, $sarr);//PC端
		}
		//推送到APP上
		if($slx == 0 || $slx==2){
			if($wxurl!='')$sarr['url'] = $wxurl;
			$this->pushapp($receid, $title, $sarr, $slx);
		}
		
	}
	
	
	//获取REIM未读的
	public function getwdarr($mid=0, $ldt='')
	{
		$rows 	= array();
		if($mid==0)$mid	= $this->adminid;
		$whes	= $this->rock->dbinstr('receuid', $mid);
		$wher	= '';
		if(!$this->isempt($ldt))$wher=" and `optdt`>='$ldt' ";
		$arr 	= $this->getall("`zt`=0 and receid='$mid' and `type`='user' $wher group by `sendid`", "`sendid`,count(1) as stotal,max(optdt) as optdts,cont");
		foreach($arr as $k=>$rs){
			$uid 	= $rs['sendid'];
			$urs 	= $this->db->getone('[Q]admin',"`id`='$uid'",'`name`,`face`');
			if($urs){
				$rows[] = array(
					'type' 	=> 'user',
					'id'	=> $uid,
					'stotal'=> $rs['stotal'],
					'optdt'	=> $rs['optdts'],
					'name'	=> $urs['name'],
					'cont'	=> $rs['cont'],
					'face'	=> $this->getface($urs['face'])
				);
			}
		}
		
		// 讨论组 群
		$groupa	= $this->db->getarr('[Q]im_group','1=1','`name`,`face`,`type`');
		$gid 	= '0';
		foreach($groupa as $_gid=>$kvs)$gid.=','.$_gid.'';
		$arr 	= $this->getall("`type`='group' and `receid` in($gid) and $whes $wher and id in(select mid from [Q]im_messzt where uid='$mid') group by `receid`", "`receid`,count(1) as stotal,max(optdt) as optdts,cont");
		$typea	= array('group','group');
		foreach($arr as $k=>$rs){
			$grs	= $groupa[$rs['receid']];
			$typ 	= $typea[$grs['type']];
			$rows[] = array(
				'type' 	=> 'group',
				'id'	=> $rs['receid'],
				'stotal'=> $rs['stotal'],
				'optdt'	=> $rs['optdts'],
				'name'	=> $grs['name'],
				'cont'	=> $rs['cont'],
				'face'	=> $this->getface($grs['face'],'images/'.$typ.'.png')
			);
		}
		
		//应用的信息	
		$arr 	= $this->getall("`type`='agent' and `receid` in($gid) and $whes $wher and id in(select mid from [Q]im_messzt where uid='$mid') group by `receid`", "`receid`,count(1) as stotal,max(optdt) as optdts,cont");
		foreach($arr as $k=>$rs){
			$grs	= $groupa[$rs['receid']];
			$rows[] = array(
				'type' 	=> 'agent',
				'id'	=> $rs['receid'],
				'stotal'=> $rs['stotal'],
				'optdt'	=> $rs['optdts'],
				'cont'	=> $rs['cont'],
				'name'	=> $grs['name'],
				'face'	=> $this->getface($grs['face'])
			);
		}
		
		return $rows;
	}
	
	public function getweitotal($uid, $type, $sid=0, $blx=0)
	{
		$whes	= $this->rock->dbinstr('receuid', $uid);
		$where  = "`type`='$type' and `receid` ='$sid' and $whes and id in(select mid from [Q]im_messzt where uid='$uid')";
		if($type == 'user'){
			$where  = "`zt`=0 and `receid`='$uid' and `type`='user' and $whes";
		}
		if($blx==1)return $where;
		$to 	= $this->rows($where);
		return $to;
	}
	
	/**
	*	获取未读的会话消息数量
	*/
	public function getreimwd($uid)
	{
		$to = $this->db->getmou('[Q]im_history','sum(stotal)','uid='.$uid.'');
		if(isempt($to))$to = '0';
		return $to;
	}
	
	/**
	*	获取人员所在的会话上
	*/
	public function getgroup($uid)
	{
		$ids	= '0';
		$idrsa	= m('im_groupuser')->getall("uid='$uid'",'gid');
		foreach($idrsa as $k=>$rs){
			$ids.=','.$rs['gid'];
		}
		
		$sql = "select a.gid,count(1)utotal from `[Q]im_groupuser` a left join `[Q]admin` b on a.uid=b.id where a.gid in($ids) and b.status=1 group by a.gid";
		$urows	= $this->db->getall($sql);
		$ugarr	= array();
		foreach($urows as $k=>$rs)$ugarr[$rs['gid']] = $rs['utotal'];
		
		$rows 	= m('im_group')->getall("`id`>0 and ((`type` in(0,1) and `id` in($ids) ) ) order by `type`,`sort` ",'`id`,`type`,`name`,`face`,`sort`,`deptid`');
		$facarr = array('images/group.png','images/group.png','images/system.png');
		foreach($rows as $k=>$rs){
			$rows[$k]['face'] 	= $this->getface($rs['face'], $facarr[$rs['type']]);
			$rows[$k]['utotal'] = arrvalue($ugarr,$rs['id'], '0');
		}
		return $rows;
	}
	
	public function getgroupuser($gid, $type)
	{
		$sql = "select b.id,b.name,b.face from `[Q]im_groupuser` a left join `[Q]admin` b on a.uid=b.id where a.gid='$gid' and b.status=1";
		if($type=='user')$sql = "select id,name,face from `[Q]admin` where id in(".$gid.",".$this->adminid.")";
		$rows = $this->db->getall($sql);
		foreach($rows as $k=>$rs){
			$rows[$k]['face'] = $this->getface($rs['face']);
		}
		$arr['uarr'] 	= $rows;
		if($type=='user'){
			$arr['infor'] 	= array();
		}else{
			$arr['infor'] 	= $this->getgroupxinxi($gid);
		}
		return $arr;
	}
	
	public function getgroupxinxi($gid)
	{
		$rs 	= m('im_group')->getone($gid,'`id`,`type`,`name`,`face`,`deptid`,`createname`,`createid`');
		$facarr = array('images/group.png','images/group.png','images/todo.png');
		if(!$rs){
			$rs = array(
				'face'  => '',
				'type'  => 2,
				'name'  => '',
				'id' 	=> $gid,
			);
		}
		$rs['face'] 	= $this->getface($rs['face'], $facarr[$rs['type']]);
		$rs['utotal'] 	= $this->db->rows('[Q]im_groupuser','gid='.$gid.'');
		$rs['innei'] 	= $this->db->rows('[Q]im_groupuser','gid='.$gid.' and uid='.$this->adminid.''); //是否在会话中
		return $rs;
	}
	
	private function getface($face, $mr='')
	{
		if($mr=='')$mr 	= 'images/noface.png';
		$url = URL;
		if(!$url)$url = getconfig('outurl');
		if(substr($mr,0,4)!='http')$mr = $url.''.$mr.'';
		if(substr($face,0,4)!='http' && !$this->isempt($face))$face = $url.''.$face.'';
		$face 			= $this->rock->repempt($face, $mr);
		return $face;
	}
	
	/**
		设置已读
	*/
	public function setyd($ids, $receid)
	{
		$this->update("`zt`=1", "`id` in($ids) and receid='$receid' and `type` ='user' ");
		m('im_messzt')->delete("uid='$receid' and `mid` in($ids)");
	}
	
	public function setallyd($type,$uid, $gid)
	{
		if($type=='user'){
			$this->update("`zt`=1", "`sendid` ='$gid' and receid='$uid' and `type`='user'");
		}else{
			m('im_messzt')->delete("uid='$uid' and `gid`=$gid");
		}
		$this->hisobj->update('stotal=0', "`type`='$type' and `uid`='$uid' and `receid`='$gid'");
	}
	
	
	/**
	*	桌面版PC客户端获取应用
	*/
	public function getagent($uid=0, $whe='', $pid=0)
	{
		if($uid==0)$uid = $this->adminid;
		$yylx	= '2';
		if($this->rock->get('cfrom')=='reim')$yylx='1';
		$dboaj	= m('admin');
		$where	= $dboaj->getjoinstr('receid', $this->adminid);
		$rows 	= $this->db->getrows('[Q]im_group',"`valid`=1 and `type`=2 and `yylx` in(0,".$yylx.") $where $whe",'`id`,`name`,`url`,`face`,`num`,`pid`,`iconfont`,`iconcolor`,`types`,`urlpc`,`urlm`','`sort`');
		$dbs 	= m('im_menu');
		$mdbs 	= m('menu');
		$barr	= $carr = array();
		$mids 	= '0';
		foreach($rows as $k=>$rs)$mids.=','.$rs['id'].'';
		$allmenu  = $cmenu	 = array();
		$allmenua = $dbs->getall("`mid` in($mids)",'`pid`,`mid`,`id`,`name`,`type`,`url`,`num`,`color`,`receid`','`sort`');
	
		foreach($allmenua as $k1=>$rs1){
			if(isempt($rs1['receid'])){
				$allmenu[] = $rs1;
			}else{
				$bo = $dboaj->containjoin($rs1['receid'], $uid);
				if($bo)$allmenu[] = $rs1;
			}
		}
		
		foreach($allmenu as $k=>$rs){
			
			if($rs['pid']=='0'){
				$submenu	= array();
				foreach($allmenu as $k1=>$rs1){
					if($rs1['pid']==$rs['id'])$submenu[] = $rs1;
				}
				$rs['submenu'] = $submenu;
				$cmenu[$rs['mid']][] = $rs;
			}
		}

		foreach($rows as $k=>$rs){
			if(isempt($rs['num']))continue;
			
			$rs['face'] 	= $this->getface($rs['face']);
			$stotal			= 0;
			
			$btosr = m('agent:'.$rs['num'].'')->gettotal();
			$stotal			= $btosr['stotal'];
			$rs['titles'] 	= $btosr['titles'];
		
			$menu			= array();
			if(isset($cmenu[$rs['id']]))$menu = $cmenu[$rs['id']];
			$rs['menu'] 	= $menu;
			
			$rs['stotal'] 	= $stotal;
			$rs['totals'] 	= 0;
			
			//连接地址转化
			if($rs['url']=='link' || $rs['url']=='linko'){
				$urlpc = $rs['urlpc'];
				if(!isempt($urlpc) && $mrs = $mdbs->getone("`num`='$urlpc'")){
					$urlpc = 'index.php?m=index&homeurl='.$this->rock->jm->base64encode($mrs['url']).'&homename='.$this->rock->jm->base64encode($mrs['name']).'&menuid='.$this->rock->jm->base64encode($mrs['id']).'';
					$rs['urlpc'] = $urlpc;
				}
			}
			
			$barr[] = $rs;
		}
		foreach($barr as $k=>$rs){
			$types = $rs['types'];
			if(isempt($types))$types='应用';
			if(!isset($carr[$types]))$carr[$types]=array();
			$rs['types']	= $types;
			$carr[$types][] = $rs;
		}
		
		//应用统计
		$gcarr = array();
		foreach($carr as $types=>$rows){
			$ntypes = $types.'('.count($rows).')';
			foreach($rows as $k=>$rs)$rows[$k]['types'] = $ntypes;
			$gcarr[$ntypes] = $rows;
		}
		
		$barr = array();
		foreach($gcarr as $types=>$rs){
			$barr = array_merge($barr, $rs);
		}
		return $barr;
	}
	
	/**
	*	手机app/手机网页版上获取应用
	*/
	public function getappagent($uid=0)
	{
		$uid 	= $this->adminid;
		$where	= m('admin')->getjoinstr('receid', $this->adminid);
		$rows 	= $this->db->getrows('[Q]im_group',"`valid`=1 and `type`=2 and `yylx` in(0,2) $where ",'`id`,`name`,`url`,`face`,`num`,`types`,`urlm`','`sort`');
		$dbs 	= m('im_menu');
		$barr	= $carr = array();
		$stotalt= 0;
		foreach($rows as $k=>$rs){
			if(isempt($rs['num']))continue;
			$rs['face'] 	= $this->getface($rs['face']);
			$stotal			= 0;
			$btosr 			= m('agent:'.$rs['num'].'')->gettotal(); //统计红点数
			$stotal			= $btosr['stotal'];
			$stotalt+=$stotal;
			$rs['titles'] 	= $btosr['titles'];
			$rs['stotal'] 	= $stotal;
			$barr[] = $rs;
		}
		
		foreach($barr as $k=>$rs){
			$types = $rs['types'];
			if(isempt($types))$types='应用';
			if(!isset($carr[$types]))$carr[$types]=array();
			$rs['types']	= $types;
			$carr[$types][] = $rs;
		}
		
		//应用统计
		$gcarr = array();
		foreach($carr as $types=>$rows){
			$ntypes = $types.'('.count($rows).')';
			foreach($rows as $k=>$rs)$rows[$k]['types'] = $ntypes;
			$gcarr[$ntypes] = $rows;
		}
		
		$barr = array();
		foreach($gcarr as $types=>$rs){
			$barr = array_merge($barr, $rs);
		}
		return array(
			'rows' 		=> $barr,
			'stotal' 	=> $stotalt,
		);
	}
	
	
	/**
	*	获取历史记录
	*/
	public function gethistory($uid=0, $optdt='', $whes='')
	{
		if($uid==0)$uid = $this->adminid;
		$where 	= $whes;
		if($optdt!='')$where = "and `optdt`>'$optdt'";
		$rows 	= $this->db->getall("select * from `[Q]im_history` where `uid`=$uid $where order by `optdt` desc");
		$dt 	= $this->rock->date;
		foreach($rows as $k=>$rs){
			$rows[$k]['optdts'] = substr($rs['optdt'],11,5);
			if(!contain($rs['optdt'], $dt))$rows[$k]['optdts'] = substr($rs['optdt'],5,5); 
			$rows[$k]['id'] 	= $rs['receid'];
			$name = '';
			$rson = false;
			if($rs['type']=='user'){
				$rson  	= $this->db->getone('[Q]admin', $rs['receid'], 'name,face');
				$face 	= 'images/noface.png';
			}else{
				$face	= 'images/group.png';
				$rows[$k]['gid'] = $rs['receid'];
				$rson  	= $this->db->getone('[Q]im_group', $rs['receid'], 'name,face,deptid');
				if(!isempt($rs['title']) && $rson)$rson['name'] = $rs['title'];
			}
			if($rson){
				$name = $rson['name'];
				$face = $this->getface($rson['face'], $face);
				if($rs['type']=='group')$rows[$k]['deptid'] = $rson['deptid'];
			}
			$rows[$k]['face'] = $face;
			$rows[$k]['name'] = $name;
		}
		return $rows;
	}
	
	/**
	*	微信上获取未读消息
	*/
	public function getuntodo($uid)
	{
		$rows = $this->gethistory($uid, '', 'and `stotal`>0');
		$str = '';
		foreach($rows as $k=>$rs){
			if($k>0)$str.= "\n";
			$str.=''.($k+1).'、'.$rs['name'].'：'.$rs['stotal'].'条';
		}
		return $str;
	}
	
	
	/**
	*	添加到历史记录,用户不显示历史记录让从新显示
	*/
	public function addhistory($type, $receid, $uids,$optdt, $cont,$sendid=0, $title='', $xgurl='',$messid=0)
	{
		$uidsas 	= explode(',', $uids);
		$db 		= $this->hisobj;
		$isuar		= array();
		$uarrs 		= $db->getrows("`type`='$type' and `receid`='$receid' and `uid` in($uids)", '`uid`,`id`');
		foreach($uarrs as $k=>$rs)$isuar[$rs['uid']]=$rs['id'];
		$iarr 		= $garr = array();
		$gids	= '';
		foreach($uidsas as $uid){
			$where  = '';
			if(isset($isuar[$uid]))$where = $isuar[$uid];
			$arr 	= array();
			$arr['optdt'] 	= $optdt;
			$arr['cont'] 	= substr($cont, 0, 190);
			$arr['sendid'] 	= $sendid;
			$arr['title'] 	= $title;
			$arr['xgurl'] 	= $xgurl;
			$arr['messid'] 	= $messid;
			if($where==''){
				$arr['type'] 	= $type;
				$arr['receid'] 	= $receid;
				$arr['uid'] 	= $uid;
				$arr['stotal'] 	= 1;
			}else{
				$arr['stotal'] 	= '(&;)`stotal`+1';
			}
			if($where==''){
				$iarr[] = $arr;
			}else{
				if(!$garr)$garr = $arr;
				$gids.=','.$where.'';
			}
		}
		if($iarr)$db->insertAll($iarr);
		if($gids!='')$db->update($garr,'`id` in('.substr($gids,1).')');
		$db->update('`stotal`=0',"`type`='$type' and `receid`='$receid' and `uid`='$this->adminid'");
	}
	
	public function delhistory($type, $receid, $uid=0)
	{
		$where  = "`type`='$type' and `receid`='$receid'"; 
		if($uid>0)$where.=" and `uid`='$uid'";
		if($type=='all'){
			$where  = "`uid`='$uid'"; 
		}
		$this->hisobj->delete($where);
	}
	
	/**
	*	获取聊天会话记录
	*/
	public function getrecord($type, $uid, $gid, $minid=0, $lastdt='')
	{
		$arr = array();
		$rows= array();
		$loadci  = (int)$this->rock->get('loadci','0');
		if($type == 'user'){
			$arr 	= $this->getuserinfor($uid, $gid, $minid, $lastdt);
		}
		if($type=='group'){
			$arr 	= $this->getgroupinfor($uid, $gid, $minid, $lastdt);
		}
		$arr['receinfor'] = $this->getreceinfor($type, $gid);
		$arr['nowdt'] 	  = time();
		$arr['servernow'] = $this->rock->now;
		if($loadci==0){
			$arr['sendinfo']  = m('admin')->getinfor($uid);
		}
		if(isset($arr['rows']))$arr['rows'] = $this->replacefileid($arr['rows']);
		$this->hisobj->update('stotal=0',"`type`='$type' and `receid`='$gid' and `uid`='$uid'");
		return $arr;
	}
	public function getreceinfor($type, $gid)
	{
		$info = array();
		if($type == 'user'){
			$info = m('admin')->getinfor($gid);
		}
		if($type=='group'){
			$info = $this->getgroupxinxi($gid);
		}
		
		$bsear = $this->getreims();
		
		$info['type'] = $type;
		$info['gid']  = $gid;
		$info['chehui']  = $bsear['chehui'];
		return $info;
	}
	private function replacefileid($rows)
	{
		$fileids = '0';
		if($rows)foreach($rows as $k=>$rs){
			if($rs['fileid'])$fileids.=','.$rs['fileid'].'';
		}
		$imgext = ',gif,png,jpg,jpeg,bmp,';
		$fobj	= m('file');
		if($fileids!='0'){
			$farr  = array();
			$frows = $fobj->getrows("id in ($fileids)", 'id,fileext,filenum,filepath,filename,thumbpath,filetype,filesizecn,optid,optname,adddt,filesize,thumbplat');
			foreach($frows as $k=>$rs)$farr[$rs['id']]=$rs;
			if($farr)foreach($rows as $k=>$rs){
				$frs  = array();
				$fid  = $rs['fileid'];
				if(isset($farr[$fid]))$frs=$farr[$fid];
				if($frs){
					$type = $frs['fileext'];
					$path = $frs['filepath'];
					$boc  = false;
					if(substr($path,0,4)=='http' || !isempt($frs['filenum'])){
						$boc = true;
					}else{
						if(file_exists($path))$boc = true;
					}
					if($boc){
						if($this->contain($imgext, ','.$type.',')){
							$frs['thumbpath'] = $fobj->getthumbpath($frs);
							//$cont = '<img fid="'.$fid.'" src="'.$frs['thumbpath'].'">';
							//$rows[$k]['cont'] = $this->rock->jm->base64encode($cont);
						}else{
							
						}
						$frs['fileid'] = $fid;
						$rows[$k]['filers']	  = $frs;
					}else{
						$rows[$k]['fileid']	  = 0;
					}
				}
			}
		}
		return $rows;
	}
	
	
	/**
	*	获取人员信息
	*	$uid 当前用户
	*/
	public function getuserinfor($uid, $receid, $minid=0, $lastdt='')
	{
		$type 	= 'user';
		$whes	= $this->rock->dbinstr('receuid', $uid);
		$soulx	= $this->rock->get('soulx');
		$soukey	= $this->rock->get('soukey');
		
		$wdtotal= 0;
		$where1	= "`type`='$type' and `zt`=0 and `receid`='$uid' and `sendid`='$receid' and $whes";
		if($lastdt=='' && $this->rock->get('laiyuan')!='not')$wdtotal= $this->rows($where1);
		
		if($wdtotal > 0){
			$where	= "$where1 order by `id` desc limit 10";
		}else{
			$where 	= "`type`='$type' and ((`receid`='$uid' and `sendid`='$receid') or (`sendid`='$uid' and `receid`='$receid')) and $whes ";
			if($lastdt != ''){
				$where .= " and `optdt`>'$lastdt'";
				if($this->rock->get('laiyuan')!='new')$where .=' and `sendid`<>'.$uid.'';
			}
			if($soulx=='sou0' | $soulx=='sou1')$where.= " and `fileid`>0 ";
			if($soukey)$where.=" and `cont` like '%".$soukey."%'";
			
			if($minid==0){
				$where .= ' order by `id` desc limit 5';
			}else{
				$where .= ' and `id`<'.$minid.' order by `id` desc limit 10';
			}
		}
		$rows 	= $this->getall($where, 'SQL_CALC_FOUND_ROWS optdt,zt,id,cont,sendid,fileid,type');
		$total  = $this->db->found_rows();
		$len	= 0;
		$suids	= '0';
		$ids 	= '0';
		foreach($rows as $k=>$rs){
			$len++;
			if($rs['zt']==0)$ids .= ','.$rs['id'].'';
			$suids.= ','.$rs['sendid'];
			$wdtotal--;
		}
		$rows 		= $this->ivaregarr($suids, $rows);
		if($ids!='0')$this->setyd($ids, $uid);
		if($wdtotal<0)$wdtotal=0;
		$total	= $total-$len;
		if($total<=0)$total = 0;
		return array(
			'rows' 		=> $rows,
			'wdtotal' 	=> $wdtotal,
			'systotal'  => $total,
		);
	}
	
	public function getgroupinfor($uid, $receid, $minid=0, $lastdt='')
	{
		$whes		= $this->rock->dbinstr('receuid', $uid);
		$order 		= '';
		$type		= 'group';
		$wdtotal	= 0;
		if($lastdt=='' && $this->rock->get('laiyuan')!='not')$wdtotal	= $this->getweitotal($uid, $type, $receid);
		$soulx		= $this->rock->get('soulx');
		$soukey		= $this->rock->get('soukey');
	
		if($wdtotal > 0){
			$wdwhere	= $this->getweitotal($uid, $type, $receid, 1);
			$zwhere = " $wdwhere order by `id` desc limit 10";
		}else{
			$zwhere = " `receid`='$receid' and `type`='$type' and $whes";
			if($lastdt != ''){
				$zwhere .= " and `optdt`>'$lastdt'";
				if($this->rock->get('laiyuan')!='new')$zwhere .=' and `sendid`<>'.$uid.'';
			}
			if($soulx=='sou0' | $soulx=='sou1')$zwhere.= " and `fileid`>0 ";
			if($soukey)$zwhere.=" and `cont` like '%".$soukey."%'";
			
			if($minid==0){
				$zwhere .= ' order by `id` desc limit 5';
			}else{
				$zwhere .= ' and `id`<'.$minid.' order by `id` desc limit 10';
			}
		}
		$rows	= $this->getall($zwhere, 'SQL_CALC_FOUND_ROWS optdt,zt,id,cont,sendid,fileid');
		$total  = $this->db->found_rows();
		$ids 	= '0';
		$suids	= '0';
		$len 	= 0;
		foreach($rows as $k=>$rs){
			$len++;
			$ids .= ','.$rs['id'].'';
			$suids.= ','.$rs['sendid'];
			$wdtotal--;
		}
		$rows 	= $this->ivaregarr($suids, $rows);
		if($ids!='0')$this->setyd($ids, $uid);
		if($wdtotal<0)$wdtotal=0;
		$total	= $total-$len;
		if($total<=0)$total = 0;
		return array(
			'rows' 		=> $rows,
			'wdtotal' 	=> $wdtotal,
			'systotal' => $total,
		);
	}
	
	public function ivaregarr($suids,$rows,$fid='')
	{
		if($suids=='' || $suids=='0')return $rows;
		if($fid=='')$fid='sendid';
		$farr	= $this->db->getarr('[Q]admin', "`id` in($suids)",'`face`,`name`');
		foreach($rows as $k=>$rs){
			$face =  $name = '';
			if(isset($farr[$rs[$fid]])){
				$face = $farr[$rs[$fid]]['face'];
				$name = $farr[$rs[$fid]]['name'];
			}	
			$rows[$k]['face'] 	  = $this->getface($face);
			$rows[$k]['sendname'] = $name;
		}
		return $rows;
	}
	
	private function ivarggarr($sgids,$rows, $fid='')
	{
		if($sgids=='' || $sgids=='0')return $rows;
		if($fid=='')$fid='receid';
		$farr	= $this->db->getarr('[Q]im_group', "`id` in($suids)",'`face`,`name`');
		foreach($rows as $k=>$rs){
			$face =  $name = '';
			if(isset($farr[$rs[$fid]])){
				$face = $farr[$rs[$fid]]['face'];
				$name = $farr[$rs[$fid]]['name'];
			}	
			$rows[$k]['face'] 	  = $this->getface($face);
			$rows[$k]['sendname'] = $name;
		}
		return $rows;
	}
	
	
	/**
		发送单人信息
		$lx = 0 app发送 1web客户端
	*/
	public function senduser($sendid,$receid, $cans=array(), $lx=0)
	{
		$cont		= '';
		if(isset($cans['cont']))$cont=$cans['cont'];
		$optdt		= $this->rock->now;
		$fileid		= 0;
		$msgid		= '';
		if(isset($cans['optdt']))$optdt=$cans['optdt'];
		if(isset($cans['sendid']))$sendid=$cans['sendid'];
		if(isset($cans['fileid']))$fileid=$cans['fileid'];
		if(isset($cans['msgid']))$msgid=$cans['msgid'];
		$pushcont	= arrvalue($cans, 'pushcont');
		$arr = array(
			'cont'		=> $cont,
			'sendid'	=> $sendid,
			'receid'	=> $receid,
			'type'		=> 'user',
			'optdt'		=> $optdt,
			'zt'		=> '0',
			'fileid'	=> $fileid,
			'msgid'		=> $msgid
		);
		$arr['receuid'] = $arr['sendid'].','.$arr['receid'];
		$bo = $this->insert($arr);
		$arr['id'] 		= $this->db->insert_id();
		$arr['nuid'] 	= $this->rock->request('nuid');
		$farr			= array();
		if($fileid>0){
			m('file')->addfile($fileid, 'im_mess', $arr['id']);
			$farr = m('file')->getone($fileid,'filesizecn,fileext,thumbpath,filename');
			if($farr)foreach($farr as $fk=>$fv)$arr[$fk]		= $fv;
		}
		//给服务端发送0
		if($lx==0){
			$receids = m('admin')->getonline($arr['receid']);
			if($receids != ''){
				$pusharr 	= array(
					'cont' 	=> $cont,
					'type' 	=> 'user',
					'optdt' => $optdt,
					'messid' => $arr['id'],
					'fileid' => $fileid
				);
				if($farr)foreach($farr as $fk=>$fv)$pusharr[$fk] = $fv;
				$this->sendpush($arr['sendid'], $receids , $pusharr);
			}
		}
		//告诉app端也有推送，因为app也用到websocket连接服务端
		
		
		$this->addhistory('user', $receid, $sendid, $optdt, $cont, $sendid,'','', $arr['id']);
		if($sendid!=$receid)$this->addhistory('user', $sendid, $receid, $optdt, $cont, $sendid,'','', $arr['id']);
		
		//推送的原生App上(使用异步推送哦)
		$tuicont['sendid'] 		= $arr['sendid'];
		$tuicont['sendname'] 	= $this->adminname;
		$tuicont['name'] 		= $this->adminname;
		$tuicont['cont'] 		= $cont;
		$tuicont['pushcont'] 	= $pushcont;
		$tuicont['pushtype'] 	= 'chat'; //推送消息类型
		$tuicont['receid'] 		= $receid;
		$tuicont['fileid'] 		= $fileid;
		$tuicont['type'] 		= 'user';
		$tuicont['id'] 			= $arr['id'];
		$tuicont['optdt'] 		= $optdt;
		$tuicont['optdts'] 		= substr($optdt,11,5);
		$this->pushapp($receid, '['.$this->adminname.']发来一条消息', $tuicont, $lx);
		
		/*
		$last	= date('Y-m-d H:i:s', time()-15);
		$where 	= "`uid`='$receid' and `online`=1 and `cfrom` in('appandroid','appios') and `moddt`<'$last'";
		$tos 	= m('logintoken')->rows($where);
		if($tos>0){//没有打开应用
			$conts = substr($this->rock->jm->base64decode($cont),0,99);
			c('JPush')->send($receid,'['.$this->adminname.']发来一条消息', ''.$this->adminname.':'.$conts, 1);
		}*/
		
		return $arr;
	}
	
	/**
		发送群讨论信息
		$lx = 0 app发送 1web客户端
	*/
	public function sendgroup($sendid, $gid, $cans=array(), $lx=0)
	{
		$cont		= '';
		if(isset($cans['cont']))$cont=$cans['cont'];
		$receid		= $gid;
		$grs 		= $this->getgroupxinxi($gid);
		$gname		= $grs['name'];
		$type		= 'group';
		$fileid		= 0;
		$msgid		= '';
		$optdt		= $this->rock->now;
		if(isset($cans['optdt']))$optdt=$cans['optdt'];
		if(isset($cans['type']))$type=$cans['type'];
		if(isset($cans['sendid']))$sendid=$cans['sendid'];
		if(isset($cans['fileid']))$fileid=$cans['fileid'];
		if(isset($cans['msgid']))$msgid=$cans['msgid'];
		$aors		= m('im_groupuser')->getall("`gid`='$receid'",'uid');
		$asid		= $asids =  '';
		foreach($aors as $k=>$rs){
			$_uid = $rs['uid'];
			if($_uid != $sendid)$asid.=','.$_uid;
			$asids.=','.$_uid;
		}
		
		if($asids != '')$asids = substr($asids, 1);	
		$arr = array(
			'cont'		=> $cont,
			'sendid'	=> $sendid,
			'receid'	=> $receid,
			'receuid'	=> $asids,
			'type'		=> $type,
			'optdt'		=> $optdt,
			'zt'		=> '1',
			'fileid'	=> $fileid,
			'msgid'		=> $msgid
		);
		$bo = $this->insert($arr);
		$arr['id'] 		= $this->db->insert_id();
		$arr['nuid'] 	= $this->rock->request('nuid');
		$arr['gid'] 	= $receid;
		if($asid != ''){
			$asid = substr($asid, 1);
			$this->db->insert('[Q]im_messzt','`mid`,`uid`,`gid`','select '.$arr['id'].',`id`,'.$gid.' from `[Q]admin` where id in('.$asid.') and `status`=1', true);
		}
		$arr['receid']	= $asid;
		$farr			= array();
		if($fileid>0){
			m('file')->addfile($fileid, 'im_mess', $arr['id']);
			$farr = m('file')->getone($fileid,'filesizecn,fileext,thumbpath,filename');
			if($farr)foreach($farr as $fk=>$fv)$arr[$fk]		= $fv;
		}
		
		//推送到PC客户端上
		if($lx==0 && $asid != ''){
			$receids = m('admin')->getonline($asid);
			if($receids != ''){
				$pusharr	= array(
					'cont' 	=> $cont,
					'type' 	=> 'group',
					'gid'	=> $gid,
					'gname'	=> $gname,
					'optdt' => $optdt,
					'gface' => arrvalue($grs,'face'),
					'messid' => $arr['id'],
					'fileid' => $fileid
				);
				if($farr)foreach($farr as $fk=>$fv)$pusharr[$fk] = $fv;
				$this->sendpush($arr['sendid'], $receids , $pusharr);
			}
		}
		$cont1 = $this->rock->jm->base64encode(''.$this->adminname.':'.$this->rock->jm->base64decode($cont).'');
		$this->addhistory('group', $gid, $arr['receuid'], $optdt, $cont1,$sendid,'','', $arr['id']);
		
		//推送的原生App上(使用异步推送哦)
		if($asid != ''){
			$tuicont['sendid'] 		= $arr['sendid'];
			$tuicont['sendname'] 	= $this->adminname;
			$tuicont['cont'] 		= $cont1;
			$tuicont['name'] 		= $gname;
			$tuicont['pushtype'] 	= 'chat'; //推送消息类型
			$tuicont['receid'] 		= $receid;
			$tuicont['fileid'] 		= $fileid;
			$tuicont['type'] 		= 'group';
			$tuicont['id'] 			= $arr['id'];
			$tuicont['optdt'] 		= $optdt;
			$tuicont['optdts'] 		= substr($optdt,11,5);
			$this->pushapp($asid, '['.$gname.']发来一条消息', $tuicont, $lx);
		}
		/*
		if($asid != ''){
			$last	= date('Y-m-d H:i:s', time()-15);
			$where 	= "`uid` in($asid) and `online`=1 and `cfrom` in('appandroid','appios') and `moddt`<'$last'";
			$tos 	= m('logintoken')->rows($where);
			if($tos>0){//有打开应用
				$conts = substr($this->rock->jm->base64decode($cont),0,99);
				c('JPush')->send($asid,'['.$gname.']发来一条消息', ''.$this->adminname.':'.$conts, 1);
			}
		}*/
		$arr['gname'] = $gname;
		return $arr;
	}
	
	public function sendinfor($type, $sendid, $gid, $cans=array(), $lx=0)
	{
		$arr 		= array();
		if($type == 'user'){
			$arr 	= $this->senduser($sendid, $gid, $cans, $lx);
		}
		if($type == 'group'){
			$arr 	= $this->sendgroup($sendid, $gid, $cans, $lx);
		}
		return $arr;
	}
	
	/**
	*	获取用户的app上设置别名，也就是token拉
	*/
	public function getalias($uid, $lx=0)
	{
		if($uid=='')return false;
		$where 	= "id in($uid) and ";
		if($uid=='all'){
			$where='';
		}else{
			if($this->contain($uid,'u') || $this->contain($uid,'d')){
				$uid = m('admin')->gjoin($uid);
				if($uid=='')return false;
				$where 	= "id in($uid) and ";
			}
		}
		$uwhere = "$where `status`=1";
		$rows 	= m('logintoken')->getrows("`uid` in(select id from `[Q]admin` where $uwhere) and `cfrom` in ('appandroid','nppandroid','nppios') and `online`=1",'*','id desc');
		$alias 	= $uida = $xmalias = $oldalias = $pushuids = $newalias = $alias2019 = $uid2019  =array();
		$uids	= '0';
		$times  = date('Y-m-d H:i:s', time()-5*60);//5分钟
		foreach($rows as $k=>$rs){
			$_uid 	 = $rs['uid'];
			$_web 	 = $rs['web'];
			//if(in_array($_uid, $uida))continue;
			$uida[]  = $_uid;
			$uids	.= ','.$_uid.'';
			if($_web=='xiaomi'){
				$xmalias[] = $rs['token'];
			}else if(in_array($rs['cfrom'], array('nppandroid','nppios'))){//2019-11-25最新新app
				$nestr = ''.$rs['token'].'|'.$rs['web'].'|'.$_uid.'|';
				if(contain($rs['web'],'huawei') && !contain($rs['ip'],'.'))$nestr.=''.$rs['ip'].'';
				$alias2019[] = $nestr;
				$uid2019[]   = $_uid;
			}else if(substr($_web,0,4)=='app_'){
				$newalias[] = $rs['token'];	
			}else if(substr($_web,0,4)=='apk_'){
				$oldalias[] = $rs['token'];	
			}else{
				$alias[] 	= $rs['token'];
			}
			if($rs['ispush']=='1')$pushuids[] = $_uid;//可以手机推送的用户
		}
		return array('alias' => $alias, 'uids'=>$uids, 'xmalias'=>$xmalias, 'oldalias'=>$oldalias, 'newalias'=>$newalias,'alias2019'=>$alias2019,'uid2019'=>$uid2019,'pushuids'=>$pushuids);
	}
	
	/**
	*	推送到原生app上
	*/
	public function pushapp($receid, $title, $conta, $lx)
	{
		$alias = $this->getalias($receid, $lx);
		if(!$alias)return false;
		//$alias	= $garr['alias'];
		//if(!$alias)return false;
		$uids		= $alias['uids'];
		if($uids=='0')return;
		$contjson	= '';
		foreach($conta as $k=>$v)$contjson.=',"'.$k.'":"'.$v.'"';
		$contjson 	= '{'.substr($contjson,1).'}';
		
		//最新webapp也用服务端推送
		$uid2019	= $alias['uid2019'];
		$alias2019	= $alias['alias2019'];
		if($uid2019){
			$reimtype = $this->option->getval('reimservertype');
			$reimappwx= $this->option->getval('reimappwxsystem');
			if($reimtype=='1' && $reimappwx=='1'){
				$gbarr = $this->pushserver('sendapp', array(
					'receid' => join(',', $uid2019)
				));
				//服务端返回{"zshu":2,"yfuid":"1,8","wfuid":""}
				if($gbarr && $gbarr['success'] && $bstr = arrvalue($gbarr, 'data')){
					$data = json_decode($bstr, true);
					$yfuid= explode(',', arrvalue($data, 'yfuid'));
					if($yfuid){
						$nealas = array();
						foreach($alias2019 as $alis){
							$bo = false;
							foreach($yfuid as $yfid){if(contain($alis,'|'.$yfid.'|'))$bo=true;};
							if(!$bo)$nealas[] = $alis;
						}
						$alias['alias2019'] = $nealas;
					}
				}
			}
		}
		$pushcont = arrvalue($conta,'pushcont');
		if(!$pushcont)$pushcont = arrvalue($conta,'cont'); //推送的内容已经是base64的
		return c('JPush')->push($title, $pushcont, $contjson, $alias);
	}
	
	/**
	*	推送到服务端运行
	*/
	public function sendpush($sendid, $receid, $conarr=array())
	{
		$bsarr 	= array('msg'=>'notpushurl','code'=>2);
		$bstt	= json_encode($bsarr);
		if($sendid==0)$sendid = 1;
		$sers 	= $this->db->getone('[Q]admin',"`id`='$sendid'", "`name`,`face`");
		if(!$sers)return $bstt;
		$face 	= $sers['face']; 
		$carr['adminid'] 	= $sendid;
		$carr['optdt'] 		= $this->rock->now;
		$carr['sendname'] 	= $sers['name'];
		$carr['face'] 		= $this->getface($face); //发送人头像
		$carr['receid'] 	= $receid;
		foreach($conarr as $k=>$v)$carr[$k]=$v;
		return $this->pushserver('send', $carr);
	}
	
	/**
	*	推送发送命令类型
	*/
	public function sendcmd($receid, $conarr=array())
	{
		$conarr['type'] = 'cmd';
		return $this->sendpush($this->adminid, $receid, $conarr);
	}
	
	/**
	*	向服务端发送异步任务
	*	$runtime 运行的时间贞
	*/
	public function asynurl($m, $a,$can=array(), $runtime=0)
	{
		$asyn   = (int)getconfig('asynsend','0');
		$runurl	= m('base')->getasynurl($m, $a,$can);
		
		$queuelogid= m('log')->addlogs('异步队列','', 3);
		if($queuelogid)$runurl.= '&queuelogid='.$queuelogid.''; 
		
		//用官网VIP异步
		if($asyn==2){
			$barr = c('xinhuapi')->sendanay($m, $a,$can, $runtime);
			if($barr['success'])return true;
		}
		
		$barr =  $this->pushserver('runurl', array(
			'url' => $runurl,
			'runtime' => $runtime
		));
		
		if($queuelogid){
			m('log')->update(array(
				'url' => $runurl,
				'remark'=> '[asynurl]'.$runtime.'',
			),$queuelogid);
		}
		
		return $barr['success'];
	}
	
	/**
	*	获取得到推送的端口号
	*/
	public function getpushhostport($str)
	{
		$host = ''; $port = 0;
		$stra = explode('//', $str);
		if(isset($stra[1])){
			$strb 	= explode(':', str_replace('/','', $stra[1]));
			$host	= $strb[0];
			$port	= (int)arrvalue($strb, 1, '0');
		}
		return array('host'=>$host,'port'=>$port);
	}

	/**
	*	服务端推送，返回boolean看是否成功
	*/
	public function pushserver($atype, $cans=array())
	{
		if(isempt($this->serverpushurl))return returnerror('没配置服务端');

		$carr['from'] 	= $this->serverrecid;
		$carr['adminid']= $this->adminid;
		$carr['atype'] 	= $atype;
		$carr['qtype'] 	= 'reim';
		foreach($cans as $k=>$v)$carr[$k]=$v;
		
		$reimtype = $this->option->getval('reimservertype');
		if($reimtype=='1')return c('rockqueue')->pushdata($carr);
		
		$str 			= json_encode($carr);
		//echo 'abc ';return array('code'=>0);
		$posts			= $this->getpushhostport($this->serverpushurl);
		$barr 			= c('socket')->udppush($str, $posts['host'], $posts['port']);

		return $barr;
	}
	
	/**
	*	判断异步地址是否可以使用
	*/
	public function asynurlbo()
	{
		$url 	= $this->serverpushurl;
		if(isempt($url))return false;
		$ishttp	= substr($url,0, 4)=='http';
		
		if($ishttp){
			$str = c('curl')->getcurl($url);
			return contain($str, 'msg');
		}else{
			$spath	= str_replace('\\','/', $url);
			return is_writable($spath.'/Rock/push');
		}
	}
	
	
	
	//创建群等
	public function creategroup($name, $receid, $type=1, $explain='')
	{
		$arr['name'] 		= $name;
		$arr['type'] 		= $type;
		$arr['createid'] 	= $this->adminid;
		$arr['createname'] 	= $this->adminname;
		$arr['createdt'] 	= $this->rock->now;
		$arr['explain'] 	= $explain;
		$arr['valid'] 		= 1;
		$gid 	= m('im_group')->insert($arr);
		$this->db->insert('[Q]im_groupuser','gid,uid','select '.$gid.',id from [Q]admin where id in('.$receid.') and `status`=1', true);
		$arr['id']			= $gid;
		$arr['type']		= 'group';
		return $arr;
	}
	
	/**
	*	下载同步聊天记录到app本地
	*	$uid 对应用户，$maxid 从哪个最大的id，$minid从哪个最小Id
	*	最多下载20天内的记录，每次下载30条
	*/
	public function downrecord($uid, $maxid=0, $minid=999999999)
	{
		$whes	= $this->rock->dbinstr('receuid', $uid);
		$lastdt = date('Y-m-d 00:00:00', time()-20*24*3600);
		$limit	= 30;
		$fields	= 'optdt,zt,id,`type`,receid,cont,sendid,fileid';
		$sql1	= "select $fields from `[Q]im_mess` where `id`> $maxid and $whes";
		if($maxid==0){
			$sql1.=' order by id desc';
		}else{
			$sql1.=' order by id asc';
		}
		$sql1.=' limit '.$limit.'';
		$rows 	= $this->db->getall($sql1);
		$nsaid	= '0';
		foreach($rows as $k=>$rs)$nsaid.=','.$rs['id'].'';
		$olimie	= $limit-$this->db->count;
		if($olimie>0 && $minid>1){
			$sql2	= "select $fields from `[Q]im_mess` where `id`< $minid and `optdt`>='$lastdt' and `id` not in($nsaid) and $whes order by id desc limit $olimie";
			$rowss 	= $this->db->getall($sql2);
			if($rowss)$rows 	= array_merge($rows, $rowss);
		}
		$suids	= '0';
		$dbs 	= m('im_messzt');
		foreach($rows as $k=>$rs){
			$suids.= ','.$rs['sendid'];
			if($rs['type'] != 'user'){
				$zt = 0;
				if($dbs->rows("`mid`='".$rs['id']."' and `uid`='$uid'")==0)$zt=1;
				$rows[$k]['zt'] = $zt;
			}
			if($rs['sendid']==$uid)$rows[$k]['zt'] = 1;
			$id = (int)$rs['id'];
			if($id>$maxid)$maxid = $id;
			if($id<$minid)$minid = $id;
		}
		$rows 	= $this->ivaregarr($suids, $rows);
		$rows	= $this->replacefileid($rows);
		$isdown	= '0';
		if(count($rows)==$limit)$isdown = '1'; //需要继续下载
		$arr['rows'] 	= $rows;
		$arr['maxid'] 	= $maxid;
		$arr['minid'] 	= $minid;
		$arr['isdown'] 	= $isdown;
		return $arr;
	}
	
	
	/**
	*	删除服务器上记录
	*/
	public function clearrecord($type,$gid, $uid, $ids='',$day=0)
	{
		$whes	= $this->rock->dbinstr('receuid', $uid);
		$this->setallyd($type,$uid, $gid);
		if(!isempt($type)){
			if($type=='user'){
				$where1	= "`type`='$type' and ((`receid`='$uid' and `sendid`='$gid') or (`receid`='$gid' and `sendid`='$uid')) and $whes";
			}else{
				$where1	= "`type`='$type' and `receid`='$gid' and $whes";
			}
		}else{
			$where1 = $whes;
		}
		if($ids!='')$where1.=" and `id` in($ids)";
		if($day>0){
			$dts = date('Y-m-d H:i:s',time()-$day*24*3600);
			$where1.=" and `optdt`< '$dts'";
		}
		$rows 	= $this->getall($where1, '`receuid`,`id`');
		$xids	= '0';
		foreach($rows as $k=>$rs){
			$sid = $rs['id'];
			if($this->isempt($rs['receuid'])){
				$xids.=','.$sid.'';
			}else{
				$ssid  = '';
				$uidsa = explode(',', $rs['receuid']);
				foreach($uidsa as $suid){
					if($suid != $uid){
						$ssid.=','.$suid.'';
					}
				}
				if($ssid==''){
					$xids.=','.$sid.'';
				}else{
					$ssid = substr($ssid,1);
					$this->update("`receuid`='$ssid'", $sid);
				}
			}
			$this->hisobj->update("`cont`=''", "`type`='$type' and `uid`='$uid' and `messid`='$sid'");
		}
		if($xids!='0')$this->delete("`id` in($xids)");
		if($ids=='' && $day==0)$this->delhistory($type,$gid, $uid);
	}
	
	/**
	*	转发
	*/
	public function forward($tuid, $type, $cont, $fid=0)
	{
		$uid 	= $this->adminid;
		if($fid>0){
			$frs	= m('file')->getone($fid, '`filepath`,`filename`,`filesizecn`,`fileext`');
			$msg 	= '文件不存在了';
			if(!$frs)return $msg;
			if(!file_exists($frs['filepath']))return $msg;
			$cont 	= '';$jpgallext	= '|jpg|png|gif|bmp|jpeg|';
			if(contain($jpgallext,'|'.$frs['fileext'].'|')){
				$cont = '[图片 '.$frs['filesizecn'].']';
			}else{
				$cont = '['.$frs['filename'].' '.$frs['filesizecn'].']';
			}
			$cont	  = $this->rock->jm->base64encode($cont);
		}
		$tuids	= explode(',', $tuid);
		foreach($tuids as $gid)$this->sendinfor($type, $uid, $gid, array(
			'optdt' => $this->rock->now,
			'cont'  => $cont,
			'fileid'=> $fid
		));
		return 'ok';
	}
	
	//会话管理的
	public function createchat($name, $aid, $uids='', $na='', $optdt='', $iscjwx=false)
	{
		if($optdt=='')$optdt=$this->rock->now;
		if($na=='')$na = $this->adminname;
		if($uids=='')$uids = $aid;
		$this->db->record('[Q]im_group', array(
			'type'			=> 1,
			'name'			=> $name,
			'createid'		=> $aid,
			'createname'	=> $na,
			'createdt'		=> $optdt,
			'valid'			=> '1'
		));
		$gid	= $this->db->insert_id();
		$this->adduserchat($gid, $uids, false);
		return $gid;
	}
	//邀请
	public function adduserchat($gid, $uids, $isadd=false)
	{
		if(isempt($uids))return '';
		$ids 	= '';
		$uidss	= explode(',', $uids);
		$db		= m('im_groupuser');
		foreach($uidss as $aid){
			if($db->rows("gid='$gid' and `uid`='$aid'")==0){
				$db->insert(array('gid' => $gid,'uid' => $aid));
				$ids .= ','.$aid.'';
			}
		}
		if($ids!=''){
			$ids = substr($ids,1);
			$unaem = '';
			$urows = m('admin')->getall('`id` in('.$ids.')');
			foreach($urows as $k=>$rs)$unaem.=','.$rs['name'].'';
			if($unaem!=''){
				$this->addxitong($gid, ''.$this->adminname.'邀请“'.substr($unaem,1).'”加入本会话');
			}
		}
		return $ids;
	}
	public function deluserchat($gid, $uids)
	{
		if(isempt($uids))return;
		m('im_groupuser')->delete("`gid`='$gid' and `uid` in($uids)");
	}
	public function deletechat($gid)
	{
		m('im_group')->delete($gid);
		m('im_groupuser')->delete("`gid`='$gid'");
		m('im_messzt')->delete("`gid`='$gid'");
		$this->delhistory('group',$gid, 0);
	}
	public function exitchat($gid, $aid)
	{
		$names = m('admin')->getmou('name', $aid);
		$this->addxitong($gid, ''.$names.'退出本会话');
		$dbs = m('im_groupuser');
		$dbs->delete("`gid`='$gid' and `uid`='$aid'");
		m('im_messzt')->delete("`gid`='$gid' and `uid`='$aid'");
		if($dbs->rows('gid='.$gid.'')==0)m('im_group')->delete($gid);
		$this->delhistory('group',$gid, $aid);
	}
	public function addxitong($gid, $cont, $fid=0)
	{
		$this->sendinfor('group', $this->adminid, $gid, array(
			'optdt' => $this->rock->now,
			'cont'  => $this->rock->jm->base64encode($cont),
			'fileid'=> $fid
		));
	}
	
	//修改会话名称
	public function editname($gid, $name)
	{
		m('im_group')->update("`name`='$name'",$gid);
		$this->addxitong($gid, ''.$this->adminname.'将会话名称修改为“'.$name.'”');
	}
	
	//修改头像
	public function editface($gid, $fileid)
	{
		$face= '';
		if($fileid>0){
			$frs = m('file')->getone($fileid);
			if($frs)$face= $frs['thumbpath'];
		}
		m('im_group')->update("`face`='$face'",$gid);
	}
	
	
	
	
	//微信消息回调（弃用了）
	public function getwxchat($arr)
	{
		$this->rock->debugs(json_encode($arr),'cccc');if(!isset($arr['MsgType']))return;
		$MsgType		= $arr['MsgType'];
		$FromUserName	= $arr['FromUserName'];
		$user 			= $FromUserName;
		$urs		 	= m('admin')->getone("`user`='$FromUserName'",'id,name');
		if(!$urs)return;
		$sendid			= $urs['id'];
		$sendname		= $urs['name'];
		$this->adminid	= $sendid;
		$this->adminname= $sendname;
		if($MsgType == 'event'){
			$event	= $arr['Event'];
			if($event=='create_chat')m('weixin:chat')->addchat($sendid, $sendname,$arr);
			if($event=='update_chat')m('weixin:chat')->updatechat($arr);
			if($event=='quit_chat')m('weixin:chat')->quitchat($arr);
			if($event=='subscribe')m('weixin:user')->subscribe($user,1);
			if($event=='unsubscribe')m('weixin:user')->subscribe($user,4);
			return;
		}
		if(!isset($arr['Type']))return;
		$Type			= $arr['Type'];
		$gid 			= 0;
		$optdt 			= date('Y-m-d H:i:s', $arr['CreateTime']);
		$cont 			= '';
		if($Type=='single' || $Type=='userid'){
			$gid = (int)m('admin')->getmou('id', "`user`='".$arr['Id']."'");
			$type= 'user';
		}
		if($Type=='group'){
			$gid = m('weixin:chat')->getchatid($arr['Id'], $sendid, $sendname);
			$type= 'group';
		}
		if($gid==0)return;
		
		@$msgid	= $arr['MsgId'];if(isempt($msgid))return;
		if($this->rows("`msgid`='$msgid'")>0)return;
		
		if($MsgType=='text'){
			$cont = $arr['Content'];
		}
		if($MsgType=='location'){
			$cont = '位置：'.$arr['Label'];
		}
		if($MsgType=='voice'){
			$cont = '语音,请用微信收听';
			if(isset($arr['MediaId']))$this->asynurl('asynrun','downwxmedia', array(
				'mediaid' 	=> $arr['MediaId'],
				'msgid' 	=> $msgid,
				'fileext'	=> 'amr',
				'adminid'	=> $sendid	
			));
		}
		if($MsgType=='image'){
			$cont = '[图片]';
			$PicUrl = $this->rock->jm->encrypt($arr['PicUrl']);
			$this->asynurl('asynrun','downwxpic', array(
				'picurl' 	=> $PicUrl,
				'msgid' 	=> $msgid,
				'adminid'	=> $sendid
			));
		}
		if($MsgType=='link'){
			if(isempt($arr['Title']))$arr['Title']='链接';
			$cont = '[A]'.$arr['Title'].'|'.$arr['Url'].'[/A]';
		}
		if($cont!='')$this->sendinfor($type,$sendid, $gid, array(
			'cont'  => $this->rock->jm->base64encode($cont),
			'optdt' => $optdt,
			'msgid' => $msgid
		));
	}
	
	//下载微信上图片
	public function downwximg($url, $msgid)
	{
		if($url=='' || $msgid=='')return;
		$cont	= c('curl')->getcurl($url);
		$barr 	= c('down')->createimage($cont,'jpg','微信图片');
		if($barr){
			$fileid 	= $barr['id'];
			$filesize 	= $barr['filesizecn'];
			$mors 		= $this->getone("`msgid`='$msgid'",'id');
			if($mors){
				$id = $mors['id'];
				$this->update(array('fileid' => $fileid), $id);
				m('file')->addfile($fileid, 'im_mess', $id);
			}
		}
	}
	
	/**
	*	定时未读的会话消息提醒推送到微信上
	*/
	public function chatpushtowx($dt='')
	{
		if(getconfig('platdwnum'))return false;
		if($dt=='')$dt = date('Y-m-d H:i:s', time()-5*60);
		//$bowx 	= $this->installwx(0);
		$bowxqy	= $this->installwx(1);
		if(!$bowxqy)return;
		
		$rows 	= $this->db->getall("select * from `[Q]im_history` where `optdt`>='$dt' and `stotal`>0 and `type` in('user','group') order by `uid`,`optdt` asc");
		
		$uarrs 	= array();
		$gusrra = array();
		foreach($rows as $k=>$rs){
			$rson = false;
			$key  = $rs['type'].$rs['receid'];
			$face 	= 'images/noface.png';
			if($rs['type']=='group')$face	= 'images/group.png';
			if(!isset($gusrra[$key])){
				if($rs['type']=='user'){
					$rson  	= $this->db->getone('[Q]admin', $rs['receid'], 'name,face');
				}else{
					$rson  	= $this->db->getone('[Q]im_group', $rs['receid'], 'name,face');
				}
			}else{
				$rson		= $gusrra[$key];
			}
			if(!$rson)continue;
			$gusrra[$key] = $rson;
			$rs['name'] = $rson['name'];
			$rs['face'] = $this->getface($rson['face'], URL.$face);
			$uarrs[$rs['uid']][] = $rs;
		}
		
		$sendarr	= array();
		
		if($uarrs)foreach($uarrs as $uid=>$usend){
			$cont 	= $tites  = '';
			$zshu	= 0;
			if($usend)foreach($usend as $k=>$rs){
				$zshu++;
				if($k>0)$cont.="\n";
				$cont.=''.$rs['name'].'：'.$this->rock->jm->base64decode($rs['cont']).' ('.substr($rs['optdt'],11,5).')';
			}
			if($zshu==0)continue;
			if($zshu==1){
				$title 	= '你有['.$rs['name'].']未读会话消息';
				$url 	= ''.URL.'?m=chat&d=we&type='.$rs['type'].'&uid='.$rs['receid'].'';
			}else{
				$title 	= '你有'.$zshu.'个未读会话消息';
				$url 	= ''.URL.'?d=we#list';
			}
			
			$wxarr		= array(
				'title'			=> $title,
				'description' 	=> $cont,
				'url'			=> $url,
				'uid'			=> $uid
			);
			
			//根据内容分组发送
			$contkey 	= md5($cont);
			$sendarr[$contkey][] = $wxarr;
		}
		
		$devagent  = $this->optiondb->getval('weixinqy_devagent');
		if(isempt($devagent))$devagent = '办公助手';
		
		foreach($sendarr as $key=>$rowss){
			$uids = '';
			$wxarr= $rowss[0];
			foreach($rowss as $k=>$rs){
				$uids.=','.$rs['uid'].'';
			}
			//发送
			if($uids!=''){
				$uids = substr($uids, 1);
				if($bowxqy){
					$barr = m('weixinqy:index')->sendxiao($uids, 'REIM,REIM助手,'.$devagent.'', $wxarr);
					m('log')->todolog('企业微信提醒', $barr);
				}
			}
		}
	}
	
	/**
	*	撤回消息功能
	*/
	public function chehuimess($type, $gid, $id)
	{
		$chehui 	= (int)$this->optiondb->getval('reimchehuisystem',0);
		if($chehui<=0)return '没有开启此功能';
		
		$createid	= m('im_group')->getmou('createid', $gid);
		$rs 	 	= $this->getone('`id`='.$id.'');
		if(!$rs)return '不存在';
		
		if($createid != $this->adminid){
			if($rs['sendid'] != $this->adminid)return '不是你发的';
			$t3 = time()-strtotime($rs['optdt']);
			if($t3>$chehui*60)return '已经超过'.$chehui.'分钟无法撤回';
		}
		
		$msg1= '<del style="color:gray">已撤回</del>';
		$msg = $this->rock->jm->base64encode($msg1);
		$msg2 = $this->rock->jm->base64encode($this->adminname.':');
		$this->update("`cont`='$msg',`fileid`=0", $id);
		$this->hisobj->update("`cont`='".$msg2.$msg."',`optdt`='{$this->rock->now}'", "`messid`='$id'");
		
		$pusharr 	= array(
			'cont' 	=> $msg,
			'type' 	=> 'chehui',
			'messid' => $id,
		);
		$this->sendpush($this->adminid, $rs['receuid'], $pusharr);
		$pusharr['atype'] = 'sendapp';
		$this->sendpush($this->adminid, $rs['receuid'], $pusharr);
		return array(
			'receid' => $rs['receuid'],
			'id'	=> $id,
			'msg'	=> $msg,
			'msg1'	=> $msg1,
		);
	}
}