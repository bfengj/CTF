<?php
class deptClassAction extends Action
{
	public function defaultAction()
	{
		
	}
	
	public function dataAjax()
	{
		$carr		= m('admin')->getcompanyinfo(0,5);
		$this->allid= $carr['companyallid'];
		
		$this->rows	= array();
		$this->getdept(0, 1);
		$errmsg = '';
		if(ISMORECOM){
			foreach($this->rows as $k1=>$rs1){
				if($rs1['companyname']=='' && $rs1['level']==2){
					$this->rows[$k1]['trbgcolor']='red';
					$errmsg = '1';
				}
			}
			if($errmsg=='1')$errmsg='红色行必须选择所属单位，请编辑';
		}
		echo json_encode(array(
			'totalCount'=> 0,
			'rows'		=> $this->rows,
			'carr'		=> $carr,
			'errmsg'	=> $errmsg
		));
	}
	
	private function getdept($pid, $oi)
	{
		$db		= m('dept');
		$menu	= $db->getall("`pid`='$pid' order by `sort`",'*');
		foreach($menu as $k=>$rs){
			$comid 			= $rs['companyid'];
			
			//开启单位模式只能看到自己单位下的组织结构
			if(!in_array($comid, $this->allid) && $this->adminid>1 && getconfig('companymode'))continue;
			
			$companyname	= '';
			if($comid>0 && getconfig('companymode'))$companyname = m('company')->getmou('name', $comid);
			$rs['companyname'] = $companyname;
			$sid			= $rs['id'];
			$rs['level']	= $oi;
			$rs['stotal']	= $db->rows("`pid`='$sid'");
			$this->rows[] = $rs;
			
			$this->getdept($sid, $oi+1);
			
		}
	}
	
	public function publicbeforesave($table, $cans, $id)
	{
		$pid 	= (int)$cans['pid'];
		if($pid<=0 && $id != 1)return '上级ID必须大于0';
		if($pid!=0 && $id == 1)return '顶级禁止修改上级ID';
		if($pid!=0 && m($table)->rows('id='.$pid.'')==0)return '上级ID不存在';
		return '';
	}
	
	public function publicaftersave($table, $cans, $id)
	{
		$name 	= $cans['name'];
		$db 	= m('admin');
		$db->update("deptname='$name'", "`deptid`=$id");
		$db->updateinfo("and instr(a.`deptpath`,'[$id]')>0");
	}
	
	public function deptuserdataAjax()
	{
		$type 	= $this->request('changetype');
		$val 	= $this->request('value');
		$pid	= 0;
		$rows	= $this->getdeptmain($pid, $type, ','.$val.',');
		echo json_encode($rows);
	}
	
	private function getdeptmain($pid, $type, $val)
	{
		$sql	= $this->stringformat('select `id`,`name` from `?0` where `pid`=?1 order by `sort`', array($this->T('dept'), $pid));
		$arr	= $this->db->getall($sql);
		$rows	= array();
		foreach($arr as $k=>$rs){
			$children		= $this->getdeptmain($rs['id'], $type, $val);
			$uchek			= $this->contain($type, 'check');
			$expanded		= false;
			if($this->contain($type, 'user')){
				$sql	= $this->stringformat('select `id`,`name`,`sex`,`ranking`,`deptname` from `?0` where `deptid`=?1 and `status`=1 order by `sort`', array($this->T('admin'), $rs['id']));			
				$usarr	= $this->db->getall($sql);
				foreach($usarr as $k1=>$urs){
					$usarr[$k1]['leaf'] = true;
					$usarr[$k1]['uid']  = $urs['id'];
					$usarr[$k1]['id']   = 'u'.$urs['id'];
					$usarr[$k1]['type'] = 'u';
					$usarr[$k1]['icons'] = 'user';
					if($uchek){
						$bo = false;
						if($this->contain($type, 'dept')){
							$bo = $this->contain($val, $usarr[$k1]['id']);
						}else{
							$bo = $this->contain($val, $usarr[$k1]['uid']);
						}
						$usarr[$k1]['checked']=$bo;
						if(!$expanded)$expanded = $bo;
					}	
				}
				$children= array_merge($children, $usarr);
			}
			if($pid==0)$expanded = true;
			$ars['children']= $children;
			$ars['name'] 	= $rs['name'];
			$ars['id'] 		= 'd'.$rs['id'];
			$ars['did'] 	= $rs['id'];
			$ars['type'] 	= 'd';
			$ars['expanded'] = $expanded;
			
			if($this->contain($type, 'dept')){
				if($uchek){
					$bo = false;
					if($this->contain($type, 'user')){
						$bo = $this->contain($val, $ars['id']);
					}else{
						$bo = $this->contain($val, $ars['did']);
					}
					$ars['checked']=$bo;
				}	
			}
			$rows[]	= $ars;
		}
		return $rows;
	}
	
	public function deptuserjsonAjax()
	{
		$udarr 		= m('dept')->getdeptuserdata(1);
		$userarr 	= $udarr['uarr'];
		$deptarr 	= $udarr['darr'];
		$grouparr 	= $udarr['garr'];
		
		$arr['deptjson']	= json_encode($deptarr);
		$arr['userjson']	= json_encode($userarr);
		$arr['groupjson']	= json_encode($grouparr);
		$this->showreturn($arr);
	}
}