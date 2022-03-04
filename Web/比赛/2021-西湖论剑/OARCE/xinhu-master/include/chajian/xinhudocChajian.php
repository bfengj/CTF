<?php 
/**
*	连接信呼文件管理平台
*/

class xinhudocChajian extends Chajian{
	
	
	public function geturlstr($mod, $act, $can=array())
	{
		$url 	= getconfig('xinhudoc_platurl');
		$key 	= getconfig('xinhudoc_openkey');
		if(substr($url,-1)!='/')$url.='/';
		$url   .= 'openapi/'.$mod.'/'.$act.'?openkey='.md5($key).'';
		foreach($can as $k=>$v)$url.='&'.$k.'='.$v.'';
		return $url;
	}
	
	/**
	*	返回内容处理
	*/
	public function returnresult($result)
	{
		if($result && contain($result,'success')){
			$stat = strpos($result,'{');
			if(!$stat)$stat=0;
			$barr = json_decode(substr($result, $stat), true);
			if(isset($barr['success']))return $barr;
		}
		return returnerror('returnerr:'.$result.'');
	}
}