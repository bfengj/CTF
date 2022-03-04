<?php
class meetClassModel extends Model
{

	
	public function getmeet($dts, $uid=0)
	{
		if($uid==0)$uid = $this->adminid;
		$arr 	= array();
		$hyarr 	= array('<font color=green>正常</font>','<font color=blue>会议中</font>','<font color=#ff6600>结束</font>','<font color=#888888>取消</font>');
		$time 	= time();
		$narr 	= $this->getall("`type`=0 and `status`=1 and `startdt` like '$dts%' order by `startdt` asc");
		$adb  	= m('admin');
		foreach($narr as $k=>$rs){
			$zt 	= $rs['state'];
			$nzt 	= $zt;
			$stime 	= strtotime($rs['startdt']);
			$etime 	= strtotime($rs['enddt']);
			if($zt < 2){
				if($etime<$time){
					$nzt = 2;
				}else if($stime>$time){
					$nzt = 0;
				}else{
					$nzt = 1;
				}
			}
			if($adb->containjoin($rs['joinid'], $uid)){
				$state 	= $hyarr[$nzt];
				$dt 	= ''.str_replace($dts.' ', '', $rs['startdt']).'至'.str_replace($dts.' ', '', $rs['enddt']).'';
				$arr[]= array(
					'type' 		=> '会议',
					'ssid' 		=> $rs['id'],
					'hyname' 	=> $rs['hyname'],
					'title' 	=> '['.$rs['hyname'].']'.$rs['title'].'',
					'titles' 	=> $rs['title'],
					'joinname' 	=> $rs['joinname'],
					'optname' 	=> $rs['optname'],
					'state' 	=> $state,
					'status' 	=> $nzt,
					'startdt' 	=> $dt,
					'starttime' => $stime,
					'endtime' 	=> $etime,
				);	
			}
			if($zt != $nzt)$this->update('state='.$nzt.'', $rs['id']);
		}
		return $arr;
	}
	
	public function getmeethome($dts, $uid=0)
	{
		$rows = $this->getmeet($dts, $uid);
		$barr= array();
		foreach($rows as $k=>$rs){
			$title 	= '【'.$rs['startdt'].'】'.$rs['title'].'('.$rs['state'].')';
			if($rs['status']>=2)$title 	= '<font color="#888888">'.$title.'</font>';
			if($rs['status']==1)$title 	= '<b>'.$title.'</b>';
			$barr[] = array(
				'id' 	=> $rs['ssid'],
				'title' => $title,
			);
		}
		return $barr;
	}
	
	/**
	*	判断会议室是否重复申请了
	*/
	public function isapplymsg($startdt, $enddt, $hyname, $id=0)
	{
		$msg 		= '';
		$rows 		= $this->getall("id <> '$id' and `type`=0 and `state` in(0,1)");
		$gdrow		= m('flow')->initflow('meet')->createmeet(0, substr($startdt,0,10) ,true);//从固定会议中读取
		foreach($gdrow as $k1=>$rs1)$rows[]=$rs1;
		
		foreach($rows as $k=>$rs){
			if($rs['hyname'] != $hyname)continue;
			$sdt = $rs['startdt'];
			$edt = $rs['enddt'];
			if(
			($sdt<=$startdt && $edt>$startdt)
			|| ($sdt<$enddt && $edt>=$enddt)
			|| ($sdt>$startdt && $edt<$enddt)
			|| ($sdt==$startdt && $edt==$enddt) 
			)$msg = '该会议室的时间段已被申请过了，主题“'.$rs['title'].'”';
		}
		return $msg;
	}
	
}