<?php
/**
*	桌面项目
*/
class homeitemsClassModel extends Model
{
	public function getmyshow()
	{
		$str 	= m('admin')->getjoinstr('receid', $this->adminid, 1);
		$rows 	= $this->getall('`status`=1 and ('.$str.')','`num`,`row`,`name`,`sort`','`row`,`sort`');
		return $rows;
	}
	
	/**
	*	桌面项目数据读取
	*/
	public function getitemsdata($nums='')
	{
		if(isempt($nums))$nums = 'apply,gong,meet';
		$numarr	= explode(',', $nums);
		$barr 	= array();
		$xhtype = getconfig('xinhutype');
		$obj 	= false;
		if(!isempt($xhtype))$obj = m($xhtype);
		foreach($numarr as $num){
			$act = 'get_'.$num.'_arr';
			if(method_exists($this, $act)){
				$barr[''.$num.'arr'] = $this->$act();
			}else if($obj){
				if(method_exists($obj, $act))
					$barr[''.$num.'arr'] = $obj->$act();
			}
		}
		$barr['total'] = $this->gettotals($this->adminid);//统计角标
		return $barr;
	}
	
	//快捷入口红数字的统计的(根据菜单编号来的)
	public function gettotals($uid)
	{
		$optdt 	= $this->rock->now;
		$arr 	= array();
		$bidb	= m('flowbill');
		
		$todo			= m('todo')->rows("uid='$uid' and `status`=0 and `tododt`<='$optdt'");
		$arr['todo']	= $todo;
		
		$obj 	= false;
		$cbarr	= array();
		$xhtype = getconfig('xinhutype');
		if(!isempt($xhtype))$obj = m($xhtype);
		if($obj){
			if(method_exists($obj, 'menutotals')){
				$cbarr = $obj->menutotals();
				if(is_array($cbarr))foreach($cbarr as $k=>$v)$arr[$k]=$v;
			}
		}
		
		$nubar = array();
		$nuarr = m('menu')->getall('`status`=1 and num is not null');
		foreach($nuarr as $k=>$rs)$nubar[] = $rs['num'];
		
		if(!isset($arr['daiban']))$arr['daiban']		= $bidb->daibanshu($uid);
		if(!isset($arr['applywtg']))$arr['applywtg']	= $bidb->applymywgt($uid);
		if(!isset($arr['daiturn']))$arr['daiturn'] 		= $bidb->daiturntotal($uid);
		if(!isset($arr['danerror']))$arr['danerror']	= $bidb->errortotal();
		if(in_array('workwwc', $nubar) && !isset($arr['workwwc']))$arr['workwwc']		= m('work')->getwwctotals($uid);
		if(in_array('email', $nubar) && !isset($arr['email']))$arr['email']			= m('emailm')->wdtotal($uid);
		if(in_array('flowtodo', $nubar) && !isset($arr['flowtodo']))$arr['flowtodo']	= m('flowtodo')->getwdtotals($uid);
		if(in_array('cropt', $nubar) && !isset($arr['cropt']))$arr['cropt']			= m('goods')->getdaishu(); //出入库操作数
		if(in_array('receiptmy', $nubar) && !isset($arr['receiptmy']))$arr['receiptmy']	= m('flow:receipt')->getweitotal($uid);
		if(in_array('myhong', $nubar) && !isset($arr['myhong']))$arr['myhong'] 		= m('official')->rows('`uid`='.$uid.' and `type`=0 and `status`=1 and `thid`=0');//统计未套红的
		if(in_array('officidus', $nubar) && !isset($arr['officidus']))$arr['officidus'] = m('officidu')->rows('`status` in(0,3) and `isturn`=1 and '.$this->rock->dbinstr('runrenid',$uid).'');

		return $arr;
	}
	
	
	//我的申请
	public function get_apply_arr()
	{
		return m('flowbill')->homelistshow();
	}
	
	//通知公告读取，5是读取的条数
	public function get_gong_arr()
	{
		return m('flow')->initflow('gong')->getflowrows($this->adminid,'my', 5);
	}
	
	//会议
	public function get_meet_arr()
	{
		$to = m('mode')->rows("`num`='meet' and `status`=1");
		if($to==0)return array();
		return m('meet')->getmeethome($this->rock->date, $this->adminid);
	}
	
	//系统日志
	public function get_syslog_arr()
	{
		return m('log')->getrows('1=1','type,remark,optdt,level','id desc limit 5');
	}
	
	//考勤打卡的
	public function get_kqdk_arr()
	{
		$to = m('mode')->rows("`num`='kqdkjl' and `status`=1");
		if($to==0)return array('sbarr'=>array(),'dkarr'=>array());
		$kq 	= m('kaoqin');
		$dt 	= $this->rock->date;
		if($this->rock->get('atype')=='daka')$kq->kqanay($this->adminid, $dt);
		$sbarr 	= $kq->getsbanay($this->adminid, $dt);
		$dkarr 	= $kq->getdkjl($this->adminid, $dt);
		
		return array(
			'sbarr' => $sbarr,
			'dkarr' => $dkarr,
		);
	}
	
	//读取我查阅公文,5是读取条数
	public function get_officic_arr()
	{
		$to = m('mode')->rows("`num`='officic' and `status`=1");
		if($to==0)return array();
		return m('flow')->initflow('officic')->getflowrows($this->adminid,'my',5);
	}
	
	//读取新闻的
	public function get_news_arr()
	{
		$typearr = m('option')->getdata('newstype',"and `value` like 'home%'");
		$rows 	 = m('flow')->initflow('news')->getflowrows($this->adminid,'my');
		
		return array(
			'typearr' => $typearr,
			'rows' 	  => $rows,
		);
	}
	
	//考勤情况统计
	public function get_kqtotal_arr()
	{
		$to = m('mode')->rows("`num`='kqdkjl' and `status`=1");
		if($to==0)return array();
		return m('flow')->initflow('kqdkjl')->homekqtotal();
	}
	
	//登录统计
	public function get_tjlogin_arr()
	{
		return m('login')->homejtLogin();
	}
}