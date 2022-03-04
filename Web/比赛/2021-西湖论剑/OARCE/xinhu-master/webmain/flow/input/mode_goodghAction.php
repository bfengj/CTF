<?php
/**
*	此文件是流程模块【goodgh.物品归还】对应控制器接口文件。
*/ 
class mode_goodghClassAction extends inputAction{
	
	
	protected function savebefore($table, $arr, $id, $addbo){
		//判断是否重复录入
		$custid = $arr['custid'];
		$to 	= m('goodm')->rows("`custid`='$custid' and `type`=4 and `status` in(0,1,2)");
		if($to>0)return '已经申请过物品归还，不要重复申请';
		
		$rows['type'] = '4';//一定要是4，不能去掉
		return array(
			'rows'=>$rows
		);
	}
	
	
	protected function saveafter($table, $arr, $id, $addbo){
		
	}
	
	//读取需要归还的领用单
	public function getgoodly()
	{
		$rows = $this->db->getall('select a.`mid` from `[Q]goodn` a left join `[Q]goodm` b on a.mid=b.id where a.`lygh`=1 and b.`uid`='.$this->adminid.' and b.`status`=1 and b.`type`=0');
		$mid  = '';
		$barr = array();
		if($rows){
			foreach($rows as $k=>$rs)$mid.=','.$rs['mid'].'';
			$barr = $this->db->getall('select a.`id` as `value`,b.`sericnum` as name from `[Q]goodm` a left join `[Q]flow_bill` b on a.`id`=b.`mid` and b.`table`=\'goodm\' where a.`id` in('.substr($mid,1).') and a.`uid`='.$this->adminid.'');
		}
		
		return $barr;
	}
	
	//ajax读取需要归还的子表
	public function getgoodnAjax()
	{
		$wmid= (int)$this->get('wmid','0');
		$rows = $this->db->getall("select a.`aid`,b.name as temp_aid,a.`count` from `[Q]goodn` a left join `[Q]goods` b on a.`aid`=b.`id` where a.`mid`='$wmid' and a.`lygh`=1");
		return $rows;
	}
}	
			