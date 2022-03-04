<?php
include_once('mysql.php');
class pdoClass extends mysql{
	
	
	protected function connect()
	{
		$this->errormsg	= '';
		try {
			$this->conn = @new PDO('mysql:host='.$this->db_host.';dbname='.$this->db_base.'', $this->db_user, $this->db_pass);
			$this->conn->query("SET NAMES 'utf8'");
			$this->selectdb($this->db_base);
		} catch (PDOException $e) {
			$this->conn 	= null;
			$this->errormsg = $e->getMessage();
		}
	}
	
	protected function querysql($sql)
	{
		try {
			$bo = $this->conn->query($sql);
		} catch (PDOException $e) {
			$bo = false;
			$this->errormsg = $e->getMessage();
		}
		return $bo;
	}
	
	public function fetch_array($result, $type = 0)
	{
		$result_type = ($type==0)? PDO::FETCH_ASSOC : PDO::FETCH_NUM;
		return $result->fetch($result_type);
	}
	
	public function insert_id()
	{
		return $this->conn->lastInsertId();
	}
	
	protected function starttran()
	{
		$this->conn->beginTransaction();
	}
	
	protected function endtran($bo)
	{
		if(!$bo){
			$this->conn->rollBack();
		}else{
			$this->conn->commit();
		}
	}
		
	public function error()
	{
		$str = $this->conn->errorInfo();
		return 'pdoError('.$str[0].'):'.$str[2].''.$this->errormsg.'';
	}
	
	public function close()
	{
		if($this->conn==null)return;
		return $this->conn=null;
	}
}