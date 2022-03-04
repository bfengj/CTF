<?php
class adminClassModel extends Model
{
	private $_getjoinstr = array();
	public $nowurs;
	
	public function gjoin($joinid, $glx='', $blx='bxl')
	{
		$uid 	= $did = $gid = '0';
		if($glx=='')$glx = 'ud';
		if(isempt($joinid))return '';
		$joinid 	= strtolower($joinid);
		if(contain($joinid, 'all'))return 'all';
		if($blx!='where' && $glx!='d' && !contain($joinid,'u')
			&& !contain($joinid,'d') && !contain($joinid,'g'))return $joinid;
		$narr 	= explode(',', $joinid);
		$dwhe	= array();
		foreach($narr as $sid){
			if(isempt($sid))continue;
			$lx 	= substr($sid, 0, 1);
			$ssid 	= str_replace(array('u','d','g'), array('','',''), $sid);
			if($lx == 'd' || $glx=='d'){
				$did.=','.$ssid.'';
				$dwhe[] = "instr(`deptpath`, '[$ssid]')>0";
			}else if($lx=='g'){
				$gid.=','.$ssid.'';
			}else{
				$uid.=','.$ssid.'';
			}
		}
		$where = '';
		if($gid!='0'){
			$uids = $this->getgrouptouid($gid);
			if($uids!='')$uid.=','.$uids.'';
		}
		if($did != '0'){
			$where = join(' or ', $dwhe);
			if($uid!='0')$where.=" or `id` in($uid)";
		}else{
			if($uid!='0')$where ="`id` in($uid)";
		}
		if($blx == 'where')return $where;
		$guid = '';
		if($where!=''){
			$swhe = '`status`=1';
			if($blx=='all')$swhe = '1=1';
			$swhe.= $this->getcompanywhere();
			$rows = $this->getall("$swhe and ($where)", '`id`');
			foreach($rows as $k=>$rs)$guid.=','.$rs['id'].'';
			if($guid !='')$guid = substr($guid, 1);
		}
		return $guid;
	}
	
	/**
	*	根据组部门id获取底下人员ID
	*/
	public function gjoins($receid)
	{
		if(contain($receid,'u') || contain($receid, 'd') || contain($receid, 'g'))$receid = $this->gjoin($receid);
		return $receid;
	}
	
	/**
	*	根据组获取底下人员Id
	*/
	public function getgrouptouid($gid)
	{
		if(isempt($gid))return '';
		$where 	= "1=1 and ((`type`='gu' and `mid` in($gid)) or (`type`='ug' and `sid` in($gid)))";
		$rows  	= $this->db->getall("select `type`,`mid`,`sid` from `[Q]sjoin` where $where");
		$uids 	= array();
		foreach($rows as $k=>$rs){
			$uid = '';
			if($rs['type']=='gu')$uid =$rs['sid'];
			if($rs['type']=='ug')$uid =$rs['mid'];
			if($uid!='' && !in_array($uid, $uids)){
				$uids[]= $uid;
			}
		}
		return join(',', $uids);
	}

	/**
	*	判断某个id是不是在里面,权限
	*/
	public function containjoin($joinid, $myid=0, $glx='ud')
	{
		$bo 	= false;
		$wh 	= $this->gjoin($joinid, $glx, 'where');
		if($wh == 'all')$bo = true;
		if(!$bo && $wh != ''){
			if($this->rows("`id`='$myid' and ($wh)")>0)$bo = true;
		}
		return $bo;
	}
	
	public function getjoinstr($fids, $us, $lx=0, $slx=0)
	{
		$s 		= '';
		if(is_numeric($us)){
			$key= 'a'.$fids.''.$us.'_'.$lx.'_'.$slx.'';
			if(isset($this->_getjoinstr[$key]))return $this->_getjoinstr[$key];
			$us	= $this->getone($us,'id,`name`,`deptid`,`deptpath`,`type`');
		}
		if(!$us)return '';
		$this->nowurs = $us;
		$uid	= $us['id'];
		$key 	= 'a'.$fids.''.$uid.'_'.$lx.'_'.$slx.'';
		if(isset($this->_getjoinstr[$key]))return $this->_getjoinstr[$key];
		if($slx==0)$tj[]	= "ifnull($fids,'')=''";
		$tj[]	= $this->rock->dbinstr($fids, 'all');
		$tj[]	= $this->rock->dbinstr($fids, 'u'.$uid);
		if($us){
			$dep = explode(',', $us['deptpath']);
			foreach($dep as $deps){
				$_deps 	= str_replace(array('[',']'), array('',''), $deps);
				$tj[]	= $this->rock->dbinstr($fids, 'd'.$_deps);
			}
			//所在组Id
			$gids 		= m('sjoin')->getgroupid($uid);
			if($gids!='0'){
				$gidsa	= explode(',', $gids);
				foreach($gidsa as $k1=>$gid1)if($k1>0)$tj[]	= $this->rock->dbinstr($fids, 'g'.$gid1);
			}
		}
		$s	= join(' or ', $tj);
		if($s != '' && $lx==0)$s = ' and ('.$s.')';
		$this->_getjoinstr[$key] = $s;
		return $s;
	}
	
