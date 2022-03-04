<?php
class imgroupClassAction extends Action
{
	public function groupusershow($table)
	{
		$s 		= 'and 1=2';
		$gid 	= $this->post('gid','0');
		if($gid>0){
			$s = " and id in(select `uid` from `[Q]im_groupuser` where `gid`='$gid')";
		}
		return array(
			'where' => $s,
			'fields'=> 'id,user,name,deptname,ranking'
		);
	}
	
	public function groupafter($table, $rows)
	{
		
		foreach($rows as $k=>$rs){
			$gid = $rs['id'];
			$s 	 = "`gid`='$gid'";
			$rows[$k]['utotal'] = $this->db->rows('[Q]im_groupuser', $s);
		}
		return array('rows'=>$rows);
	}
	
	public function saveuserAjax()
	{
		$gid 	= (int)$this->post('gid','0');
		$sid 	= $this->post('sid');
		m('reim')->adduserchat($gid, $sid, true);
		echo 'success';
	}
	
	public function deluserAjax()
	{
		$gid 	= $this->post('gid','0');
		$sid 	= $this->post('sid','0');
		m('reim')->exitchat($gid, $sid);
		echo 'success';
	}
	
	//保存组织结构
	public function savegroupafter($table, $arr, $id)
	{
		m('imgroup')->updateguser($id, $arr['deptid']);
		$rs = m($table)->getone($id);
		if(isempt($rs['createname'])){
			m($table)->update(array(
				'createid' => $this->adminid,
				'createname' => $this->adminname,
				'createdt' => $this->now,
			),$id);
		}
	}
	
	public function reloadallAjax()
	{
		m('imgroup')->updategall();
	}
}