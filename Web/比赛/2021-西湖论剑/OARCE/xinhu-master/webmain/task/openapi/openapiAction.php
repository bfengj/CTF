<?php
/**
*	对外开发接口文件
*	createname：信呼
*	homeurl：http://www.rockoa.com/
*	Copyright (c) 2016 rainrock (www.rockoa.com)
*	Date:2016-11-01
*	explain：返回200为正常
*	post需开启：always_populate_raw_post_data = On
*/
class openapiAction extends ActionNot
{
	private $openkey = '';
	public 	$postdata= '';
	
	//是否验证openkey
	protected 	$keycheck= true;
	
	public function initAction()
	{
		$this->display= false;
		$openkey 		= $this->post('openkey');
		$this->openkey 	= getconfig('openkey');
		if($this->keycheck && HOST != '127.0.0.1' && !contain(HOST,'192.168') && $this->openkey != ''){
			if($openkey != md5($this->openkey))$this->showreturn('', 'openkey not access', 201);
		}
		$this->getpostdata();
	}
	
	public function getpostdata()
	{
		if(isset($GLOBALS['HTTP_RAW_POST_DATA']))$this->postdata = $GLOBALS['HTTP_RAW_POST_DATA'];
		if($this->postdata=='')$this->postdata = trim(file_get_contents('php://input'));
	}
	
	public function getvals($nae, $dev='')
	{
		$sv = $this->rock->jm->base64decode($this->post($nae));
		if($this->isempt($sv))$sv=$dev;
		return $sv;
	}
	
	/**
	*	获取提交的数据
	*/
	public function getpostarr()
	{
		$str = $this->postdata;
		if(isempt($str))return false;
		$arr 	= json_decode($str, true);
		return $arr;
	}
	
	/**
	*	根据关键字获取用户
	*/
	public function getuserid($id, $sur=true)
	{
		if(isempt($id))return 0;
		$where = "`user`='$id'";
		$check = c('check');
		if($check->iscnmobile($id)){
			$where = "`mobile`='$id'";
		}elseif($check->isemail($id)){
			$where = "`email`='$id'";
		}elseif($check->isincn($id)){
			$where = "`name`='$id'";
		}elseif($check->isnumber($id)){
			$where = "`id`='$id'";
		}
		$urs = $this->db->getall("select `id`,`name` from `[Q]admin` where $where and `status`=1");
		if($this->db->count!=1)return 0;
		$urs = $urs[0];
		$uid = (int)$urs['id'];
		
		if($sur){
			$this->adminid 			= $uid;
			$this->adminname		= $urs['name'];
			$this->rock->adminid	= $uid; //用户Id
			$this->rock->adminname 	= $urs['name'];
		}
		return $uid;
	}
}