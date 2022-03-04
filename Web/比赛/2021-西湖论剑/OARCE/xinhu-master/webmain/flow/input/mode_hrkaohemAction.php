<?php
/**
*	此文件是流程模块【hrkaohem.考核项目】对应控制器接口文件。
*/ 
class mode_hrkaohemClassAction extends inputAction{
	
	
	protected function savebefore($table, $arr, $id, $addbo){
		$data = $this->getsubtabledata(0);
		if(count($data)==0)return '至少要有一行考核项目内容';
		$wqz  = 0;
		foreach($data as $k=>$rs)$wqz+=floatval($rs['weight']);
		
		//if(round($wqz,2)!=100)return '考核项目内容权重加起来必须100%';
		
		$data = $this->getsubtabledata(1);
		if(count($data)==0)return '至少要有一行评分人';
		
		$wqz  = 0;
		foreach($data as $k=>$rs)$wqz+=floatval($rs['pfweight']);
		if(round($wqz,2)!=100)return '评分人的权重加起来必须100%';

	}
	
	
	protected function saveafter($table, $arr, $id, $addbo){
		
	}
	
	//复制
	public function copyfuzAjax()
	{
		$sid = (int)$this->get('sid');
		$arow= m('hrkaohem')->getone($sid);
		if(!$arow)return;
		unset($arow['id']);
		$arow['optdt'] = $this->rock->now;
		$arow['optname'] = $this->adminname;
		$arow['optid'] = $this->adminid;
		$mid = m('hrkaohem')->insert($arow);
		$dbs = m('hrkaohes');
		$dbn = m('hrkaohen');
		
		$nrows= $dbs->getall("`mid`='$sid'");
		foreach($nrows as $k=>$rs){
			$rs['mid'] = $mid;
			unset($rs['id']);
			$dbs->insert($rs);
		}
		$nrows= $dbn->getall("`mid`='$sid'");
		foreach($nrows as $k=>$rs){
			$rs['mid'] = $mid;
			unset($rs['id']);
			$dbn->insert($rs);
		}
	}
	
	//生成
	public function shengchegeAjax()
	{
		$keox = m('flow')->initflow('hrcheck')->hrkaohemrun();
		return '今日'.$this->rock->date.'有'.$keox.'个考核项目生成成功';
	}
}	
			