<?php
/**
*	印章申请使用
*/
class mode_sealaplClassAction extends inputAction{
	

	protected function savebefore($table, $arr, $id, $addbo){
		
	}
	
	
	protected function saveafter($table, $arr, $id, $addbo){
		
		//更新
		$mknum = arrvalue($arr, 'mknum');
		if(!isempt($mknum)){
			$numa = explode(',', $mknum);
			$num  = $numa[0];
			$mid  = (int)arrvalue($numa,1);
			$flow = m('flow')->initflow($num);
			if($num=='officia'){
				$flow->update("`yzid`='$id'", "`id`='$mid'");
			}
		}
	}
	
	//获取印章
	public function getsealdata()
	{
		$where= m('admin')->getcompanywhere(1);
		$rows = m('seal')->getall('1=1 '.$where.'','`id`as value,`name`,`type`','`sort`');
		$aaar = $barr = array();
		foreach($rows as $k=>$rs){
			$optgroup = '印章';
			if(!contain($rs['type'],'章'))$optgroup='证照';
			$rs['optgroup'] = $optgroup;
			$rs['subname'] = $rs['type'];
			$rs['padding'] = '40';
			if($optgroup=='印章'){
				$aaar[] = $rs;
			}else{
				$barr[] = $rs;
			}
		}
		
		$ba1[] = array('value'=>'','name'=>'印章','disabled'=>true);
		$ba2[] = array('value'=>'','name'=>'证照','disabled'=>true);
		if(!isempt($this->get('mknum'))){
			$ba2 = array();
			$barr = array();
		}
		
		return array_merge($ba1,$aaar,$ba2, $barr);
	}
	
	public function getsealdatass()
	{
		$where= m('admin')->getcompanywhere(1);
		$rows = m('seal')->getall('1=1 '.$where.'','`id`as value,`name`,`type`','`sort`,`type`');
		$barr = array();
		$type = '';
		foreach($rows as $k=>$rs){
			if($type!=$rs['type'])$barr[] = array('value'=>'','name'=>$rs['type'],'disabled'=>true);
			$rs['padding'] = '40';
			$barr[] = $rs;
			$type = $rs['type'];
		}

		return $barr;
	}
	
	//获取相关信息放到说明里
	public function getbinfoAjax()
	{
		$mknum = $this->get('mknum');
		$barr  = array();
		if(!isempt($mknum)){
			$numa = explode(',', $mknum);
			$num  = $numa[0];
			$mid  = (int)arrvalue($numa,1);
			$flow = m('flow')->initflow($num, $mid, false);
			$barr['zhaiyao'] = $flow->getsummary();
			
		}
		return $barr;
	}
}	
			