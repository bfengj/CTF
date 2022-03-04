<?php
class todoClassModel extends Model
{
	/**
		添加通知
	*/
	public function add($uid, $title, $mess, $arrs=array())
	{
		if(isempt($uid))return false;
		$arr['title']	= $title;
		$mess			= htmlspecialchars(str_replace(array('<br>',"\n"),'[br]',$mess));
		$arr['mess']	= str_replace('[br]','<br>',$mess);
		$arr['status']	= '0';
		$arr['optdt']	= $this->rock->now;
		$arr['tododt']	= $this->rock->now;
		foreach($arrs as $k=>$v)$arr[$k] = $v;
		$uid	= ''.$uid.'';
		$suid	= explode(',', $uid);
		$iarr	= array();
		foreach($suid as $suids){
			$arr['uid']	= $suids;
			$iarr[] = $arr;
		}
		if($iarr)$this->insertAll($iarr);
	}
	
	public function addtodo($receid, $title, $mess, $mode='', $mid=0)
	{
		if($receid=='')return;
		$where 		= '';
		if($receid!='all')$where = ' and `id` in('.$receid.')';
		$rows 	= $this->db->getrows('[Q]admin','`status`=1 '.$where.'','id');
		$uids 	= '';
		foreach($rows as $k=>$rs)$uids.= ','.$rs['id'].'';
		if($uids != ''){
			$uids = substr($uids, 1);
			if($mode!='' && $mid>0)$this->delete("`uid` in($uids) and `modenum`='$mode' and mid='$mid' and `status`=0");
			$this->add($uids, $title, $mess, array(
				'modenum' => $mode,
				'mid'	  => $mid
			));
		}
		return $uids;
	}
	
	/**
		添加唯一的通知
	*/
	public function addtz($uid, $title, $mess, $table='', $mid='', $tododt='')
	{
		$where = '';
		if($table != '')$where = " and `table`='$table'";
		if($mid != '')$where .= " and `mid`='$mid'";
		if($where != ''){
			$this->delete("`uid` in($uid) and `status`=0 $where");
		}
		$arr 	= array(
			'table'	=> $table,
			'mid'	=> $mid
		);
		if(!$this->isempt($tododt))$arr['tododt'] = $tododt;
		$this->add($uid, $title, $mess, $arr);
	}
	
	/**
	*	删除提醒
	*/
	public function deltodo($num, $mid, $uid, $table='')
	{
		$where = "`uid` in($uid) and `modenum`='$num' and `mid`='$mid'";
		$this->delete($where);
		m('flow_todos')->delete($where);
		m('im_history')->delete("`uid` in($uid) and `xgurl`='".$num."|".$mid."'");
	}
}