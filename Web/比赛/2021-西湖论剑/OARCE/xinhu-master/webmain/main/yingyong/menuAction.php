<?php
class menuClassAction extends Action
{

	public function dataAjax()
	{
		$this->rows	= array();
		$where 	= $this->rock->covexec($this->post('where'));
		$this->getmenu(0, 1, $where);
		
		echo json_encode(array(
			'totalCount'=> 0,
			'rows'		=> $this->rows
		));
	}
	
	private function getmenu($pid, $oi, $wh='')
	{
		$db		= m('menu');
		$menu	= $db->getall("`pid`='$pid' $wh order by `sort`",'*');
		foreach($menu as $k=>$rs){
			$sid			= $rs['id'];
			$rs['level']	= $oi;
			$rs['stotal']	= $db->rows("`pid`='$sid'  $wh ");
			$this->rows[] = $rs;
			
			$this->getmenu($sid, $oi+1, $wh);
		}
	}
	
	
}