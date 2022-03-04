<?php 
class minute5ClassAction extends runtAction
{
	
	public function runAction()
	{
		$time 	= time();
		$time1 	= $time;
		$time2 	= $time1+5*60;
		$time3 	= $time1-5*60;
		$this->startdt	= date('Y-m-d H:i:s', $time1);	
		$this->enddt	= date('Y-m-d H:i:s', $time2);
		$this->enddtss	= date('Y-m-d H:i:s', $time3);
		$this->scheduletodo();
	
		m('flowbill')->autocheck(); //自动审批作废
		m('reim')->chatpushtowx($this->enddtss); //REIM消息
		return 'success';
	}
	
	private function scheduletodo()
	{
		$to = m('mode')->rows("`num`='schedule' and `status`=1");
		if($to==1)m('schedule')->gettododata();//日程
		
		$to = m('mode')->rows("`num`='remind' and `status`=1");
		if($to==1)m('remind')->todorun();//单据
		
		$to = m('mode')->rows("`num`='meet' and `status`=1");
		if($to==1)m('flow')->initflow('meet')->meettodo(); //会议提醒的
	}
	
}