<?php
class whereClassModel extends Model
{
	private $moders	= array();
	private $ursarr	= array();
	
	public function initModel()
	{
		$this->settable('flow_where');
		$this->admindbs	= m('admin');
	}
	
	/**
	*	条件格式化，返回是没有and开头的
	*/
	public function getstrwhere($str, $uid=0, $fid='')
	{
		if(isempt($str))return '';
		if($uid==0)$uid = $this->adminid;
		$dbs		= $this->admindbs;

		if(isset($this->ursarr[$uid])){
			$urs	= $this->ursarr[$uid];
		}else{
			$urs 	= $dbs->getone($uid);
			$this->ursarr[$uid] = $urs;
		}
		
		$companyid	= arrvalue($urs, 'companyid','0'); //对应单位ID
		$deptid		= arrvalue($urs, 'deptid','0'); //部门ID
		if(ISMORECOM){
			$comid	= arrvalue($urs, 'comid','0');
			if($comid>'0')$companyid = $comid;
		}
		
		$sw1		= $this->rock->dbinstr('superid',$uid);
		$super		= "select `id` from `[Q]admin` where $sw1";//我的直属下属
		$allsuper	= "select `id` from `[Q]admin` where instr(`superpath`,'[$uid]')>0"; //我所有下属的下属
		$companys	= "select `id` from `[Q]admin` where `companyid`=".$companyid.""; //对应单位下的
		
		
		//加上a.
		$str  = str_replace('[A]`uid`','`uid`', $str);
		$str  = str_replace('[A]uid','`uid`', $str);
		$barr = $this->rock->matcharr($str,2);
		$itsha= array('status','uid','optid','optname','applydt','createdt','createid');
		$thar = array();
		foreach($barr as $bsuid){
			if(in_array($bsuid, $itsha) && !in_array($bsuid, $thar)){
				$thar[] = $bsuid;
				$str  = str_replace('`'.$bsuid.'`','{asqom}`'.$bsuid.'`', $str);
			}
		}
		
		$str 		= m('base')->strreplace($str, $uid);
		$str 		= str_replace(array('{super}','{allsuper}','{company}'), array($super,$allsuper,$companys), $str);
		
		//未读替换
		if(contain($str,'{unread}')){
			$rstr = '';
			if($this->moders){
				$ydid  = m('log')->getread($this->moders['table'], $uid); 
				$rstr  = '{asqom}`id` not in('.$ydid.')';
			}
			$str = str_replace('{unread}', $rstr, $str);
		}
		//已读替换
		if(contain($str,'{read}')){
			$rstr = '';
			if($this->moders){
				$ydid  = m('log')->getread($this->moders['table'], $uid); 
				$rstr  = '{asqom}`id` in('.$ydid.')';
			}
			$str = str_replace('{read}', $rstr, $str);
		}
		//receid
		if(contain($str,'{receid}')){
			$rstr= $dbs->getjoinstr('{asqom}`receid`', $uid, 1);
			$str = str_replace('{receid}', '('.$rstr.')', $str);
		}
		//本周一{weekfirst}
		if(contain($str,'{weekfirst}')){
			$rstr= c('date')->getweekfirst($this->rock->date);
			$str = str_replace('{weekfirst}', $rstr, $str);
		}
		//本周日{weeklast}
		if(contain($str,'{weeklast}')){
			$rstr= c('date')->getweeklast($this->rock->date);
			$str = str_replace('{weeklast}', $rstr, $str);
		}
		$barr = $this->rock->matcharr($str);
		foreach($barr as $match){
			$rstr = $type = '';
			$_artr= explode(',', $match);
			$fie	= $_artr[0];
			if($fie=='asqom')continue;
			if(isset($_artr[1]))$type = $_artr[1];
			//包含uid里面：{uid,uidin}
			if($type=='uidin'){
				$rstr= $this->rock->dbinstr('{asqom}`'.$fie.'`', $uid);
			}
			//我直属下级：{uid,down}
			if($type=='down'){
				$rstr= $dbs->getdownwhere('{asqom}`'.$fie.'`', $uid, 1);
			}
			//我全部直属下级：{uid,downall}
			if($type=='downall'){
				$rstr= $dbs->getdownwhere('{asqom}`'.$fie.'`', $uid, 0);
			}
			//字段包含部门人员Id：{uid,receall}
			if($type=='receall'){
				$rstr= $dbs->getjoinstr('{asqom}`'.$fie.'`', $uid, 1);
			}
			//字段包含部门人员Id，空全部：{uid,recenot}
			if($type=='recenot'){
				$rstr= $dbs->getjoinstr('{asqom}`'.$fie.'`', $uid, 1, 1);
			}
			//我的同级部门人员：{uid,dept}
			if($type=='dept'){
				$rstr= '{asqom}`'.$fie.'` in(select `id` from `[Q]admin` where `deptid`='.$deptid.' or '.$this->rock->dbinstr('deptids',$deptid).')';
			}
			//我的同级部门人员(含子部门)：{uid,deptall}
			if($type=='deptall'){
				$rstr= '{asqom}`'.$fie.'` in(select `id` from `[Q]admin` where instr(`deptpath`,\'['.$deptid.']\')>0)';
			}
			//所属单位：{uid,company}
			if($type=='company'){
				$rstr= '{asqom}`'.$fie.'` in('.$companys.')';
			}
			$str = str_replace('{'.$match.'}', '( '.$rstr.' )', $str); //加上括号
		}
		return $str;
	}
	
