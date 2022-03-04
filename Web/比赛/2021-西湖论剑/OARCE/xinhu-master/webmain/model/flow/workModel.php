<?php
class flow_workClassModel extends flowModel
{
	private $absfile = false;

	public function initModel()
	{
		$this->statearr		 = c('array')->strtoarray('待执行|blue,已完成|green,执行中|#ff6600,终止|#888888,验证未通过|#9D4FF7');
	}
	
	//自定义审核人读取
	protected function flowcheckname($num){
		$sid = '';
		$sna = '';
		if($num=='run'){
			$sid = $this->rs['distid'];
			$sna = $this->rs['dist'];
		}
		return array($sid, $sna);
	}
	
	//转办时要更新对应的执行人员
	protected function flowcheckbefore(){
		$up = array();
		if($this->checkiszhuanyi==1){
			$up['dist'] 	= $this->rs['syszb_name'];
			$up['distid'] 	= $this->rs['syszb_nameid'];
			$up['status'] 	= 3; //待执行状态
		}
		if($up)$up['update'] = $up;
		return $up;
	}
	
	public function flowrsreplace($rs, $slx=0){
		
		$zts 		= $rs['status'];
		$str 		= $this->getstatus($rs,'','',1);
		if($slx>=1){
			$projectid 	= (int)$rs['projectid'];
			$rs['projectid'] = '';
			if($projectid>0){
				$prs 		= $this->db->getone('[Q]project', $projectid);
				if($prs){
					$rs['projectid']=''.$prs['title'].'('.$prs['progress'].'%)';
				}
			}
		}
		if(!isempt($rs['enddt']) && !in_array($zts,array(1,2,5))){
			if(strtotime($rs['enddt'])<time())$rs['explain'].='<font color=red>(已超期)</font>';
		}
		//$rs['status']= $str;
		if($rs['score']==0)$rs['score']='';
		if($rs['mark']==0)$rs['mark']='';
		if($slx==1){
			$zhuid = (int)arrvalue($rs,'zhuid','0');
			$nrs   = arrvalue($rs,'file_content');
			if($zhuid>0 && isempt($nrs)){
				$rs['file_content'] = m('file')->getstr($this->mtable, $zhuid, 3);
				$this->absfile = true;
			}
		}
		return $rs;
	}
	
	protected function flowgetfields($lx)
	{
		if($this->absfile){
			return array('file_content'=>'关联文件');
		}
	}
	
	protected function flowchangedata(){
		$this->rs['stateid'] = $this->rs['state'];
	}
	
	
	protected function flowdatalog($arr)
	{
		$isaddlog	= 0;
		$uid 		= $this->adminid;
		$ispingfen	= 0;
		$distid 	= ','.$this->rs['distid'].',';
		$zt 		= $this->rs['stateid'];
		if($this->contain($distid, ','.$this->adminid.',') && ($zt==0||$zt==2)){
			$isaddlog = 1;
		}
		
		$arr['isaddlog'] = $isaddlog; //是否可以添加日志记录
		$arr['state'] 	 = $this->rs['stateid'];
		
		//判断是否可以督导评分
		$where  = $this->ddwhere($uid);
		if($this->rows("`id`='$this->id' and `status`=1 and `mark`=0 $where")==1){
			$ispingfen		= 1;
		}
		$arr['ispingfen'] 	= $ispingfen; //是否可以评分
		$arr['score'] 		= $this->rs['score'];
		return $arr;
	}
	
	protected function flowsubmit($na, $sm)
	{
		//$this->push($this->rs['distid'], '', '[{type}]{title}');//提交给对应人提醒
		$this->push($this->rs['ddid'], '', '{optname}提交任务[{type}.{title}]分配给:{dist}，需要你督导','任务督导');//提醒给督导人员
		
		$zt  = 0;
		if(!isempt($this->rs['distid']))$zt = 3;//待执行的状态值
		$this->updatestatus($zt);
		
	}
	
