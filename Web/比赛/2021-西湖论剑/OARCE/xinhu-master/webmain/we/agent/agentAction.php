<?php 
class agentClassAction extends ActionNot{
	
	public function initAction()
	{
		$this->mweblogin(0, true);
	}
	
	public function defaultAction()
	{
		$num = $this->get('num');
		$reim= m('reim');
		$arr = $reim->getagent(0, "and `num`='$num'");
		if(!$arr)exit('应用['.$num.']不存在');
		$rs  = $arr[0];
		$this->title = $rs['name'];
		$yyurl 	= ''.P.'/we/ying/yingyong/'.$num.'.html';
		if(!file_exists($yyurl))$yyurl='';
		$yyurljs 	= ''.P.'/we/ying/yingyong/'.$num.'.js';
		if(!file_exists($yyurljs))$yyurljs='';
		$this->assign('arr', $rs);
		$this->assign('openfrom', $this->get('openfrom'));
		$this->assign('yyurl', $yyurl);
		$this->assign('yyurljs', $yyurljs);
		$gid 	= $rs['id'];
		$reim->setallyd('agent', $this->adminid, $gid);
	}
	
}