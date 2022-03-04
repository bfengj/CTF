<?php
class modeClassAction extends ActionNot
{
	public function init123Action()
	{
		$aid 	= (int)$this->get('adminid');
		$token 	= $this->get('token');
		$aid 	= m('login')->autologin($aid, $token);
		if($aid==0){
			$this->mweblogin(1);
		}
		$this->getlogin(1);
	}
	
	public function initAction()
	{
		$this->mweblogin(0);
	}

	public function defaultAction()
	{
		$fn	 	= $this->get('fn');
		$title 	= $this->rock->jm->base64decode($this->get('title'));
		if($title!='')$this->title = $title;
		$path 	= P.'/task/mode/html/'.$fn.'.html';
		if(!file_exists($path))exit('not found '.$fn.'');
		$this->displayfile = $path;
	}
	
	//移动端页面详情
	public function xAction()
	{
		$num = $this->get('modenum');
		if($num=='')$num=$this->get('num');
		
		$mid 	 = (int)$this->get('mid');
		if($num=='' || $mid==0)exit('无效请求');
		
		
		$arr 	 = m('flow')->getdatalog($num, $mid, 1);
		$pagetitle 		= $arr['title'];
		$this->title 	= $arr['title'];
		if($pagetitle=='')$pagetitle = $arr['modename'];
		$this->smartydata['arr'] = $arr;
		
		$spagepath 	= P.'/flow/page/viewpage_'.$num.'_1.html';
		if(!file_exists($spagepath))$spagepath 	= P.'/flow/page/viewpage_'.$num.'.html';
		if(!file_exists($spagepath)){
			$spagepath = '';
		}
		$this->smartydata['spagepath']		= $spagepath;
		$this->smartydata['pagetitle']		= $pagetitle;
		
		$inputjspath	= P.'/flow/input/inputjs/mode_'.$num.'.js';
		if(!file_exists($inputjspath)){
			$inputjspath = '';
		}
		$this->smartydata['inputjspath']	= $inputjspath;
		$this->assign('inputobj', c('input'));
		
		$jswxsdk = '0';
		if($this->rock->web=='wxbro' && !isempt($this->option->getval('weixinqy_corpid')))$jswxsdk='1';
		$this->assign('jswxsdk', $jswxsdk);
	}
	
	//pc端页面详情
	public function pAction()
	{
		$num = $this->get('modenum');
		if($num=='')$num=$this->get('num');
		
		$mid 	 = (int)$this->get('mid');
		if($num=='' || $mid==0)exit('无效请求');
		$stype 			= $this->get('stype');
		
		$arr 	 		= m('flow')->getdatalog($num, $mid, 0);
		$pagetitle 		= $arr['title'];
		$this->title 	= $arr['title'];
		if($pagetitle=='')$pagetitle = $arr['modename'];
		$this->smartydata['arr'] = $arr;
		
		$spagepath 	= P.'/flow/page/viewpage_'.$num.'_0.html';
		if(!file_exists($spagepath))$spagepath 	= P.'/flow/page/viewpage_'.$num.'.html';
		if(!file_exists($spagepath)){
			$spagepath = '';
		}
		$this->smartydata['spagepath']		= $spagepath;
		$this->smartydata['pagetitle']		= $pagetitle;
		$this->assign('stype', $stype);
		if($stype=='word'){
			m('file')->fileheader($arr['modename'].'.doc');
		}
		$this->smartydata['bordercolor']	= getconfig('bcolorxiang', '#888888');
		
		$inputjspath	= P.'/flow/input/inputjs/mode_'.$num.'.js';
		if(!file_exists($inputjspath)){
			$inputjspath = '';
		}
		$this->smartydata['inputjspath']	= $inputjspath;
		$this->smartydata['xiangwidth']		= $this->option->getval('xiangwidth', 700);
		$issetprint = file_exists(''.P.'/flow/page/view_'.$num.'_2.html');
		if(COMPANYNUM && !$issetprint)$issetprint = file_exists(''.P.'/flow/page/view_'.$num.'_'.COMPANYNUM.'_2.html');
		$this->smartydata['issetprint']		= $issetprint;
		$this->assign('inputobj', c('input'));
	}
	
	//下载
	public function downAction()
	{
		$this->display = false;
		$id  = (int)$this->jm->gettoken('id');
		m('file')->show($id);
	}
	
	
	//打印的
	public function tAction()
	{
		$num = $this->get('modenum');
		if($num=='')$num=$this->get('num');
		
		$mid 	 = (int)$this->get('mid');
		if($num=='' || $mid==0)exit('无效请求');
		$stype 			= $this->get('stype');
		
		$path 			 = ''.P.'/flow/page/view_'.$num.'_2.html';
		if(!file_exists($path))return '没有设置打印模版';
		
		$arr 	 		= m('flow')->initflow($num, $mid)->getdatalog(0, 2);
		$pagetitle 		= $arr['title'];
		$this->title 	= $arr['title'];
		if($pagetitle=='')$pagetitle = $arr['modename'];
		$this->smartydata['arr'] = $arr;
		
		$spagepath 	= P.'/flow/page/viewpage_'.$num.'_2.html';
		if(!file_exists($spagepath))$spagepath 	= P.'/flow/page/viewpage_'.$num.'.html';
		if(!file_exists($spagepath)){
			$spagepath = '';
		}
		$this->smartydata['spagepath']		= $spagepath;
		$this->smartydata['pagetitle']		= $pagetitle;
		$this->assign('stype', $stype);
		if($stype=='word'){
			m('file')->fileheader($arr['modename'].'.doc');
		}
		
		
		$this->smartydata['xiangwidth']		= $this->option->getval('xiangwidth', 700);
		
	}
	
	
	
	
	
	//导出页面
	public function eAction()
	{
		$num	= $this->get('num');
		$event	= $this->get('event');
		$stype	= $this->get('stype');
		
		$arr 	= m('flow')->printexecl($num, $event);
		$this->title = $arr['moders']['name'];
		$urlstr	= '?a=e&num='.$num.'&event='.$event.'';
		$this->assign('arr', $arr);
		$this->assign('urlstr', $urlstr);
		$this->assign('stype', $stype);
		if($stype!=''){
			$filename = $this->title;
			header('Content-type:application/vnd.ms-excel');
			header('Content-disposition:attachment;filename='.iconv("utf-8","gb2312",$filename).'.'.$stype.'');
		}
	}
	
	//邮件上打开详情
	public function aAction()
	{
		$num = $this->get('num');
		$mid = $this->get('mid');
		$act = 'p';
		if($this->rock->ismobile())$act='x';
		$url = 'task.php?a='.$act.'&num='.$num.'&mid='.$mid.'';
		$this->rock->location($url);
	}
}