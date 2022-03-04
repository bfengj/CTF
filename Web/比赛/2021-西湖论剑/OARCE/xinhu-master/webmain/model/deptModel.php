<?php
class deptClassModel extends Model
{
	/**
	*	$uarr 相应人员才能查看对应部门数据
	*/
	public function getdata($uarr=array())
	{
		$darr = $dtotal =array();
		$gids = '0';
		$dbs  = m('admin');
		//要权限判断
		if(is_array($uarr)){
			$did  = '0';
			if($uarr)foreach($uarr as $k=>$rs){
				$dpath = str_replace(array('[',']'), array('',''), $rs['deptpath']);
				if(!isempt($dpath)){
					$dpatha = explode(',', $dpath);
					foreach($dpatha as $dpatha1){
						if(isempt($dpatha1))continue;
						$darr[$dpatha1]=$dpatha1;
						if(!isset($dtotal[$dpatha1]))$dtotal[$dpatha1]=0;
						$dtotal[$dpatha1]++;
					}
				}
				if(!isempt(arrvalue($rs,'groupname')))$gids.=','.$rs['groupname'].'';
			}
			foreach($darr as $k1=>$v1)$did.=','.$k1.'';
			$where= 'id in('.$did.')';
			
			if(isempt($this->rock->get('changerange'))){
				if((int)$dbs->getmou('type', $this->adminid)==1){
					$where = '1=1'; //管理员可看全部
				}else{
					$where1= m('view')->viewwhere('dept', $this->adminid, 'id');
					if(contain($where1,'1=1')){
						$where = '1=1'; //全部
					}else{
						$where = '`id`>0 and ((1 '.$where1.') or (id in('.$did.')))';
					}
				}
			}
		}else{
			$where = '1=1';
		}
		$this->firstpid = 0;
		
		//多单位
		if(ISMORECOM){
			$sysalluview = ','.m('option')->getval('sysalluview','0').',1,';
			if(!contain($sysalluview,','.$this->adminid.',')){
				$comid = $dbs->getcompanyid();
				$where.=' and `companyid` in(0,'.$comid.') and `id`>1';
				$this->firstpid = 1;
			}
		}
		
		$rows = $this->getall($where,'`id`,`name`,`pid`,`sort`','`pid`,`sort`');
		if(is_array($uarr))foreach($rows as $k=>$rs){
			//$stotal = $dbs->rows("`status`=1 and instr(`deptpath`,'[".$rs['id']."]')>0");
			$stotal = 0;
			$rows[$k]['stotal'] = $stotal; //对应部门下有多少人
			$rows[$k]['ntotal']	= $this->rock->arrvalue($dtotal, $rs['id'], '0');
		}
		$this->groupids = $gids;
		$this->temparaa	= array();
		$this->getshowdeptarr($rows, $this->firstpid, 1);
		return $this->temparaa;
	}
	
	private function getshowdeptarr($rows, $pid, $level)
	{
		foreach($rows as $k=>$rs){
			if($pid>=0){
				if($rs['pid']==$pid){
					$rs['level'] = $level;
					$this->temparaa[] = $rs;
					$this->getshowdeptarr($rows, $rs['id'],$level+1);
				}
			}
		}
	}
	
	public function getdeptrows($ids)
	{
		if(isempt($ids))return array();
		$rows = $this->getall("`id` in($ids)",'`id`,`name`,`pid`,`sort`','`pid`,`sort`');
		return $rows;
	}
	
	/**
	*	获取部门和人员数据
	*	$lx=0 通讯录，1选择人员
	*/
	public function getdeptuserdata($lx=0)
	{
		$changerange= $this->rock->get('changerange');
		$admindb 	= m('admin');
		$userarr 	= $admindb->getuser($lx);
		//根据禁看权限
		$flow 		= m('flow')->initflow('user');
		$userarr 	= $flow->viewjinfields($userarr);
		
		$deptarr 	= $this->getdata($userarr);
		$where1		= '';
		if(ISMORECOM && $this->adminid>1)$where1=' and `companyid` in('.$admindb->getcompanyid().')';
		$grouparr	= m('group')->getall('id >0'.$where1.'','id,name','`sort`');
		$garr 		= array();

		foreach($grouparr as $k=>$rs){
			if(!isempt($changerange)){
				if(!contain(','.$changerange.',',',g'.$rs['id'].','))continue;
			}
			$uids = $admindb->getgrouptouid($rs['id']);
			$usershu = 0;
			if($uids!='')$usershu = count(explode(',', $uids));
			$rs['usershu'] = $usershu;
			$garr[] = $rs;
		}
		
		return array(
			'uarr' => $userarr,
			'darr' => $deptarr,
			'garr' => $garr,
		);
	}
	
	//获取某个人对应部门Id
	public function getudept($uid)
	{
		$urs 	= $this->db->getone('[Q]admin', "`id`='$uid'",'deptid,deptpath');
		$deptid = arrvalue($urs,'deptid','0');
		$drs 	= $this->getone('`id`='.$deptid.'');
		
		if(!$drs)$drs = array('name'=>'','id'=>0,'num'=>'');
		$nums = $drs['num'];
		if(isempt($nums))$nums = $drs['id'];
		$drs['nums'] = $nums;
		return $drs;
	}
	
	//人员在线离线更新
	public function online($lx)
	{
		$ustr = "online=".$lx.",`lastonline`='".$this->rock->now."'";
		$this->db->update('[Q]admin', $ustr,'`id`='.$this->adminid.'');
		if($lx==1)m('login')->uplastdt('pc', $this->rock->session('admintoken'));
	}
	
	
	public function getheadman($did)
	{
		$rs 	= $this->getone($did);
		$headid = '';
		$headids = '';
		if($rs){
			$headid  = $rs['headid'];
			$headids = $rs['headman'];
			if(isempt($headid) && $rs['pid']>0){
				$sars= $this->getheadman($rs['pid']);
				$headid  = $sars[0];
				$headids = $sars[1];
			}
		}
		return array($headid, $headids);
	}
}