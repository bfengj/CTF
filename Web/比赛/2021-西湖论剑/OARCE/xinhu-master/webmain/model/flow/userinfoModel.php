<?php
class flow_userinfoClassModel extends flowModel
{
	public  $uidfields = 'id';
	protected  $flowviewufieds = 'id';
	protected  $flowcompanyidfieds = 'companyid';
	
	public function flowsearchfields()
	{
		$arr[] = array('name'=>'部门/用户...','fields'=>'id');
		return $arr;
	}
	
	public function initModel()
	{
		//读取状态
		$this->statearr 	= array();
		$this->statearrs 	= array();
		$ztarrs = m('option')->getmnum('userstate');
		if(!$ztarrs){
			$ztarrs[] = array('value'=>0,'name'=>'试用期');
			$ztarrs[] = array('value'=>1,'name'=>'正式');
			$ztarrs[] = array('value'=>2,'name'=>'实习生');
			$ztarrs[] = array('value'=>3,'name'=>'兼职');
			$ztarrs[] = array('value'=>4,'name'=>'临时工');
			$ztarrs[] = array('value'=>5,'name'=>'离职');
		}
		foreach($ztarrs as $k=>$rs){
			$this->statearr[$rs['value']] = $rs['name'];
			$this->statearrs[] = array('id'=> $rs['value'], 'name'=>$rs['name']);
		}
		
		$this->birtypearr 	= explode(',','阳历,农历');
		//$this->calendar		= c('calendar');去掉了，没用
	}
	
	public function getstatusarr()
	{
		$barr	 = array();
		foreach($this->statearr as $k=>$v){
			$barr[$k] = array($v,'');
		}

		return $barr;
	}
	
	private function getdwname($rs)
	{
		$dwid1 = arrvalue($rs,'companyid','0');
		$dwid  = arrvalue($rs,'dwid','0');
		$dwid .= ','.$dwid1.'';
		$temp_dwid = '';
		if(!isempt($dwid)){
			$dwarr = m('company')->getall('`id` in('.$dwid.')');
			foreach($dwarr as $k1=>$rs1)$temp_dwid.=','.$rs1['name'].'';
			if($temp_dwid!='')$temp_dwid = substr($temp_dwid, 1);
		}
		return $temp_dwid;
	}

	public function flowrsreplace($rs, $lx=0)
	{
		if($rs['state']==5)$rs['ishui']=1;
		$rs['stateval']		= $rs['state'];
		$rs['state']		= $this->getuserstate($rs['state']);
		
		$rs['shengri']		= '';
		if(!isempt($rs['birthday'])){
			if($rs['birtype']==0){
				//$rs['shengri'] = $rs['birthday'];
			}else{
				//$days			= $this->calendar->daytocal($rs['birthday']);
				//$rs['shengri'] = ''.$days['year'].'年农历'.$days['month'].'月'.$days['day'].'';
			}
		}
		
		$rs['birtype']		= $this->birtypearr[$rs['birtype']];
		
		if($lx==1){
			$rs['companyid'] = $this->getdwname($rs);
		}
		
		if(getconfig('systype')=='demo')$rs['mobile']='';
		
		//导出处理
		if($this->daochubo){
			//if(isset($rs['mobile']))$rs['mobile'] = '&nbsp;'.$rs['mobile'];
			//if(isset($rs['idnum']))$rs['idnum'] = '&nbsp;'.$rs['idnum'];
			//if(isset($rs['banknum']))$rs['banknum'] = '&nbsp;'.$rs['banknum'];
		}
	
		return $rs;
	}
	
	//编辑时候替换
	protected function flowrsreplaceedit($rs)
	{
		if(getconfig('systype')=='demo')$rs['mobile']	 = '';
		return $rs;
	}
	
	public function getuserstate($zt)
	{
		return $this->rock->arrvalue($this->statearr,$zt,'未知');
	}
	
