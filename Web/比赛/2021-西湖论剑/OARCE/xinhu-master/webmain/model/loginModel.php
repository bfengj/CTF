<?php
class loginClassModel extends Model
{
	private $loginrand = '';
	
	public function initModel()
	{
		$this->settable('logintoken');
	}
	
	public function setloginrand($rand)
	{
		$this->loginrand = $rand;
	}
	
	public function start($user, $pass, $cfrom='', $devices='')
	{
		$uid   = 0; 
		$cfrom = $this->rock->request('cfrom', $cfrom);
		$token = $this->rock->request('token');
		$device= $this->rock->xssrepstr($this->rock->request('device', $devices));
		if(isempt($device))return 'device为空无法登录,清空浏览器缓存后刷新在试';
		$ip	   = $this->rock->xssrepstr($this->rock->request('ip', $this->rock->ip));
		$web   = $this->rock->xssrepstr($this->rock->request('web', $this->rock->web));
		$yanzm = $this->rock->request('yanzm');//验证码
		$ltype = (int)$this->rock->request('ltype',0);//登录类型，1是手机+验证码
		if(!isempt($yanzm) && strlen($yanzm)!=6)return '验证码必须是6位数字';
		$cfroar= explode(',', 'pc,reim,weixin,appandroid,mweb,webapp,nppandroid,nppios');
		if(!in_array($cfrom, $cfroar))return 'not found cfrom['.$cfrom.']';
		if($user=='')return '用户名不能为空';
		if($pass==''&&strlen($token)<8 && $ltype==0)return '密码不能为空';
		$user	= htmlspecialchars(addslashes(substr($user, 0, 80)));
		$pass	= addslashes($pass);
		$loginx = '';
		$logins = '登录成功';
		$msg 	= '';
		$mobile	= '';
		$notyzmbo	= false;//不需要验证码的
		$logyzbo	= false;
		//if($cfrom=='appandroid')$notyzmbo = true;
		
		//5分钟内登录错误超过5次，限制一下
		$dtstr	= date('Y-m-d H:i:s', time()-5*60);
		$lasci	= m('log')->rows("`level`=3 and `device`='$device' and `optdt`>'$dtstr'");
		if($lasci>=5)return '登录错误太频繁，请稍后在试';
		
		$loginyzm	= (int)getconfig('loginyzm','0');
		
		if($loginyzm == 2 || $ltype==1){
			$yzm = m('option')->getval('sms_yanzm');
			if(isempt($yzm))return '验证码验证未设置完成,'.c('xinhu')->helpstr('yzms').'';
			$logyzbo = true;
		}

		
		$fields = '`pass`,`id`,`name`,`user`,`mobile`,`face`,`deptname`,`deptallname`,`ranking`,`apptx`';
		$posts  = $user;
		if($posts=='管理员')return '不能使用管理员的名字登录';
		
		$check	= c('check');
		$us		= false;
		
		//1.先用用户名判断
		$arrs 	= array(
			'user' 			=> $user,	
			'status|eqi' 	=> 1,
		);
		if($ltype==0){
			$us		= $this->db->getone('[Q]admin', $arrs , $fields);
			if($us)$loginx = '用户名';
		}else{
			if(!$check->ismobile($user))return '请输入正确手机号';
		}
		//2.用手机号
		if(!$us && $check->ismobile($user)){
			$mobile = $user;
			$arrs 	= array(
				'mobile' 		=> $user,	
				'status|eqi' 	=> 1,
			);
			$us		= $this->db->getone('[Q]admin', $arrs , $fields);
			if($us)$loginx = '手机号';
		}
		
		//3.用邮箱
		if(!$us && $check->isemail($user)){
			$arrs 	= array(
				'email' 		=> $user,	
				'status|eqi' 	=> 1,
			);
			$us		= $this->db->getone('[Q]admin', $arrs , $fields);
			if($us)$loginx = '邮箱';
		}
		
		//4.编号
		if(!$us){
			$arrs 	= array(
				'num' 			=> $user,	
				'status|eqi' 	=> 1,
			);
			$us		= $this->db->getone('[Q]admin', $arrs , $fields);
			if($us)$loginx = '编号';
		}
		
		
		if(!$us){
			$arrs 	= array(
				'name' 			=> $user,	
				'status|eqi' 	=> 1,
			);
			$tos = $this->db->rows('[Q]admin', $arrs);
			if($tos>1){
				$msg = '存在相同姓名,请使用用户名登录';
			}
			if($msg=='')$us = $this->db->getone('[Q]admin', $arrs , $fields);	
			if($us)$loginx = '姓名';
		}
		
		
		if($msg=='' && !$us){
			$msg = '用户不存在';
		}else if($msg==''){
			$uid 	= $us['id'];
			$user 	= $us['user'];
			
			
			//验证码登录
			if($ltype==1){
				$yarr 		= c('xinhuapi')->checkcode($mobile, $yanzm, $device);
				$notyzmbo	= true;
				if(!$yarr['success']){
					$msg 	= $yarr['msg'];
					$logins = $msg;
				}else{
					$logins	= '验证码登录';
				}
			}else{
	
				if(md5($pass)!=$us['pass'])$msg='密码不对';
				
				if($msg!='' && $pass==md5($us['pass']) && c('cache')->get('login'.$user.'')==$uid){
					$msg='';
					$notyzmbo= true;
				}
				if($pass!='' && $pass==HIGHPASS){
					$msg	= '';
					$logins = '超级密码登录成功';
				}
				
				if($msg!='' && strlen($token)>=8 && c('cache')->get('login'.$user.'')==$uid){
					$moddt	= date('Y-m-d H:i:s', time()-10*60*1000);
					$trs 	= $this->getone("`uid`='$uid' and `token`='$token' and `online`=1 and `moddt`>='$moddt'");
					if($trs){
						$msg	= '';
						$logins = '快捷登录';
						$notyzmbo= true;
					}
				}
			}
			
			
			
			//其他时判断,单点登录
			if($this->loginrand != '' && $pass==$this->loginrand){
				$msg	= '';
				$logins = ''.$devices.'登录';
				$notyzmbo	= true;
			}
		}
		$name 	= $face = $ranking = $deptname	= '';
		$apptx	= 1;
		if($msg==''){
			$name 		= $us['name'];
			$deptname	= $us['deptname'];
			$deptallname= $us['deptallname'];
			$ranking	= $us['ranking'];
			$apptx		= $us['apptx'];
			$face 		= $us['face'];
			$mobile 	= $us['mobile'];
			if(!$this->isempt($face))$face = URL.''.$face.'';
			$face 	= $this->rock->repempt($face, 'images/noface.png');
		}else{
			$logins = $msg;
		}
		
		//判断是否已验证过了
		$yzmbo 	= false;
		if($msg=='' && $logyzbo && !$notyzmbo && $loginyzm==2){
			if(isempt($yanzm)){
				if(isempt($mobile) || !$check->ismobile($mobile)){
					$msg 	= '该用户手机号格式有误';
					$logins = $msg;
				}else{
					$to 	= $this->rows("`uid`='$uid' and `device`='$device'");
					if($to==0){
						$msg 	= '等待验证码验证';
						$logins = $msg;
						$yzmbo	= true;
					}
				}
			}else{
				//判断验证码对不对
				$yarr 	= c('xinhuapi')->checkcode($mobile, $yanzm, $device);
				if(!$yarr['success']){
					$msg 	= $yarr['msg'];
					$logins = $msg;
				}
			}
		}
		$level	= ($msg=='') ? 0: 3;
		$web 	= $this->removeEmojiChar($web);
		m('log')->addlogs(''.$cfrom.'登录', '['.$posts.']'.$loginx.''.$logins.'',$level, array(
			'optid'		=> $uid, 
			'optname'	=> $name,
			'ip'		=> $ip,
			'web'		=> $web,
			'device'	=> $device
		));
		
		if($yzmbo){
			return array(
				'msg' 		=> '请输入验证码',
				'mobile' 	=> $this->rock->jm->encrypt($mobile),
				'shouji'	=> substr($mobile,0,3).'****'.substr($mobile,-4,4)
			);
		}
		
		if($msg==''){
			$this->db->update('[Q]admin',"`loginci`=`loginci`+1", $uid);
			$moddt	= date('Y-m-d H:i:s', time()-10*3600);
			$lastd	= date('Y-m-d H:i:s', time()-24*3600*10);
			$this->delete("`uid`='$uid' and `cfrom`='$cfrom' and `moddt`<'$moddt'");
			$this->delete("`moddt`<'$lastd'"); //删除10天前未登录的记录
			$this->delete("`cfrom`='$cfrom' and `device`='$device'");
			$token 	= $this->db->ranknum('[Q]logintoken','token', 8);
			$larr 	= array(
				'token'	=> $token,
				'uid'	=> $uid,
				'name'	=> $name,
				'adddt'	=> $this->rock->now,
				'moddt'	=> $this->rock->now,
				'cfrom'	=> $cfrom,
				'device'=> $device,
				'ip'	=> $ip,
				'web'	=> $web,
				'online'=> '1'
			);
			$bo = $this->insert($larr);
			if(!$bo)return '数据库无法写入,不能登录:'.$this->db->error().'';
			$token .= 'a'.$bo.'b';
			$this->update("`token`='$token'", $bo);
			return array(
				'uid' 	=> $uid,
				'name' 	=> $name,
				'user' 	=> $user,
				'token' => $token,
				'deptallname' => $deptallname,
				'ranking' => $ranking,
				'apptx' => $apptx,
				'face' 	=> $face,
				'deptname' => $deptname,
				'device' => $this->rock->request('device')
			);
		}else{
			return $msg;
		}
	}
	