	/**
	*	获取对应部门负责人
	*/
	public function getdeptheadman($id, $lx=0)
	{
		$drs 	= $this->db->getone('[Q]dept','id='.$id.'');
		if(!$drs)return false;
		$cuid 	= $drs['headid'];
		$name 	= $drs['headman'];
		if(isempt($cuid)){
			if($lx==0){
				$lbar = $this->getdeptheadman($drs['pid'], 1);
				if($lbar){
					$cuid 	= $lbar[0];
					$name 	= $lbar[1];
				}
			}
		}
		if(isempt($cuid))return false;
		return array($cuid, $name);
	}
	
	/**
	*	获取某个人的上级主管或者领导
	*	返回 array(id,$name)
	*/
	public function getsuperman($uid)
	{
		$b 		= array(0,'');
		$urs 	= $this->getone($uid,'`superid`,`superman`,`deptid`');
		if(!$urs)return $b;
		$cuid 	= $urs['superid'];
		$name 	= $urs['superman'];
		if(isempt($cuid)){
			$deptid  = (int)$urs['deptid'];
			if($deptid > 0){
				$drs = $this->getdeptheadman($deptid);
				if($drs){
					$cuid = $drs[0];
					$name = $drs[1];
				}
			}
		}
		if(!isempt($cuid)){
			$b = array($cuid, $name);
		}
		return $b;
	}
	
	public function getjoinstrs($fids, $us, $slx=0, $lx=0)
	{
		return $this->getjoinstr($fids, $us, $lx, $slx);
	}
	
	/**
		获取人员上级主管id
	*/
	public function getup($uid)
	{
		$one 	= $this->getone($uid, 'superid,deptid');
		$rows 	= $this->getpath($one['deptid'], $one['superid']);
		$s		= $rows['superpath'];
		$s		= str_replace('[', '', $s);
		$s		= str_replace(']', '', $s);
		return $s;
	}
	
	/**
	*	递归获取人员全部上级
	*/
	public function getsuperarr($uid, $lx=0)
	{
		if($lx==0)$this->getsuperarrba = array();
		$sql 		= "select a.id,a.name,b.`headid`,a.`superid` from `[Q]admin` a left join `[Q]dept` b on a.`deptid`=b.`id` where a.`id`='$uid'";
		$rows	 	= $this->db->getall($sql);
		$superid 	= '';
		if($rows){
			$urs = $rows[0];
			$superid = $urs['superid'];
			if(isempt($superid))$superid = $urs['headid'];
		}
		if(!isempt($superid) && $lx<20){
			$superida = explode(',', $superid);
			foreach($superida as $sid){
				if($sid != $uid){
					$sna = $this->getmou('name', $sid);
					$this->getsuperarrba[] = array('id'=>$sid,'name'=>$sna);
					$this->getsuperarr($sid, $lx+1);
				}
			}
		}
		return $this->getsuperarrba;
	}
	