	public function getflowwhere($id, $uid=0, $fid='')
	{
		if(is_array($id)){
			$rs 		= $id;
		}else{
			$swhe		= "`num`='$id'";
			if(is_numeric($id))$swhe=$id;
			$rs 		= $this->getone($swhe);
		}
		if(!$rs)return false;
		if($fid=='')$fid='`uid`';
		$modeid 		= (int)$rs['setid'];
		$this->moders 	= m('flow_set')->getone($modeid);
		
		$wheresstr 	= $this->getstrwhere($this->rock->jm->base64decode($rs['wheresstr']), $uid, $fid);
		$whereustr 	= $this->getstrwhere($this->rock->jm->base64decode($rs['whereustr']), $uid, $fid);
		$wheredstr 	= $this->getstrwhere($this->rock->jm->base64decode($rs['wheredstr']), $uid, $fid);
		$str 		= $wheresstr;if(isempt($str))$str='';
		$ustr 		= $nstr = '';
		if(!isempt($rs['receid'])){
			$tsrt 	= m('admin')->gjoin($rs['receid'],'ud', 'where');
			if($tsrt=='all'){
				$tsrt 	= '1=1';
			}else{
				$tsrt 	= '('.$tsrt.')';
			}
			$ustr = $tsrt;
		}
		if(!isempt($whereustr)){
			if($ustr!='')$ustr.=' and ';
			$ustr .= $whereustr;
		}
		
		if(!isempt($rs['nreceid'])){
			$tsrt 	= m('admin')->gjoin($rs['nreceid'],'ud', 'where');
			if($tsrt=='all'){
				$tsrt 	= '1=1';
			}
			$nstr = $tsrt;
		}
		if(!isempt($wheredstr)){
			if($nstr!='')$nstr.=' or ';
			$nstr .= $wheredstr;
		}
		$astr 	= $str;
		if($ustr != '' || $nstr != ''){
			$_sar= '1=1';
			if($ustr!='')$_sar.=' and '.$ustr.'';
			if($nstr!='')$_sar.=' and not ('.$nstr.')';
			if(!isempt($astr))$astr.=' and ';
			$astr .= '{asqom}'.$fid.' in(select `id` from `[Q]admin` where '.$_sar.')';
		}
		return array(
			'str'	=> $str,
			'utr'	=> $ustr,
			'ntr'	=> $nstr,
			'atr'	=> $astr
		);
	}
	
	public function getwherestr($id, $uid=0, $fid='', $lx=0)
	{
		$where 	= '';
		$arr 	= $this->getflowwhere($id, $uid, $fid);
		if($arr){
			$where = $arr['atr'];
			if($lx==0 && !isempt($where))$where = ' and '.$where;
		}
		return $where;
	}
	
	
	public function getmywhere($modeid,$uid=0, $pnum='')
	{
		if($uid==0)$uid = $this->adminid;
		$where 	= m('admin')->getjoinstr('syrid', $uid, 1);
		$where	= '`status`=1 and `setid`='.$modeid.' and `num` is not null and `islb`=1 ';//and ('.$where.')
		if(isempt($pnum)){
			$where .=" and ifnull(`pnum`,'')=''";
		}else{
			$where .=" and `pnum`='$pnum'";
		}
		$rows = $this->getrows($where, '`id`,`num`,`name`', '`sort`');
		return $rows;
	}
}