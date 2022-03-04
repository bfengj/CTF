<?php
//回执确认
class agent_receiptClassModel extends agentModel
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
		$stotal	= m('flow:receipt')->getweitotal($uid);
		return $stotal;
	}
	
	//菜单未读数组
	protected function agenttotals($uid)
	{
		$a = array(
			'mywei' => $this->getwdtotal($uid)
		);
		return $a;
	}
	
	protected function agentrows($rows, $rowd, $uid){
		if($rows){
			foreach($rowd as $k=>$rs){
				$rows[$k]['ishui']		= $rs['ishui'];
				$rows[$k]['modenum']	= $rs['modenumshow'];
				$rows[$k]['id']			= $rs['mid'];
			}
		}
		return $rows;
	}
}