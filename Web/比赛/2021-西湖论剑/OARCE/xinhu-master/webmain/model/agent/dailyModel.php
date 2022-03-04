<?php
/**
	工作日报
*/
class agent_dailyClassModel extends agentModel
{
	
	public function gettotal()
	{
		$stotal	= $this->getwdtotal($this->adminid);
		$titles	= '';
		return array('stotal'=>$stotal,'titles'=> $titles);
	}
	
	public function getwdtotal($uid)
	{
		$ydid  	= m('log')->getread('daily', $uid);
		$whe1w 	= 'and '.m('admin')->getdownwheres('uid', $uid, 0);
		$where	= "id not in($ydid) $whe1w ";
		$stotal	= m('daily')->rows($where);
		return $stotal;
	}
	
	protected function agenttotals($uid)
	{
		$a = array(
			'under' => $this->getwdtotal($uid)
		);
		return $a;
	}
	protected function agentrows($rows, $rowd, $uid){
		$ydarr	= explode(',', m('log')->getread('daily', $uid));
		foreach($rowd as $k=>$rs){
			if(!in_array($rs['id'], $ydarr) && $rs['uid'] != $uid){
				$rows[$k]['statustext'] 	= '未读';
				$rows[$k]['statuscolor'] 	= '#ED5A5A';
			}else{
				$rows[$k]['ishui']			= 1;
			}
		}
		return $rows;
	}
	
}