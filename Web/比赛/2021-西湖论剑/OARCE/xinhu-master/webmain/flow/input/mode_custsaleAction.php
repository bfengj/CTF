<?php
/**
*	客户.销售机会
*/
class mode_custsaleClassAction extends inputAction{
	
	public function selectcust()
	{
		$rows = m('crm')->getmycust($this->adminid, $this->rock->arrvalue($this->rs, 'custid'));
		return $rows;
	}
}	
			