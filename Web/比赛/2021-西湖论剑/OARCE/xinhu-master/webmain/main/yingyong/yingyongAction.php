<?php
class yingyongClassAction extends Action
{
	public function yingyongdataAjax()
	{
		$rows = m('im_group')->getall('`type`=2','*','`sort`');
		$arrs = array();
		/*
		foreach($rows as $k=>$rs){
			$sub 	= m('im_group')->getall('`type`=2 and pid='.$rs['id'].'','*','`sort`');
			$rs['leave'] 	= 1;
			$arrs[] 		= $rs;
			foreach($sub as $k1=>$rs1){
				$rs1['leave'] = 2;
				$arrs[] 	   = $rs1;
			}
		}*/
		echo json_encode(array('rows'=>$rows));
	}
	
	public function getdataAjax()
	{
		$rows = m('im_group')->getall('`type`=2','id,name,face,num,valid','`sort`');
		echo json_encode($rows);
	}
	
	public function loaddataAjax()
	{
		$id = (int)$this->get('id');
		$arr['data'] = m('im_group')->getone($id);
		echo json_encode($arr);
	}
	
	public function beforesave($table, $cans, $id)
	{
		$msg = '';
		$num = $cans['num'];
		if(m($table)->rows("`num`='$num' and `id`<>$id")>0)$msg='编号['.$num.']已存在';
		return array('msg'=>$msg);
	}
	
	
	public function menudataAjax()
	{
		$this->rows	= array();
		$mid		= (int)$this->get('mid');
		$agentnum	= m('im_group')->getmou('num',$mid);
		$where 		= "and `mid`='$mid'";
		$this->getmenu(0, 1, $where);
		$modeid 	= (int)m('flow_set')->getmou('id',"`num`='$agentnum'");
		$wherearr	= m('flow_where')->getrows("setid='$modeid' and `num` is not null and `status`=1",'`name`,`num`','`pnum`,`sort`');
		$barr[]		= array('num'=>'','name'=>'-选择-');
		foreach($wherearr as $k=>$rs){
			$wherearr[$k]['name'] = ''.$rs['num'].'.'.$rs['name'].'';
			$barr[] = $wherearr[$k];
		}
		
		echo json_encode(array(
			'totalCount'=> 0,
			'rows'		=> $this->rows,
			'agentnum'	=> $agentnum,
			'modeid'	=> $modeid,
			'wherearr'	=> $barr,
		));
	}
	
	private function getmenu($pid, $oi, $wh='')
	{
		$db		= m('im_menu');
		$menu	= $db->getall("`pid`='$pid' $wh order by `sort`",'*');
		foreach($menu as $k=>$rs){
			$sid			= $rs['id'];
			$rs['level']	= $oi;
			$rs['stotal']	= $db->rows("`pid`='$sid'  $wh ");
			$this->rows[] = $rs;
			
			$this->getmenu($sid, $oi+1, $wh);
		}
	}
	
	public function yingyongbefore($table)
	{
		return array(
			'order' => '`valid` desc,`sort` asc'
		);
	}
	
	public function yingyongafter($table, $rows)
	{
		foreach($rows as $k=>$rs){
			if($rs['valid']=='0')$rows[$k]['status']=0;
		}
		return array(
			'rows' => $rows
		);
	}
}