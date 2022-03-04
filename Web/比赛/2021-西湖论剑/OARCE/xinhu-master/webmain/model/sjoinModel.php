<?php
class sjoinClassModel extends Model
{
	private $getgroupidarr=array();
	
	//获取用户所在组Id
	public function getgroupid($uid, $fid='')
	{
		if($fid=='')$fid = 'id';
		$keys   = ''.$fid.''.$uid.'';
		if(isset($this->getgroupidarr[$keys]))return $this->getgroupidarr[$keys];
		$gasql	= " ( id in( select `sid` from `[Q]sjoin` where `type`='ug' and `mid`='$uid') or id in( select `mid` from `[Q]sjoin` where `type`='gu' and `sid`='$uid') )";
		$gsql	= "select `id` from `[Q]group` where $gasql ";
		$rows 	= $this->db->getall($gsql);
		$ids 	= '0';
		foreach($rows as $k=>$rs)$ids.=','.$rs[$fid].'';
		$this->getgroupidarr[$keys] = $ids;
		return $ids;
	}
	
	//把人员加到对应组上
	public function addgroupuid($uid, $gid)
	{
		$where 	= "1=1 and ((`type`='gu' and `sid`=$uid ) or (`type`='ug' and `mid`=$uid))";
		$this->delete($where);
		if(isempt($gid))return;
		$this->db->insert($this->table, '`type`,`mid`,`sid`,`indate`', "select 'ug','$uid',`id`,now() from `[Q]group` where id in($gid)", true);
	}
	
	//获取权限菜单id
	public function getmenuid($uid)
	{
		$gid 	= $this->getgroupid($uid);
		$whe1 	= "select `sid` from `[Q]sjoin` where ((`type`='um' and `mid`='$uid')";
		$whe2	= "select `mid` from `[Q]sjoin` where ((`type`='mu' and `sid`='$uid')";
		if($gid != '0'){
			$whe1.="  or (`type`='gm' and `mid` in($gid)) ";
			$whe2.="  or (`type`='mg' and `sid` in($gid)) ";
		}
		$whe1.= ')';
		$whe2.= ')';
		
		$ids 	= '0';
		$rows	= $this->db->getall($whe1);
		foreach($rows as $k=>$rs)$ids.=','.$rs['sid'].'';
		
		$rows	= $this->db->getall($whe2);
		foreach($rows as $k=>$rs)$ids.=','.$rs['mid'].'';
		return $ids;
	}
	
	/**
	*	查看菜单权限
	*/	
	public function getuserext($uid, $type=0)
	{
		$guid 	= '-1';
		if($type==1)return $guid;
		$guid	= '[0]';
		$mid 	= $this->getmenuid($uid);
		$arss	= $this->db->getall("select `id`,`pid`,(select `pid` from `[Q]menu` where `id`=a.`pid`)as `mpid` from `[Q]menu` a where (`status` =1 and `id` in($mid)) or (`status` =1 and `ispir`=0) order by `sort`");
		foreach($arss as $ars){
			$guid	.= ',['.$ars['id'].']';
			$bpid	 = $ars['pid'];
			$bmpid	 = $ars['mpid'];
			if(!contain($guid, '['.$bpid.']')){
				$guid.=',['.$bpid.']';
			}
			if(!isempt($bmpid)){
				if(!contain($guid, '['.$bmpid.']')){
					$guid.=',['.$bmpid.']';
				}
			}
		}
		return $guid;
	}
	
	/**
	*	获取组列表
	*/
	public function getgrouparr()
	{
		$where = '';
		if(ISMORECOM){
			$where='where `companyid` in(0,'.m('admin')->getcompanyid().')';
		}
		return $this->db->getall("select `id`,`name` from `[Q]group` $where order by `sort`");
	}
	/**
	*	获取组列表
	*/
	public function getgrouparrs()
	{
		$where = '';
		if(ISMORECOM){
			$where='where `companyid` in(0,'.m('admin')->getcompanyid().')';
		}
		return $this->db->getall("select `id` as value,`name` from `[Q]group` $where order by `sort`");
	}
}