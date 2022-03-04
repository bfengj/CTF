<?php
//部门的
class flow_deptClassModel extends flowModel
{
	//删除之前判断
	protected function flowdeletebillbefore()
	{
		if(m('admin')->rows($this->rock->dbinstr('deptpath',$this->id,'[',']'))>0)return '部门下有用户不允许删除';
		if($this->rows('pid='.$this->id.'')>0)return '有下级部门不允许删除';
	}
}