<?php
//考勤机管理命令
class kqjcmdClassModel extends Model
{
	private $snrs;
	private $pinpai = 0;
	
	public function initModel()
	{
		$this->snobj = m('kqjsn');
		$this->kquobj = m('kqjuser');
	}
	
	/**
	*	命令类型
	*/
	public function cmdtype($type)
	{
		$atrr = array(
			'config' => '设置配置',
			'reboot' => '重启',
			'dept' 	 => '上传部门',
			'user' 	 => '上传人员',
			'deluser' 	=> '删除人员',
			'delsuser' 	=> '删除不存在人员',
			'getfingerprint' => '获取指纹',
			'getheadpic' => '获取头像',
			'headpic' 	 => '上传头像',
			'advert1' 	 => '设置广告图1',
			'advert2' 	 => '设置广告图2',
			'advert3' 	 => '设置广告图3',
			'deladvert' 	 => '删除广告图',
			'getuser' 	 => '获取人员',
			'getinfo' 	 => '获取设备信息',
			'getclockin' 	 => '获取打卡记录',
			'delclockin' 	 => '删除打卡记录',
			'getpic'  => '获取现场照片',
			'delpic'  => '删除现场照片',
			'fingerprint'  => '上传指纹',
		);
		
		return arrvalue($atrr, $type, $type);
	}
	
	/**
	*	发送命令
	*/
	public function send($snid, $type, $ohter='')
	{
		$snrs 	= $this->getsninfo($snid);
		if(!$snrs)return returnerror('设备不存在,请添加');
		$pinpai	= (int)arrvalue($snrs,'pinpai', 0);
		$this->pinpai = $pinpai;
		if(isempt($snrs['name']))return returnerror('请设置设备名称');
		if(isempt($snrs['company']))return returnerror('请设置设备显示公司名称');
		$id 	= 0;
		
		//中控支持命令类型
		$zkarr	= array('reboot','config','user','dept','getuser','getinfo','deluser','delsuser','getclockin','delclockin'); 
		if($pinpai==1 && !in_array($type, $zkarr))return returnerror('中控考勤机不支持['.$type.'.'.$this->cmdtype($type).']命令发送');
		
		//判断是不是有重复
		$arrpda = array('reboot','config','getuser','getinfo','advert');
		if(in_array($type, $arrpda)){
			$tod = $this->rows("`snid`='$snid' and `atype`='$type' and `status`=0");
			if($tod>0)return returnerror('还有['.$this->cmdtype($type).']命令待运行，不能重复发送');
		}
		
		//重启
		if($type=='reboot'){
			$id = $this->savedata($snid, $type, array(
				'do' => 'cmd',
				'cmd' => 'reboot',
			));
		}
		
		//获取设备信息
		if($type=='getinfo'){
			$id = $this->savedata($snid, $type, array(
				'do' 	=> 'upload',
				'data' 	=> 'info',
			));
		}
		
		//发送配置信息
		if($type=='config'){
			$id = $this->savedata($snid, $type, $this->getconfigs($snrs));
		}
		
		//部门推送更新
		if($type=='dept'){
			$id = $this->savedata($snid, $type, $this->depttosn($ohter));
		}
		
		//人员推送
		if($type=='user'){
			$id = $this->savedata($snid, $type, $this->usertosn($ohter));
		}
		
		//获取指纹和头像
		if($type=='getfingerprint' || $type=='getheadpic'){
			$id = $this->savedata($snid, $type, $this->sntofingerhead($ohter, $type));
		}
		
		//获取所有人员
		if($type=='getuser'){
			$data 	= array('user');
			if($pinpai==1)$data = 'user';
			$id 	= $this->savedata($snid, $type, array(
				'do' 	=> 'upload',
				'data' 	=> $data,
			));
		}
		
		//设置广告图
		if(substr($type,0,6)=='advert'){
			$index = substr($type,6);
			$path  = 'images/kqbanner'.$index.'.jpg';
			if(!file_exists($path)){
				$id = '广告图片'.$index.'不存在，请在系统目录下添加图片：'.$path.'';
			}else{
				$id = $this->savedata($snid, $type, array(
					'do' 	=> 'update',
					'data' 	=> 'advert',
					'index' => $index,
					'advert'=> base64_encode(file_get_contents($path))
				));
			}
		}
		
		//上传头像
		if($type=='headpic'){
			$id = $this->uoloadface($snid,$ohter);
		}
		
		//删除选中人员
		if($type=='deluser'){
			$id = $this->savedata($snid, $type, $this->userdeltosn($snid, $ohter));
		}
		//删除不存在人员
		if($type=='delsuser'){
			$id = $this->savedata($snid, 'deluser', $this->userdeltosns($snid));
		}
		
		//获取打卡记录
		if($type=='getclockin' || $type=='getpic' || $type=='delclockin' || $type=='delpic'){
			$id = $this->savedata($snid, $type, $this->getsntosyspic($snid, $type,$ohter));
		}
		
		//上次指纹,采集就不上传了
		if($type=='fingerprint'){
			$id = $this->fingerprinttosn($snid, $ohter);
		}
		
		
		if($id==0 || is_string($id))return returnerror('发送失败:'.$id.'');
		
		return returnsuccess(array(
			'id' => $id
		));
	}
	
