<?php
//公文查阅
class agent_officicClassModel extends agentModel
{
	
	protected $showuface	= false; //不显示人员头像
	
	
	
	public function gettotal()
	{
		$stotal	= $this->getwdtotal($this->adminid);
		$titles	= '';
		return array('stotal'=>$stotal,'titles'=> $titles);
	}
	
	//未读统计
	private function getwdtotal($uid)
	{
		$ydid 	= m('log')->getread('official', $uid);
		$where	= "id not in($ydid) and `status`=1";
		$meswh	= m('admin')->getjoinstr('receid', $uid, 0, 1);
		$where .= $meswh;
		$stotal	= m('official')->rows($where);
		return $stotal;
	}
	
	//菜单未读数组
	protected function agenttotals($uid)
	{
		$a = array(
			'mywcy' => $this->getwdtotal($uid)
		);
		return $a;
	}
	
}