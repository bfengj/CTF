<?php
/**
	*****************************************************************
	* 联系QQ： 290802026											*
	* 版  本： V2.0													*
	* 开发者：雨中磐石工作室										*
	* 网  址： http://www.rockoa.com/								*
	* 说  明: 数据库核心类											*
	* 备  注: 未经允许不得商业出售，代码欢迎参考纠正				*
	*****************************************************************
*/ 
if(!defined('HOST'))exit('not access');
abstract class mysql{
	
	public $conn		= null; 
	public $iudcount	= 0;
	public $iudarr		= array();
	public $tran		= false;
	public $rock;
	
	public  $nowsql;
	public  $countsql	= 0;
	public  $sqlarr		= array();
	public  $total		= 0;
	public  $count		= 0;
	public 	$perfix		= PREFIX;
	public  $errorbool	= false;
	public  $errormsg	= '';
	public  $errorlast	= '';
	public  $nowerror	= false;
	public  $basename;
	
	protected $db_host;
	protected $db_user;
	protected $db_pass;
	protected $db_base;
	
	protected $exparray	= array(
		'eq'	=> "='?0'",		'neq'	=> "<>'?0'",	'eqi'	=> '=?0',	'neqi'	=> '<>?0', 	'lt'	=> "<'?0'", 	'elt'	=> "<='?0'",
		'gt'	=> ">'?0'", 	'egt'	=> ">='?0'",	'lti'	=> '<?0',	'elti'	=> '<=?0',	'gti'	=> '>?0',		'egti'	=> '>=?0',
		'like' => "LIKE '%?0%'",	'notlike' => "NOT LIKE '%?0%'",	'leftlike' => "LIKE '%?0'",	'rightlike' => "LIKE '?0%'",
		'in' => "IN(?0)",			'notin' => "NOT IN(?0)",
		'between' => "BETWEEN '?0' AND '?1'",	'notbetween' => "NOT BETWEEN '?0' AND '?1'",
		'betweeni' => "BETWEEN ?0 AND ?1",	'notbetweeni' => "NOT BETWEEN ?0 AND ?1"
	);
	
	public function __construct()
	{
		$this->rock			= $GLOBALS['rock'];
		$this->errorbool	= false;
		$this->errormsg		= '';
		if(getconfig('dbencrypt')){
			$this->db_host		= $this->rock->jm->uncrypt(DB_HOST);
			$this->db_user		= $this->rock->jm->uncrypt(DB_USER);
			$this->db_pass		= $this->rock->jm->uncrypt(DB_PASS);
			$this->db_base		= $this->rock->jm->uncrypt(DB_BASE);
		}else{
			$this->db_host		= DB_HOST;
			$this->db_user		= DB_USER;
			$this->db_pass		= DB_PASS;
			$this->db_base		= DB_BASE;
		}
		$this->basename = $this->db_base;
	} 

	public function __destruct()
	{
		if($this->conn){
			$this->tranend();
			$this->close();
		}
		//记录访问sql日志
		if(getconfig('sqllog')){
			$sql = '';
			$filstr = 'sqllog_'.date('Y.m.d.H.i.s').'_'.$this->rock->adminid.'_'.str_shuffle('abcdefghijklmn').'.log';
			foreach($this->sqlarr as $sql1)$sql.="\n\n$sql1;";
			if($sql!='')$this->rock->createtxt(''.UPDIR.'/sqllog/'.date('Y-m-d').'/'.$filstr.'', "时间[".$this->rock->now."],用户[".$this->rock->adminid.".".$this->rock->adminname."],IP[".$this->rock->ip."],WEB[".$this->rock->web."],URL[".$this->rock->nowurl()."]".$sql);
		}
	}

	protected function connect(){}
	protected function selectdb($name)
	{
		$this->basename	= $name;
	}
	
	protected function querysql($sql){return false;}
	
	protected function starttran(){}
	protected function endtran($bo){}
	
