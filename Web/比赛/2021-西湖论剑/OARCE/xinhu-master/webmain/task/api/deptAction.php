<?php 
class deptClassAction extends apiAction
{
	public function dataAction()
	{
		$udarr 		= m('dept')->getdeptuserdata(0);
		$userarr 	= $udarr['uarr'];
		$deptarr 	= $udarr['darr'];
		
		$grouparr 	= m('reim')->getgroup($this->adminid);
		
		$arr['deptjson']	= json_encode($deptarr);
		$arr['userjson']	= json_encode($userarr);
		$arr['groupjson']	= json_encode($grouparr);
		$this->showreturn($arr);
	}
	
	/**
	*	app获取数据
	*/
	public function dataappAction()
	{
		$udarr 		= m('dept')->getdeptuserdata(0);
		$userarr 	= $udarr['uarr'];
		$deptarr 	= $udarr['darr'];
		$grouparr 	= m('reim')->getgroup($this->adminid);
		
		$arr['deptarr']	= $deptarr;
		$arr['userarr']	= $userarr;
		$arr['grouparr']= $grouparr;
		$this->showreturn($arr);
	}
}