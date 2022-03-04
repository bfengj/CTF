<?php 
class yygongClassAction extends apiAction
{
	public function saveAction()
	{
		$data['title'] 		= $this->post('title');
		$data['content']	= $this->post('content');
		$data['typename']	= $this->post('typename');
		$data['receid']		= $this->post('receid');
		$data['recename']	= $this->post('recename');
		$data['url']		= $this->post('url');
		$data['optdt']		= $this->now;
		$data['optid']		= $this->adminid;
		$data['optname']	= $this->adminname;
		
		foreach($data as $k=>$v){
			if($k=='receid')break;
			if($this->isempt($v))$this->showreturn('', '['.$k.']不能为空', 201);
		}
		
		$mid = m('infor')->insert($data);
		m('flow')->submit('gong', $mid, '发布');
		$this->showreturn('');
	}
}