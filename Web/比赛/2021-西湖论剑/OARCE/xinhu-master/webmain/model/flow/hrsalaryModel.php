<?php
class flow_hrsalaryClassModel extends flowModel
{
	protected $flowviewufieds	= 'xuid';
	
	public $floweditother	= true; //可编辑uid别人的单据
	
	//薪资是个严格的模块，只能设置权限后才可以查看，不管是不是管理员
	protected function flowbillwhere($uid, $lx)
	{
		$key  	= $this->rock->post('key');
		$dt  	= $this->rock->post('dt');
		$ispay  = $this->rock->post('ispay');
		$isturn  = $this->rock->post('isturn');
		$where 	= '';
		$where 	= $this->viewmodel->viewwhere($this->moders, $this->adminid, $this->flowviewufieds, 1);
		if($lx=='faf'){
			$where.=' and a.`status`=1';
		}
		if($ispay!='')$where.=' and a.`ispay`='.$ispay.'';
		if($isturn!='')$where.=' and a.`isturn`='.$isturn.'';
		
		//自己部门下的
		if($lx=='dept'){
			$uidall = $this->adminmodel->getdown($uid);
			if(isempt($uidall))$uidall='0';
			$where.=' and a.`xuid` in('.$uidall.')';
		}
	//echo $where;
		
		//if($key!='')$where.=" and (c.deptallname like '%$key%' or a.uname like '%$key%' or a.ranking like '%$key%' )";
		if($dt!='')$where.=" and a.`month`='$dt'";
		return array(
			'where' => $where,
			'orlikefields' => 'c.`deptallname`,a.`uname`,a.`ranking`',
			'tableleft' => '`[Q]userinfo` c on a.`xuid`=c.`id`',
			'fieldsleft'=> 'c.`num`,c.deptallname,c.`bankname`,c.`banknum`',
			'order' 	=> 'a.`month` desc,a.`id` asc',
		);
	}
	
	public function flowrsreplace($rs, $lx=0){
		$s = '<font color=red>待发放</font>';
		$rs['ispays']	= $rs['ispay'];
		if($rs['ispay']==1)$s = '<font color=green>已发放</font>';
		$rs['ispay'] = $s;
		
		$s = '<font color=red>待核算</font>';
		if($rs['isturn']==1)$s = '<font color=green>已核算</font>';
		$rs['isturnss'] = $s;
		
		//详情展示时
		if($lx==1){
		}
		
		return $rs;
	}
	
	private function fafang()
	{
		$this->addlog(array(
			'name' => '发放'
		));
		$this->update('ispay=1', $this->id);
		m('reim')->asynurl('asynrun','salaryff', array(
			'id' => $this->id
		));//异步通知给人员
	}
	
	public function todouser()
	{
		$this->push($this->rs['xuid'],'工资条','您['.$this->rs['month'].']月份薪资已发放，请注意查看对账。','薪资发放通知');
	}
	
	public function gongzifafang($sid)
	{
		$sarrid = explode(',',$sid);
		foreach($sarrid as $id){
			$this->loaddata($id, false);
			if($this->rs['status']==1 && $this->rs['ispay']==0)$this->fafang();
		}
	}
	
	//一键生成薪资
	public function createdata($month)
	{
		$lmonth = c('date')->adddate($month.'-01','m',-1, 'Y-m');
		$swer 	= m('admin')->monthuwhere($month);
		$where 	= "`month`<'$month' and xuid in(select id from `[Q]userinfo` where 1=1 $swer) and `xuid` not in(select xuid from `[Q]".$this->mtable."` where `month`='$month')";
		$addfes = 'base,postjt';//基本工资跟岗位工资
		
		$adees	= explode(',', $addfes);
		$rows 	= $this->db->getall("select * from `[Q]hrsalary` where $where order by `month` desc");
		$count  = 0;
		$xuarr  = array();
		foreach($rows as $k=>$rs){
			unset($rs['id']);
			$xuid	= $rs['xuid'];
			if(isset($xuarr[$xuid]))continue;
			$count++;
			$xuarr[$xuid] = 1;
			$arr 	= array();
			$arr1 	= array(
				'xuid' 		=> $xuid,
				'uname' 	=> $rs['uname'],
				'ranking' 	=> $rs['ranking'],
				'udeptname' => $rs['udeptname'],
				'uid' 		=> $this->adminid,
				'optid' 	=> $this->adminid,
				'optname' 	=> $this->adminname,
				'optdt' 	=> $this->rock->now,
				'applydt' 	=> $this->rock->date,
				'status' 	=> 0,
				'isturn' 	=> 0,
				'month' 	=> $month,
			);
			foreach($adees as $adees1)$arr[$adees1]=$rs[$adees1];
			
			foreach($arr1 as $k1=>$v1)$arr[$k1]=$v1;
			$arr['explain']='一键生成参考了'.$rs['month'].'月份的工资';
			
			//读取固定值的
			$farrr = $this->getfiearrs($xuid, $month);
			foreach($farrr as $k1=>$rs1){
				foreach($rs1['fieldsarr'] as $k2=>$rs2){
					if($rs2['type']=='0')$arr[$rs2['fields']] = $rs2['devzhi'];
					
					if($rs2['gongsi']=='last')$arr[$rs2['fields']] = $rs[$rs2['fields']]; //读取上月
				}
			}
			
			$arr['jxdf'] 	= '0';
			$arr['jiansr'] 	= '0';
			$arr['otherzj'] = '0';
			$arr['otherjs'] = '0';
			
			/*
			$money 	= 0;
			
			
			foreach($adees as $fid){
				$arr[$fid] = $rs[$fid];
				$money += floatval($rs[$fid]);
			}
			$money -= floatval($rs['socials']);
			$arr['money']	= $money;*/
		
			$this->insert($arr);
		}
		return '成功生成'.$count.'条';
	}
	