	public function fetch_array($res, $type=0){return false;}
	public function insert_id(){return 0;}
	
	public function error(){return '';}
	public function close(){}
	
	
	public function changeattr($host, $user, $pass, $base)
	{
		$this->db_host	= $host;
		$this->db_user	= $user;
		$this->db_pass	= $pass;
		$this->db_base	= $base;
	}
	
	public function connectdb()
	{
		$this->errormsg	= '';
		$this->connect();
		return $this->conn;
	}
	
	public function query($sql, $ebo=true)
	{
		if($this->conn == null)$this->connect();
		if($this->conn == null)exit('数据库的帐号/密码有错误!'.$this->errormsg.'');
		$sql	= trim($sql);
		$sql	= str_replace(array('[Q]','[q]','{asqom}'), array($this->perfix, $this->perfix,''), $sql);
		$this->countsql++;
		$this->sqlarr[]	= $sql;
		$this->nowsql	= $sql;
		$this->count 	= 0;
		try {
			$rsbool		= $this->querysql($sql);
		} catch (Exception $e) {
			$rsbool		= false;
			$this->errormsg = $e->getMessage();
		}
		
		$this->nowerror	= false;
		if(!$rsbool)$this->nowerror = true;
		
		$stabs  = ''.$this->perfix.'log';
		if(!contain($sql, $stabs) && !$rsbool)$this->errorlast = $this->error(); //最后错误信息
		
		//记录错误sql
		if(!$rsbool && $ebo){
			$txt	= '[ERROR SQL]'.chr(10).''.$sql.''.chr(10).''.chr(10).'[Reason]'.chr(10).''.$this->error().''.chr(10).'';
			$efile 	= $this->rock->debug($txt,''.DB_DRIVE.'_sqlerr', true);
			$errmsg = str_replace("'",'&#39;', $this->error());
			if(!contain($sql, $stabs)){
				m('log')->addlogs('错误SQL',''.$errmsg.'', 2, array(
					'url' => $efile
				));
			}
		}
		return $rsbool;
	}
	
	/**
	*	返回最后错误信息
	*/
	public function lasterror()
	{
		$err = $this->errorlast;
		if($err=='')$err = $this->error();
		return $err;
	}
	
	public function execsql($sql)
	{
		$rsa	= $this->query($sql);
		$this->iudarr[]=$rsa;
		return $rsa;
	}
	
	public function getLastSql()
	{
		return $this->nowsql;
	}
	
	public function getsyscount($lx='')
	{
		$to 	= 0;
		if($lx=='')return $to;
		$lx		= strtoupper($lx);
		$rsa	= $this->getall('SELECT '.$lx.'() as total');
		$to 	= $rsa[0]['total'];
		return $to;
	}
	
	/**
	*	返回使用SQL_CALC_FOUND_ROWS，统计总记录数
	*/
	public function found_rows()
	{
		return $this->getsyscount('found_rows');
	}
	
	/**
	*	返回update,insert,delete上所影响的条数
	*/
	public function row_count()
	{
		return $this->getsyscount('row_count');
	}
	
	/**
	*	获取select的sql
	*/
	public function getsql($arr=array())
	{
		$where 	= $table = $order = $limit = $group = '';
		$fields	= '*';
		if(isset($arr['table']))$table=$arr['table'];
		if(isset($arr['where']))$where=$arr['where'];
		if(isset($arr['order']))$order=$arr['order'];
		if(isset($arr['limit']))$limit=$arr['limit'];
		if(isset($arr['group']))$group=$arr['group'];
		if(isset($arr['fields']))$fields=$arr['fields'];
		$where	= $this->getwhere($where);
		$table	= $this->gettable($table);
		$sql	= "SELECT $fields FROM $table";
		if($where!=''){
			//$where = $this->filterstr($where);
			$sql.=" WHERE $where";
		}
		if($order!='')$sql.=" ORDER BY $order";
		if($group!='')$sql.=" GROUP BY $group";
		if($limit!='')$sql.=" LIMIT $limit";
		return $sql;
	}
	
