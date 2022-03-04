<?php
/**
*	运行方式：E:\php\php-5.4.14\php.exe E:\IIS\app\xinhu\task.php beifen
*	url:http://demo.rockoa.com/task.php?m=beifen|runt
*	url:http://127.0.0.1/app/xinhu/task.php?m=beifen|runt
*/
class beifenClassAction extends runtAction
{
	//每天cli备份数据为sql文件的
	public function defaultAction()
	{
		
		$alltabls 	= $this->db->getalltable();
		$nobeifne	= array(''.PREFIX.'log',''.PREFIX.'logintoken',''.PREFIX.'kqanay',''.PREFIX.'email_cont',''.PREFIX.'reads',''.PREFIX.'dailyfx',''.PREFIX.'todo',''.PREFIX.'city'); //不备份的表;
		$data 		= array();
		$strstr 	= "/*
	备份时间：".$this->now."		
*/

";
		foreach($alltabls as $tabs){
			if(in_array($tabs, $nobeifne))continue;	
			$strstr	.= "DROP TABLE IF EXISTS `$tabs`;\n";
			$sqla 	 = $this->db->getall('show create table `'.$tabs.'`');
			$strstr	.= "".$sqla[0]['Create Table'].";\n";
			
			$rows  	= $this->db->getall('select * from `'.$tabs.'`');
			foreach($rows as $k=>$rs){
				$vstr = '';
				foreach($rs as $k1=>$v1){
					if(!isempt($v1))$v1 = str_replace("\n",'\n', $v1);
					$v1 = ($v1==null) ? 'null' : "'$v1'";
					$vstr.=",$v1";
				}
				$strstr	.= "INSERT INTO `$tabs` VALUES(".substr($vstr,1).");\n";
			}
			
			$strstr	.= "\n";
		}

		$rnd  = str_shuffle('abcedfghijk').rand(1000,9999);
		$file = ''.DB_BASE.'_'.date('Y.m.d.H.i.s').'.sql';
		$filepath = ''.UPDIR.'/data/'.$file.'';
		$this->rock->createtxt($filepath, $strstr);
		
		//给管理员邮箱发邮件
		m('email')->sendmail(''.TITLE.'数据库备份',''.TITLE.'数据库备份'.$this->rock->now.'', 1 , array(), 1, array(
			'attachname'=> $file,
			'attachpath'=> $filepath,
		));
		
		@unlink($filepath);
		
		return 'success';
	}
	
}