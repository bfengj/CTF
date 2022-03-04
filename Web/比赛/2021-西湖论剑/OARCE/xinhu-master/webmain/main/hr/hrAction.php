<?php
class hrClassAction extends Action
{
	//培训考试
	public function kaoshiAction()
	{
		$id 	= (int)$this->get('id');
		m('flow:knowtraim')->reloadstate($id);//更新状态
		
		$mrs 	= m('knowtraim')->getone($id);
		if(!$mrs)return '主题不存在';
		
		if($mrs['state']!='1')return '培训考试题目可能还未开始或已结束了';
		
		$uid 	= $this->adminid;
		$ors 	= m('knowtrais')->getone('`mid`='.$id.' and `uid`='.$uid.'');
		if(!$ors)return '当前主题你不需要培训考试';
		
		
		
		if($ors['isks']=='1')return '你已经考试过了分数:'.$ors['fenshu'].'';
		
		$tkids	= $ors['tkids'];
		$tkrows= array();
		if(!isempt($tkids)){
			$tkarr = m('knowtiku')->getall('id in('.$tkids.')','`id`,`title`,`typeid`,`type`,`content`,`ana`,`anb`,`anc`,`and`,`ane`');
			$tkidsa= explode(',', $tkids);
			foreach($tkidsa as $ids){
				foreach($tkarr as $k=>$rs){
					$id = $rs['id'];
					if($ids==$id){
						$tkrows[] = $rs;
						break;
					}
				}
			}
		}
		
		$this->assign('tkrows', json_encode($tkrows));
		$this->assign('mrs', $mrs);
		$this->assign('ors', $ors);
	}
	
}