	protected function flowaddlog($a)
	{
		//提交报告时发送给创建人和督导人员
		if($a['name']=='进度报告'){
			$state 	= $a['status'];
			$arr['state'] = $state;
			$cont = ''.$this->adminname.'添加[{type}.{title}]的任务进度,说明:'.$a['explain'].'';
			if($state=='1')$cont='[{type}.{title}]任务'.$this->adminname.'已完成';
			$toid 	= $this->rs['optid'];
			$ddid	= $this->rs['ddid'];
			if(!isempt($ddid))$toid.=','.$ddid.'';
			$this->push($toid, '任务', $cont);
			$this->update($arr, $this->id);
		}
		if($a['name']=='指派给' || $a['name']=='转发'){
			$cname 	 = $this->rock->post('changename');
			$cnameid = $this->rock->post('changenameid');
			$state = '0';
			$arr['state'] 	= $state;
			$arr['distid'] 	= $cnameid;
			$arr['dist'] 	= $cname;
			$this->update($arr, $this->id);
			$this->push($cnameid, '任务', ''.$this->adminname.'指派任务[{type}.{title}]给你');
		}
		if($a['name'] == '任务评分'){
			$fenshu	 = (int)$this->rock->post('fenshu','0');
			$this->push($this->rs['distid'], '任务', ''.$this->adminname.'评分[{type}.{title}],分数('.$fenshu.')','任务评分');
			$this->update(array(
				'mark' => $fenshu
			), $this->id);
		}
	}
	
	private function ddwhere($uid)
	{
		$downid = m('admin')->getdown($uid, 1);
		$where  = 'and `ddid`='.$uid.'';
		if($downid!='')$where  = 'and (('.$uid.' in(`ddid`)) or (ifnull(`ddid`,\'0\')=\'0\' and `distid` in('.$downid.')) or (ifnull(`ddid`,\'0\')=\'0\' and `optid`='.$uid.'))';
		return $where;
	}
	
	protected function flowbillwhere($uid, $lx)
	{
		$where		= '';
		$projcetid 	= (int)$this->rock->post('projcetid');
		if($projcetid>0)$where='and `projectid`='.$projcetid.'';
		
		return array(
			'keywhere' => $where,
			'order' => '`optdt` desc'
		);
	}
	
	/**
	*	提醒快过期的任务
	*	$txsj 提前几天提醒
	*/
	public function tododay($txsj = 1)
	{
		$dtobj= c('date');
		$dt   = $this->rock->date;
		$rows = $this->getrows("`status` in(0,3,4) and ifnull(`distid`,'')<>'' and `enddt`>='$dt'");
		$arr  = array();
		foreach($rows as $k=>$rs){
			$jg = $dtobj->datediff('d', $this->rock->date, $rs['enddt']);
			if($jg <= $txsj){
				$dista = explode(',', $rs['distid']);
				foreach($dista as $distid){
					if(!isset($arr[$distid]))$arr[$distid] = array();
					$tis = ''.$jg.'天后截止';
					if($jg == 0)$tis = '需今日完成';
					$arr[$distid][]= '['.$rs['type'].']'.$rs['title'].'('.$tis.');';
				}
			}
		}
		foreach($arr as $uid => $strarr){
			$this->flowweixinarr['url'] = $this->getwxurl();//设置微信提醒的详情链接
			$str = '';
			foreach($strarr as $k=>$str1){
				if($k>0)$str.="\n";
				$str.="".($k+1).".$str1";
			}
			if($str != '')$this->push($uid, '', $str, '任务到期提醒');
		}
	}
	
	//任务待办格式推送
	protected function flownexttodo($type)
	{
		if($type=='daiban'){
			return array(
				'cont' => '标题：{title}\n创建人：{optname}\n任务类型：{type}\n等级：{grade}',
				'title'=> '任务待处理'
			);
		}
		
	}
	
}