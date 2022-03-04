<?php
/**
*	单位管理
*/
class flow_companyClassModel extends flowModel
{
	public $flowcompanyidfieds	= 'none'; 
	public $baseall = array();
	
	public function flowdeletebillbefore()
	{
		if($this->rows('pid='.$this->id.'')>0)return '有下级不允许删除';
	}
	
	
	public function getallbase()
	{
		if($this->baseall)return $this->baseall;
		$rows = $this->db->getall('show DATABASES');
		foreach($rows as $k=>$rs){
			$this->baseall[] = $rs['Database'];
		}
		return $this->baseall;
	}
	
	//替换
	public function flowrsreplace($rs)
	{
		$iscreate = 0;
		if(!isempt($rs['num'])){
			$base = ''.DB_BASE.'_company_'.$rs['num'].'';
			$arr  = $this->getallbase();
			if(in_array($base, $arr))$iscreate = 1;
		}
		if(isset($rs['iscreate']) && $iscreate!=$rs['iscreate'])$this->update('`iscreate`='.$iscreate.'', $rs['id']);
		$rs['iscreate'] = $iscreate;
		return $rs;
	}
	
	public function testabc()
	{
	}
}