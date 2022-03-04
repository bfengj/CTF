<?php 
class indexClassAction extends Action{
	
	private function homeicons()
	{
		$myext	= $this->getsession('adminallmenuid');
		$where	= '';
		if($myext != '-1'){	
			$caids  = str_replace(array('[',']'), array('',''), $myext);
			if(isempt($caids))$caids='0';
			$where	= ' and `id` in('.$caids.')';
		}
		$mrows = m('menu')->getrows("`ishs`=1 and `status`=1 $where ", "`id`,`num`,`name`,`url`,`color`,`icons`",'`sort`');
		return $mrows;
	}
	
	public function gettotalAjax()
	{
		$loadci	= (int)$this->get('loadci','0');
		$optdta	= $this->get('optdt');
		$optdt 	= $this->now;
		$uid 	= $this->adminid;
		$urs	= m('admin')->getone("`id`='$uid' and `status`=1");
		if(!$urs)exit('用户不存在');
		
		$arr['optdt']	= $optdt;
		$todo			= m('todo')->rows("uid='$uid' and `status`=0 and `tododt`<='$optdt'");
		$arr['todo']	= $todo;
		
		$arr['reimstotal']	= 0;
		if(getconfig('reim_show',true))$arr['reimstotal'] = m('reim')->getreimwd($uid);
		
		if($loadci==0){
			$arr['showkey'] = $this->jm->base64encode($this->jm->getkeyshow());
			$arr['menuarr'] = $this->homeicons();
			$arr['token']	= $this->admintoken;
			$arr['authkey'] = $this->option->getval('auther_authkey');
			$_key 			= substr(md5(URL.getconfig('randkey')),0,20);
			$usedt 			= $this->option->getval($_key);
			if(isempt($usedt)){
				$usedt	= $this->jm->base64encode(date('Y-m-d', time()+7*24*3600));
				$this->option->setval($_key.'@-102', $usedt);
			}
			$arr['usedt']	= $usedt;
		
			if(DB_USER=='root'){
				$sqld = $this->db->getall('select @@global.sql_mode as total');
				if($sqld){
					$sqlmodel = $sqld[0]['total'];
					$arr['sqlmodel']	= $sqlmodel;
					$sqlsr	= explode(',', $sqlmodel);
					$kes 	= 'ONLY_FULL_GROUP_BY';
					$nstr	= array();
					if(in_array($kes, $sqlsr))foreach($sqlsr as $_kt)if($_kt!=$kes)$nstr[]=$_kt;
					if($nstr)$this->db->query("set @@global.sql_mode ='".join(',', $nstr)."'",false);
				}
			}
			
		}
		$s = $s1 = '';
		if($loadci==0){
			if($todo>0){
				$s = '您还有<font color=red>('.$todo.')</font>条未读提醒信息;<a onclick="return opentixiangs()" href="javascript:">[查看]</a>';
				$s1= '您还有('.$todo.')条未读提醒信息;';
			}
		}else{
			m('dept')->online(1);//在线状态更新
			if($todo>0){
				$rows = m('todo')->getrows("uid='$uid' and `status`=0 and `optdt`>'$optdta' and `tododt`<='$optdt' order by `id` limit 3");
				foreach($rows as $k=>$rs){
					$s .= ''.($k+1).'、['.$rs['title'].']'.$rs['mess'].'。<br>';
					$s1.= ''.($k+1).'、['.$rs['title'].']'.$rs['mess'].'。'."\n";
				}
			}
		}
		$msgar[0] 		= $s;
		$msgar[1] 		= $s1;
		$arr['msgar']	= $msgar; //右下角提示
		
		
		
		
		//桌面项的数据和红数字统计
		$itemsarr		= m('homeitems')->getitemsdata($this->get('nums'));
		foreach($itemsarr as $k=>$v)$arr[$k] = $v;
		
		$arr['notodo']	= $this->option->getval('gerennotodo_'.$uid.'');
		
		$arr['editpass']= m('admin')->iseditpass($uid);
		$arr['miaoshu'] = (int)$this->option->getval('syshometime', '200');
		$arr['tanwidth']= $this->option->getval('tanwidth', '900x800');
		
		return $arr;
	}
	
	
	//显示手机版二维码
	public function getqrcoresAjax()
	{
		if(!function_exists('ImageCreate')){
			echo ''.URL.'?d=we';
		}else{
			echo 'ok';
		}
	}
	public function getqrcodeAjax()
	{
		header("Content-type:image/png");
		$urls= $this->rock->getouturl();
		c('cache')->set('login'.$this->adminuser.'', $this->adminid, 300);
		$url = ''.$urls.'?m=login&d=we&token='.$this->admintoken.'&user='.$this->jm->base64encode($this->adminuser).'';
		if(COMPANYNUM)$url.='&dwnum='.COMPANYNUM.'';
		$img = c('qrcode')->show($url);
		echo $img;
	}
}