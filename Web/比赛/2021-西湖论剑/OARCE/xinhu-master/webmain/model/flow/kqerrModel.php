<?php
class flow_kqerrClassModel extends flowModel
{

	//审核完成了添加到打卡记录
	protected function flowcheckfinsh($zt)
	{
		m('kqdkjl')->insert(array(
			'uid' 		=> $this->uid,
			'optdt' 	=> $this->rock->now,
			'dkdt' 		=> $this->rs['dt'].' '.$this->rs['ytime'],
			'type' 		=> '4',
			'explain' 	=> '['.$this->rs['errtype'].']'.$this->rs['explain'].'',
		));
	}
	
	protected function flowbillwhere($uid, $lx)
	{
		$month	= $this->rock->post('month');
		$where 	= '';
		if($month!=''){
			$where.=" and a.`dt` like '$month%'";
		}

		return array(
			'where' => $where
		);
	}
	
	protected function flowbillwhere33($uid, $lx)
	{
		$dt 	= $this->rock->date;
		$key 	= $this->rock->post('key');
		$month 	= $this->rock->post('month');
		$where 	= "a.`uid`=$uid";
		if($lx=='all'){
			$where = '1=1';
		}
		if($key!='')$where.= m('admin')->getkeywhere($key, 'b.');
		if($month !='')$where.=" and a.`dt` like '$month%'";
		return array(
			'where' => 'and '.$where,
			'fields'=> 'a.*,b.name,b.deptname,b.ranking',
			'table'	=> '`[Q]'.$this->mtable.'` a left join `[Q]admin` b on a.`uid`=b.`id`',
			'order' => 'a.`id` desc'
		);
	}
}