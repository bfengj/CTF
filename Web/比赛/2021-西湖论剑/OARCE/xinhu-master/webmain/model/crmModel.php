<?php
class crmClassModel extends Model
{
	public function initModel()
	{
		$this->settable('customer');
	}
	
	//读取我的客户和共享给我的
	public function getmycust($uid=0, $id=0)
	{
		if(isempt($id))$id = 0;
		if($uid==0)$uid=$this->adminid;
		$s		= $this->rock->dbinstr('shateid', $uid);
		$rows 	= $this->getrows("`status`=1 and ((`uid`='$uid') or (`id`=$id) or (".$s."))",'id as value,name,id,unitname as subname','`name`');
		return $rows;
	}
	
	//读取所有客户
	public function custdata()
	{
		$where  = m('admin')->getcompanywhere(3);
		$rows 	= $this->getrows("`status`=1 ".$where."",'id as value,name,id,unitname as subname','`optdt` desc');
		return $rows;
	}
	
	
	//读取我的销售机会
	public function getmysale($uid, $id=0)
	{
		$where 	= '`uid`='.$uid.' and `state` in(0,1) and (`htid`=0 or `htid`='.$id.')';
		$rows 	= m('custsale')->getrows($where, 'id,custid,custname,money,laiyuan');
		return $rows;
	}
	
	//读取我的合同
	public function getmyract($uid, $id=0)
	{
		$where 	= '`uid`='.$uid.' and (`isover`=0 or `id`='.$id.')';
		$rows 	= m('custract')->getrows($where, 'id,custid,custname,money,num');
		return $rows;
	}
	
	//更新合同状态
	public function ractmoney($htid)
	{
		if(isempt($htid))return false;
		if(!is_array($htid)){
			$ors  	= $this->db->getone('[Q]custract','id='.$htid.'','money,moneys,ispay,id,isover');
		}else{
			$ors	= $htid;
		}
		if(!$ors)return false;
		$zmoney	= $ors['money']; $moneys	= $ors['moneys'];
		$oispay	= $ors['ispay'];
		$htid	= $ors['id'];
		$money 	= $this->db->getmou('[Q]custfina','sum(money)','htid='.$htid.' and `ispay`=1');
		$moneyy	= $this->getmoneys($htid); //已创建收付款单金额
		$symon	= $zmoney - $money;
		$ispay	= 0;
		$isover	= 0;
		if($symon<=0){
			$ispay = 1;
		}else if($money>0){
			$ispay = 2;
		}
		if($moneyy>=$zmoney)$isover = 1;
		if($ispay != $oispay || $symon!= $moneys || $isover != $ors['isover']){
			$this->db->update('[Q]custract','`ispay`='.$ispay.',`moneys`='.$symon.',`isover`='.$isover.'', $htid);
		}
		return array($ispay, $symon);
	}
	
	public function getmoneys($htid, $id=0)
	{
		$moneys = floatval($this->db->getmou('[Q]custfina','sum(money)','`htid`='.$htid.' and `id`<>'.$id.''));
		return $moneys;
	}
	
	
	/**
	*	对应人统计金额
	*/
	public function moneytotal($uid, $month)
	{
		$uid 	= (int)$uid;
		$sql 	= "SELECT uid,type,ispay,sum(money)money,count(1)stotal FROM `[Q]custfina` where `uid`='$uid' and `dt` like '$month%' GROUP BY type,ispay";
		$farr	= explode(',', 'shou_moneyd,shou_moneyz,shou_moneys,shou_moneyn,shou_shu,fu_moneyd,fu_moneyz,fu_moneys,fu_moneyn,fu_shu');
		foreach($farr as $f)$$f= 0;
		$rows 	= $this->db->getall($sql);
		foreach($rows as $k=>$rs){
			$type 	= $rs['type']; $ispay = $rs['ispay']; 
			$money 	= floatval($rs['money']);
			$stotal	= floatval($rs['stotal']);
			if($type==0){
				if($ispay==1){
					$shou_moneys += $money;	
				}else{
					$shou_moneyd += $money;	
				}
				$shou_shu 	 += $stotal;	
				$shou_moneyz += $money;	
			}else{
				if($ispay==1){
					$fu_moneys += $money;	
				}else{
					$fu_moneyd += $money;	
				}
				$fu_shu 	 += $stotal;	
				$fu_moneyz 	 += $money;	
			}
		}
		//当月已收付
		$sql = "SELECT type,sum(money)money FROM `[Q]custfina` where `uid`='$uid' and `ispay`=1 and `paydt` like '$month%' GROUP BY type";
		$rows 	= $this->db->getall($sql);
		foreach($rows as $k=>$rs){
			if($rs['type']==0)$shou_moneyn = $rs['money']+0;
			if($rs['type']==1)$fu_moneyn = $rs['money']+0;
		}
		$arr = array();
		foreach($farr as $f)$arr[$f] = $$f;
		return $arr;
	}
	
