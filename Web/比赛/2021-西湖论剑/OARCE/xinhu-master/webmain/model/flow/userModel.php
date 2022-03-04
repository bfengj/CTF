<?php
class flow_userClassModel extends flowModel
{
	
	protected $flowviewufieds	= 'id';
	protected $flowcompanyidfieds	= 'companyid';
	
	public function getstatusarr()
	{
		$barr[1] = array('启用','green');
		$barr[0] = array('停用','#888888');
		return $barr;
	}
	

	public function flowsearchfields()
	{
		$arr[] = array('name'=>'部门/用户...','fields'=>'id');
		return $arr;
	}
	
	
	
	/**
	*	用户显示展示
	*/
	protected function flowbillwhere($uid, $lx)
	{
		$where 	= '';
		$pnum	= $this->rock->get('pnum');
		//其他地方来的，需要权限
		if($pnum != 'all' ){
			$where 	= 'and `status`=1 '.$this->viewmodel->viewwhere($this->moders, $uid, 'id');//权限控制
		}
		$detpid = (int)$this->rock->post('deptid','0');
		if($detpid>0){
			$where.= " and instr(`deptpath`,'[$detpid]')>0";
		}
		return array(
			'fields'=> '`name`,`id`,`id` as uid,`face`,`sort`,`deptallname`,deptpath,`ranking`,`tel`,`mobile`,`email`,`user`,num,workdate,sex,deptname,deptnames,superman,status,type,online,lastonline,isvcard,`companyid`',
			'order' => '`status` desc,`sort`',
			'where' => $where
		);
	}
	
	
	//替换
	private $companyarr = array();
	public function flowrsreplace($rs, $lx=0)
	{
		if(!$this->companyarr){
			$this->companyarr = $this->db->getkeyall('[Q]company','id,name');
		}
		if(isset($rs['mobile'])){
			$sjhao = $rs['mobile'];
		}
		if(getconfig('systype')=='demo')$rs['mobile']='';
		
		if($this->rock->ismobile()){
			if(isset($rs['mobile']) && !isempt($rs['mobile']))$rs['mobile']='<a onclick="return callPhone(\''.$sjhao.'\')" href="tel:'.$sjhao.'">'.$rs['mobile'].'</a>';
			if(isset($rs['tel']) && !isempt($rs['tel']))$rs['tel']='<a onclick="return callPhone(this)" href="tel:'.$rs['tel'].'">'.$rs['tel'].'</a>';
		}
		$type = arrvalue($rs,'type');
		if($type=='0')$rs['type']='';
		if($type=='1')$rs['type']='<font color=green>是</font>';
		if(isset($rs['companyid']))$rs['companyid'] = arrvalue($this->companyarr, $rs['companyid']);
		
		//判断当前用户状态
		$online 	= arrvalue($rs,'online','0');
		$lastonline = arrvalue($rs,'lastonline');
		if($online=='1'){
			$jgtime = time()- strtotime($lastonline);
			if($jgtime>210){
				$online = '0'; //超过200
				$this->adminmodel->update('online=0', $rs['id']);
			}
		}
		$rs['online'] = $online;
		if($lx==1){
			$rs['temp_dwid'] = $this->getdwname($rs);
		}
		return $rs;
	}
	
	//编辑时候替换
	protected function flowrsreplaceedit($rs)
	{
		$rs['groupname'] = m('sjoin')->getgroupid($rs['id']);
		$rs['pass']		 = '';
		unset($rs['deptallname']);
		
		$rs['temp_dwid'] = $this->getdwname($rs);
		if(getconfig('systype')=='demo')$rs['mobile']	 = '';
		return $rs;
	}
	
	private function getdwname($rs)
	{
		$dwid = arrvalue($rs,'dwid');
		$temp_dwid = '';
		if(!isempt($dwid)){
			$dwarr = m('company')->getall('`id` in('.$dwid.')');
			foreach($dwarr as $k1=>$rs1)$temp_dwid.=','.$rs1['name'].'';
			if($temp_dwid!='')$temp_dwid = substr($temp_dwid, 1);
		}
		return $temp_dwid;
	}
	
	//删除用户时
	protected function flowdeletebill($sm)
	{
		$id 	= $this->id;
		$name 	= $this->rs['name'];
		m('im_messzt')->delete('`uid`='.$id.'');
		m('im_history')->delete('`uid`='.$id.'');
		
		
		$dbs = m('userinfo');
		$urs = $dbs->getone($id);
		if(!$urs)return;
		$quitdt = $urs['quitdt'];
		$state  = $urs['state'];
		$uarr	= array();
		if(isempt($quitdt))$uarr['quitdt'] = date('Y-m-d'); //设置离职日期
		if($state != '5')$uarr['state']		= 5;//离职状态为5
		if($uarr)$dbs->update($uarr, $id);
	}
	
