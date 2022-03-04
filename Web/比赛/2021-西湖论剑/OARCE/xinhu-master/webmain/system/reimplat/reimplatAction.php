<?php
class reimplatClassAction extends Action
{

	public function setsaveAjax()
	{
		$this->option->setval('reimplat_purl@-7', $this->post('purl'));
		$this->option->setval('reimplat_cnum@-7', $this->post('cnum'));
		$this->option->setval('reimplat_secret@-7', $this->post('secret'));
		$this->option->setval('reimplat_devnum@-7', $this->post('devnum'));
		$this->option->setval('reimplat_huitoken@-7', $this->post('huitoken'));
		return 'ok';
	}
	
	public function getsetAjax()
	{
		$arr= array();
		$arr['purl']		= $this->option->getval('reimplat_purl');
		$arr['cnum']		= $this->option->getval('reimplat_cnum');
		$arr['secret']		= $this->option->getval('reimplat_secret');
		$arr['devnum']		= $this->option->getval('reimplat_devnum');
		$arr['huitoken']	= $this->option->getval('reimplat_huitoken');
		$arr['huiurl']		= ''.URL.'api.php?m=reimplat';
		if(COMPANYNUM)$arr['huiurl'].='&dwnum='.COMPANYNUM.'';
		echo json_encode($arr);
	}
	
	//获取信呼系统上部门
	public function deptdataAjax()
	{
		$this->rows	= array();
		$this->getdept(0, 1);
		
		$this->returnjson(array(
			'totalCount'=> 0,
			'rows'		=> $this->rows
		));
	}
	private function getdept($pid, $oi)
	{
		$db		= m('dept');
		$menu	= $db->getall("`pid`='$pid' order by `sort`",'*');
		foreach($menu as $k=>$rs){
			$sid			= $rs['id'];
			
			$rs['level']	= $oi;
			$rs['stotal']	= $db->rows("`pid`='$sid'");
		
			$rs['zt']		= 1;
			$this->rows[] = $rs;
			$this->getdept($rs['id'], $oi+1);
		}
	}
	
	
	public function deptwxdataAjax()
	{
		$barr = m('reimplat:dept')->getdeptlist();
		if(!$barr['success'])return $barr;
		$rows = $barr['data'];
		
		$this->returnjson(array(
			'totalCount'=> 0,
			'rows'		=> $rows
		));
	}
	
	
	
	//微信上用户操作
	public function beforeusershow($table)
	{
		$fields = 'id,name,`user`,deptname,status,tel,ranking,superman,loginci,deptid,sex,mobile,email,sort,face';
		$fields.=',deptids,deptnames';
		$s 		= '';
		$key 	= $this->post('key');
		if($key!=''){
			$s = " and (`name` like '%$key%' or `user` like '%$key%' or `ranking` like '%$key%' or `deptname` like '%$key%' ";
			$s.=" or `deptnames` like '%$key%'";
			$s.= ')';
		}
		
		return array(
			'fields'=> $fields,
			'where'	=> $s
		);
	}
	
	private function isgcstr($urs, $purs)
	{
		if(!$urs || !$purs)array(0,'');
		
		$isgc = 0;
		$isgcstr = '';
		if($urs['mobile']!=$purs['mobile']){$isgc = 1;$isgcstr = '手机号';}
		if($urs['deptid']!=$purs['deptid']){$isgc = 1;$isgcstr = '部门';}
		if($urs['ranking']!=$purs['position']){$isgc = 1;$isgcstr = '职位';}
		if($urs['name']!=$purs['name']){$isgc = 1;$isgcstr = '姓名';}
		if($urs['email']!=$purs['email']){$isgc = 1;$isgcstr = '邮箱';}
		if($urs['tel']!=$purs['tel']){$isgc = 1;$isgcstr = '办公电话';}
		
		return array($isgc, $isgcstr);
	}
	
	public function afterusershow($table, $rows)
	{
		$obj = m('reimplat:dept');
		foreach($rows as $k=>$rs){
			$iscj 		= 0;
			$yurs 	= $obj->getuinfo($rs['user']);
			if($yurs){
				$iscj 		= 1;
				$rows[$k]['isgz']	= $yurs['status'];
				$nars  = $this->isgcstr($rs, $yurs);
				$rows[$k]['isgc'] = $nars[0];
				$rows[$k]['isgcstr'] = $nars[1];
			}
			$rows[$k]['iscj']	= $iscj;
			$rows[$k]['yurs']	= $yurs;
			$rows[$k]['mobile']	= substr($rs['mobile'],0,3).'****'.substr($rs['mobile'],-4);
		}
		$noarr = $obj->notinadmin();
		return array('rows'=>$rows,'notstr'=>join(',', $noarr));
	}
	
	public function reloaduserAjax()
	{
		return m('reimplat:dept')->getuserlist();
	}
	
	public function delalluserAjax()
	{
		return m('reimplat:dept')->deleteuserall();
	}
	
	public function updateuserAjax()
	{
		$id 	= (int)$this->get('id','0');
		$urs	= m('admin')->getone($id,'`user`,`name`,`ranking`,`superman`,`isvcard`,`tel`,`mobile`,`email`,`deptid`,`deptname`,`sex`,`sort`,`pingyin`');
		$barr 	= m('reimplat:dept')->userupdate($urs);
		return $barr;
	}
	
	public function updatealldeptAjax()
	{
		$barr 	= m('reimplat:dept')->deptallupdate();
		return $barr;
	}
	
	public function agentdataAjax()
	{
		$barr =m('reimplat:agent')->listdata(); 
		$rows = array();
		if($barr['success'])$rows = $barr['data'];
		
		$this->returnjson(array(
			'totalCount'=> 0,
			'rows'		=> $rows
		));
	}
	
	public function sendmsgAjax()
	{
		$name = $this->post('name');
		$msg = $this->post('msg');
		return m('reimplat:agent')->sendxiao($this->adminid, $name, $msg);
	}
	
	public function senduserAjax()
	{
		$id 	= (int)$this->post('id');
		$msg 	= $this->post('msg');
		return m('reimplat:agent')->sendxiao($id, '', $msg);
	}
}