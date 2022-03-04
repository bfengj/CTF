<?php 
class flowClassAction extends ActionNot{
	
	public function initAction()
	{
		$this->mweblogin(0, true);
	}
	
	public function applyAction()
	{
		$this->title = '申请流程';
		
		$viewobj 	= m('view');
		$applyarr	= m('mode')->getmoderows($this->adminid,'and islu=1');
		$modearr	= array();
		$otyle		= '';
		$oi 		= 0;
		foreach($applyarr as $k=>$rs){
			if(!$viewobj->isadd($rs, $this->adminid))continue;
			if($otyle!=$rs['type']){
				$oi = 0;
			}
			$otyle = $rs['type'];
			$oi++;
			$modearr[$rs['type']][]=array('modenum'=>$rs['num'],'url'=>'?a=lum&m=input&d=flow&num='.$rs['num'].'&show=we','name'=>$rs['name'],'title'=>''.$oi.'.'.$rs['name']);
		}
		$this->assign('modearr', $modearr);
	}
	
	//单据查看
	public function viewAction()
	{
		$this->title = '单据查看';
		
		$viewobj 	= m('view');
		$applyarr	= m('mode')->getmoderows($this->adminid,'');
		$modearr	= array();
		$otyle		= '';
		$oi 		= 0;
		foreach($applyarr as $k=>$rs){
			if($rs['isscl']==0)continue;
			if($otyle!=$rs['type']){
				$oi = 0;
			}
			$otyle = $rs['type'];
			$oi++;
			$modearr[$rs['type']][]=array('modenum'=>$rs['num'],'url'=>'?m=ying&d=we&mnum='.$rs['num'].'&show=we','name'=>$rs['name'],'title'=>''.$oi.'.'.$rs['name']);
		}
		$this->assign('modearr', $modearr);
		$this->displayfile = 'webmain/we/flow/tpl_flow_apply.html';
	}
}