	//弃用过滤
	public function filterstr($str)
	{
		$str = strtolower($str);
		$file= explode(',','delete,drop,update,union,exec,insert,declare,master,truncate,create,alter,database');
		$res = array();
		foreach($file as $fid)$res[]='';
		$str = str_replace($file, $res, $str);
		return $str;
	}
	
	public function getone($table,$where,$fields='*',$order='')
	{
		$rows 	= $this->getrows($table,$where,$fields,$order,'1');
		$row	= false;
		if($this->count>0)$row=$rows[0];
		return $row;
	}
	
	public function getrows($table,$where,$fields='*',$order='', $limit='',$group='')
	{
		$sql	= $this->getsql(array(
			'table'	=> $table,
			'where'	=> $where,
			'fields'=> $fields,
			'order'	=> $order,
			'limit'	=> $limit,
			'group'	=> $group
		));
		return $this->getall($sql);
	}
	
	public function getall($sql)
	{
		$res=$this->query($sql);
		$arr=array();
		if($res){
			while($row=$this->fetch_array($res)){
				$arr[]	= $row;
				$this->count++;
			}
		}
		return $arr;
	}
	
	/**
		string table1 a left JOIN table2 b on b.uid=a.id
		array(table=>$table,join=>'left')
	*/
	public function gettable($arr)
	{
		if(is_array($arr)){
			$s = '';$oi=0;
			foreach($arr as $k=>$v){
				if($oi==0){
					$s=''.$v.' a';
				}else{
					if($k=='join')$s.=' '.$v.' JOIN';
					if($k=='table1')$s.=' '.$v.' b';
					if($k=='where')$s.=' ON '.$v.'';
					if($k=='where1')$s.=' AND '.$v.'';
				}					
				$oi++;
			}
			$arr = $s;
		}
		return $arr;
	}
	
	/**
		条件的
		$arrs = array(
			'id|eqi|a' => '0',
			'name|like' => '我',
			'id|notin' => '0,12',
			'enddt|rightlike' => '2015-10',
			'startdt|between' => '2015-10-01@@@2015-10-31',
			'price|notbetweeni' => '1@@@10',
			'sid > ?0 and <?1' => '0@@@2'
		);
	*/
	public function getwhere($where='')
	{
		$len  = func_num_args();
		$arr  = array();
		$sfh1 = '';
		for($i=0; $i<$len; $i++){
			$sfh	= func_get_arg($i);
			if(is_numeric($sfh)){
				$arr[] 	= "`id`='$sfh'";
			}else if($sfh=='AND' || $sfh=='OR' || $sfh=='and' || $sfh=='or'){
				$sfh1 	= $sfh;
			}else{
				$arr[]  = $this->_getwhere($sfh);
			}				
		}
		$joins = ') AND (';
		if($sfh1!='')$joins = ') '.$sfh1.' (';
		$where = join($joins, $arr);
		if($sfh1!='')$where = "($where)";
		
		return $where;
	}
	private function _getwhere($where='')
	{
		if($where=='')return '';
		if(is_numeric($where)){
			$where = "`id`='$where'";
		}else if(is_array($where)){
			$sarr = array();
			foreach($where as $fid=>$val){
				$qz 	= '';
				$farr 	= explode('|', $fid);
				$fid  	= $farr[0];
				$_fhs 	= "='?0'";
				if(isset($farr[1])){
					$_fh  = $farr[1];
					if(isset($this->exparray[$_fh]))$_fhs=$this->exparray[$_fh];
				}
				if(isset($farr[2]))$qz=''.$farr[2].'.';
				$vala = explode('@@@', $val);
				$val1 = $vala[0];$val2='';
				if(isset($vala[1]))$val2=$vala[1];
				$_bo1 = $this->contain($fid,'?0');
				if($_bo1)$_fhs = $fid;
				$_fhs = str_replace(array('?0','?1'), array($val1,$val2), $_fhs);
				$s = $_fhs;
				if(!$_bo1)$s = ''.$qz.'`'.$fid.'` '.$_fhs.'';
				$sarr[]=$s;
			}
			$where = join(' AND ', $sarr);
		}
		return $where;
	}

