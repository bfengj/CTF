<?php
include_once('mysql.php');
class mysqliClass extends mysql{
	
	
	protected function connect()
	{
		$this->errormsg	= '';
		$this->conn = @new mysqli($this->db_host,$this->db_user, $this->db_pass, $this->db_base);
		if (mysqli_connect_errno()) {
			$this->conn 	= null;
			$this->errormsg	= mysqli_connect_error();
		}else{
			$this->selectdb($this->db_base);
			$this->conn->query("SET NAMES 'utf8'");
		}
	}
	
	protected function querysql($sql)
	{
		return $this->conn->query($sql);
	}
	
	public function fetch_array($result, $type = 0)
	{
		$result_type = ($type==0)?MYSQLI_ASSOC:MYSQLI_NUM;
		return $result->fetch_array($result_type);
	}
	
	public function insert_id()
	{
		return $this->conn->insert_id;
	}
	
	protected function starttran()
	{
		$this->conn->autocommit(FALSE);
	}
	
	protected function endtran($bo)
	{
		if(!$bo){
			$this->conn->rollback();
		}else{
			$this->conn->commit();
		}
	}
	
	public function getallfields($table)
	{
		$sql	= 'select * from '.$table.' limit 0,0';
		$result	= $this->query($sql);
		$finfo 	= $result->fetch_fields();
		foreach ($finfo as $val) {
			$arr[] = $val->name;
		}
		return $arr;
	}
		
	public function error()
	{
		return 'mysqliError:'.$this->conn->error;
	}
	
	public function close()
	{
		if($this->conn==null)return;
		return $this->conn->close();
	}
}