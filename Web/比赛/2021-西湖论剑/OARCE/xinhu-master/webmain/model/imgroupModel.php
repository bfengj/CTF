<?php
class imgroupClassModel extends Model
{
	public function initModel()
	{
		$this->settable('im_group');
	}
	
	public function updateguser($id, $deptid='')
	{
		if(isempt($deptid)){
			$onr 	= $this->getone($id);
			$deptid = $onr['deptid'];
		}
		if(isempt($deptid) || $deptid=='0')return;
		$dbs	= m('im_groupuser');
		$uids	= m('admin')->gjoin($deptid,'d');
		if(isempt($uids))$uids='0';
		$dbs->delete("`gid`='$id' and `uid` not in($uids)");
		$rows 	= $this->db->getall("SELECT a.id,a.`name`,b.gid FROM `[Q]admin` a left join `[Q]im_groupuser` b on a.`id`=b.`uid` and b.`gid`='$id' where a.`status`=1 and a.`id` in($uids)");
		foreach($rows as $k=>$rs){
			if(isempt($rs['gid']))$dbs->insert(array(
				'gid' => $id,
				'uid' => $rs['id']
			));
		}
	}
	
	public function updategall()
	{
		$rows = $this->getall('`type`<>2','`id`,`deptid`');
		foreach($rows as $k=>$rs)$this->updateguser($rs['id'], $rs['deptid']);
	}
}