	/**
	*	以$kfied作为主键返回数组
	*/
	public function getarr($table, $where='', $fields='*', $kfied='id')
	{
		$sql	= $this->getsql(array(
			'table'	=> $table,
			'where'	=> $where,
			'fields'=> "`$kfied`,$fields"
		));
		$res = $this->query($sql);
		$arr = array();
		if($res){
			while($row=$this->fetch_array($res)){
				$arr[$row[$kfied]]	= $row;
				$this->count++;
			}
		}
		return $arr;
	}	

	/**
		读取全部同时将第一个字段作为主键(读取的数据存在数组里)
	*/	
	public function getkeyall($table,$fields,$where='')
	{
		$sql	= $this->getsql(array(
			'table'	=> $table,
			'where'	=> $where,
			'fields'=> $fields
		));
		$res=$this->query($sql);
		$arr=array();
		if($res){
			while(list($ka,$ab) = $this->fetch_array($res, 1)){
				$arr[$ka]=$ab;
				$this->count++;
			}
		}
		return $arr;
	}
	
	/**
		读取一条sql语句用规定字符连接起来
	*/	
	public function getjoinval($table,$fields,$where='',$join=',')
	{
		$sql	= $this->getsql(array(
			'table'	=> $table,
			'where'	=> $where,
			'fields'=> $fields
		));
		$res=$this->query($sql);
		$arr=array();
		if($res){
			while(list($kv) = $this->fetch_array($res, 1)){
				$arr[]=$kv;
				$this->count++;
			}
		}
		return join($join,$arr);
	}	
	
	/**
		读取某行某字段的
	*/	
	public function getmou($table,$fields,$where,$order='')
	{
		$sql	= $this->getsql(array(
			'table'	=> $table,
			'where'	=> $where,
			'fields'=> $fields,
			'order'	=> $order
		));
		$res=$this->query($sql);
		if($res){
			$row = $this->fetch_array($res, 1);
			if($row){
				$this->count = 1;
				return $row[0];
			}
		}
		return false;
	}
	
	/**
	*	开启事务
	*/
	public function routinestart()
	{
		$this->starttran();
	}
	
	/**
	*	提交/回滚事务
	*	$bo=null 自动 true 提交,false 回滚
	*/
	public function routineend($bo=null)
	{
		if(!is_bool($bo))$bo = $this->backsql();
		$this->endtran($bo);
		return $bo;
	}
	

	/**
	*	启用事务，没有事务
	*/	
	private function tranbegin($sql)
	{
		//if($this->errorbool)return false;
		if($this->conn == null)$this->connect();
		$this->iudcount++;
		if(!$this->tran){
			//$this->starttran();
			//$this->tran=true;
		}
		$rsa	= $this->query($sql);
		$this->iudarr[]=$rsa;
		if(!$rsa)$this->errorbool = true;
		return $rsa;
	}
	
	/**
		事务结束
	*/	
	private function tranend()
	{
		if($this->tran){
			//$this->endtran($this->backsql());
		}
		$this->tran=false;
	}
	
	/**
		判断插入更新删除sql语句是否有错
	*/	
	public function backsql()
	{
		$subt=true;
		foreach($this->iudarr as $tra){
			if(!$tra){
				$subt=false;
				break;
			}
		}	
		return $subt;	
	}

	public function insert($table,$name,$values,$sel=false)
	{
		$sql="insert into `$table` ($name) ";
		if(!$sel){
			$sql.="values($values)";
		}else{
			$sql.=$values;
		}
		return $this->tranbegin($sql);
	}

	public function update($table,$content,$where)
	{
		$where = $this->getwhere($where);
		$sql="update `$table` set $content where $where ";
		return $this->tranbegin($sql);
	}	

