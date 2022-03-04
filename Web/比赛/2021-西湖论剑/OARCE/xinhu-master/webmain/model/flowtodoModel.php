<?php
/**
*	流程通知
*/
class flowtodoClassModel extends Model
{

	public function initModel()
	{
		$this->settable('flow_todos');
	}
	
	/**
	*	添加人员提醒表里，让他必读查看
	*/
	public function addtotouids($uids, $uarr=array())
	{
		if(isempt($uids) || !$uarr)return;
		$mid		= arrvalue($uarr,'mid');
		$modenum	= arrvalue($uarr,'modenum');
		$uidsa 		= explode(',',$uids);
		$isuar		= array();
		$uarrs 		= $this->getrows("`modenum`='$modenum' and `mid`=$mid and `uid` in($uids)", '`uid`,`id`');
		foreach($uarrs as $k=>$rs)$isuar[$rs['uid']]=$rs['id'];
		$iarr 		= $garr = array();
		$gids	= '';
		foreach($uidsa as $uid){
			$where = '';
			if(isset($isuar[$uid]))$where = $isuar[$uid];
			$adda['adddt'] 	= $this->rock->now;
			$adda['readdt'] = null;
			$adda['isread'] = 0;
			foreach($uarr as $k=>$v)$adda[$k] = $v;
			if($where==''){
				$adda['uid'] = $uid;
				$iarr[] = $adda;
			}else{
				if(!$garr)$garr = $adda;
				$gids.=','.$where.'';
			}
		}
		if($iarr)$this->insertAll($iarr);
		if($gids!='')$this->update($garr,'`id` in('.substr($gids,1).')');
	}
	
	/**
	*	标识已读
	*/
	public function biaoyidu($uid, $mode, $mid)
	{
		$where 	= "`uid`='$uid' and `modenum`='$mode' and `mid`='$mid'";
		$this->update(array(
			'isread'    => 1,
			'readdt'	=> $this->rock->now
		),"$where and `isread`=0");
		m('todo')->update(array(
			'status'    => 1,
			'readdt'	=> $this->rock->now
		), "$where and `status`=0");
		//历史会话
		m('im_history')->update('`stotal`=0',"`uid`='$uid' and `stotal`>0 and `xgurl`='".$mode."|".$mid."'");
	}
	
	public function getwdtotals($uid)
	{
		$to = $this->rows("`uid`='$uid' and `isread`=0");
		return $to;
	}
	
}