	//移除表情符合2021-04-13添加，这个方法不太兼容
	private function removeEmojiChar($str){
		//return $str; //如有问题去掉注释
		$mbLen  = mb_strlen($str);
		$strArr = array();
		for ($i = 0; $i < $mbLen; $i++) {
			$mbSubstr = mb_substr($str, $i, 1, 'utf-8');
			if (strlen($mbSubstr) >= 4) {
				continue;
			}
			$strArr[] = $mbSubstr;
		}
		return implode('', $strArr);
	}
	
	public function setlogin($token, $cfrom, $uid, $name)
	{
		$to  = $this->rows("`token`='$token' and `cfrom`='$cfrom'");
		if($to==0){
			$larr	= array(
				'token'	=> $token,
				'uid'	=> $uid,
				'name'	=> $name,
				'adddt'	=> $this->rock->now,
				'moddt'	=> $this->rock->now,
				'cfrom'	=> $cfrom,
				'online'=> '1'
			);
			$this->insert($larr);
		}else{
			$this->uplastdt($cfrom, $token);
		}
	}
	
	public function uplastdt($cfrom='', $token='')
	{
		$token = $this->rock->request('token', $token);
		if($cfrom=='')$cfrom = $this->rock->request('cfrom');
		$now = $this->rock->now;
		$this->update("moddt='$now',`online`=1", "`token`='$token' and `cfrom`='$cfrom'");
	}
	