	public function delete($table,$where)
	{
		$where = $this->getwhere($where);
		$sql="delete from `$table` where $where ";
		return $this->tranbegin($sql);
	}
	
	/**
		记录添加修改
	*/	
	public function record($table,$array,$where='')
	{
		$addbool  	= true;
		if(!$this->isempt($where))$addbool=false;
		$cont		= '';
		if(is_array($array)){
			foreach($array as $key=>$val){
				$cont.=",`$key`=".$this->toaddval($val)."";
			}
			$cont	= substr($cont,1);
		}else{
			$cont	= $array;
		}
		if($addbool){
			$sql="insert into `$table` set $cont";
		}else{
			$where = $this->getwhere($where);
			$sql="update `$table` set $cont where $where";
		}
		return $this->tranbegin($sql);
	}
	
	/**
		返回总条数
	*/	
	public function rows($table,$where,$rowtype='count(1)'){
		return (int)$this->getmou($table,$rowtype,$where);
	}

	/**
		返回所有数据库的表
	*/	
	public function getalltable($base='')
	{	
		if($base=='')$base = $this->basename;
		$sql = "select `TABLE_NAME` from information_schema.`TABLES` where `TABLE_SCHEMA`='$base'";
		$arr = $this->getall($sql);
		$rows= array();
		foreach($arr as $k=>$rs)$rows[] = $rs['TABLE_NAME'];
		return $rows;
	}
	
	/**
		返回表所有字段
	*/	
	public function getallfields($table)
	{
		$finfo 	= $this->gettablefields($table);
		foreach ($finfo as $val) {
			$arr[] = $val['name'];
		}
		return $arr;
	}
	
	public function getfields($table)
	{
		$f	= $this->getallfields($table);
		foreach($f as $f1)$arr[$f1]='';
		return $arr;
	}
	
	public function gettablefields($table, $base='',$whe='')
	{
		if($base=='')$base = $this->db_base;
		$sql	= "select COLUMN_NAME as `name`,DATA_TYPE as `type`,COLUMN_COMMENT as `explain`,COLUMN_TYPE as `types`,`COLUMN_DEFAULT` as dev,`IS_NULLABLE` as isnull,`CHARACTER_MAXIMUM_LENGTH` as lens,`NUMERIC_PRECISION` as xslen1,`NUMERIC_SCALE` as xslen2 from information_schema.COLUMNS where `TABLE_NAME`='$table' and `TABLE_SCHEMA` ='$base' $whe order by `ORDINAL_POSITION`";
		return $this->getall($sql);
	}
	
	/**
		读取表结构
	*/
	public function gettablecolumn($table, $fields='')
	{
		$where 	= '';
		if($fields!='')$where = "and `COLUMN_NAME`='$fields'";
		$sql 	= "select COLUMN_NAME as `name`,DATA_TYPE as `type`,COLUMN_COMMENT as `explain`,COLUMN_TYPE as `types`,COLUMN_DEFAULT as 'defval' from information_schema.COLUMNS where `TABLE_NAME`='$table' and `TABLE_SCHEMA` ='$this->db_base' $where order by `ORDINAL_POSITION`";	
		$arr 	= $this->getall($sql);
		$rows 	= array();
		foreach($arr as $k=>$rs){
			$dev 	= 'NULL';
			if(!$this->isempt($rs['defval']))$dev=$rs['defval'];
			$str 	= "`".$rs['name']."` ".$rs['types']." DEFAULT ".$dev."";
			
			if(!$this->isempt($rs['explain']))$str.=" COMMENT '".$rs['explain']."'";
			$rows[] = $str;
		}
		return $rows;
	}
	
	public function showcreatetable($table)
	{
		$sql = "show create table `$table`";
		$res= $this->query($sql);
		list($ka,$nr) = $this->fetch_array($res, 1);
		return $nr;
	}

