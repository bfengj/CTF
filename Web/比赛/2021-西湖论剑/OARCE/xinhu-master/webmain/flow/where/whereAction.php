<?php
class whereClassAction extends ActionNot
{
	
	public function setwhereAction()
	{
		$modeid = (int)$this->get('modeid');
		$farr 	= $this->db->getrows('[Q]flow_element',"`mid`='$modeid' and `iszb`=0 and `iszs`=1",'`fields`,`name`,`fieldstype`','sort,id');
		
		$this->assign('farr', $farr);
	}
	
}