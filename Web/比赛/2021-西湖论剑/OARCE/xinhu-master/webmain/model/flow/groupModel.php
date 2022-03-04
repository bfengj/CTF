<?php
class flow_groupClassModel extends flowModel
{
	
	protected  $flowcompanyidfieds = 'none';
	
	protected function flowbillwhere($uid, $lx)
	{
		
		//$carr		= $this->adminmodel->getcompanyinfo();
		//$this->allid= $carr['companyallid'];
		//$companywhere = ' and `companyid` in('.join(',', $this->allid).')';
		$where = '';
		if(ISMORECOM && $this->adminid>1){
			$where = ' and `companyid`='.$this->companyid.'';
		}
		
		return array(
			'companywhere' => '',
			'where' => $where
		);
	}
}