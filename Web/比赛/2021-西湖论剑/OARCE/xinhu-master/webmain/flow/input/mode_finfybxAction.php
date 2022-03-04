<?php
/**
*	此文件是流程模块【finfybx.费用报销】对应接口文件。
*	可在页面上创建更多方法如：public funciton testactAjax()，用js.getajaxurl('testact','mode_finfybx|input','flow')调用到对应方法
*/ 
class mode_finfybxClassAction extends inputAction{
	

	protected function savebefore($table, $arr, $id, $addbo){
		
	}
	
	
	protected function saveafter($table, $arr, $id, $addbo){
		
	}
	
	public function getlastAjax()
	{
		$rs = m('fininfom')->getone("`uid`='$this->adminid' and `type`<3 order by `optdt` desc",'paytype,cardid,openbank,fullname');
		if(!$rs)$rs='';
		$this->returnjson($rs);
	}
	
	//根据报销项目统计
	public function itemtotalAjax()
	{
		$rows   = array();
		$where  = m('flow')->initflow('finfybx')->gethighwhere();
		$where1 = str_replace('{asqom}','b.', $where);
		$where  = m('admin')->getcompanywhere(1,'b.');
		$rosa   = $this->db->getall('SELECT a.`name`,sum(a.`money`)as money FROM `[Q]fininfos` a left join `[Q]fininfom` b on a.mid=b.id where b.`status` in(1) and b.`type`=0 '.$where.' '.$where1.' group by a.`name` ');
		$money = 0;
		foreach($rosa as $k=>$rs)$money+=floatval($rs['money']);
		if($money>0){
			foreach($rosa as $k=>$rs){
				$rows[] = array(
					'value' => $rs['money'],
					'name' => $rs['name'],
					'bili' => $this->rock->number( floatval($rs['money']) * 100 / $money).'%',
				);
			}
		}else{
			$rows[] = array(
				'value' => 0,
				'name' => '暂无数据',
				'bili' => '',
			);
		}
		
		return array('rows'=>$rows);
	}
}	
			