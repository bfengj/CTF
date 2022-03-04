<?php
class agent_emailmClassModel extends agentModel
{
	public function gettotal()
	{
		$stotal = $this->getwdtotal($this->adminid);
		return array('stotal'=>$stotal,'titles'=>'');
	}
	
	public function getwdtotal($uid)
	{

		$stotal	= m('emailm')->wdtotal($uid);
		return $stotal;
	}
	
	protected function agenttotals($uid)
	{
		$a = array(
			'sjx' => $this->getwdtotal($uid)
		);
		return $a;
	}
	
	//数据替换
	protected function agentrows($rows, $rowd, $uid){
		if($rows){
			foreach($rowd as $k=>$rs){
				$rows[$k]['ishui'] = $rs['zt'];
			}
		}
		return $rows;
	}
}