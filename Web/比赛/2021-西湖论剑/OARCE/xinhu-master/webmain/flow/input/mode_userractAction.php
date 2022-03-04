<?php
/**
*	此文件是流程模块【userract.员工合同】对应接口文件。
*	可在页面上创建更多方法如：public funciton testactAjax()，用js.getajaxurl('testact','mode_userract|input','flow')调用到对应方法
*/ 
class mode_userractClassAction extends inputAction{
	
	
	protected function savebefore($table, $arr, $id, $addbo){
		$tqenddt = $arr['tqenddt'];
		$startdt = $arr['startdt'];
		$enddt 	 = $arr['enddt'];
		$barr	 = array();
		if(isempt($tqenddt)){
			
		}
	
		$barr['company'] = m('company')->getmou('name', (int)$arr['companyid']);
		return array(
			'rows' => $barr
		);
	}
	
	
	protected function saveafter($table, $arr, $id, $addbo){
		
		//替换word里的变量
		$htfid 	= (int)arrvalue($arr,'htfid','0');
		$uobj	= m('userinfo')->getone('`id`='.$arr['uid'].'');
		m('word')->replaceWord($htfid, array(
			'company' => $arr['company'],
			'name' 	  => $arr['uname'],
			'idnum'   =>$uobj['idnum'],
			'mobile'  =>$uobj['mobile'],
		));
	}
	
	//签署公司数据源
	public function companydata()
	{
		return m('company')->getselectdata(1);
	}
}	
			