<?php
class goodsClassAction extends Action
{
	
	public function initAction()
	{
		$this->goodsobj = m('goods');
	}
	
	private function gettypename($tid)
	{
		return $this->goodsobj->gettypename($tid);
	}
	
	public function aftershow($table, $rows)
	{
		$typearr = $depotarr = array();
		$mid 	 = (int)$this->post('mid','0');//根据主表出入库操作
		if($rows){
			$aid = '0';
			foreach($rows as $k=>$rs){

				$rows[$k]['typeid'] = $this->gettypename($rs['typeid']); 
				$aid.=','.$rs['id'].'';
				if($rs['stock']=='0')$rows[$k]['stock'] = '';
				if($rs['stock']<0)$rows[$k]['ishui']=1;
			}
			$rows = $this->pandian($aid, $rows);
		}else{
			if($mid>0){
				m('goodm')->update('state=1', $mid);
			}
		}
		if($this->loadci==1){
			$type	= (int)$this->post('type');
			$typearr= $this->option->getdata('kutype'.$type.'');
			$where  = m('admin')->getcompanywhere(1);
			$depotarr = m('godepot')->getall('1=1'.$where.'','id,depotname as name,depotnum','`sort`');
			foreach($depotarr as $k=>$rs){
				$depotarr[$k]['namea']= $rs['name'];
				$depotarr[$k]['name'] = ''.$rs['depotnum'].'.'.$rs['name'].'';
			}
		}
		return array(
			'rows' 		=> $rows,
			'typearr' 	=> $typearr,
			'depotarr' 	=> $depotarr,
		);
	}
	
	public function beforeshow($table)
	{
		$key 	= $this->post('key');
		$typeid = (int)$this->post('typeid');
		$mid 	= (int)$this->post('mid','0');//根据主表出入库操作
		$where 	 	= '';
		if($typeid != 0){
			$alltpeid = $this->option->getalldownid($typeid);
			$where .= ' and `typeid` in('.$alltpeid.')';
		}
		if($key!=''){
			$where .= " and (`name` like '%$key%' or `num` like '%$key%' or `guige` like '%$key%' or `xinghao` like '%$key%') ";
		}
		
		$where.=m('admin')->getcompanywhere(1);
		
		if($mid>0){
			/*
			$carro = m('goodn')->getall('mid='.$mid.' and `couns`<`count`');
			$typeids = '0';
			foreach($carro as $k=>$rs)$typeids.=','.$rs['aid'].'';
			$where .= ' and `id` in('.$typeids.')';
			*/
			return array(
				'where' => 'and b.`mid`='.$mid.' and  b.`couns`<b.`count`',
				'fields' => 'a.*,(b.`count`-b.`couns`)maxcount',
				'table' => '`[Q]goods` a left join `[Q]goodn` b on a.`id`=b.`aid`'
			);
		}
		
		return $where;
	}
	
	
	//盘点对应仓库库存计算
	private function pandian($aid,$rows)
	{
		if($this->post('atype')!='pan')return $rows;
		
		$ckarr	= m('goods')->getstock($aid, $this->post('dt'));
		foreach($rows as $k=>&$rs){
			$rs['stock'] = '';
			if(isset($ckarr[$rs['id']])){
				$kdsra= $ckarr[$rs['id']];
				$rs['stock'] = $kdsra[0]=='0'?'':$kdsra[0]; //总库存
				
				foreach($kdsra as $k1=>$v1){
					if($k1>0)$rs['stockdepotid'.$k1.''] = $v1=='0'?'':$v1; //对应仓库库存
				}
			}
		}
		
		return $rows;
	}
	
	public function xiangbeforeshow($table)
	{
		$key = $this->post('key');
		$dt  = $this->post('dt');
		$types    = $this->post('types');
		$typeid   = (int)$this->post('typeid', 0);
		$depotid  = (int)$this->post('depotid', 0);
		
		$where 	 = '';
		if($typeid>0){
			$alltpeid = $this->option->getalldownid($typeid);
			$where.=" and b.typeid in($alltpeid)";
		}
		if($key!=''){
			$where .= " and (b.`name` like '%$key%' or a.optname  like '%$key%' or c.depotname like '%$key%' )";
		}
		if($dt!=''){
			$where .= " and a.`applydt` like '$dt%' ";
		}
		if($depotid>0){
			$where .= " and a.`depotid`='$depotid'";
		}
		if($types){
			$typea = explode('_', $types);
			$where.= ' and a.type='.substr($typea[0],1).' and a.kind='.$typea[1].'';
		}
		
		$where .= m('admin')->getcompanywhere(1,'a.');
		$table	= '`[Q]goodss` a left join `[Q]goods` b on a.aid=b.id left join `[Q]godepot` c on a.depotid=c.id';
		$fields	= 'a.id,b.name,a.count,c.depotname,a.type,a.kind,a.status,a.optname,b.typeid,b.xinghao,b.guige,a.applydt,a.explain,a.mid';
		return array(
			'where' => $where,
			'table' => $table,
			'fields' => $fields,
		);
	}
	
