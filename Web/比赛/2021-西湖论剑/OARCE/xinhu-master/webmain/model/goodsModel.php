<?php
class goodsClassModel extends Model
{
	//$lx=0入库,$lx=1出
	public function crkstate($zt, $lx=0)
	{
		$arrs = array('入','出');
		$ztna = array('待'.$arrs[$lx].'库','已'.$arrs[$lx].'库','已部分'.$arrs[$lx].'库');
		$ztnc = array('red','green','#ff6600');
		
		return '<font color="'.$ztnc[$zt].'">'.$ztna[$zt].'</font>';
	}
	
	//设置库存
	public function setstock($id='', $lsx='1')
	{
		$where = '';
		if($id!='')$where=' and `aid` in('.$id.')';
		$sql = 'SELECT sum(count)stock,aid FROM `[Q]goodss` where `status`=1 '.$where.' GROUP BY aid';
		if($id=='')$this->update('stock=0','id>0');
		$rows= $this->db->getall($sql);
		foreach($rows as $k=>$rs){
			$this->update('`stock`='.$rs['stock'].'', $rs['aid']);
		}
	}
	
	//根据仓库日期获取库存
	public function getstock($id='', $dt='')
	{
		$where= '';
		if($id!='')$where='`aid` in('.$id.') and ';
		if($dt!='')$where.="`applydt`<='$dt' and "; //日期
		$sql = 'SELECT sum(count)stock,`aid`,`depotid` FROM `[Q]goodss` where '.$where.' `status`=1  GROUP BY `aid`,`depotid`';
		$rows= $this->db->getall($sql);
		$arra = array();
		foreach($rows as $k=>$rs){
			$aid = $rs['aid'];
			$arra[$aid][$rs['depotid']] = $rs['stock'];
			if(!isset($arra[$aid][0])) $arra[$aid][0]= 0;
			$arra[$aid][0]+=floatval($rs['stock']);
		}
		return $arra;
	}
	
	//待出入库数量
	public function getdaishu()
	{
		$where = m('admin')->getcompanywhere(1);
		return $this->db->rows('`[Q]goodm`','`status`=1 and `state`<>1 '.$where.'');
	}
	
	//判断是否存在相同库存
	public function existsgoods($rs, $id=0)
	{
		$where 	= "`id`<>".$id." and `typeid`=".$rs['typeid']." and `name`='".$rs['name']."' and ifnull(`guige`,'')='".$rs['guige']."' and ifnull(`xinghao`,'')='".$rs['xinghao']."'";
		$to 	= $this->rows($where);
		return $to>0;
	}
	
	public function getgoodstype($lx=0)
	{
		$dbs 	= m('option');
		$num  	= 'goodstype';
		if(ISMORECOM && $cnum=m('admin')->getcompanynum())$num.='_'.$cnum.'';
		
		$rowss  = $dbs->getdata($num);
		$rows	= array();
		$str1   = '	&nbsp;	&nbsp; ';
		if($lx==1)$str1='	';
		foreach($rowss as $k=>$rs){
			$rows[] = array(
				'name' => $rs['name'],
				'value' => $rs['id'],
			);
			$rowsa = $dbs->getdata($rs['id']);
			if($rowsa)foreach($rowsa as $k1=>$rs1){
				$rows[] = array(
					'name' => ''.$str1.'├'.$rs1['name'],
					'value' => $rs1['id'],
				);
			}
		}
		return $rows;
	}
	
