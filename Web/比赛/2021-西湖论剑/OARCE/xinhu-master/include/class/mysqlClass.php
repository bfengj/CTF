<?php
include_once('mysql.php');
class mysqlClass extends mysql{
	
	
	protected function connect()
	{
		$this->errormsg	= '';
		if(!function_exists('mysql_connect'))exit('不支持mysql_connect的php扩展');
		$this->conn	= @mysql_connect($this->db_host,$this->db_user, $this->db_pass);
		$msg  = $this->error();
		if($msg){
			$this->conn 	= null;
			$this->errormsg	= $msg;
		}else{
			$this->selectdb($this->db_base);
			$this->query("SET NAMES 'utf8'");
		}
	}
	
	protected function selectdb($name)
	{
		$this->basename	= $name;
		@mysql_select_db($name, $this->conn); 
		$msg  = $this->error();
		if($msg){
			$this->errormsg	= $msg;
		}
	}

	protected function querysql($sql)
	{
		return mysql_query($sql,$this->conn);
	}
	
	public function fetch_array($result, $type = 0)
	{
		$result_type = ($type==0)?MYSQL_ASSOC:MYSQL_NUM;
		return mysql_fetch_array($result, $result_type);
	}
	
	public function insert_id()
	{
		return mysql_insert_id();
	}
	
	protected function starttran()
	{
		$this->query('BEGIN');
	}
	
	protected function endtran($bo)
	{
		if(!$bo){
			$this->query('ROLLBACK');
		}else{
			$this->query('COMMIT');
		}
	}
	
	public function getalltable_old()
	{
		@$result = mysql_list_tables($this->basename);
		while($row = mysql_fetch_row($result)) {
			$arr[]=$row[0];
		}	
		return $arr;
	}
	
	public function getallfields($table)
	{
		$sql	= 'select * from '.$table.' limit 0,0';
		$row	= $this->query($sql);
		$scount = mysql_num_fields($row);
		for($i=0; $i<$scount; $i++){
			$arr[]	= mysql_field_name($row, $i);
		}
		return $arr;
	}
		
	public function error()
	{
		return mysql_error();
	}
	
	public function close()
	{
		return @mysql_close($this->conn);
	}
}