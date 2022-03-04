<?php
class hrClassAction extends runtAction
{
	/*
	* 员工合同到期提醒/人事每天调动运行
	*/
	public function httodoAction()
	{
		m('hr')->hrrun(); //人事每天调动/离职等运行
	
		//员工合同到期提醒
		$flow 	= m('flow')->initflow('userract');
		$flow->updatestate();
		$dtobj  = c('date');
		$dt 	= $this->rock->date;
		$dt30 	= $dtobj->adddate($dt,'d',35);
		$rows 	= m('userract')->getall("state=1 and `enddt`<='$dt30'",'id,enddt,httype,name,uname');
		$str 	= '';
		foreach($rows as $k=>$rs){
			$jg = $dtobj->datediff('d', $dt, $rs['enddt']);
			if($jg==30 || $jg<=7){
				$str.='人员['.$rs['uname'].']的【'.$rs['httype'].'.'.$rs['name'].'】将在'.$jg.'天后的'.$rs['enddt'].'到期;';
			}
		}
		if($str != ''){
			$this->todoarr	= array(
				'modenum' 	=> 'userract',
				'agentname' => '员工合同',
				'title' 	=> '员工合同到期提醒',
				'cont' 		=> $str,
			);
		}
		
		//生日提醒
		m('flow')->initflow('userinfo')->birthdaytodo();
		
		//自动从考核项目中添加
		m('flow')->initflow('hrcheck')->hrkaohemrun();
		
		//个人资料完善提醒
		
		return 'success';
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
		if($uids != '0')$mdb->updateinfo("and `id` in($uids)");
	}
}