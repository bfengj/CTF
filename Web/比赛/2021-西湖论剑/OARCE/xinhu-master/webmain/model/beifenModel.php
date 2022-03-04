<?php
class beifenClassModel extends Model
{
	/**
	*	备份到upload/data下
	*/
	public function start()
	{
		$alltabls 	= $this->db->getalltable();
		$nobeifne	= array(''.PREFIX.'log',''.PREFIX.'logintoken',''.PREFIX.'kqanay',''.PREFIX.'email_cont',''.PREFIX.'dailyfx',''.PREFIX.'todo',''.PREFIX.'city',''.PREFIX.'kqjcmd'); //不备份的表;
		
		$beidir 	= ''.UPDIR.'/data/'.date('Y.m.d.H.i.s').'';
		foreach($alltabls as $tabs){
			if(in_array($tabs, $nobeifne))continue;
			$rows  	= $this->db->getall('select * from `'.$tabs.'`');
			$fields	= $this->db->gettablefields($tabs);
			$data 		 = array();
			$data[$tabs] = array(
				'fields' 	=> $fields,
				'data'		=> $rows
			);
			$file	= ''.$tabs.'_'.count($fields).'_'.count($rows).'.json';
			$str  	= json_encode($data);
			$bo 	= $this->rock->createtxt(''.$beidir.'/'.$file.'', $str);
			if(!$bo){echo '无权限写入：'.$beidir.'';break;return false;}
		}
		return true;
	}
	
	/**
	*	获取备份的数据
	*/
	public function getbfdata($file, $path='')
	{
		$str 	= array();
		if($path=='')$path	= ''.ROOT_PATH.'/'.UPDIR.'/data/'.$file.'';
		if(file_exists($path)){
			$cont = file_get_contents($path);
			if(substr($cont, 0, 2) != '{"'){
				$cont = $this->rock->jm->mcrypt_decrypt($cont);
			}
			$str  = json_decode($cont, true);
		}
		return $str;
	}
	
	
	public function updatefabric($cont, $ylx=0)
	{
		$bos 	= $this->updatefabricfile($cont, $ylx);
		if(!$bos)return 'dberr:'.$this->db->lasterror();
		return 'ok';
	}
	
	public function updatefabricfile($cont='', $ylx=0)
	{
		if($cont=='')return false;
		$data = json_decode($cont, true);
		foreach($data as $tabe=>$da){
			$table 	= str_replace('xinhu_', PREFIX, $tabe);
			if($ylx==1)$table = PREFIX.$tabe;
			$fields = $da['fields'];
			$nowfiel= $this->getfieldsa($table);   
			$str 	= '';
			$sql	= '';
			if(!$nowfiel){
				$str  = '`id` int(11) NOT NULL AUTO_INCREMENT';
				foreach($fields as $k=>$frs){
					$fname = $frs['name'];
					$nstr  = $this->getfielstr($frs);
					if($fname!='id')$str.=','.$nstr.'';
				}
				$str .=',PRIMARY KEY (`id`)';
				$sql  = "CREATE TABLE `$table`($str)ENGINE=".getconfig('db_engine','MyISAM')." DEFAULT CHARSET=utf8";
				if(isset($da['createsql'])){
					$sql = $da['createsql'];
					$sql = str_replace('`xinhu_','`'.PREFIX.'', $sql);
				}
			}else{
				foreach($fields as $k=>$frs){
					$fname = $frs['name'];
					if($fname=='id')continue;
					$nstr  = $this->getfielstr($frs);
					if(!isset($nowfiel[$fname])){
						$str.=',add '.$nstr.'';
					}else{
						$ofrs = $nowfiel[$fname]; //系统上字段类型
						$ostr = $this->getfielstr($ofrs);
						$lxarr= array('text','mediumtext','bigint');
						
						//如果自己字段长度大于官网就不更新
						if($frs['type']==$ofrs['type']  && !isempt($ofrs['lens']) && $ofrs['lens']>$frs['lens']){
							
						}else if($nstr != $ostr && !in_array($ofrs['type'], $lxarr) ){
							$str.=',MODIFY '.$nstr.'';
						}
					}
				}
				if($str!=''){
					$str = substr($str, 1);
					$sql = "alter table `$table` $str";
				}
			}
			if($sql!=''){
				$bo  = $this->db->query($sql);
				if($bo)$this->rock->debugs($sql, 'upgmysql');
				if(!$bo)return false;
			}
		}
		return true;
	}
	private function getfieldsa($table)
	{
		$nowfiel= $this->db->gettablefields($table);
		$a 		= array();
		foreach($nowfiel as $k=>$rs){
			$a[$rs['name']] = $rs;
		}
		return $a;
	}
	private function getfielstr($rs)
	{
		$str 	= '`'.$rs['name'].'` '.$rs['types'].'';
		$dev 	= $rs['dev'];
		$isnull = $rs['isnull'];
		if($isnull=='NO')$str.=' NOT NULL';
		if(is_null($dev)){
			if($isnull != 'NO')$str.=' DEFAULT NULL';
		}else{
			$str.=" DEFAULT '$dev'";
		}
		if(!isempt($rs['explain']))$str.=" COMMENT '".$rs['explain']."'";
		return $str;
	}
}