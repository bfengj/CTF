<?php
/**
*	便笺(2021-08-31)添加
*/
class agent_bianjianClassModel extends agentModel
{
	
	public function gettotal()
	{
		$stotal	= $this->getwdtotal($this->adminid);
		$titles	= '';
		return array('stotal'=>$stotal,'titles'=> $titles);
	}
	
	private function getwdtotal($uid)
	{
		$stotal	= m('bianjian')->rows('`uid`='.$uid.' and `state`=2');
		return $stotal;
	}
	
	
	protected function agenttotals($uid)
	{
		$a = array(
			'weiwc' => $this->getwdtotal($uid)
		);
		return $a;
	}
	
	protected function agentrows($rows, $rowd, $uid)
	{
		if($rowd)foreach($rowd as $k=>$rs){
			if($rs['stateval']>0){
				$ztrs = $this->flow->getststrsssa($rs['stateval']);
				if($ztrs){
					$rows[$k]['statustext'] 	= $ztrs['name'];
					$rows[$k]['statuscolor'] 	= $ztrs['color'];
				}
			}
		}
		return $rows;
	}	
}