	public function xiangaftershow($table, $rows)
	{
		$tyeparr = array();
		if($rows){
			$typearr0= $this->option->getdata('kutype0');
			$typearr1= $this->option->getdata('kutype1');
			$tyeparr['a0_3'] = '调拨入库';
			$tyeparr['a1_3'] = '调拨出库';
			foreach($typearr0 as $k=>$rs)$tyeparr['a0_'.$rs['value'].''] = $rs['name'];
			foreach($typearr1 as $k=>$rs)$tyeparr['a1_'.$rs['value'].''] = $rs['name'];
			$statusar= array('<font color=blue>待审核</font>','<font color=green>已审核</font>','<font color=red>审核未通过</font>');
			$typearr = array();
			
			foreach($rows as $k=>$rs){
				
				$tid = $rs['typeid'];
				if(isset($typearr[$tid])){
					$rows[$k]['typeid'] = $typearr[$tid];
				}else{
					$rows[$k]['typeid']	= $this->db->getpval('[Q]option','pid','name', $tid,'/','id',2);
					$typearr[$tid] = $rows[$k]['typeid'];
				}
				
				$skey = 'a'.$rs['type'].'_'.$rs['kind'].'';
				$kind = '';
				if(isset($tyeparr[$skey]))$kind = $tyeparr[$skey];
				$rows[$k]['kind']	= $kind;
				$rows[$k]['status']	= $statusar[$rs['status']];
				if($rs['mid']>0)$rows[$k]['checkdisabled'] = true;//有主表ID，不能删除
			}
		}
		$barr = array('rows' => $rows,'tyeparr'=>$tyeparr);
		return $barr;
	}
	
	/**
	*	删除出入库详情
	*/
	public function delxiangAjax()
	{
		$ids	= c('check')->onlynumber($this->post('id','0'));
		m('goodss')->delete("id in($ids) and `mid`=0");
		backmsg();
	}
	
	public function chukuoptAjax()
	{
		$dt 	= $this->post('dt');
		$type 	= (int)$this->post('type');
		$depotid= (int)$this->post('depotid');
		$kind 	= (int)$this->post('kind');
		$mid 	= (int)$this->post('mid','0');
		$sm 	= $this->post('sm');
		$cont 	= $this->post('cont');
		$sharr	= c('array')->strtoarray($cont);
		$arr['applydt'] = $dt;
		$arr['type'] 	= $type;
		$arr['kind'] 	= $kind;
		$arr['depotid'] = $depotid;
		$arr['explain'] = $sm;
		$arr['uid'] 	= $this->adminid;
		$arr['optid'] 	= $this->adminid;
		$arr['optdt'] 	= $this->now;
		$arr['comid'] 	= m('admin')->getcompanyid();
		$arr['optname'] = $this->adminname;
		$arr['status'] 	= 1;
		$aid 			= '0';
		
		$ndbs			= m('goodn');
		
		$mtype 			= -1;
		
		//根据主表出入库操作
		if($mid>0){
			$mrs 	= m('goodm')->getone("`id`='$mid' and `status`=1");
			if(!$mrs)return '该单据还未审核完成，不能出入库操作';
			//读取已入库数量
			$arwos = $ndbs->getall('`mid`='.$mid.' and `couns`<`count`');
			$ruks  = array();
			foreach($arwos as $k1=>$rs1){
				$ruks[$rs1['aid']] = array(
					'kes'   => floatval($rs1['count']) - floatval($rs1['couns']),//还可入库数
					'id'	=> $rs1['id'],
					'couns' => floatval($rs1['couns'])
				); 
			}
			$mtype = (int)$mrs['type']; //3就是调拨
			$arr['comid'] = $mrs['comid'];
		}
		
		//调拨必须先出库原来的
		if($mtype==3 && $depotid==$mrs['custid'])return '调拨出入库仓库不能相同';
		
		
		foreach($sharr as $k=>$rs){
			$arr['aid'] = $rs[0];
			$count = (int)$rs[1];
			$arr['depotid'] = $depotid;
			$arr['type'] 	= $type;
			$arr['explain'] = $sm;
			
			if($count<0)$count = 0-$count;
			
			if($mid>0){
				if(!isset($ruks[$arr['aid']]))continue;
				$shua = $ruks[$arr['aid']];
				if($count>$shua['kes'])$count=$shua['kes'];//超过
				$arr['mid'] = $mid;
			}
			
			
			if($count==0)continue;
			
			
			$arr['count'] = $count;
			if($type==1)$arr['count'] = 0- $arr['count'];//出库为负数
			
			$ussid = $this->db->record('[Q]goodss', $arr);
			$aid.=','.$rs[0].'';
			
			//更新已出入库的数量
			if($mid>0 && $ussid){
				$ndbs->update('`couns`=`couns`+'.$count.'', $shua['id']);
			}
			
			if($mtype==3){
				$arr['depotid'] = $mrs['custid']; //仓库
				$arr['type'] 	= 1; //出库
				$arr['count']	= 0 - $count;
				//$arr['explain']	= '调拨出库';
				$this->db->record('[Q]goodss', $arr);
			}
		}
		if($aid!='0')m('goods')->setstock($aid);
		if($mid>0){
			m('goods')->upstatem($mid);
		}
		return 'success';
	}
	
	
	
	
	
	
	
	
	
	
	//刷新库存
	public function reloadkcAjax()
	{
		m('goods')->setstock();
	}
	
