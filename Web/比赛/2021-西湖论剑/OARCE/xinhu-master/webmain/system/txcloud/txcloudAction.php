<?php
class txcloudClassAction extends Action
{
	

	public function setsaveAjax()
	{
		$secretid = $this->post('secretid');
		$secretkey = $this->post('secretkey');
		if(!contain($secretid,'****'))
			$this->option->setval('txcloud_secretid@-6', $this->jm->encrypt($secretid));
		if(!contain($secretkey,'****'))
			$this->option->setval('txcloud_secretkey@-6', $this->jm->encrypt($secretkey));
		
		$this->option->setval('txcloud_rlroupid@-6', $this->post('rlroupid'));
		$this->backmsg();
	}
	
	public function getsetAjax()
	{
		$arr= array();
		$arr['secretid']		= $this->option->getval('txcloud_secretid');
		$arr['secretkey']		= $this->option->getval('txcloud_secretkey');
		$arr['rlroupid']		= $this->option->getval('txcloud_rlroupid');
		if(!isempt($arr['secretid'])){
			$secretid		 = $this->jm->uncrypt($arr['secretid']);
			$arr['secretid'] = substr($secretid,0,5).'******'.substr($secretid,-5);
		}
		if(!isempt($arr['secretkey'])){
			$secretkey		 = $this->jm->uncrypt($arr['secretkey']);
			$arr['secretkey'] = substr($secretkey,0,5).'******'.substr($secretkey,-5);
		}
		echo json_encode($arr);
	}
	
	//获取
	public function reloaduserAjax()
	{
		return m('txcloud:renlian')->GetPersonList();
	}
	
	//删除
	public function delrenlianAjax()
	{
		return m('txcloud:renlian')->deleteRenlian((int)$this->post('id','0'));
	}
	
	public function beforeuserdshow($table)
	{
		$where = '';
		$key 	= $this->post('key');
		if(!isempt($key))$where=" and (a.`personname` like '%$key%' or b.`deptallname` like '%$key%')";
		return array(
			'where' => $where,
			'fields'=> 'a.*,b.name,b.deptallname',
			'table' => '`[Q]'.$table.'` a left join `[Q]admin` b on a.uid=b.id'
		);
	}
	public function aftereuserdshow($table, $rows)
	{
		foreach($rows as $k=>$rs){
			if($rs['uid']>0 && $rs['personname']!=$rs['name']){
				$rows[$k]['name'].='<font color=red>,关联的姓名不一样</font>';
			}
			if(!isempt($rs['faceids']))$rows[$k]['imgshu'] = count(explode(',', $rs['faceids']));
		}
		return array(
			'rows' => $rows
		);
	}
	//创建用户
	public function createurenlianAjax()
	{
		return m('txcloud:renlian')->createUser(array(
			'id' => (int)$this->post('id'),
			'uid' => (int)$this->post('uid'),
			'personname' => $this->post('personname'),
			'imgurl' 	=> $this->post('imgurl'),
		));
	}
}