	//保存命令到数据库
	private function savedata($snid, $type, $data)
	{
		if(is_string($data))return $data;
		if(!$data)return 0;
		if(!isset($data[0]))$data = array($data);
		$id 	= $this->getrandid();
		$others = '';
		foreach($data as $k=>$rs){
			$data[$k]['id'] = $id+$k;
			if($k==0)$others = arrvalue($rs,'others');
			unset($data[$k]['others']);
		}
		$cmd	= json_encode($data,256);
		$this->insert(array(
			'id'	=> $id,
			'snid' 	=> $snid,
			'others' 	=> $others, //其他主键ID
			'status'=> '0',
			'atype' => $type,
			'cmd' 	=> $cmd,
			'optdt' => $this->rock->now,
		));
		return $id;
	}
	
	private function getrandid()
	{
		$id = rand(10000,99999999);
		if($this->rows($id)>0)$id = $this->getrandid();
		return $id;
	}
	
	
	/**
	*	获取命令,一次可获取条数
	*/
	public function getcmd($snid)
	{
		//10分钟内的
		$optdt= date('Y-m-d H:i:s', time()-10*60);
		$rows = $this->getall("`snid`='$snid' and `status`=0 and `optdt`>'$optdt'",'*','optdt asc');
		$snrs = $this->getsninfo($snid);
		if($rows){
			$data = $rows[0];
			$this->update(array(
				'status' => 2,
				'qjtime' => $this->rock->now,
			), $data['id']);
			$cmd  = $data['cmd'];
			$cmd  = str_replace("\n",'', $cmd);
			$barr = json_decode($cmd, true);
		}else{
			//$barr[] 	= $this->getconfigs($snrs);
			$barr = '';
		}
		$this->snobj->update(array(
			'lastdt' => $this->rock->now
		), $snid);
		return $barr;
	}
	