	//导入数据的测试显示
	public function flowdaorutestdata()
	{
		$barr = array(
			'uname' 		=> '貂蝉',
			'month' 		=> '[2017-08]',
			'base' 			=> '1700',
			'skilljt' 		=> '3500',
			'telbt' 		=> '0',
			'travelbt' 		=> '0',
			'postjt' 		=> '500',
			'reward' 		=> '0',
			'jiabans' 		=> '0',
			
			'punish' 		=> '0', //处罚
			'socials' 		=> '0', 
			'taxes' 		=> '0', 
			'money' 		=> '5700', 
			'explain' 		=> '本月薪资', 
			'isturn' 		=> '是', 
			'status' 		=> '是',
			
		);
		$barr1 = array(
			'uname' 		=> '大乔',
			'month' 		=> '[2017-08]',
			'base' 			=> '1700',
			'skilljt' 		=> '3000',
			'telbt' 		=> '0',
			'travelbt' 		=> '0',
			'postjt' 		=> '500',
			'reward' 		=> '0',
			'jiabans' 		=> '0',
			
			'explain' 		=> '本月薪资',
			
			'punish' 		=> '0', //处罚
			'socials' 		=> '0', 
			'taxes' 		=> '0', 
			'money' 		=> '5200', 
			'isturn' 		=> '是', 
			'status' 		=> '是', 
			
		);
		$barr2 = array(
			'uname' 		=> '小乔',
			'month' 		=> '[2017-08]',
			'base' 			=> '1700',
			'skilljt' 		=> '2500',
			'telbt' 		=> '0',
			'travelbt' 		=> '0',
			'postjt' 		=> '500',
			'reward' 		=> '0',
			'jiabans' 		=> '0',
			
			'explain' 		=> '导入',
			
			'punish' 		=> '5', //处罚
			'socials' 		=> '0', 
			'taxes' 		=> '0', 
			'money' 		=> '4695', 
			'isturn' 		=> '是', 
			'status' 		=> '是', 
			
		);
		return array($barr,$barr1,$barr2);
	}
	
	//导入之前判断
	public function flowdaorubefore($rows)
	{
		$inarr	= array();
		$uarra	= array();
		foreach($rows as $k=>$rs){
			$name 	= $rs['uname'];
			
			$month 	= str_replace('[','', $rs['month']);
			$month 	= substr(str_replace(']','', $month),0,7);
			
			$arr 	= $rs;
			$urs 	= $this->adminmodel->getone("`name`='$name'");
			if(!$urs)continue;
			
			$to 	= $this->rows("`xuid`='".$urs['id']."' and `month`='$month'");
			if($to>0)continue;//已经存在了
			
			$arr['month'] = $month;
			$arr['xuid'] = $urs['id'];
			$arr['udeptname'] = $urs['deptname'];
			$arr['ranking'] = $urs['ranking'];
			
			$arr['isturn']  = (arrvalue($arr,'isturn')=='是') ? 1 : 0;
			$arr['status']  = (arrvalue($arr,'status')=='是') ? 1 : 0;
			$arr['ispay']  	= (arrvalue($arr,'ispay')=='是') ? 1 : 0;
			
			if($arr['status']==1)$arr['isturn'] = 1;
			
			$inarr[] = $arr;
		}
		
		return $inarr;
	}
	
	//导入后处理,未审核需要提交审核
	public function flowdaoruafter($drdata=array())
	{
		foreach($drdata as $k=>$rs){
			//
			//if($rs['status']==0 && $rs['isturn']==1){
			//	$id = $rs['id'];
			//	$this->loaddata($id, false);
			//	$this->submit('提交');
			//}
		}
	}
	
