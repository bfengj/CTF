<?php
/**
*	模块.图书借阅
*/
class flow_bookborrowClassModel extends flowModel
{
	public function flowrsreplace($rs)
	{
		$fte = '<font color=red>否</font>';
		$isgh= 0;
		if(!isempt($rs['ghtime'])){
			$fte = '<font color=green>是</font>';
			$isgh= 1;
		}
		if($rs['isgh'] != $isgh)$this->update('`isgh`='.$isgh.'', $rs['id']);
		$rs['isgh'] = $fte;
		return $rs;
	}
}