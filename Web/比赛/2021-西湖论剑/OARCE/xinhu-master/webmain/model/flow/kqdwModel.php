<?php
class flow_kqdwClassModel extends flowModel
{
	protected $flowcompanyidfieds	= 'uid'; //多单位用这个关联 
	
	public function initModel()
	{
		$this->dateobj = c('date');
		$this->typearr = explode(',','普通,事件,企业微信定位');
	}
	
	//打开详情时跳转到地理位置显示
	protected function flowchangedata()
	{
		if(!isajax() && !isempt($this->rs['location_x'])){
			$url = 'index.php?m=kaoqin&a=location&d=main&id='.$this->id.'';
			$this->rock->location($url);
			exit();
		}
	}
	
	/**
	*	显示条件过滤
	*/
	protected function flowbillwhere($uid, $lx)
	{
		$atype	= $lx;
		$dt1	= $this->rock->post('dt1');
		$dt2	= $this->rock->post('dt2');
		$key	= $this->rock->post('key');
		$s 		= '';
		$s		= ' and b.id='.$this->adminid.'';
		
		//全部下属打卡
		if($lx=='down'){
			$s  = 'and '.$this->adminmodel->getdownwheres('b.id', $uid, 0);
		}
		if($atype=='all')$s ='';
		if(!isempt($dt1))$s.=" and a.`optdt`>='$dt1'";
		if(!isempt($dt2))$s.=" and a.`optdt`<='$dt2 23:59:59'";
		if(!isempt($key))$s.=" and (b.`name` like '%$key%' or b.`deptallname` like '%$key%' or b.`ranking` like '%$key%')";
		$fields = 'a.*,b.name,b.deptname';
		$tabls  = $this->mtable;
		
		$table  = '`[Q]'.$tabls.'` a left join `[Q]userinfo` b on a.uid=b.id';
		return array(
			'where' => $s,
			'table' => $table, 
			'order' => 'a.`id` desc',
			'fields'=> $fields
		);
	}
	
	//替换
	public function flowrsreplace($rs, $lx=0)
	{
		$week 		= $this->dateobj->cnweek($rs['optdt']);
		$rs['week'] = $week;
		if($week=='六' || $week=='日')$rs['ishui']= 1;
		$rs['type']= arrvalue($this->typearr, $rs['type']);
		return $rs;
	}
}