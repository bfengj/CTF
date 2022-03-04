<?php
//考勤信息
class flow_leavehrClassModel extends flowModel
{
	//导入数据的测试显示
	public function flowdaorutestdata()
	{
		$barr[]=array(
			'uname' 		=> '张飞',
			'kind' 		=> '增加年假',
			'stime' 		=> '2019-07-01 00:00:00',
			'etime' 		=> '2020-07-01 00:00:00',
			'totals' 		=> '16',
			'totday' 		=> '2',
			'explain' 		=> '奖励2天年假',
		
		);
		$barr[]=array(
			'uname' 	=> '赵子龙',
			'kind' 		=> '增加陪产假',
			'stime' 		=> '2019-07-01 08:00:00',
			'etime' 		=> '2020-07-01 18:00:00',
			'totals' 		=> '120',
			'totday' 		=> '15',
			'explain' 		=> '陪产假男性，一般15天是要一次休完',
		
		);
		return $barr;
	}
	
	//导入之前判断
	public function flowdaorubefore($rows)
	{
		$inarr	= array();
		foreach($rows as $k=>$rs){
			$urs = $this->adminmodel->geturs($rs['uname']);
			if(!$urs)return '行'.($k+1).'找不到对应人员:'.$rs['uname'].'';
			
			$rs['uid'] 	 = $urs['id'];
			$rs['uname'] = $urs['name'];
			$rs['comid'] = $urs['companyid'];
			$rs['applydt'] = $this->rock->date;
			
			$rs['status']=1;
			$rs['isturn']=1;
			$inarr[] = $rs;
		}
		return $inarr;
	}
}