	private $deptarr = array();
	public function getpath($did, $sup,$dids='')
	{
		$deptpath 	= $this->db->getpval('[Q]dept', 'pid', 'id', $did, '],[');
		$deptallname= $this->db->getpval('[Q]dept', 'pid', 'name', $did, '/');
		if(!$this->deptarr)$this->deptarr = $this->db->getkeyall('[Q]dept','id,name');
		$deptname	= arrvalue($this->deptarr, $did);
		$supername	= '';
		$deptnames	= '';
		
		$superpath	= '';
		if(!isempt($sup)){
			$sua = explode(',', $sup);
			foreach($sua as $suas){
				$sss1 	= $this->db->getpval('[Q]admin', 'superid', 'id' ,$suas, '],[');
				if($sss1 != '')$superpath.=',['.$sss1.']';
				$sss2	= $this->db->getmou('[Q]admin', 'name', "`id`='$suas'");
				if(!isempt($sss2))$supername.=','.$sss2;
			}
			if($superpath!='')$superpath=substr($superpath,1);
			if($supername!='')$supername=substr($supername,1);
		}
		//部门路径
		if(!isempt($deptpath))$deptpath	= $this->rock->strformat('[?0]', $deptpath);
		//有多部门
		if(!isempt($dids)){
			$didsa = explode(',', $dids);
			foreach($didsa as $dids1){
				$desss 	= $this->db->getpval('[Q]dept', 'pid', 'id', $dids1, '],[');
				if(isempt($desss))continue;
				$desssa	= explode(',', $this->rock->strformat('[?0]', $desss));
				foreach($desssa as $desssa1){
					if(!contain($deptpath, $desssa1))$deptpath.=','.$desssa1.'';
				}
				$names1 = arrvalue($this->deptarr, $dids1);
				if(!isempt($names1))$deptnames.=','.$names1.'';
			}
			if($deptnames!='')$deptnames=substr($deptnames, 1);
		}
		if(!isempt($deptpath) && substr($deptpath,0,1)==',')$deptpath = substr($deptpath,1);
		
		$rows['deptpath'] 	= $deptpath; 
		$rows['superpath'] 	= $superpath;
		$rows['deptname'] 	= $deptname;
		$rows['deptnames'] 	= $deptnames;
		$rows['superman'] 	= $supername;
		$rows['deptallname']= $deptallname;
		
		return $rows;
	}
	
	/**
	*	获取下级人员id
	*	$lx 0 全部下级，1直属下级
	*	return 所有人员ID
	*/
	public function getdown($uid, $lx=0)
	{
		$where = $this->getdowns($uid, $lx);
		$rows = $this->getall($where, 'id');
		$s	  = '';
		foreach($rows as $k=>$rs)$s.=','.$rs['id'];
		if($s != '')$s = substr($s, 1);
		return $s;
	}
	
	/**
	*	获取下级人员id
	*	$lx 0 全部下级，1直属下级
	*	return 字符串条件
	*/
	public function getdowns($uid, $lx=0)
	{
		$where = "instr(superpath,'[$uid]')>0";
		if($lx==1)$where=$this->rock->dbinstr('superid', $uid);
		return $where;
	}
	
	/**
	*	获取下属人员Id条件记录,如我下属任务
	*	返回如( distid in(1) or uid in(2) )
	*/
	public function getdownwhere($fid, $uid, $lx=0)
	{
		$bstr = $this->getdown($uid, $lx);
		$where= '1=2';
		if($bstr=='')return $where;
		$bas  = explode(',', $bstr);
		$barr = array();
		foreach($bas as $bid){
			$barr[] = ''.$fid.' in('.$bid.')';
		}
		$where = join(' or ', $barr);
		$where = '('.$where.')';
		return $where;
	}
	
	//返回我下属字符串条件如： instr(',1,2,3,', 字段)>0;
	public function getdownwheres($fid, $uid, $lx=0)
	{
		$bstr = $this->getdown($uid, $lx);
		$where= '1=2';
		if($bstr=='')return $where;
		$bstr = ','.$bstr.',';
		$where= "instr('$bstr', concat(',',$fid,','))>0";
		return $where;
	}
	
	
	
	
	/**
	*	获取用户信息(部门，单位，职位等)
	*/
	public function getinfor($uid)
	{
		$unitname 	= $deptname = $ranking = '';
		$name	= '';
		$face	= '';
		$deptid	= '';
		$rs		= $this->getone($uid, 'name,deptname,deptid,ranking,face');
		if($rs){
			$deptname 	= $rs['deptname'];
			$ranking 	= $rs['ranking'];
			$name 		= $rs['name'];
			$deptid 	= $rs['deptid'];
			$face 		= $this->getface($rs['face']);
			if(!$this->isempt($deptid))$unitname = $this->db->getpval('[Q]dept','pid','name', $deptid);
		}
		return array(
			'id' 		=> $uid,
			'unitname' => $unitname,
			'deptname' => $deptname,
			'name' 		=> $name,
			'ranking' 	=> $ranking,
			'face' 		=> $face,
			'deptid' 	=> $deptid
		);
	}
	
