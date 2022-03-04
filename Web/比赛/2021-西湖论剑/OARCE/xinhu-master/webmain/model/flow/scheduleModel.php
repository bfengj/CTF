<?php
class flow_scheduleClassModel extends flowModel
{

	protected function flowinit(){
		$this->ratearr		 = array('d'=>'天','w'=>'周','m'=>'月');
	}
	
	//是否有读权限
	public function flowisreadqx()
	{
		if(!isempt($this->rs['receid'])){
			$where 		= m('admin')->gjoin($this->rs['receid'], 'ud', $blx='where');
			$where 		= 'id='.$this->adminid.' and ('.$where.')';
			$bo 		= true;
			if(m('admin')->rows($where)==0)$bo=false;
			return $bo;
		}else{
			return false;
		}
	}
	
	public function getstatusarr()
	{
		$barr[1] = array('启用','green');
		$barr[0] = array('停用','#888888');
		return $barr;
	}

	public function flowrsreplace($rs, $lx=0)
	{
		$txsj = (int)$rs['txsj'];
		$str  = '不提醒';
		if($txsj==1)$str  = '提醒';
		$rs['txsj'] = $str;
		$rate = $rs['rate'];
		if(isset($this->ratearr[$rate])){
			if($rate=='w')$rate='每周'.$rs['rateval'].'';
			if($rate=='d')$rate='每天';
			if($rate=='m')$rate='每月'.$rs['rateval'].'号';
			$rs['rate'] = $rate;
		}
		if($rs['status']==0)$rs['ishui']=1;
		if($lx==1){
			$barrs = $this->getstatusarr();
			$zts   = $barrs[$rs['status']];
			$rs['status'] = '<font color="'.$zts[1].'">'.$zts[0].'</font>';
		}
		return $rs;
	}
	

	protected function flowbillwhere($uid, $lx)
	{
		$where	= '';
		$dt 	= $this->rock->post('dt');
		if($dt!='')$where =" and `startdt` like '$dt%'";
		return array(
			'keywhere' => $where,
			'order' => 'optdt desc'
		);
	}
}