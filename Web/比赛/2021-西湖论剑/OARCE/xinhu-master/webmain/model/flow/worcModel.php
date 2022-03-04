<?php
class flow_worcClassModel extends flowModel
{


	
	//删除分区前判断
	protected function flowdeletebillbefore()
	{
		if(m('word')->rows('cid='.$this->id.'')>0)return '分区下有存在文件/文件夹不能删除';
		
	}
	
	
}