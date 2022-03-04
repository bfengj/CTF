<?php
/**
*	应用管理方法
*/
class reimplat_agentClassModel extends reimplatModel
{
	/**
	*	获取所有应用
	*/
	public function listdata()
	{
		$url 	= $this->geturl('openagent','listdata');
		$result = c('curl')->getcurl($url);
		
		$barr 	= $this->recordchu($result);
		if($barr['success']){
			$list = $barr['data']['agentlist'];
			$stra = array();
			foreach($list as $k=>$rs)$stra[$rs['num']] = $rs['name'];
			$this->option->setval('reimplat_agentlist@-7', json_encode($stra, JSON_UNESCAPED_UNICODE));
			return returnsuccess($list);
		}
		return $barr;
	}
	
	/**
	*	发应用消息
	*/
	public function sendtext($touid,$agentnum, $mess)
	{
		$touids = $this->gettouid($touid);
		if(!$touids)return returnerror(''.$touid.'未关注');
		$url = $this->geturl('openagent','sendmsg');
		$data['agentnum'] 	= $agentnum;
		$data['touser'] 	= $touids;
		$data['msgtype'] 	= 'text'; 
		$data['content'] 	= $mess;
		
		$result = c('curl')->postcurl($url, json_encode($data));
		$barr 	= $this->recordchu($result);
		
		return $barr;
	}
	
	/**
	*	发卡片消息
	*/
	public function sendtextcard($touid,$agentnum, $wxarr)
	{
		$touids = $this->gettouid($touid);
		if(!$touids)return returnerror(''.$touid.'未关注');
		$url = $this->geturl('openagent','sendmsg');
		$data['agentnum'] 	= $agentnum;
		$data['touser'] 	= $touids;
		$data['msgtype'] 	= 'textcard'; 
		$data['content'] 	= $wxarr;
		
		$result = c('curl')->postcurl($url, json_encode($data));
		$barr 	= $this->recordchu($result);
		return $barr;
	}
	
	private function gettouid($touid)
	{
		if($touid=='@all' || $touid=='all')return '@all';
		$uarrs	= $this->db->getall("select a.`user`,a.`name` from `[Q]admin` a left join `[Q]zreim_user` b on a.`user`=b.`user` where a.`status`=1 and b.id is not null and b.`status`=1 and a.`id` in($touid)");
		$uids  = '';
		foreach($uarrs as $k=>$rs)$uids.=','.$rs['user'].'';
		if($uids)$uids = substr($uids, 1);
		return $uids;
	}
	
	/**
	*	发送消息
	*/
	public function sendxiao($touid, $agentname, $wxarr=array(),$iszs=false)
	{
		$liststr = $this->option->getval('reimplat_agentlist');
		if(isempt($liststr))return returnerror('请先获取应用');
		
		if(!$iszs && getconfig('asynsend')=='1'){
			$cans['touid'] 	   = $touid;
			$cans['agentname'] = $agentname;
			$cans['wxarr'] 	   = $wxarr;
			$barr = c('rockqueue')->push('cli,reimplatsend', array(
				'body' => $this->rock->jm->base64encode(json_encode($cans))
			));
			if($barr['success'])return returnsuccess('anaysend');
		}
		
		$yarr	 = json_decode($liststr, true);
		$num	 = $dnum = '';
		foreach($yarr as $k=>$v){
			if($dnum=='')$dnum = $k;
			if($agentname==$v || $agentname==$k)$num = $k;
		}
		if($num==''){
			$num = $this->reimplat_devnum;
			if(isempt($num))$num = $dnum;
		}
		if(isempt($num))return returnerror('没有找到对应应用('.$agentname.')');
		if(is_string($wxarr))$wxarr = array('title'=>$wxarr);
		
		if(arrvalue($wxarr, 'url')){
			$barr = $this->sendtextcard($touid, $num, $wxarr);
		}else{
			$des  = arrvalue($wxarr,'description');
			$cont = $wxarr['title'];
			if($des)$cont.='<br>'.$des.'';
			$barr = $this->sendtext($touid, $num, $cont);
		}
		return $barr;
	}
}