	//客户转移
	public function movetouser($uid, $sid, $toid)
	{
		$rows 	= $this->getrows("`id` in($sid)",'id,uid,name');
		$toname = m('admin')->getmou('name',"`id`='$toid'");
		if(isempt($toname))return false;
		
		foreach($rows as $k=>$rs){
			$id  = $rs['id'];
			$uarr			= array();
			$uarr['uid'] 	= $toid;
			$uarr['optname']= $toname;
			$nowid = (int)$rs['uid'];
			if($nowid==0)$nowid = $uid;
			
			$this->update(array('uid'=>$toid,'suoname'=>$toname), $id);
			
			m('custract')->update($uarr, "`uid`='$nowid' and `custid`='$id'"); 
			m('custsale')->update($uarr, "`uid`='$nowid' and `custid`='$id'"); //销售机会
			m('goodm')->update($uarr, "`uid`='$nowid' and `custid`='$id' and `type`=2"); //销售的
			m('custplan')->update($uarr, "`uid`='$nowid' and `custid`='$id'"); //跟进计划
			
			$uarr['ismove']=1;
			m('custfina')->update($uarr, "`uid`='$nowid' and `custid`='$id'");
			
		}
	}
	
	//客户统计
	public function custtotal($ids='')
	{
		$wher	= '';
		$ustr 	= '`moneyz`=0,`moneyd`=0,`htshu`=0';
		if($ids!=''){
			$wher=' and `custid` in('.$ids.')';
			$this->update($ustr,'id in('.$ids.')');
		}else{
			$this->update($ustr,'id>0');
		}
		$rows 	= $this->db->getall('SELECT custid,sum(money)as moneys,ispay FROM `[Q]custfina` where `type`=0 '.$wher.' GROUP BY custid,ispay');
		$arr 	= array();
		foreach($rows as $k=>$rs){
			$custid = $rs['custid'];
			if(!isset($arr[$custid]))$arr[$custid] = array(0,0,0);
			$arr[$custid][0]+=$rs['moneys'];
			if($rs['ispay']==0)$arr[$custid][1]+=$rs['moneys'];
		}
		foreach($arr as $id=>$rs){
			$uarr['moneyz'] = $rs[0];
			$uarr['moneyd'] = $rs[1];
			$this->update($uarr, $id);
		}
		$rows 	= $this->db->getall('SELECT custid,count(1)htshu FROM `[Q]custract` where id>0 '.$wher.' GROUP BY custid');
		foreach($rows as $k=>$rs){
			$custid = $rs['custid'];
			$this->update('htshu='.$rs['htshu'].'', $custid);
		}
		$rows= $this->db->getall('select b.name,a.id from `[Q]customer` a left join `[Q]admin` b on a.`uid`=b.`id` where  a.`uid`>0 and (a.`suoname`<>b.`name` or a.`suoname` is null)');
		foreach($rows as $k=>$rs){
			$this->update("`suoname`='".$rs['name']."'", $rs['id']);
		}
		$this->update('`suoname`=null','`uid`=0');
	}
	
	//合同状态金额更新
	public function custractupzt($htid='')
	{
		$where1= $where2= '';
		if(!isempt($htid)){
			$where1="and `htid` in($htid)";
			$where2="and `id` in($htid)";
		}
		$this->db->update('[Q]custract','`ispay`=0,`isover`=0,`moneys`=money','`id`>0 '.$where2.'');
		
		$rows = $this->db->getall('SELECT `htid` FROM `[Q]custfina` where `htid`>0 '.$where1.' GROUP BY htid');
		foreach($rows as $k=>$rs){
			$htid = $rs['htid'];
			$this->ractmoney($htid);
		}
		
		//更新收付款单
		$rows = $this->db->getall('SELECT a.id,a.htid,a.htnum,b.num FROM `[Q]custfina` a left join `[Q]custract` b on a.htid=b.id where a.htid>0 and a.htnum<>b.num ');
		foreach($rows as $k=>$rs){
			$htid = $rs['htid'];
			if(isempt($rs['num']))$htid = 0;
			$this->db->record('[Q]custfina', array(
				'htid' 	=> $htid,
				'htnum' => $rs['num'],
			), $rs['id']);
		}
	}
	
	/**
	*	跟进名称读取客户档案
	*/
	public function getcustomer($name)
	{
		if(isempt($name))return false;
		$rs = $this->getone("(`name`='$name' or `unitname`='$name')");
		return $rs;
	}
	
	
	/**
	*	销售单是收款状态
	*/
	public function xiaozhuantai($rs, $lx=0, $csid=0)
	{
		$str = '';
		$wshou1 = 0;
		if($rs['status']=='5')return ($lx==0)?'作废了':0;
		
		if($rs['custractid']=='0'){
			$finrows = $this->db->getall('select * from `[Q]custfina` where `htid`=-'.$rs['id'].' and `id`<>'.$csid.'');
			$shou	 = 0;
			$shou1	 = 0;//已创建金额
			$ispay	 = '0';
			foreach($finrows as $k1=>$rs1){
				if($rs1['ispay']=='1')$shou+=floatval($rs1['money']);
				$shou1+=floatval($rs1['money']);
			}
			$wshou	  = floatval($rs['money'])-$shou;
			$wshou1	  = floatval($rs['money'])-$shou1;
			if($wshou<0)$wshou = 0; 
			if($wshou1<=0){
				$wshou1 = 0;//未创建
				$ispay	= '1';
			}
			if($wshou==0){
				$str = '<font color=green>已全部收款</font>';
			}else{
				$str = '待收<font color=#ff6600>'.$wshou.'</font>';
			}
			if($ispay!=$rs['ispay'])$this->db->update('[Q]goodm','`ispay`='.$ispay.'', '`id`='.$rs['id'].'');
		}
		if($lx==1)return $wshou1;
		return $str;
	}
}