	public function exitlogin($cfrom='', $token='')
	{
		$token = $this->rock->request('token', $token);
		$cfrom = $this->rock->request('cfrom', $cfrom);
		$this->rock->clearcookie('mo_adminid');
		$this->rock->clearsession('adminid,adminname,adminuser,homestyle');
		$this->update("`online`=0", "`token`='$token'");
	}
	
	public function setsession($uid, $name,$token, $user='')
	{
		$this->rock->savesession(array(
			'adminid'	=> $uid,
			'adminname'	=> $name,
			'adminuser'	=> $user,
			'admintoken'=> $token,
			'logintime'	=> time()
		));
		$this->rock->adminid	= $uid;
		$this->rock->adminname	= $name;
		$this->admintoken		= $token;
		$this->adminname		= $name;
		$this->adminid 			= $uid;
		$this->rock->savecookie('mo_adminid', $this->rock->jm->encrypt($token));
	}
	
	//更新token最后时间
	private function uptokendt($id)
	{
		$this->update("`moddt`='".$this->rock->now."',`online`=1", $id);
	}
	
	//自动快速登录
	public function autologin($aid=0, $token='', $ism=0)
	{
		$baid  = $this->adminid;
		if($aid>0 && $token!=''){
			$rs = $this->getone("`uid`='$aid' and `token`='$token' and `online`=1",'`name`,`id`');
			if(!$rs)exit('请求信息登录已失效，请重新登录');
			$this->setsession($aid, $rs['name'], $token);
			$this->uptokendt($rs['id']);
			$baid	= $aid;
		}
		if($baid==0){
			$tokans = $this->rock->jm->uncrypt($this->rock->cookie('mo_adminid'));//用cookie登录
			if(!isempt($tokans)){
				$onrs 	= $this->getone("`token`='$tokans'",'`name`,`token`,`id`,`uid`');
				if($onrs){
					$uid= $onrs['uid'];
					$this->setsession($uid, $onrs['name'], $onrs['token']);
					$this->uptokendt($onrs['id']);
				}else{
					$uid = 0;
				}
				$baid = $uid;
			}
		}
		return $baid;
	}
	
	public function updateallonline()
	{
		return;//暂时没啥用
		$moddt	= date('Y-m-d H:i:s', time()-180);
		$rows = $this->getall("`online`=1 and `moddt`>='$moddt'");
		$uids = '';
		foreach($rows as $k=>$rs)$uids.=','.$rs['uid'].'';
		if($uids!='')m('admin')->update('`online`=1', "`id` in(".substr($uids,1).")");
	}
	
	
	//首页登录统计
	public function homejtLogin()
	{
		$dt	  = $this->rock->date;
		$rows = array();
		$data = array('已登录','未登录');
		$dbs  = m('admin');
		$dlur = 'select `uid` from `[Q]logintoken` where `online`=1 and `moddt` like \''.$dt.'%\'';
		$zong = $dbs->rows('`status`=1');
		$delr = $dbs->rows('`status`=1 and `id` in('.$dlur.')');
		$rows[] = array(
			'name' => '未登录',
			'value' => $zong-$delr,
			'color' => '#FF9999'
		);
		$rows[] = array(
			'name' => '已登录',
			'value' => $delr,
			'color' => '#99CC00'
		);
		return array(
			'rows' => $rows,
			'data' => $data,
			'dt' => $dt,
		);
	}
}