<?php
/**
	通知公告的
*/
class agent_gongClassModel extends agentModel
{
	
	public function gettotal()
	{
		$stotal	= $this->getwdtotal($this->adminid);
		$titles	= '';
		return array('stotal'=>$stotal,'titles'=> $titles);
	}
	
	private function getwdtotal($uid)
	{
		$ydid 	= m('log')->getread('infor', $uid);
		$where	= "id not in($ydid) and `status`=1";
		$meswh	= m('admin')->getjoinstr('receid', $uid);
		$where .= $meswh;
		
		$where.= " and (`zstart` is null or `zstart`<='{$this->rock->date}')";
		$where.= " and (`zsend` is null or `zsend`>='{$this->rock->date}')";
		
		$where .= m('admin')->getcompanywhere(1);
		$stotal	= m('infor')->rows($where);
		return $stotal;
	}
	
	
	protected function agenttotals($uid)
	{
		$a = array(
			'weidu' => $this->getwdtotal($uid)
		);
		return $a;
	}
	protected function agentrows($rows, $rowd, $uid){
		$typearr = array();
		if($this->loadci==1)$typearr = $this->flow->flowwesearchdata(1);
		return array(
			'rows' =>$rows,
			'typearr' =>$typearr,
		);
	}
}