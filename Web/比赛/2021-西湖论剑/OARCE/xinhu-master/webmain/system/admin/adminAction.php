<?php
class adminClassAction extends Action
{
	public function loadadminAjax()
	{
		$id = (int)$this->get('id',0);
		$data = m('admin')->getone($id);
		if($data){
			$data['pass']='';
		}
		$arr['data'] 		= $data;
		$dbs 				= m('sjoin');
		$arr['grouparr'] 	= $dbs->getgrouparr();
		$arr['groupid'] 	= $dbs->getgroupid($id);
		
		$this->returnjson($arr);
	}

	public function beforeshow($table)
	{
		$fields = 'id,name,`user`,deptname,`type`,`num`,status,tel,workdate,ranking,superman,loginci,sex,sort,face';
		$s 		= '';
		$key 	= $this->post('key');
		if($key!=''){
			$s = m('admin')->getkeywhere($key);
		}
		//这句是bug修改
		$sql1 = 'alter table `[Q]admin` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;'; 
		$opts = $this->option->getval('adminbug1');
		if(isempt($opts)){
			$this->db->query($sql1);
			$this->option->setval('adminbug1',$this->now, '用户bug1');
		}
		return array(
			'fields'=> $fields,
			'where'	=> $s
		);
	}
	

	public function fieldsafters($table, $fid, $val, $id)
	{
		$fields = 'sex,ranking,tel,mobile,workdate,email,quitdt';
		if(contain($fields, $fid))m('userinfo')->update("`$fid`='$val'", $id);
	}
	

	
	public function publicbeforesave($table, $cans, $id)
	{
		$user = strtolower(trimstr($cans['user']));
		$name = trimstr($cans['name']);
		$num  = trimstr($cans['num']);
		$email= trimstr($cans['email']);
		$check= c('check');
		$mobile 	= $cans['mobile'];
		$weixinid 	= $cans['weixinid'];
		$pingyin 	= $cans['pingyin'];
		$msg  = '';	
		if($check->isincn($user))return '用户名不能有中文';
		if(strlen($user)<2)return '用户名必须2位字符以上';
		if(!isempt($email) && !$check->isemail($email))return '邮箱格式有误';
		if(!isempt($pingyin) && $check->isincn($pingyin))return '名字拼音不能有中文';
		if(!isempt($num) && $check->isincn($num))return '编号不能有中文';
		if(!isempt($mobile)){
			if(!$check->ismobile($mobile))return '手机格式有误';
		}
		if(isempt($mobile) && isempt($email))return '邮箱/手机号不能同时为空';
		if(!isempt($weixinid)){
			if(is_numeric($weixinid))return '微信号不能是数字';
			if($check->isincn($weixinid))return '微信号不能有中文';
		}
		$db  = m($table);
		
		if($msg=='' && $num!='')if($db->rows("`num`='$num' and `id`<>'$id'")>0)$msg ='编号['.$num.']已存在';
		if($msg=='')if($db->rows("`user`='$user' and `id`<>'$id'")>0)$msg ='用户名['.$user.']已存在';
		if($msg=='')if($db->rows("`name`='$name' and `id`<>'$id'")>0)$msg ='姓名['.$name.']已存在';
		$rows = array();
		if($msg == ''){
			$did  = $cans['deptid'];
			$sup  = $cans['superid'];
			$rows = $db->getpath($did, $sup);
		}
		if(isempt($pingyin))$pingyin = c('pingyin')->get($name,1);
		$rows['pingyin'] = $pingyin;
		$rows['user'] 	= $user;
		$rows['name'] 	= $name;
		$rows['email'] 	= $email;
		$arr = array('msg'=>$msg, 'rows'=>$rows);
		return $arr;
	}
	
	public function publicaftersave($table, $cans, $id)
	{
		m($table)->record(array('superman'=>$cans['name']), "`superid`='$id'");
		if(getconfig('systype')=='demo'){
			m('weixin:user')->optuserwx($id);
		}
		$mygroup = $this->post('mygroup');
		m('sjoin')->addgroupuid($id, $mygroup);
		$this->updatess('and a.id='.$id.'');
	}
	
	public function updatedataAjax()
	{
		$a = $this->updatess();
		m('imgroup')->updategall();
		echo '总'.$a[0].'条记录,更新了'.$a[1].'条';
	}
	
	public function updatess($whe='')
	{
		return m('admin')->updateinfo($whe);
	}
	
	
	//批量导入
	public function saveadminplAjax()
	{
		$rows  	= c('html')->importdata('user,name,sex,ranking,deptname,mobile,email,tel,superman','name');
		$oi 	= 0;
		$db 	= m('admin');
		$sort 	= (int)$db->getmou('max(`sort`)', '`id`>0');
		$dbs	= m('dept');
		$py 	= c('pingyin');
		
		$inarr	= array();
		
		foreach($rows as $k=>$rs){
			$user = $rs['user'];
			$name = $rs['name'];
			$arr	= array();
			
			$arr['pingyin'] 	= $py->get($name,1);
			if($db->rows("`name`='$name'")>0)$name = $name.'1';
			if(isempt($user))$user = $arr['pingyin'];
			
			if($db->rows("`user`='$user'")>0)$user = $user.'1'; //相同用户名?

			$arr['user'] = strtolower($user);
			$arr['name'] = $name;
			
			$arr['sex']  		= $rs['sex'];
			$arr['ranking']  	= $rs['ranking'];
			$arr['deptname']  	= $rs['deptname'];
			$arr['mobile']  	= $rs['mobile'];
			$arr['email']  		= $rs['email'];
			$arr['tel']  		= $rs['tel'];
			$arr['superman']  	= $rs['superman'];
			$arr['pass']  		= md5('123456');
			$arr['sort']  		= $sort+$oi;
			$arr['workdate']  	= $this->date;
			$arr['adddt']  		= $this->now;
			$arr['companyid']  	= 1; //默认公司Id为1
			
			//读取上级主管Id
			$superid			= (int)$db->getmou('id', "`name`='".$arr['superman']."'");
			if($superid==0)$arr['superman'] = '';
			$arr['superid'] = $superid;
			
			//读取部门Id
			$deptarr 	= $this->getdeptid($rs['deptname'], $dbs);
			
			if($deptarr['deptid']==0)return returnerror('行'.($k+1).'找不到对应顶级部门['.$rs['deptname'].']');
			foreach($deptarr as $k1=>$v1)$arr[$k1]=$v1;
			
			$inarr[] = $arr;
		}
		
		foreach($inarr as $k=>$rs){
			$bo = $db->insert($rs);
			if($bo)$oi++;
		}

		if($oi>0)$this->updatess();
		return returnsuccess('成功导入'.$oi.'个用户');
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
	
	//修改头像
	public function editfaceAjax()
	{
		$fid = (int)$this->post('fid');
		$uid = (int)$this->post('uid');
		echo m('admin')->changeface($uid, $fid);
	}
	
	//获取职位
	public function getrankAjax()
	{
		$arr 	= array();
		$rows 	= $this->db->getall('select `ranking` from `[Q]admin` group by `ranking`');
		foreach($rows as $k=>$rs)$arr[] = array('name'=>$rs['ranking'],'value'=>'');
		return $arr;
	}
}