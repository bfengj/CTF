<?php
class extentClassAction extends Action
{
	public function beforeextentuser($table)
	{
		$key	= $this->post('key');
		$where  = '';
		if(!isempt($key))$where = m('admin')->getkeywhere($key);
		if($this->adminid>1)$where.=m('admin')->getcompanywhere();
		return array(
			'where' => 'and `status`=1 and `type`=0 '.$where.'',
			'fields'=> '`id`,`name`,`user`,`deptname`'
		);
	}
	
	public function beforeextentgroup($table)
	{
		$where = '';
		if($this->adminid>1 && ISMORECOM){
			$where.= 'and `companyid` in(0,'.m('admin')->getcompanyid().')';
		}
		return $where;
	}

	/**
		保存
	*/
	public function saveAjax()
	{
		$type		= $this->rock->post('type');
		$mid		= $this->rock->post('mid');
		$checkaid	= $this->rock->post('checkaid');
		if($type == 'clear'){
			$this->extentclear($mid);
		}else{
			$this->db->delete($this->T('sjoin'), "`type`='$type' and `mid`='$mid'");
		}
		$ntable		= '';
		$msg		= '';
		switch($type){
			case 'um';
				$ntable = ''.PREFIX.'menu';
			break;	
			case 'gm';
				$ntable = ''.PREFIX.'menu';
			break;
			case 'mu';
				$ntable = ''.PREFIX.'admin';
			break;
			case 'mg';
				$ntable = ''.PREFIX.'group';
			break;
		}
		if($ntable != '' && $checkaid != '' ){
			$this->db->insert($this->T('sjoin'),'`type`,`mid`,`sid`,`indate`',"select '$type','$mid',`id`,'$this->now' from `$ntable` where `id` in($checkaid)",true);
		}
		if($msg=='')$msg='success';
		echo $msg;
	}
	
	//清空用户权限
	private function extentclear($uid)
	{
		$this->db->delete($this->T('sjoin'), "( (`type` in ('um','uu','ut') and `mid`='$uid') or (`type`='mu' and `sid`='$uid') )");
	}
	
	public function qingkongAjax()
	{
		$this->db->delete($this->T('sjoin'), "`type` not in ('ug','gu')");
	}
	
	/**
		获取权限信息
	*/
	function getextentAjax()
	{
		$type		= $this->rock->post('type');
		$mid		= $this->rock->post('mid');
		$ntable		= '';
		$s			= '[0]';
		
		//权限查看的
		if($type == 'view'){
			$s		= m('sjoin')->getuserext($mid);
		}else{
			$rsa	= $this->db->getall("select `sid` from `".PREFIX."sjoin` where `type`='$type' and `mid`='$mid'");
			foreach($rsa as $rs)$s.=',['.$rs['sid'].']';
		}
		echo $s;
	}
	
}