	/*
		获取在线的人员Id
	*/
	public function getonline($receid, $lx=10)
	{
		$uarr 		= $this->getonlines('reim,pc', $receid, $lx);
		$jonus		= join(',', $uarr);
		return $jonus;
	}
	
	//获取对应类型在线人员
	public function getonlines($type, $teuid='all', $lx=11, $where='')
	{
		$arrs 	= array();
		$dts 	= c('date')->adddate($this->rock->now, 'i', 0-$lx);
		$wheres		= '';
		if($teuid != 'all' && $teuid!=''){
			if($this->contain($teuid,'u') || $this->contain($teuid,'d')){
				$teuid = $this->gjoin($teuid);
				if($teuid=='')return $arrs;
			}
			$wheres=" and `uid` in($teuid)";
		}
		if($lx>0){
			$wheres .= " and `moddt`>'$dts'";
		}
		$sql 	= "select `uid` from `[Q]logintoken` where instr(',".$type.",', concat(',',`cfrom`, ','))>0 and `online`=1 $wheres $where group by `uid`";
		
		$rows   = $this->db->getall($sql);
		foreach($rows as $k=>$rs){
			$arrs[] = $rs['uid'];
		}
		return $arrs;
	}
	
	public function getface($face, $mr='')
	{
		if($mr=='')$mr 	= 'images/noface.png';
		if(substr($face,0,4)!='http' && !$this->isempt($face))$face = URL.''.$face.'';
		$face 			= $this->rock->repempt($face, $mr);
		return $face;
	}
	
	/**
	*	获取人员信息
	*/
	public function getuserinfo($uids='0')
	{
		$uarr = $this->getall("`id` in(".$uids.") and `status`=1",'`id`,`name`,`face`','`sort`');
		foreach($uarr as $k=>$rs){
			$uarr[$k]['face'] = $this->getface($rs['face']);
		}
		return $uarr;
	}
	
	/**
	*	获取人员数据
	*	$lx=0 通讯录，1选择人员
	*/
	public function getuser($lx=0, $uid=0)
	{
		$fields = '`id`,`name`,`deptid`,`deptname`,`deptpath`,`groupname`,`deptallname`,`mobile`,`ranking`,`tel`,`face`,`sex`,`email`,`pingyin`,`deptids`,`isvcard`';
		if($uid==0){
			$uid  	= $this->adminid;
			$where	= m('view')->viewwhere('user', $uid, 'id');
			$where	= str_replace('{asqom}','', $where);
			$range 	= $this->rock->get('changerange'); //指定了人
			$rangeno= $this->rock->get('changerangeno'); //no指定了人
			$where1 = '';$where2 = '';
			if(!isempt($range)){
				//本部门||下级部门
				if($range=='dept' || $range=='deptall'){
					$urs 	= $this->getone($uid);
					$deptid = $urs['deptid'];
					if(!isempt($urs['deptids']))$deptid.=','.$urs['deptids'].'';
					$deptida = explode(',', $deptid);
					$datsa   = array();
					
					if($range=='dept'){
						$datsa[]= '`deptid` in('.$deptid.')';
						foreach($deptida as $did1)if($did1)$datsa[]=$this->rock->dbinstr('deptids', $did1);
					}else{
						foreach($deptida as $did1)if($did1)$datsa[] = 'instr(`deptpath`,\'['.$did1.']\')>0';
					}
					
					$where1 = join(' )or( ', $datsa);
					$where1 = 'and ('.$where1.')';
				}else if($range=='down' || $range=='downall'){
					$where1 = 'and '.$this->rock->dbinstr('superid', $this->adminid).'';
					if($range=='downall')$where1 = 'and instr(`superpath`,\'['.$this->adminid.']\')>0';
				}else{
					$where1 = $this->gjoin($range, '', 'where');
					$where1 = 'and ('.$where1.')';
				}
			}
			if(!isempt($rangeno)){
				$where2 = $this->gjoin($rangeno, '', 'where');
				$where2 = 'and not('.$where2.')';
			}
			if($lx==0)$where.=' and `isvcard`=1'; //通讯录显示
			//--start--
			if(ISMORECOM && $uid>1){
				$comid = $this->getcompanyid($uid);
				$str11 = $this->rock->dbinstr('`dwid`', $comid);
				$where2.=' and (`companyid`='.$comid.' or '.$str11.')';
			}
			//--end--
			//读取我可查看权限
			if(contain($where,'1=1')){
				$where = '';
			}else{
				$where = 'and ((1 '.$where.') or (`id`='.$uid.'))';
			}
			$rows = $this->getall("`status`=1 $where $where1 $where2",$fields,'`sort`,`name`');
		}else{
			$rows = $this->getall("`id`='$uid'",$fields,'`sort`,`name`');
		}

		
		foreach($rows as $k=>$rs){
			$rows[$k]['face'] = $rs['face'] = $this->getface($rs['face']);
			$deptidss = ','.$rs['deptid'].',';
			if(!isempt($rs['deptids']))$deptidss.=''.$rs['deptids'].',';
			$rows[$k]['deptidss'] = $deptidss;
			if($rs['pingyin']==null)$rows[$k]['pingyin']='';
			if($rs['ranking']==null)$rows[$k]['ranking']='';
			if($rs['deptname']==null)$rows[$k]['deptname']='';
		}
		return $rows;
	}
	
