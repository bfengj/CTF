<?php
class flowcnameClassModel extends Model
{
	public function initModel()
	{
		$this->settable('flow_cname');
	}
	
	/**
	*	根据编号读取对应审核人员
	*/
	public function getcheckname($num, $uid)
	{
		$cuid 	= $cname = '';
		$barr 	= array($cuid, $cname);
		if(isempt($num))return $barr;
		$ors  	= $this->getone("`num`='$num'");
		if(!$ors)return $barr;
		$id 	= $ors['id'];
		$rows 	= $this->getrows('pid='.$id.'','*','`sort`');
		$rowa 	= array();
		$bid 	= 0;
		if($rows){
			$bid 	= m('kaoqin')->getpipeimid($uid, $rows,'id', 0);
			foreach($rows as $k=>$rs)$rowa[$rs['id']] = $rs;
		}
		if($bid==0){
			$cuid 	= $ors['checkid'];
			$cname 	= $ors['checkname'];
		}else{
			$grs 	= $rowa[$bid];
			$cuid 	= $grs['checkid'];
			$cname 	= $grs['checkname'];
		}
		return array($cuid, $cname);
	}
}