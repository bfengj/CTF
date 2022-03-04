<?php
class mode_finjkdClassAction extends inputAction{
	

	protected function savebefore($table, $arr, $id, $addbo){
		$rows['type'] = '2';//一定要是2，不能去掉
		return array(
			'rows'=>$rows
		);
	}

	protected function saveafter($table, $arr, $id, $addbo){
		
	}
	
	public function getlastAjax()
	{
		$rs = m('fininfom')->getone("`uid`='$this->adminid' and `type`<3 order by `optdt` desc",'paytype,cardid,openbank,fullname');
		if(!$rs)$rs='';
		$this->returnjson($rs);
	}
	
	
	//借款统计
	public function jktotalbeforeshow($table)
	{
	
		$kjk	= $this->post('kjk','0');
		$key	= $this->post('key');
		$atype	= $this->post('atype');
		$where 	= '';
		
		if(!isempt($key))$where.=" and (`name` like '%$key%' or `ranking` like '%$key%' or `deptname` like '%$key%')";
		if($kjk=='1'){
			$str   = m('fina')->getjkdwhere();
			$where.=" and `id` in($str)";
		}
		if($atype=='my'){
			$where='and id='.$this->adminid.'';
		}else{
			$where.= m('admin')->getcompanywhere(5);
		}
		
		$fields = 'id,name,deptname,ranking,workdate,state';
		return array('where'=>$where,'fields'=>$fields,'order'=>'`id`');
	}
	
	public function jktotalaftershow($table, $rows)
	{
		$zta 	= m('flow:userinfo');
		$uids 	= '0';
		foreach($rows as $k=>$rs){
			if($rs['state']==5)$rows[$k]['ishui']=1;
			$rows[$k]['state'] = $zta->getuserstate($rs['state']);
			$uids.=','.$rs['id'].'';
		}
		if($uids!='0')$rows = m('fina')->totalfkd($rows, $uids);
		
		return array(
			'rows' => $rows
		);
	}
}	
			