	public function getadmininfor($rows, $suids, $fid='checkid')
	{
		$farr	= $this->db->getarr('[Q]admin', "`id` in($suids)",'`face`,`name`');
		foreach($rows as $k=>$rs){
			$face =  $name = '';
			if(isset($farr[$rs[$fid]])){
				$face = $farr[$rs[$fid]]['face'];
				$name = $farr[$rs[$fid]]['name'];
				$rows[$k]['name'] = $name;
			}	
			$rows[$k]['face'] = $this->getface($face);
		}
		return $rows;
	}
	
	public function getusinfo($uid, $fields='id')
	{
		$urs = $this->db->getone('[Q]userinfo', $uid, $fields);
		if(!$urs){
			$urs = array();
			$far = explode(',', str_replace('`','',$fields));
			foreach($far as $f)$urs[$f]='';
			$urs['id'] = $uid;
		}
		return $urs;
	}
	
	
	
	
	public function getidtouser($id)
	{
		return $this->getmou('user', "`id`='$id'");
	}
	
	
	
	
	/**
	*	更新信息
	*/
	public function updateinfo($where='')
	{
		$rows	= $this->db->getall("select * from `[Q]admin` a where id>0 $where");
		$total	= $this->db->count;
		$cl		= 0;
		$sjo    = m('sjoin');
		foreach($rows as $k=>$rs){
			$nrs	= $this->getpath($rs['deptid'], $rs['superid'], $rs['deptids']);
			$gids 	= $sjo->getgroupid($rs['id']);
			if($gids=='0'){
				$gids = '';
			}else{
				$gids = substr($gids, 2);
			}
			if($nrs['deptpath'] != $rs['deptpath'] || $nrs['deptname'] != $rs['deptname'] || $nrs['superpath'] != $rs['superpath'] || $nrs['superman'] != $rs['superman'] || $nrs['deptnames'] != $rs['deptnames'] || $nrs['deptallname'] != $rs['deptallname'] || $gids != $rs['groupname']){
				$nrs['groupname'] = $gids;
				$this->record($nrs, "`id`='".$rs['id']."'");
				$cl++;
			}
		}
		
		$cl += $this->updateuserinfo($where);
		
		//更新单据上flow_bill上的uname,udeptname
		m('flowbill')->updatebill($where);
		m('imgroup')->updategall(); //更新会话上
		
		return array($total, $cl);
	}
	public function updateuserinfo($whe='')
	{
		$db 	= m('userinfo');
		$rows	= $this->db->getall('select a.name,a.deptname,a.id,a.status,a.ranking,b.id as ids,a.sex,a.tel,a.mobile,a.email,a.workdate,a.quitdt,b.state,a.num,a.companyid,a.dwid,a.deptnames,a.rankings,a.deptallname from `[Q]admin` a left join `[Q]userinfo` b on a.id=b.id where a.id>0 '.$whe.' ');
		$xbo 	= 0;
		foreach($rows as $k=>$rs){
			$uparr = array(
				'id' 		=> $rs['id'],
				'name' 		=> $rs['name'],
				'deptname' 	=> $rs['deptname'],
				'deptnames' => $rs['deptnames'],
				'deptallname' => $rs['deptallname'],
				'ranking' 	=> $rs['ranking'],
				'rankings' 	=> $rs['rankings'],
				'sex' 		=> $rs['sex'],
				'tel' 		=> $rs['tel'],
				'mobile' 	=> $rs['mobile'],
				'email' 	=> $rs['email'],
				'workdate' 	=> $rs['workdate'],
				'quitdt' 	=> $rs['quitdt'],
				'num' 		=> $rs['num'],
				'companyid' => $rs['companyid'],
				'dwid' 		=> $rs['dwid'],
			);
			if(isempt($rs['quitdt'])){
				if($rs['state']=='5')$uparr['state'] = 0;
			}else{
				$uparr['state'] = 5;//离职
			}
			if(isempt($rs['ids'])){
				$db->insert($uparr);
			}else{
				unset($uparr['id']);
				$db->update($uparr, $rs['ids']);
			}
			$xbo+=$this->db->row_count();
		}
		return $xbo;
	}
	