	protected function flowbillwhere($uid, $lx)
	{
		$fields = 'a.id,a.name,a.deptname,a.ranking,a.state,a.tel,a.sex,a.mobile,a.workdate,a.jiguan,a.minzu,a.xueli,a.email,a.syenddt,a.quitdt,a.positivedt,a.birtype,a.birthday,a.num,b.name as companyid';
		if($this->daochubo)$fields='a.*,b.name as companyid';//导出的
		return array(
			'ztfields'	=> 'state',
			'order'		=> 'a.id',
			'table'		=> '`[Q]userinfo` a left join `[Q]company` b on a.companyid=b.id',
			'fields'	=> $fields,
			'asqom'		=> 'a.',
			'orlikefields' => 'b.name'
		);
	}
	
	/**
	*	员工生日提醒
	*/
	public function birthdaytodo()
	{
		$dt 	= date('m-d');
		//$cala   = $this->calendar->toCalday($this->rock->date);
		$nongli	= '';
		$rows 	= $this->db->getall("select a.`birthday`,b.`id`,b.`name` from `[Q]".$this->mtable."` a left join `[Q]admin` b on a.`id`=b.`id` where b.`status`=1 and a.`state`<>5 and a.`birthday` like '%".$dt."' ");
		
		//是否生日短信提醒
		$smsnum	= $this->option->getval('smsbirthday');
		$dxobj	= c('xinhuapi');
		$dt 	= date('Y年m月d日');
		foreach($rows as $k=>$rs){
			
			$dtnong	= '';
			$cont = '今天是'.$dt.',是你的生日，我们在这里祝你生日快乐。';
			$this->push($rs['id'],'', $cont, '生日祝福');
			
			if(!isempt($smsnum)){
				$smarr	= explode(',', $smsnum);
				$tpl	= $smarr[0];
				$qmv	= arrvalue($smarr, 1, ''); //签名
				
				$dxobj->sendsms($rs['id'], $qmv, $tpl, array(
					'name' 	=> $rs['name'],
					'dt'  	=> $dt,
					'dtnong'=> $dtnong
				));
			}
		}
		
	}
	
	
	//导入数据的测试显示
	public function flowdaorutestdata()
	{
		return array(
			'name' 		=> '张三',
			'state'		=> '正式',
			'mobile' 	=> '15812345678',
			'ranking' 	=> '程序员',
			'idnum' 	=> '1001111111',
			'housedizhi'=> '福建',
			'nowdizhi' 	=> '福建',
			'hunyin' 	=> '未婚',
			'birthday' 	=> '2017-01-17',
			'xueli' 	=> '博士后',
			'minzu' 	=> '汉族',
			'email' 	=> 'zhangsan@rockoa.com',
			'workdate' 	=> '2017-01-17',
			'syenddt' 	=> '2017-03-17',
			'positivedt'=> '2017-04-01',
			'jiguan' 	=> '福建',
		);
	}
	
	//导入之前判断
	public function flowdaorubefore($rows)
	{
		$inarr	= array();
		$dba  	= m('admin');
		//根据手机号关联用户
		foreach($rows as $k=>$rs){
			
			$arr 	= $rs;
			if(!isset($rs['mobile']))return '手机号必须开启导入';
			
			$mobile = $rs['mobile'];
			$urs 	= $this->adminmodel->getone("`mobile`='$mobile'");
			
			if(!$urs)return '行'.($k+1).'的手机号('.$mobile.')找不对应用户，请先添加用户';
			$arr['id'] 			= $urs['id'];
			$arr['ranking'] 	= $urs['ranking'];
			$arr['deptname'] 	= $urs['deptname'];
			
			if(isset($rs['state'])){
				$state 	= $rs['state'];
				$zt 	= 0; //默认人员状态
				foreach($this->statearrs as $k1=>$rs1){
					if($rs1['name']==$state){
						$zt = $rs1['id'];
						break;
					}
				}
				$arr['state'] 		= $zt;
			}
			
			//更新入职日期
			$workdate = arrvalue($arr, 'workdate');
			if(!isempt($workdate)){
				$dba->update("`workdate`='$workdate'", $arr['id']);
			}
			
			$inarr[] = $arr;
		}
		
		return $inarr;
	}
	
	//导入之后
	public function flowdaoruafter()
	{
		
		$this->adminmodel->updateinfo();
	}
}