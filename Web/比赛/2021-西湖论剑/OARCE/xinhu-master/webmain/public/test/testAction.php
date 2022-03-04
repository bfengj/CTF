<?php 
class testClassAction extends ActionNot{
	
	//测试地址http://127.0.0.1/app/xinhu/?m=test&d=public
	public function defaultAction()
	{
		$this->display = false;
		
		echo m('kaoqin')->getworktime(1);
		//print_r(m('weixinqy:daka')->getrecord(1));
		//return m('wxgzh:index')->sendtpl('images/logo.png');
		//echo  m('weixinqy:index')->getagentid('adds,办公助手e,OA2主页,办公助手');
		//return m('weixin:media')->downmedia('3MhSL1jKzVjnDOI3GHBU-Zf5xXJuVs48ciMMWiP0xv4Afp9ijTTalyhYTpNG2o8mEr-O5tGcNGeRBp-6_N5Y_CQ');
	}
	
}