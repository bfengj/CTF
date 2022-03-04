<?php
//加班
class flow_jiabanClassModel extends flowModel
{
	
	public function flowrsreplace($rs, $lx=0)
	{
		$rs['modenum'] = $this->modenum;
		$type 			= arrvalue($rs,'jiatype','0');
		$types			= array('调休','加班费');
		$rs['jiatype']  = $types[$type];
		$dakatime		= '未打卡';
		
		//详情时读取前后2小时打卡记录
		if($lx==1){
			$stime		= date('Y-m-d H:i:s', strtotime($rs['stime'])-3600*2);
			$etime		= date('Y-m-d H:i:s', strtotime($rs['etime'])+3600*2);
			$kqdkjl		= m('kqdkjl')->getall("`uid`='".$rs['uid']."' and `dkdt`>='$stime' and `dkdt`<='$etime'",'dkdt','`dkdt` desc');
			if($kqdkjl)$dakatime='';
			foreach($kqdkjl as $k=>$rs1){
				if($k>0)$dakatime.=',&nbsp;';
				$dakatime.=''.$rs1['dkdt'].'';
			}
		}
		
		if($type==1)$rs['jiatype'].=''.$rs['jiafee'].'元';
		if($type=='0')$rs['jiafee'] = '';
		
		$rs['dakatime']	= $dakatime;
		
		return $rs;
	}
	
	protected function flowgetfields($lx)
	{
		$arr['dakatime'] 		= '此时间段打卡';
		return $arr;
	}
	
	protected function flowbillwhere($uid, $lx)
	{
		$month	= $this->rock->post('month');
		$where 	= '';
		if($month!=''){
			$where.=" and `stime` like '$month%'";
		}

		return array(
			'where' => $where
		);
	}
	
	
	protected function flowcheckfinsh($zt)
	{
		if($zt==1)m('flow:leave')->updateenddt();
	}
	
}