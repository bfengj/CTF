<?php
class flow_gongClassModel extends flowModel
{
	private $readunarr = array();//未读人员
	
	public function initModel()
	{
		$this->logobj = m('log');
	}
	
	protected function flowchangedata(){
		$cont 	= c('html')->replace($this->rs['content']);
		$fm 	= $this->rs['fengmian'];
		if(!isempt($fm)){
			$cont='<div align="center"><img style="max-width:100%" src="'.$this->rock->gethttppath($fm).'"></div>'.$cont.'';
		}
		$url	= $this->rs['url'];
		if(!isempt($url))$cont.='<div><a href="'.$url.'">打开相应地址&gt;&gt;</a></div>';
		$this->rs['content'] = $cont;
	}
	
	public function flowrsreplace($rs, $lx=0)
	{
		$mintou		= (int)arrvalue($rs, 'mintou','0');
		if($lx==2){
			$zt = $this->logobj->isread($this->mtable, $rs['id'], $this->adminid);
			if($zt>0)$rs['ishui']=1;
		}
		if($lx==1){
			$receid = $rs['receid'];
			if(isempt($receid))$receid='all';
			$barr 	= m('log')->getreadshu($this->mtable, $rs['id'],$receid , $rs['optdt'], $this->adminmodel);
			foreach($barr as $k=>$v)$rs[$k]=$v;
			$this->readunarr = $barr['wduarr'];
		}
		if($mintou>0){
			$rs['title'] .='(投票)';
		}
		return $rs;
	}
	
	protected function flowsubmit($na, $sm)
	{
		if($this->rs['status']==1)$this->tisongtodo();
	}
	
	//移动端列表
	private $ydarr = false;
	public function flowrsreplace_we($row, $rs)
	{
		if(!isempt($rs['fengmian']))$row['picurl'] = $rs['fengmian'];
		if($this->ydarr===false)$this->ydarr	= explode(',', m('log')->getread('infor', $this->adminid));
		
		if(!in_array($rs['id'], $this->ydarr)){
			$row['statustext'] 	= '未读';
			$row['statuscolor'] = '#ED5A5A';
		}else{
			$row['ishui']		= 1;
		}
		
		return $row;
	}
	public function flowwesearchdata($lx)
	{
		if($lx==1)return $this->option->getselectdata('gongtype', true);
		return array(
			'typename' => '所有通知',
			'searchmsg' => '通知标题/分类',
		);
	}
	
	//审核完成后发通知
	protected function flowcheckfinsh($zt)
	{
		if($zt==1)$this->tisongtodo();
	}
	
	public function getreceids($receid, $whe='')
	{
		$receid 	= $this->adminmodel->gjoin($receid,'ud','where');
		if($receid=='' || $receid=='all'){
			$where 	= '';
		}else{
			$where	= 'and id>0 and ('.$receid.')';
		}
		$ids 		= '';
		$rows 		= $this->adminmodel->getall('`status`=1 '.$where.' '.$whe.'','id');
		foreach($rows as $k=>$rs)$ids.=',u'.$rs['id'].'';
		if($ids!='')$ids = substr($ids, 1);
		
		return $ids;
	}
	
	
	//发送推送通知
	private function tisongtodo()
	{
		//还没到展示时间就不发送提醒
		$zstart= arrvalue($this->rs, 'zstart');
		if(!isempt($zstart) && $zstart>$this->rock->date)return;
		
		$h 	  = c('html');
		$cont = $h->htmlremove($this->rs['content']);
		$cont = $h->substrstr($cont,0, 50);
		if(strlen($cont)>40)$cont.='...';
		if(isempt($cont))$cont = $this->rs['title']; //为空时
		$this->push($this->rs['receid'], '通知公告', $cont, $this->rs['title'],1);
		
		//添加短信提醒，短信提醒
		if(arrvalue($this->rs,'issms')=='1'){
			$receid = $this->rs['receid'];//接收人ID，可以为部门合聚，如d2,u1(必须)
			if(isempt($receid))$receid = 'all'; //为空就是全部人
			$qiannum= ''; //【系统→短信管理→短信签名】下获取,如没有自己的签名默认【信呼OA】
			$tplnum	= m('option')->getval('gongsmstpl', 'gongsms');//到【数据选项→行政选项】下设置通知短信模版编号
			$params = array(
				'title' 	=> $this->rs['title'],
				'typename' 	=> $this->rs['typename'],
			);
			$url	= $this->getxiangurlx();//详情连接地址(选填)，短信模版有url就需要填写
			c('xinhuapi')->sendsms($receid, $qiannum, $tplnum, $params, $url);
		}
	}
	
	protected function flowgetoptmenu($opt)
	{
		$to = m('log')->isread($this->mtable, $this->id);
		return $to<=0;
	}
	