	//配置信息
	private function getconfigs($snrs)
	{
		$name 		= arrvalue($snrs,'name','信呼云考勤');
		$company 	= arrvalue($snrs,'company','信呼云考勤');
		$snid 		= arrvalue($snrs,'id','0');
		
		if($this->pinpai==1){
			$dtarr	= explode('.', date('Y.m.d.H.i.s'));
			return array(
				'id' 		=> 0,
				'do' 		=> 'update',
				'data' 		=> 'config',
				'name' 		=> $name,
				'y0'		=> (int)$dtarr[0],
				'm0'		=> (int)$dtarr[1],
				'd0'		=> (int)$dtarr[2],
				'h0'		=> (int)$dtarr[3],
				'i0'	=> (int)$dtarr[4],
				's0'	=> (int)$dtarr[5],
				'systime'	=> $this->rock->now
			);
		}
		return array(
			'id' 		=> 0,
			'do' 		=> 'update',
			'data' 		=> 'config',
			'name' 		=> $name,
			'company' 	=> $company,
			'companyid' => $snid, 	//公司ID/设备ID
			'max' 		=> 3000,	//目前设计最大值
			'function' 	=> 65535, 	//全部功能
			'delay' 	=> 20,
			'errdelay' 	=> 50,
			'interval' 	=> 5,
			'timezone' 	=> 'GMT+08:00',
			'encrypt' 	=> 0,
			'expired' 	=> 0
		);
	}
	
	
	/**
	*	推送过来的数据
	*/
	public function postdata($snid, $dstr)
	{
		$this->rock->debugs($dstr,'postkqj_'.$snid.'_');
		$barr = json_decode($dstr, true);
		$carr = array();
		$uids = $dids = '';
		$snrs = $this->getsninfo($snid);
		if($barr)foreach($barr as $k=>$rs){
			$dtype = arrvalue($rs, 'data'); //数据类型
			
			$carr[]= $rs['id']; //设备上来的ID
			
			//发送的命令返回
			if($dtype == 'return'){
				$mids 	= '';
				foreach($rs['return'] as $k1=>$rs1){
					$mid 	= arrvalue($rs1,'id'); //我发送时ID
					if(isempt($mid))continue;
					$result = $rs1['result']; //处理结果
					$status = ($result=='0') ? 1 : 3;//
					$this->update(array(
						'status' => $status,
						'cjtime' => $this->rock->now,
					),'`id`='.$mid.'');
					$mids.=','.$mid.'';
				}
				if($mids!=''){
					$mids = substr($mids, 1);
					$this->returnchuli($mids, $snid); //返回处理
				}
			}
			
			//推送过来的人员信息
			if($dtype=='user' && isset($rs['deptid'])){
				$uids .= ','.$rs['ccid'].'';
				$dids .= ','.$rs['deptid'].'';
			}
			
			//推送来的指纹
			if($dtype=='fingerprint'){
				$this->savefingerprint($snid, $rs['ccid'], $rs['fingerprint']);
			}
			
			//推送来的头像
			if($dtype=='headpic'){
				$this->saveheadpic($snid, $rs['ccid'], $rs['headpic']);
			}
			
			//解除绑定（解除绑定会清空设备上所有数据，包括设备上待发送的命令）
			if($dtype=='unbound'){
				$this->cleardatasn($snid);
			}
			
			//打卡记录
			if($dtype=='clockin'){
				$this->adddkjl($snid, $rs);
			}
			
			//推送来的设备信息
			if($dtype=='info'){
				$this->setsnconfig($snid, $rs);
			}
		}
		
		//保存用户
		if($uids!='')$this->saveuseriddids(substr($uids, 1), substr($dids, 1), $snid);

		return $carr;
	}
	
	//上传完成回调处理
	public function returnchuli($mids, $snid)
	{
		$clarr = $this->getall("`id` in($mids) and `status`=1");//处理成功的
		$detpids= $userids= $useridsdel = '';
		foreach($clarr as $k=>$rs){
			$others = $rs['others'];
			if(isempt($others))continue;//不需要处理
			$atype  = $rs['atype'];
			
			//部门说明设备已
			if($atype=='dept'){
				$detpids.=','.$others.'';
			}
			
			//人员
			if($atype=='user'){
				$userids.=','.$others.'';
			}
			
			//上传头像成功
			if($atype=='headpic'){
				$uid = (int)$others;
				$face= $this->db->getmou('[Q]admin','face','`id`='.$uid.'');
				$this->saveheadpic($snid, $uid, '', $face); //设置设备头像
			}
			
			//删除人员成功
			if($atype=='deluser'){
				$useridsdel.=','.$others.'';
			}
			
			//指纹上传成功
			if($atype=='fingerprint'){
				$cmdarr = json_decode($rs['cmd'], true);
				$cnsrs	= $cmdarr[0];
				$this->savefingerprint($snid, $cnsrs['ccid'], $cnsrs['fingerprint']); //保存指纹
			}
		}
		
		//部门
		if(!isempt($detpids)){
			$this->addupstr($snid, substr($detpids, 1), 'deptids');
		}
		
		//人员的，说明设备上有哪些人员
		if(!isempt($userids)){
			$this->addupstr($snid, substr($userids, 1), 'userids');
		}
		
		//删除人员
		if(!isempt($useridsdel)){
			$this->delupstr($snid, substr($useridsdel, 1), 'userids');
		}
	}
	
