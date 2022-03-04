<?php
class logClassModel extends Model
{
	public function addlog($type='', $remark='', $sarr=array())
	{
		$arr['type']	= $type;
		$arr['ip']		= $this->rock->ip;
		$arr['web']		= $this->rock->web;
		$arr['optdt']	= $this->rock->now();
		$arr['optid']	= $this->adminid;
		$arr['optname']	= $this->adminname;
		$arr['remark']	= $remark;
		//$arr['url']		= $this->rock->nowurl();//不记录这个没用
		foreach($sarr as $k=>$v)$arr[$k]=$v;
		return $this->insert($arr);
	}
	
	/**
	*	添加日志的 $level=2错误
	*/
	public function addlogs($type='', $remark='', $level=0, $sarr=array())
	{
		$sarr['level'] = $level;
		return $this->addlog($type, $remark, $sarr);
	}
	
	/**
	*	提醒返回错误日志添加
	*/
	public function todolog($type,$barr=array())
	{
		if((int)arrvalue($barr,'errcode',0) != 0)$this->addlogs($type,''.$barr['errcode'].':'.$barr['msg'].'', 2);
	}
	
	public function addread($table, $mid, $uid=0)
	{
		if($uid==0)$uid=$this->adminid;
		$where  		= "`table`='$table' and `mid`='$mid' and `optid`=$uid";
		$dbs 			= m('reads');
		$onrs 			= $dbs->getone($where);
		if(!$onrs){
			$arr['table']	= $table;
			$arr['mid']		= $mid;
			$arr['optid']	= $uid;
			$arr['stotal']	= 1;
			$arr['adddt']	= $this->rock->now();
			$where 			= '';
		}else{
			$arr['stotal']	= (int)$onrs['stotal']+1;
		}
		$arr['ip']		= $this->rock->ip;
		$arr['web']		= $this->rock->web;
		$arr['optdt']	= $this->rock->now();
		$dbs->record($arr, $where);
	}
	
	/**
	*	获取已读人员
	*/
	public function getreadarr($table, $mid)
	{
		$rows = $this->db->getrows('[Q]reads',"`table`='$table' and `mid`='$mid' ",'optid,optdt,stotal','`id` desc');
		$uids = '0';
		$srows= $sssa = array();
		foreach($rows as $k=>$rs){
			$uid 	 = $rs['optid'];
			$uids	.=','.$uid.'';
			if(!isset($sssa[$uid])){
				$srows[] = $rs;
			}
			$sssa[$uid]  = 1;
		}
		$usarr = array();
		if($uids!='0'){
			$uarr = $this->db->getarr('[Q]admin',"`id` in($uids) and `status`=1", '`name`,`face`');
			foreach($srows as $k=>$rs){
				$uid = $rs['optid'];
				if(isset($uarr[$uid])){
					$usarr[] = array(
						'uid' 		=> $uid,
						'optdt' 	=> $rs['optdt'],
						'stotal' 	=> $rs['stotal'],
						'name'	=> $uarr[$uid]['name'],
						'face'	=> $this->rock->repempt($uarr[$uid]['face'],'images/noface.png')
					);
				}
			}
		}
		return $usarr;
	}
	

	
	public function getread($table, $uid=0)
	{
		if($uid==0)$uid=$this->adminid;
		$sid = $this->db->getjoinval('[Q]reads','mid',"`table`='$table' and `optid`=$uid group by `mid`");
		if($sid==''){
			$sid = '0';
		}else{
			$sid = '0,'.$sid.'';
		}
		return $sid;
	}
	
	public function isread($table, $mid, $uid=0)
	{
		if($uid==0)$uid=$this->adminid;
		$where  = "`table`='$table' and `mid`='$mid' and `optid`=$uid";
		$to 	= $this->db->rows('[Q]reads', $where);
		return $to;
	}
	
	//获取已读未读数
	public function getreadshu($table, $mid, $receid, $optdt='', $dbs=null)
	{
		$ydshu	= $wdshu = $zzshu = 0;
		$ydname = $wdname= '';
		if($dbs==null)$dbs = m('admin');
		$where	= $dbs->gjoin($receid,'ud','where');
		if($where=='all')$where = '';
		if(!isempt($where))$where = ' and ('.$where.')';
		if(!isempt($optdt)){
			$dt = substr($optdt,0,10);
			$where.=" and `workdate`<='$dt'";
		}
		$where .= $dbs->getcompanywhere();
		$uarr 	= $dbs->getall('`status`=1'.$where.'','`id`,`name`,`face`','`sort`');
		
		$receas	= explode(',', str_replace('u','', $receid));
		$rows 	= $this->db->getall("SELECT `optid` FROM `[Q]reads` where `table`='$table' and `mid`='$mid' GROUP BY `optid`");
		$ydarr	= array();
		foreach($rows as $k=>$rs)$ydarr[] = $rs['optid'];
		$wduarr	= array(); //未读人员数组
		foreach($uarr as $k=>$rs){
			$uid 	= $rs['id'];
			$name 	= $rs['name'];
			$rs['face'] = $this->rock->repempt($rs['face'], 'images/noface.png');
			if(in_array($uid, $ydarr)){
				$ydshu++;
				$ydname.=','.$name.'';
			}else{
				$wdshu++;
				$wdname.=','.$name.'';
				$wduarr[] = $rs;
			}
			$zzshu++;
		}
		if($ydname!='')$ydname = substr($ydname, 1);
		if($wdname!='')$wdname = substr($wdname, 1);
		
		return array(
			'zzshu'  => $zzshu,
			'ydshu'  => $ydshu,
			'wdshu'  => $wdshu,
			'ydname' => $ydname,
			'wdname' => $wdname,
			'wduarr' => $wduarr
		);
	}
}