	//读取薪资模版上的
	private $pipeibarr = array();
	public function getfiearrs($uid, $month, $bo=true)
	{
		if($this->pipeibarr && $bo)return $this->pipeibarr;
		$month= substr($month, 0, 7);
		$rows = m('hrsalarm')->getall("`status`=1 and `startdt`<='$month' and `enddt`>='$month'",'*','`sort`');
		$rowa = array();
		foreach($rows as $k=>$rs){
			$rs['xuhao'] = $k+1;
			$rowa[$rs['atype']][] = $rs;
		}
		$kqob = m('kaoqin');
		$dbs  = m('hrsalars');
		$garr = array();
		foreach($rowa as $klx=>$carr){
			$xu = $kqob->getpipeimid($uid, $carr, 'xuhao',0);
			if($xu>0){
				$nrsaa  = $rows[$xu-1];
				$nrsaa['fieldsarr'] = $dbs->getall('mid='.$nrsaa['id'].'','*','`sort`');
				$garr[] = $nrsaa;
			}
		}

		if($bo)$this->pipeibarr = $garr; //最后匹配到的模版
		
		//echo $xu;
		return $garr;
	}
	
	public function flowgetfields($lx)
	{
		$farr = $this->flowfieldarr($this->fieldsarra, 3);
		$barr = array();
		foreach($farr as $k=>$rs){
			if(isset($rs['iszs']) && $rs['iszs']==2)$barr[$rs['fields']]=$rs['name'];
		}
		return $barr;
	}
	
	//先运行这个$lx=0,1移动端,2保存,3展示
	public function flowfieldarr($farr, $lx)
	{
		$mid  = (int)$this->rock->get('mid','0');
		$demonth = c('date')->adddate($this->rock->date,'m',-1,'Y-m');
		$dexuid	 = $this->adminid;
		if($mid>0 && $mrs=$this->getone($mid)){
			$demonth = $mrs['month'];
			$dexuid  = $mrs['xuid'];
		}
		
		$month= $this->rock->post('month', $demonth);
		$xuid = (int)$this->rock->post('xuid', $dexuid);
		
		$cfarr= $this->getfiearrs($xuid, $month);
		$fnar = array();
		foreach($cfarr as $k=>$rs){
			foreach($rs['fieldsarr'] as $k1=>$rs1)$fnar[$rs1['fields']]=$rs1;
		}
		$urs  = $this->adminmodel->getone($xuid);
		
		foreach($farr as $k=>$rs){
			$farr[$k]['suantype']=-1;
			$fid 	= $rs['fields'];
			if(isset($fnar[$fid])){
				$nfrs = $fnar[$fid];
				if($nfrs['gongsi']=='last')$nfrs['gongsi']='';
				$farr[$k]['dev'] 	 = $nfrs['devzhi'];
				$farr[$k]['gongsi']  = $nfrs['gongsi']; //公式
				$farr[$k]['islu']	 = 1;
				$farr[$k]['iszs']	 = 2;
				$farr[$k]['suantype']= $nfrs['type'];
			}
			if($fid=='month'){
				$farr[$k]['dev'] = $month;
			}
			if($fid=='uname'){
				$farr[$k]['dev'] = ''.$urs['name'].'|'.$xuid.'';
			}
			if($fid=='udeptname'){
				$farr[$k]['dev'] = $urs['deptname'];
			}
			if($fid=='ranking'){
				$farr[$k]['dev'] = $urs['ranking'];
			}
			if($fid=='gonghao'){
				$farr[$k]['dev'] = $urs['num'];
			}
		}

		return $farr;
	}
	
	//在运行这个，模版处理
	public function flowinputtpl($cont, $lx)
	{
		//pc
		if($lx==0){
			$cfarr = $this->pipeibarr;
			$str = '';
			foreach($cfarr as $k=>$rs){
				$carr = $rs['fieldsarr'];
				if(isempt($rs['title']))$rs['title']=$rs['atype'];
				$str.='<div><br><strong>'.$rs['title'].'</strong></div>';
				if(count($carr)%2!=0)$carr[]=array(
					'name' => '',
					'fields' => '',
				);
				$str.='<table width="100%"  border="0" class="ke-zeroborder">';
				$str.='<tr>';
				foreach($carr as $k1=>$rs1){
					$str.='<td align="right" class="ys1" width="15%">'.$rs1['name'].'</td>';
					$str.='<td class="ys2" width="35%">{'.$rs1['fields'].'}</td>';
					
					if(($k1+1)%2==0)$str.='</tr><tr>';
				}
				$str.='</tr>';
				$str.='</table>';
			}
			$cont = str_replace('{autotpl}', $str, $cont);
		}
		return $cont;
	}
	
	public function flowviewtpl($cont, $lx)
	{
		$this->getfiearrs($this->rs['xuid'], $this->rs['month']);
		return $this->flowinputtpl($cont, $lx);
	}
}