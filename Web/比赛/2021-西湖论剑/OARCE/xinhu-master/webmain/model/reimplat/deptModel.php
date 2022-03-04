<?php
class reimplat_deptClassModel extends reimplatModel
{
	
	public function initReimplat()
	{
		$this->settable('zreim_user');
	}
	
	//获取部门
	public function getdeptlist()
	{
		$url 	= $this->geturl('opendept','deptlist');
		$result = c('curl')->getcurl($url);
		
		$barr 	= $this->recordchu($result);
		if(!$barr['success'])return $barr;
		
		return returnsuccess($barr['data']['deptlist']);
	}
	
	//获取所有用户
	public function getuserlist()
	{
		$url 	= $this->geturl('opendept','useralllist');
		$result = c('curl')->getcurl($url);
		
		$barr 	= $this->recordchu($result);
		if($barr['success']){
			$userarr = $barr['data']['userlist'];
			$ids 	 = '0';
			foreach($userarr as $k=>$rs){
				$id1 = $this->userupdates($rs);
				$ids.=','.$id1.'';
			}
			$this->delete('`id` not in('.$ids.')');
		}
		return returnsuccess();
	}
	
	public function getuinfo($user)
	{
		return $this->getone("`user`='$user'");
	}
	
	public function userupdates($rs)
	{
		$where = "`user`='".$rs['user']."'";
		$uars  = $this->getone($where);
		if($uars){
			$id1  = $uars['id'];
		}else{
			$id1  = 0;
			$where='';
		}
		$uarr = array(
			'user' 	=> $rs['user'],
			'name' 	=> $rs['name'],
			'position' => $rs['position'],
			'mobile' => $rs['mobile'],
			'email' => $rs['email'],
			'tel' => $rs['tel'],
			'status' => $rs['status'],
			'deptid' => $rs['deptid'],
		);
		if(isset($rs['face']))$uarr['face'] = $rs['face'];
		$this->record($uarr, $where);
		if($id1==0)$id1 = $this->db->insert_id();
		return $id1;
	}
	
	
	
	//用户信息
	public function getuserinfo($user)
	{
		$url 	= $this->geturl('opendept','userinfo', array('user'=>$user));
		$result = c('curl')->getcurl($url);
		$barr 	= $this->recordchu($result);
		if(!$barr['success'])return $barr;
		$rs 	= $barr['data']['userinfo'];
		$this->userupdates($rs);
		return returnsuccess($rs);
	}
	
	//更新用户
	public function userupdate($urs)
	{
		$urs['position'] = $urs['ranking'];
		$urs['istxl'] 	 = $urs['isvcard'];
		$urs['gender']	 = ($urs['sex']=='女')?2:1;
		unset($urs['ranking']);
		unset($urs['sex']);
		unset($urs['isvcard']);
		$url 	= $this->geturl('opendept','userupdate');
		$result = c('curl')->postcurl($url, json_encode($urs));
		$barr 	= $this->recordchu($result);
		if($barr['success'])$this->getuserinfo($urs['user']);
		return $barr;
	}
	
	//更新全部部门
	public function deptallupdate()
	{
		$rows 	= m('dept')->getall('1=1','`id`,`name`,`num`,`pid`,`sort`,`headman`');
		$arr['deptalldata'] = $rows;
		$url 	= $this->geturl('opendept','deptallupdate');
		$result = c('curl')->postcurl($url, json_encode($arr));
		$barr 	= $this->recordchu($result);
		return $barr;
	}
	
	public function notinadmin($ids='')
	{
		$sql = "select a.`user` from `[Q]zreim_user` a left join `[Q]admin` b on a.`user`=b.`user` where";
		if($ids!=''){
			$sql.= " b.id in($ids)";
		}else{
			$sql.= ' b.id is null';
		}
		$rows= $this->db->getall($sql);
		$arr = array();
		foreach($rows as $k=>$rs){
			$arr[] = $rs['user'];
		}
		return $arr;
	}
	
	//删除用户
	public function deleteuserall($ids='')
	{
		$arr 	= $this->notinadmin($ids);
		if(!$arr){
			return returnerror('没有可删除用户');
		}
		return $this->userdelete(join(',', $arr));
	}
	public function userdelete($user)
	{
		$url 	= $this->geturl('opendept','userdelete');
		$body	= '{"userlist":"'.$user.'"}';
		$result = c('curl')->postcurl($url, $body);
		$barr 	= $this->recordchu($result);
		if($barr['success'])$this->getuserlist();
		return $barr;
	}
}