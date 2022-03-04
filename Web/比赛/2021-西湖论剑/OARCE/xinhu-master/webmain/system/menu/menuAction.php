<?php
class menuClassAction extends Action
{

	public function dataAjax()
	{
		$this->rows		= array();
		$type 			= $this->get('type');
		$where 			= '';
		//权限那来的
		if($type != ''){
			$where		= 'and `status`=1 and `ispir`=1 and `type`=0';
			if($type=='view')$where = 'and `status`=1 and `type`=0';
		}else{
			$this->updatepirss();
		}
		$this->alldata 	= $this->db->getall('select *,(select count(1)from `[Q]menu` where `pid`=a.id '.$where.')stotal from `[Q]menu` a where 1=1 '.$where.' order by `sort`');
		
		$this->getmenu(0, 1, 1);
		echo json_encode(array(
			'totalCount'=> 0,
			'rows'		=> $this->rows
		));
	}
	
	private function getmenu($pid, $oi, $zt)
	{
		$downid = '';
		foreach($this->alldata as $k=>$rs){
			if($pid==$rs['pid']){
				$downid.=','.$rs['id'].'';
				$rs['level']	= $oi;
				$zthui			= $rs['status'];
				if($zt==0){
					$rs['ishui']=1;
					$zthui = 0;
				}
				//if($oi>1)$rs['trstyle']='display:none;';
				$this->rows[] 	= $rs;
				$len = count($this->rows)-1;
				$sidss = $this->getmenu($rs['id'], $oi+1, $zthui);
				//if($sidss)$this->rows[$len]['downallid'] = substr($sidss,1);
			}
		}
		return $downid;
	}
	
	//下级需要验证，那上级也必须验证的
	private function updatepirss()
	{
		$rows 	= $this->db->getall('select `pid` from `[Q]menu` where `pid`>0 and `ispir`=1 group by `pid`');
		$sid 	= '0';
		foreach($rows as $k=>$rs)$sid.=','.$rs['pid'].'';
		if($sid!='')m('menu')->update('`ispir`=1', "`id` in($sid)");
	}
	
	/**
	* 菜单管理获取菜单
	*/
	public function getdataAjax()
	{
		$pvalue = (int)$this->get('pvalue','0');
		$level 	= (int)$this->get('level','1');
		$rows 	= $this->db->getall('select *,(select count(1)from `[Q]menu` where `pid`=a.id )stotal from `[Q]menu` a where `pid`='.$pvalue.' order by `sort`');
		foreach($rows as $k=>$rs)$rows[$k]['level'] = $level;
		echo json_encode(array(
			'totalCount'=> 0,
			'rows'		=> $rows
		));
	}
	
	public function delmenuAjax()
	{
		$id = (int)$this->post('id');
		if(m('menu')->rows('pid='.$id.'')>0)return returnerror('有下级菜单不能删除');
		m('menu')->delete($id);
		return returnsuccess();
	}
}