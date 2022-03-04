<?php
/**
*	此文件是流程模块【hrsalarm.薪资模版】对应控制器接口文件。
*/ 
class mode_hrsalarmClassAction extends inputAction{
	
	

	
	//复制
	public function copyfuzAjax()
	{
		$sid = (int)$this->get('sid');
		$db  = m('hrsalarm');
		$dbs  = m('hrsalars');
		$arow= $db->getone($sid);
		if(!$arow)return;
		unset($arow['id']);
		$arow['optdt'] = $this->rock->now;
		$arow['optid'] = $this->adminid;
		$arow['optname'] = $this->adminname;

		$mid = $db->insert($arow);
		
		$nrows = $dbs->getall('mid='.$sid.'');
		foreach($nrows as $k=>$rs){
			$rs['mid'] = $mid;
			unset($rs['id']);
			$dbs->insert($rs);
		}
	}
	
	public function daoruxzmbAjax()
	{
		$db  = m('hrsalarm');
		$dbs  = m('hrsalars');
		//echo json_encode($dbs->getall('1=1'),256);
		
		if($db->rows('1=1')>0)return '已有记录不能导入，要导入请先清空';
		
		$strm = '[{"id":"1","title":null,"receid":"d1","recename":"信呼开发团队","explain":null,"optid":"1","optname":"管理员","optdt":"2018-09-26 22:37:21","atype":"基本工资","startdt":"2013-12","enddt":"2050-12","sort":"0","status":"1"},{"id":"2","title":"绩效考核","receid":"d1","recename":"信呼开发团队","explain":null,"optid":"1","optname":"管理员","optdt":"2018-09-20 13:14:02","atype":"绩效","startdt":"2013-12","enddt":"2050-12","sort":"10","status":"1"},{"id":"3","title":"人事考勤","receid":"d1","recename":"信呼开发团队","explain":null,"optid":"1","optname":"管理员","optdt":"2018-09-29 20:23:15","atype":"考勤","startdt":"2013-01","enddt":"2050-12","sort":"20","status":"1"},{"id":"4","title":null,"receid":"d1","recename":"信呼开发团队","explain":"起征点3500","optid":"1","optname":"管理员","optdt":"2018-09-30 09:23:26","atype":"个人所得税","startdt":"2013-01","enddt":"2018-09","sort":"70","status":"1"},{"id":"5","title":null,"receid":"d1","recename":"信呼开发团队","explain":null,"optid":"1","optname":"管理员","optdt":"2018-09-23 20:06:27","atype":"社保公积金","startdt":"2013-01","enddt":"2050-12","sort":"50","status":"1"},{"id":"15","title":"人事考勤","receid":"d1","recename":"信呼开发团队","explain":null,"optid":"1","optname":"管理员","optdt":"2018-09-29 20:25:05","atype":"考勤","startdt":"2013-01","enddt":"2050-12","sort":"20","status":"0"},{"id":"13","title":null,"receid":"d1","recename":"信呼开发团队","explain":null,"optid":"1","optname":"管理员","optdt":"2018-09-26 22:35:43","atype":"补贴","startdt":"2013-01","enddt":"2050-12","sort":"30","status":"1"},{"id":"14","title":null,"receid":"d1","recename":"信呼开发团队","explain":null,"optid":"1","optname":"管理员","optdt":"2018-09-27 12:08:52","atype":"其他","startdt":"2013-01","enddt":"2050-12","sort":"40","status":"1"},{"id":"16","title":null,"receid":"d1","recename":"信呼开发团队","explain":"起征点5000，2018年10月起","optid":"1","optname":"管理员","optdt":"2018-09-30 09:24:37","atype":"个人所得税","startdt":"2018-10","enddt":"2050-12","sort":"70","status":"1"}]';
		
		$strs = '[{"id":"1","mid":"1","sort":"0","name":"基本工资","fields":"base","gongsi":null,"type":"1","beizhu":"厦门市最低工资","devzhi":"1700"},{"id":"58","mid":"14","sort":"0","name":"其它增加","fields":"otherzj","gongsi":null,"type":"1","beizhu":null,"devzhi":"0"},{"id":"3","mid":"2","sort":"0","name":"绩效基数","fields":"jxjs","gongsi":null,"type":"0","beizhu":null,"devzhi":"2000"},{"id":"4","mid":"2","sort":"1","name":"绩效系数","fields":"jxxs","gongsi":null,"type":"0","beizhu":null,"devzhi":"1.5"},{"id":"5","mid":"2","sort":"2","name":"绩效分数","fields":"jxdf","gongsi":null,"type":"0","beizhu":null,"devzhi":"0"},{"id":"6","mid":"2","sort":"3","name":"绩效收入","fields":"jtpost","gongsi":"{jxjs}*{jxxs}*{jxdf}*0.01","type":"1","beizhu":null,"devzhi":"0"},{"id":"15","mid":"3","sort":"0","name":"应出勤天数","fields":"ysbtime","gongsi":null,"type":"0","beizhu":null,"devzhi":"0"},{"id":"16","mid":"3","sort":"1","name":"出勤天数","fields":"zsbtime","gongsi":null,"type":"0","beizhu":null,"devzhi":"0"},{"id":"76","mid":"16","sort":"0","name":"个税起征点","fields":"taxbase","gongsi":null,"type":"0","beizhu":null,"devzhi":"5000"},{"id":"75","mid":"16","sort":"1","name":"个人所得税","fields":"taxes","gongsi":"faxgerenn({mones}-{taxbase})","type":"4","beizhu":null,"devzhi":"0"},{"id":"51","mid":"13","sort":"0","name":"交通补贴","fields":"travelbt","gongsi":null,"type":"1","beizhu":null,"devzhi":"0"},{"id":"17","mid":"4","sort":"1","name":"个人所得税","fields":"taxes","gongsi":"faxgeren({mones}-{taxbase})","type":"4","beizhu":null,"devzhi":"0"},{"id":"18","mid":"4","sort":"0","name":"个税起征点","fields":"taxbase","gongsi":null,"type":"0","beizhu":null,"devzhi":"3500"},{"id":"19","mid":"3","sort":"2","name":"奖励","fields":"reward","gongsi":null,"type":"1","beizhu":null,"devzhi":"0"},{"id":"20","mid":"3","sort":"3","name":"处罚","fields":"punish","gongsi":null,"type":"2","beizhu":null,"devzhi":"0"},{"id":"21","mid":"3","sort":"4","name":"加班(小时)","fields":"jiaban","gongsi":null,"type":"0","beizhu":null,"devzhi":"0"},{"id":"22","mid":"3","sort":"5","name":"加班补贴","fields":"jiabans","gongsi":null,"type":"1","beizhu":null,"devzhi":"0"},{"id":"23","mid":"5","sort":"0","name":"个人社保","fields":"socials","gongsi":null,"type":"2","beizhu":null,"devzhi":"0"},{"id":"24","mid":"5","sort":"1","name":"单位社保缴费","fields":"socialsunit","gongsi":null,"type":"0","beizhu":null,"devzhi":"0"},{"id":"25","mid":"5","sort":"2","name":"公积金个人","fields":"gonggeren","gongsi":null,"type":"2","beizhu":null,"devzhi":"0"},{"id":"26","mid":"5","sort":"3","name":"公积金单位","fields":"gongunit","gongsi":null,"type":"0","beizhu":null,"devzhi":"0"},{"id":"63","mid":"15","sort":"4","name":"早退(次)","fields":"zaotui","gongsi":null,"type":"0","beizhu":null,"devzhi":"0"},{"id":"61","mid":"15","sort":"2","name":"迟到(次)","fields":"cidao","gongsi":null,"type":"0","beizhu":null,"devzhi":"0"},{"id":"62","mid":"15","sort":"3","name":"迟到处罚","fields":"cidaos","gongsi":null,"type":"2","beizhu":null,"devzhi":"0"},{"id":"60","mid":"14","sort":"2","name":"计件收入","fields":"jiansr","gongsi":null,"type":"1","beizhu":null,"devzhi":"0"},{"id":"59","mid":"14","sort":"1","name":"其它减少","fields":"otherjs","gongsi":null,"type":"2","beizhu":null,"devzhi":"0"},{"id":"57","mid":"13","sort":"6","name":"其他补贴","fields":"otherbt","gongsi":null,"type":"1","beizhu":null,"devzhi":"0"},{"id":"56","mid":"13","sort":"5","name":"电脑补贴","fields":"dnbt","gongsi":null,"type":"1","beizhu":null,"devzhi":"0"},{"id":"55","mid":"13","sort":"4","name":"高温津贴","fields":"hotbt","gongsi":null,"type":"1","beizhu":null,"devzhi":"0"},{"id":"54","mid":"13","sort":"3","name":"餐补贴","fields":"foodbt","gongsi":null,"type":"1","beizhu":null,"devzhi":"0"},{"id":"52","mid":"13","sort":"1","name":"通信补贴","fields":"telbt","gongsi":null,"type":"1","beizhu":null,"devzhi":"0"},{"id":"53","mid":"13","sort":"2","name":"技能津贴","fields":"skilljt","gongsi":null,"type":"1","beizhu":null,"devzhi":"0"},{"id":"64","mid":"15","sort":"5","name":"早退处罚","fields":"zaotuis","gongsi":null,"type":"2","beizhu":null,"devzhi":"0"},{"id":"65","mid":"15","sort":"6","name":"未打卡(次)","fields":"weidk","gongsi":null,"type":"0","beizhu":null,"devzhi":"0"},{"id":"66","mid":"15","sort":"7","name":"未打卡处罚","fields":"weidks","gongsi":null,"type":"2","beizhu":null,"devzhi":"0"},{"id":"67","mid":"15","sort":"8","name":"请假(小时)","fields":"leave","gongsi":null,"type":"0","beizhu":null,"devzhi":"0"},{"id":"68","mid":"15","sort":"9","name":"请假减少","fields":"leaves","gongsi":null,"type":"2","beizhu":null,"devzhi":"0"},{"id":"69","mid":"15","sort":"0","name":"应出勤天数","fields":"ysbtime","gongsi":null,"type":"0","beizhu":null,"devzhi":"0"},{"id":"70","mid":"15","sort":"1","name":"出勤天数","fields":"zsbtime","gongsi":null,"type":"0","beizhu":null,"devzhi":"0"},{"id":"71","mid":"15","sort":"10","name":"奖励","fields":"reward","gongsi":null,"type":"1","beizhu":null,"devzhi":"0"},{"id":"72","mid":"15","sort":"11","name":"处罚","fields":"punish","gongsi":null,"type":"2","beizhu":null,"devzhi":"0"},{"id":"73","mid":"15","sort":"12","name":"加班(小时)","fields":"jiaban","gongsi":null,"type":"0","beizhu":null,"devzhi":"0"},{"id":"74","mid":"15","sort":"13","name":"加班补贴","fields":"jiabans","gongsi":null,"type":"1","beizhu":null,"devzhi":"0"}]';
		
		$dtpt  = m('dept')->getmou('name', 1);
		if(!isempt($dtpt))$strm = str_replace('信呼开发团队', $dtpt, $strm);
		$mrows = json_decode($strm, true);
		$oldarr= array();
		foreach($mrows as $k=>$rs){
			$rs['optid'] 	= $this->adminid;
			$rs['optname'] 	= $this->adminname;
			$rs['optdt'] 	= $this->rock->now;
			$oldid = $rs['id']+0;
			unset($rs['id']);
			$oldarr[$oldid] = $db->insert($rs);
		}
		
		//子表
		$mrows = json_decode($strs, true);
		foreach($mrows as $k=>$rs){
			$rs['mid'] = $oldarr[$rs['mid']];
			unset($rs['id']);
			$dbs->insert($rs);
		}
		
		return 'ok';
	}
}	
			