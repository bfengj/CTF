<?php
class wordClassAction extends Action
{

	public function getmywordtypeAjax()
	{
		$showlx = (int)$this->post('showlx',0); //0个人,1部门
		$bo 	= $showlx==1;
		$pid 	= m('word')->getfolderid($this->adminid, $bo);
		$rows 	= m('word')->getfoldrows($this->adminid, $bo);
		$rows	= array(
			'rows' 	=> $rows,
			'pid'	=> $pid
		);
		$this->returnjson($rows);
	}
	
	public function getshatewordtypeAjax()
	{
		$rows	= array();
		$rows	= array(
			'rows' 	=> $rows
		);
		$this->returnjson($rows);
	}
	
	public function wordbeforeaction($table)
	{
		$typeid = (int)$this->post('typeid',0);
		$showlx = (int)$this->post('showlx',0); //0个人,1部门
		$bo 	= $showlx==1;
		if($showlx==0){
			//$pid 	= m('word')->getfolderid($this->adminid);
			//$where 	= " and a.optid=".$this->adminid."";
		}else{
			
		}
		
		$pid 	= m('word')->getfolderid($this->adminid, $bo);
		$alltpeid = $this->option->getalldownid($pid);
		$where  = " and a.typeid in($alltpeid)";
		
		if($pid==$typeid || $typeid==0){
			
		}else{
			$alltpeid = $this->option->getalldownid($typeid);
			$where.=" and a.typeid in($alltpeid)";
		}
		
		return array(
			'table' => '`[Q]word` a left join `[Q]file` b on a.fileid=b.id left join `[Q]option` c on c.id=a.typeid',
			'fields'=> 'b.id,a.shate,a.typeid,b.filepath,a.optname,a.optid,a.optdt,b.filename,b.fileext,b.filesizecn,b.downci,c.`name` as typename',
			'where'	=> "and b.id is not null $where",
			'order'	=> 'a.id desc'
		);
	}

	
	public function savefileAjax()
	{
		$typeid = (int)$this->post('typeid',0);
		$sid 	= $this->post('sid');
		$sadid	= explode(',', $sid);
		
		$arr['optid'] 	= $this->adminid;
		$arr['optname'] = $this->adminname;
		$arr['optdt'] 	= $this->now;
		$arr['typeid'] 	= $typeid;
		$file 			= m('file');
		foreach($sadid as $fid){
			$arr['fileid'] = $fid;
			$sid = m('word')->insert($arr);
			$file->addfile($fid, 'word', $sid, 'word');
		}
		echo 'ok';
	}
	
	public function sharefileAjax()
	{
		$fileid 		= $this->post('fid','0');
		$arr['shateid'] = $this->post('sid');
		$arr['shate']   = $this->post('sna');
		m('word')->update($arr, "optid='$this->adminid' and fileid in($fileid)");
	}
	
	public function sharefileerAjax()
	{
		$fileid 			= (int)$this->post('fid','0');
		$arr['receid'] 		= $this->post('sid');
		$arr['recename']   = $this->post('sna');
		m('option')->update($arr, "id ='$fileid'");
	}
	
	
	public function shatebefore($talbe)
	{
		$key	= $this->post('key');
		$atype	= $this->post('atype');
		$where  = m('admin')->getjoinstrs('a.shateid', $this->adminid, 1);
		$optid 	= 0;
		if($atype=='wfx'){
			$where 	= " and a.optid=".$this->adminid." and a.shate is not null";
			$optid	= $this->adminid;
		}
		$alsid	= $this->option->getreceiddownall($this->adminid, $optid);
		if($alsid != ''){
			$where = ' and ((1 '.$where.') or a.`typeid` in('.$alsid.') )';
		}
		
		if($key!=''){
			$where.=" and (a.`optname` like '%$key%' or b.`filename` like '%$key%' or c.`name` like '%$key%')";
		}
		return array(
			'table' => '`[Q]word` a left join `[Q]file` b on a.fileid=b.id left join `[Q]option` c on c.id=a.typeid',
			'where' => 'and b.id is not null '.$where.'',
			'fields'=> 'b.id,a.shate,a.typeid,a.optname,a.optid,b.filepath,a.optdt,b.filename,b.fileext,b.filesizecn,b.downci,c.`name` as typename',
			'order' => 'a.id desc'
		);
	}
	
	public function delwordAjax()
	{
		$fid	= $this->post('id','0');
		m('word')->delete("`fileid`='$fid'");
		m('file')->delfile($fid);
		backmsg();
	}
	
	//移动
	public function movefileAjax()
	{
		$fid	= $this->post('fid','0');
		$tid	= $this->post('tid','0');
		m('word')->update("`typeid`='$tid'","`fileid` in ($fid)");
	}
}