	//返回这个月份人员
	public function monthuwhere($month, $qz='')
	{
		$month	= substr($month, 0, 7);
		$start	= ''.$month.'-01';
		$enddt	= c('date')->getenddt($month);
		$s 		= $this->monthuwheres($start, $enddt, $qz);
		return $s;
	}
	public function monthuwheres($start, $enddt, $qz='')
	{
		$s 		= " and ($qz`quitdt` is null or $qz`quitdt`>='$start') and ($qz`workdate` is null or $qz`workdate`<='$enddt')";
		return $s;
	}
	
	public function changeface($uid, $fid)
	{
		$frs 	= m('file')->getone($fid);
		if(!$frs)return false;
		$path 	= $frs['thumbpath'];
		if(isempt($path))$path = $frs['filepath'];
		$face	= $path;
		if(file_exists($path)){
			$face = ''.UPDIR.'/face/'.$uid.'_'.rand(1000,9999).'.jpg';
			$this->rock->createdir($face);
			c('image')->conver($path, $face);
			$oface  = $this->getmou('face', $uid);
			if(!isempt($oface) && file_exists($oface))@unlink($oface);//删除原来头像
			$this->update("face='$face'", $uid);
		}
		m('file')->delfile($fid);
		if(!file_exists($face))$face='';
		return $face;
	}
	
	//根据邮箱获取人员姓名
	private $emailtoursarr = array();
	public function emailtours($email)
	{
		$key  = 'rock'.$email.'';
		if(!isset($this->emailtoursarr[$key])){
			$urs 	= $this->getone("`email`='$email'",'`id`,`name`');
			$this->emailtoursarr[$key] = $urs;
		}else{
			$urs	= $this->emailtoursarr[$key];
		}
		return $urs;
	}
	
	/**
	*	关键词搜索的
	*/
	public function getkeywhere($key, $qz='', $ots='')
	{
		$where = " and ($qz`name` like '%$key%' or $qz`user` like '%$key%' or $qz`deptallname` like '%$key%' or $qz`ranking` like '%$key%' or $qz`pingyin` like '$key%' $ots)";
		return $where;
	}
	
	/**
	*	根据receid获取对应字段$fid聚合得到多个,分开的
	*/
	public function getjoinfields($receid, $fid)
	{
		if(!is_numeric($receid)){
			$receid = $this->gjoin($receid,'ud', 'where'); //读取
			$where 	= '1=1';
			if($receid != 'all')$where = $receid;
			if(isempt($receid))$where = '1=2';
		}else{
			$where = 'id='.$receid.'';
		}
		$wherew1 = $this->getcompanywhere();
		$rows = $this->getall("`status`=1 $wherew1 and ($where)", '`id`,`'.$fid.'`');
		$strs = '';
		foreach($rows as $k=>$rs){
			if(!isempt($rs[$fid]))$strs.=','.$rs[$fid].'';
		}
		if($strs!='')$strs = substr($strs, 1);
		
		return $strs;
	}
	
	/**
	*	是否已修改密码了
	*/
	public function iseditpass($uid)
	{
		$editpass = (int)getconfig('editpass','0');
		$stype	  = getconfig('systype');
		if($editpass==0 || $stype=='dev' || $stype=='demo')return 1;
		$urs 	  = $this->getone('`id`='.$uid.'');
		$editpass = (int)arrvalue($urs, 'editpass','0');
		return $editpass;
	}
	
