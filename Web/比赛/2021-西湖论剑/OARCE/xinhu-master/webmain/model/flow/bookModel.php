<?php
class flow_bookClassModel extends flowModel
{
	public function flowrsreplace($rs,$isv=0)
	{
		if(isset($rs['typeid']))$rs['typeid'] 	= $this->db->getmou('[Q]option','name',"`id`='".$rs['typeid']."'");
		return $rs;
	}

	protected function flowbillwhere($uid, $lx)
	{
		$where  = '';
		$typeid = $this->rock->post('typeid','0');
		$key 	= $this->rock->post('key');
		if($typeid!='0'){
			$where .= ' and `typeid`='.$typeid.'';
		}
		//if($key != '')$where.=" and (`title` like '%$key%' or `author` like '%$key%')";
		return array(
			'where' => $where,
			'order' => 'optdt desc'
		);
	}
	
	//导入数据的测试显示
	public function flowdaorutestdata()
	{
		return array(
			'typeid' 		=> '计算机/编程',
			'title' 		=> 'PHP从入门到放弃',
			'num' 		=> 'TS-003',
			'author' 		=> '磐石',
			'chuban' 		=> '信呼开发团队',
			'cbdt' 			=> '2017-01-17',
			'price' 		=> '49.9',
			'shul' 		=> '5',
			'weizhi' 		=> '不知道',
			'isbn' 		=> 'PANSHI-001',
		);
	}
	
	//导入之前
	public function flowdaorubefore($rows)
	{
		$inarr = array();
		$num 	= 'booktype';
		if(ISMORECOM && $cnum=$this->adminmodel->getcompanynum())$num.='_'.$cnum.'';
		foreach($rows as $k=>$rs){
			$rs['typeid'] 	= $this->option->gettypeid($num,$rs['typeid']);
			$inarr[] = $rs;
		}
		
		return $inarr;
	}
	
	
}