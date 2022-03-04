<?php
class hrClassModel extends Model
{
	/**
	*	每天运行
	*/
	public function hrrun()
	{
		$tiemss = strtotime($this->rock->date);
		$this->updatepositive($tiemss);
		$this->updatehrredund($tiemss);
		$this->updatehrtransfer($tiemss);
	}
	
	
	//转正的
	private function updatepositive($timess)
	{
		$db		= m('hrpositive');
		$rows 	= $db->getall("`status`=1 and `isover`=0",'`id`,`uid`,`entrydt`,`syenddt`,`positivedt`');
		foreach($rows as $k=>$rs){
			if(strtotime($rs['positivedt']) <= $timess){
				$bo = m('userinfo')->update(array(
					'state' 	=> '1',
					'syenddt' 	=> $rs['syenddt'],
					'positivedt' => $rs['positivedt'],
				), $rs['uid']);
				if($bo)$db->update("`isover`=1", $rs['id']);
			}
		}
	}
	
	//离职的
	private function updatehrredund($timess)
	{
		$db		= m('hrredund');
		$rows 	= $db->getall("`status`=1 and `isover`=0",'`id`,`uid`,`quitdt`');
		$timess	= $timess - 24*3600;//昨天
		foreach($rows as $k=>$rs){
			if(strtotime($rs['quitdt']) <= $timess){
				$bo = m('userinfo')->update(array(
					'state' => '5',
					'quitdt' => $rs['quitdt']
				), $rs['uid']);
				m('admin')->update(array(
					'quitdt' => $rs['quitdt']
				), $rs['uid']);
				if($bo)$db->update("`isover`=1", $rs['id']);
			}
		}
	}
	
	//调动的
	private function updatehrtransfer($timess)
	{
		$db		= m('hrtransfer');
		$mdb	= m('admin');
		$rows 	= $db->getall("`status`=1 and `isover`=0",'`id`,`uid`,`effectivedt`,`newdeptid`,`tranuid`,`newdeptname`,`newranking`');
		$uids	= '0';
		foreach($rows as $k=>$rs){
			if(strtotime($rs['effectivedt']) <= $timess){
				$uid = $rs['tranuid'];
				$bo = $mdb->update(array(
					'deptid' 	=> $rs['newdeptid'],
					'deptname' 	=> $rs['newdeptname'],
					'ranking' 	=> $rs['newranking'],
				), $uid);
				if($bo){
					$db->update("`isover`=1", $rs['id']);
					$uids.=','.$uid;
				}	
			}
		}
		if($uids != '0')$mdb->updateinfo("and a.`id` in($uids)");
	}
}