	/**
	*	读取用户信息
	*/
	private $getuserrsarr = array();
	public function geturs($id)
	{
		if(isempt($id))return false;
		if(isset($this->getuserrsarr[$id]))return $this->getuserrsarr[$id];
		
		$where = "`user`='$id'";
		$check = c('check');
		if($check->iscnmobile($id)){
			$where = "`mobile`='$id'";
		}elseif($check->isemail($id)){
			$where = "`email`='$id'";
		}elseif($check->isincn($id)){
			$where = "`name`='$id'";
		}elseif($check->isnumber($id)){
			$where = "`id`='$id'";
		}
		if(contain($id, ':')){
			$ida = explode(':', $id);
			$where = "`".$ida[0]."`='".$ida[1]."'";
		}
		$urs = $this->db->getall("select * from `[Q]admin` where $where");
		if($urs)$urs = $urs[0];
		$this->getuserrsarr[$id] = $urs;
		return $urs;
	}
	
	/**
	*	获取单位的信息
	*/
	public function getcompanyinfo($uid=0, $glx=0)
	{
		if($uid==0)$uid = $this->adminid;
		if($uid==0)$uid = (int)arrvalue($GLOBALS,'adminid','0');
		if($uid==0)$uid = 1;//必须要有个值
		$urs 	  	 = $this->getone('`id`='.$uid.'');
		$companyid	 = $urs['companyid'];
		$comid	 	 = (int)arrvalue($urs, 'comid', '0');
		if(isempt($companyid) || $companyid=='0'){
			$this->update('`companyid`=1', $uid);
			$companyid = '1';
		}
		$alldwid	 = $companyid;
		$dwid= arrvalue($urs, 'dwid');
		if(!isempt($dwid))$alldwid.=','.$dwid.'';
		$companyinfo 	= array('id'=>0);
		$companyinfd 	= false;
		$cwhere 		= '`id` in('.$alldwid.')';
		if($glx==5 && $uid==1)$cwhere = '`id`>0';
		$companyinfoall = m('company')->getall($cwhere,'*','`pid`,`sort`');
		$nid			= $companyid;
		$allid			= array(0);
		foreach($companyinfoall as $k=>$rs){
			$nlogo = 'images/logo.png';
			$logo  = $rs['logo'];
			if(isempt($logo)){
				$logo = $nlogo;
			}else{
				if(substr($logo,0,4)!='http' && !file_exists($logo))
					$logo = $nlogo;
			}
			$companyinfoall[$k]['logo'] = $rs['logo'] = $this->getface($logo, $nlogo);
			
			if($rs['id']==$nid)$companyinfo = $rs;
			if($rs['id']==$comid)$companyinfd = $rs;
			$allid[] = $rs['id'];
		}
		if($companyinfd)$companyinfo = $companyinfd;
		$this->setcompanyid($companyinfo['id']);
		if($glx==1)return $companyinfo;
		if($glx==2)return $companyinfo['id'];
		return array(
			'companyinfoall' => $companyinfoall,
			'companyallid' 	 => $allid,
			'companyinfo' 	 => $companyinfo,
			'companyid'		 => $companyinfo['id']
		);
	}
	
	public function getcompanyid($uid=0)
	{
		$comid = (int)$this->rock->session('companyid','0');
		if($comid==0)$comid = $this->getcompanyinfo($uid,2);
		if(!$comid)$comid=1;
		return $comid;
	}
	
	public function setcompanyid($comid)
	{
		$this->rock->setsession('companyid', $comid);
		return $comid;
	}
	
	public function getcompanywhere($lx=0, $qz='',$comid=false)
	{
		$where = '';
		//--start--
		if(ISMORECOM){
			if($comid===false)$comid = ''.$this->getcompanyid().'';
			$comi2 = $comid;
			$str11 = $this->rock->dbinstr(''.$qz.'dwid', $comid);
			if($this->adminid==1)$comid.=',0';
			$where= " and (".$qz."`companyid` in (".$comid.") or $str11)";
			if($lx==1)$where= " and ".$qz."`comid` in (".$comid.")";
			if($lx==2)$where= " and ".$qz."`companyid` in (".$comid.")";
			if($lx==3)$where= " and ".$qz."`comid`=".$comi2."";
			if($lx==5)$where= " and ".$qz."`companyid`=".$comi2.""; //用在关联userinfo表只看本单位
			if($lx==4)$where= " and ".$qz."`uid` in(select `id` from `[Q]admin` where `companyid`=".$comi2.")";
		}
		//--end--
		return $where;
	}
	
	public function getcompanynum($uid=0)
	{
		$num  ='';
		$carr = $this->getcompanyinfo($uid);
		$num  = $carr['companyinfo']['num'];
		if(isempt($num))$num='';
		return $num;
	}
}