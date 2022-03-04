<?php
/**
*	音视频通话使用的
*/ 
class tonghuaClassAction extends Action{
	
	public function defaultAction()
	{
		$id			= (int)$this->get('id','0');
		$channel	= $this->get('channel');
		$iscall		= 'true';
		if($channel){
			$thrs = m('im_tonghua')->getone("`channel`='$channel'");
			if(!$thrs)return '无效记录';
			$state= $thrs['state'];
			if($state==3 || $state==5)return '通话已取消';
			if($state!='0')return '通话记录无效';
			if($thrs['faid']!=$id)return '无效打开';
			if($thrs['joinids']!=$this->adminid)return '不是跟你的通话';
			$iscall = 'false';
			$sytime = time()-strtotime($thrs['adddt']);
			$thrs['sytime'] = $sytime;
			$this->assign('thrs', $thrs);
		}else{
			$thrs['sytime'] = 0;
			$thrs['channel'] = '';
			$thrs['type'] = 0;
			$this->assign('thrs', $thrs);
		}
		$dbs= m('admin');
		$ars= $dbs->getone('`id`='.$id.' and `status`=1','id,name,face');
		if(!$ars)return '用户不存在';
		$this->title = '与'.$ars['name'].'通话';
		$ars['face'] = $dbs->getface($ars['face']);
		$ars['iscall'] = $iscall;
		$this->assign('ars', $ars);
	}
	
}