	//导入数据的测试显示
	public function flowdaorutestdata()
	{
		return array(
			'user' 		=> 'zhangsan',
			'name' 		=> '张三',
			'sex' 		=> '男',
			'mobile' 	=> '15812345678',
			'ranking' 	=> '程序员',
			'superman' 	=> '磐石',
			'deptname' 	=> '信呼开发团队/开发部',
			'tel' 		=> '0592-1234567-005',
			'email' 	=> 'zhangsan@rockoa.com',
			'workdate' 	=> '2017-01-17',
		);
	}
	
	//导入之后
	public function flowdaoruafter()
	{
		//更新设置上级主管
		foreach($this->superarrar as $superman=>$suparr){
			$superid			= (int)$this->getmou('id', "`name`='$superman'");
			$userld				= "'".join("','", $suparr)."'";
			if($superid>0){
				$this->update(array(
					'superman' 	=> $superman,
					'superid' 	=> $superid,
				),"`user` in($userld)");
			}
		}
		m('admin')->updateinfo();
	}
	
	//导入之前判断
	private $superarrar = array();
	public function flowdaorubefore($rows)
	{
		$inarr	= array();
		$sort 	= (int)$this->getmou('max(`sort`)', '`id`>0');
		$dbs	= m('dept');
		$py 	= c('pingyin');
		$dname	= $dbs->getmou('name', 1);if(isempt($dname))$dname = '信呼开发团队';
		
		foreach($rows as $k=>$rs){
			$user = $rs['user'];
			$name = $rs['name'];
			$mobile = $rs['mobile'];
			$arr	= $rs;
			
			$arr['pingyin'] 	= $py->get($name,1);
			if($this->rows("`name`='$name'")>0)$name = $name.'1';
			if(isempt($user))$user = $arr['pingyin'];
			
			if($this->rows("`user`='$user'")>0)$user = $user.'1'; //相同用户名?


			$arr['user'] = strtolower($user);
			$arr['name'] = $name;
			
			$arr['pass']  		= md5('123456');
			$arr['sort']  		= $sort+$k+1;
			$arr['workdate']  	= arrvalue($rs,'workdate', $this->rock->date);
			$arr['adddt']  		= $this->rock->now;
			$arr['companyid']  	= $this->companyid; //默认单位
			
			//读取上级主管Id
			if(isset($arr['superman'])){
				//$superid			= (int)$this->getmou('id', "`name`='".$arr['superman']."'");
				//if($superid==0)$arr['superman'] = '';
				//$arr['superid'] = $superid;
				$this->superarrar[$arr['superman']][] = $arr['user'];
			}
			$arr['superman'] = '';
			$arr['superid']  = '';
			
			//读取部门Id
			$deptarr 	= $this->getdeptid($rs['deptname'], $dbs);
			
			if($deptarr['deptid']==0)return '行'.($k+1).'找不到顶级部门['.$rs['deptname'].'],请写完整部门路径如：'.$dname.'/'.$rs['deptname'].'';
			
			foreach($deptarr as $k1=>$v1)$arr[$k1]=$v1;
			
			$inarr[] = $arr;
		}
		
		return $inarr;
	}
	private function getdeptid($str,$dobj)
	{
		$deptid = '0';
		if(isempt($str))return $deptid;
		$stra 	= explode(',', $str);
		$depad	= $this->getdeptids($stra[0],$dobj);
		$deptids= '';
		$deptnames= '';
		for($i=1;$i<count($stra);$i++){
			$depads	= $this->getdeptids($stra[$i],$dobj);
			if($depads[0]>0){
				$deptids.=','.$depads[0].'';
				$deptnames.=','.$depads[1].'';
			}
		}
		if($deptids!='')$deptids = substr($deptids, 1);
		if($deptnames!='')$deptnames = substr($deptnames, 1);
		
		return array(
			'deptid' 	=> $depad[0],
			'deptname' 	=> $depad[1],
			'deptallname' 	=> $stra[0],
			'deptids' 	=> $deptids,
			'deptnames' 	=> $deptnames,
		);
	}
	private function getdeptids($str,$dobj)
	{
		$stra	= explode('/', $str);
		$pid 	= 0;
		$id 	= 1;//默认顶级ID
		$deptname = '';
		for($i=0;$i<count($stra);$i++){
			$name = $stra[$i];
			$deptname = $name;
			$id   = (int)$dobj->getmou('id',"`pid`='$pid' and `name`='$name'");
			//不存在就创建部门
			if($id==0){
				if($pid==0)return array(0, $deptname);
				$cjbm['name'] = $deptname;
				$cjbm['pid']  = $pid;
				$id 	= $dobj->insert($cjbm);
				$pid 	= $id;
			}else{
				$pid = $id;
			}
		}
		
		return array($id, $deptname);
	}
}