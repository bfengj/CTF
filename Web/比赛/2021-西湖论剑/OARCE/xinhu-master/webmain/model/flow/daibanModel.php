<?php
class flow_daibanClassModel extends flowModel
{
	
	/**
	*	每天流程待办提醒
	*/
	public function tododay()
	{
		$arr  = array();
		$rows = $this->getrows('`status` not in(1,2) and `isturn`=1 and `isdel`=0 and `nowcheckid` is not null','`nowcheckid`,`modename`');
		foreach($rows as $k=>$rs){
			$dista = explode(',', $rs['nowcheckid']);
			foreach($dista as $distid){
				if(!isset($arr[$distid]))$arr[$distid] = array();
				if(!isset($arr[$distid][$rs['modename']]))$arr[$distid][$rs['modename']] = 0;
				$arr[$distid][$rs['modename']]++;
			}
		}
		foreach($arr as $uid => $strarr){
			$this->flowweixinarr['url'] = $this->getwxurl();//设置微信提醒的详情链接
			$str = '';
			$k 	 = 0;
			foreach($strarr as $mod=>$sl){
				$k++;
				if($k>1)$str.="\n";
				$str.="".$k.".$mod(".$sl."条);";
			}
			if($str != '')$this->push($uid, '', $str, '流程待办处理');
		}
	}
}