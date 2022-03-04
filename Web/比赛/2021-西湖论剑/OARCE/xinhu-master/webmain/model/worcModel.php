<?php
class worcClassModel extends Model
{
	
	/**
	*	获取我的分区
	*/
	public function getmywroc($uid=0)
	{
		if($uid==0)$uid = $this->adminid;
		$db   = m('admin');
		$dbs  = m('word');
		$str  = $db->getjoinstr('receid', $uid, 1);
		$utype= arrvalue($db->nowurs,'type','0');
		$where= $db->getcompanywhere(1);
		$rows = $this->getall('1=1 and ('.$str.')'.$where.'','id,name,guanname,guanid,uptype,upuserid,uid','`sort`');
		$ids  = '';
		foreach($rows as $k=>$rs){
			$ids.=','.$rs['id'].'';
			//判断是否管理权限
			$ismanage = 0;
			$isup	  = 0;
			
			if(!isempt($rs['guanid'])){
				if($db->containjoin($rs['guanid'], $uid))$ismanage=1;
			}else{
				if($utype=='1')$ismanage=1;
			}
			$rows[$k]['ismanage'] = $ismanage;
			if(isempt($rs['upuserid']) && $rs['uid']==$uid)$isup = 1;
			if(!isempt($rs['upuserid'])){
				if($db->containjoin($rs['upuserid'], $uid))$isup=1;
			}
			
			$rows[$k]['isup'] = $isup;
			
			$wcount = $dbs->rows('`cid`='.$rs['id'].' and `type`=0');
			
			$rows[$k]['wcount'] = $wcount;
			if(isempt($rs['uptype']))$rs['uptype'] = '';
			$rows[$k]['uptype'] = $rs['uptype'];
		}
		if($ids!='')$ids = substr($ids, 1);
		return array(
			'rows' => $rows,
			'ids' => $ids,
			'officebj' => getconfig('officebj')
		);
	}
	
}