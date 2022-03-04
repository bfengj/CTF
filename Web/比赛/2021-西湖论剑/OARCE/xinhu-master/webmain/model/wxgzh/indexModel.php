<?php
class wxgzh_indexClassModel extends wxgzhModel
{
	public function initWxgzh()
	{
		$this->settable('wotpl');
	}
	
	/**
	*	获取系统上模版消息
	*/
	public function getxinhutpl()
	{
		$barr[] = array(
			'title' => '流程待办',
			'params'=> array(
				'name' 		=> '姓名',
				'applyname' => '申请人',
				'deptname'	=> '申请人部门',
				'sericnum'	=> '单号',
				'modename'	=> '模块名称',
				'summary'	=> '备注摘要'
			),
			'paramsdefault'=> array(
				'name' 		=> '赵子龙',
				'applyname' => '张飞',
				'deptname'	=> '技术部',
				'sericnum'	=> 'TEST-20190326',
				'modename'	=> '请假条',
				'summary'	=> '请假时间2019-03-26的9时到18共8小时'
			),
		);
		$xhtype = getconfig('xinhutype');
		if(!isempt($xhtype)){
			$obj = m($xhtype);
			if(method_exists($obj, 'getwxgzhtpl')){
				$narr = $obj->getwxgzhtpl();
				if(is_array($narr))foreach($narr as $k=>$rs1)$barr[]=$rs1;
			}
		}
		return $barr;
	}
	
	/**
	*	发模版消息
	*/
	private $tplidarr = array();
	public function sendtpl($openid, $tplid, $params=array(),$istest=false)
	{
		return $this->setbackarr('不能使用');
	}
	

	
	/**
	*	获取我消息模版列表
	*/
	public function gettpllist()
	{
		return $this->setbackarr('不能使用');
	}
}