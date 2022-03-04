<?php
class agent_flowClassModel extends agentModel
{
	public function initModel()
	{
		$this->settable('flow_bill');
	}
	
	public function gettotal()
	{
		$stotal	= $this->getwdtotal($this->adminid);
		$titles	= '';
		return array('stotal'=>$stotal,'titles'=> $titles);
	}
	
	private function getwdtotal($uid)
	{
		$stotal	= m('flowbill')->applymywgt($uid);;
		return $stotal;
	}
	
	protected function agentdata($uid, $lx)
	{
		if($lx=='moreapply'){
			$viewobj 	= m('view');
			$applyarr	= m('mode')->getmoderows($uid,'and islu=1');
			$modearr	= array();
			$otyle		= '';
			$oi 		= 0;
			foreach($applyarr as $k=>$rs){
				if(!$viewobj->isadd($rs, $this->adminid))continue;
				if($otyle!=$rs['type']){
					$oi = 0;
					$modearr[] = array(
						'showtype' 	=> 'line',
						'title'		=> $rs['type']
					);
				}
				$otyle = $rs['type'];
				$oi++;
				$modearr[]=array('modenum'=>$rs['num'],'type'=>'applybill','name'=>$rs['name'],'title'=>''.$oi.'.'.$rs['name']);
			}
			$arr['rows']= $modearr;
			return $arr;
		}
		
		$arr 	= m('flowbill')->getrecord($uid, $this->agentnum.'_'.$lx, $this->page, $this->limit);
		$modearr= array();
		if($this->loadci==1){
			$modearr = m('mode')->getmodemyarr($uid);
		}
		return array(
			'rows' => $arr,
			'modearr' => $modearr,
		);
	}
	
	protected function agenttotals($uid)
	{
		return array(
			'mywtg' => $this->getwdtotal($uid)
		);
	}
}