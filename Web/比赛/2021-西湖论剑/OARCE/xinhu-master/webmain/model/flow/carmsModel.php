<?php
//车辆信息登记
class flow_carmsClassModel extends flowModel
{
	
	
	public function flowrsreplace($rs)
	{
		$ztname	= '';
		if(!isempt($rs['enddt'])){
			$jg = c('date')->datediff('d', $this->rock->date, $rs['enddt']);
			if($jg<0){
				$ztname='<font color="#888888">已到期</font>';
				$rs['ishui'] = 1;
			}
			if($jg==0)$ztname='<font color="blue">今日到期</font>';
			if($jg>0 && $jg<30)$ztname='<font color="#ff6600">'.$jg.'天后到期</font>';
			if($jg>=30)$ztname='<font color="green">生效中</font>';
		}
		if(isset($rs['carnum'])){
			$ors 	= $rs;
		}else{
			$ors 	= m('carm')->getone($rs['carid']);
		}
		if($ors)$rs['carid'] = ''.$ors['carbrand'].','.$ors['carmode'].'('.$ors['carnum'].')';
		$rs['ztname']	= $ztname;

		return $rs;
	}

	public function flowbillwhere($uid, $lx)
	{
		$where = '';
		$carid = (int)$this->rock->get('carid',0);
		if($carid>0)$where='and a.`carid`='.$carid.'';
		
		return array(
			'table' 		=> '`[Q]'.$this->mtable.'` a left join `[Q]carm` b on a.`carid`=b.id',
			'fields'		=> 'a.*,b.carnum,b.carbrand,b.carmode,cartype',
			'orlikefields'	=> 'b.carnum,b.carbrand,b.carmode,b.`cartype`,a.`otype`@1',
			'asqom'			=> 'a.',
			'where'			=> $where,
		);
	}
	
	//每天信息提醒
	public function todocarms($toid)
	{
		if(isempt($toid))return '没设置提醒人员';
		$dt 	= $this->rock->date;
		$dt30	= c('date')->adddate($dt,'d', 30);
		$rows 	= $this->db->getall('select a.`enddt`,a.`otype`,b.`carnum` from `[Q]carms` a left join `[Q]carm` b on a.carid=b.id where b.id is not null and a.`enddt` is not null and  a.`enddt`>=\''.$dt.'\'');
		$txlist = m('option')->getval('cartodo','0,3,7,15,30');
		$txarr 	= explode(',', $txlist);
		$dtobj 	= c('date');
		$cars	= array();
		$str 	= '';
		foreach($rows as $k=>$rs){
			$jg = $dtobj->datediff('d', $dt, $rs['enddt']);
			if(in_array($jg, $txarr)){
				$strs = ''.$jg.'天后('.$rs['enddt'].')';
				if($jg==1)$strs='明天';
				if($jg==0)$strs='今天';
				$str .= ''.$rs['carnum'].'的['.$rs['otype'].']将在'.$strs.'到期;';
			}
		}
		
		//下次保养提醒
		$rows 	= $this->db->getall('select a.`nextdt`,b.`carnum`,a.`jiaid`,a.`uid` from `[Q]carmang` a left join `[Q]carm` b on a.carid=b.id where b.id is not null and a.`type`=1 and a.`status`=1 and a.`nextdt` is not null and  a.`nextdt`>=\''.$dt.'\'');
		
		foreach($rows as $k=>$rs){
			$jg = $dtobj->datediff('d', $dt, $rs['nextdt']);
			if(in_array($jg, $txarr)){
				$strs = ''.$jg.'后('.$rs['nextdt'].')';
				if($jg==1)$strs='明天';
				if($jg==0)$strs='今天';
				$str .= ''.$rs['carnum'].'在'.$strs.'后需保养了;';
			}
		}
		if($str!=''){
			$this->push($toid, '车辆', $str, '车辆信息提醒');
		}
		return 'success';
	}
}