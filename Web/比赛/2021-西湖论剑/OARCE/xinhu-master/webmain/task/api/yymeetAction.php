<?php 
class yymeetClassAction extends apiAction
{
	public function saveAction()
	{
		$data['title'] 		= $this->post('title');
		$data['hyname']		= $this->post('hyname');
		$data['startdt']	= $this->post('startdt');
		$data['enddt']		= $this->post('enddt');
		$data['joinid']		= $this->post('joinid');
		$data['joinname']	= $this->post('joinname');
		
		$data['explain']	= $this->post('explain');
		$data['optdt']		= $this->now;
		$data['optid']		= $this->adminid;
		$data['optname']	= $this->adminname;
		$data['status']		= 1;
		$data['type']		= 0;
		$data['applydt']	= $this->date;
		
		foreach($data as $k=>$v){
			if($k=='explain')break;
			if($this->isempt($v))$this->showreturn('', '['.$k.']不能为空', 201);
		}
		$mid = m('meet')->insert($data);
		m('flow')->submit('meet', $mid, '预定');
		$this->showreturn('');
	}
}