	//清除设备上所有信息
	private function cleardatasn($snid)
	{
		m('kqjuser')->delete('`snid`='.$snid.'');//删除数据
		m('kqjcmd')->delete('`snid`='.$snid.''); //删除命令
		$this->snobj->update(array(
			'userids' => '',
			'deptids' => '',
		), $snid);
	}
	
	//添加打卡记录$rs = {time,ccid,pic,verify}
	private $uinfoarr = array();
	public function adddkjl($snid, $rs, $type=1, $ddbs=null, $iszk=0)
	{
		$dkdt 	= $rs['time'];
		$uid 	= $rs['ccid']; //用户ID
		//是中控考勤机来的
		if($iszk==1){
			if(isset($this->uinfoarr[$uid])){
				$uid 	= $this->uinfoarr[$uid];
			}else{
				$unfo 	= $this->db->getone('[Q]userinfo',"`finger`='$uid'");
				if($unfo){
					$this->uinfoarr[$uid] = $unfo['id'];
					$uid = $unfo['id'];
				}else{
					$this->uinfoarr[$uid] = $uid;
				}
			}
		}
		$pic	= arrvalue($rs,'pic');	 //现成照片
		$sntype = $rs['verify'];//打卡方式
		$where 	= "`uid`='$uid' and `dkdt`='$dkdt' and `type`='$type'";
		if($ddbs==null)$ddbs	= m('kqdkjl');
		$to 	= $ddbs->rows($where);
		$datype = array('密码','指纹','刷卡');
		
		$uarr['sntype'] = $sntype;
		$uarr['snid'] 	= $snid;
		$uarr['optdt']  = $this->rock->now;
		$uarr['explain']  = '在['.$this->snrs['name'].']使用('.arrvalue($datype, $sntype).')打卡';
		if($to==0){
			$uarr['type'] 	= $type;
			$uarr['uid'] 	= $uid;
			$uarr['dkdt'] 	= $dkdt;
			$where = '';
		}
		
		if(!isempt($pic)){
			$imgpath = ''.UPDIR.'/'.date('Y-m').'/'.$uid.'_'.strtotime($dkdt).'.jpg';
			$this->rock->createtxt($imgpath, base64_decode($pic));
			$uarr['imgpath'] 	= $imgpath;
		}
		$ddbs->record($uarr, $where);
		
		$dkdta = explode(' ', $dkdt);
		$fenxiarr[''.$dkdta[0].'|'.$uid] = $uid;
		return $fenxiarr;
	}
	
	//保存设备用户
	private function saveuseriddids($userids, $dids, $snid)
	{
		$darrs 	 = $this->depttosn($dids);
		$deptids = $darrs['others'];
		$this->snobj->update(array(
			'userids' => $userids,
			'deptids' => $deptids,
		), $snid);
	}
	
	//保存指纹
	public function savefingerprint($snid, $uid, $finge)
	{
		$where = "`snid`='$snid' and `uid`='$uid'";
		$arr['fingerprint1'] = str_replace("\n",'', arrvalue($finge, 0));
		$arr['fingerprint2'] = str_replace("\n",'', arrvalue($finge, 1));
		//if(isempt($arr['fingerprint1']) && isempt($arr['fingerprint2']))return;
		if($this->kquobj->rows($where)==0){
			$where = '';
			$arr['snid'] = $snid;
			$arr['uid'] = $uid;
		}
		$this->kquobj->record($arr, $where);
	}
	
