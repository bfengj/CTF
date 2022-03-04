<?php
class groupClassAction extends Action
{
	public function groupusershow($table)
	{
		$s 		= 'and 1=2';
		$gid 	= $this->post('gid','0');
		if($gid>0){
			$s = " and ( id in( select `sid` from `[Q]sjoin` where `type`='gu' and `mid`='$gid') or id in( select `mid` from `[Q]sjoin` where `type`='ug' and `sid`='$gid') )";
		}
		
		
		return array(
			'where' => $s,
			'fields'=> 'id,user,name,deptname,ranking'
		);
	}
	
	public function groupafter($table, $rows)
	{
		$nosq = 'select `id` from `[Q]admin` where `status`=1';
		m('sjoin')->delete("`type`='gu' and `sid` not in($nosq)");
		m('sjoin')->delete("`type`='ug' and `mid` not in($nosq)");
		
		
		$carr	= m('admin')->getcompanyinfo(0,5);
		$dbs 	= m('company');
		foreach($rows as $k=>$rs){
			$gid = $rs['id'];
			$s 	 = "( id in( select `sid` from `[Q]sjoin` where `type`='gu' and `mid`='$gid') or id in( select `mid` from `[Q]sjoin` where `type`='ug' and `sid`='$gid') )";
			$rows[$k]['utotal'] = $this->db->rows('[Q]admin', $s);
			$companyname = '';
			if($rs['companyid']>0 && getconfig('companymode'))$companyname = $dbs->getmou('name', $rs['companyid']);
			$rows[$k]['companyname'] = $companyname;
		}
		
		return array(
			'rows' => $rows,
			'carr' => $carr,
		);
	}
	
	public function saveuserAjax()
	{
		$gid 	= $this->post('gid','0');
		$sid 	= $this->post('sid','0');
		$dbs 	= m('sjoin');
		$dbs->delete("`mid`='$gid' and `type`='gu' and `sid` in($sid)");
		$this->db->insert('[Q]sjoin','`type`,`mid`,`sid`', "select 'gu','$gid',`id` from `[Q]admin` where `id` in($sid)", true);
		m('admin')->updateinfo('and a.`id` in('.$sid.')');
		echo 'success';
	}
	
	public function deluserAjax()
	{
		$gid 	= $this->post('gid','0');
		$sid 	= $this->post('sid','0');
		$dbs 	= m('sjoin');
		$dbs->delete("`mid`='$gid' and `type`='gu' and `sid`='$sid'");
		$dbs->delete("`sid`='$gid' and `type`='ug' and `mid`='$sid'");
		m('admin')->updateinfo('and a.`id` in('.$sid.')');
		echo 'success';
	}
}