	/**
	*	$lx=0默认，1领用销售，2采购，3调拨
	*/
	public function getgoodsdata($lx=0)
	{
		$typeid	= $this->rock->get('selvalue');
		$where 	= '1=1';
		if(!isempt($typeid)){
			$alltpeid = m('option')->getalldownid($typeid);
			$where = 'a.`typeid` in('.$alltpeid.')';
		}
		$stockarr = array();
		if($lx==3){
			$ckid = $this->rock->get('ckid');
			$aids = '0';
			if(!isempt($ckid)){
				$rowss= $this->db->getall("select `aid`,sum(`count`)as counts from `[Q]goodss` where `depotid`='$ckid' group by `aid`");
				foreach($rowss as $k=>$rs){
					$aids.=','.$rs['aid'].'';
					$stockarr[$rs['aid']] = $rs['counts'];
				}
			}
			$where.= ' and a.`id` in('.$aids.')';
		}
		$where .= m('admin')->getcompanywhere(1,'a.');
		$rowss  = $this->db->getall('select a.`id`,a.`name`,a.`xinghao`,a.`stock`,a.`price`,a.`unit`,b.`name` as `typename` from `[Q]goods` a left join `[Q]option` b on a.`typeid`=b.`id` where '.$where.'');
		$rows	= array();
		foreach($rowss as $k=>$rs){
			$name 	= $rs['name'];
			if(!isempt($rs['xinghao']))$name.='('.$rs['xinghao'].')';
			$stock	= $rs['stock'];
			if($lx==3){
				$stock = arrvalue($stockarr,$rs['id'],'0');
			}
			$baar	= array(
				'name' 	=> $name,
				'value' => $rs['id'],
				'price' => $rs['price'],
				'unit' => $rs['unit'],
				'stock' => $stock,
				'subname' => $rs['typename'],
			);
			if(($lx==1 || $lx==3) && $stock<='0'){
				$baar['disabled']= true;//领用没有库存了
				$baar['subname'].= ' 无库存';
			}
			$rows[] = $baar;
		}
		if($lx==0)return $rows;
		$selectdata = $this->getgoodstype(1);
		return array(
			'rows' => $rows,
			'selectdata'=>$selectdata
		);
	}
	
	/**
	*	主表goodm部分出入库状态更新
	*/
	public function upstatem($ids='')
	{
		$dbm  = m('goodm');
		$where= '';
		if($ids!='')$where="`id` in($ids) and ";
		$rows = $dbm->getall(''.$where.'`status`=1 and `state` in(0,2)');
		foreach($rows as $k=>$rs){
			$id 	= $rs['id'];
			$state 	= $rs['state'];
			$rsone 	= $this->db->getone('[Q]goodn','`mid`='.$id.'','sum(`count`)count,sum(`couns`)couns');
			$count 	= floatval($rsone['count']);
			$couns 	= floatval($rsone['couns']);
			if($couns>=$count){
				$zt = 1;
			}else if($couns==0){
				$zt = 0;
			}else{
				$zt = 2;
			}
			if($state!=$zt)$dbm->update('`state`='.$zt.'', $id);
		}
	}
	
	/**
	*	 供应商列表
	*/
	public function getgys()
	{
		$where  = m('admin')->getcompanywhere(1);
		$arows 	= m('customer')->getall('`status`=1 and `isgys`=1 '.$where.'','id as value,name');
		return $arows;
	}
	
	/**
	*	获取仓库下拉框
	*/
	public function godepotarr()
	{
		$where 	  = m('admin')->getcompanywhere(1);
		$depotarr = m('godepot')->getall('1=1'.$where.'','id,depotname as name,depotnum','`sort`');
		$rows 		= array();
		foreach($depotarr as $k=>$rs){
			$rows[] = array(
				'name' 	=> ''.$rs['depotnum'].'.'.$rs['name'].'',
				'value' => $rs['id'],
			);
		}
		return $rows;
	}
	
	/**
	*	根据主表Id获取申请物品信息, $glx 0原始数组,1字符串
	*/
	public function getgoodninfo($mid, $glx=0, $mgx=5)
	{
		$rows 	= $this->db->getall("select a.`count`,a.couns,a.`price`,b.`unit`,b.`num`,b.`name`,b.`guige`,b.`xinghao` from `[Q]goodn` a left join `[Q]goods` b on a.`aid`=b.`id` where a.`mid`='$mid' order by a.`sort`");
		$str 	= '';
		if($glx==1){
			foreach($rows as $k1=>$rs1){
				if($k1>$mgx)break;
				$str.=''.$rs1['name'].'';
				if(!isempt($rs1['xinghao']))$str.='('.$rs1['xinghao'].')';
				$str .=':'.$rs1['count'].''.$rs1['unit'].';';
			}
			return $str;
		}
		return $rows;
	}
	
	/**
	*	获取分类名称
	*/
	private $typenamearr= array();
	public function gettypename($tid)
	{
		if(isset($this->typenamearr[$tid])){
			return $this->typenamearr[$tid];
		}else{
			$one = $this->db->getone('[Q]option',$tid);
			$varr= '';
			if($one){
				$varr = $one['name'];
				if(!isempt($one['pid']) && $one['pid']){
					$one = $this->db->getone('[Q]option',$one['pid']);
					if($one && !contain($one['num'],'goodstype')){
						$varr = $one['name'].'/'.$varr.'';
					}
				}
			}
			return $varr;
		}
	}
}