	protected function flowoptmenu($ors, $crs)
	{
		$table 	= $this->mtable;
		$mid	= $this->id;
		$uid	= $this->adminid;
		$lx 	= $ors['num'];
		$log 	= m('log');
		if($lx=='yd'){
			$log->addread($table, $mid, $uid);
		}
		if($lx=='allyd'){
			$ydid 	= $log->getread($table, $uid);
			$where	= "id not in($ydid)";
			$meswh	= m('admin')->getjoinstr('receid', $uid);
			$where .= $meswh;
			$rows 	= m($table)->getrows($where,'id');
			foreach($rows as $k=>$rs)$log->addread($table, $rs['id'], $uid);
		}
	}

	
	protected function flowbillwhere($uid, $lx)
	{
		$key 	= $this->rock->post('key');
		$typeid 	= (int)$this->rock->post('typeid','0');
		$keywere= '';
		$whyere = '';
		if(!isempt($key))$keywere.=" and (`title` like '%$key%' or `typename`='$key')";
		//我和我未读
		if($lx=='my' || $lx=='wexx'){
			$whyere= "and (`zstart` is null or `zstart`<='{$this->rock->date}')";
			$whyere.= " and (`zsend` is null or `zsend`>='{$this->rock->date}')";
		}
		
		if($typeid>0){
			$typename=$this->option->getmou('name', $typeid);
			$whyere.=" and `typename`='$typename'";
		}
		
		return array(
			'order' 	=> '`istop` desc,`optdt` desc',
			'keywere' 	=> $keywere,
			'where' 	=> $whyere,
			'fields'	=> 'id,typename,optdt,title,optname,zuozhe,indate,recename,fengmian,mintou,`status`,`istop`,`appxs`'
		);
	}
	
	
	//显示投票
	protected function flowdatalog($arr)
	{
		$istoupiao	= 0;
		$mintou		= (int)arrvalue($this->rs, 'mintou','0');
		$maxtou		= (int)arrvalue($this->rs, 'maxtou','0');
		$status		= (int)arrvalue($this->rs, 'status','0');
		$touarr		= array();
		$logarr 	= array();
		if($mintou>0){
			$istoupiao	= 4;
			$toutype 	= 0;//不需要投票，1已投票，2未投票
			$receid 	= $this->rs['receid'];
			if(isempt($receid)){
				$istoupiao = 1;
			}else{
				$bo		= $this->adminmodel->containjoin($receid, $this->adminid);
				if($bo)$istoupiao = 1;
			}
			$zongps				= 0;
			$touitems			= m('infors')->getall('mid='.$this->id.'','*','`sort`');
			foreach($touitems as $k=>$rs){
				$zongps+=floatval($rs['touci']);
				$touitems[$k]['bili'] = 0;
			}
			if($zongps>0)foreach($touitems as $k=>$rs){
				$touitems[$k]['bili'] = $this->rock->number(floatval($rs['touci'])/$zongps * 100);
			}
			
			
			$touarr['touitems'] = $touitems;
			
			if($istoupiao==1){
				if($this->rs['startdt']>$this->rock->now)$istoupiao = 2; //未开始
				if($this->rs['enddt']<$this->rock->now)$istoupiao = 3; //结束
			}
			
			$touarr['type']		= ($mintou==1 && $maxtou==1)? 'radio': 'checkbox';
		
			//判断我是否投票了
			if($istoupiao==1 && $status==1){
				$towheer	= $this->mwhere." and `name`='投票' and `checkid`='$this->adminid'";
				if($this->flogmodel->rows($towheer)>0){
					$toutype = 1; 
					$istoupiao = 5;
				}else{
					$toutype = 2; //未投票
				}
			}
			$touarr['toutype'] = $toutype;
			
			//判断是否可以显示结果
			$showtou	= 0;
			if($istoupiao!=2 && $status==1 && ($toutype==1 || $toutype==0))$showtou = 1;
			if($showtou==0){
				foreach($arr['logarr'] as $k1=>$rs1){
					if($rs1['actname']!='投票')$logarr[] = $rs1;
				}
			}
			
			$touarr['showtou'] = $showtou;
			$touarr['mintou'] = $mintou;
			$touarr['maxtou'] = $maxtou;
		}
		
		$toupiaoarrr		= explode(',',',<font color=blue>投票进行中</font>,<font color=#ff6600>未开始</font>,<font color=#888888>已结束</font>,<font color=#888888>你不在投票对象中</font>,<font color=green>已投票过了</font>');
		
		
		$arr['istoupiao'] 	= $istoupiao; //投票状态0不能,1可以，2未开始，3结束 4你不需要投票
		$arr['toupiaostatus'] 	= $toupiaoarrr[$istoupiao];
		$arr['title'] 		= '';
		$arr['touarr'] 		= $touarr;
		if($logarr)$arr['logarr'] 	= $logarr;
		
		
		$arr['readunarr'] 			= $this->readunarr;//读取未查阅
		
		return $arr;
	}
}