<?php
class smsClassAction extends Action
{
	public function initAction()
	{
		$this->smsobj = c('xinhuapi');
	}
	
	public function gettotalAjax()
	{
		$barr = $this->smsobj->getdata('sms','smstotal');
		$barr['sms_iscb'] = $this->option->getval('sms_iscb','0');
		$barr['sms_cbnum'] = $this->option->getval('sms_cbnum','defnum');
		$barr['sms_apikey'] = $this->option->getval('sms_apikey');
		$barr['sms_txnum'] 	= $this->option->getval('sms_txnum');
		$barr['sms_mknum'] 	= $this->option->getval('sms_mknum');
		$barr['sms_qmnum'] 	= $this->option->getval('sms_qmnum');
		$barr['sms_dirtype'] 	= $this->option->getval('sms_dirtype');
		$barr['sms_yanzm'] 	= $this->option->getval('sms_yanzm');
		$barr['pingtarr'] 	= $this->option->getdata('syssmsplat');
		return $barr;
	}
	
	//保存设置
	public function cogsaveAjax()
	{
		$sms_dirtype	= $this->post('sms_dirtype');
		if(isempt($sms_dirtype) || $sms_dirtype=='null')$sms_dirtype = '';
		$this->option->setval('sms_iscb', $this->post('sms_iscb','0'));
		$this->option->setval('sms_cbnum', $this->post('sms_cbnum')); //催办编号
		$this->option->setval('sms_apikey', $this->post('sms_apikey'));
		$this->option->setval('sms_txnum', $this->post('sms_txnum')); //审批提醒模块编号
		$this->option->setval('sms_mknum', $this->post('sms_mknum'));  //要提醒的模块编号
		$this->option->setval('sms_qmnum', $this->post('sms_qmnum')); //签名
		$this->option->setval('sms_dirtype', $sms_dirtype);
		$this->option->setval('sms_yanzm', $this->post('sms_yanzm'));//验证码的短信编号
		$this->option->delete("`num` like 'alisms\_%'");
	}
	
	//测试
	public function testsendAjax()
	{
		$mobile 	= $this->get('mobile');
		$dirtype 	= $this->get('dirtype');
		$lxss 		= $this->option->getval('sms_dirtype');
		if($dirtype!=$lxss)return returnerror('请先保存后在测试');
		
		$parasm		= array(
			'modename' 	=> '模块测试',
			'sericnum' 	=> 'AB-'.date('Ymd').'-001',
			'applyname' => $this->adminname,
			'code' 		=> rand(100000,999999),
		);
		$bh 	= $this->option->getval('sms_cbnum', 'defurls');
		$barr 	= $this->smsobj->send($mobile, '' ,$bh, $parasm, ''.URL.'?d=we', false);
		return $barr;
	}
	
	//获取签名
	public function getqianAjax()
	{
		$type = $this->option->getval('sms_dirtype');
		if(!isempt($type)){
			$rows[] = array(
				'cont' => '非使用官网平台请到对应短信平台处理',
				'isgk' => 0
			);
			return array(
				'rows' => $rows,
				'dirtype' => $type
			);
		}
		$barr = $this->smsobj->getdata('sms','getqian');
		$rows = array();
		if($barr['success']){
			$rows = $barr['data'];
		}
		return array(
			'rows' => $rows
		);
	}
	
	//获取发送记录
	public function getrecordAjax()
	{
		$barr = $this->smsobj->getdata('sms','getrecord');
		$rows = array();
		if($barr['success']){
			$rows = $barr['data'];
		}
		return array(
			'rows' => $rows
		);
	}
	
	//删除短信记录
	public function delrecordAjax()
	{
		$barr = $this->smsobj->getdata('sms','delrecord', array(
			'id' => $this->post('id')
		));
		return $barr;
	}
	
	//保存签名
	public function saveqianAjax()
	{
		if(getconfig('systype')=='demo')return returnerror('demo演示上禁止操作');
		$cont = trim($this->post('cont'));
		$num  = $this->post('num');
		$explain  = trim(htmlspecialchars($this->post('explain')));
		$isgk = (int)$this->post('isgk',1);
		$barr = $this->smsobj->postdata('sms','saveqian', array(
			'cont' 	=> $cont,
			'num' 	=> $num,
			'isgk' 	=> $isgk,
			'explain' 	=> $explain,
		));
		return $barr;
	}
	
	//获取模版
	public function gettplAjax()
	{
		$type = $this->option->getval('sms_dirtype');
		if(!isempt($type)){
			$rows[] = array(
				'cont' => '非使用官网平台请到对应短信平台处理',
				'isgk' => 0
			);
			return array(
				'rows' => $rows,
				'dirtype' => $type
			);
		}
		$barr = $this->smsobj->getdata('sms','gettpl');
		$rows = array();
		if($barr['success']){
			$rows = $barr['data'];
		}
		return array(
			'rows' => $rows
		);
	}
	//保存模版
	public function savetplAjax()
	{
		if(getconfig('systype')=='demo')return returnerror('demo演示上禁止操作');
		$cont = $this->post('cont');
		$num  = $this->post('num');
		$isgk = 1;
		$barr = $this->smsobj->postdata('sms','savetpl', array(
			'cont' 	=> $cont,
			'num' 	=> $num,
			'isgk' 	=> 1,
		));
		return $barr;
	}
	
	//删除模版
	public function deltplAjax()
	{
		if(getconfig('systype')=='demo')return returnerror('demo演示上禁止操作');
		$num  = $this->post('num');
		$barr = $this->smsobj->getdata('sms','deltpl', array(
			'num' 	=> $num,
		));
		return $barr;
	}
	
	//刷新模版状态
	public function relaodtplAjax()
	{
		$num  = $this->post('num');
		$barr = $this->smsobj->getdata('sms','reloadtpl', array(
			'num' 	=> $num,
		));
		return $barr;
	}
	
	//刷新状态
	public function reloadsignAjax()
	{
		$barr = $this->smsobj->getdata('sms','reloadsign');
		return $barr;
	}
}