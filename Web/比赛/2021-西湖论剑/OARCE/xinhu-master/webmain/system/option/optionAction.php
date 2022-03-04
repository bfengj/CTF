<?php
class optionClassAction extends Action
{
	public function getlistAjax()
	{
		$num	= $this->request('num');
		$name	= $this->request('name');
		$key 	= $this->post('key');
		$id		= $this->option->getnumtoid($num, $name, false);
		$this->option->update("`pid`=1,`name`='行政选项'","`num`='goods'"); //行政选项移动到数据选项下
		if(isempt($key)){
			$where = "a.`pid`='$id'";
		}else{
			$where = "1=1 and a.`name` is not null and (a.`name` like '%$key%' or a.`num`='$key') ";
		}
		
		$rows	= $this->db->getall("select *,(select count(1) from `[Q]option` where pid=a.id)as stotal from `[Q]option` a where $where order by a.`sort`, a.`id`");
		echo json_encode(array(
			'totalCount'=> $this->db->count,
			'rows'		=> $rows,
			'pid'		=> $id
		));
	}
	
	public function getfileAjax()
	{
		$mtype 	= $this->request('mtype');
		$mid 	= $this->request('mid');
		$rows 	= m('file')->getfile($mtype, $mid);
		echo json_encode($rows);
	}
	
	public function gettreedataAjax()
	{
		$num 	= $this->get('num');
		if($num=='')exit('error;');
		if(!contain($num, 'gerenvcard_') && ISMORECOM && $cnum=m('admin')->getcompanynum())$num.='_'.$cnum.'';//多单位时个人通讯不用加单位编号
		$pid 	= $this->option->getnumtoid($num,''.$num.'选项', false);
		$rows 	= $this->option->gettreedata($pid);
		$rows	= array(
			'rows' 	=> $rows,
			'pid'	=> $pid
		);
		$this->returnjson($rows);
	}
	
	public function deloptionAjax()
	{
		$id 	= (int)$this->post('id','0');
		$stable = $this->post('stable');
		$delbo	= true;
		if($delbo)if($this->option->rows("`pid`='$id'")>0)$delbo=false;
		if(!$delbo)$this->showreturn('','有下级分类不允许删除',201);
		$this->option->delete($id);
		if($stable!='')m($stable)->update('`typeid`=0', "`typeid`='$id'");
		$this->showreturn();
	}
	
	//分类移动
	public function movetypeAjax()
	{
		$id 	= (int)$this->post('id','0');
		$toid 	= (int)$this->post('toid','0');
		$lx 	= (int)$this->post('lx','0');
		$spath 	= $this->db->getpval('[Q]option','pid','pid', $toid,'],[');
		$spath	= '['.$spath.']';
		if(contain($spath,'['.$id.']')){
			echo '不能移动到自己的下级';
		}else{
			$this->option->update('pid='.$toid.'', $id);
			echo 'ok';
		}
	}
	
	public function downshuafter($table, $rows)
	{
		$db = m($table);
		foreach($rows as $k=>$rs){
			$dcount = $db->rows('pid='.$rs['id'].'');
			if($dcount>0)$rows[$k]['dcount'] = $dcount;
		}
		return array(
			'rows' => $rows
		);
	}
}