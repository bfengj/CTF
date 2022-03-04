<?php 
/**
*	字符检查插件
*/

class checkChajian extends Chajian{
	
	/**
	*	是否为邮箱
	*/
	public function isemail($str)
	{
		if(isempt($str))return false;
		return filter_var($str, FILTER_VALIDATE_EMAIL);
	}
	
	/**
	*	是否为手机号
	*/
	public function ismobile($str)
	{
		if(isempt($str))return false;
		if(!is_numeric($str) || strlen($str)<5)return false;
		return true;
	}
	
	/**
	*	判断是否为国内手机号
	*/
	public function iscnmobile($str)
	{
		if(isempt($str))return false;
		if(!is_numeric($str) || strlen($str)!=11)return false;
		if(!preg_match("/1[3458769]{1}\d{9}$/", $str))return false;
		return true;
	}
	
	/**
	*	是否有中文
	*/
	public function isincn($str)
	{
		return preg_match("/[\x7f-\xff]/", $str);
	}
	
	//是否整个的英文a-z,0-9
	public function iszgen($str)
	{
		if(isempt($str))return false;
		if($this->isincn($str)){
			return false;
		}
		return true;
	}
	
	//返回字符串编码
	public function getencode($str)
	{
		$encode = mb_detect_encoding($str, array('ASCII','UTF-8','GB2312','GBK','BIG5'));
		$encode = strtolower($encode);
		return $encode;
	}
	
	/**
	*	是否为数字
	*/
	public function isnumber($str)
	{
		if(isempt($str))return false;
		return is_numeric($str);
	}
	
	/**
	*	字符是否包含数字
	*/
	public function isinnumber($str)
	{
		return preg_match("/[0-9]/", $str);
	}
	
	/**
	*	是否为日期
	*/
	public function isdate($str)
	{
		return preg_match("/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/", $str);
	}
	
	/**
	*	是否为月份
	*/
	public function ismonth($str)
	{
		return preg_match("/^([0-9]{4})-([0-9]{2})$/", $str);
	}
	
	/**
	*	过滤字母,只留数字
	*/
	public function onlynumber($str)
	{
		return preg_replace('/[a-zA-Z]/','', $str);
	}
	
	/**
	*	替换空格
	*/
	public function replacekg($str)
	{
		$str 	= preg_replace('/\s*/', '', $str);
		$qian	= array(" ","　","\t","\n","\r");
		return str_replace($qian, '', $str); 
	}
}