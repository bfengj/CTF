<?php
//问卷调查模块接口问卷
class flow_wenjuanClassModel extends flowModel
{
	public $inputwidth	= 950;
	
	private $readunarr = array();//未读人员
	
	
	//替换读取统计
	public function flowrsreplace($rs, $lx=0)
	{
		
		if($lx==2 && $this->adminid==$rs['uid']){
			$where = $this->adminmodel->gjoin($rs['receid'],'','where');
			$where.= " and `workdate`<='".$rs['startdt']."' and `status`=1";
			$yito  = $this->adminmodel->rows($where);
			$yato  = 0;
			if(!isempt($rs['dauserids']))$yato = count(explode(',', $rs['dauserids']));
			$temp_total = ''.$yato.'/'.$yito.'';
			
			$rs['temp_total'] = $temp_total;
		}
		if($rs['enddt']<$this->rock->date)$rs['ishui']=1;
		
		//未查阅人
		if($lx==1){
			$barr 	= m('log')->getreadshu($this->mtable, $rs['id'],$rs['receid'] , $rs['startdt'], $this->adminmodel);
			foreach($barr as $k=>$v)$rs[$k]=$v;
			$this->readunarr = $barr['wduarr'];
		}
		
		return $rs;
	}
	
	//作废或删除时
	protected function flowzuofeibill($sm)
	{
		m('wenjuau')->delete("`mid`='$this->id'");
	}
	
	
	protected function flowdatalog($arr)
	{
		
		$arr['title'] 		= '';
		$itemarr			= m('wenjuat')->getall("`mid`='$this->id'",'*','sort asc');
		$suid		= (int)$this->rock->get('suid','0');
		if($suid==0)$suid = $this->adminid;
		
		//读取我提交的结果
		$tjrows 			= m('wenjuau')->getall("`mid`='$this->id' and `optid`='$suid'");
		$arr['tjcount']		= count($tjrows);//是否提交过
		$hlx	= $this->rock->get('hlx'); 
		foreach($itemarr as $k=>$rs)$itemarr[$k]['daan']='';
		
		//显示汇总
		if($hlx=='true'){
			$xuan= explode(',','a,b,c,d,e,f,g,h,i,k');
			$zongtiarr	= m('wenjuau')->getall("`mid`='$this->id'");
			foreach($itemarr as $k=>$rs){
				$showcont = '';
				$piaoshu  = 0;
				foreach($xuan as $xua)$itemarr[$k]['daan'.$xua.'']=0;
				$itemarr[$k]['zong'] = 0;
				foreach($zongtiarr as $k1=>$rs1){
					if($rs['id']==$rs1['sid']){
						if($rs['itemtype']=='2'){
							$showcont.='<div>'.$rs1['optname'].'：'.$rs1['conts'].' <span style="font-size:12px;color:#888888">('.$rs1['optdt'].')</font></div>';
						}else{
							$conts = ','.$rs1['conts'].',';
							foreach($xuan as $xua){
								if(contain($conts,','.$xua.',')){
									$itemarr[$k]['daan'.$xua.'']++;
									$itemarr[$k]['zong']++;
								}
							}
						}
					}
				}
				$itemarr[$k]['showcont'] = $showcont;
			}
		}else if($arr['tjcount']>0){
			$rand = $tjrows[0]['rand'];
			foreach($itemarr as $k=>$rs){
				foreach($tjrows as $k1=>$rs1){
					if($rs1['rand']==$rand && $rs1['sid']==$rs['id']){
						$itemarr[$k]['daan'] = $rs1['conts'];
					}
				}
			}
		}
		
		$arr['readunarr'] 	= $this->readunarr;//读取未查阅
		$arr['itemarr']		= $itemarr;
		$arr['showname']	= $this->adminmodel->getmou('name', $suid);
		$arr['hlx']		= $hlx;
		return $arr;
	}
	
	//显示调查内容
	public function showitem()
	{
		
	}
	
	/**
	*	提交问卷了
	*/
	public function submitwenjuan()
	{
		if(isset($this->rs['status']) && $this->rs['status']!='1')return '记录审核未完成';
		if($this->rs['startdt']>$this->rock->date)return '还没开始呢';
		if($this->rs['enddt']<$this->rock->date)return '已经结束了哦';
		$itemarr	= m('wenjuat')->getall("`mid`='$this->id'",'*','sort asc');
		$rand 		= rand(100000,999999);
		$dbs 		= m('wenjuau');
		if($dbs->rows("`mid`='$this->id' and `optid`='$this->adminid'")>0)return '已经提交过了哦';
		if(!isempt($this->rs['dauserids'])){
			$dauserids	= explode(',', $this->rs['dauserids']);
			if(!in_array($this->adminid, $dauserids))$dauserids[] = $this->adminid;
		}else{
			$dauserids  = array($this->adminid);
		}
		foreach($itemarr as $k1=>$rs1){
			$tyname = 'itemname_'.$rs1['id'].'';
			$tyvale = $this->rock->post($tyname);
			if(isempt($tyvale))return '选项'.($k1+1).'你还没选呢';
			$itemarr[$k1]['tyvale'] = $tyvale;
		}
		
		foreach($itemarr as $k1=>$rs1){
			$uarr = array('mid'=>$this->id);
			$uarr['sid'] = $rs1['id'];
			$uarr['comid'] = $this->rs['comid'];
			$uarr['conts'] = $rs1['tyvale'];
			$uarr['optid'] = $this->adminid;
			$uarr['optname'] = $this->adminname;
			$uarr['optdt'] = $this->rock->now;
			$uarr['rand'] = $rand;
			$dbs->insert($uarr);
		}

		$this->update(array(
			'dauserids' => join(',', $dauserids)
		), $this->id);
		
		$this->numtodosend('wmsubmit','提交问卷');
		
		return 'ok';
	}
}