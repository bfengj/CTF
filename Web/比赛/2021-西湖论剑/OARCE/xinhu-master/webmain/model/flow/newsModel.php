<?php
class flow_newsClassModel extends flowModel
{
	private $readunarr = array();//未读人员
	
	public function initModel()
	{
		$this->logobj = m('log');
		$this->htmlobj = c('html');
	}
	
	protected function flowchangedata(){
		$cont 	= c('html')->replace($this->rs['content']);
		$fm 	= $this->rs['fengmian'];
		if(!isempt($fm)){
			$cont='<div align="center"><img src="'.$this->rock->gethttppath($fm).'"></div>'.$cont.'';
		}
		$url	= $this->rs['url'];
		if(!isempt($url))$cont.='<div><a href="'.$url.'">查看原文&gt;&gt;</a></div>';
		$this->rs['content'] = $cont;
	}
	
	public function flowrsreplace($rs, $lx=0)
	{
		$content = $rs['content'];
		if($lx==2){
			unset($rs['content']);
			$rs['smallcont'] = $this->neircong($content);
		}
		return $rs;
	}
	//移动端列表
	public function flowrsreplace_we($row, $rs)
	{
		if(!isempt($rs['fengmian']))$row['picurl'] = $rs['fengmian'];	
		return $row;
	}
	public function flowwesearchdata($lx)
	{
		if($lx==1)return $this->option->getselectdata('newstype', true);
		return array(
			'typename' => '所有分类',
			'searchmsg' => '新闻标题/分类',
		);
	}
	
	protected function flowsubmit($na, $sm)
	{
		if($this->rs['status']==1)$this->tisongtodo();
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
	
	private function neircong($nr)
	{
		$cont = $this->htmlobj->htmlremove($nr);
		$cont = $this->htmlobj->substrstr($cont,0, 50);
		if(strlen($cont)>40)$cont.='...';
		return $cont;
	}
	
	
	//发送推送通知
	private function tisongtodo()
	{
		//还没到展示时间就不发送提醒
		$zstart= arrvalue($this->rs, 'startdt');
		if(!isempt($zstart) && $zstart>$this->rock->date)return;

		
		$cont = $this->neircong($this->rs['content']);
		if(isempt($cont))$cont = $this->rs['title']; //为空时
		$this->push($this->rs['receid'], '', $cont, $this->rs['title'],1);
		
		//添加短信提醒，短信提醒
		if(arrvalue($this->rs,'issms')=='1'){
			$receid = $this->rs['receid'];
			if(isempt($receid))$receid = 'all';
			$qiannum= ''; 
			$tplnum	= 'gongsms';
			$params = array(
				'title' 	=> $this->rs['title'],
				'typename' 	=> $this->rs['typename'],
			);
			$url	= $this->getxiangurlx();
			c('xinhuapi')->sendsms($receid, $qiannum, $tplnum, $params, $url);
		}
	}
	
	
	protected function flowbillwhere($uid, $lx)
	{
		$key 	= $this->rock->post('key');
		$typeid 	= (int)$this->rock->post('typeid','0');
		$keywere= '';
		if(!isempt($key))$keywere.=" and (`title` like '%$key%' or `typename`='$key')";
		$whyere = '';
		//我和我未读
		if($lx=='my'){
			$whyere= "and (`startdt` is null or `startdt`<='{$this->rock->date}')";
			$whyere.= " and (`enddt` is null or `enddt`>='{$this->rock->date}')";
		}
		if($typeid>0){
			$typename=$this->option->getmou('name', $typeid);
			$whyere.=" and `typename`='$typename'";
		}
		
		return array(
			'order' 	=> '`istop` desc,`optdt` desc',
			'keywere' 	=> $keywere,
			'where' 	=> $whyere,
			'fields'	=> 'id,typename,optdt,title,optname,content,zuozhe,indate,recename,fengmian,mintou,`status`,`istop`,`appxs`'
		);
	}
	
	//去掉标题
	protected function flowdatalog($arr)
	{
		
		$arr['title'] 		= '';
		
		return $arr;
	}
}