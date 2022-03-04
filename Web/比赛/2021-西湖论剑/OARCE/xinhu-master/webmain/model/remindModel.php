<?php
//单据提醒设置
class remindClassModel extends Model
{
	public function initModel()
	{
		$this->settable('flow_remind');
	}
	
	public function todorun()
	{
		return m('flow')->initflow('remind')->getremindtodo();
	}
	
	/**
	*	获取主ID
	*/
	public function getremindrs($table, $mid)
	{
		$rs = $this->getone("`table`='$table' and `mid`='$mid'");
		return $rs;
	}
}