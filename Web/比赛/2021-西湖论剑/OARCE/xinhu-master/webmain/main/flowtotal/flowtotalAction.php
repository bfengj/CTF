<?php
class flowtotalClassAction extends Action
{
	public function flowtotalbefore($table)
	{
		return array(
			'where' => ' and 1=2'
		);
	}
	
	public function flowtotalafter($table,$rows)
	{
		
		$barr['rows'] = $rows;
		if($this->loadci==1)$barr['modearr'] = m('mode')->getmodearr('and `id` in(select `mid` from `[Q]flow_element` where `istj`=1)');
		$modeid = (int)$this->post('modeid');
		if($modeid>0){
			$flnum	= m('flow_set')->getmou('num', $modeid);
			$rows 	= m('flow')->initflow($flnum)->flowtotal();
			$barr['rows'] = $rows;
		}
		return $barr;
	}
	
	public function changefieldsAjax()
	{
		$modeid = (int)$this->get('modeid');
		$rows 	= m('flow_element')->getall('mid='.$modeid.' and `istj`=1','`fields`,`name`,`fieldstype`','`sort`');
		$fset 	= m('flow_set')->getone($modeid);
		$farr	= array();
		if(arrvalue($fset,'isflow')>0){
			$farr[]= array(
				'name' => '申请人',
				'fields' => 'b.`uname`',
			);
			$farr[]= array(
				'name' => '申请人部门',
				'fields' => 'b.`udeptname`',
			);
		}
		foreach($rows as $k=>$rs){
			$rows[$k]['name'] 		= ''.$rs['name'].'('.$rs['fields'].')';
			$rows[$k]['names'] 		= $rs['name'];
			$rows[$k]['fields'] 	= '[A]`'.$rs['fields'].'`';
			$rows[$k]['fieldss']	= $rs['fields'];
			$farr[] = $rows[$k];
		}
		
		$fwhee = m('flow_where')->getall('`setid`='.$modeid.' and `num` is not null','`num`,`name`','`pnum`,`sort`');
		
		echo json_encode(array(
			'farr' => $farr,
			'fwhe' => $fwhee
		));
	}
}