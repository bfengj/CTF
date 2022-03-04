<?php
//公司单位
class companyClassModel extends Model
{
	public function getselectdata($lx=0)
	{
		$rows = $this->getall('id>0','`id`,name,pid','`sort`');
		$barr = array();
		if($lx==0)$barr[] = array(
			'value' => '0',
			'name'  => '最顶级',
		);
		$this->getselectdatas($rows, $barr, '0', 0);
		return $barr;
	}
	private function getselectdatas($rows,&$barr, $pid='0', $level=0)
	{
		foreach($rows as $k=>$rs){
			if($rs['pid']==$pid){
				$str  = '';
				for($i=0;$i<$level;$i++)$str.='&nbsp;&nbsp;&nbsp;';
				if($str!='')$str.='├';
				$name = ''.$str.''.$rs['name'].'';
				$barr[] = array(
					'name'  => $name,
					'value' => $rs['id'],
				);
				$this->getselectdatas($rows, $barr, $rs['id'], $level+1);
			}
		}
	}
	
	//树形结构
	public function gettreedata($rows, &$barr, $pid='0', $level=1)
	{
		foreach($rows as $k=>$rs){
			if($rs['pid']==$pid){
				$rs['level'] 	= $level;
				$rs['stotal'] 	= $this->gettreetotal($rows, $rs['id']);

				$barr[] = $rs;
				$this->gettreedata($rows, $barr, $rs['id'], $level+1);
			}
		}
	}
	public function gettreetotal($rows, $pid)
	{
		$stotal = 0;
		foreach($rows as $k=>$rs){
			if($rs['pid']==$pid){
				$stotal++;
			}
		}
		return $stotal;
	}
	
	//公司名称修改了，对应数据更新
	public function updatecompany($id, $name)
	{
		m('userract')->update("`company`='$name'","`companyid`='$id'");//员工合同
	}
}