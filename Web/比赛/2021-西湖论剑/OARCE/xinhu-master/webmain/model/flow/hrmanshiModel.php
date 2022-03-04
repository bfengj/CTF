<?php
/**
*	人事模块.面试安排
*/
class flow_hrmanshiClassModel extends flowModel
{

	public function flowrsreplace($rs, $lx=0)
	{
		$statearr	= array('待面试','录用','不适合');
		$rs['state'] = arrvalue($statearr, $rs['state']);
		return $rs;
	}
	
	//面试处理，不时候
	protected function flowcheckbefore($zt=0, $ufied=null, $sm='')
	{
		$num	= $this->nowcourse['num'];
		if($zt==2 && $num=='msque'){
			$this->update('state=2', $this->id);
		}
	}
}