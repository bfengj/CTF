<?php
class wxgzhClassAction extends Action
{

	public function setsaveAjax()
	{
		m('wxgzh:index')->clearalltoken();
		$pid = m('dingding:index')->optionpid;
		$this->option->setval('wxgzh_appid@'.$pid.'', $this->post('appid'));
		$this->option->setval('wxgzh_secret@'.$pid.'', $this->post('secret'));
		$this->option->setval('wxgzh_tplmess@'.$pid.'', $this->post('tplmess'));
		$this->backmsg();
	}
	
	public function getsetAjax()
	{
		$arr= array();
		$arr['appid']		= $this->option->getval('wxgzh_appid');
		$arr['secret']		= $this->option->getval('wxgzh_secret');
		$arr['tplmess']		= $this->option->getval('wxgzh_tplmess');
		echo json_encode($arr);
	}
	
	public function testsendAjax()
	{
		$lx  = (int)$this->get('lx');
		if($lx==0){
			$val = m('wxgzh:wxgzh')->getticket();
		}else{
			$val = 'ok';
		}
		if($val==''){
			showreturn('','测试失败');
		}else{
			showreturn('','测试成功');
		}
	}
	
	//获取模版消息列表
	public function getlisttplAjax()
	{
		return m('wxgzh:index')->gettpllist();
	}
	
	
	public function wotpl_before($table)
	{
		return array(
			'order' => 'status desc'
		);
	}
	
	public function wouser_before($table)
	{
		$where = '';
		$key = $this->post('key');
		if(!isempt($key)){
			$where=" and (`nickname` like '%$key%' or `province`='$key' or `city`='$key')";
		}
		return $where;
	}
	
	public function wotpl_after($table, $rows)
	{

		return array(
			'rows' => $rows
		);
	}
	
	public function wxxcyus_after($table, $rows)
	{
		$dm = getconfig('systype');
		foreach($rows as $k=>$rs){
			if(!isempt($rs['mobile'])){
				$rows[$k]['mobile'] = substr($rs['mobile'],0,3).'****'.substr($rs['mobile'],-4);
				if($dm=='demo')$rows[$k]['mobile']='已绑定';
			}
			
		}
		return array(
			'rows' => $rows
		);
	}
	public function gettpleditAjax()
	{
		$id = (int)$this->get('id','0');
		$rs = m('wotpl')->getone($id);
		$cont = $rs['content'];
		$farr = $this->rock->matcharr($cont);
		
		return array(
			'data' => $rs,
			'farr' => $farr,
			'marr' => m('wxgzh:index')->getxinhutpl()
		);
	}
	public function savetpleditAjax()
	{
		$id = (int)$this->post('id','0');
		m('wotpl')->update(array(
			'modename' => $this->post('modename'),
			'modeparams' => $this->post('modeparams')
		),$id);
	}
	
	public function testsendtplAjax()
	{
		$id 	= (int)$this->post('id','0');
		$openid = $this->post('openid');
		if(isempt($openid))return returnerror('没有输入openid');
		
		$urs  = m('wouser')->getone("`openid`='$openid'");
		if(!$urs)return returnerror('没有找到此授权的微信人');
		
		$barr = m('wxgzh:index')->sendtpl($openid, $id, array(), true);
		if($barr['errcode']!=0)return returnerror($barr['errcode'].'.'.$barr['msg']);
		
		return returnsuccess($barr['msg']);
	}
}