	//保存设备头像
	private function saveheadpic($snid, $uid, $headpic, $face='')
	{
		$where = "`snid`='$snid' and `uid`='$uid'";
		if(isempt($face)){
			if(isempt($headpic))return;
			$face  = ''.UPDIR.'/face/kqj'.$snid.'_u'.$uid.'.jpg'; //头像保存为图片
			$this->rock->createtxt($face, base64_decode($headpic));
		}
		$arr['headpic'] = $face;
		if($this->kquobj->rows($where)==0){
			$where = '';
			$arr['snid'] = $snid;
			$arr['uid']  = $uid;
		}
		$this->kquobj->record($arr, $where);
	}
	
	//获取设备信息
	public function getsninfo($id)
	{
		$snrs			= $this->db->getone('`[Q]kqjsn`','`id`='.$id.'');
		$this->snrs 	= $snrs;//当前设备信息
		return $snrs;
	}
	
	//设置设备信息
	private function setsnconfig($snid, $rs)
	{
		$uarr['model'] = arrvalue($rs,'model');
		$uarr['appver'] = $rs['app'];
		$uarr['romver'] = $rs['rom'];
		$uarr['space'] = $rs['space'];
		$uarr['memory'] = $rs['memory'];
		$uarr['usershu'] = $rs['user'];
		$uarr['fingerprintshu'] = $rs['fingerprint'];
		$uarr['headpicshu'] = $rs['headpic'];
		$uarr['clockinshu'] = $rs['clockin'];
		$uarr['picshu'] = $rs['pic'];
		$this->snobj->update($uarr, $snid);
	}
	
	
	private function delupstr($snid, $dstr, $fields)
	{
		$this->kquobj->delete('`snid`='.$snid.' and `uid` in('.$dstr.')');
		$snrs = $this->getsninfo($snid);
		$odeptid = $snrs[$fields];
		if(isempt($odeptid))return;
		$dstr = ','.$dstr.','; //要删除的
		
		$depta 	 = explode(',', $odeptid);
		$dids 	 = array(); //最后Id
		foreach($depta as $dis1){
			if(!contain($dstr,','.$dis1.','))$dids[] = $dis1;
		}
		
		$ids 	= join(',', $dids);
		$this->snobj->update("`$fields`='$ids'", $snid);
	}
	
	//更新添加记录
	private function addupstr($snid, $strss, $fields)
	{
		$snrs = $this->getsninfo($snid);
		$odeptid = $snrs[$fields];
		if(isempt($odeptid))$odeptid = '';
		if(!isempt($odeptid))$odeptid.=',';
		$odeptid.=''.$strss.''; //最新的
		
		$depta 	 = explode(',', $odeptid);
		$dids 	 = array(); //最后Id
		foreach($depta as $dis1){
			if(!in_array($dis1, $dids))$dids[] = $dis1;
		}
		$ids 	= join(',', $dids);
		
		$this->snobj->update("`$fields`='$ids'", $snid);
	}
	
	/**
	*	部门推送更新
	*/
	private function depttosn($deptids)
	{
		if(isempt($deptids))return 0;
		$dids 		= '';
		$deptida	= explode(',', $deptids);
		foreach($deptida as $did){
			$didsss 	= $this->db->getpval('[Q]dept', 'pid', 'id', $did,','); //获取路径
			if(!isempt($didsss))$dids.=','.$didsss.'';
			
		}
		if($dids=='')return 0;
		$dids = substr($dids, 1);
		$drows= $this->db->getall('select * from `[Q]dept` where `id` in('.$dids.')');
		$deptarr = array();
		$ids  = '';
		foreach($drows as $k=>$rs){
			$deptarr[] = array(
				'id' 	=> $rs['id'],
				'name' 	=> $rs['name'],
				'pid' 	=> $rs['pid'],
			);
			$ids.=','.$rs['id'].'';
		}
		$data['do'] 	= 'update';
		$data['data'] 	= 'dept';
		$data['dept'] 	= $deptarr;
		$data['others'] = substr($ids, 1); //部门id

		return $data;
	}
	