	/**
		判断变量是否为空
	*/	
	public function isempt($str)
	{
		return isempt($str);
	}
	
	public function contain($str,$a)
	{
		$bool=false;
		if(!$this->isempt($a) && !$this->isempt($str)){
			$ad=strpos($str,$a);
			if($ad>0||!is_bool($ad))$bool=true;
		}
		return $bool;
	}
	
	/**
		转换数据库可插入的对象
	*/	
	public function toaddval($str)
	{
		$adstr="'$str'";
		if($this->isempt($str)){
			$adstr='null';
		}else{
			if(substr($str,0,4)=='(&;)')$adstr=substr($str,4);
		}
		return $adstr;
	}
	
	/**
	*	替换特殊符合'
	*/
	public function tocovexec($str, $lx=0)
	{
		$str = str_replace('\'', '&#39;',$str);
		if($lx==1){
			$str = str_replace("\n", '',$str);
		}
		return $str;
	}
	
	/**
		创建随机编号
	*/		
	public function ranknum($table,$field='num',$n=6, $dx=0)
	{
		$arr	= array('0','1','2','3','4','5','6','7','8','9','a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z');
		$num	= '';
		for($i=1;$i<=$n;$i++)$num.=$arr[rand(0,count($arr)-1)];
		if($dx==1)$num	= strtoupper($num);//转换成大写
		$rsnum	= $this->getmou($table,$field,"`$field`='$num'");
		return ($rsnum)?$this->ranknum($table,$field,$n, $dx):$num;		
	}
	
	/**
		流水编号
	*/		
	public function sericnum($num, $table,$fields='sericnum', $ws=4, $whe='')
	{
		$dts 	= explode('-', date('Y-m-d'));
		$ymd 	= $dts[0].$dts[1].$dts[2];
		$ym 	= $dts[0].$dts[1];
		$num	= str_replace('Ymd', $ymd, $num);
		$num	= str_replace('Ym', $ym, $num);
		$num	= str_replace('Year', $dts[0], $num);
		$num	= str_replace('Day', $dts[2], $num);
		$num	= str_replace('Month', $dts[1], $num);
		$where 	= "`$fields` like '".$num."%' $whe";
		$max	= (int)$this->getmou($table, "max(cast(replace(`$fields`,'$num','') as decimal(10)))", $where);
		$max++;
		$wsnum	= ''.$max.'';
		$len 	= strlen($wsnum);
		$oix	= $ws - $len;
		for($i=1;$i<=$oix;$i++)$wsnum='0'.$wsnum;
		$num   .= $wsnum;
		return $num;
	}	
	
	/**
	*	获取所有顶级信息连接起来
	*	@param $table	表名
	*	@param $pfields	上级字段 $jfield 要连接的字段名 $afid = 值
	*/
	private $joinarr=array();
	public function getpval($table,$pfields,$jfield,$afid,$plit='/',$afield='id',$maxlen=8)
	{
		$this->joinarr	= array();
		$this->joinlen	= 0;
		$this->getpvala($table,$pfields,$jfield,$afid,$afield,$maxlen);
		return join($plit,array_reverse($this->joinarr));
	}
	private function getpvala($table,$pfields,$jfield,$afid,$afield,$maxlen)
	{
		if(count($this->joinarr)>=$maxlen)return;
		$rsa	= $this->getone($table,"`$afield`='$afid'","`id`,`$pfields`,`$jfield`");
		if($rsa){
			$this->joinarr[]=$rsa[$jfield];
			$pid	= $rsa[$pfields];
			if($pid!=$afid)if($this->rows($table,"`$afield`='$pid'")>0)$this->getpvala($table,$pfields,$jfield,$pid,$afield,$maxlen);
		}
	}
}
class DB{
	
	public static $tablename;
	
	public static function table($tab)
	{
		self::$tablename = ''.getconfig('.perfix.').$tab.'';
		return m($tab);
	}
	
	public static function where($f, $v)
	{
		
	}
}