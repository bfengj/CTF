<?php
/**
*	外部调用单据详情页
*/
class openxiangClassAction extends openapiAction
{
	/**
	*	详情
	*/
	public function dataAction()
	{
		$list['hetong'] = 'custract,customer'; //配置可读取模块
		$num 	 = $this->get('num');
		$xcytype = $this->get('xcytype');
		$mid 	 = (int)$this->get('mid','0');
		if(isempt($num) || !$xcytype || $mid==0)return returnerror('num isempt');
		$kears 	 = arrvalue($list, $xcytype);
		if(!$kears)return returnerror('无效模块1');
		if(!in_array($num,explode(',', $kears)))return returnerror('无效模块2');
		
		$flow 	= m('flow')->initflow($num,$mid, false);
		$barr['modename'] = $flow->modename;
		$barr['btndata']  = array();
		$barr['xiangdata']= array();
		if(method_exists($flow,'openxiang')){
			$lbarr	= $flow->openxiang();
			if(is_array($lbarr))foreach($lbarr as $k=>$v)$barr[$k]=$v;
		}
		return returnsuccess($barr);
	}
	
	/**
	*	操作菜单
	*/
	public function optmenuAction()
	{
		$num 	 = $this->get('num');
		$xcytype = $this->get('xcytype');
		$mid 	 = (int)$this->get('mid','0');
		$menuid  = (int)$this->get('menuid','0');
		$sm 	 = $this->jm->base64decode($this->get('sm'));
		if(isempt($num) || !$xcytype || $mid==0)return returnerror('num isempt');
		$flow 	 = m('flow')->initflow($num,$mid, false);
		$msg 	 = $flow->optmenu($menuid,1,$sm);
		if($msg!='ok')return returnerror($msg);
		return returnsuccess($msg);
	}
	
	/**
	*	下载文件
	*/
	public function downurlAction()
	{
		$id 	= (int)$this->get('id','0');
		$openid = $this->get('openid');
		if(isempt($openid))return;
		if(m('wxxcyus')->rows("`openid`='$openid'")==0)return;
		m('file')->show($id, true);
	}
	
	/**
	*	返回文件详情
	*/
	public function fileinfoAction()
	{
		$id 	= (int)$this->get('id','0');
		$openid = $this->get('openid');
		if(isempt($openid))return returnerror('无效openid1');
		if(m('wxxcyus')->rows("`openid`='$openid'")==0)return returnerror('无效openid');
		$frs 	= m('file')->getone($id,'id,filename,filesize,fileext,filepath,filepathout');
		return returnsuccess($frs);
	}
}