	//人员上传
	private function usertosn($uids)
	{
		//$uarr = m('admin')->getall('id in('.$uids.') and `status`=1');
		$uarr = $this->db->getall('select a.*,b.`finger` from `[Q]admin` a left join `[Q]userinfo` b on a.`id`=b.`id` where a.`id` in('.$uids.') and a.`status`=1');
		
		if(!$uarr)return 0;
		$data 	= array();
		$ids  	= '';
		$dids 	= '';
		$deptids= ','.arrvalue($this->snrs,'deptids').',';
		
		foreach($uarr as $k=>$rs){
			$data[] = array(
				'do' 	=> 'update',
				'data' 	=> 'user',
				'ccid' 	=> $rs['id'],
				'finger' => $this->rock->repempt($rs['finger']),
				'name' 	=> $rs['name'],
				'passwd'=> $rs['pass'], // 密码
				'card' 	=> $rs['user'],
				'deptid' => $rs['deptid'],
				'auth' 	=> 0, //刷卡卡号
				'faceexist' => 0, //是否有人脸，0 没有，1 有（暂无用，预留字段）
			);
			$ids.=','.$rs['id'].'';
			
			if(!contain($deptids,','.$rs['deptid'].',')){
				$dids.=','.$rs['deptid'].'';
			}
		}
		$data[0]['others'] = substr($ids, 1); //人员ID
		
		
		//同时也要上传部门ID
		if($dids!=''){
			$dids = substr($dids, 1);
			$this->savedata($this->snrs['id'], 'dept', $this->depttosn($dids));
		}
		
		
		return $data;
	}
	
	//设备上获取指纹和头像
	private function sntofingerhead($uids, $type)
	{
		$uarr 		= $this->userinsn($uids);
		if(is_string($uarr))return $uarr;
		
		$ccid = array();
		foreach($uarr as $k=>$rs){
			$ccid[] = $rs['id'];
		}
		$data = array(
			'do' => 'upload',
			'data' => array(substr($type,3)),
			'ccid' => $ccid,
			'others' => join(',', $ccid)
		);
		if($this->pinpai==1){
			$data['data'] = $data['data'][0];
			$data['ccid'] = $data['others'];
		}
		
		return $data;
	}
	
	//判断人员是否在设备上
	private function userinsn($uids)
	{
		$userids 	= arrvalue($this->snrs,'userids');
		if(isempt($userids))return '设备上没有人员';
		$uarr = m('admin')->getall('id in('.$uids.') and `id` in('.$userids.')');
		if(!$uarr)return '没有选中人员没在此设备上';
		
		return $uarr;
	}
	
	//上传头像
	private function uoloadface($snid, $uids)
	{
		$uarr 		= $this->userinsn($uids);
		if(is_string($uarr))return $uarr;
		foreach($uarr as $k=>$rs){
			$face = $rs['face'];
			if(!isempt($face) && file_exists($face)){
				
				$data['do'] 	= 'update';
				$data['data'] 	= 'headpic';
				$data['ccid'] 	= $rs['id'];
				$data['others'] = $rs['id'];
				$data['headpic']= base64_encode(file_get_contents($face));
				
				$this->savedata($snid, 'headpic', $data);
			}
		}
		return 1;
	}
	
	//删除选中的人员
	private function userdeltosn($snid, $uids)
	{
		$uarr 		= $this->userinsn($uids);
		if(is_string($uarr))return $uarr;
		
		$ccid = array();
		foreach($uarr as $k=>$rs){
			$ccid[] = $rs['id'];
		}
		$data = array(
			'do' 	=> 'delete',
			'data' 	=> array("user","fingerprint","face","headpic","clockin","pic"), //删除全部
			'ccid' 	=> $ccid,
			'others' => join(',', $ccid)
		);
		//中控
		if($this->pinpai==1){
			$data['data'] = 'deluser';
			$data['ccid'] = join(',', $ccid);
		}
		return $data;
	}
	//删除不存在的
	private function userdeltosns($snid)
	{
		$ccid = $this->getnosys($snid);
		if(!$ccid)return '没有可删除的人员';
		$data = array(
			'do' 	=> 'delete',
			'data' 	=> array("user","fingerprint","face","headpic","clockin","pic"), //删除全部
			'ccid' 	=> $ccid,
			'others' => join(',', $ccid)
		);
		//中控
		if($this->pinpai==1){
			$data['data'] = 'deluser';
			$data['ccid'] = join(',', $ccid);
		}
		return $data;
	}
	
