<?php
class flow_knowledgeClassModel extends flowModel
{
	protected function flowchangedata(){
		$this->rs['content'] = c('html')->replace($this->rs['content']);
	}
	
	protected function flowdatalog($arr)
	{
		return array('title'=>'');
	}
	
	protected function flowbillwhere($uid, $lx)
	{
		$where  = '';
		$typeid = (int)$this->rock->post('typeid','0');
		$key 	= $this->rock->post('key');
		if($typeid!='0'){
			$alltpeid = m('option')->getalldownid($typeid);
			$where .= ' and a.`typeid` in('.$alltpeid.')';
		}
		if($key != ''){
			$where.=" and (a.`title` like '%$key%' or b.`name` like '%$key%')";
		}
		return array(
			'where' => $where,
			'order' => 'a.`sort`,a.`optdt` desc',
			'asqom' => 'a.',
			'table'	=> '`[Q]'.$this->mtable.'` a left join `[Q]option` b on a.`typeid`=b.`id`',
			'fields'=> 'a.id,a.title,a.adddt,a.optdt,a.optname,b.`name` as typename,a.`sort`'
		);
	}
}