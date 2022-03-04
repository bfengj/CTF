<?php
class kaoqinClassAction extends runtAction
{
	/**
	*	定时任务发送昨天考勤异常的啊
	*/
	public function todoAction()
	{
		$dt 	= date('Y-m-d', time()-3600*20);//昨天
		$sql  	= "SELECT a.uid FROM `[Q]kqanay` a left join `[Q]userinfo` b on a.uid=b.id where a.dt='$dt' and b.iskq=1 and a.state<>'正常' and a.states is null and a.iswork=1 group by a.uid;";
		$rows 	= $this->db->getall($sql);
		$ids 	= '';
		foreach($rows as $k=>$rs){
			$ids .=','.$rs['uid'].'';
		}
		if($ids!=''){
			$flow 	= m('flow')->initflow('leavehr');
			$flow->push(substr($ids, 1),'考勤','昨天['.$dt.']的你考勤存在异常，此消息仅供参考！','考勤异常提醒');
		}
		return 'success';
	}
	
	public function anayAction()
	{
		$dt 	= date('Y-m-d', time()-3600*20);//昨天
		m('kaoqin')->kqanayalldt($dt);
		return 'success';
	}
	
	//每月分析上月
	public function lmanayAction()
	{
		$month = c('date')->adddate($this->rock->date, 'm', -1,'Y-m');
		m('kaoqin')->kqanayall($month);
		return 'success';
	}
	
	//分析工作日报统计
	public function dailyfxAction()
	{
		$dt 	= c('date')->adddate($this->rock->date, 'd', -1);
		$flow 	= m('flow')->initflow('daily');
		$flow->dailyanay(0, $dt);
		$flow->dailytodo($dt); 	//未写日报通知
		return 'success';
	}

	public function dayAction()
	{
		m('flow:leave')->autoaddleave(); //年假自动添加
		return 'success';
	}
	
	//定时从企业微信/钉钉上获取打卡记录，一般30分钟获取一次
	public function getdkAction()
	{
		$h 		= (int)date('H');
		if($h>=2 && $h<=6)return '凌晨2-6点暂停读取';
		
		$reimbo = m('reim');
		$uids 	= '';//全部
		$dt1	= '';
		$dt2	= '';
		$msg 	= 'success';
		if($reimbo->installwx(1)){
			$barr 	= m('weixinqy:daka')->getrecord($uids, $dt1, $dt2, 1);
			//加入异步
			if($uids=='' && $barr['errcode']==0 && $barr['maxpage']>1){
				for($i=1;$i<=$barr['maxpage'];$i++){
					if($i>1)$reimbo->asynurl('asynrun','wxdkjl', array(
						'dt1' 		=> $dt1,
						'dt2' 		=> $dt2,
						'page' 		=> $i
					));
				}
			}
			if($barr['errcode']!=0)$msg .= ',企业微信('.$barr['msg'].')';
		}
		
		//钉钉
		if($reimbo->installwx(2)){
			$barr = m('dingding:daka')->getrecord($uids, $dt1, $dt2);
			if($barr['errcode']!=0)$msg .= ',钉钉('.$barr['msg'].')';
		}
		return $msg;
	}
}