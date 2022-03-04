<?php
class waichuClassModel extends Model
{
	public function initModel()
	{
		$this->settable('kqout');
	}
	
	public function getoutrows($dt, $uid)
	{
		$rows 	= $this->getall("uid=$uid and `status` in(0,1) and isturn=1 and `intime`>'$dt 00:00:00' and `outtime`<'$dt 23:59:59'",'id,atype,status,address,outtime,reason','intime asc');
		return $rows;
	}
}