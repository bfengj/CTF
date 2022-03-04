<?php
/**
*	此文件是流程模块【userinfo.人员信息】对应接口文件。
*/ 
class mode_userinfoClassAction extends inputAction{
	
	public function companydata()
	{
		return m('company')->getselectdata(1);
	}

	protected function savebefore($table, $arr, $id, $addbo){
		$mobile = $arr['mobile'];
		$optlx	= $this->post('optlx');
		if(!c('check')->ismobile($mobile)){
			return '手机号格式有误';
		}
		//if(m('admin')->rows("`mobile`='$mobile' and `id`<>'$id'")>0){
		//	return '手机号['.$mobile.']已存在';
		//}
		
		$notsave = 'name,deptname,ranking,email';//不保存字段
		if($id==1)$notsave.=',quitdt';
		if($optlx=='my')$notsave.=',state,workdate,syenddt,positivedt,quitdt,companyid'; //个人编辑不保存
		
		
		return array(
			'notsave' => $notsave
		);
	}
	

	protected function saveafter($table, $arr, $id, $addbo){
		
		$this->userstateafter($table, $arr, $id);
	}
	
	
	public function storeafter($table, $rows)
	{
		return array(
			'statearr' => $this->flow->statearrs,
			'isadd'    => false
		);
	}
	
	//人员状态切换保存后处理
	public function userstateafter($table, $cans, $id)
	{
		$optlx		= $this->post('optlx');
		
		$quitdt 	= $cans['quitdt'];
		$state 		= array($cans,'state');
		$workdate 	= $cans['workdate'];
		$uarr		= array();
		
		if($optlx!='my'){
			$uarr['workdate'] 	= $workdate;
			$uarr['quitdt'] 	= $quitdt;
			if(!isempt($quitdt) || $state=='5')$uarr['status']='0';//离职状态
		}
		if(isset($cans['tel']))$uarr['tel'] = $cans['tel'];
		if(isset($cans['mobile']))$uarr['mobile'] = $cans['mobile'];
		if($id==1){
			unset($uarr['status']);
			unset($uarr['quitdt']);
		}
		
		if($uarr)m('admin')->update($uarr, $id);
	}
	
	//获取打开记录
	public function gethetongAjax()
	{
		$guid = (int)$this->get('guid','0');
		$ind  = (int)$this->get('ind','0');
		$bh   = 'userract';
		$zd   = 'uid';
		if($ind==4){
			$bh   = 'reward';
			$zd   = 'objectid';
		}
		if($ind==5){
			$bh   = 'hrpositive';
		}
		if($ind==6){
			$bh   = 'hrredund';
		}
		if($ind==7){
			$bh   = 'hrtrsalary';
		}
		if($ind==8){
			$bh   = 'hrtransfer';
			$zd   = 'tranuid';
		}
		$flow = m('flow')->initflow($bh);
		$cont = $flow->getrowstable('all','and {asqom}`'.$zd.'`='.$guid.'');
		return $cont;
	}
}	
			