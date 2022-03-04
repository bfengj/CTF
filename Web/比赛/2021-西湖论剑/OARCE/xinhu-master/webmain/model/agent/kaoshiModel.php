<?php
/**
*	考试培训
*/
class agent_kaoshiClassModel extends agentModel
{
	protected function agentdata($uid, $lx)
	{
		$rows 			= array();
		$table 			= '`[Q]knowtrais` a left join `[Q]knowtraim` b on a.mid=b.id left join `[Q]admin` c on c.id=a.`uid`';
		$where 			= '1=1';
		$where.=' and a.`uid`='.$uid.'';
		if($lx=='weiks')$where.=' and a.`isks`=0 and b.`state`=1';
		$arr 			=  m('knowtrais')->getlimit($where, $this->page, 'a.*,b.title,b.state,b.startdt,b.enddt,c.`name`,c.deptname', 'a.id desc', $this->limit, $table); //读取记录
		
		//格式化数据
		//$this->statearr = explode(',','<font color=#ff6600>还未开始</font>,<font color=green>进行中</font>,<font color=#888888>已结束</font>');
		foreach($arr['rows'] as $k=>$rs){
			$cont	= '';
			if($rs['isks']=='1'){
				$cont = '分数：'.$rs['fenshu'].'<br>考试时间：'.substr($rs['kssdt'],5,11).'';
				if(!isempt($rs['ksedt']))$cont.='至'.substr($rs['ksedt'],5,11).'';
			}
			
			$sarr	= array(
				'title' => $rs['title'],
				'optdt' => ''.substr($rs['startdt'],5,11).'至'.substr($rs['enddt'],5,11).'',
				'id'	=> $rs['mid'],
				'modenum' => 'knowtraim',
				'cont'	=> $cont
			);
			if($rs['isks']=='1'){
				$sarr['statustext']='已考试';
				$sarr['statuscolor']='green';
				$sarr['ishui']='1';
			}else{
				$zt = $rs['state'];
				if($zt=='0'){
					$sarr['statustext']='未开始';
					$sarr['statuscolor']='#ff6600';
				}elseif($zt=='2'){
					$sarr['statustext']='已结束';
					$sarr['statuscolor']='#888888';
					$sarr['ishui']='1';
				}else{
					$sarr['statustext']='未考试';
					$sarr['statuscolor']='red';
				}
			}
			$rows[] = $sarr;
		}
		$arr['rows'] 	= $rows;
		return $arr;
	}
	
	//统计我为考试记录数
	public function gettotal()
	{
		$stotal	= $this->getwdtotal($this->adminid);
		$titles	= '';
		return array('stotal'=>$stotal,'titles'=> $titles);
	}
	
	private function getwdtotal($uid)
	{
		$table 	= '`[Q]knowtrais` a left join `[Q]knowtraim` b on a.mid=b.id';
		$stotal = $this->db->rows($table,'a.uid='.$uid.' and a.`isks`=0 and b.`state`=1');
		return $stotal;
	}
	
	//底部菜单显示未考试数
	protected function agenttotals($uid)
	{
		$a = array(
			'weiks' => $this->getwdtotal($uid)
		);
		return $a;
	}
}