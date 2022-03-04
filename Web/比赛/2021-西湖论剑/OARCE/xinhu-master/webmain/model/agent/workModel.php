<?php
//任务的应用
class agent_workClassModel extends agentModel
{
	
	public function gettotal()
	{
		$stotal	= $this->getwdtotal($this->adminid);
		$titles	= '';
		return array('stotal'=>$stotal,'titles'=> $titles);
	}
	
	private function getwdtotal($uid)
	{
		$to	= m('work')->getwwctotals($uid);
		return $to;
	}
	
	protected function agenttotals($uid){
		return array(
			'myrw' => $this->getwdtotal($uid)
		);
	}
	
	protected function agentrows($rows, $rowd, $uid){
		$rows = $this->agentrows_status($rows, $rowd);
		$projectarr = false;
		//读取我的项目
		if($this->loadci==1){
			$tos = m('flow_set')->rows("`num`='project' and `status`=1");
			if($tos>0){
				$where1 = $this->rock->dbinstr('`fuzeid`', $uid);
				$where2 = m('admin')->getjoinstr('`runuserid`', $uid, 1, 1);
				$xmrows = m('project')->getall("`status` <>5 and ($where1 or $where2)",'*');
				if($xmrows){
					$projectarr = array();
					foreach($xmrows as $k1=>$rs1){
						$projectarr[] = array(
							'id' => $rs1['id'],
							'name' => '['.$rs1['type'].']'.$rs1['title'].'('.$rs1['progress'].'%)',
						);
					}
				}
			}
		}
		return array(
			'rows' => $rows,
			'projectarr' => $projectarr
		);
	}
	
	protected function agentrows11($rows, $rowd, $uid)
	{
		$statea = $this->flow->statearr;
		foreach($rowd as $k=>$rs){
			$state 	 = $rs['state'];
			if($state==3){
				$rows[$k]['ishui']		=1;
			}
			$ztarr	 = $statea[$state];
			$rows[$k]['statustext']		= $ztarr[0];
			$rows[$k]['statuscolor']	= $ztarr[1];
		}
		return $rows;
	}
}