	//对应设备显示显示离职人员等信息
	public function getnosys($snid)
	{
		$snrs = $this->getsninfo($snid);
		$userids = $snrs['userids'];
		if(isempt($userids))return array();
		$uarr = m('admin')->getall('`status`=1');
		$ccid = array();
		foreach($uarr as $k=>$rs){
			$ccid[] = $rs['id'];
		}
		$useridsa = explode(',', $userids);
		$nuco	= array();//不存在
		foreach($useridsa as $adis){
			if(!in_array($adis, $ccid))$nuco[] = $adis;
		}
		return $nuco;
	}
	
	//获取打卡记录
	private function getsntosyspic($snid, $type, $uids)
	{
		$startdt = $this->rock->post('startdt', $this->rock->date);
		$endddt  = $this->rock->post('endddt', $this->rock->date);
		if($endddt<$startdt)return '获取开始日期不能大于截止日期';
		
		$userids 	= arrvalue($this->snrs,'userids');
		if(isempt($userids))return '设备上没有人员';
		
		$ccid 		= array();
		if($uids!='0'){
			$uarr = m('admin')->getall('`id` in('.$userids.') and `id` in('.$uids.')');
			if(!$uarr)return '没有选中人员没在此设备上';
			
			$ccid = array();
			foreach($uarr as $k=>$rs){
				$ccid[] = $rs['id'];
			}
		}
		//删除
		if($type=='delclockin' || $type=='delpic'){
			$data['do'] = 'delete';
			$data['data'] = array(substr($type, 3));
		}else{
			$data['do'] = 'upload';
			$data['data'] = array('clockin');
			if($type=='getpic')$data['data'][]= 'pic';//要照片
		}
		if($ccid){
			$data['ccid'] = $ccid;
			$data['others'] = join(',', $ccid);
		}
		$data['from'] = ''.$startdt.' 00:00:00';
		$data['to']   = ''.$endddt.' 23:59:59';
		
		if($this->pinpai==1){
			$data['data'] = $data['data'][0];
			$data['ccid'] = join(',', $ccid);
		}
		return $data;
	}
	
	//上传指纹
	public function fingerprinttosn($snid, $uids)
	{
		$uarr 		= $this->userinsn($uids);
		if(is_string($uarr))return $uarr;
		
		$ccid = array();
		$ubo  = 0;
		foreach($uarr as $k=>$rs){
			$uid  = $rs['id'];
			$zwra = $this->kquobj->getone('snid='.$snid.' and `uid`='.$uid.'');
			$fingerprint1 = arrvalue($zwra,'fingerprint1');
			$fingerprint2 = arrvalue($zwra,'fingerprint2');
			
			$uobo		 = false;
			
			//找找别的设备有没有指纹
			if(isempt($fingerprint1)){
				$fingerprint1s = $this->kquobj->getmou('fingerprint1',"`uid`='$uid' and ifnull(`fingerprint1`,'')<>''");
				if(!isempt($fingerprint1s)){
					$fingerprint1 = $fingerprint1s;
					$uobo = true;
				}
			}
			
			if(isempt($fingerprint2)){
				$fingerprint2s = $this->kquobj->getmou('fingerprint2',"`uid`='$uid' and ifnull(`fingerprint2`,'')<>''");
				if(!isempt($fingerprint2s)){
					$fingerprint2 = $fingerprint2s;
					$uobo = true;
				}
			}
			
			if($uobo){
				$ubo++;
				$data['do'] 	= 'update';
				$data['data'] 	= 'fingerprint';
				$data['ccid'] 	= $uid;
				$data['others'] = $uid;
				$data['fingerprint'] = array($fingerprint1, $fingerprint2);
				
				$this->savedata($snid, 'fingerprint', $data);
			}
		}
		if($ubo==0)$ubo = '没有可上传的指纹';
		return $ubo;
	}
}