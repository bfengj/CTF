<?php
class flow_userractClassModel extends flowModel
{
	protected $flowcompanyidfieds	= 'companyid';

	public function initModel()
	{
		$this->statearr 	= explode(',','<font color=blue>待执行</font>,<font color=green>生效中</font>,<font color=#888888>已终止</font>,<font color=red>已过期</font>');
	}

	public function flowrsreplace($rs)
	{
		$rs['state']		= $this->statearr[$rs['state']];
		return $rs;
	}
	public function updatestate()
	{
		$dt 	= $this->rock->date;
		$this->update("`state`=2", "`tqenddt` is not null and `tqenddt`<`enddt` and `tqenddt`<'$dt'");
		$this->update("`state`=1", "`startdt`<='$dt' and `enddt`>='$dt' and `tqenddt` is null");
		$this->update("`state`=3", "`enddt`<'$dt' and `tqenddt` is null");
		$this->update("`state`=0", "`startdt`>'$dt'");
		
	}
	
	protected function flowbillwhere($uid, $lx)
	{
		$this->updatestate();
		$table 	= '`[Q]userract` a left join `[Q]admin` b on a.uid=b.id';
		return array(
			'where' => '',
			'table'	=> $table,
			'fields'=> 'a.*,b.deptname',
			'orlikefields'=>'b.deptname',
			'order' => 'a.`optdt` desc',
			'asqom' => 'a.'
		);
	}
}