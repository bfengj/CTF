<?php 
class yingClassAction extends ActionNot{
	
	public function initAction()
	{
		if($this->adminid==0){
			exit('登录已经失效了');
		}
	}
	
	public function defaultAction()
	{
		
	}
	
	public function dakaAction()
	{
		$this->title = '考勤打卡';
		
		$kq 	= m('kaoqin');
		$dt 	= $this->rock->date;
		if($this->rock->get('atype')=='daka')$kq->kqanay($this->adminid, $dt);
		$sbarr 	= $kq->getsbanay($this->adminid, $dt);
		$dkarr 	= $kq->getdkjl($this->adminid, $dt);
		
		$kqarr	= array(
			'sbarr' => $sbarr,
			'dkarr' => $dkarr,
		);
		$this->assign('kqarr', $kqarr);
	}
}