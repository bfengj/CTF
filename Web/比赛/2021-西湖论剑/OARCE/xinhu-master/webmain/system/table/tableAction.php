<?php
class tableClassAction extends Action
{
	public function initAction()
	{
		if($this->getsession('isadmin')!='1')backmsg('别乱来');
		if(getconfig('systype')=='demo')backmsg('演示的不要改');
	}
	
	public function tablebefore($table)
	{
		$key	= $this->post('key');
		$where 	= 'and `TABLE_SCHEMA`=\''.DB_BASE.'\'';
		if($key!='')$where.=" and (`TABLE_NAME` like '%$key%' or `TABLE_COMMENT` like '%$key%')";
		return array(
			'table' => 'information_schema.`TABLES`',
			'fields'=> '`TABLE_NAME` as id,`ENGINE` as `engine`,`TABLE_ROWS` as `rows`,`TABLE_COMMENT` as `explain`,`CREATE_TIME` as `cjsj`,`UPDATE_TIME` as `gxsj`,`TABLE_COLLATION`',
			'where' => $where
		);
	}
	
	//保存表备注
	public function tablesmAjax()
	{
		$id 	= $this->post('id');
		$value 	= $this->post('value');
		$sql 	= "ALTER TABLE `$id` COMMENT '$value';";
		$this->db->query($sql);
		return 'success';
	}
	
	public function tablefieldsAjax()
	{
		$table 	= $this->post('table');
		$rows 	= $this->db->gettablefields($table);
		foreach($rows as $k=>$rs)$rows[$k]['id']=$rs['name'];
		$arr['rows'] = $rows;
		$this->returnjson($arr);
	}
	
	public function savefieldsAjax()
	{
		
		$table 	= $this->post('table');
		$allfields = $this->db->getallfields($table);
		$name 	= strtolower($this->post('name'));
		if(c('check')->isincn($name))backmsg('字段名不能有中文');
		$type 	= $this->post('type');
		$dev 	= $this->post('dev');
		$isnull = $this->post('isnull');
		if($table=='' || $name=='' || $type=='')backmsg('hehe');
		
		$lens 	= $this->post('lens');
		$sm  	= $this->post('explain');
		$sql 	= "ALTER TABLE `$table`";
		if(!in_array($name, $allfields)){
			$sql.=' ADD';
		}else{
			$sql.=' MODIFY';
		}
		$sql.=" `$name`";
		$cew = '[varchar][mediumint][int][bigint][smallint][tinyint][decimal]';
		if(contain($cew,'['.$type.']')){
			if($lens=='0')$lens='10';
			$sql.=" $type($lens)";
		}else{
			$sql.=" $type";
		}
		if($isnull=='NO')$sql.=' NOT NULL';
		if($dev==''){
			//$sql.=' DEFAULT NULL';
		}else{
			$sql.=" DEFAULT '$dev'";
		}
		$sql.=" COMMENT '$sm'";
		$bo = $this->db->query($sql);
		$msg = '';
		if(!$bo)$msg='错误《'.$sql.'》';
		backmsg($msg);
	}
	
	public function delfieldsAjax()
	{
		$table 	= $this->post('table');
		$id 	= $this->post('id');
		$sql 	= "ALTER table `$table` DROP COLUMN `$id`;";
		$msg = '';
		$bo = $this->db->query($sql);
		if(!$bo)$msg='错误《'.$sql.'》';
		backmsg($msg);
	}
	
	
	
	public function tablerecord_before()
	{
		$stable = $this->post('stable','', 1);
		$key = $this->post('key');
		$fid = $this->post('fields','id');
		$this->nowtablename = $stable;
		$where = '';
		if(!isempt($key))$where=" and `$fid`='$key'";
		return array(
			'table' => $stable,
			'order' => 'id desc',
			'where'	=> $where
		);
	}
	public function tablerecord_after($table, $rows)
	{
		$fieldsar	= array();
		if($this->loadci==1){
			$fieldsarr 	= $this->db->gettablefields($this->nowtablename);
			foreach($fieldsarr as $k1=>$rs1){
				$sortable = in_array($rs1['type'], array('int','date','datetime','tinyint','smallint','decimal'));
				$text = $rs1['name'];
				if(!isempt($rs1['explain']))$text.='('.$rs1['explain'].')';
				$fieldsar[] = array(
					'text' 		=> $text,
					'dataIndex' => $rs1['name'],
					'sortable' 	=> $sortable
				);
			}
		}
		return array(
			'fieldsarr' => $fieldsar
		);
	}
}