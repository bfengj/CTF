<?php
//读取多行对应子表数据
class itemsClassModel extends Model
{
	public function getitemsdata($modeid, $table, $mid)
	{
		$subarr	 = $this->db->getall("SELECT iszb,GROUP_CONCAT(`fields`)`fields` FROM `[Q]flow_element` where mid='$modeid' and `iszb`>0 GROUP BY `iszb`");
		$arr 	 = array();
		foreach($subarr as $k=>$rs){
			$xu 	= $rs['iszb']-1;
			$fields = $rs['fields'].',sort,id';
			$fields	= str_replace(',','`,`', $fields);
			$fields	= "`$fields`";
			$arr['subdata'.$xu.''] = $this->getrows("`table`='$table' and `mid`='$mid' and `valid`=1", $fields, '`sort`');
		}
		return $arr;
	}
	
}