<?php
class homeClassAction extends Action
{
	public function delhomeAjax()
	{
		$id = c('check')->onlynumber($this->post('id','0'));
		m('homeitems')->delete('id in('.$id.')');
		$this->backmsg();
	}
	
	//导入默认的官网首页项
	public function daordriwsAjax()
	{
		$rows = json_decode('[{"num":"kjrk","row":"0","name":"快捷入口","sort":"0"},{"num":"kjrko","row":"0","name":"快捷入口(大图标)","sort":"0"},{"num":"gong","row":"0","name":"通知公告","sort":"0"},{"num":"bianjian","row":"0","name":"便笺","sort":"1"},{"num":"tjlogin","row":"0","name":"登录统计","sort":"5","receid":"u1","recename":"管理员"},{"num":"kqtotal","row":"0","name":"今日出勤情况","sort":"2"},{"num":"news","row":"0","name":"新闻资讯","sort":"3"},{"num":"kqdk","row":"0","name":"考勤打卡","sort":"4"},{"num":"officic","row":"1","name":"公文查阅","sort":"3"},{"num":"gwwx","row":"0","receid":"u1","recename":"管理员","name":"微信办公","sort":"10"},{"num":"apply","row":"1","name":"我的申请","sort":"0"},{"num":"meet","row":"1","name":"今日会议","sort":"2"},{"num":"syslog","receid":"u1","recename":"管理员","row":"1","name":"系统日志","sort":"3"},{"num":"about","row":"1","receid":"u1","recename":"管理员","name":"关于信呼","sort":"10"}]', true);
		$db  = m('homeitems');
		foreach($rows as $k=>$rs){
			$num 	= $rs['num'];
			$where	= "`num`='$num'";
			if($db->rows($where)==0){
				$where='';
			}else{
				unset($rs['row']);
				unset($rs['sort']);
			}
			$db->record($rs, $where);
		}
	}
}