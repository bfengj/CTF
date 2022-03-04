<?php
class agent_daibanClassModel extends agentModel
{
	public function initModel()
	{
		$this->settable('flow_bill');
	}
	
	public function gettotal()
	{
		$stotal	= $this->getdbtotal($this->adminid);
		$titles	= '';
		return array('stotal'=>$stotal,'titles'=> $titles);
	}
	
	private function getdbtotal($uid)
	{
		$stotal	= m('flowbill')->daibanshu($uid);
		return $stotal;
	}
	
	protected function agenttotals($uid)
	{
		return array(
			'daiban' => $this->getdbtotal($uid)
		);
	}
	
	protected function agentdata($uid, $lx)
	{
		$atype  = $this->agentnum.'_'.$lx;
		$dbss	= m('flowbill');
		$arr 	= $dbss->getrecord($uid, $atype, $this->page, $this->limit, 0);
		if($atype=='daiban_daib'){
			$rows = $arr['rows'];
			if($rows){
				$arr['rows'] = $rows;
			}
		}
		$sdar = $this->db->getall('select `modeid` from `[Q]flow_bill` where '.$dbss->nowwhere.' group by `modeid`');
		$idss = '0';
		foreach($sdar as $k=>$rs)$idss.=','.$rs['modeid'].'';
		
		$arr['modearr'] = m('mode')->getmodearr('and `id` in('.$idss.')');

		return $arr;
	}
}