	//出入库操作
	public function croptbeforeshow($table)
	{
		$key = $this->post('key');
		$where= '';
		if($key!=''){
			$where.=" and (b.`uname` like '%$key%' or b.`sericnum` like '$key%')";
		}
		$where .= m('admin')->getcompanywhere(1,'a.');
		return array(
			'where' => 'and a.`status`=1 and a.`state`<>1 '.$where.'',
			'table' => '`[Q]'.$table.'` a left join `[Q]flow_bill` b on a.id=b.mid and b.`table`=\''.$table.'\'',
			'fields' => 'a.id,a.applydt,a.optdt,a.`explain`,a.`state`,a.`type`,b.uname,b.sericnum,b.udeptname'
		);
	}
	public function croptaftershow($table, $rows)
	{
		$dgs 	= m('goods');
		$typeb = array('0'		,'1'	  ,'2'		,'3', '4','5'); 
		$typea = array('领用单' ,'采购单' ,'销售单'	,'调拨单', '归还单','退货单'); 
		$chux  = array('0','2');
		if($rows)foreach($rows as $k=>&$rs){
			$rs['typev'] = $rs['type'];
			$rs['type']  = arrvalue($typea, $rs['type']);
			$lx = 0; //入
			if(in_array($rs['typev'],$chux))$lx=1;
			$rs['state']  = $dgs->crkstate($rs['state'], $lx);
		}
		return array(
			'rows' 		=> $rows
		);
	}
	
	
	
	
	
	
	
	
	//根据仓库统计
	public function pdck_beforeshow($table)
	{
		$depotid = (int)$this->post('depotid');
		$where	  = 'and a.`depotid`='.$depotid.'';
		$key 	 = $this->post('key');
		$dt 	 = $this->post('dt');
		
		if($key!=''){
			$where .= " and (b.`name` like '%$key%' or b.`num` like '%$key%' or b.`guige` like '%$key%' or b.`xinghao` like '%$key%') ";
		}
		if($dt!=''){
			$where .= " and a.`applydt` <= '$dt'";
		}
		
		return array(
			'table' => '`[Q]goodss` a left join `[Q]goods` b on a.`aid`=b.`id`',
			'where' => $where,
			'fields'=> 'b.*,sum(a.`count`) as `stock`',
			'group' => 'a.`aid`'
		);
	}
	public function pdck_aftershow($table, $rows)
	{
		foreach($rows as $k=>$rs){
			$rows[$k]['typeid'] = $this->gettypename($rs['typeid']);
			if($rs['stock']<='0')$rows[$k]['ishui']=1;
		}
		return array(
			'rows' => $rows
		);
	}
	
	//打印二维码
	public function printewmAction()
	{
		$sid = c('check')->onlynumber($this->get('sid'));
		$rows= m('goods')->getall('id in('.$sid.')');
		
		foreach($rows as $k=>$rs){
			$rows[$k]['url'] = $this->jm->base64encode('task.php?a=x&num=goods&mid='.$rs['id'].'');
		}

		$this->assign('rows', $rows);
		$this->displayfile = ''.P.'/main/assetm/tpl_assetm_printewm.html';
		$this->title = '物品二维码打印';
	}
}